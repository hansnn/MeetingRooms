<h1>
	<? if ($this->title == 'svovelkis ii'): ?>
		Svovelkis II
	<? else: ?>
		<?= $this->title ?>
	<? endif ?>
	<br><span>Sist oppdatert <?= $this->last_updated ?></span>
</h1>
<hr>
<? foreach ($this->meetings as $date => $meetings): ?>
	<div>
		<h2><?= $date ?></h2>
		<? foreach ($meetings as $meeting): ?>
			<p>(<?= $meeting['time'] ?>) <?= $meeting['subject'] ?></p>
		<? endforeach ?>
	</div>
<? endforeach ?>