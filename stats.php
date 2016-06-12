<?php
	include "dblogin.php";

	if ($_POST["console"] == "All Games") {
		$sql = "SELECT COUNT(*) FROM games";
	} else {
		$sql = "SELECT COUNT(*) FROM games WHERE console='" . $_POST["console"] . "'";
	}
	$result = mysqli_query($db, $sql);
	$count = mysqli_fetch_array($result);
	
	echo "<p>Total Games: " . $count[0] . "</p>";
?>