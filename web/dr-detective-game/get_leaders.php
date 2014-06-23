<?php

include "mysql.php";

//echo $_COOKIE["Username"];

if (isset($_COOKIE["Username"])) {
	$wid = $_COOKIE["Username"];
} else {
	mysqli_close($con);
	header('Location: login.php') ;
	exit();
}

$jid = 1;


if (isset($_GET['role'])) {
	$worker_role = $_GET['role'];
} else {
	$worker_role = 'annotate';
}

if (isset($_GET['task'])) {
	$fac_or_rel = $_GET['task'];
} else {
	$fac_or_rel = 'factors';
}


$current_score = 0;
$points_gained = 0;
$points_lost = 0;

$query = "SELECT * FROM game_user WHERE
	Name = '$wid' AND
	Worker_role = 'annotate' AND
	Task = 'factors'";
$res = mysqli_query($con, $query);

if ($row = mysqli_fetch_array($res)) {
	$current_score = $row["Score"];
	$points_gained = round($row["Points_gained"],1);
	$points_lost = round($row["Points_lost"],1);
	echo $row["Points_gained"]."\n".$row["Points_lost"]."\n\n\n";
}



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

$table = "";

$query = "SELECT DISTINCT(Name), Score FROM game_user ORDER BY Score DESC LIMIT 10";
$res = mysqli_query($con, $query);

$index = 1;
while ($row = mysqli_fetch_array($res)) {
	$name = $row["Name"];
	$score = $row["Score"];
	
	if (strcmp($wid, $name) != 0) {
		$table = $table."<tr><td>".$index."</td><td>".$name."</td><td>".round($score,1)."</td></tr>\n";
	}
	else {
		$table = $table."<tr><td><span class=\"trme\">".$index.
			"</span></td><td><span class=\"trme\">You".
			"</span></td><td><span class=\"trme\">".round($score,1).
			"</span></td></tr>\n";
	}

	$index++;
}

//echo $table;


mysqli_close($con);


?>
