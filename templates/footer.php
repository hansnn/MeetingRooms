<? if (count($this->extra_footers)): ?>
	<? foreach ($this->extra_footers as $footer): ?>
		<?= $footer ?>
	<? endforeach ?>
<? endif ?>


<style>
	#footerlinks {
		position: fixed;
		bottom: 0;
		right: 0;
		clear: both;
		font-size: 0.8em;
	}
</style>
<? if (isset($_SESSION['login']) && $_SESSION['login'] == true): ?>
	<div id="footerlinks">
		<a class="footerlink" href="rediger.php">Til redigering</a><br>
		<a class="footerlink" href="/">Til oversikten</a>
	</div>
<? endif ?>
</body>
</html>