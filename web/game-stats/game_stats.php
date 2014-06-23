<?php

include "mysql.php";

function check_if_exists($arr_item, $multi_arr, $iter) {
	for ($i = 0; $i <= $iter; $i++) {
		if (in_array($arr_item, $multi_arr[$i])) return true;
	}
	return false;
}

// get top (user-par) pairings
$query = "SELECT Worker_ID, Par_ID, COUNT(*) AS Count_no
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	GROUP BY Worker_ID, Par_ID";
$game_users = array();
$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	if (array_key_exists($row["Par_ID"], $game_users) == false) {
		$game_users[$row["Par_ID"]] = 1;
	}
	else {
		$game_users[$row["Par_ID"]]++;
	}
}

$query = "SELECT Worker_ID, Par_ID, COUNT(*) AS Count_no
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	GROUP BY Worker_ID, Par_ID";
$simple_users = array();
$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	if (array_key_exists($row["Par_ID"], $simple_users) == false) {
		$simple_users[$row["Par_ID"]] = 1;
	}
	else {
		$simple_users[$row["Par_ID"]]++;
	}
}

$query = "SELECT COUNT(DISTINCT Worker_ID)
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1";

$nr_players_game = "<p>Users who played the game version: ";
$res = mysqli_query($con, $query);
if ($row = mysqli_fetch_array($res)) {
	$nr_players_game .= $row["COUNT(DISTINCT Worker_ID)"] ."</p>";
}
else {
	$nr_players_game .= "0</p>";
}

$query = "SELECT COUNT(DISTINCT Worker_ID)
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1";

$nr_players_simple = "<p>Users who played the simple version: ";
$res = mysqli_query($con, $query);
if ($row = mysqli_fetch_array($res)) {
	$nr_players_simple .= $row["COUNT(DISTINCT Worker_ID)"] ."</p>";
}
else {
	$nr_players_simple .= "0</p>";
}

$query = "SELECT COUNT(DISTINCT game_simple_output_factors.Worker_ID)
	FROM game_simple_output_factors
	INNER JOIN game_output_factors
	ON game_simple_output_factors.Worker_ID = game_output_factors.Worker_ID
	WHERE
	game_simple_output_factors.Worker_ID <> 'robot' AND
	game_simple_output_factors.Worker_ID <> 'android' AND
	game_simple_output_factors.Worker_ID <> 'Lora' AND
	game_simple_output_factors.Worker_ID <> 'levas' AND
	game_simple_output_factors.Worker_ID <> 'gsc' AND
	game_simple_output_factors.Worker_ID <> 'Rik' AND
	game_simple_output_factors.Worker_ID <> 'paranoid' AND
	game_simple_output_factors.Worker_ID <> 'oana' AND
	game_simple_output_factors.Worker_ID <> 'aaa' AND
	game_simple_output_factors.Worker_ID <> 'testme' AND
	game_simple_output_factors.Factors_count > -1 AND
	game_output_factors.Factors_count > -1";

$nr_players_total = "<p>Users who played both versions: ";
$res = mysqli_query($con, $query);
if ($row = mysqli_fetch_array($res)) {
	$nr_players_total .= $row["COUNT(DISTINCT game_simple_output_factors.Worker_ID)"] ."</p>";
}
else {
	$nr_players_total .= "0</p>";
}

$query = "SELECT COUNT(*) FROM game_user WHERE Score > 0 AND
	Name <> 'robot' AND
	Name <> 'android' AND
	Name <> 'Lora' AND
	Name <> 'levas' AND
	Name <> 'gsc' AND
	Name <> 'Rik' AND
	Name <> 'paranoid' AND
	Name <> 'aaa' AND
	Name <> 'testme' AND
	Name <> 'oana'";
$nr_players_either = "<p>Users who played either of the versions: ";
$res = mysqli_query($con, $query);
if ($row = mysqli_fetch_array($res)) {
	$nr_players_either .= $row["COUNT(*)"] ."</p>";
}
else {
	$nr_players_either .= "0</p>";
}


$query = "SELECT Factors_list, Par_ID, Factors_count
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
  Factors_count > -1
	ORDER BY Par_ID";
