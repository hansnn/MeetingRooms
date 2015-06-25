<?
session_start();
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $ROOT . '/helpers/database.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true && process_it()) 
	header('Location: ../rediger.php?success=true');
else
	header('Location: ../rediger.php?success=false');

exit;


function process_it() {
	$con = connect_db();
	$date = date('Y-m-d', strtotime(htmlspecialchars($_POST['date'])));
	unset($_POST['date']);  // remove the date from $_POST, leaving only room=>text pairs
	if (!($stmt = $con->prepare("INSERT INTO meetings
										 (subject,
										  start_datetime,
										  end_datetime,
										  F_Id)
							 	 VALUES  (?, ?, ?, ?)"))) {
		echo 'Prepare failed: ' . $con->errno . ' - ' . $con->error;
		exit;
	}
	if (!($stmt->bind_param('sssi', $subject, $start_datetime, $end_datetime, $room_id))) {
		echo 'BindParam failed: ' . $stmt->errno . ' - ' . $stmt->error;
		exit;
	}
	foreach ($_POST as $room => $text) {
		$room = htmlspecialchars($room);
		$text = htmlspecialchars($text);
		$room_id = get_room_id(str_replace('_', ' ', $room));

		$con->query("DELETE FROM meetings
				     WHERE F_Id='$room_id'
				     AND date(start_datetime)='$date'");
		$meetings = explode("\r\n", $text);
		if (strlen(preg_replace('/\s+/', '', $meetings[0])) > 1) {
			foreach ($meetings as $meet) {
				$meeting = create_meeting_object($meet);
				if ($meeting !== false) {
					$start_datetime = $date . ' ' . $meeting['start'];
					$end_datetime = $date . ' ' . $meeting['end'];
					$subject = $meeting['subject'];
					if (!($stmt->execute())) {
						echo 'Execute failed: ' . $stmt->errno . ' - ' . $stmt->error;
						exit;
					}
				}
				elseif (strlen($meet) > 1) {
					echo utf8_encode('Noe gikk galt under lagring av møtet: ') . $meet .
									 '<br>Gå tilbake for å rette opp';
					exit;
				}
			}
		}
	}
	return true;
}

function create_meeting_object($text) {
	$regex = '/^[0-2][0-9]:[0-5][0-9]$/'; // Matches time in format '00:00'
	try {
		$arr = explode(')', $text);
		if (isset($arr[1])) {
			$times = explode('-', $arr[0]);
			$start = trim($times[0], '( ');
			$end = trim($times[1]);
			if (preg_match($regex, $start) && preg_match($regex, $start))
				return array('start' 	=> $start, 
							 'end'   	=> $end,
							 'subject' 	=> ltrim($arr[1])
				);
		}
	}
	catch (Exception $e) {
		return false;
	}
	return false;
}