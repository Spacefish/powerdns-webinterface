<?php

/**
 * Generic Action Class this class should be the base for every action
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.1.2
 */
abstract class Action {
	protected $params;
	protected $post;
	protected $get;
	protected $app;

	protected $msgs = array();
	protected $cmds = array();

	const MSG_INFO = 1;
	const MSG_WARN = 2;
	const MSG_ERROR = 3;
	const MSG_OK = 4;

	public function __construct(Application $app) {
		$this->app = $app;
	}

	public function setParams($params) {
		$this->params = $params;
	}

	public function setPostdata($data) {
		$this->post = $data;
	}

	public function setGetdata($data) {
		$this->get = $data;
	}

	protected function msg($type = self::MSG_INFO, $msg) {
		$this->msgs[] = array($type, $msg);
	}

	protected function cmd($cmd) {
		$this->cmds[] = $cmd;
	}

	public function getCommands() {
		if(is_array($this->msgs)) {
			$this->msgs = array_reverse($this->msgs);
			foreach($this->msgs as $msg) {
				$ret[] = "showMsg(".$msg[0].", '".$msg[1]."');";
			}
		}


		foreach($this->cmds as $cmd) {
			$ret[] = $cmd;
		}

		return array("cmds" => $ret);
	}
}