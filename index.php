<?php
	include "dblogin.php";
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Gaming Collection</title>

		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Oswald:400,300'>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.css"/>
		<link rel="stylesheet" type="text/css" href="css/checkbox-toggle.css">
		<link rel="stylesheet" type="text/css" href="css/master.css">
		<link rel="stylesheet" type="text/css" href="css/sidedrawer.css">
		<link rel="stylesheet" type="text/css" href="css/loading-icon.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.js"></script>
		<script type="text/javascript" src="js/sidedrawer.js"></script>
	</head>
	<body>
		<div id="sidedrawer">
			<h3>Consoles! </h3>
			<a class="sidedrawer-toggle sidedrawer-toggle-mobile">☰</a>

			<?php include 'consoles.php' ?>
		</div>
		<div id="content-wrapper">
			<h1>Video Games</h1>

			<div id="console-box-access">
				<h2 id="console-title">All Games </h2> 

				<a class="sidedrawer-toggle sidedrawer-toggle-mobile">☰</a>
				<a class="sidedrawer-toggle sidedrawer-toggle-desktop">☰</a>
			</div>
			
			<div id="stats-box">
				<h3>Stats!</h3>

				<div id="stats"></div>
			</div>

			<div id="games-box">
				<h3>Games!</h3>

				<!--Component from Proto.io - https://proto.io/freebies/onoff/-->
				<div class="onoffswitch">
				    <input type="checkbox" id="singlemulti" name="singlemulti" class="onoffswitch-checkbox" checked>
				    <label class="onoffswitch-label" for="singlemulti">
				        <span class="onoffswitch-inner"></span>
				        <span class="onoffswitch-switch"></span>
				    </label>
				</div>

				<!--Component from Loading.io -->
				<div class='loading-icon uil-rolling-css' style='transform:scale(0.11);'><div><div></div><div></div></div></div>

				<div id="games"></div>
			</div>

			<script>
				$(document).ready(function(event){ //Default View
					$.post("stats.php", {"console":"All Games"}, function(data) {
						$("#stats").html(data);
					});
					$.post("games.php", {"console":"All Games"}, function(data) {
						$("#games").html(data);
					});
				});
			</script>
		</div>
	</body>
</html>