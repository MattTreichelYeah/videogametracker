<?php
	/* Be extra sure UTF-8 */
	header('Content-Type: text/html; charset=utf-8');
	include "dblogin.php";
?>

<!doctype html>
<html>
	<head>
		<?php include 'head.php' ?>
	</head>
	<body class="hide-sidedrawer-right">
		<div class="sidedrawer sidedrawer-left">
			<h3>Consoles</h3>
			<a class="sidedrawer-toggle sidedrawer-left-toggle-mobile">☰</a>

			<!--Component from Loading.io -->
			<div class='loading-icon hidden uil-rolling-css'><div><div></div><div></div></div></div>

			<?php include 'consoles.php' ?>
		</div>
		<div class="content-wrapper">
			<h1>Video Games</h1>

			<div class="console-box-access">
				<div class="console-access">
					<a class="sidedrawer-toggle sidedrawer-left-toggle-mobile"><h2 class="console-title">All Games</h2> ☰</a>
					<a class="sidedrawer-toggle sidedrawer-left-toggle-desktop"><h2 class="console-title">All Games</h2> ☰</a>
				</div><div class="tag-access"> <!-- No Line Break -->
					<a class="sidedrawer-toggle sidedrawer-right-toggle-mobile">☰ <h2 class="tag-title">All Tags</h2> </a>
					<a class="sidedrawer-toggle sidedrawer-right-toggle-desktop">☰	<h2 class="tag-title">All Tags</h2> </a>
				</div>
			</div>
			
			<div id="stats-box">
				<!--<h3>Stats!</h3>-->

				<!--Component from Loading.io -->
				<div class='loading-icon uil-rolling-css'><div><div></div><div></div></div></div>

				<div id="stats"></div>
			</div>

			<div id="games-box">
				<!-- <h3>Games!</h3> -->

				<!--Component from Loading.io -->
				<div class='loading-icon uil-rolling-css'><div><div></div><div></div></div></div>

				<div id="games"></div>
			</div>
		</div>
		<div class="sidedrawer sidedrawer-right">
			<h3>Tags</h3>
			<a class="sidedrawer-toggle sidedrawer-right-toggle-mobile">☰</a>

			<!--Component from Loading.io -->
			<div class='loading-icon hidden uil-rolling-css'><div><div></div><div></div></div></div>

			<?php include 'tags.php' ?>
		</div>
	</body>
</html>