$res = mysqli_query($con, $query);
$pid = -1;
$unique_factors_game = array();
$unique_factors_game_score = array();
while ($row = mysqli_fetch_array($res)) {
	if ($pid != $row["Par_ID"]) {
		$unique_factors_game[$row["Par_ID"]] = array();
		$unique_factors_game_score[$row["Par_ID"]] = array();
	}	
	
	$pid = $row["Par_ID"];
	$fl = $row["Factors_list"];
	$fc = $row["Factors_count"];
	
	$tok_rows = explode("\n", $fl);
	for ($i = 0; $i < $fc; $i++) {
		$tok_words = explode(",", $tok_rows[$i]);
		
		if (in_array($tok_words[0], $unique_factors_game[$pid])) {
			$unique_factors_game_score[$pid][$tok_words[0]]++;
		}
		else {
			$unique_factors_game[$pid][] = $tok_words[0];
			$unique_factors_game_score[$pid][$tok_words[0]] = 1;
			//echo $pid." - ".$tok_words[0]."\n";
		}
	}
}

/*foreach ($unique_factors_game as $par_fac) 
	foreach ($par_fac as $fct)
		echo $fct."\n";*/
		
$query = "SELECT Factors_list, Par_ID, Factors_count
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
  Factors_count > -1
	ORDER BY Par_ID";
$res = mysqli_query($con, $query);
$pid = -1;
$unique_factors_simple = array();
$unique_factors_simple_score = array();
while ($row = mysqli_fetch_array($res)) {
	if ($pid != $row["Par_ID"]) {
		$unique_factors_simple[$row["Par_ID"]] = array();
		$unique_factors_simple_score[$row["Par_ID"]] = array();
	}	
	
	$pid = $row["Par_ID"];
	$fl = $row["Factors_list"];
	$fc = $row["Factors_count"];
	
	$tok_rows = explode("\n", $fl);
	for ($i = 0; $i < $fc; $i++) {
		$tok_words = explode(",", $tok_rows[$i]);
		
		if (in_array($tok_words[0], $unique_factors_simple[$pid])) {
			$unique_factors_simple_score[$pid][$tok_words[0]]++;
		}
		else {
			$unique_factors_simple[$pid][] = $tok_words[0];
			$unique_factors_simple_score[$pid][$tok_words[0]] = 1;
			//echo $pid." - ".$tok_words[0]."\n";
		}
	}
}

$unique_factors_combined = array();
$unique_factors_combined_score = array();
for ($i = 1; $i <= 20; $i++) {
	$unique_factors_combined[$i] = array();
	$unique_factors_combined_score[$i] = array();
	
	if (array_key_exists($i, $unique_factors_game)) {
		foreach ($unique_factors_game[$i] as $w1) {
			$unique_factors_combined[$i][] = $w1;
			$unique_factors_combined_score[$i][$w1] = $unique_factors_game_score[$i][$w1];
		}
	}
	
	if (array_key_exists($i, $unique_factors_simple)) {
		foreach ($unique_factors_simple[$i] as $w1) {
			if (in_array($w1, $unique_factors_combined[$i])) {
				$unique_factors_combined_score[$i][$w1] += $unique_factors_simple_score[$i][$w1];
			}
			else {
				$unique_factors_combined[$i][] = $w1;
				$unique_factors_combined_score[$i][$w1] = $unique_factors_simple_score[$i][$w1];
			}
		}
	}
}

$query= "SELECT Factor_type, Par_ID, COUNT(*) AS Count_no
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > 0
	GROUP BY Factor_type, Par_ID";
$factor_types_game = array();
$factor_types_game_score = array();
$factor_types_game_total = array();
$pid = -1;
$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	$pid = $row["Par_ID"];
	
	if (array_key_exists($pid, $factor_types_game) == false) {
		$factor_types_game[$row["Par_ID"]] = array();
		$factor_types_game_score[$row["Par_ID"]] = array();
		$factor_types_game_total[$row["Par_ID"]] = 0;
	}	
	
	$fl = $row["Factor_type"];
	
	if (in_array($fl, $factor_types_game[$pid])) {
		//$factor_types_game_total[$pid]++;
	}
	else {
		$factor_types_game[$pid][] = $fl;
		$factor_types_game_score[$pid][$fl] = $row["Count_no"];
		$factor_types_game_total[$pid]++;
	}
}

$query= "SELECT Factor_type, Par_ID, COUNT(*) AS Count_no
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > 0
	GROUP BY Factor_type, Par_ID";
