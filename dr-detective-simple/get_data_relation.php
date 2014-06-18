<?php


/*$text = 'Approximately 3 weeks earlier, a sharp pain in her chest had developed, associated with shortness of breath, while she was walking home from school, a distance of a quarter to a half mile. Thereafter, she had dyspnea with exertion. Her respirations became audible on both inspiration and expiration during exercise but were normal at rest and during sleep.';
$diagnosis = 'glomus tumor of the trachea';*/

// get text from the DB


$query = "SELECT
	DISTINCT(game_doc_par_rel.Par_ID), game_doc_par_rel.Term1, game_doc_par_rel.Term2, game_doc_par_rel.ID, Doc_ID, Doc_order, Readability, Sen_count, Word_count, Sen_avg_len FROM game_doc_par_rel
  INNER JOIN game_doc_par
  ON game_doc_par.ID = game_doc_par_rel.Par_ID
  WHERE NOT EXISTS (
  SELECT game_output_relations.Par_ID FROM game_output_relations
  WHERE game_output_relations.Worker_ID = '$wid' AND
  game_output_relations.Par_ID = game_doc_par_rel.Par_ID AND
  game_output_relations.Worker_role = '$worker_role')";
//echo $query;
$res = mysqli_query($con, $query);

// add next document selection mechanism

$text = "";
$diagnosis = "";
$pid = -1;
$term1 = "";
$term2 = "";

if ($row = mysqli_fetch_array($res)) {
	$pid = $row['Par_ID'];
	$doc_id = $row["Doc_ID"];
	$doc_order = $row["Doc_order"];
	$term1 = $row["Term1"];
	$term2 = $row["Term2"];
	
	$text = get_par_from_order($doc_id, $doc_order, $con);
	
	$query = "SELECT Diagnosis FROM game_doc WHERE ID = $doc_id";
	//echo $query;
	$d = mysqli_query($con, $query);
	if ($r = mysqli_fetch_array($d)) {
		$diagnosis = $r["Diagnosis"];
	}
}

$words_ann_other = array();
foreach ($relations as $item) {
	$words_ann_other_a[$item] = 0;
	$words_ann_other_v[$item] = 0;
}

// get results of other users for the same paragraph
$query = "SELECT Relations_count, Relations_list FROM game_output_relations WHERE
	Par_ID = $pid AND
	Worker_role = 'annotate' AND
	Worker_ID <> '$wid' AND
	Term1 = '$term1' AND
	Term2 = '$term2'";
//echo $query;
$ann_other = mysqli_query($con, $query);
$total_answers_a = 0;
while ($row = mysqli_fetch_array($ann_other)) {
	$total_answers_a++;
	
	$lines = explode("\n", $row["Relations_list"]);
	foreach ($lines as $line) {
		$rel = explode(",", $line);
		$i = 0;
		foreach ($rel as $r) {
			//echo $line."\n";
			if ($i > 0) $words_ann_other_a[$r]++;
			$i++;
		}
	}
}

$query = "SELECT Relations_count, Relations_list FROM game_output_relations WHERE
	Par_ID = $pid AND
	Worker_role = 'validate' AND
	Worker_ID <> '$wid' AND
	Term1 = '$term1' AND
	Term2 = '$term2'";
$ann_other = mysqli_query($con, $query);
$total_answers_v = 0;
while ($row = mysqli_fetch_array($ann_other)) {
	$total_answers_v++;
	
	$lines = explode("\n", $row["Relations_list"]);
	foreach ($lines as $line) {
		$rel = explode(",", $line);
		$i = 0;
		foreach ($rel as $r) {
			//echo $line."\n";
			if ($i > 0) $words_ann_other_v[$r]++;
			$i++;
		}
	}
}

//echo "Results: ".count($words_ann_other["Factors"])."\n";

// get most popular answers
$pop_score = array();
$pop_rel = array();
foreach ($relations as $rel) {
	if (strcmp($worker_role, "annotate") == 0 and $total_answers_a > 0) {
		//$s = $words_ann_other_a[$rel] / $total_answers_a;
			if ($words_ann_other_a[$rel] > 0) {
				$pop_score[] = $words_ann_other_a[$rel];
				$pop_rel[] = $rel;
			}
	}
	else if (strcmp($worker_role, "validate") == 0 and $total_answers_v > 0) {
		//$s = $words_ann_other_v[$rel] / $total_answers_v;
			if ($words_ann_other_v[$rel] > 0) {
				$pop_score[] = $words_ann_other_v[$rel];
				$pop_rel[] = $rel;
			}
	}
}

?>
