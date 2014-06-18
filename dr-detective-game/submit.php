<?php

include "mysql.php";
include "verbose.php";

//echo $_COOKIE["Username"];

$url = $_POST["nextURL"];

if (isset($_COOKIE["Username"])) {
	$wid = $_COOKIE["Username"];
} else {
	mysqli_close($con);
	header('Location: login.php') ;
	exit();
}

$consecutive = 0;
if (isset($_COOKIE["consecutive"])) {
	$consecutive = intval($_COOKIE["consecutive"]);
}

function parse_existing_factors($pid, $wid, $con) {
	$query = "SELECT ID, Worker_ID
		FROM game_output_saved_factors
		WHERE Status = 'pending' AND
		Par_ID = $pid AND
		Worker_ID <> '$wid'";
	$res = 	mysqli_query($con, $query);
	while ($row = mysqli_fetch_array($res)) {
		$id = $row["ID"];
		$wid = $row["Worker_ID"];
		
		$query = "UPDATE game_user
			SET Score = Score - 1,
			Points_lost = Points_lost + 1
			WHERE Name = '$wid'";
		mysqli_query($con, $query);
		
		$query = "UPDATE game_output_saved_factors
			SET Status = 'substracted'
			WHERE ID = $id";
		mysqli_query($con, $query);
	}
}

function get_factor_status($wid, $pid, $factor, $con) {
	$query = "SELECT Status
		FROM game_output_saved_factors
		WHERE Worker_ID = '$wid' AND
		Par_ID = $pid AND
		Factor = '$factor'";
	$res = 	mysqli_query($con, $query);
	$status = "";
	if ($row = mysqli_fetch_array($res)) {
		$status = $row["Status"];
	}
	return $status;
}

function update_factor_status($wid, $pid, $factor, $con) {
	$query = "DELETE FROM game_output_saved_factors
		WHERE Worker_ID = '$wid' AND
		Par_ID = $pid AND
		Factor = '$factor'";
	mysqli_query($con, $query);
}

function get_term_from_word($text, $word) {
	$tok_lines = explode("\n", $text);
	
	foreach ($tok_lines as $line) {
		//echo $line."\n";
		$tok_row = explode(",", $line);
		
		$i = 0;
		$word_list = array();
		$factor_exists = false;
		foreach ($tok_row as $w) {
			if ($i > 1) {
				if ($word == intval($w)) {
					return $line;
				}
			}
			$i = $i + 1;
		}
	}
	return "";
}

function word_count($text) {
	$tok_line = explode(",", $text);
	if (count($tok_line) > 2) {
		return count($tok_line) - 2;
	}
	return 0;
}

function word_intersection_count($text1, $text2) {
	$tok_line1 = explode(",", $text1);
	$count = 0;
	$i = 0;
	foreach ($tok_line1 as $w1) {
		if ($i > 1) {
			$tok_line2 = explode(",", $text2);
			$j = 0;
			foreach ($tok_line2 as $w2) {
				if ($j > 1) {
					if (strcmp($w1, $w2) == 0) {
						$count++;
					}
				}
				$j++;
			}
		}
		
		$i++;
	}
	
	return $count;
}

