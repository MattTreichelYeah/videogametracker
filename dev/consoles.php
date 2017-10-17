<?php
	include $_SERVER['DOCUMENT_ROOT']."/videogames/.database.php";
?>

<div class="sidebar-content consoles">
	<ul>
		<li><a href="" class="console-link option-selected" data-id="-1" data-root="-1">All Games</a></li>
		<?php
			$sql = "SELECT * FROM consoles ORDER BY consoles.`order`";
			$result = mysqli_query($db, $sql);
			
			$previous_root = "";
			$root_name = "";
			$sublist_start = false;
			$sublist = false;
			
			//To create console sublists, assumes child consoles are ordered following root console
			while($row = mysqli_fetch_array($result)) {
				if ($row['owned'] == 1) {
					if ($row['console_root'] == $row['id']) { 
						$data_name = strtolower($row['name_short']); //for URL parsing
						if (!$sublist) { //New root console, no previous sublist, ready to start sublist
							echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}' data-name='{$data_name}'>{$row['name']}</a>";
						} else { //New root console, previous sublist, quit sublist and ready to start new sublist
							$sublist = false;
							echo "</ul></li>
							<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}' data-name='{$data_name}'>{$row['name']}</a>";
						}
						$previous_root = $row['console_root'];
						$root_name = $row['name_short'];
						$sublist_start = true;
					//Child console exists, create sublist
					} else if ($row['console_root'] == $previous_root && $sublist_start){
						$data_subname = str_replace(" ","-",strtolower($row['name'])); //for URL parsing
						$sublist_start = false;
						echo " <a href='' class='dropdown'><img src='/videogames/svg/dropdown.svg' alt='Dropdown Arrow'></a>
						<ul class='console-sublist'>
							<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}' data-name='{$data_name} ${data_subname}'>{$row['name']}<span class='sidebar-accessible'> ({$root_name})</span></a></li>";
						$sublist = true;
					//New child console, continue sublist
					} else if ($row['console_root'] == $previous_root && !$sublist_start){
						$data_subname = str_replace(" ","-",strtolower($row['name'])); //for URL parsing
						echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}' data-name='{$data_name} ${data_subname}'>{$row['name']}<span class='sidebar-accessible'> ({$root_name})</span></a></li>";
					}
				}
			}
		?>
	</ul>
</div>