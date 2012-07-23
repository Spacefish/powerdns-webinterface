<?php

/**
 * Generic Page Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.1
 */
abstract class Page {
	protected $t;
	protected $params;
	protected $post;
	protected $get;
	protected $tpl;
	protected $app;

	public function __construct(Application $app) {
		$this->app = $app;

		// alias for Template
		$this->t = $this->app->Template;
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

	private function doAssigns() {
		$this->t->assign(array(
			"app" => $this->app,
			"_SESSION" => $this->app->Session->data,
		));
	}

	public function render() {
		$this->doAssigns();
		$this->t->callDisplay($this->tpl);
	}

	abstract function loadPage();
}