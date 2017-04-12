<?php
	include "dblogin.php";
?>

<div class="sidebar-content tags">
	<ul>
		<li><a href="" class="tag-link option-selected" data-id="-1" data-root="-1">All Tags</a></li>
		<?php
			$sql = "SELECT * FROM tags ORDER BY name";
			$result = mysqli_query($db, $sql);
			
			while($row = mysqli_fetch_array($result)) {
				echo "<li><a href='' class='tag-link' data-id='{$row['id']}'>{$row['name']}</a>";
			}
		?>
	</ul>
</div>