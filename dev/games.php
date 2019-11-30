<?php
	include $_SERVER['DOCUMENT_ROOT']."/videogames/.database.php";

	// Apparently necessary on server
	// Though really means my query is badly optimized
	$sql = "SET SQL_BIG_SELECTS = 1; ";
	mysqli_query($db, $sql);

	$sql = "SELECT g.name, g.completion, g.rating, g.console, g.original_console, g.compilation_root, g.dlc_root, g.multi_note, g.multi_comp_local, g.multi_comp_LAN, g.multi_comp_LAN_max, g.multi_coop_local, g.multi_coop_LAN, g.multi_coop_LAN_max, g.xbox_one_compat,
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

	$whereConsole = "";
	$groupBy = "";
	if ($_POST["consoleID"] != "-1") { 
		if (isset($_POST["consoleChildren"])) {
			// Some redundancy here with the first criteria
			$whereConsole .= " WHERE (g.console = {$_POST["consoleID"]}";
			foreach ($_POST["consoleChildren"] as $child) {
	    		$whereConsole .= " OR g.console = {$child}";
	    	}
		} else {
			$whereConsole .= " WHERE (g.console = {$_POST["consoleID"]}";
		}

		if(isset($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$whereConsole .= ") AND (gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$whereConsole .= " OR gt.tagid = {$tag}";
	    	}
	    	$whereConsole .= ")";
	    	$groupBy .= " GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
	    	$whereConsole .= ")";
	    	$groupBy .= " GROUP BY g.id";
		}
	} else {
		if(isset($_POST["tagIDs"]) && $_POST["tagIDs"][0] != "-1") {
			$whereConsole .= " WHERE (gt.tagid = {$_POST["tagIDs"][0]}"; 
			foreach ($_POST["tagIDs"] as $tag) {
	    		$whereConsole .= " OR gt.tagid = {$tag}";
	    	}
	    	$whereConsole .= ")";
	    	$groupBy .= " GROUP BY g.id HAVING COUNT(*) = " . count($_POST["tagIDs"]);
		} else {
	    	$groupBy .= " GROUP BY g.id";
		}
	}

	$sql .= $whereConsole . $groupBy;

	//echo $sql;

	$games = mysqli_query($db, $sql);

	function playerRange($playercount) {
		$range = "one";
		if (2 <= $playercount && $playercount <= 3) $range = "low";
		else if ($playercount == 4) $range = "medium"; 
		else if (5 <= $playercount && $playercount <= 6) $range = "high"; 
		else if (7 <= $playercount) $range = "extreme";
		return $range;
	}
	
	function printrow($row) {
		echo "<tr class='local-";
		$playercount = max($row["multi_comp_local"], $row["multi_coop_local"], $row["multi_comp_LAN"], $row["multi_coop_LAN"]);
		echo playerRange($playercount) . "'>
			<td><div class='title-cell'>"; 
			if ($row["compilation_root"] != 0 && $row["compilation_root"] != -1) { echo "<img class='svg' src='/videogames/svg/arrow.svg' title='" . $row["compilation_name"] . "' alt='Compilation Indicator'><span class='sort-data'>" . $row["compilation_name"] . "</span> "; } 
			else if ($row["dlc_root"] != 0) { echo "<img class='svg' src='/videogames/svg/arrow.svg' title='" . $row["dlc_name"] . "' alt='DLC Indicator'><span class='sort-data'>" . $row["dlc_name"] . "</span> "; } 
			echo $row["name"] ."</div></td>
			<td>"; 
			switch ($row["completion"]) { 
				case NULL: break;
				case 0: echo "<span class='sort-data'>" . $row["completion"] . "</span><img class='svg' src='/videogames/svg/endless.svg' title='Endless' alt='Endless'>"; break;
				case 1: echo "<span class='sort-data'>" . $row["completion"] . "</span><img class='svg' src='/videogames/svg/unplayed.svg' title='Unplayed' alt='Unplayed'>"; break;
				case 2: echo "<span class='sort-data'>" . $row["completion"] . "</span><img class='svg' src='/videogames/svg/unfinished.svg' title='Unfinished' alt='Unfinished'>"; break; 
				case 3: echo "<span class='sort-data'>" . $row["completion"] . "</span><img class='svg' src='/videogames/svg/beaten.svg' title='Beaten' alt='Beaten'>"; break; 
				case 4: echo "<span class='sort-data'>" . $row["completion"] . "</span><img class='svg' src='/videogames/svg/completed2.svg' title='Completed' alt='Completed'>"; break; 
			}
			echo "</td>
			<td>"; for ($i=1; $i <= $row["rating"]; $i++) { echo "<span class='sort-data'>" . $row["rating"] . "</span><img class='rating svg' src='/videogames/svg/star.svg' alt='Star'>"; } echo "</td>
			<td>". $row["console_name"]; 
			if ($row["original_console"] != "") echo " (". $row["console_original_name"] .")"; 
			echo "</td>
			<td>". max($row["multi_comp_local"], $row["multi_coop_local"]) ."</td>
			<td>". max($row["multi_comp_LAN"], $row["multi_coop_LAN"]);
			if ($row["multi_comp_LAN_max"] != "" || $row["multi_coop_LAN_max"] != "") echo "<span title='Theoretical ". max($row["multi_comp_LAN_max"], $row["multi_coop_LAN_max"]) ."'>*</span>";
			echo "</td><td>". $row["multi_note"] ."</td>
		</tr>";
	}

