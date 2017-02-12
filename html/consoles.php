<?php
	include "dblogin.php";
?>

<div id="console-box">
	<ul id="console-list">
		<li><a href="" class="console-link" data-id="-1" data-root="-1">All Games</a></li>
		<?php
			$sql = "SELECT * FROM consoles ORDER BY consoles.`order`";
			$result = mysqli_query($db, $sql);
			
			$previous_root = "";
			$root_name = "";
			$sublist_start = false;
			$sublist = false;
			
			//To create console sublists, assumes child consoles are ordered following root console
			while($row = mysqli_fetch_array($result)) {
				if ($row['console_root'] == $row['id']) { 
					if (!$sublist) { //New root console, no previous sublist, ready to start sublist
						echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']}</a>";
					} else { //New root console, previous sublist, quit sublist and ready to start new sublist
						$sublist = false;
						echo "</ul></li>
						<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']}</a>";
					}
					$previous_root = $row['console_root'];
					$root_name = $row['name_short'];
					$sublist_start = true;
				//Child console exists, create sublist
				} else if ($row['console_root'] == $previous_root && $sublist_start){
					$sublist_start=false;
					echo " <img class='dropdown' src='../images/dropdown2.png'>
					<ul class='console-sublist'>
						<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
					$sublist=true;
				//New child console, continue sublist
				} else if ($row['console_root'] == $previous_root && !$sublist_start){
					echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
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

		// Change Title Element
		$("#console-title").text($(this).text());

		// Get Data to Pass
		var consoleID = $(this).attr("data-id");
		var consoleRoot = $(this).attr("data-root");
		var consoleChildren = [];
		if (consoleRoot == consoleID) {
			$(`[data-root='${consoleRoot}']`).each(function() { consoleChildren.push($(this).attr("data-id")); });
		}

		// Pass Data
		$.post("stats.php", {"consoleID": consoleID, "consoleChildren": consoleChildren}, function(data) {
			$("#stats").html(data);
		});
		$.post("games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren}, function(data) {
			$("#games").html(data);
		});
	});
</script>