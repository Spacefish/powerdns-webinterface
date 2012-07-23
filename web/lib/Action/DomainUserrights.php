<?php

class Action_DomainUserrights extends Action {
	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$domain_id = (int)$this->post['extra']['domain_id'];

		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $e) {
				if($e['haspower'] == 1) {
					$sql = "REPLACE INTO perm SET userid = ".(int)$e['id'].", domain_id = ".$domain_id;
					$this->app->ActionLog->log(
						"domains/perm",
						$this->app->DB->getOne("SELECT username FROM user WHERE id = ".(int)$e['id']).
						" Zugriff auf ".
						$this->app->DB->getOne("SELECT name FROM domains WHERE id = ".(int)$domain_id).
						" erteilt"
					);
				}
				else {
					$sql = "DELETE FROM perm WHERE userid = ".(int)$e['id']." AND domain_id = ".$domain_id." LIMIT 1";
					$this->app->ActionLog->log(
						"domains/perm",
						$this->app->DB->getOne("SELECT username FROM user WHERE id = ".(int)$e['id']).
						" Zugriff auf ".
						$this->app->DB->getOne("SELECT name FROM domains WHERE id = ".(int)$domain_id).
						" entzogen"
					);
				}
				$this->app->DB->query($sql);
			}
		}

		$this->msg(self::MSG_OK, "Saved!");
		$this->cmd("reloadRecords(fields);");
	}
}