<?php
	include "dblogin.php";
	
	$sql = "SELECT * FROM games WHERE console='" . $_POST["console"] . "'";
	$result = mysqli_query($db, $sql);
	
	echo $_POST["console"]
?>