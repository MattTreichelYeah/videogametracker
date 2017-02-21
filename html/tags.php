<?php
	include "dblogin.php";
?>

<div class="console-box">
	<ul class="console-list">
		<li><a href="" class="tag-link option-selected" data-id="-1" data-root="-1">All Tags</a></li>
		<?php
			$sql = "SELECT * FROM tags ORDER BY name";
			$result = mysqli_query($db, $sql);
			
			while($row = mysqli_fetch_array($result)) {
				echo "<li><a href='' class='tag-link' data-id='{$row['id']}'>{$row['name']}</a>";
			}
		?>
	</ul>
</div>

<script>
	$('.tag-link').click(function(event){
		event.preventDefault(); //No link Page Reload

		$(this).toggleClass("option-selected");
		$(".tag-title").text("Tag Filtered");
		var alltags = $(".tag-link").first()
		if ($(".tag-link.option-selected").length === 0) {
			alltags.addClass("option-selected");
			$(".tag-title").text($(this).text());
		} else if ($(this).is(alltags)) {
			$(".tag-link.option-selected").removeClass("option-selected");
			alltags.addClass("option-selected");
			$(".tag-title").text($(this).text());
		} else if (alltags.hasClass("option-selected")) {
			alltags.removeClass("option-selected");
		}

		// Get Data to Pass
		var tagIDs = [];
		$(".tag-link.option-selected").each(function() { tagIDs.push($(this).attr("data-id")); });

		var consoleID = $(".console-link.option-selected").attr("data-id");
		var consoleRoot = $(".console-link.option-selected").attr("data-root");
		var consoleChildren = [];
		if (consoleRoot == consoleID) {
			$(`[data-root='${consoleRoot}']`).each(function() { consoleChildren.push($(this).attr("data-id")); });
		}

		$(".sidedrawer-right .loading-icon").appendTo($(this)).removeClass("hidden");

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