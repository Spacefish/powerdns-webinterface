<?php
class Action_TemplateNewDomain extends Action {
	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		// EINTRÄGE LÖSCHEN
		if(is_array($this->post['delete'])) {
			foreach($this->post['delete'] as $row) {
				$this->db->query("DELETE FROM template_records_newdomain WHERE id = ".(int)$row);
			}
		}

		// EINTRÄGE EDITIEREN
		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $row) {
				$sql = "UPDATE template_records_newdomain SET ";
				$sql.= "name = '".addslashes($row['name'])."', ";
				$sql.= "type = '".addslashes($row['type'])."', ";
				$sql.= "content = '".addslashes($row['content'])."', ";
				$sql.= "ttl = ".(int)$row['ttl'].", ";
				$sql.= "prio = ".(int)$row['prio']." ";
				$sql.= "WHERE id = ".(int)$row['id'];

				$this->db->query($sql);
			}
		}

		// NEUE EINTRÄGE
		$emptyentrys = 0;
		if(is_array($this->post['new'])) {
			foreach($this->post['new'] as $row) {
				if(!$row['name'] || !$row['content']) {
					$emptyentrys++;
					continue;
				}

				$sql = "INSERT INTO template_records_newdomain SET ";
				$sql.= "name = '".addslashes($row['name'])."', ";
				$sql.= "type = '".addslashes($row['type'])."', ";
				$sql.= "content = '".addslashes($row['content'])."', ";
				$sql.= "ttl = ".(int)$row['ttl'].", ";
				$sql.= "prio = ".(int)$row['prio'];

				$this->db->query($sql);
			}
		}

		if($emptyentrys) {
			$this->msg(self::MSG_INFO, $emptyentrys." were not added because their name or content was empty.");
		}

		$this->msg(self::MSG_OK, "Saved....");

		$this->cmd("reloadRecords(fields);");
	}
}
?>