<?php
	include "dblogin.php";

	// Apparently necessary on server
	// Though really means my query is badly optimized
	$sql = "SET SQL_BIG_SELECTS = 1; ";
	mysqli_query($db, $sql);

	$sql = "SELECT g.*, 
		c.name_short AS console_name, 
		c2.name_short AS console_original_name, 
		g2.name AS compilation_name, 
		g3.name AS dlc_name
		FROM games AS g
		LEFT JOIN consoles AS c ON g.console = c.id
		LEFT JOIN consoles AS c2 ON g.original_console = c2.id
		LEFT JOIN games AS g2 ON g.compilation_root = g2.id
		LEFT JOIN games AS g3 ON g.dlc_root = g3.id
		LEFT JOIN games_tags AS gt ON g.id = gt.gameid
		LEFT JOIN tags AS t ON t.id = gt.tagid";

	if ($_POST["consoleID"] != "-1") { 
		if (!is_null($_POST["consoleChildren"])) {
			// Some redundancy here with the first criteria
			$sql .= " WHERE (g.console = {$_POST["consoleID"]}";
			foreach ($_POST["consoleChildren"] as $child) {
	    		$sql .= " OR g.console = {$child}";
	    	}
		} else {
			$sql .= " WHERE (g.console = {$_POST["consoleID"]}";
		}

		if(!is_null($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$sql .= ") AND (gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$sql .= " OR gt.tagid = {$tag}";
	    	}
	    	$sql .= ") GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
			$sql .= ") GROUP BY g.id";
		}
	} else {
		if(!is_null($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$sql .= " WHERE gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$sql .= " OR gt.tagid = {$tag}";
	    	}
	    	$sql .= " GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
			$sql .= " GROUP BY g.id";
		}
	}

	$games = mysqli_query($db, $sql);
	
	function printrow($row) {
		// Should probably use Join?
		echo "<tr class='local-". max($row["multi_comp_local"], $row["multi_coop_local"]) ."'>
			<td class='title-cell'>"; 
			if ($row["compilation_root"] != 0 && $row["compilation_root"] != -1) { echo "<img class='svg' src='../svg/arrow.svg' title='" . $row["compilation_name"] . "'><span class='filter-data'>" . $row["compilation_name"] . "</span> "; } 
			else if ($row["dlc_root"] != 0) { echo "<img class='svg' src='../svg/arrow.svg' title='" . $row["dlc_name"] . "'><span class='filter-data'>" . $row["dlc_name"] . "</span> "; } 
			echo $row["name"] ."</td>
			<td>"; 
			switch ($row["completion"]) { 
				case 1: echo "<span class='filter-data'>" . $row["completion"] . "</span><img class='svg' src='../svg/unplayed.svg' title='Unplayed'>"; break;
				case 2: echo "<span class='filter-data'>" . $row["completion"] . "</span><img class='svg' src='../svg/unfinished.svg' title='Unfinished'>"; break; 
				case 3: echo "<span class='filter-data'>" . $row["completion"] . "</span><img class='svg' src='../svg/beaten.svg' title='Beaten'>"; break; 
				case 4: echo "<span class='filter-data'>" . $row["completion"] . "</span><img class='svg' src='../svg/completed2.svg' title='Completed'>"; break; 
			}
			echo "</td>
			<td>"; for ($i=1; $i <= $row["rating"]; $i++) { echo "<span class='filter-data'>" . $row["rating"] . "</span><img class='rating svg' src='../svg/star.svg'>"; } echo "</td>
			<td>". $row["console_name"]; 
			if ($row["original_console"] != "0") echo " (". $row["console_original_name"] .")"; 
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

<div class="games-table-detail">

	<h3>Now Playing:&nbsp;&nbsp;</h3>

	<?php 

		$sql = "SELECT g.name, c.name_short AS console_name
			FROM games AS g
			LEFT JOIN consoles AS c ON g.console = c.id
			WHERE now_playing = 1";

		$now_playing = mysqli_query($db, $sql);

		while($row = mysqli_fetch_array($now_playing)) {
			echo "<span class='now-playing'>" . $row["name"] . " (" . $row["console_name"] . ")</span>";
		}

	?>
</div>

<div>
	<table id="games-table" class="hover compact order-column nowrap" width="100%"> <!--nowrap is useful attribute-->
		<thead>
			<tr>
				<!--Class Names are for Datatables Column Visibility Control-->
				<th class="name"><span class="th-desktop">Name</span><span class="th-mobile">&nbsp;</span></th>
				<th class="completion"><span class="th-desktop">Completion</span><span class="th-mobile">&nbsp;</span></th>
				<th class="rating"><span class="th-desktop">Rating</span><span class="th-mobile">&nbsp;</span></th>
				<th class="system"><span class="th-desktop">Console</span><span class="th-mobile">&nbsp;</span></th>
				<th class="local-comp"><img class='svg' src="../svg/local.svg" title="Local Competitive Players"></th>
				<th class="local-coop"><img class='svg' src="../svg/local.svg" title="Local Co-op Players"></th>
				<th class="LAN-comp"><img class='svg' src="../svg/LAN.svg" title="LAN Competitive Players"></th>
				<th class="LAN-coop"><img class='svg' src="../svg/LAN.svg" title="LAN Co-op Players"></th>
				<th class="online-comp"><img class='svg' src="../svg/online.svg" title="Online Competitive Players"></th>
				<th class="online-coop"><img class='svg' src="../svg/online.svg" title="Online Co-op Players"></th>
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
</div>

<script type="text/javascript" src="../js/game-table.js"></script>