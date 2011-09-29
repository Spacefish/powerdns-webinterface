<?php

class Page_Templates extends Page {
	public function LoadPage() {
		$this->app->Auth->forceAuth();

		$this->db = $this->app->DB;

		include 'configs/record_types.php';
		$record_types = "['".implode("', '", $cfg['record_types'])."']";

		if($this->params['ajax']) {
			switch($this->params['type']) {
				case "newdomain":
					$this->t->basetpl = "blank.tpl";
					$this->tpl = "template_records_newdomainlist.tpl";
					break;
				case "newrecord":
					$this->t->basetpl = "blank.tpl";
					$this->tpl = "template_newrecordlist.tpl";
					break;
			}
		}
		else {
			$this->tpl = "templates.tpl";
		}

		$this->t->assign(array(
			"template_newrecord" => $this->db->getAll("SELECT * FROM template_newrecord"),
			// "template_newrecord_domain" => $this->db->getAll("SELECT * FROM template_newrecord"),
			"template_records_newdomain" => $this->db->getAll("SELECT * FROM  template_records_newdomain"),
			"record_types" => $record_types,
		));
	}
}

?>