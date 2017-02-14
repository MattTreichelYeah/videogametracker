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
	
	// Passing $console & $original_console is really hacky and bad
	function printrow($row, $console, $original_console) {
		// Should probably use Join?
		echo "<tr class='local-". max($row["multi_comp_local"], $row["multi_coop_local"]) ."'>
			<td class='title-cell'>"; 
			//if ($row["compilation_root"] != 0) { echo "<img src='../images/star.png' title='". $row["compilation_root"] ."'> "; } 
			echo $row["name"] ."</td>
			<td>"; 
			switch ($row["completion"]) { 
				case 1: echo "<img class='svg' src='../svg/unplayed.svg'>"; break;
				case 2: echo "<img class='svg' src='../svg/unfinished.svg'>"; break; 
				case 3: echo "<img class='svg' src='../svg/beaten.svg'>"; break; 
				case 4: echo "<img class='svg' src='../svg/completed2.svg'>"; break; 
			}
			echo "</td>
			<td>"; for ($i=1; $i <= $row["rating"]; $i++) { echo "<img class='rating svg' src='../svg/star.svg'>"; } echo "</td>
			<td>". $console; 
			if ($row["original_console"] != "0") echo " (". $original_console .")"; 
			echo "</td>
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
			<th class="name"><span class="th-desktop">Name</span><span class="th-mobile">&nbsp;</span></th>
			<th class="completion"><span class="th-desktop">Completion</span><span class="th-mobile">&nbsp;</span></th>
			<th class="rating"><span class="th-desktop">Rating</span><span class="th-mobile">&nbsp;</span></th>
			<th class="system"><span class="th-desktop">Console</span><span class="th-mobile">&nbsp;</span></th>
			<th class="local-comp"><img class='svg' src="../svg/local.svg"></th>
			<th class="local-coop"><img class='svg' src="../svg/local.svg"></th>
			<th class="LAN-comp"><img class='svg' src="../svg/LAN.svg"></th>
			<th class="LAN-coop"><img class='svg' src="../svg/LAN.svg"></th>
			<th class="online-comp"><img class='svg' src="../svg/online.svg"></th>
			<th class="online-coop"><img class='svg' src="../svg/online.svg"></th>
		</tr>
	</thead>
	<tbody>
		<?php
			while($row = mysqli_fetch_array($games)) {
				// This is really slowing things down
				$sql = "SELECT name_short FROM consoles WHERE id={$row["console"]}";
				$console = mysqli_fetch_array(mysqli_query($db, $sql))[0];
				$sql = "SELECT name_short FROM consoles WHERE id={$row["original_console"]}";
				$original_console = mysqli_fetch_array(mysqli_query($db, $sql))[0];
				printrow($row, $console, $original_console);
			}
		?>
	</tbody>
</table>

<script type="text/javascript" src="../js/game-table.js"></script>