<?php
	include "dblogin.php";
?>

<!doctype html>
<html>
	<head>
		<?php include 'head.php' ?>
	</head>
	<body>
		<div id="sidedrawer">
			<h3>Consoles!</h3>
			<a class="sidedrawer-toggle sidedrawer-toggle-mobile">☰</a>

			<?php include 'consoles.php' ?>
		</div>
		<div id="content-wrapper">
			<h1>Video Games</h1>

			<div id="console-box-access">
				<h2 id="console-title">All Games</h2> 

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
				<!-- <div class='loading-icon uil-rolling-css' style='transform:scale(0.11);'><div><div></div><div></div></div></div> -->

				<div id="games"></div>
			</div>
		</div>
	</body>
</html>