function update_submitter_score($pid, $word, $term, $factor_type, $worker_role, $fac_or_rel, $con) {
	if (strcmp($fac_or_rel, "factors") == 0) {
		$query = "SELECT DISTINCT(Worker_ID), Time_create, Factors_list
			FROM game_output_factors WHERE 
			Par_ID = $pid AND
			Factor_type = '$factor_type' AND
			Worker_role = '$worker_role' AND
			(Factors_list LIKE '%,$word,%' OR
			Factors_list LIKE '%,$word\n%' OR
			Factors_list LIKE '%,$word')
			ORDER BY Time_create ASC";
		//echo "$query\n\n";
		$res = 	mysqli_query($con, $query);
		$found = 0;
		while ($row = mysqli_fetch_array($res)) {
			$prev_answer = $row["Factors_list"];
			$userid = $row["Worker_ID"];
			
			$prev_term = get_term_from_word($prev_answer, $word);
			$wc = word_count($prev_term);
			$updated_score = 0;
			
			if ($found == 0) {
			
				if (strcmp(get_factor_status($userid, $pid, $prev_term, $con), "substracted") == 0) {
					$updated_score += 1;
				
					$query = "UPDATE game_user
						Points_lost = Points_lost - 1
						WHERE Name = '$userid'";
					mysqli_query($con, $query);
				}
				update_factor_status($userid, $pid, $prev_term, $con);
			
				if ($wc > 0) $updated_score += 1 / word_count($prev_term);
			
				$query = "UPDATE game_user
					SET Score = Score + $updated_score,
					Points_gained = Points_gained + $updated_score
					WHERE Name = '$userid'";
				//echo "$query\n\n";
				mysqli_query($con, $query);
				$found++;
			}
		}
		
		return $found / word_count($term);
	}
	else {
		$query = "SELECT DISTINCT(Worker_ID), Time_start
			FROM game_output_relations WHERE 
			Par_ID = $pid AND
			Worker_role = '$worker_role' AND
			Relations_list LIKE '%$answer%'
			ORDER BY Time_create ASC";
		$res = 	mysqli_query($con, $query);
		if ($row = mysqli_fetch_array($res)) {
			$userid = $row["Worker_ID"];
			$query = "UPDATE game_user
				SET Score = Score + 1
				WHERE Name = '$userid'";
			mysqli_query($con, $query);
		}
	}
	return 0;
}