$factor_types_simple = array();
$factor_types_simple_score = array();
$factor_types_simple_total = array();
$pid = -1;
$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	$pid = $row["Par_ID"];
	if (array_key_exists($pid, $factor_types_simple) == false) {
		$factor_types_simple[$row["Par_ID"]] = array();
		$factor_types_simple_score[$row["Par_ID"]] = array();
		$factor_types_simple_total[$row["Par_ID"]] = 0;
	}	
	
	$fl = $row["Factor_type"];
	
	if (in_array($fl, $factor_types_simple[$pid])) {
		//$factor_types_simple_total[$pid]++;
	}
	else {
		$factor_types_simple[$pid][] = $fl;
		$factor_types_simple_score[$pid][$fl] = $row["Count_no"];
		$factor_types_simple_total[$pid]++;
	}
}

$factor_types_combined_total = $factor_types_game_total;
$factor_types_combined_score = $factor_types_game_score;
$factor_types_combined = $factor_types_game;

for ($i = 1; $i <= 20; $i++) {
	if (array_key_exists($i, $factor_types_simple)) {
		foreach ($factor_types_simple[$i] as $type) {
			if (array_key_exists($i, $factor_types_combined)) {
				if (in_array($type, $factor_types_combined[$i])) {
					$factor_types_combined_score[$i][$type] += $factor_types_simple_score[$i][$type];
				}
				else {
					$factor_types_combined[$i][] = $type;
					$factor_types_combined_score[$i][$type] = $factor_types_simple_score[$i][$type];
					$factor_types_combined_total[$i]++;
				}
			}
			else {
				$factor_types_combined[$i] = array();
				$factor_types_combined_score[$i] = array();
				$factor_types_combined_total[$i] = 0;
				
				$factor_types_combined[$i][] = $type;
				$factor_types_combined_score[$i][$type] = $factor_types_simple_score[$i][$type];
				$factor_types_combined_total[$i]++;
			}
		}
	}
}

$max_iterations = -1;


$new_types_game = array();
$new_terms_game = array();
$new_words_game = array();

$query= "SELECT *
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	ORDER BY Par_ID, ID, Worker_ID";

$pid = -1;
$wid = "";
$iteration = -1;

$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	if ($pid != $row["Par_ID"]) {
		$pid = $row["Par_ID"];
		$iteration = -1;
		$wid = "";
		
		$new_types_game[$pid] = array();
		$new_terms_game[$pid] = array();
		$new_words_game[$pid] = array();
	}
	
	if (strcmp($wid, $row["Worker_ID"]) != 0) {
		$wid = $row["Worker_ID"];
		$iteration++;
		
		$new_types_game[$pid][$iteration] = array();
		$new_terms_game[$pid][$iteration] = array();
		$new_words_game[$pid][$iteration] = array();
	}
	
	
	if ($row["Factors_count"] > 0) {
		// get new term types for this game iteration
		$this_type = $row["Factor_type"];
		if (check_if_exists($this_type, $new_types_game[$pid], $iteration) == false) {
			$new_types_game[$pid][$iteration][] = $this_type;
		}
	
		$this_list = $row["Factors_list"];
		$tok_rows = explode("\n", $this_list);
		foreach ($tok_rows as $row) {
			$tok_words = explode(",", $row);
			$i = 0;
		
			foreach ($tok_words as $word) {
				// get new terms for this game iteration
				if ($i == 0) {
					if (check_if_exists($word, $new_terms_game[$pid], $iteration) == false) {
						$new_terms_game[$pid][$iteration][] = $word;
					}
				}
				else if ($i > 1) {
					if (check_if_exists($word, $new_words_game[$pid], $iteration) == false) {
						$new_words_game[$pid][$iteration][] = $word;
					}
				}
			
				$i++;
			}
		}
	}
	
	$max_iterations = max($iteration, $max_iterations);
}


$new_types_simple = array();
$new_terms_simple = array();
$new_words_simple = array();

$query= "SELECT *
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	ORDER BY Par_ID, ID, Worker_ID";

$pid = -1;
$wid = "";
$iteration = -1;

