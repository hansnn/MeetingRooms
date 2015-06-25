<h1><?= $this->date ?><br><span>Sist oppdatert <?= $this->last_updated ?></span></h1>
<hr>
<div id="left">
<? $i = 0; ?>
<? foreach ($this->meeting_data as $room_name => $meetings): ?>
	<? if ($i === 7): ?>
		</div>
		<div id="right">
	<? endif ?>
	<div class="rooms">
		<h2>
			<a class="room" href="all_meetings.php?room_name=<?= str_replace(' ', '+', $room_name) ?>">
				<? if ($room_name == 'svovelkis ii'): ?>
					Svovelkis II
				<? else: ?>
					<?= $room_name ?>
				<? endif ?>
			</a>
		</h2> 	
		<? foreach ($meetings as $meeting): ?>
			<? if (date('Ymd His', strtotime($meeting['end_datetime'])) > date('Ymd His')): ?>
				<p>(<?= $meeting['time'] ?>) <?= $meeting['subject'] ?></p>
			<? endif ?>
		<? endforeach ?>
	</div>
	<? $i++ ?>
<? endforeach ?>
</div>