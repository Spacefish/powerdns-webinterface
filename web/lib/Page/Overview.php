<?php

class Page_Overview extends Page {
	public function LoadPage() {
		$this->app->Auth->forceAuth();
		$this->tpl = "overview.tpl";
	}
}