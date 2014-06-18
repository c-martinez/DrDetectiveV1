<?php

include "mysql.php";
include "game_flow.php";

//echo $_COOKIE["Username"];

if (isset($_COOKIE["Username"])) {
	$wid = $_COOKIE["Username"];
} else {
	mysqli_close($con);
	header('Location: login.php') ;
	exit();
}

$query = "UPDATE game_output_factors
	SET Factors_count = -2
	WHERE Factors_count = -1 AND
	Worker_ID = '$wid'";
//echo $query;
mysqli_query($con, $query);
$query = "UPDATE game_output_factors
	SET Factors_count = -2
	WHERE Factors_count = -1 AND
	Worker_ID = '$wid'";
//echo $query;
mysqli_query($con, $query);


setcookie ("LastPID", "", time() - 3600);
setcookie ("domains", "", time() - 3600);

$current_score = 0;
$points_gained = 0;
$points_lost = 0;

$query = "SELECT * FROM game_user WHERE
	Name = '$wid' AND
	Worker_role = 'annotate' AND
	Task = 'factors'";
$res = mysqli_query($con, $query);

$exp_group = -1;
if ($row = mysqli_fetch_array($res)) {
	$current_score = $row["Score"];
	$points_gained = round($row["Points_gained"],1);
	$points_lost = round($row["Points_lost"],1);
	//echo $row["Points_gained"]."\n".$row["Points_lost"]."\n\n\n";
	$exp_group = $row["Exp_group"];
}
//echo "Exp group: $exp_group";


$pop_text = "";
if ($points_gained == 0) {
	if ($points_lost == 0) {
		$pop_text = "nothing yet";
	}
	else {
		$pop_text = "<span class='trmin'>-$points_lost</span> points deducted by other users disagreeing with your answers";
	}
}
else {
	if ($points_lost == 0) {
		$pop_text = "<span class='trme'>+$points_gained</span> points gained from other users agreeing with your answers";
	}
	else {
		$pop_text = "<p><span class='trme'>+$points_gained</span> points gained from other users agreeing with your answers</p><p><span class='trmin'>-$points_lost</span> points deducted by other users disagreeing with your answers</p>";
	}
}

setcookie ("domains", "", time() - 3600);

$domain_count = array();

$domain_count[0] = count_paragraphs_per_domain($wid, "Hematology/Oncology", $con, $exp_group);
$domain_count[1] = count_paragraphs_per_domain($wid, "Nephrology", $con, $exp_group);
$domain_count[2] = count_paragraphs_per_domain($wid, "Primary Care/Hospitalist/Clinical Practice", $con, $exp_group);
$domain_count[3] = count_paragraphs_per_domain($wid, "Viral Infections", $con, $exp_group);

$domain_count_solved = array();
$domain_count_solved[0] = count_solved_paragraphs_per_domain($wid, "Hematology/Oncology", $con, $exp_group);
$domain_count_solved[1] = count_solved_paragraphs_per_domain($wid, "Nephrology", $con, $exp_group);
$domain_count_solved[2] = count_solved_paragraphs_per_domain($wid, "Primary Care/Hospitalist/Clinical Practice", $con, $exp_group);
$domain_count_solved[3] = count_solved_paragraphs_per_domain($wid, "Viral Infections", $con, $exp_group)



?>
