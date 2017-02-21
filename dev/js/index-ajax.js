$(document).ready(function(event) { //Default View
	var tagIDs = ["-1"];

	var loading = {
		"finish": function(component) { 
			if (component === "stats") {
				$("#stats-box .loading-icon").addClass("hidden");					
			} else if (component === "games") {
				$("#games-box .loading-icon").addClass("hidden");					
			}
		}
	};

	$.post("stats.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$("#stats").html(data);
		loading.finish("stats");
	});
	$.post("games.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$("#games").html(data);
		loading.finish("games");
	});
});