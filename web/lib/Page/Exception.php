<?php

class Page_Exception extends Page {
	public function LoadPage() {
		$this->tpl = "exception.tpl";

		$e = $this->params['exception'];

		$this->t->assign(array(
			"msg" => "Exception: ".$e->getMessage(),
			"trace" => nl2br($e->getTraceAsString()),
		));
	}
}