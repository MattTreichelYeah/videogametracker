<?php
	include "dblogin.php";

	// $queries = [];

	// $total = "SELECT COUNT(*) FROM games";
	// /*Unfinished*/
	// for ($i = 0; $i < 5; i++) {
	// 	$rating[] = "SELECT COUNT(*) FROM games WHERE rating='" . $i . "'";
	// 	$completion[] = "SELECT COUNT(*) FROM games WHERE completion='" . $i . "'";
	// }
	// // These don't really do what I want. They'll double-count co-op and comp
	// $multiplayer[] = "SELECT COUNT(*) FROM games WHERE multiplayer_comp_local='1' OR multiplayer_coop_local='1'";
	// $multiplayer[] = "SELECT COUNT(*) FROM games WHERE '1'<multiplayer_comp_local<'4' OR '1'<multiplayer_coop_local<'4'";
	// $multiplayer[] = "SELECT COUNT(*) FROM games WHERE multiplayer_comp_local='4' OR multiplayer_coop_local='4'";
	// $multiplayer[] = "SELECT COUNT(*) FROM games WHERE '4'<multiplayer_comp_local<'8' OR '4'<multiplayer_coop_local<'8'";
	// $multiplayer[] = "SELECT COUNT(*) FROM games WHERE '8'<multiplayer_comp_local OR '8'<multiplayer_coop_local";
	// if ($_POST["console"] != "All Games") {
	// 	$whereConsole = " console='" . $_POST["console"] . "'";
	// 	$total .= " WHERE" . whereConsole;
	// 	foreach ($ as &$value) { // & Lets you modify array values
	// 	    $value = $value * 2;
	// 	}
	// }
	// $total = mysqli_fetch_array(mysqli_query($db, $total));
	// $total = mysqli_fetch_array(mysqli_query($db, $total));
	// $total = mysqli_fetch_array(mysqli_query($db, $total));
	/*Unfinished End*/
	
	/*Placeholder Stats*/
	$total = "SELECT COUNT(*) FROM games";
	if ($_POST["console"] != "All Games") { $total .= " WHERE console='" . $_POST["console"]; }
	$total = mysqli_fetch_array(mysqli_query($db, $total));

	$rating = [154, 33, 22, 10, 44];
	$completion = [33, 44, 22, 50, 10];
	$multiplayer = [22, 50, 44, 10, 33];

	$percent = function($value) {
		global $total;
		if ($total[0] != 0) { 
	    	return round($value / $total[0] * 100);
	    } else {
	    	return 0;
	    }
	};
	$ratingPercent = array_map($percent, $rating);
	$completionPercent = array_map($percent, $completion);
	$multiplayerPercent = array_map($percent, $multiplayer);

	// <table>s cannot have whitespace between them to balance 33% width

	echo "<p>Total Games: " . $total[0] . "</p>
		<table class='stats-table'>
			<tr><th>Ratings</th><th></th><th></th></tr>
			<tr><td>1-Star: </td><td>" . $rating[0] . "</td><td>" . $ratingPercent[0] . "%</td></tr>
			<tr><td>2-Star: </td><td>" . $rating[1] . "</td><td>" . $ratingPercent[1] . "%</td></tr>
			<tr><td>3-Star: </td><td>" . $rating[2] . "</td><td>" . $ratingPercent[2] . "%</td></tr>
			<tr><td>4-Star: </td><td>" . $rating[3] . "</td><td>" . $ratingPercent[3] . "%</td></tr>
			<tr><td>5-Star: </td><td>" . $rating[4] . "</td><td>" . $ratingPercent[4] . "%</td></tr>
		</table><table class='stats-table'>
			<tr><th>Completion</th><th></th><th></th></tr>
			<tr><td>Unplayed: </td><td>" . $completion[0] . "</td><td>" . $completionPercent[0] . "%</td></tr>
			<tr><td>Unfinished: </td><td>" . $completion[1] . "</td><td>" . $completionPercent[1] . "%</td></tr>
			<tr><td>Beaten: </td><td>" . $completion[2] . "</td><td>" . $completionPercent[2] . "%</td></tr>
			<tr><td>Complete: </td><td>" . $completion[3] . "</td><td>" . $completionPercent[3] . "%</td></tr>
			<tr><td>Null: </td><td>" . $completion[4] . "</td><td>" . $completionPercent[4] . "%</td></tr>
		</table><table class='stats-table'>
			<tr><th>Multiplayer</th><th></th><th></th></tr>
			<tr><td>1-Player </td><td>" . $multiplayer[0] . "</td><td>" . $multiplayerPercent[0] . "%</td></tr>
			<tr><td>2-Player+ </td><td>" . $multiplayer[1] . "</td><td>" . $multiplayerPercent[1] . "%</td></tr>
			<tr><td>4-Player </td><td>" . $multiplayer[2] . "</td><td>" . $multiplayerPercent[2] . "%</td></tr>
			<tr><td>5-Player+ </td><td>" . $multiplayer[3] . "</td><td>" . $multiplayerPercent[3] . "%</td></tr>
			<tr><td>7-Player+ </td><td>" . $multiplayer[4] . "</td><td>" . $multiplayerPercent[4] . "%</td></tr>
		</table>";
?>

<script>

$('.stats-table tr td:nth-child(3)').each(function(index, element) {
	var percent = $(element).text();
	$(element).css("background", "url('images/stat-percent-background.png') no-repeat");
    $(element).css("background-size", percent + " 100%");
});

</script>