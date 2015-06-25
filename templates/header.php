<!doctype html>
<html lang="en">
<head>
        <meta charset="UTF-8">
	<title><?= $this->title ?></title>
	<link href="http://yui.yahooapis.com/3.14.1/build/cssreset/cssreset-min.css" rel="stylesheet" type="text/css">
	
	<? if (count($this->extra_headers)): ?>
		<? foreach ($this->extra_headers as $header): ?>
			<?= $header ?>
		<? endforeach ?>
	<? endif ?>
</head>
<body>