$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	if ($pid != $row["Par_ID"]) {
		$pid = $row["Par_ID"];
		$iteration = -1;
		$wid = "";
		
		$new_types_simple[$pid] = array();
		$new_terms_simple[$pid] = array();
		$new_words_simple[$pid] = array();
	}
	
	if (strcmp($wid, $row["Worker_ID"]) != 0) {
		$wid = $row["Worker_ID"];
		$iteration++;
		
		$new_types_simple[$pid][$iteration] = array();
		$new_terms_simple[$pid][$iteration] = array();
		$new_words_simple[$pid][$iteration] = array();
	}
	
	
	if ($row["Factors_count"] > 0) {
	
		// get new term types for this simple iteration
		$this_type = $row["Factor_type"];
		if (check_if_exists($this_type, $new_types_simple[$pid], $iteration) == false) {
			$new_types_simple[$pid][$iteration][] = $this_type;
		}
	
		$this_list = $row["Factors_list"];
		$tok_rows = explode("\n", $this_list);
		foreach ($tok_rows as $row) {
			$tok_words = explode(",", $row);
			$i = 0;
		
			foreach ($tok_words as $word) {
				// get new terms for this simple iteration
				if ($i == 0) {
					if (check_if_exists($word, $new_terms_simple[$pid], $iteration) == false) {
						$new_terms_simple[$pid][$iteration][] = $word;
					}
				}
				else if ($i > 1) {
					if (check_if_exists($word, $new_words_simple[$pid], $iteration) == false) {
						$new_words_simple[$pid][$iteration][] = $word;
					}
				}
			
				$i++;
			}
		}
	}
	
	$max_iterations = max($iteration, $max_iterations);
}

$types_json = array();
$per = array();
for ($i = 1; $i <= 20; $i++) {
	$type_it = array();
	$type_it["paragraph"] = strval($i);
	$per[$i] = array();
	
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (array_key_exists($i, $new_types_game) &&
			array_key_exists($j, $new_types_game[$i])) {
			$type_it["game_it_".$j] = count($new_types_game[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_types_game[$i][$j]);
		}
		else {
			$type_it["game_it_".$j] = 0;
		}
		
		
		if (array_key_exists($i, $new_types_simple) &&
			array_key_exists($j, $new_types_simple[$i])) {
			$type_it["simple_it_".$j] = count($new_types_simple[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_types_simple[$i][$j]);
		}
		else {
			$type_it["simple_it_".$j] = 0;
		}
	}
	
	$types_json[] = $type_it;
}

for ($i = 1; $i <= 20; $i++) {
	$per[$i] = array_unique($per[$i]);
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (count($per[$i]) > 0) {
			$types_json[$i - 1]["game_it_per_".$j] = round(
				$types_json[$i - 1]["game_it_".$j]
				/ count($per[$i]) * 100, 0);
			$types_json[$i - 1]["simple_it_per_".$j] = round(
				$types_json[$i - 1]["simple_it_".$j]
				/ count($per[$i]) * 100, 0);
		}
		else {
			$types_json[$i - 1]["game_it_per_".$j] = 0;
			$types_json[$i - 1]["simple_it_per_".$j] = 0;
		}
	}
}


$words_json = array();
$per = array();
for ($i = 1; $i <= 20; $i++) {
	$type_it = array();
	$type_it["paragraph"] = strval($i);
	$per[$i] = array();
	
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (array_key_exists($i, $new_words_game) &&
			array_key_exists($j, $new_words_game[$i])) {
			$type_it["game_it_".$j] = count($new_words_game[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_words_game[$i][$j]);
		}
		else {
			$type_it["game_it_".$j] = 0;
		}
		
		
		if (array_key_exists($i, $new_words_simple) &&
			array_key_exists($j, $new_words_simple[$i])) {
			$type_it["simple_it_".$j] = count($new_words_simple[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_words_simple[$i][$j]);
		}
		else {
			$type_it["simple_it_".$j] = 0;
		}
	}
	
	$words_json[] = $type_it;
}

for ($i = 1; $i <= 20; $i++) {
	$per[$i] = array_unique($per[$i]);
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (count($per[$i]) > 0) {
			$words_json[$i - 1]["game_it_per_".$j] = round(
				$words_json[$i - 1]["game_it_".$j]
				/ count($per[$i]) * 100, 0);
			$words_json[$i - 1]["simple_it_per_".$j] = round(
				$words_json[$i - 1]["simple_it_".$j]
				/ count($per[$i]) * 100, 0);
		}
		else {
			$words_json[$i - 1]["game_it_per_".$j] = 0;
			$words_json[$i - 1]["simple_it_per_".$j] = 0;
		}
	}
}


$terms_json = array();
$per = array();
for ($i = 1; $i <= 20; $i++) {
	$type_it = array();
	$type_it["paragraph"] = strval($i);
	$per[$i] = array();
	
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (array_key_exists($i, $new_terms_game) &&
			array_key_exists($j, $new_terms_game[$i])) {
			$type_it["game_it_".$j] = count($new_terms_game[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_terms_game[$i][$j]);
		}
		else {
			$type_it["game_it_".$j] = 0;
		}
		
		
		if (array_key_exists($i, $new_terms_simple) &&
			array_key_exists($j, $new_terms_simple[$i])) {
			$type_it["simple_it_".$j] = count($new_terms_simple[$i][$j]);
			$per[$i] = array_merge($per[$i], $new_terms_simple[$i][$j]);
		}
		else {
			$type_it["simple_it_".$j] = 0;
		}
	}
	
	$terms_json[] = $type_it;
}

