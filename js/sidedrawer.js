$(function() {
	function mobileSidedrawer(side) {
		return function() { 
			$sidedrawer = $(`.sidedrawer-${side}`);
			$sidedrawer.toggleClass('active'); 
		};
	}

	function desktopSidedrawer(side) {
		return function() { 
			$('body').toggleClass(`hide-sidedrawer-${side}`); 
		};
	}

	// Table doesn't update as transition occurs, which sucks
	$(".sidedrawer").on("transitionend", function() {
		$("#games-table").DataTable().columns.adjust().responsive.recalc();
	});

	$('.sidedrawer-left-toggle-mobile').on('click', mobileSidedrawer("left"));
	$('.sidedrawer-left-toggle-desktop').on('click', desktopSidedrawer("left"));
	$('.sidedrawer-right-toggle-mobile').on('click', mobileSidedrawer("right"));
	$('.sidedrawer-right-toggle-desktop').on('click', desktopSidedrawer("right"));

	// Left & Right Arrow Keys
	$('body').keydown(function(event) {
		if (event.keyCode === 37) desktopSidedrawer("left")();
		else if (event.keyCode === 39) desktopSidedrawer("right")();
	});

	$(".content-wrapper").on('swipeleft', function(){ mobileSidedrawer("right")(); }).on('swiperight', function(){ mobileSidedrawer("left")(); });
	$(".sidedrawer-left").on('swipeleft',  function(){ mobileSidedrawer("left")(); });
	$(".sidedrawer-right").on('swiperight',  function(){ mobileSidedrawer("right")(); });

});