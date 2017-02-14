<?php
	include "dblogin.php";

	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);

	$total = "SELECT COUNT(*) FROM games";

	$ratings = [];
	$completions = [];
	for ($i = 0; $i < 5; $i++) {
		$ratings[] = "SELECT COUNT(*) FROM games WHERE rating = " . ($i + 1);
		$completions[] = "SELECT COUNT(*) FROM games WHERE completion = {$i}";
	}

	$multiplayers = [];
	$multiplayers[] = "SELECT DISTINCT COUNT(*) FROM games WHERE (GREATEST(multi_comp_local, multi_coop_local) = 1)";
	$multiplayers[] = "SELECT DISTINCT COUNT(*) FROM games WHERE (2 <= GREATEST(multi_comp_local, multi_coop_local) AND GREATEST(multi_comp_local, multi_coop_local) <= 3)";
	$multiplayers[] = "SELECT DISTINCT COUNT(*) FROM games WHERE (GREATEST(multi_comp_local, multi_coop_local) = 4)";
	$multiplayers[] = "SELECT DISTINCT COUNT(*) FROM games WHERE (5 <= GREATEST(multi_comp_local, multi_coop_local) AND GREATEST(multi_comp_local, multi_coop_local) <= 6)";
	$multiplayers[] = "SELECT DISTINCT COUNT(*) FROM games WHERE (7 <= GREATEST(multi_comp_local, multi_coop_local))";
	
	$whereConsole = "";
	if ($_POST["consoleID"] == "-1") { 
		$whereConsole = ""; 
	} else if (!is_null($_POST["consoleChildren"])) {
		// Some redundancy here with the first criteria
		$whereConsole = "(console={$_POST["consoleID"]}";
		foreach ($_POST["consoleChildren"] as $child) {
    		$whereConsole .= " OR console={$child}";
    	}
    	$whereConsole .= ")";
	} else {
		$whereConsole = "console={$_POST["consoleID"]}";
	}

	if ($whereConsole != "") $total .= " WHERE " . $whereConsole; 
	$total = mysqli_fetch_array(mysqli_query($db, $total))[0];

	foreach ($ratings as $index => $rating) {
		if ($whereConsole != "") $rating .= " AND " . $whereConsole;
		$ratings[$index] = mysqli_fetch_array(mysqli_query($db, $rating))[0];
	}
	foreach ($completions as $index => $completion) {
		if ($whereConsole != "") $completion .= " AND " . $whereConsole;
		$completions[$index] = mysqli_fetch_array(mysqli_query($db, $completion))[0];
	}
	foreach ($multiplayers as $index => $multiplayer) {
		if ($whereConsole != "") $multiplayer .= " AND " . $whereConsole;
		$multiplayers[$index] = mysqli_fetch_array(mysqli_query($db, $multiplayer))[0];
	}

	// $rating = [154, 33, 22, 10, 44];
	// $completion = [33, 44, 22, 50, 10];
	// $multiplayer = [22, 50, 44, 10, 33];

	$percent = function($value) {
		global $total;
		if ($total != 0) { 
	    	return round($value / $total * 100);
	    } else {
	    	return 0;
	    }
	};
	$ratingPercent = array_map($percent, $ratings);
	$completionPercent = array_map($percent, $completions);
	$multiplayerPercent = array_map($percent, $multiplayers);

	// <table>s cannot have whitespace between them to balance 33% width

	echo "<p>Total Games: " . $total . "</p>
		<table class='stats-table'>
			<tr><th>Ratings</th><th></th><th></th></tr>
			<tr><td>1-Star </td><td>" . $ratings[0] . "</td><td>" . $ratingPercent[0] . "%</td></tr>
			<tr><td>2-Star </td><td>" . $ratings[1] . "</td><td>" . $ratingPercent[1] . "%</td></tr>
			<tr><td>3-Star </td><td>" . $ratings[2] . "</td><td>" . $ratingPercent[2] . "%</td></tr>
			<tr><td>4-Star </td><td>" . $ratings[3] . "</td><td>" . $ratingPercent[3] . "%</td></tr>
			<tr><td>5-Star </td><td>" . $ratings[4] . "</td><td>" . $ratingPercent[4] . "%</td></tr>
		</table><table class='stats-table'>
			<tr><th>Completion</th><th></th><th></th></tr>
			<tr><td>Endless </td><td>" . $completions[0] . "</td><td>" . $completionPercent[0] . "%</td></tr>
			<tr><td>Unplayed </td><td>" . $completions[1] . "</td><td>" . $completionPercent[1] . "%</td></tr>
			<tr><td>Unfinished </td><td>" . $completions[2] . "</td><td>" . $completionPercent[2] . "%</td></tr>
			<tr><td>Beaten </td><td>" . $completions[3] . "</td><td>" . $completionPercent[3] . "%</td></tr>
			<tr><td>Complete </td><td>" . $completions[4] . "</td><td>" . $completionPercent[4] . "%</td></tr>
		</table><table class='stats-table'>
			<tr><th>Multiplayer</th><th></th><th></th></tr>
			<tr><td>1-Player </td><td>" . $multiplayers[0] . "</td><td>" . $multiplayerPercent[0] . "%</td></tr>
			<tr><td>2-Player+ </td><td>" . $multiplayers[1] . "</td><td>" . $multiplayerPercent[1] . "%</td></tr>
			<tr><td>4-Player </td><td>" . $multiplayers[2] . "</td><td>" . $multiplayerPercent[2] . "%</td></tr>
			<tr><td>5-Player+ </td><td>" . $multiplayers[3] . "</td><td>" . $multiplayerPercent[3] . "%</td></tr>
			<tr><td>7-Player+ </td><td>" . $multiplayers[4] . "</td><td>" . $multiplayerPercent[4] . "%</td></tr>
		</table>";

	echo "<script> updateStats(); </script>"
?>