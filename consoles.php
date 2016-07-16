<?php
	include "dblogin.php";
?>

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
</script>