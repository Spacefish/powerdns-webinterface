<?php

class Action_Supermasters extends Action {
	private $db;

	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if(is_array($this->post['delete'])) {
			foreach($this->post['delete'] as $d) {
				$this->db->query("DELETE FROM supermasters WHERE id = ".(int)$d);
			}
		}

		if(is_array($this->post['new'])) {
			foreach($this->post['new'] as $e) {
				if(!$e['ip'] && !$e['nameserver'] && !$e['account'])
					continue;
				$sql = "INSERT INTO supermasters SET ";
				$sql.= "ip = '".addslashes($e['ip'])."', ";
				$sql.= "nameserver = '".addslashes($e['nameserver'])."', ";
				$sql.= "account = '".addslashes($e['account'])."'";

				$this->db->query($sql);
			}
		}

		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $e) {
				$sql = "UPDATE supermasters SET ";
				$sql.= "ip = '".addslashes($e['ip'])."', ";
				$sql.= "nameserver = '".addslashes($e['nameserver'])."', ";
				$sql.= "account = '".addslashes($e['account'])."' ";
				$sql.= "WHERE id = ".(int)$e['id'];

				$this->db->query($sql);
			}
		}

		$this->cmd("reloadRecords(fields);");
		$this->msg(self::MSG_OK, "Gespeichert!");
	}
}