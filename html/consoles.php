<?php
	include "dblogin.php";
?>

<div class="console-box">
	<ul class="console-list">
		<li><a href="" class="console-link option-selected" data-id="-1" data-root="-1">All Games</a></li>
		<?php
			$sql = "SELECT * FROM consoles ORDER BY consoles.`order`";
			$result = mysqli_query($db, $sql);
			
			$previous_root = "";
			$root_name = "";
			$sublist_start = false;
			$sublist = false;
			
			//To create console sublists, assumes child consoles are ordered following root console
			while($row = mysqli_fetch_array($result)) {
				if ($row['owned'] == 1) {
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
						echo " <img class='dropdown' src='../svg/dropdown.svg'>
						<ul class='console-sublist'>
							<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
						$sublist=true;
					//New child console, continue sublist
					} else if ($row['console_root'] == $previous_root && !$sublist_start){
						echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
					}
				}
			}
		?>
	</ul>
</div>

<script>
	$('.dropdown').click(function(event) {
		$(this).siblings(".console-sublist").slideToggle();
	});

	$('.console-link').click(function(event) {
		event.preventDefault(); //No link Page Reload
		$(this).parents("ul").find('.option-selected').removeClass("option-selected");
		$(this).addClass("option-selected");

		// Change Title Element
		$(".console-title").text($(this).text());

		// Get Data to Pass
		var tagIDs = [];
		$(".tag-link.option-selected").each(function() { tagIDs.push($(this).attr("data-id")); });

		var consoleID = $(this).attr("data-id");
		var consoleRoot = $(this).attr("data-root");
		var consoleChildren = [];
		if (consoleRoot == consoleID) {
			$(`[data-root='${consoleRoot}']`).each(function() { consoleChildren.push($(this).attr("data-id")); });
		}

		if ($(this).next().is("img")) {
			$(".sidedrawer-left .loading-icon").insertAfter($(this).next("img")).removeClass("hidden");
		} else {
			$(".sidedrawer-left .loading-icon").appendTo($(this)).removeClass("hidden");			
		}

		var loading = {
			"stats": false, 
			"games": false, 
			"finish": function(component) { 
				if (component === "stats") this.stats = true;
				else if (component === "games") this.games = true;

				if (this.stats && this.games) {
					$(".loading-icon").addClass("hidden");					
				}
			}
		};

		// Pass Data
		$.post("stats.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			loading.finish("stats");
			$("#stats").html(data);
		});
		$.post("games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			loading.finish("games");
			$("#games").html(data);
		});
	});
</script>