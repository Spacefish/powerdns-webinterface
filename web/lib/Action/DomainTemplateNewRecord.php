<?php
class Action_DomainTemplateNewRecord extends Action {
	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $row) {
				if($row['value']) {
					$sql = "REPLACE INTO template_newrecord_domain SET ";
					$sql.= "domain_id = ".(int)$this->post['extra']['domain_id'].", ";
					$sql.= "`key` = '".addslashes($row['id'])."', ";
					$sql.= "`value` = '".addslashes($row['value'])."'";
					$this->db->query($sql);
				}
				else {
					$this->db->query("DELETE FROM template_newrecord_domain WHERE domain_id = ".(int)$this->post['extra']['domain_id']." AND `key` = '".addslashes($row['id'])."'");
				}
			}
		}

		$this->msg(self::MSG_OK, "Gespeichert!");
		$this->cmd("reloadRecords(dl);");
	}
}
?>