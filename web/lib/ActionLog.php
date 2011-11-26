<?php

/**
 * Action logger
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright GPLv3
 * 
 * @version 1.0
 * @todo should be a wrapper for the loggingclass
 * @todoblocked loggingclass needs more features
 */
class ActionLog {
	private $app;
	private $log;
	private $db;

	public static function get(Application $app) {
		$log = new ActionLog();
		$log->setApp($app);
		$log->setDB($app->DB);
		return $log;
	}

	public function setDB(DB $db) {
		$this->db = $db;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function log($facility, $msg) {
		$sql = "INSERT INTO actionlog SET ";
		$sql.= "`date` = UTC_TIMESTAMP(), ";
		$sql.= "username = '".addslashes($this->app->Session->data['auth']['username'])."', ";
		$sql.= "facility = '".addslashes($facility)."', ";
		$sql.= "msg = '".addslashes($msg)."'";

		$this->db->query($sql);
	}
}

?>
