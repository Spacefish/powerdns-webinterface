<?php

/**
 * Generic Model Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.0
 */
class Model {
	protected $app;
	protected $db;

	public function __construct(Application $app) {
		$this->app = $app;
		$this->db = $this->app->DB;
	}
}

?>