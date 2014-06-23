<?php

$user_domain_difficulty_scores = array();
$user_avg_domain_difficulty = array();

function feature_difficulty($feature_name, $feature_value, $con) {
	$query = "SELECT AVG($feature_name) FROM game_doc_par";
	$res = mysqli_query($con, $query);
	if ($row = mysqli_fetch_array($res)) {
		$avg_value = $row["AVG($feature_name)"];
		if ($avg_value >= $feature_value) {
			return 0;
		}
		else {
			return 1;
		}
	}
	return 0;
}

function get_domains($dom) {
	$domains = array();
	$tok_dom = explode(",", $dom);
	foreach ($tok_dom as $d) {
		$domains[] = $d;
	}
	return $domains;
}

function get_domain_difficulty($par_id, $user_id, $con) {
	if (isset($user_domain_difficulty_scores[$user_id][$par_id])) {
		return $user_domain_difficulty_scores[$user_id][$par_id];
	}
	
	$query = "SELECT Domains FROM game_user WHERE
		Name = '$user_id'";
	$res = mysqli_query($con, $query);	
	$user_domains = array();
	if ($row = mysqli_fetch_array($res)) {
		$user_domains = get_domains($row['Domains']);
	}
	
	$query = "SELECT Domains FROM game_doc, game_doc_par WHERE
		game_doc_par.ID = $par_id AND
		game_doc_par.Doc_ID = game_doc.ID";
	$res = mysqli_query($con, $query);
	$diff = 0;
	if ($row = mysqli_fetch_array($res)) {
		$doc_domains = get_domains($row["Domains"]);
		foreach ($doc_domains as $dd) {
			if (in_array($dd, $user_domains) == false) {
				$diff++;
			}
		}
	}
	
	$user_domain_difficulty_scores[$user_id][$par_id] = $diff;
	
	return $diff;
}

function average_domain_difficulty($user_id, $con) {
	if (isset($user_avg_domain_difficulty[$user_id])) {
		return $user_avg_domain_difficulty[$user_id];
	}
	$query = "SELECT ID FROM game_doc_par";
	$res = mysqli_query($con, $query);
	$all_diffs = array();
	while ($row = mysqli_fetch_array($res)) {
		$par_id = $row["ID"];
		$all_diffs[] = get_domain_difficulty($par_id, $user_id, $con);	
	}
	
	$user_avg_domain_difficulty[$user_id] = array_sum($all_diffs) / count($all_diffs);
	
	return array_sum($all_diffs) / count($all_diffs);
}

function domain_difficulty($user_id, $par_id, $con) {
	$diff = get_domain_difficulty($par_id, $user_id, $con);
	$avg_diff = average_domain_difficulty($user_id, $con);
	
	if ($diff <= $avg_diff) {
		return 0;
	}
	return 1;
}

function paragraph_difficulty($user_id, $par_id, $con) {
	$query = "SELECT Readability, Sen_count, Word_count, Sen_avg_len, UMLS
		FROM game_doc_par
		WHERE ID = $par_id";
	$res = mysqli_query($con, $query);
	$diff = array();
	
	if ($row = mysqli_fetch_array($res)) {
		$val = $row['Readability'];
		$d = feature_difficulty("Readability", $val, $con);
		$diff[] = $d;
		
		$val = $row['Sen_count'];
		$d = feature_difficulty("Sen_count", $val, $con);
		$diff[] = $d;
		
		$val = $row['Word_count'];
		$d = feature_difficulty("Word_count", $val, $con);
		$diff[] = $d;
		
		$val = $row['Sen_avg_len'];
		$d = feature_difficulty("Sen_avg_len", $val, $con);
		$diff[] = $d;
		
		$val = $row['UMLS'];
		$d = feature_difficulty("UMLS", $val, $con);
		$diff[] = $d;
		
		/*$d = domain_difficulty($user_id, $par_id, $con);
		$diff[] = $d;*/
	}
	
	return $diff;
}

function difficulty_difference($d1, $d2) {
	$score = 0;
	for ($i = 0; $i < count($d1); $i++) {
		if ($d1[$i] != $d2[$i]) $score++;
	}
	return $score;
}

/**
  * how many solutions exist for this paragraph, task combination
  */
function solved_score($par_id, $fac_or_rel, $worker_role, $con) {
	$query = "SELECT COUNT(ID) FROM game_simple_output_$fac_or_rel WHERE
		Worker_role = '$worker_role' AND
		Par_ID = $par_id";
	$score = 0;
	$res = mysqli_query($con, $query);
	if ($row = mysqli_fetch_array($res)) {
		$score = $row["COUNT(ID)"];
	}
	return $score;
}

