<form action="authenticate.php" method="POST">
		<label>Passord: </label>
		<input type="password" name="password">
</form>
<? if (isset($_POST['password'])): ?>
<p>Feil passord</p>
<? endif ?>