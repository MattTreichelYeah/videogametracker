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

					// == why
					let consoleName = (consoleID != "-1") ? $(`.sidebar-l [data-id='${consoleID}']`).attr("data-name") : "";
					consoleName = consoleName.replace(/ /g, "/");
					let searchValue = "";
					if (initial) {
						searchValue = window.location.search;
						initial = false;
					}
					window.history.replaceState({}, "", `/videogames/${consoleName}${searchValue}${window.location.hash}`);			
				}
			}
		};

		// Pass Data
		$.post("/videogames/stats.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			$("#stats-content").html(data);
			updateStats();
			loading.finish("stats");
		});
		$.post("/videogames/games.php", {"consoleID": consoleID, "consoleChildren": consoleChildren, "tagIDs": tagIDs}, function(data) {
			// Retrieve current state if exists
			let multiToggle = $("#singlemulti");
			let multi;
			if (multiToggle.length !== 0) {
				multi = !multiToggle.prop("checked");
			} else {
				multi = parseMultiURL();
			}

			// Setup HTML
			$("#games-content").html(data);
			initializeDataTable().then(() => {
				setSearch();
				if (multi) {
					setMultiplayerView(true);
					multiToggle = $("#singlemulti").prop("checked", false);
				}
				loading.finish("games");
			});
			console.log('unblocked?');
		});
	}

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

	// The detection method for mobile is stupid
	function mobileSidebarActive() {
		return $(".sidebar-toggle-l-m:visible").length !== 0;
	}

	// Table doesn't update as transition occurs, which sucks
	// Don't need this for mobile since sidebar doesn't move table, kills performance
	$(".sidebar").on("transitionend", function() {
		if (!mobileSidebarActive()) {
			$("#games-table").DataTable().columns.adjust().responsive.recalc();
		}
	});

	$('.sidebar-toggle-l-m').on('click', mobileSidebar("l"));
	$('.sidebar-toggle-l-d').on('click', desktopSidebar("l"));
	$('.sidebar-toggle-r-m').on('click', mobileSidebar("r"));
	$('.sidebar-toggle-r-d').on('click', desktopSidebar("r"));

	// Left & Right Arrow Keys
	$('body').keydown(function(event) {
		if (event.keyCode === 37) {
			if (!mobileSidebarActive()) desktopSidebar("l")();
			else mobileSidebar("l")();
		} else if (event.keyCode === 39) {
			if (!mobileSidebarActive()) desktopSidebar("r")();
			else mobileSidebar("r")();
		}
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

	function setMultiplayerView(multi) {
		let table = $('#games-table').DataTable({ "retrieve": true });
		// This isn't really single view, but makes sense to toggle initial view on/off
		table.columns([".completion",".rating",".console"]).visible(!multi, false);
		if (!multi) {
			table.order([0, 'asc']); // Relying on indexes is fragile
			$("#games-table").removeClass("multi-active"); //enable highlighting
			window.history.replaceState({}, "", `${window.location.pathname}${window.location.search}`);
		} else {
			table.order([4, 'desc'], [5, 'desc']);
			$("#games-table").addClass("multi-active");
			window.history.replaceState({}, "", `${window.location.pathname}${window.location.search}#multi`);
		}
		table.columns.adjust().draw();
	}

	let initial = true;
	function setSearch() {
		if (initial) {
			let table = $('#games-table').DataTable({ "retrieve": true });
			table.search(parseSearchURL(decodeURI(window.location.href))).draw();
			// initial = false; // lol actually flip off after URL is parsed for first time
		}
	}

	function initializeDataTable() {
		// await (() => {
			return new Promise(resolve => {
				setTimeout(() => {
				    let table = $('#games-table').DataTable({
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

					table.on('search.dt', function () {
						let searchValue = $('.dataTables_filter input').val();
						if (searchValue !== "") {
							searchValue = "?" + searchValue.replace(/#/g, "").replace(/ /g, "_");
						}
						window.history.replaceState({}, "", `${window.location.pathname}${searchValue}${window.location.hash}`);
					});

					$("#singlemulti").on("change", function() {
						setMultiplayerView(!this.checked);
					});

					$(".dataTables_paginate").on("click", function(event) {
					    $("#games-table").DataTable().columns.adjust().responsive.recalc();
					});
					
					console.log("done processing")
					return resolve();
				}, 0);
			});
		// });
	}

	function updateStats() {
		$('.stats-table tr td:nth-child(3)').each(function(index, element) {
			var percent = $(element).text();
		    $(element).css("background-size", percent + " 100%");
		    // Mobile visible
		    $(element).prev().prev().css("background-size", percent + " 100%");
		});
	}

	// *** Initial Loading ***

	let mainConsole, subConsole;
	[mainConsole, subConsole] = parseConsoleURL(window.location.href);
	initialDataCall(mainConsole, subConsole);

	function parseConsoleURL(URL) {
		URL = URL.replace(window.location.hash, "").split("?")[0];
		let queryString = URL.split("videogames/")[1];
		return queryString.split("/");
	}

	function parseSearchURL(URL) {
		URL = URL.replace(window.location.hash, "");
		let queryString = URL.split("?")[1];
		if (queryString) return queryString.replace(/_/g," ");
		else return "";
	}

	function parseMultiURL(URL) {
		return window.location.hash === "#multi";
	}

	function initialDataCall(mainConsole, subConsole) {
		if (!mainConsole) {
			getData(-1, [-1], [-1]); // Default "All Games"
		} else {
			// Lazy Hacky
			// Expects short name for mainConsole, hyphenated long name for subConsole
			try {
				if (!subConsole) {
					$(`.sidebar-l [data-name='${mainConsole}']`)[0].click();
				} else {
					$(`.sidebar-l [data-name='${mainConsole} ${subConsole}']`)[0].click();
					$(`.sidebar-l [data-name='${mainConsole} ${subConsole}']`).parent().parent().siblings(".dropdown")[0].click();
				}
			} catch(error) {
				getData(-1, [-1], [-1]); // Default "All Games"
			}
		}
	}

});