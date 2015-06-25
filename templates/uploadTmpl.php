<div>
	<form action="helpers/processupload.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
		<input name="FileInput" id="fileInput" type="file" >
		<select name="RoomName" id="roomName">
			<option value="fylkestingsal a">Fylkestingsal A</option>
			<option value="fylkestingsal b">Fylkestingsal B</option>
			<option value="svovelkis i">Svovelkis I</option>
			<option value="svovelkis ii">Svovelkis II</option>
			<option value="kleberstein">Kleberstein</option>
			<option value="utvalgsalen">Utvalgsalen</option>
			<option value="grønnstein">Grønnstein</option>
			<option value="kvarts">Kvarts</option>
			<option value="skifer">Skifer</option>
			<option value="sandstein">Sandstein</option>
			<option value="trondhjemitt">Trondhjemitt</option>
			<option value="thulitt">Thulitt</option>
			<option value="kobberkis">Kobberkis</option>
		</select>
		<input type="submit" name="submit" value="Last opp">
	</form>
	<? 	if (isset($_GET['nofile']))
		 	echo 'Vennligst velg en fil';
		else if (isset($_GET['success']))
			echo 'Møtene ble lagret!';
	?>
</div>