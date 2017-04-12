<?php
	include "dblogin.php";
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
						if (!$sublist) { //New root console, no previous sublist, ready to start sublist
							echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']}</a>";
						} else { //New root console, previous sublist, quit sublist and ready to start new sublist
							$sublist = false;
							echo "</ul></li>
							<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']}</a>";
						}
						$previous_root = $row['console_root'];
						$root_name = $row['name_short'];
						$sublist_start = true;
					//Child console exists, create sublist
					} else if ($row['console_root'] == $previous_root && $sublist_start){
						$sublist_start=false;
						echo " <img class='dropdown' src='svg/dropdown.svg'>
						<ul class='console-sublist'>
							<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
						$sublist=true;
					//New child console, continue sublist
					} else if ($row['console_root'] == $previous_root && !$sublist_start){
						echo "<li><a href='' class='console-link' data-id='{$row['id']}' data-root='{$row['console_root']}'>{$row['name']} ({$root_name})</a></li>";
					}
				}
			}
		?>
	</ul>
</div>