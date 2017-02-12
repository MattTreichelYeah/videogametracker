<?php
	include "dblogin.php";

	if ($_POST["consoleID"] == "-1") { 
		$sql = "SELECT * FROM games"; 
	} else if (!is_null($_POST["consoleChildren"])) {
		// Some redundancy here with the first criteria
		$sql = "SELECT * FROM games WHERE console={$_POST["consoleID"]}";
		foreach ($_POST["consoleChildren"] as $child) {
    		$sql .= " OR console={$child}";
    	}
	} else {
		$sql = "SELECT * FROM games WHERE console={$_POST["consoleID"]}";
	}
	$games = mysqli_query($db, $sql);
	
	function printrow($row) {
		echo 
		"<tr class='local-". $row["multi_comp_local"] ."'>
			<td class='title-cell'>"; if ($row["compilation_root"] != 0) { echo "<img src='../images/star.png' title='". $row["compilation_root"] ."'> "; } echo $row["name"] ."</td>
			<td>"; 
			switch ($row["completion"]) { 
				case 0: echo "<img src='../images/star.png'>"; break; 
				case 1: echo "<img src='../images/star.png'>"; break;
				case 2: echo "<img src='../images/star.png'>"; break; 
				case 3: echo "<img src='../images/star.png'>"; break; 
				case 4: echo "<img src='../images/star.png'>"; break; 
			}
			echo "</td>
			<td>"; for ($i=1; $i <= $row["rating"]; $i++) { echo "<img class='rating' src='../images/star.png'>"; } echo "</td>
			<td>". $row["console"]; if ($row["console_original"] != "") echo " (". $row["console_original"] .")"; echo "</td>
			<td>". $row["multi_comp_local"] ."</td>
			<td>". $row["multi_coop_local"] ."</td>
			<td>". $row["multi_comp_LAN"] ."</td>
			<td>". $row["multi_coop_LAN"] ."</td>
			<td>". $row["multi_comp_online"] ."</td>
			<td>". $row["multi_coop_online"] ."</td>
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
			<th class="local-comp"><img src="../images/multiplayer-comp-local.png"></th>
			<th class="local-coop"><img src="../images/multiplayer-comp-local.png"></th>
			<th class="LAN-comp"><img src="../images/multiplayer-comp-LAN.png"></th>
			<th class="LAN-coop"><img src="../images/multiplayer-comp-LAN.png"></th>
			<th class="online-comp"><img src="../images/multiplayer-comp-online.png"></th>
			<th class="online-coop"><img src="../images/multiplayer-comp-online.png"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			while($row = mysqli_fetch_array($games)) {
				printrow($row);
			}
		?>
	</tbody>
</table>

<script type="text/javascript" src="../js/game-table.js"></script>