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
		<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Press+Start+2P|VT323' rel='stylesheet' type='text/css'>-->
		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Oswald:400,300'>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.css"/>
		<!-- <link rel="stylesheet" type="text/css" href="//cdn.muicss.com/mui-0.6.0/css/mui.min.css"> -->
		<link rel="stylesheet" type="text/css" href="css/master.css">
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.js"></script>
		<!-- <script type="text/javascript" src="//cdn.muicss.com/mui-0.6.0/js/mui.min.js"></script> -->

		<style>
			/**
			 * Body CSS
			 */
			html,
			body {
				height: 100%;
			}

			html,
			body,
			input,
			textarea,
			buttons {
				text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
			}


			/**
			 * Layout CSS
			 */
			#header {
				position: fixed;
				top: 0;
				right: 0;
				left: 0;
				z-index: 2;
				transition: left 0.2s;
			}

			#sidedrawer {
				position: fixed;
				top: 0;
				bottom: 0;
				width: 200px;
				left: -200px;
				overflow: auto;
				z-index: 2;
				background-color: #C6EC51;
				transition: transform 0.2s;
			}

			#content-wrapper {
				min-height: 100%;
				overflow-x: hidden;
				margin-left: 0px;
				transition: margin-left 0.2s;

				/* sticky bottom */
				margin-bottom: -160px;
				padding-bottom: 160px;
			}

			@media (min-width: 768px) {
				#header {
					left: 200px;
				}

				#sidedrawer {
					transform: translate(200px);
				}

				#content-wrapper {
					margin-left: 200px;
				}

				#footer {
					margin-left: 200px;
				}

				body.hide-sidedrawer #header {
					left: 0;
				}

				body.hide-sidedrawer #sidedrawer {
					transform: translate(0px);
				}

				body.hide-sidedrawer #content-wrapper {
					margin-left: 0;
				}

				body.hide-sidedrawer #footer {
					margin-left: 0;
				}
			}


			/**
			 * Toggle Side drawer
			 */
			#sidedrawer.active {
				transform: translate(200px);
			}


			/**
			 * Header CSS
			 */
			.sidedrawer-toggle {
				color: #fff;
				cursor: pointer;
				font-size: 20px;
				line-height: 20px;
				margin-right: 10px;
			}

			.sidedrawer-toggle:hover {
				color: #fff;
				text-decoration: none;
			}

			/**
			 * Side drawer CSS
			 */
			#sidedrawer-brand {
				padding-left: 20px;
			}

			#sidedrawer ul {
				list-style: none;
			}

			#sidedrawer > ul {
				padding-left: 0px;
			}

			#sidedrawer > ul > li:first-child {
				padding-top: 15px;
			}

			#sidedrawer strong {
				display: block;
				padding: 15px 22px;
				cursor: pointer;
			}

			#sidedrawer strong:hover {
				background-color: #E0E0E0;
			}

			#sidedrawer strong + ul > li {
				padding: 6px 0px;
			}
		</style>
		<script>
			jQuery(function($) {
				var $bodyEl = $('body'), $sidedrawerEl = $('#sidedrawer');

				function showSidedrawer() {
					setTimeout(function() {
						$sidedrawerEl.toggleClass('active');
					}, 20);
				}

				function hideSidedrawer() {
					$bodyEl.toggleClass('hide-sidedrawer');
				}

				$('.js-show-sidedrawer').on('click', showSidedrawer);
				$('.js-hide-sidedrawer').on('click', hideSidedrawer);
			});
		</script>
	</head>
	<body>
		<div id="sidedrawer" class="mui--no-user-select">
			<div id="sidedrawer-brand" class="mui--appbar-line-height mui--text-title">Consoles!</div>
			<!-- <a class="sidedrawer-toggle mui--visible-xs-inline-block js-hide-sidedrawer">☰</a> -->
			<div id="console-box">
				<ul id="console-list">
					<li><a href="" class="console-link">All Games</a></li>
					<?php
						$sql = "SELECT * FROM  consoles ORDER BY consoles.`order`";
						$result = mysqli_query($db, $sql);
						
						$previous_root = "";
						$start_sublist = false;
						$sublist = false;
						
						//To create console sublists, assumes child consoles are ordered following root console
						while($row = mysqli_fetch_array($result)) {
						 //New root console, no previous sublist, ready to start sublist
						 if ($row['console_root'] == $row['name'] && !$sublist) { 
							 echo '<li><a href="" class="console-link">'. $row['name'] .'</a>';
							 $previous_root = $row['console_root'];
							 $start_sublist = true;
						 //New root console, previous sublist, quit sublist and ready to start new sublist
						 } else if ($row['console_root'] == $row['name'] && $sublist) { 
							 $sublist = false;
							 echo '</ul></li>
							 <li><a href="" class="console-link">'. $row['name'] .'</a>';
							 $previous_root = $row['console_root'];
							 $start_sublist = true;
						 //Child console exists, create sublist
						 } else if ($row['console_root'] == $previous_root && $start_sublist){
							 $start_sublist=false;
							 echo ' <img class="dropdown" src="images/dropdown2.png">
							 <ul class="console-sublist">
								 <li><a href="" class="console-link">'. $row['name'] .'</a></li>';
							 $sublist=true;
						 //New child console, continue sublist
						 } else if ($row['console_root'] == $previous_root && !$start_sublist){
							 echo '<li><a href="" class="console-link">'. $row['name'] .'</a></li>';
						 }
						}
					?>
				</ul>
			</div>
		</div>
		<header id="header">
			<div class="mui--appbar-line-height">
				<div class="mui-container-fluid">
					<a class="sidedrawer-toggle mui--visible-xs-inline-block js-show-sidedrawer">M☰</a>
					<a class="sidedrawer-toggle mui--hidden-xs js-hide-sidedrawer">D☰</a>
				</div>
			</div>
		</header>
		<div id="content-wrapper">
			<div class="mui--appbar-height"></div>
			<br>
			<h1 id="ultimate-title">Video Games</h1>

			<div id="content">
				
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
		</div>
	</body>
</html>