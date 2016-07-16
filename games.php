<?php
	include "dblogin.php";

	if ($_POST["console"] == "All Games") { 
		$sqlgames = "SELECT * FROM games WHERE compilation IS NULL ORDER BY name"; 
		$sqlcompilations = "SELECT DISTINCT compilation FROM games WHERE compilation IS NOT NULL";
	} else {
		$sqlgames = "SELECT * FROM games WHERE console='" . $_POST["console"] . "' AND compilation IS NULL ORDER BY name";
		$sqlcompilations = "SELECT DISTINCT compilation FROM games WHERE console='" . $_POST["console"] . "' AND compilation IS NOT NULL";
	}
	$compilations = mysqli_query($db, $sqlcompilations);
	$compilations = mysqli_fetch_all($compilations);
	$compilations = array_column($compilations,0);
	$games = mysqli_query($db, $sqlgames);
	
	function printrow($row) {
		echo 
		"<tr class='local-". $row["multiplayer_comp_local"] ."'>
			<td class='title-cell'>"; if ($row["compilation"] != NULL) { echo "<img src='images/star.png' title='". $row["compilation"] ."'> "; } echo $row["name"] ."</td>
			<td>"; 
			switch ($row["completion_status"]) { 
				case 0: echo "<img src='images/completion-null.png'>"; break; 
				case 1: echo "<img src='images/completion-unfinished.png'>"; break; 
				case 2: echo "<img src='images/completion-beat.png'>"; break; 
				case 3: echo "<img src='images/completion-complete.png'>"; break; 
			}
			echo "</td>
			<td>"; for ($i=0; $i <= $row["rating"]; $i++) { echo "<img class='rating' src='images/star.png'>"; } echo "</td>
			<td>". $row["console"]; if ($row["console_original"] != "") echo " (". $row["console_original"] .")"; echo "</td>
			<td>". $row["multiplayer_comp_local"] ."</td>
			<td>". $row["multiplayer_coop_local"] ."</td>
			<td>". $row["multiplayer_comp_LAN"] ."</td>
			<td>". $row["multiplayer_coop_LAN"] ."</td>
			<td>". $row["multiplayer_comp_online"] ."</td>
			<td>". $row["multiplayer_coop_online"] ."</td>
		</tr>";
	}
?>

<table id="games-table" class="hover compact order-column nowrap" width="100%"> <!--nowrap is useful attribute-->
	<thead>
		<tr>
			<!--Class Names are for Datatables Column Visibility Control-->
			<th class="name">Name</th>
			<th class="completion">Completion</th>
			<th class="rating">Rating</th>
			<th class="system">System</th>
			<th class="local-comp"><img src="images/multiplayer-comp-local.png"></th>
			<th class="local-coop"><img src="images/multiplayer-comp-local.png"></th>
			<th class="LAN-comp"><img src="images/multiplayer-comp-LAN.png"></th>
			<th class="LAN-coop"><img src="images/multiplayer-comp-LAN.png"></th>
			<th class="online-comp"><img src="images/multiplayer-comp-online.png"></th>
			<th class="online-coop"><img src="images/multiplayer-comp-online.png"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			while($row = mysqli_fetch_array($games)) {
				printrow($row);
				//Sub Games:
				if(in_array($row["name"], $compilations)) {
					if ($_POST["console"] == "All Games") { 
						$sqlsubgames = "SELECT * FROM games WHERE compilation='". $row["name"] ."'";
					} else {
						$sqlsubgames = "SELECT * FROM games WHERE console='" . $_POST["console"] . "' AND compilation='". $row["name"] ."'";
					}
					$subgames = mysqli_query($db, $sqlsubgames);
					while($subrow = mysqli_fetch_array($subgames)) {
						printrow($subrow);
					}
				}
			}
		?>
	</tbody>
</table>

<script>
$(document).ready(function () {
    var table = $('#games-table').DataTable({
		"paging": false,
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
            'colvis',
            'print'
        ]
	});

	$("#singlemulti").on("change", function() {
		var single = this.checked;
        table.columns([".local-coop",".local-comp",".LAN-coop",".LAN-comp",".online-coop",".online-comp"]).visible(!single, false);
		table.columns([".completion",".rating",".system"]).visible(single, false);
		table.columns.adjust().draw();
	});
});
</script>