function updateStats() {
	$('.stats-table tr td:nth-child(3)').each(function(index, element) {
		var percent = $(element).text();
	    $(element).css("background-size", percent + " 100%");
	    // Mobile visible
	    $(element).prev().prev().css("background-size", percent + " 100%");
	});
};