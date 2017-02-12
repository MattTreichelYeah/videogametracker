$(document).ready(function(event) { //Default View
	$.post("stats.php", {"consoleID": "-1"}, function(data) {
		$("#stats").html(data);
	});
	$.post("games.php", {"consoleID": "-1"}, function(data) {
		$("#games").html(data);
	});
});