?>

<div class="games-table-detail">

	<h3>Now Playing:</h3>

	<?php 

		$sql = "SELECT g.name, g.now_playing, g.dlc_root,
			c2.name_short AS console_name,
			g2.name AS dlc_name
			FROM games AS g
			LEFT JOIN consoles AS c ON g.console = c.id
			LEFT JOIN consoles AS c2 ON c2.id = c.console_root
			LEFT JOIN games AS g2 ON g.dlc_root = g2.id
			LEFT JOIN games_tags AS gt ON g.id = gt.gameid
			LEFT JOIN tags AS t ON t.id = gt.tagid";

		if ($whereConsole != "") $sql .= $whereConsole . " AND g.now_playing = 1 " . $groupBy;
		else $sql .= " WHERE g.now_playing = 1" . $groupBy;

		$sql .= " ORDER BY g.name";

		$now_playing = mysqli_query($db, $sql);

		if (mysqli_num_rows($now_playing) != 0) {
			while($row = mysqli_fetch_array($now_playing)) {
				echo "<span class='now-playing'>"; 
				if ($row["dlc_root"] != 0) { echo "<img class='svg' src='/videogames/svg/arrowBlack.svg' title='" . $row["dlc_name"] . "' alt='DLC Indicator'>"; } 
				echo $row["name"] . " (" . $row["console_name"] . ")</span>";
			}
		} else {
			echo "None!";
		}

	?>
</div>
<div>
	<?php include 'components/toggle.html' ?>

	<table id="games-table" class="games-table hover compact order-column nowrap" width="100%">
		<thead>
			<tr>
				<!--Class Names are for DataTables Column Visibility Control-->
				<th class="name"><span class="th-desktop">Name</span><span class="th-mobile">&nbsp;</span></th>
				<th class="completion"><span class="th-desktop">Completion</span><span class="th-mobile">&nbsp;</span></th>
				<th class="rating"><span class="th-desktop">Rating</span><span class="th-mobile">&nbsp;</span></th>
				<th class="console"><span class="th-desktop">Console</span><span class="th-mobile">&nbsp;</span></th>
				<th class="local-comp"><img class='svg' src="/videogames/svg/local.svg" title="Local Player Count" alt="Local Player Count"></th>
				<th class="link-comp"><img class='svg' src="/videogames/svg/link.svg" title="System Link Player Count" alt="System Link Player Count"></th>
				<th class="multi-note"><span class="th-desktop">Multiplayer Note</span><span class="th-mobile">&nbsp;</span></th>
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