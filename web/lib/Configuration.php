<?php

/**
 * Generic Configuration Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2011 Timo Witte
 * @licence apache
 * 
 * @version 1.0 
 */
class Configuration {
	private $app;
	private $configs;

	public static function get(Application $app) {
		$conf = new Configuration();
		$conf->setApp($app);
		return $conf;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function load($name) {
		// if in cache just return the data
		if(isset($this->configs[$name]))
			return $this->configs[$name];

		$file = dirname(dirname(__FILE__))."/configs/".$name.".php";
		if(file_exists($file)) {
			include $file;
			if(!isset($cfg[$name]))
				throw new Exception('Arraykey $cfg["'.$name.'"] is missing!');
			else
				return $this->configs[$name] = $cfg[$name];
		}
		else
			throw new Exception("Configuration File ".$name.".php not found in configs folder!");
	}
}

?>
