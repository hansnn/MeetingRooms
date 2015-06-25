<?
session_start();

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $ROOT . '/helpers/database.php';
require_once $ROOT . '/helpers/templater.php';

$stylesheet = '<link href="static/css/allmeetings.css" rel="stylesheet">';

if (isset($_GET['room_name'])) {
	$last_updated = fileatime($ROOT . '/last_updated/' . str_replace(' ', '_', $_GET['room_name']));
	$templater = new Templater;
	$templater->title = $_GET['room_name'];
	$templater->meetings = sort_by_date(extract_meetings($_GET['room_name'], true));
	$templater->last_updated = utf8_encode(strftime('%A %d. %B kl %H:%M', strtotime(date('Ymd Hi', $last_updated))));
	$templater->extra_headers = array($stylesheet);

	$templater->render('allmeetingsTmpl.php');
}
else
	echo "No room name available";

function sort_by_date($meetings) {
	$sorted = array();
	foreach ($meetings as $meeting) {
		$meeting_date = date('Ymd', strtotime($meeting['start_datetime']));
		if (isset($sorted[$meeting_date]))
			$sorted[$meeting_date][] = $meeting;
		else
			$sorted[$meeting_date] = array($meeting);
	}
	ksort($sorted);

	return format_dates($sorted);
}
function format_dates($date_array) {
	$new_array = array();
	foreach ($date_array as $date => $meetings)
		$new_array[utf8_encode(strftime('%A %d. %B %Y', strtotime($date)))] = $meetings;
	return $new_array;
}