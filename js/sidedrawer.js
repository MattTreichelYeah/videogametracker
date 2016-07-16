$(function() {
	var $body = $('body'),
		$sidedrawer = $('#sidedrawer');

	function mobileSidedrawer() {
		$sidedrawer.toggleClass('active');
	}

	function desktopSidedrawer() {
		$body.toggleClass('hide-sidedrawer');
	}

	$('.sidedrawer-toggle-mobile').on('click', mobileSidedrawer);
	$('.sidedrawer-toggle-desktop').on('click', desktopSidedrawer);
});