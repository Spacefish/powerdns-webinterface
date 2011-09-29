<?php

class Page_Domains extends Page {
	private $db;

	public function LoadPage() {
		$this->app->Auth->forceAuth();
		$this->db = $this->app->DB;

		$mDomains = new Model_Domains($this->app);

		if($this->params['ajax']) {
			$this->tpl = "domains_list.tpl";
			$this->t->basetpl = 'blank.tpl';

			if(isset($this->params['savecallback'])) {
				$domains = $mDomains->getDomainlist(
					"name",
					"ASC",
					$this->app->Auth->isAdmin() ? false: $this->getAllowedIds()
				);
			}
			else {
				$domains = $mDomains->searchDomainlist(
					$this->post['search'], $this->post['col'],
					$this->post['dir'],
					$this->app->Auth->isAdmin() ? false: $this->getAllowedIds()
				);
			}

			$this->t->assign("domains", $domains);
		}
		else {
			$this->tpl = "domains.tpl";

			$domains = $mDomains->getDomainlist(
				"name",
				"ASC",
				$this->app->Auth->isAdmin() ? false: $this->getAllowedIds()
			);

			$this->t->assign("domains", $domains);
		}
	}

	private function getAllowedIds() {
		$sql = "SELECT domain_id FROM perm WHERE userid = ".(int)$this->app->Session->data['auth']['userid'];
		$ids = array();
		foreach($this->db->getAll($sql) as $d) {
			$ids[] = $d['domain_id'];
		}
		return $ids;
	}
}

?>
