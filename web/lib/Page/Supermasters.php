<?php

class Page_Supermasters extends Page {
	private $db;

	public function loadPage() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if($this->params["ajax"]) {
			$this->tpl = "supermasters_list.tpl";
			$this->t->basetpl = "blank.tpl";
		}
		else {
			$this->tpl = "supermasters.tpl";
		}

		$this->t->assign(array(
			"supermasters" => $this->db->getAll("SELECT * FROM supermasters"),
		));
	}
}

?>