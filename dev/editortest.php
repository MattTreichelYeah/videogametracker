<?php
	/* Be extra sure UTF-8 */
	header('Content-Type: text/html; charset=utf-8');
?>

<!doctype html>
<html lang="en">
	<head>
		<?php include 'head.php' ?>
		<style>
		/*#FD927E
		  #C6EC51*/
		  body {
		  	/*background: grey;*/
		  }
			.gamelist {
				min-height: 100vh;
				width: 50%;
				background: #C6EC51;
				text-overflow: ellipsis;
			}
			.gamelist td {
				color: white;
			}

			main {
				width: 50%;
				vertical-align: top;
				color: white;
				font-size: 20px;
			}
			main > form {
				padding: 0 20px 20px 20px; /*Gross*/
			}
			main, section {
				display: inline-block;
			}
			label {
				color: white;
			}
			input {
				border: none;
				height:20px;
			}
			select {
				-webkit-appearance: none;
				-webkit-border-radius: 0px;
				border: none;
			}
			.inputs {
				display: grid;
				grid-template-columns: 1fr 1fr;
			}
			.svg {
				fill: #FFFFFF;
			}
		</style>
	</head>
	<body>
		<section class="gamelist">
			<?php 
				$class = "toggleEdit";
				include 'components/toggle.html'
			?>
			<select>
				<option>Accessible</option>
				<option>Splitscreen</option>
				<option>Racing</option>
			</select>
			<table id="games-table" class="games-table hover compact order-column nowrap" width="100%">
				<thead>
					<tr>
						<!--Class Names are for DataTables Column Visibility Control-->
						<th class="console"><span class="th-desktop">Console</span><span class="th-mobile">&nbsp;</span></th>
						<th class="name"><span class="th-desktop">Name</span><span class="th-mobile">&nbsp;</span></th>
					</tr>
				</thead>
				<tbody>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
					<tr><td>N64</td><td>Animal Crossing: amiibo Festival</td></tr>
					<tr><td>Wii U</td><td>Super Mario Sunshine</td></tr>
					<tr><td>Switch</td><td>Mario Party 10</td></tr>
					<tr><td>GBA</td><td>Call of Duty: Modern Warfare 2</td></tr>
					<tr><td>GBC</td><td>Halo 4</td></tr>
				</tbody>
			</table>
		</section><main>
			<h1 contenteditable>Super Mario Sunshine</h1>
			<form class="inputs">
				<label>Console<br><input></label>
				<label>Original Console<br><input></label>
				<label>Compilation Root<br>
					<button><img class='svg' src="svg/star.svg" alt="Compilation Root Indicator"></button>
					<button><img class='svg' src="svg/arrow.svg" alt="Compilation Indicator"></button>
				</label>
				<label>DLC Root<br>
					<button><img class='svg' src="svg/arrow.svg" alt="Compilation Indicator"></button>
				</label>
				<label>Completion<br><input></label>
				<label>Rating<br>
					<select>
						<option>5 Stars</option>
						<option>4 Stars</option>
						<option>-1 Stars</option>
					</select>
				</label>
				<!--Not Sure about this Label Setup-->
				<span id="PlayerCountForm">Multiplayer Player Count</span><br>
				<label>Comp 
				<img class='svg' src="svg/localWhite.svg" title="Local Player Count" alt="Local Player Count"><input type="number" min="1" max="99" aria-describedby="PlayerCountForm">
				<img class='svg' src="svg/linkWhite.svg" title="System Link Player Count" alt="System Link Player Count"><input type="number" min="1" max="99" aria-describedby="PlayerCountForm"></label>
				<label>Coop
				<img class='svg' src="svg/localWhite.svg" title="Local Player Count" alt="Local Player Count"><input type="number" min="1" max="99" aria-describedby="PlayerCountForm">
				<img class='svg' src="svg/linkWhite.svg" title="System Link Player Count" alt="System Link Player Count"><input type="number" min="1" max="99" aria-describedby="PlayerCountForm"></label>
				<label>Note<input></label>
			</form>
		</main>
	</body>
</html>