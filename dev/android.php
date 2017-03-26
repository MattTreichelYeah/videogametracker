<?php

	// Error Display
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Database Functions

	function create($db) {
		if (isset($_POST['name']) && 
			isset($_POST['console']) &&
			isset($_POST['completion']) &&
			isset($_POST['rating']) &&
			isset($_POST['playercount']) &&
			isset($_POST['multinote'])) {

			$console = getConsoleId($db, $_POST['console']);

			$statement = mysqli_prepare($db, "INSERT INTO games (name, console, completion, rating, multi_comp_local, multi_note, compilation_root, original_console, multi_coop_local)
						VALUES (?, ?, ?, ?, ?, ?, 0, 0, 1)");
			mysqli_stmt_bind_param($statement, "ssssss", $_POST['name'], $console, $_POST['completion'], $_POST['rating'], $_POST['playercount'], $_POST['multinote']);
			$result = mysqli_stmt_execute($statement);

			// Return Result to Android
			if ($result != false) {
				echo "Success!";   
			} else {
				echo "Failed :(";   
			}
		}
	}

	function update($db) {
		if (isset($_POST['id']) &&
			isset($_POST['name']) && 
			isset($_POST['console']) &&
			isset($_POST['completion']) &&
			isset($_POST['rating']) &&
			isset($_POST['playercount']) &&
			isset($_POST['multinote'])) {

			$console = getConsoleId($db, $_POST['console']);

			$statement = mysqli_prepare($db, "UPDATE games SET name=?, console=?, completion=?, rating=?, multi_comp_local=?, multi_note=? WHERE id=?");
			mysqli_stmt_bind_param($statement, "sssssss", $_POST['name'], $console, $_POST['completion'], $_POST['rating'], $_POST['playercount'], $_POST['multinote'], $_POST['id']);
			$result = mysqli_stmt_execute($statement);

			// Return Result to Android
			if ($result != false) {
				echo "Success!";   
			} else {
				echo "Failed :(";   
			}
		}
	}

	function delete($db) {
		if (isset($_POST['id'])) {

			$statement = mysqli_prepare($db, "DELETE FROM games 
				 WHERE id=?");
			mysqli_stmt_bind_param($statement, "s", $_POST['id']);
			$result = mysqli_stmt_execute($statement);

			// Return Result to Android
			if ($result != false) {
				echo "Success!";   
			} else {
				echo "Failed :(";   
			}
		}
	}

	function listgames($db) {
		$query = "SELECT g.id, g.name, c.name AS console_name, g.multi_comp_local 
			FROM games AS g 
			LEFT JOIN consoles AS c ON g.console = c.id";

		$result = mysqli_query($db, $query);

		// Return Result to Android
		if ($result != false) {
			while($row = mysqli_fetch_array($result)) {
				echo "{$row['id']};{$row['name']};{$row['console_name']};{$row['multi_comp_local']}\n";
			} 
		} else {
			echo "Failed :(";   
		}
	}

	function consoles($db) {
		$query = "SELECT name FROM consoles WHERE owned = 1";

		$result = mysqli_query($db, $query);

		// Return Result to Android
		if ($result != false) {
			while($row = mysqli_fetch_array($result)) {
				echo "{$row['name']}\n";
			} 
		} else {
			echo "Failed :(";   
		}
	}

	function game($db) {
		if (isset($_POST["id"])) {

			$statement = mysqli_prepare($db, "SELECT g.id, g.name, c.name AS console_name, g.completion, g.rating, g.multi_comp_local, g.multi_note
				FROM games AS g 
				LEFT JOIN consoles AS c ON g.console = c.id
				WHERE g.id = ?");
			mysqli_stmt_bind_param($statement, "s", $_POST['id']);
			$result = mysqli_stmt_execute($statement);
			mysqli_stmt_bind_result($statement, $id, $name, $console_name, $completion, $rating, $multi_comp_local, $multi_note);

			// Return Result to Android
			if ($result != false) {
				while (mysqli_stmt_fetch($statement)) {
					echo "{$id};{$name};{$console_name};{$completion};{$rating};{$multi_comp_local};{$multi_note}";
				} 
			} else {
				echo "Failed :(";   
			}
		}
	}

	function stats1($db) {
		$ALPHABET = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

		$query = "SELECT ";
		foreach ($ALPHABET as $letter) {
			$lowercase = strtolower($letter);
			$query .= "COUNT(CASE WHEN (name LIKE '{$letter}%' OR name LIKE '{$lowercase}%') THEN 1 END) AS {$letter}";
			if ($letter != "Z") $query .= ", ";
		}
		$query .= " FROM games";

		$result = mysqli_query($db, $query);

		// Return Result to Android
		if ($result != false) {
			echo "1\n";
			while ($row = mysqli_fetch_array($result)) {
				foreach($row as $letter) {
					echo "{$letter}\n";
				}
			} 
		} else {
			echo $query;   
		}
	}

	function stats2($db) {

		$query = "SELECT (completion * rating) AS power FROM games ORDER BY power";

		$result = mysqli_query($db, $query);

		// Return Result to Android
		if ($result != false) {
			echo "2\n";
			while ($row = mysqli_fetch_array($result)) {
				echo "{$row["power"]}\n";
			} 
		} else {
			echo $query; 
		}
	}

	// Utility Function - get console ID from Name

	function getConsoleID($db, $console) {
		$statement = mysqli_prepare($db, "SELECT id FROM consoles WHERE name = ?");
		mysqli_stmt_bind_param($statement, "s", $console);
		mysqli_stmt_execute($statement);
		mysqli_stmt_bind_result($statement, $console);
		mysqli_stmt_fetch($statement);
		return $console;
	}

	// Connect to DB

	$hostname = "localhost";
	$username = "ehhemnet_stpdoor";
	$password = "pop219";
	$dbname = "ehhemnet_test";

	$db = mysqli_connect($hostname, $username, $password, $dbname);
	mysqli_set_charset($db, "utf8");

	// Main Switch Board of POSTed Command from Android

	if (isset($_POST["command"])) {
		switch ($_POST["command"]) {
			case "CREATE": create($db); break;
			case "UPDATE": update($db); break;
			case "DELETE": delete($db); break;
			case "CONSOLES": consoles($db); break;
			case "GAME": game($db); break;
			case "LIST": listgames($db); break;
			case "STATS1": stats1($db); break;
			case "STATS2": stats2($db); break;
			default: echo "{$_POST['command']}?";
		}
	}

?>