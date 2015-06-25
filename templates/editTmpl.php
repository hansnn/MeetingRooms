<form action="rediger.php" method="GET">
	<input type="hidden" name="path" value="edit">
	<input name="date" type="date" value="<?= date('Y-m-d', strtotime($this->date)) ?>">
	<input type="submit" value="Oppdater">
</form>
<form action="helpers/processedits.php" method="POST">
	<input name="date" type="hidden" value="<?=$this->date?>">
	<? foreach ($this->meeting_data as $room_name => $meetings): ?>
		<div>
			<h2>
				<? if ($room_name == 'svovelkis ii'): ?>
					Svovelkis II
				<? else: ?>
					<?= $room_name ?>
				<? endif ?>
			</h2>
			<textarea name="<?= $room_name ?>"><? foreach ($meetings as $meeting)
				echo '('.$meeting['time'].') '.$meeting['subject'].'&#13&#10';
		  ?></textarea>
		</div>	
	<? endforeach ?>
	<input type="submit" value="Lagre">
</form>