$(document).ready(function(event) { //Default View
	var tagIDs = ["-1"];
	$.post("stats.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$(".content-wrapper .loading-icon").hide();
		$("#stats").html(data);
	});
	$.post("games.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$(".content-wrapper .loading-icon").hide();
		$("#games").html(data);
	});
});