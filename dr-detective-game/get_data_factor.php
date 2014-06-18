<?php


/*$text = 'Approximately 3 weeks earlier, a sharp pain in her chest had developed, associated with shortness of breath, while she was walking home from school, a distance of a quarter to a half mile. Thereafter, she had dyspnea with exertion. Her respirations became audible on both inspiration and expiration during exercise but were normal at rest and during sleep.';
$diagnosis = 'glomus tumor of the trachea';*/

// get text from the DB

//echo $_POST["domains"];


$domains = "";
if (!isset($_COOKIE["domains"])) {
	if (!isset($_POST["domains"])) {
		header('Location: game.php') ;
		exit();
	}
	else {
		$domains = $_POST["domains"];
		setcookie ("domains", $domains, time() + 3600);
	}
}
else {
	$domains = $_COOKIE["domains"];
}

$consecutive = 0;
if (isset($_COOKIE["consecutive"])) {
	$consecutive = intval($_COOKIE["consecutive"]);
}

$level = "";
$levelVerb = "";
if (!isset($_POST["level"])) {
	if (!isset($_COOKIE["level"])) {
		header('Location: game.php') ;
		exit();
	}
	else {
		$l = $_COOKIE["level"];
		if (strcmp($l, "1") == 0) {
			$levelVerb = "Quick";
			$level = "1";
		}
		else if (strcmp($l, "3") == 0) {
			$level = "3";
			$levelVerb = "Normal";
		} else {
			$levelVerb = "Hard";
			$level = "5";
		}
	}
}
else {
	$l = $_POST["level"];
	
		if (strcmp($l, "quick") == 0) {
			setcookie ("level", "1", time() + 3600);	
			$levelVerb = "Quick";
			$level = "1";
		}
		else if (strcmp($l, "normal") == 0) {
			setcookie ("level", "3", time() + 3600);	
			$levelVerb = "Normal";
			$level = "3";
		} else {
			setcookie ("level", "5", time() + 3600);	
			$levelVerb = "Hard";
			$level = "5";
		}
}


$condition = "";

if ($exp_group == 1) {
	$condition = "AND game_doc_par.ID < 11";
}
else if ($exp_group == 2) {
	$condition = "AND game_doc_par.ID > 10";
}

$query = "SELECT
		DISTINCT(game_doc_par.ID), Doc_ID, Doc_order, Readability,
		game_doc_par.Sen_count, game_doc_par.Word_count, game_doc_par.Sen_avg_len
		FROM game_doc_par
		INNER JOIN game_doc ON game_doc_par.Doc_ID = game_doc.ID
		WHERE NOT EXISTS (
		SELECT * FROM game_output_factors WHERE
  	game_output_factors.Par_ID = game_doc_par.ID AND
  	game_output_factors.Worker_ID = '$wid' AND
  	game_output_factors.Worker_role = '$worker_role' AND
  	game_output_factors.Factors_count > -2) AND
  	game_doc.Domains LIKE '%$domains%'
  	$condition";
//echo $query;
//$res = mysqli_query($con, $query);

$text = "";
$diagnosis = "";

//$pid = -1;

if (isset($_COOKIE["LastPID"])) {
	$pid = intval($_COOKIE["LastPID"]);
}
else {
	$pid = -1;
}

//echo "Last PID: ".$pid."\n";
//echo "Level: ".$level."\n";

$pid = get_next_paragraph($pid, $wid, $fac_or_rel, $worker_role, $query, $level,  $con);

setcookie ("LastPID", $pid, time() + 3600);

/*if ($pid == -1) {
	mysqli_close($con);
	header('Location: game.php') ;
	exit();
}*/

$query = "SELECT
	Doc_ID, Doc_order, Readability, Sen_count, Word_count, Sen_avg_len FROM game_doc_par
	WHERE ID = $pid";
//echo $query."\n\n";	
$res = mysqli_query($con, $query);
if ($row = mysqli_fetch_array($res)) {
	$doc_id = $row["Doc_ID"];
	$doc_order = $row["Doc_order"];
	$text = get_par_from_order($doc_id, $doc_order, $con);
	
	$query = "SELECT Diagnosis FROM game_doc WHERE ID = $doc_id";
	//echo $query;
	$d = mysqli_query($con, $query);
	if ($r = mysqli_fetch_array($d)) {
		$diagnosis = $r["Diagnosis"];
	}
}

$words_ann_other = array();
$words_ann_other_v = array();
$total_answers = array();
foreach ($av_rel_roles as $rr) {
	$words_ann_other[$rr] = array();
	$words_ann_other_v[$rr] = array();
	$total_answers[$rr] = array();
	
	foreach ($factor_classes as $jtem) {
		foreach ($available_factor_types[$jtem] as $item) {
			$words_ann_other[$rr][$item] = array();
			$words_ann_other_v[$rr][$item] = array();
			$total_answers[$rr][$item] = 0;
			//echo $item."\n";
		}
	}
}