for ($i = 1; $i <= 20; $i++) {
	$per[$i] = array_unique($per[$i]);
	for ($j = 0; $j <= $max_iterations; $j++) {
		if (count($per[$i]) > 0) {
			$terms_json[$i - 1]["game_it_per_".$j] = round(
				$terms_json[$i - 1]["game_it_".$j]
				/ count($per[$i]) * 100, 0);
			$terms_json[$i - 1]["simple_it_per_".$j] = round(
				$terms_json[$i - 1]["simple_it_".$j]
				/ count($per[$i]) * 100, 0);
		}
		else {
			$terms_json[$i - 1]["game_it_per_".$j] = 0;
			$terms_json[$i - 1]["simple_it_per_".$j] = 0;
		}
	}
}

$query = "SELECT Par_ID, t1.Count_no+t2.Count_no AS Count_sum, ABS(t2.Count_no-t1.Count_no) AS Count_diff FROM 
	(SELECT Par_ID, COUNT(DISTINCT Worker_ID) AS Count_no
	FROM game_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	GROUP BY Par_ID) t1
	INNER JOIN
	(SELECT Par_ID, COUNT(DISTINCT Worker_ID) AS Count_no
	FROM game_simple_output_factors
	WHERE
	Worker_ID <> 'robot' AND
	Worker_ID <> 'android' AND
	Worker_ID <> 'Lora' AND
	Worker_ID <> 'levas' AND
	Worker_ID <> 'gsc' AND
	Worker_ID <> 'Rik' AND
	Worker_ID <> 'paranoid' AND
	Worker_ID <> 'oana' AND
	Worker_ID <> 'aaa' AND
	Worker_ID <> 'testme' AND
	Factors_count > -1
	GROUP BY Par_ID) t2 USING(Par_ID)
	WHERE ABS(t2.Count_no-t1.Count_no) < 2
	GROUP BY Par_ID
	ORDER BY Count_sum DESC, Count_diff ASC
	LIMIT 3";

$top_pars = array();
$res = mysqli_query($con, $query);
while ($row = mysqli_fetch_array($res)) {
	$top_pars[] = $row["Par_ID"];
}

$lines_json = array();

for ($i = 0; $i <= $max_iterations; $i++) {
	$lines_json[$i] = array();
	$lines_json[$i]["round"] = $i + 1;
	
	for ($j = 0; $j < 3; $j++) {
		$pid = $top_pars[$j];
		
		if (array_key_exists($pid, $new_terms_game)
		 && array_key_exists($i, $new_terms_game[$pid])) {
		 	$lines_json[$i]["game_term_".$j] = count($new_terms_game[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["game_term_".$j] : 0);
		}
		if (array_key_exists($pid, $new_terms_simple)
		 && array_key_exists($i, $new_terms_simple[$pid])) {
		 	$lines_json[$i]["simple_term_".$j] = count($new_terms_simple[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["simple_term_".$j] : 0);
		}
		
		if (array_key_exists($pid, $new_words_game)
		 && array_key_exists($i, $new_words_game[$pid])) {
		 	$lines_json[$i]["game_word_".$j] = count($new_words_game[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["game_word_".$j] : 0);
		}
		if (array_key_exists($pid, $new_words_simple)
		 && array_key_exists($i, $new_words_simple[$pid])) {
		 	$lines_json[$i]["simple_word_".$j] = count($new_words_simple[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["simple_word_".$j] : 0);
		}
		
		if (array_key_exists($pid, $new_types_game)
		 && array_key_exists($i, $new_types_game[$pid])) {
		 	$lines_json[$i]["game_type_".$j] = count($new_types_game[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["game_type_".$j] : 0);
		}
		if (array_key_exists($pid, $new_types_simple)
		 && array_key_exists($i, $new_types_simple[$pid])) {
		 	$lines_json[$i]["simple_type_".$j] = count($new_types_simple[$pid][$i]) +
			 	($i > 0 ? $lines_json[$i - 1]["simple_type_".$j] : 0);
		}
		
		$lines_json[$i]["par_game_".$j] = "par. ".$pid." game";
		$lines_json[$i]["par_simple_".$j] = "par. ".$pid." simple";
	}
}


mysqli_close($con);

?>
