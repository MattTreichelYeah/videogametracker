$(document).ready(function () {

	//Initial Loading

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
		updateStats();
		loading.finish("stats");
	});
	$.post("games.php", {"consoleID": "-1", "tagIDs": tagIDs}, function(data) {
		$("#games").html(data);
		initializeDataTable();
		loading.finish("games");
	});

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
	// Don't need this for mobile since sidebar doesn't move table, kills performance
	// The detection method for mobile is stupid
	$(".sidedrawer").on("transitionend", function() {
		if ($(".sidedrawer-left-toggle-mobile:visible").length === 0) {
			$("#games-table").DataTable().columns.adjust().responsive.recalc();
		}
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

	$(".content-wrapper").on('swipeleft', mobileSidedrawer("right")).on('swiperight', mobileSidedrawer("left"));
	$(".sidedrawer-left").on('swipeleft', mobileSidedrawer("left"));
	$(".sidedrawer-right").on('swiperight', mobileSidedrawer("right"));

	// Functions?

	$('.dropdown').click(function(event) {
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

		if ($(this).next().is("img")) {
			$(".sidedrawer-left .loading-icon").insertAfter($(this).next("img")).removeClass("hidden");
		} else {
			$(".sidedrawer-left .loading-icon").appendTo($(this)).removeClass("hidden");			
		}

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
			$("#stats").html(data);
			updateStats();
			loading.finish("stats");
		});
		$.post("games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			$("#games").html(data);
			initializeDataTable();
			loading.finish("games");
		});
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
			$("#stats").html(data);
			updateStats();
			loading.finish("stats");
		});
		$.post("games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			$("#games").html(data);
			initializeDataTable();
			loading.finish("games");
		});
	});

	function initializeDataTable() {
	    var table = $('#games-table').DataTable({
			//"paging": false,
			"pageLength": 150,
			"pagingType": "simple",
			"info": false,
			"responsive": true,
			// "columnDefs": [
			//     { "targets": ["rating","completion"], "visible": false, "searchable": false }
			// ],
			"dom": 'Bfrtip', /*Ordering of Table Elements, B needed for buttons*/
			"buttons": [
				// {
	   //              text: 'Single/Multi',
	   //              action: function ( e, dt, node, config ) {
	   //              	var single = dt.column(".completion").visible();
	   //              	console.log(single);
	   //                  dt.columns([".local-coop",".local-comp",".LAN-coop",".LAN-comp",".online-coop",".online-comp"]).visible(single);
	   //                  dt.columns([".completion",".rating",".system"]).visible(!single);
	   //              }
	   //          },
	            //'colvis',
	            //'print'
	        ],
	        "columnDefs": [
	        	{ "targets": ["rating","completion","local-comp","LAN-comp","multi-note"], "orderSequence": ["desc", "asc"] },
	        	{ "targets": ["rating","completion","system","local-comp","LAN-comp","multi-note"], "searchable": false }
	        ]
		});

		$("#singlemulti").on("change", function() {
			var single = this.checked;
			// This isn't really single view, but makes sense to toggle initial view on/off
			table.columns([".completion",".rating",".system"]).visible(single, false);
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