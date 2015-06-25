<div>
	<a class="btn" href="../rediger.php?path=upload">Last opp .csv fil</a>
	<a class="btn" href="../rediger.php?path=edit">Rediger manuelt</a>
</div>
<? if (isset($_GET['success']) and $_GET['success'] == 'true'): ?>
<p>Suksess!<br>Møteromsoversikten er nå oppdatert</p>
<? elseif (isset($_GET['success']) and $_GET['success'] == 'false'): ?>
<p>Noe gikk galt</p>
<? endif ?>
