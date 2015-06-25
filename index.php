<?
session_start();

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once 'helpers/templater.php';
require_once 'helpers/database.php';

$stylesheet = '<link href="static/css/mainview.css" rel="stylesheet">';
$script = '<script src="static/js/mainview.js"></script>';

$templater = new Templater;
$templater->title = 'Møteromsoversikt';
$templater->date = utf8_encode(strftime('%A %d. %B %Y'));
$templater->last_updated = utf8_encode(strftime('%A %d. %B kl %H:%M', strtotime(extract_last_updated())));
$templater->meeting_data = extract_meetings_all_rooms();
$templater->extra_headers = array($stylesheet);
$templater->extra_footers = array($script);

$templater->render('mainviewTmpl.php');
?>