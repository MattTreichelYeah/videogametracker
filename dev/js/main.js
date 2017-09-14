$(document).ready(function () {

	// Post Function that also handles loading icons
	function getData(consoleID, consoleChildren, tagIDs) {
		
		var loading = {
			"stats": false, 
			"games": false, 
			"finish": function(component) { 
				// Initial Loading Icons are initially visible and hidden seperately (and stay hidden)
				$(`.${component} .loading-icon`).addClass("hidden");
				// Sidebar Loading Icons are initially hidden and made invisible when both sections done
				if (component === "stats") this.stats = true;
				else if (component === "games") this.games = true;
				
				if (this.stats && this.games) {
					$(".sidebar .loading-icon").removeClass("visible");					
				}
			}
		};

		// Pass Data
		$.post("stats.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			$("#stats-content").html(data);
			updateStats();
			loading.finish("stats");
		});
		$.post("games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			$("#games-content").html(data);
			initializeDataTable();
			loading.finish("games");
		});
	}

	// *** Initial Loading ***

	getData(-1, [-1], [-1]);

	function mobileSidebar(side) {
		return function() { 
			$sidebar = $(`.sidebar-${side}`);
			$sidebar.toggleClass('active'); 
		};
	}

	function desktopSidebar(side) {
		return function() { 
			$('body').toggleClass(`sidebar-hide-${side}`); 
		};
	}

	// Table doesn't update as transition occurs, which sucks
	// Don't need this for mobile since sidebar doesn't move table, kills performance
	// The detection method for mobile is stupid
	$(".sidebar").on("transitionend", function() {
		if ($(".sidebar-toggle-l-m:visible").length === 0) {
			$("#games-table").DataTable().columns.adjust().responsive.recalc();
		}
	});

	$('.sidebar-toggle-l-m').on('click', mobileSidebar("l"));
	$('.sidebar-toggle-l-d').on('click', desktopSidebar("l"));
	$('.sidebar-toggle-r-m').on('click', mobileSidebar("r"));
	$('.sidebar-toggle-r-d').on('click', desktopSidebar("r"));

	// Left & Right Arrow Keys
	$('body').keydown(function(event) {
		if (event.keyCode === 37) desktopSidebar("l")();
		else if (event.keyCode === 39) desktopSidebar("r")();
	});

	$(".content").on('swipeleft', mobileSidebar("r")).on('swiperight', mobileSidebar("l"));
	$(".sidebar-l").on('swipeleft', mobileSidebar("l"));
	$(".sidebar-r").on('swiperight', mobileSidebar("r"));

	// Functions?

	$('.dropdown').click(function(event) {
		event.preventDefault();
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

		if ($(this).next().is("a")) {
			$(".sidebar-l .loading-icon").insertAfter($(this).next("a")).addClass("visible");
		} else {
			$(".sidebar-l .loading-icon").appendTo($(this)).addClass("visible");			
		}

		getData(consoleID, consoleChildren, tagIDs);
	});

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

		$(".sidebar-r .loading-icon").appendTo($(this)).addClass("visible");

		getData(consoleID, consoleChildren, tagIDs);
	});

	function initializeDataTable() {
	    var table = $('#games-table').DataTable({
			"pageLength": 150,
			"pagingType": "simple",
			"info": false,
			"responsive": true,
			"dom": 'frtip', /*Ordering of Table Elements, B needed for buttons*/
	        "columnDefs": [
	        	{ "targets": ["rating","completion","local-comp","link-comp","multi-note"], "orderSequence": ["desc", "asc"] },
	        	{ "targets": ["rating","completion","console","local-comp","link-comp","multi-note"], "searchable": false }
	        ]
		});

		$("#singlemulti").on("change", function() {
			var single = this.checked;
			// This isn't really single view, but makes sense to toggle initial view on/off
			table.columns([".completion",".rating",".console"]).visible(single, false);
			// Relying on indexes is fragile
			if (single) table.order([0, 'asc']);
			else table.order([4, 'desc'], [5, 'desc']);
			table.columns.adjust().draw();
			$("#games-table").toggleClass("multi-active"); //enable highlighting
		});

		$(".dataTables_paginate").on("click", function(event) {
		    $("#games-table").DataTable().columns.adjust().responsive.recalc();
		});
	}

	function updateStats() {
		$('.stats-table tr td:nth-child(3)').each(function(index, element) {
			var percent = $(element).text();
		    $(element).css("background-size", percent + " 100%");
		    // Mobile visible
		    $(element).prev().prev().css("background-size", percent + " 100%");
		});
	}

});