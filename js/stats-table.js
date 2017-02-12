function updateStats() {
	$('.stats-table tr td:nth-child(3)').each(function(index, element) {
		var percent = $(element).text();
		$(element).css("background", "url('../images/stat-percent-background.png') no-repeat");
	    $(element).css("background-size", percent + " 100%");
	});
};