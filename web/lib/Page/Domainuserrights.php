<?php

class Page_Domainuserrights extends Page {
	private $db;

	public function loadPage() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if(isset($this->params['ajax'])) {
			$this->t->basetpl = "blank.tpl";
			$this->tpl = "domain_userrights_list.tpl";
		}
		else
			$this->tpl = "domain_userrights.tpl";

		$rightsDB = $this->db->getAll("SELECT * FROM perm WHERE domain_id = ".(int)$this->params['domain_id']);
		$users = $this->db->getAll("SELECT * FROM user");

		if(is_array($users)) {
			// reorder rights
			$rights = array();
			if(is_array($rightsDB)) {
				foreach($rightsDB as $rightDB) {
					$rights[$rightDB['userid']] = true;
				}
			}

			foreach($users as $key => $user) {
				$users[$key]['haspower'] = isset($rights[$user['id']]) ? true : false;
			}
		}

		$this->t->assign(array(
			"domain_name"	=> $this->db->getOne("SELECT name FROM domains WHERE id = ".(int)$this->params['domain_id']),
			"domain_id" => (int)$this->params['domain_id'],
			"ur" => $users,
		));
	}
}

?>
