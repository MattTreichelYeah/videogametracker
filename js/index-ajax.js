$(document).ready(function(event) { //Default View
	var tagIDs = ["-1"];
	$.post("stats.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$("#stats").html(data);
	});
	$.post("games.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$("#games").html(data);
	});
});