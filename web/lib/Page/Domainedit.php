<?php

class Page_Domainedit extends Page {
	public function LoadPage() {
		$this->app->Auth->forceAuth();

		if(!$this->app->Auth->isAdmin()) {
			$sql = "SELECT COUNT(*) ";
			$sql.= "FROM perm WHERE ";
			$sql.= "userid = ".(int)$this->app->Session->data['auth']['userid']." ";
			$sql.= "AND domain_id = ".(int)$this->params['domain_id'];

			if(!$this->app->DB->getOne($sql)) {
				throw new Exception("Man you don´t have access here ;)");
			}
		}

		$d = new Model_Domains($this->app);
		$dr = new Model_DomainRecords($this->app);

		include 'configs/record_types.php';
		$record_types = "['".implode("', '", $cfg['record_types'])."']";

		if(isset($this->params['ajax'])) {
			$this->tpl = "domain_recordlist.tpl";
			$this->t->basetpl = 'blank.tpl';
		}
		else {
			$this->tpl = "domain_bearbeiten.tpl";
		}

		$this->t->assign(array(
			"domain_id" => (int)$this->params['domain_id'],
			"record_types" => $record_types,
			"domain_name" => $d->getNameById($this->params['domain_id']),
			"records" => $dr->getRecordlistByDomainId($this->params['domain_id']),
			"template" => $this->getTemplate(),
			"pin" => isset($this->get['pin']) ? (int)$this->get['pin'] : null,
		));
	}

	private function getTemplate() {
		$db = $this->app->DB;
		$sql = "SELECT * FROM template_newrecord";
		$r = $db->query($sql);
		while($row = $r->fetchRow()) {
			$template[$row['key']] = $row['value'];
		}

		$sql = "SELECT `key`, `value` FROM template_newrecord_domain WHERE domain_id = ".(int)$this->params['domain_id'];
		$r = $db->query($sql);
		while($row = $r->fetchRow()) {
			$template[$row['key']] = $row['value'];
		}

		$domain_name = $db->getOne("SELECT name FROM domains WHERE id = ".(int)$this->params['domain_id']);
		foreach($template as $key => $value) {
			$template[$key] = str_replace("[DOMAIN]", $domain_name, $value);
		}

		return $template;
	}
}

?>
