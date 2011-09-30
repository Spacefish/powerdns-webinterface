<?php

class Action_Users extends Action {
	private $db;
	private $cfg;

	public function save() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;
		$this->cfg = $this->app->Configuration->load("base");

		if(isset($this->post['delete']) && is_array($this->post['delete'])) {
			foreach($this->post['delete'] as $e) {
				$username = $this->db->getOne("SELECT username FROM user WHERE id = ".(int)$e);
	
				if($this->cfg['demomode'] && $username == "admin") {
					$this->msg(self::MSG_WARN, "User admin can´t be deleted in demomode!");
					continue;
				}

				$this->app->ActionLog->log("users", $username." deleted");

				$this->db->query("DELETE FROM user WHERE id = ".(int)$e);
				$this->db->query("DELETE FROM perm WHERE userid = ".(int)$e);
				$this->msg(self::MSG_OK, "User deleted!");
			}
		}

		if(isset($this->post['data']) && is_array($this->post['data'])) {
			foreach($this->post['data'] as $e) {
				$wasadmin = $this->db->getOne("SELECT isAdmin FROM user WHERE id = ".(int)$e['id']);
				$username = $this->db->getOne("SELECT username FROM user WHERE id = ".(int)$e['id']);

				if($this->cfg['demomode'] && $username == "admin") {
                                        $this->msg(self::MSG_WARN, "User admin can´t be modified in demomode!");
                                        continue;
                                }


				$sql = "UPDATE user SET ";
				$sql.= "username = '".addslashes($e['username'])."', ";
				$sql.= "isAdmin = '".($e['isadmin'] == 1 ? 1 : 0)."', ";
				$sql.= "canCreateDomain = '".($e['cancreatedomain'] == 1 ? 1 : 0)."' ";
				$sql.= "WHERE id = ".$e['id'];
				$this->db->query($sql);

				if($wasadmin != ($e['isadmin'] == 1 ? 1 : 0)) {
					$this->app->ActionLog->log(
						"users",
						"Adminrechte ".
						$this->db->getOne("SELECT username FROM user WHERE id = ".(int)$e['id'])." ".
						($e['isadmin'] ? "granted" : "revoked")
					);
				}
			}
		}

		$this->cmd("reloadRecords(fields);");
		$this->msg(self::MSG_OK, "Saved!");
	}

	public function newUser() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		// check for existance
		if($this->db->getOne("SELECT COUNT(*) FROM user WHERE username = '".addslashes($this->post['username'])."'")) {
			$this->msg(self::MSG_ERROR, $this->post['username']." is already in use!");
			return;
		}

		// check pw
		if(strlen($this->post['password']) < 3) {
			$this->msg(self::MSG_ERROR, "The password should be a minimum of 3 characters!");
			return;
		}

		$sql = "INSERT INTO user SET ";
		$sql.= "username = '".addslashes($this->post['username'])."', ";
		$sql.= "password = '".md5($this->post['password'])."', ";
		$sql.= "isAdmin = ".($this->post['isadmin'] ? 1 : 0);
		$this->db->query($sql);

		$this->app->ActionLog->log("users", "New ".($this->post['isadmin'] ? "Admin" : "User")." ".$this->post['username']." created.");

		$this->cmd("reloadRecords(fields);");
		$this->msg(self::MSG_OK, "User created!");
	}

	public function changePw() {
		$this->app->Auth->forceAuth();
		$this->app->Auth->forceAdmin();

		$this->db = $this->app->DB;

		if(strlen($this->post['password']) < 3) {
			$this->msg(self::MSG_ERROR, "Password should contain a minimum of 3 charcters!");
			return;
		}

		$this->app->ActionLog->log("users", "Password for ".$this->db->getOne("SELECT username FROM user WHERE id = ".(int)$this->post['userid'])." changed.");

		$this->db->query("UPDATE user SET password = '".md5($this->post['password'])."' WHERE id = ".(int)$this->post['userid']);
		$this->msg(self::MSG_OK, "Password for ".$this->post['username']." changed!");
	}
}

?>
