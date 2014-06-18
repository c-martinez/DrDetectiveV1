<?php

include "mysql.php";
include "verbose.php";

if (isset($_POST['time_start'])) {

	echo "TIME START: ".$_POST['time_start']."\n\n\n";

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
					$time_start_f = $results[$ft]["timeStart"];
					$time_create_f = $results[$ft]["timeCreate"];
					$count_f = count($results[$ft]["factorExpl"]);
					
					$factor_csv = "";
					for ($i = 0; $i < $count_f; $i++) {
						if ($i > 0) $factor_csv = $factor_csv."\n";
					
						$words_csv = "";
						for ($j = 0; $j < count($results[$ft]["factorPos"][$i]); $j++) {
							if ($j > 0) $words_csv = $words_csv.",";
							$words_csv = $words_csv.$results[$ft]["factorPos"][$i][$j];
						}
						
						$factor_csv = $factor_csv."\"".$results[$ft]["factorExpl"][$i]."\",".$results[$ft]["factorTimes"][$i].",".$words_csv;
					}
					
					//echo $factor_csv."\n\n";
					
					$query = "INSERT INTO game_output_factors (Par_ID, Job_ID, Worker_ID, Worker_role, Factor_class, Factor_type, Factors_count, Factors_list, Time_start, Time_create, Relation_role)
					VALUES ($pid, $jid,'$wid','$worker_role','$fc','$ft','$count_f','$factor_csv', '$time_start_f', '$time_create_f', '$relation_role')";
					mysqli_query($con, $query);
					//echo $query;
					
					$found_answers = true;
				}
			}
		}
		
		if ($found_answers == false) {
					$query = "INSERT INTO game_output_factors (Par_ID, Job_ID, Worker_ID, Worker_role,Time_start, Time_create, Relation_role)
					VALUES ($pid, $jid,'$wid','$worker_role','$time_start', '$time_create', '$relation_role')";
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
			}
			$query = "INSERT INTO game_output_relations (Par_ID, Job_ID, Worker_ID, Worker_role, Time_start, Time_create, Relations_count, Relations_list, Term1, Term2)
			VALUES ($pid, $jid, '$wid', '$worker_role', '$time_start_f', '$time_create_f', $count_f, '$rel_csv', \"$term1\",\"$term2\")";
			//echo $query;
			mysqli_query($con, $query);
		}
		else {
			$query = "INSERT INTO game_output_relations (Par_ID, Job_ID, Worker_ID, Worker_role, Time_start, Time_create, Term1, Term2)
			VALUES ($pid, $jid, '$wid', '$worker_role', '$time_start', '$time_create', \"$term1\",\"$term2\")";
			//echo $query;
			mysqli_query($con, $query);
		}
	}
}

//echo 'Location: test.php?user='.$wid.'&task='.$fac_or_rel.'&role='.$worker_role;

header('Location: test.php?user='.$wid.'&task='.$fac_or_rel.'&role='.$worker_role) ;
exit();

?>
