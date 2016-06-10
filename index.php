<?php
	include "dblogin.php";
?>

<!DOCTYPE html>
<html lang='en'>

<head>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gaming Collection</title>
	<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Press+Start+2P|VT323' rel='stylesheet' type='text/css'>-->
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="css/master.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.js"></script>
</head>

<body>

<h1 id="ultimate-title">Video Games</h1>

<div id="content">
<!-- 	<div id="console-box">
		<h3>Consoles!</h3>
		<ul id="console-list">
			<li><a href="" class="console-link">All Games</a></li>
			<?php
				// $sql = "SELECT * FROM  consoles ORDER BY consoles.`order`";
				// $result = mysqli_query($db, $sql);
				
				// $previous_root = "";
				// $start_sublist = false;
				// $sublist = false;
				
				// //To create console sublists, assumes child consoles are ordered following root console
				// while($row = mysqli_fetch_array($result)) {
				// 	//New root console, no previous sublist, ready to start sublist
				// 	if ($row['console_root'] == $row['name'] && !$sublist) { 
				// 		echo '<li><a href="" class="console-link">'. $row['name'] .'</a>';
				// 		$previous_root = $row['console_root'];
				// 		$start_sublist = true;
				// 	//New root console, previous sublist, quit sublist and ready to start new sublist
				// 	} else if ($row['console_root'] == $row['name'] && $sublist) { 
				// 		$sublist = false;
				// 		echo '</ul></li>
				// 		<li><a href="" class="console-link">'. $row['name'] .'</a>';
				// 		$previous_root = $row['console_root'];
				// 		$start_sublist = true;
				// 	//Child console exists, create sublist
				// 	} else if ($row['console_root'] == $previous_root && $start_sublist){
				// 		$start_sublist=false;
				// 		echo ' <img class="dropdown" src="images/dropdown2.png">
				// 		<ul class="console-sublist">
				// 			<li><a href="" class="console-link">'. $row['name'] .'</a></li>';
				// 		$sublist=true;
				// 	//New child console, continue sublist
				// 	} else if ($row['console_root'] == $previous_root && !$start_sublist){
				// 		echo '<li><a href="" class="console-link">'. $row['name'] .'</a></li>';
				// 	}
				// }
			?>
		</ul>
	</div> -->
	
	<div id="stats-box">
		<h3>Stats!</h3>
		<div id="stats"></div>
	</div>

	<div id="games-box">
		<h3>Games!</h3>
		<div id="games"></div>
	</div>
</div>

<script>
$('.dropdown').click(function(event){
	$(this).next().slideToggle();
});

$('.console-link').click(function(event){
	event.preventDefault(); //No Page Reload
	$.post("stats.php", {"console":$(this).text()}, function(data) {
		$("#stats").html(data);
	});
	$.post("games.php", {"console":$(this).text()}, function(data) {
		$("#games").html(data);
	});
});

$(document).ready(function(event){ //Default View
	$.post("stats.php", {"console":"All Games"}, function(data) {
		$("#stats").html(data);
	});
	$.post("games.php", {"console":"All Games"}, function(data) {
		$("#games").html(data);
	});
});
</script>

</body>
</html>