<?
// ------------------------------------
// Vis bilde hvis det er sommerferie
$today = date('d-m-Y', time());
$summerEnd = date('04-08-2014');
if (strtotime($today) < strtotime($summerEnd)) {
	require('static/sommer.html');
	die;
}
// ------------------------------------


$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once 'helpers/templater.php';
require_once 'helpers/database.php';

$stylesheet1 = '<link href="static/css/mainview.css" rel="stylesheet">';
$stylesheet2 = '<link href="static/css/infoskjerm.css" rel="stylesheet">';
$script1 = '<script src="static/js/infoskjerm.js"></script>';
$script2 = '<script src="static/js/mainview.js"></script>';


$templater = new Templater;
$templater->title = 'Møteromsoversikt';
$templater->date = utf8_encode(strftime('%A %d. %B %Y'));
$templater->last_updated = utf8_encode(strftime('%A %d. %B kl %H:%M', strtotime(extract_last_updated())));
$templater->meeting_data = extract_meetings_all_rooms();
$templater->extra_headers = array($stylesheet1, $stylesheet2);
$templater->extra_footers = array($script1, $script2);

$templater->render('mainviewTmpl.php');
