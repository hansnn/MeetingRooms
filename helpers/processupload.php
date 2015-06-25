<?
session_start();
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once $ROOT . '/helpers/database.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == true && process_it())
    header('Location: ../rediger.php?success=true');
else 
    header('Location: ../rediger.php?success=false');

exit;

class UploadException extends Exception {
    public function __construct($code) {
        $message = $this->codeToMessage($code);
        parent::__construct($message, $code);
    }

    private function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }
}

function process_it() {
    global $ROOT;

    if (isset($_FILES['FileInput']) && isset($_POST['RoomName']) &&
            $_FILES['FileInput']['name'] !== '') {
        $file = $_FILES['FileInput'];
    	if ($file['error'] === UPLOAD_ERR_OK) {
    		if ($file['type'] != 'text/csv' && $file['type'] != 'application/vnd.ms-excel') {
    			return false;
    		}
    		else {
    			$filepath = $ROOT . '/upload/previous.csv';
    			if (move_uploaded_file($file['tmp_name'], $filepath)) {
    				commit_file_to_db($filepath, $_POST['RoomName']);
                    echo unlink($filepath);
                    touch($ROOT . '/last_updated/' . str_replace(' ', '_', $_POST['RoomName']));
                    return true;
    			}
    			else {
    				echo 'move_uploaded_file() feilet i proccessupload';
                    return false;
                }
    		}
    	}
    	else
    		throw new UploadException($file['error']);
    }
    else {
        header("Location: ../rediger.php?nofile=true");
    }
    return true;
}
?>