<?
date_default_timezone_set('Europe/Paris');
$ROOMS = array('fylkestingsal a' =>  0, 
			   'fylkestingsal b' =>  1,
		   	   'svovelkis i' 	 =>  2,
		   	   'svovelkis ii' 	 =>  3, 
		   	   'kleberstein' 	 =>  4,
		   	   'utvalgsalen' 	 =>  5, 
		   	   'grønnstein'	 	 =>  6, 
		 	   'kvarts' 		 =>  7,
		 	   'skifer' 		 =>  8, 
		 	   'sandstein' 		 =>  9, 
		 	   'trondhjemitt' 	 => 10,
		 	   'thulitt'		 => 11, 
		  	   'kobberkis' 		 => 12
);

function extract_last_updated() {
	$con = connect_db();
	$sql = "SELECT created FROM meetings WHERE date(start_datetime)=CURDATE()";
	$result = sql_query($con, $sql);

	$last_updated = null;
	while ($row = $result->fetch_assoc()) {
		$created = date($row['created']);
		if ($created > $last_updated)
			$last_updated = $created;
	}
	return $last_updated;
}

function extract_meetings($room_name, $all=false, $date=null) {
	$room_id = get_room_id($room_name);
	$con = connect_db();
	$sql = "SELECT P_Id, subject, start_datetime, end_datetime
			FROM meetings
			WHERE F_Id='$room_id'";

	$result = sql_query($con, $sql);
	$rows = array();
	while ($assoc = $result->fetch_assoc()) {
		$assoc = set_time_attr($assoc);
		if ($date != null) {
			if ($date == date('Ymd', strtotime($assoc['start_datetime']))) {
				$rows[] = $assoc;
			}
		}
		else if (!$all) {
			if (date('Ymd') == date('Ymd', strtotime($assoc['start_datetime']))) {
				$rows[] = $assoc;
			}
		}
		else {
			$rows[] = $assoc;
		}
	}
	usort($rows, function($row1, $row2) {
		return strtotime($row1['start_datetime']) - strtotime($row2['start_datetime']);
	});
	return $rows;
}

function set_time_attr($meeting) {
	$start = substr($meeting['start_datetime'], -8, 5);
	$end = substr($meeting['end_datetime'], -8, 5);
	$meeting['time'] = $start . ' - ' . $end;
	return $meeting;
}
function extract_meetings_all_rooms($all=false, $date=null) {
	global $ROOMS;
	$meeting_data = array();
	foreach ($ROOMS as $room_name => $i) {
		$meeting_data[$room_name] = extract_meetings($room_name, $all, $date);
	}
	return $meeting_data;
}

function commit_file_to_db($file, $room_name) {
	$f = fopen($file, 'r');
	$room_id = get_room_id($room_name);
	$header = fgets($f);

	$con = connect_db();
	$con->query("DELETE FROM meetings WHERE F_Id='$room_id'");

	if (!($stmt = $con->prepare("INSERT INTO meetings
											 (subject,
											  start_datetime,
											  end_datetime,
											  F_Id)
								 VALUES 	 (?, ?, ?, ?)"))) {
		echo 'Prepare failed: ' . $con->errno . ' - ' . $con->error;
		exit;
	}

	if (!($stmt->bind_param('sssi', $subject, $start_datetime, $end_datetime, $room_id))) {
		echo 'BindParam failed: ' . $stmt->errno . ' - ' . $stmt->error;
		exit;
	}
	while ($line = fgets($f)) {
		$data = parse_line($line);
		if ($data) {
			extract($data); // See parse_line() for extracted values
			if (strlen($subject) > 1) {
				if (strpos($subject, 'Copy: ') === 0 || strpos($subject, 'Kopi: ') === 0)
					$subject = substr($subject, 6);
				$subject = utf8_encode($subject);
				if (!($stmt->execute())) {
					echo 'Execute() failed: ' . $stmt->errno . ' - ' . $stmt->error . '<br>';
					exit;
				}
			}
		}
	}
	return true;
}

function parse_line($line) {
	$row = explode(',"', $line);

	$subject = trim($row[0], '"');
	$start_datetime = format_datetime(trim($row[1], '"'), trim($row[2], '"'));
	$end_datetime = format_datetime(trim($row[3], '"'), trim($row[4], '"'));

	if (strlen($subject) <= 1 || count($row) <= 1)
		return null;
	else
		return array('subject' => $subject, 
					 'start_datetime' => $start_datetime,
					 'end_datetime' => $end_datetime
		);
}
function format_datetime($date, $time) {
	$dateArr = explode('.', $date);
	if (count($dateArr) == 3 && is_numeric($dateArr[2]) &&
			is_numeric($dateArr[1]) && is_numeric($dateArr[0])) {
		$date = $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0];
		return $date . ' ' . $time;
	}
	else
		return null;
}

function get_room_id($room_name){
	global $ROOMS;
	$room_id = $ROOMS[strtolower($room_name)];
	if ($room_id === null)
		throw new Exception('no room id found for room ' . $room_name . ' in get_room_id');
	else
		return $room_id;
}
function get_room_name($room_id){
	$rooms = array( 0 => 'fylkestingsal a', 
				    1 => 'fylkestingsal b',
			   	    2 => 'svovelkis i',
			   	    3 => 'svovelkis ii', 
			   	    4 => 'kleberstein',
			   	    5 => 'utvalgsalen', 
			   	    6 => 'grønnstein', 
			 	    7 => 'kvarts',
			 	    8 => 'skifer', 
			 	    9 => 'sandstein', 
			 	   10 => 'trondhjemitt',
			 	   11 => 'thulitt', 
			  	   12 => 'kobberkis');

	$room_name = $rooms[$room_id];

	if ($room_name === null)
		throw new Exception('no room name found for room id ' . $room_id . ' in get_room_name');
	else
		return $room_name;
}



function connect_db() {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/../mkkdbauth.php';

	try {
		$con = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_AUTH_KEY, DB_DATABASENAME);
		if (mysqli_connect_errno())
			throw new Exception('Unable to connect to database ' . mysqli_connect_errno());
		else {
			mysqli_set_charset($con, 'utf8');
            return $con;
        }
	} catch (Exception $e) {
		echo 'Error in file \'database.php\' in function \'connect_db()\': ' . $e->getMessage();
	}
}
function sql_query($con, $sql) {
	$result = mysqli_query($con, $sql)
		or die("Error: " . mysqli_error($con));
	return $result;
}
?>