/*echo get_domain_difficulty(157, 'anca', $con)."\n";
echo average_domain_difficulty('anca', $con)."\n";*/

function get_next_paragraph($pid, $uid, $fac_or_rel, $worker_role, $query, $level, $con) {

	$this_par_diff = array();
	if ($pid > -1) {
		$this_par_diff = paragraph_difficulty($uid, $pid, $con);
	}
	else {
		$this_par_diff = array(0, 0, 0, 0, 0);
	}

	// echo "$pid: ";
	foreach ($this_par_diff as $d) echo "$d, ";
	// echo "\n\n";


	$pars = array();
	$len = 0;
	$min_diff = 10;

	$res = mysqli_query($con, $query);
	while ($row = mysqli_fetch_array($res)) {
		$par_id = $row["ID"];
		$pd = paragraph_difficulty($uid, $par_id, $con);
		//echo "SUM: ".array_sum($pd)."\n\n"; 
		if (array_sum($pd) <= intval($level) &&
				array_sum($pd) >= intval($level) - 1 &&
			array_sum($pd) >= array_sum($this_par_diff)) {
			// echo "$par_id: ";
			foreach ($pd as $d) echo "$d, ";
		
			$pars[$len]["pid"] = $par_id;
			$pars[$len]["diff"] = difficulty_difference($pd, $this_par_diff);
		
			$min_diff = min($min_diff, $pars[$len]["diff"]);
		
			 //echo "difference: ".$pars[$len]["diff"];
		
			$len++;
			// echo "\n";
		}
	}

	//echo $min_diff;

	$next_pid = -1;
	$next_pid_score = -1;
	for ($i = 0; $i < $len; $i++) {
		if ($pars[$i]["diff"] == $min_diff) {
			$pid_score = solved_score($pars[$i]["pid"], $fac_or_rel, $worker_role, $con);
			if ($pid_score > $next_pid_score) {
				$next_pid_score = $pid_score;
				$next_pid = $pars[$i]["pid"];
			}
		}
	}

	//echo $next_pid." ".$next_pid_score;

	return $next_pid;
}

function par_diff_score($par_id, $user_id, $con) {
	return array_sum(paragraph_difficulty($user_id, $par_id, $con));
}


/*
$pid = 130;
$uid = 'anca';
$fac_or_rel = 'factors';
$worker_role = 'annotate';

echo get_next_paragraph(170, 'anca', 'factors', 'annotate', $con);

*/


function count_paragraphs_per_domain($uid, $domains, $con, $exp_group) {
		$condition = "";
		if ($exp_group == 2) {
			$condition = "AND game_doc_par.ID < 11";
		}
		else if ($exp_group == 1) {
			$condition = "AND game_doc_par.ID > 10";
		}
		
	$query = "SELECT DISTINCT(game_doc_par.ID) FROM game_doc_par
		INNER JOIN game_doc ON game_doc_par.Doc_ID = game_doc.ID
		WHERE game_doc.Domains LIKE '%$domains%'
  	$condition";
	//echo $query;
	$res = mysqli_query($con, $query);
	
	$count = 0;
	while ($row = mysqli_fetch_array($res)) {
		$pid = $row["ID"];
		$count++;
	}

	return $count;
}

function count_solved_paragraphs_per_domain($uid, $domains, $con, $exp_group) {
		$condition = "";
		if ($exp_group == 2) {
			$condition = "AND game_doc_par.ID < 11";
		}
		else if ($exp_group == 1) {
			$condition = "AND game_doc_par.ID > 10";
		}
		
	$query = "SELECT DISTINCT(game_doc_par.ID) FROM game_doc_par
		INNER JOIN game_doc ON game_doc_par.Doc_ID = game_doc.ID
		INNER JOIN game_simple_output_factors ON game_doc_par.ID = game_simple_output_factors.Par_ID
		WHERE game_doc.Domains LIKE '%$domains%' AND 
  	game_simple_output_factors.Worker_ID = '$uid' AND
  	game_simple_output_factors.Factors_count > -2
  	$condition";
	//echo $query;
	$res = mysqli_query($con, $query);
	
	$count = 0;
	while ($row = mysqli_fetch_array($res)) {
		$pid = $row["ID"];
		$count++;
	}

	return $count;
}


?>