// get results of other users for the same paragraph
$query = "SELECT Factor_class, Factor_type, Factors_count, Factors_list, Relation_role FROM game_output_factors WHERE
	Par_ID = $pid AND
	Worker_role = 'annotate' AND
	Worker_ID <> '$wid'";
$ann_other = mysqli_query($con, $query);
$index = 0;
$factor_identity = array();

while ($row = mysqli_fetch_array($ann_other)) {
	$res = $row['Factors_list'];
	$rr = $row['Relation_role'];
	//echo $row['Factors_list'];
	
	if (isset($total_answers[$rr][$row['Factor_type']])) {
		$total_answers[$rr][$row['Factor_type']] += 1;
		//echo $total_answers[$row['Factor_type']]."\n";
	}
	else {
		$total_answers[$rr][$row['Factor_type']] = 1;
	}
	
	$tok_lines = explode("\n", $res);
	foreach ($tok_lines as $line) {
		//echo $line."\n";
		$tok_row = explode(",", $line);
		
		$i = 0;
		$word_list = array();
		$factor_exists = false;
		foreach ($tok_row as $word) {
			// find duplicate factors
			if ($i == 0) {
				if (isset($factor_identity[$rr][$row['Factor_type']][$word])) {
					$factor_exists = true;
					$factor_identity[$rr][$row['Factor_type']][$word]++;
				} else {
					$factor_identity[$rr][$row['Factor_type']][$word] = 1;
				}
			}
			else if ($i > 1) {
				//echo $tok_row.",";
				//echo $row['Factor_type'];
				$word_list[] = intval($word);
			}
			$i = $i + 1;
		}

		if ($factor_exists == false) {
			$words_ann_other[$rr][$row['Factor_type']][$index++] = $word_list;
		}
	}
}

if (strcmp($worker_role, "validate") == 0) {
	$query = "SELECT Factor_class, Factor_type, Factors_count, Factors_list FROM game_output_factors WHERE
		Par_ID = $pid AND
		Worker_role = 'validate' AND
		Worker_ID <> '$wid' AND
		Relation_role = '$rel_role'";
	$ann_other = mysqli_query($con, $query);
	$index = 0;
	$total_answers = array();
	$factor_identity = array();
	while ($row = mysqli_fetch_array($ann_other)) {
		$res = $row['Factors_list'];
		$rr = $row['Relation_list'];
		//echo $row['Factors_list'];
	
		if (isset($total_answers[$rr][$row['Factor_type']])) {
			$total_answers[$rr][$row['Factor_type']] += 1;
			//echo $total_answers[$row['Factor_type']]."\n";
		}
		else {
			$total_answers[$rr][$row['Factor_type']] = 1;
		}
	
		$tok_lines = explode("\n", $res);
		foreach ($tok_lines as $line) {
			//echo $line."\n";
			$tok_row = explode(",", $line);
		
			$i = 0;
			$word_list = array();
			$factor_exists = false;
			foreach ($tok_row as $word) {
				// find duplicate factors
				if ($i == 0) {
					if (isset($factor_identity[$rr][$row['Factor_type']][$word])) {
						$factor_exists = true;
						$factor_identity[$rr][$row['Factor_type']][$word]++;
					} else {
						$factor_identity[$rr][$row['Factor_type']][$word] = 1;
					}
				}
				else if ($i > 1) {
					$word_list[] = intval($word);
				}
				$i = $i + 1;
			}

			if ($factor_exists == false) {
				$words_ann_other_v[$rr][$row['Factor_type']][$index++] = $word_list;
			}
		}
	}
}
//echo "Results: ".count($words_ann_other["Factors"])."\n";

// get most popular answers
$pop_score = array();
$pop_term = array();
foreach ($av_rel_roles as $rr) {
	$pop_score[$rr] = array();
	$pop_term[$rr] = array();
	
	foreach ($factor_classes as $fc) {
			foreach ($available_factor_types[$fc] as $ft) {
				$pop_score[$rr][$ft] = array();
				$pop_term[$rr][$ft] = array();
				if (isset($factor_identity[$rr][$ft])) {
					foreach ($factor_identity[$rr][$ft] as $key => $val) {
						//$s = $val / $total_answers[$ft];
						//echo $s." ".$key." ".$val." ".$total_answers[$ft]."\n";
						if ($val > 0) {
							$pop_score[$rr][$ft][] = $val;
							$pop_term[$rr][$ft][] = $key;
						}
					}
				}
			}
	}
}

//echo serialize($pop_term)."\n";
?>
