<?php

/**
 * Generic Session Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.01
 */
class Session {
	private $db;
	public $data;
	private $sid;

	const SESSION_LIFETIME = 3600; // 1 hour

	public function __construct() { }

	public function setDB(DB $db) {
		$this->db = $db;
	}

	public function setSID($sid) {
		$this->sid = $sid;
	}

	public function setData($data) {
		$this->data = $data;
	}

	public function destruct() {
		$this->data = array();
	}

	public static function get(Application $app) {
		$db = $app->DB;

		// check cookie
		$sid = self::getCookie();

		// get from db
		$data = $db->getOne("SELECT data FROM session WHERE sid = '".addslashes($sid)."'");
		$data = unserialize($data);

		$session = new Session();

		$session->setDB($db);
		$session->setSID($sid);
		$session->setData($data);

		return $session;
	}

	public static function getCookie() {
		if($_COOKIE['sid']) {
			$sid = $_COOKIE['sid'];
		}
		else {
			$sid = md5(mt_rand());
		}
		// renew
		setcookie("sid", $sid, time()+self::SESSION_LIFETIME, "/");

		return $sid;
	}

	public function __destruct() {
		$data = serialize($this->data);
		$this->db->query("REPLACE INTO session SET sid = '".addslashes($this->sid)."', data = '".addslashes($data)."', lastchange = NOW()");
	}
}

?>