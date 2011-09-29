<?php

class Page_Domaintemplate extends Page {
	private $db;

	public function loadPage() {
		$this->db = $this->app->DB;

		$this->app->Auth->forceAuth();

		if(isset($this->params['ajax'])) {
			$this->tpl = "template_newrecord_domainlist.tpl";
			$this->t->basetpl = "blank.tpl";
		}
		else {
			$this->tpl = 'domain_template.tpl';
		}

		$template = array(
			array("key" => "name", "value" => $this->db->getOne("SELECT `value` FROM template_newrecord_domain WHERE `key` = 'name' AND domain_id = ".(int)$this->params['domain_id'])),
			array("key" => "ttl", "value" => $this->db->getOne("SELECT `value` FROM template_newrecord_domain WHERE `key` = 'ttl' AND domain_id = ".(int)$this->params['domain_id'])),
			array("key" => "prio", "value" => $this->db->getOne("SELECT `value` FROM template_newrecord_domain WHERE `key` = 'prio' AND domain_id = ".(int)$this->params['domain_id'])),
		);

		$this->t->assign(array(
			"template" => $template,
			"domain_id" => (int)$this->params['domain_id'],
			"domain_name" => $this->db->getOne("SELECT name FROM domains WHERE id = ".(int)$this->params['domain_id']),
		));
	}
}

?>
