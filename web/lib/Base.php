<?php

class Application {
	public $t;
	private $db;

	private $objects;

	/**
	 * Static Factory for "Application" Object
	 *
	 * @return object Application
	 */
	public static function bootstrap() {	
		$app = new Application();
		$app->wakeup();
		return $app;
	}

	public function __construct() {

	}

	public static function exceptionHandler($e) {
		if($_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
			echo $e->getMessage();
		}
		else {
			$app = new Application();
			$page = new Page_Exception($app);
			$page->setParams(array("exception" => $e));
			$page->LoadPage();
			$page->render();
		}
	}

	public function wakeup() {
		// set include path
		$includePath = array();
		$includePath[] = ".";
		$includePath[] = dirname(__FILE__);
		$includePath[] = dirname(dirname(__FILE__));
		ini_set("include_path", implode(":", $includePath));		

		// register autoloader
		spl_autoload_register(array("Application", "autoload"));
		set_exception_handler(array("Application", "exceptionHandler"));

		// setup locale
		$baseConfig = $this->Configuration->load("base");
		$this->Gettext->setup($baseConfig['locale']);
	}

	public function __get($key) {
		switch($key) {
			case "ActionLog":
				return $this->ActionLog = ActionLog::get($this);
				break;
			case "DB":
				return $this->DB = DB::get("default");
				break;
			case "Session":
				return $this->Session = Session::get($this);
				break;
			case "Auth":
				return $this->Auth = Auth::get($this);
				break;
			case "Template": // NO SINGLETON HERE
				return Template::get($this);
				break;
			case "Dispatcher":
				return $this->Dispatcher = Dispatcher::get($this);
				break;
			case "Log":
				return $this->Log = Log::get($this);
				break;
			case "Configuration":
				return $this->Configuration = Configuration::get($this);
				break;
			case "Gettext":
				return $this->Gettext = Gettext::get($this);
				break;
		}
	}

	public static function autoload($name) {
		$filename = str_replace("_", "/", $name).".php";
		$paths = explode(PATH_SEPARATOR, ini_get("include_path"));
		foreach($paths as $path) {
			if(is_file($path.DIRECTORY_SEPARATOR.$filename)) {
				$found = true;
			}
		}
		if(isset($found))
			include_once $filename;
	}
}

?>
