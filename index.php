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
		<link rel="stylesheet" type="text/css" href="css/master.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/u/dt/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,fh-3.1.2,r-2.1.0/datatables.min.js"></script>

		<style>
			/**
			 * Body CSS
			 */
			html,
			body {
				height: 100%;
			}

			/**
			 * Layout CSS
			 */

			#sidedrawer {
				position: fixed;
				top: 0;
				bottom: 0;
				width: 270px;
				left: -270px;
				overflow: auto;
				z-index: 2;
				background-color: #FD927E;
				transition: transform 0.2s;

				padding: 20px;
			}

			.sidedrawer-toggle {
				font-size: 22px;
				width: 50%;
				float: right;
				text-align: right;
				line-height:0.70; /*This is neccessary since the ☰ icon and the custom font don't align well*/
			}

			#console-box-access .sidedrawer-toggle {
				font-size: 26px;
			}

			#content-wrapper {
				min-height: 100%;
				overflow-x: hidden;
				margin-left: 0px;
				transition: margin-left 0.2s;
			}

			.sidedrawer-toggle-mobile {
				display: initial;
			}

			.sidedrawer-toggle-desktop {
				display: none;
			}

			@media (min-width: 768px) {

				#sidedrawer {
					transform: translate(270px);
				}

				#content-wrapper {
					margin-left: 270px;
				}

				body.hide-sidedrawer #sidedrawer {
					transform: translate(0px);
				}

				body.hide-sidedrawer #content-wrapper {
					margin-left: 0;
				}

				.sidedrawer-toggle-mobile {
					display: none;
				}

				.sidedrawer-toggle-desktop {
					display: initial;
				}
			}


			/**
			 * Toggle Side drawer
			 */
			#sidedrawer.active {
				transform: translate(270px);
			}
		</style>
		<script>
			$(function() {
				var $body = $('body'),
					$sidedrawer = $('#sidedrawer');

				function mobileSidedrawer() {
					$sidedrawer.toggleClass('active');
				}

				function desktopSidedrawer() {
					$body.toggleClass('hide-sidedrawer');
				}

				$('.sidedrawer-toggle-mobile').on('click', mobileSidedrawer);
				$('.sidedrawer-toggle-desktop').on('click', desktopSidedrawer);
			});
		</script>
	</head>
	<body>
		<div id="sidedrawer">
			<h3>Consoles! </h3>
			<a class="sidedrawer-toggle sidedrawer-toggle-mobile">☰</a>
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
				<div id="games"></div>
			</div>

			<script>
				$('.dropdown').click(function(event){
					$(this).next().slideToggle();
				});

				$('.console-link').click(function(event){
					event.preventDefault(); //No link Page Reload

					var console = $(this).text();
					$("#console-title").text(console);

					$.post("stats.php", {"console":console}, function(data) {
						$("#stats").html(data);
					});
					$.post("games.php", {"console":console}, function(data) {
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