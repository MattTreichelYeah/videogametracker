<?php
	include "dblogin.php";
?>

<!DOCTYPE html>
<html lang='en'>

<head>
	<meta content="text/html; charset=utf-8" http-equiv="content-type">
	<title>Gaming Collection</title>
	<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Press+Start+2P|VT323' rel='stylesheet' type='text/css'>-->
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
	<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
</head>

<body>

<style>

*, :before, :after {
	box-sizing: border-box;
}

html, body {
	margin:0px;
	padding:0px;
	font-family: 'Oswald', Verdana;
}

body {
	background: black;
}

#ultimate-title {
	font-family: 'Oswald', cursive;
	margin:10px;
	font-size:50px;
	border-bottom: 4px solid white;
	color:white;
}

#content {
	margin:10px;
}

#console-box {
	color:white;
	font-size:22px;
	width:20%;
	//min-width:275px;
	background:black;
	padding:15px;
	float:left;
	border: solid 10px #C6EC51;
}

#games-box {
	font-size:15px;
	width:100%;
	min-width:250px;
	background:#C6EC51;
	padding:20px;
	clear:both;
	float:left;
}

#stats-box {
	font-size:20px;
	width:100%;
	background: #FD927E;
	padding:20px;
	float:left;
}

#console-list {
	list-style-type: none;
	padding-left:0px;
}

.console-sublist {
	font-size:15px;
	list-style-type: none;
	padding-left:15px;
	display: none;
}

#games-table {
	text-align:left;
	width:100%;
}

#right {
	float:left;
	width:80%;
}

a:link {
    color: white;
}
a:visited {
    color: white;
}
a:hover {
    color: white;
}
a:active {
    color: white;
}

h3 {
	margin-top: 0px;
}

.dataTables_filter {
	background: #FD927E;
	border-radius: 10px;
	width:100%;
	text-align:center !important;
	font-weight:bold;
	font-size:15px;
	padding:10px;
}

.dataTables_filter input {
	width:90%;
	font-size:15px;
}

.rating {
	//vertical-align:middle;
}

.sub-table {
	width:100%;
	background: yellow;
}

.sub-table td {
	padding:0px !important;
	background: yellow;
}

/*
.subgames :first-child {
	margin-left:30px;
	color:red;
}
*/


</style>

<h1 id="ultimate-title">Video Games</h1>

<div id="content">
	<div id="console-box">
		<h3>Consoles!</h3>
		<ul id="console-list">
			<li><a href="" class="console-link">All Games</a></li>
			<?php
				$sql = "SELECT * FROM  consoles ORDER BY consoles.`order`";
				$result = mysqli_query($db, $sql);
				
				$previous_root = "";
				$start_sublist = false;
				$sublist = false;
				
				while($row = mysqli_fetch_array($result)) {
					if ($row['console_root'] == $row['name'] && !$sublist) {
						echo '<li><a href="" class="console-link">'. $row['name'] .'</a>';
						$previous_root = $row['console_root'];
						$start_sublist = true;
					} else if ($row['console_root'] == $row['name'] && $sublist) {
						$sublist = false;
						echo '</ul></li>
						<li><a href="" class="console-link">'. $row['name'] .'</a>';
						$previous_root = $row['console_root'];
						$start_sublist = true;
					} else if ($row['console_root'] == $previous_root && $start_sublist){
						$start_sublist=false;
						echo ' <img class="dropdown" src="images/dropdown2.png">
						<ul class="console-sublist">
							<li><a href="" class="console-link">'. $row['name'] .'</a></li>';
						$sublist=true;
					} else if ($row['console_root'] == $previous_root && !$start_sublist){
						echo '<li><a href="" class="console-link">'. $row['name'] .'</a></li>';
					}
				}
			?>
		</ul>
	</div>
	
	<div id="right">
		<div id="stats-box">
			<h3>Stats!</h3>
			<div id="stats"></div>
		</div>

		<div id="games-box">
			<h3>Games!</h3>
			<div id="games"></div>
		</div>
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