<?php

class Action_Domains extends Action {
	private $db;

	public function deleteDomain() {
		$this->app->Auth->forceAuth();

		$this->db = $this->app->DB;

		$sql = array();
		$sql[] = "DELETE FROM records WHERE domain_id = ".(int)$this->post['domain_id'];
		$sql[] = "DELETE FROM domains WHERE id = ".(int)$this->post['domain_id'];
		$sql[] = "DELETE FROM template_newrecord_domain WHERE domain_id = ".(int)$this->post['domain_id'];
		foreach($sql as $qry) {
			$this->db->query($qry);
		}

		$this->msg(self::MSG_OK, "Domain wurde gelöscht!");
	}

	public function save() {
		$this->app->Auth->forceAuth();
		$this->db = $this->app->DB;

		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $row) {
				if($row['type'] == "SLAVE" && !$row['master']) {
					$this->msg(self::MSG_ERROR, "Please supply a master for the SLAVE! No change saved!");
					continue;
				}

				$oldtype = $this->db->getOne("SELECT type FROM domains WHERE id = ".(int)$row['id']);

				$sql = "UPDATE domains SET ";
				$sql.= "master = '".addslashes($row['master'])."', ";
				$sql.= "type = '".addslashes($row['type'])."' ";
				$sql.= "WHERE id = ".(int)$row['id'];
				$this->db->query($sql);

				if($oldtype != $row['type']) {
					$this->app->ActionLog->log(
						"domains",
						"Domaintyp für ".$this->db->getOne("SELECT name FROM domains WHERE id = ".(int)$row['id']).
						" von ".$oldtype." auf ".$row['type']." geändert"
					);
				}
			}
			$this->msg(self::MSG_OK, "Gespeichert.");
		}
		else {
			$this->msg(self::MSG_OK, "If you don´t want to change anything, thats fine with me ;)");
		}
		$this->cmd("updateList(lastsearch, lastsort.col, lastsort.dir);");
		// $this->cmd("reloadRecords(domains);");
	}

	public function newDomain() {
		$this->app->Auth->forceAuth();

		$this->db = $this->app->DB;
		$domain = $this->post['domain_name'];
		
		if(strlen(trim($domain)) == 0) {
			$this->msg(self::MSG_ERROR, "Domain shouldn´t be empty!");
			return;
		}

		$sql = "SELECT * FROM template_records_newdomain";
		$template = $this->db->getAll($sql);

		if($this->db->getOne("SELECT COUNT(*) FROM domains WHERE `name` = '".addslashes($domain)."'")) {
			$this->msg(self::MSG_ERROR, "Domain ".$domain." existiert bereits!");
			return;
		}

		$sql = "INSERT INTO domains SET ";
		$sql.= "`name` = '".addslashes($domain)."', ";
		$sql.= "`type` = 'MASTER', ";
		$sql.= "`account` = 'EXTERN'";
		$this->db->query($sql);

		$domain_id = $this->db->insertId();

		$stamp = date("Ymd")."01";

		foreach($template as $template_row) {
			// do replacements
			foreach($template_row as $tcolkey => $tcolval) {
				$template_row[$tcolkey] = str_replace(
					array("[DOMAIN]", "[STAMP]"),
					array($domain, $stamp),
					$tcolval
				);
			}

			$sql = "INSERT INTO records SET ";
			$sql.= "domain_id = ".(int)$domain_id.", ";
			$sql.= "name = '".addslashes($template_row['name'])."', ";
			$sql.= "type = '".addslashes($template_row['type'])."', ";
			$sql.= "content = '".addslashes($template_row['content'])."', ";
			$sql.= "ttl = ".(int)$template_row['ttl'].", ";
			$sql.= "prio = ".(int)$template_row['prio'].", ";
			$sql.= "change_date = ".time();
			$this->db->query($sql);
		}

		// if user add right
		if(!$this->app->Auth->isAdmin()) {
			$sql = "INSERT INTO perm SET userid = ".(int)$this->app->Session->data['auth']['userid'].", domain_id = ".(int)$domain_id;
			$this->db->query($sql);
		}

		$this->msg(self::MSG_OK, "Domain has been created!");
	}
}

?>