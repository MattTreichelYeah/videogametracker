<?php
	include "dblogin.php";

	$sql = "";

	$sql .= "SELECT COUNT(*) FROM (";

	$sql .= "SELECT COUNT(*) FROM games AS g 
		LEFT JOIN games_tags AS gt ON g.id = gt.gameid
		LEFT JOIN tags AS t ON t.id = gt.tagid
		WHERE compilation_root != -1";

	$total = $sql;

	$ratings = [];
	$completions = [];
	for ($i = 0; $i < 5; $i++) {
		$ratings[] = $sql . " AND rating = " . ($i + 1);
		$completions[] = $sql . " AND completion = {$i}";
	}

	$multiplayers = [];
	$multiplayers[] = $sql . " AND (GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) = 1)";
	$multiplayers[] = $sql . " AND (2 <= GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) AND GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) <= 3)";
	$multiplayers[] = $sql . " AND (GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) = 4)";
	$multiplayers[] = $sql . " AND (5 <= GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) AND GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)) <= 6)";
	$multiplayers[] = $sql . " AND (7 <= GREATEST(multi_comp_local, multi_coop_local, COALESCE(multi_comp_LAN, 0), COALESCE(multi_coop_LAN, 0)))";
	
	$whereConsole = "";	
	if ($_POST["consoleID"] != "-1") { 
		if (!is_null($_POST["consoleChildren"])) {
			// Some redundancy here with the first criteria
			$whereConsole .= " AND (g.console = {$_POST["consoleID"]}";
			foreach ($_POST["consoleChildren"] as $child) {
	    		$whereConsole .= " OR g.console = {$child}";
	    	}
		} else {
			$whereConsole .= " AND (g.console = {$_POST["consoleID"]}";
		}

		if(!is_null($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$whereConsole .= ") AND (gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$whereConsole .= " OR gt.tagid = {$tag}";
	    	}
	    	$whereConsole .= ") GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
			$whereConsole .= ") GROUP BY g.id";
		}
	} else {
		if(!is_null($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$whereConsole .= " AND (gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$whereConsole .= " OR gt.tagid = {$tag}";
	    	}
	    	$whereConsole .= ") GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
			$whereConsole .= " GROUP BY g.id";			
		}
	}

	$total .= $whereConsole;
	$total .= ") AS count"; 
	$total = mysqli_fetch_array(mysqli_query($db, $total))[0];

	foreach ($ratings as $index => $rating) {
		if ($whereConsole != "") $rating .= $whereConsole . ") AS count";
		$ratings[$index] = mysqli_fetch_array(mysqli_query($db, $rating))[0];
	}
	foreach ($completions as $index => $completion) {
		if ($whereConsole != "") $completion .= $whereConsole . ") AS count";
		$completions[$index] = mysqli_fetch_array(mysqli_query($db, $completion))[0];
	}
	foreach ($multiplayers as $index => $multiplayer) {
		if ($whereConsole != "") $multiplayer .= $whereConsole . ") AS count";
		$multiplayers[$index] = mysqli_fetch_array(mysqli_query($db, $multiplayer))[0];
	}

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
		</table><table class='stats-table'> <!-- No Line Break -->
			<tr><th>Completion</th><th></th><th></th></tr>
			<tr><td>Endless </td><td>" . $completions[0] . "</td><td>" . $completionPercent[0] . "%</td></tr>
			<tr><td>Unplayed </td><td>" . $completions[1] . "</td><td>" . $completionPercent[1] . "%</td></tr>
			<tr><td>Unfinished </td><td>" . $completions[2] . "</td><td>" . $completionPercent[2] . "%</td></tr>
			<tr><td>Beaten </td><td>" . $completions[3] . "</td><td>" . $completionPercent[3] . "%</td></tr>
			<tr><td>Complete </td><td>" . $completions[4] . "</td><td>" . $completionPercent[4] . "%</td></tr>
		</table><table class='stats-table'> <!-- No Line Break -->
			<tr><th>Multiplayer</th><th></th><th></th></tr>
			<tr><td>1-Player </td><td>" . $multiplayers[0] . "</td><td>" . $multiplayerPercent[0] . "%</td></tr>
			<tr><td>2-Player+ </td><td>" . $multiplayers[1] . "</td><td>" . $multiplayerPercent[1] . "%</td></tr>
			<tr><td>4-Player </td><td>" . $multiplayers[2] . "</td><td>" . $multiplayerPercent[2] . "%</td></tr>
			<tr><td>5-Player+ </td><td>" . $multiplayers[3] . "</td><td>" . $multiplayerPercent[3] . "%</td></tr>
			<tr><td>7-Player+ </td><td>" . $multiplayers[4] . "</td><td>" . $multiplayerPercent[4] . "%</td></tr>
		</table>";

	echo "<script> updateStats(); </script>"
?>