if (isset($_POST['time_start'])) {

	//echo "TIME START: ".$_POST['time_start']."\n\n\n";
	$score = $_POST['score'];
	$pid = $_POST['pid'];
	$time_start = $_POST['time_start'];
	$time_create = $_POST['time_create'];
	$worker_role = $_POST['worker_role'];
	
	$results = json_decode($_POST['results'], true);

	if (strcmp($_POST['fac_rel'], "factors") == 0 ) {
		$relation_role = $_POST['relation_role'];
	
		//echo $_POST['results'];
		
		$found_answers = false;
		
		foreach ($factor_classes as $fc) {
			foreach ($available_factor_types[$fc] as $ft) {
				if (isset($results[$ft])) {
					if ($found_answers == false) {
						parse_existing_factors($pid, $wid, $con);
						$found_answers = true;
					}
				
					$time_start_f = $results[$ft]["timeStart"];
					$time_create_f = $results[$ft]["timeCreate"];
					$count_f = count($results[$ft]["factorExpl"]);
					
					$factor_csv = "";
					$term_score = 0;
					
					for ($i = 0; $i < $count_f; $i++) {
						if ($i > 0) $factor_csv = $factor_csv."\n";
					
						$words_csv = "";
						for ($j = 0; $j < count($results[$ft]["factorPos"][$i]); $j++) {
							if ($j > 0) $words_csv = $words_csv.",";
							$words_csv = $words_csv.$results[$ft]["factorPos"][$i][$j];
						}
						$words_csv = "\"".$results[$ft]["factorExpl"][$i]."\",".$results[$ft]["factorTimes"][$i].",".$words_csv;
						
						$int_score = 0;
						for ($j = 0; $j < count($results[$ft]["factorPos"][$i]); $j++) {
							$int_score += update_submitter_score($pid,
								$results[$ft]["factorPos"][$i][$j],
								$words_csv,
								$ft, $worker_role, $fac_or_rel, $con);
						}
						
						if ($int_score == 0) {						
							$query = "INSERT INTO game_output_saved_factors
								(Worker_ID, Par_ID, Factor, Status)
								VALUES ('$wid', $pid, '$words_csv', 'pending')";
							mysqli_query($con, $query);
						}
						
						$term_score += $int_score;
						
						$factor_csv = $factor_csv.$words_csv;
					}
					
					//echo $factor_csv."\n\n";
					
					$bt = $results[$ft]["otherButtonToggle"];
					$query = "INSERT INTO game_output_factors
						(Par_ID, Job_ID, Worker_ID, Worker_role, Factor_class, Factor_type, Factors_count, Factors_list, Time_start, Time_create, Relation_role, Button_toggle)
						VALUES ($pid, $jid,'$wid','$worker_role','$fc','$ft','$count_f','$factor_csv', '$time_start_f', '$time_create_f', '$relation_role', '$bt')";
					mysqli_query($con, $query);
					//echo $query;
					
					$query = "UPDATE game_user SET Last_Par_ID = $pid WHERE
						Name = '$wid' AND
						Worker_role = '$worker_role' AND
						Task = '$fac_or_rel'";
					mysqli_query($con, $query);
					
					$new_score = $score * ($term_score + $consecutive);
					$query = "UPDATE game_user SET
						Score = $score,
						Points_gained = Points_gained + $term_score
						WHERE Name = '$wid'";
					mysqli_query($con, $query);
					
					//echo $query;
					
					$found_answers = true;
				}
			}
		}
		
		if ($found_answers == false) {
					$query = "INSERT INTO
						game_output_factors (Par_ID, Job_ID, Worker_ID, Worker_role,Time_start, Time_create, Relation_role, Factors_count)
						VALUES ($pid, $jid,'$wid','$worker_role','$time_start', '$time_create', '$relation_role', -1)";
					mysqli_query($con, $query);
				}
	}
	else {
		$term1 = $_POST["term1"];
		$term2 = $_POST["term2"];
		
		if (isset($results)) {
			$time_start_f = $results["timeStart"];
			$time_create_f = $results["timeCreate"];
			
			$count_f = count($results["relations"]);
			$rel_csv = "";
			for ($i = 0; $i < $count_f; $i++) {
				if ($i > 0) $rel_csv = $rel_csv."\n";
				$rel_csv = $rel_csv.$results["relationTimes"][$i].",".$results["relations"][$i];
				
				//update_submitter_score($pid, $results["relations"][$i], '', $worker_role, $fac_or_rel, $con);
			}
			$query = "INSERT INTO game_output_relations
				(Par_ID, Job_ID, Worker_ID, Worker_role, Time_start, Time_create, Relations_count, Relations_list, Term1, Term2)
				VALUES ($pid, $jid, '$wid', '$worker_role', '$time_start_f', '$time_create_f', $count_f, '$rel_csv', \"$term1\",\"$term2\")";
			//echo $query;
			mysqli_query($con, $query);
			
			// update last paragraph
			$query = "UPDATE game_user SET Last_Par_ID = $pid WHERE
				Name = '$wid' AND
				Worker_role = '$worker_role' AND
				Task = '$fac_or_rel'";
			mysqli_query($con, $query);
			
			// update score
			$query = "UPDATE game_user SET Score = $score WHERE
				Name = '$wid'";
			mysqli_query($con, $query);
		}
		else {
			$query = "INSERT INTO game_output_relations
				(Par_ID, Job_ID, Worker_ID, Worker_role, Time_start, Time_create, Term1, Term2, Relations_count)
				VALUES ($pid, $jid, '$wid', '$worker_role', '$time_start', '$time_create', \"$term1\",\"$term2\", -1)";
			//echo $query;
			mysqli_query($con, $query);
		}
	}
}

$consecutive++;
setcookie ("consecutive", $consecutive, time() + 3600);

//echo 'Location: test.php?user='.$wid.'&task='.$fac_or_rel.'&role='.$worker_role;

//echo $url;

if (strcmp($url,"submit") == 0) {
	mysqli_close($con);
	header('Location: test.php?task='.$fac_or_rel.'&role='.$worker_role) ;
}
else {
	$query = "UPDATE game_output_$fac_or_rel
		SET ".$fac_or_rel."_count = -2
		WHERE ". $fac_or_rel."_count = -1
		AND	Worker_ID = '$wid'";
	//echo $query;
	mysqli_query($con, $query);

	setcookie ("lastPID", "", time() - 3600);

	mysqli_close($con);
	header('Location: '.$url.'.php') ;
}

exit();

?>
