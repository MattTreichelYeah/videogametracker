<?php
	/* Be extra sure UTF-8 */
	header('Content-Type: text/html; charset=utf-8');
?>

<!doctype html>
<html lang="en">
	<head>
		<?php include 'head.php' ?>
	</head>
	<body class="sidebar-hide-r">
		<aside class="sidebar sidebar-l colour1">
			<h3>Consoles</h3>
			<a class="sidebar-toggle sidebar-toggle-l-m">☰</a>

			<?php include 'components/loading.html' ?>

			<?php include 'consoles.php' ?>
		</aside>
		<main class="content">
			<h1>Video Games</h1>

			<section id="filter" class="filter colour2">
				<div class="filter-console">
					<a class="sidebar-toggle sidebar-toggle-l-m"><h2 class="console-title">All Games</h2> ☰</a>
					<a class="sidebar-toggle sidebar-toggle-l-d"><h2 class="console-title">All Games</h2> ☰</a>
				</div><div class="filter-tag"> <!-- No Line Break -->
					<a class="sidebar-toggle sidebar-toggle-r-m">☰ <h2 class="tag-title">All Tags</h2> </a>
					<a class="sidebar-toggle sidebar-toggle-r-d">☰ <h2 class="tag-title">All Tags</h2> </a>
				</div>
			</section>
			
			<section id="stats" class="stats colour1">
				<?php include 'components/loading.html' ?>

				<div id="stats-content"></div>
			</section>

			<section id="games" class="games colour2">
				<?php include 'components/loading.html' ?>

				<div id="games-content"></div>
			</section>
		</main>
		<aside class="sidebar sidebar-r colour1">
			<h3>Tags (Experimental Data)</h3>
			<a class="sidebar-toggle sidebar-toggle-r-m">☰</a>

			<?php include 'components/loading.html' ?>

			<?php include 'tags.php' ?>
		</aside>
	</body>
</html>