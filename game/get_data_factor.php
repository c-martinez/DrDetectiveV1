<?php


/*$text = 'Approximately 3 weeks earlier, a sharp pain in her chest had developed, associated with shortness of breath, while she was walking home from school, a distance of a quarter to a half mile. Thereafter, she had dyspnea with exertion. Her respirations became audible on both inspiration and expiration during exercise but were normal at rest and during sleep.';
$diagnosis = 'glomus tumor of the trachea';*/

// get text from the DB

include 'Text-Statistics/TextStatistics.php';

function get_par_from_order($doc_id, $doc_order, $con) {
	$query = "SELECT Path FROM game_doc WHERE
	ID = $doc_id";
	$res = mysqli_query($con, $query);
	$statistics = new TextStatistics;
	
	if ($row = mysqli_fetch_array($res)) {
		$file_handle = fopen($row['Path'], "r");
    $text = "";
		while (!feof($file_handle)) {
		 $line = fgets($file_handle);
			 //echo $line;	
			 $text = $text.$line;
		}
		fclose($file_handle);
		
		$xml = simplexml_load_string($text);
		$par_no = 0;
		$par_text = "";
		$result = $xml->xpath("//p");
		//print_r($result);
		while(list( , $node) = each($result)) {
			if ($statistics->word_count($node)) {
				if ($par_no == $doc_order) {
					$par_text = $node;
				}
				$par_no++;
			}
		}
	}
	return $par_text;
}

$query = "SELECT
	DISTINCT(game_doc_par.ID), Doc_ID, Doc_order, Readability, Sen_count, Word_count, Sen_avg_len FROM game_doc_par
	WHERE NOT EXISTS (
	SELECT * FROM game_output_factors WHERE
  game_output_factors.Par_ID = game_doc_par.ID AND
  game_output_factors.Worker_ID = '$wid')";
//echo $query;
$res = mysqli_query($con, $query);

// add next document selection mechanism

$text = "";
$diagnosis = "";
$pid = -1;

if ($row = mysqli_fetch_array($res)) {
	$pid = $row['ID'];
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
foreach ($factor_classes as $jtem) {
	foreach ($available_factor_types[$jtem] as $item) {
		$words_ann_other[$item] = array();
		$words_ann_other_v[$item] = array();
		//echo $item."\n";
	}
}

// get results of other users for the same paragraph
$query = "SELECT Factor_class, Factor_type, Factors_count, Factors_list FROM game_output_factors WHERE
	Par_ID = $pid AND
	Worker_role = 'annotate' AND
	Worker_ID <> '$wid' AND
	Relation_role = '$rel_role'";
$ann_other = mysqli_query($con, $query);
$index = 0;
$total_answers = array();
$factor_identity = array();

while ($row = mysqli_fetch_array($ann_other)) {
	$res = $row['Factors_list'];
	//echo $row['Factors_list'];
	
	if (isset($total_answers[$row['Factor_type']])) {
		$total_answers[$row['Factor_type']] += 1;
		//echo $total_answers[$row['Factor_type']]."\n";
	}
	else {
		$total_answers[$row['Factor_type']] = 1;
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
				if (isset($factor_identity[$row['Factor_type']][$word])) {
					$factor_exists = true;
					$factor_identity[$row['Factor_type']][$word]++;
				} else {
					$factor_identity[$row['Factor_type']][$word] = 1;
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
			$words_ann_other[$row['Factor_type']][$index++] = $word_list;
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
		//echo $row['Factors_list'];
	
		if (isset($total_answers[$row['Factor_type']])) {
			$total_answers[$row['Factor_type']] += 1;
			//echo $total_answers[$row['Factor_type']]."\n";
		}
		else {
			$total_answers[$row['Factor_type']] = 1;
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
					if (isset($factor_identity[$row['Factor_type']][$word])) {
						$factor_exists = true;
						$factor_identity[$row['Factor_type']][$word]++;
					} else {
						$factor_identity[$row['Factor_type']][$word] = 1;
					}
				}
				else if ($i > 1) {
					$word_list[] = intval($word);
				}
				$i = $i + 1;
			}

			if ($factor_exists == false) {
				$words_ann_other_v[$row['Factor_type']][$index++] = $word_list;
			}
		}
	}
}
//echo "Results: ".count($words_ann_other["Factors"])."\n";

// get most popular answers
$pop_score = array();
$pop_term = array();
foreach ($factor_classes as $fc) {
	foreach ($available_factor_types[$fc] as $ft) {
		if (isset($factor_identity[$ft])) {
			$pop_score[$ft] = array();
			$pop_term[$ft] = array();
			foreach ($factor_identity[$ft] as $key => $val) {
				$s = $val / $total_answers[$ft];
				//echo $s." ".$key." ".$val." ".$total_answers[$ft]."\n";
				if ($s >= 0.5) {
					$pop_score[$ft][] = $s;
					$pop_term[$ft][] = $key;
				}
			}
		}
	}
}

?>
