<?php

/**
 * Generic Logging Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.0 
 */
class Log {
	private $app;
	private $log;

	public static function get(Application $app) {
		$log = new Log();
		$log->setApp($app);
		return $log;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function debug($msg) {
		$this->log[] = array(
			"msg" => $msg,
			"type" => "debug",
		);
	}

	public function echoLog() {
		echo '<table border="1">';
		echo '<tr><td><strong>'._("Type").'</strong></td><td><strong>'._("Message").'</strong></td></tr>';
		foreach($this->log as $entry) {
			echo '<tr>';
			echo '<td>'.$entry['type'].'</td>';
			echo '<td>'.$entry['msg'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
}

?>
