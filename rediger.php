<?
session_start();

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $ROOT . '/helpers/templater.php';
require_once $ROOT . '/helpers/database.php';


if (authenticate())
	direct_to_correct_page();

function authenticate() {
	if (isset($_SESSION['login']) && $_SESSION['login'] == true)
		return true;

	else {
		header('Location: authenticate.php');
	}
}

function direct_to_correct_page() {

	$editStylesheet = '<link href="/static/css/edit.css" rel="stylesheet">';
	$uploadStylesheet = '<link href="/static/css/upload.css" rel="stylesheet">';
	$editoruploadStylesheet = '<link href="/static/css/editorupload.css" rel="stylesheet">';

	$templater = new Templater;
	$templater->title = 'Rediger';
	
	if (isset($_GET['path'])) {
		if ($_GET['path'] == 'upload') {
			$templater->extra_headers = array($uploadStylesheet);
			$templater->render('uploadTmpl.php');

		} else if ($_GET['path'] == 'edit') {
			$date = isset($_GET['date']) ? 
					date('Ymd', strtotime($_GET['date'])) :
					date('Ymd');
			$templater->date = date('d-m-Y', strtotime($date));
			$templater->meeting_data = extract_meetings_all_rooms(false, $date); 
			$templater->extra_headers = array($editStylesheet);
			$templater->render('editTmpl.php');

		} else
			echo "I don't know what to do with this get request: " . var_dump($_GET);
	}
	else {
		$templater->extra_headers = array($editoruploadStylesheet);
		$templater->render('editoruploadTmpl.php');
	}
}
?>