<?
setlocale(LC_TIME, 'no_NO'); // For norwegian date representations in templates
class Templater
{
	protected $vars = array('extra_headers' => array(), 'extra_footers' => array());
	protected $template_dir = '';

	public function __construct() {
		$this->template_dir = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
	}
	public function render($template_file) {
		try {
			if (file_exists($this->template_dir.$template_file)) {
				require_once $this->template_dir.'header.php';
				require_once $this->template_dir.$template_file;
				require_once $this->template_dir.'footer.php';
			} else {
				throw new Exception("Error: could not find " . $template_file .
								    " in " . $this->template_dir);
			}
		} catch(Exception $e) {
			echo $e;
		}
	}
	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	public function __get($name) {
		return $this->vars[$name];
	}
}

?>