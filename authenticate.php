<?
session_start();
direct();

function direct() {
	$ROOT = $_SERVER['DOCUMENT_ROOT'];
	require_once $ROOT . '/helpers/templater.php';
	require_once $ROOT . '/../mkkauth.php';


	if (isset($_POST['password']) && htmlspecialchars($_POST['password']) === MKK_AUTH_KEY) {
		$_SESSION['login'] = true;
		header('Location: rediger.php');
		exit;
	}
	else {
		$stylesheet = '<link href="static/css/authenticate.css" rel="stylesheet">';
		$templater = new Templater;
		$templater->title = 'Login';
		$templater->extra_headers = array($stylesheet);
		$templater->render('authenticateTmpl.php');
	}
}