<?php

include "mysql.php";

if (isset($_POST['user_sign'])) {
	if (isset($_POST['pass'])) {
		if (isset($_POST['pass2'])) {
			if (strcmp($_POST['pass'], $_POST['pass2']) == 0) {
				$name = $_POST["user_sign"];
				$pass = $_POST["pass"];
				$query = "SELECT COUNT(*) FROM game_user WHERE
					Name = '$name'";
				$res = mysqli_query($con, $query);
				if ($row = mysqli_fetch_array($res)) {
					if ($row["COUNT(*)"] == 0) {				
						
									
						
						$query = "INSERT INTO game_user (Name, Password, Score, Last_Par_ID, Worker_role, Task, Points_gained, Points_lost, Exp_group)
							VALUES ('$name', '$pass', 0, -1, 'annotate', 'factors', 0, 0, 0)";
						//echo $query;
							
						mysqli_query($con, $query);
						
						$query = "SELECT ID FROM game_user WHERE Name = '$name'";
						$res2 = mysqli_query($con, $query);
						if ($row2 = mysqli_fetch_array($res2)) {
							$user_id = $row2["ID"];
							
							//	echo $user_id."\n\n\n";
							if ($user_id % 2 === 0) {
								//echo "par";
								$query = "UPDATE game_user SET Exp_group = 1 WHERE Name = '$name'";
								//echo $query;
								mysqli_query($con, $query);
							}
							else {
								//echo "impar";
								$query = "UPDATE game_user SET Exp_group = 2 WHERE Name = '$name'";
								//echo $query;
								mysqli_query($con, $query);
							}
						}
						
						setcookie("Username", $_POST['user_sign'], time() + 3600*24);
						//echo $_COOKIE["Username"];
								
						mysqli_close($con);
						header('Location: index.php') ;
						exit();
					}
				}
			}
		}
	}
}

if (isset($_POST['user_log'])) {
	if (isset($_POST['pass'])) {
		$name = $_POST['user_log'];
		$query = "SELECT Password FROM game_user WHERE
			Name = '$name'";
		$res = mysqli_query($con, $query);

		if ($row = mysqli_fetch_array($res)) {
			if (strcmp($_POST['pass'], $row['Password']) == 0) {
				setcookie("Username", $_POST['user_log'], time() + 3600*24);
				echo $_COOKIE["Username"];
				
				mysqli_close($con);
				header('Location: index.php') ;
				exit();

			}
		}
		
	}
}

mysqli_close($con);
header('Location: login.php') ;
exit();


?>
