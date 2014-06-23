<?php
	// Make a MySQL Connection
	$con=mysqli_connect("localhost", "watsoncs", "Tre2akEf","watsoncs");
	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>
