$(document).ready(function () {
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
        	{ "targets": ["rating","completion","local-coop","local-comp","LAN-coop","LAN-comp","online-coop","online-comp"], "orderSequence": ["desc", "asc"] }
        ]
	});

	$("#singlemulti").on("change", function() {
		var single = this.checked;
		// false means don't redraw each time
        table.columns([".local-coop",".local-comp",".LAN-coop",".LAN-comp",".online-coop",".online-comp"]).visible(!single, false);
		table.columns([".completion",".rating",".system"]).visible(single, false);
		table.columns.adjust().draw();
	});

	$(".dataTables_paginate").on("click", function(event) {
	    $("#games-table").DataTable().columns.adjust().responsive.recalc();
	});
});