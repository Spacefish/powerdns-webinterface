<?php

class Action_DomainRecords extends Action {
	private $db;

	public function save() {
		$this->app->Auth->forceAuth();

		$r = new Model_DomainRecords($this->app);
		$this->db = $this->app->DB;

		// EINTRÄGE LÖSCHEN
		if(is_array($this->post['delete'])) {
			foreach($this->post['delete'] as $row) {
				// $warn[] = "Would delete row ".$row;
				$updatesoa = true;
				$this->preDelete($row);
				$r->deleteRecord($row);
			}
		}

		// EINTRÄGE EDITIEREN
		if(is_array($this->post['data'])) {
			foreach($this->post['data'] as $row) {
				$row['change_date'] = time();
				$row['domain_id'] = $this->post['extra']['domain_id'];
				$row['action'] = "edit";

				if($this->checkRules($row)) {
					$this->preUpdate($row);
					$r->updateRecord($row['id'], $row);
					$this->postUpdate($row);
				}
				$updatesoa = true;
				if($this->checkDouble($this->post['extra']['domain_id'], $row['name'], $row['type'])) {
					$this->msg(self::MSG_WARN, sprintf(_("Double entry for %s (%s)"), $row['name'], $row['type']));
				}
			}
		}

		// NEUE EINTRÄGE
		$emptyentrys = 0;
		if(is_array($this->post['new'])) {
			foreach($this->post['new'] as $row) {
				if(!$row['content']) {
					$emptyentrys++;
					continue;
				}
				$row['change_date'] = time();
				$row['domain_id'] = $this->post['extra']['domain_id'];
				$row['action'] = "new";

				if($this->checkRules($row)) {
					$this->preCreate($row);
					$r->newRecord($row);
					$this->postCreate($row);
				}
				$updatesoa = true;

				if($this->checkDouble($this->post['extra']['domain_id'], $row['name'], $row['type'])) {
					$this->msg(self::MSG_WARN, sprintf(_("Double entry for %s (%s)"), $row['name'], $row['type']));
				}
			}
		}

		// UPDATE SOA RECORD
		if($updatesoa) {
			$this->updateSoa($this->post['extra']['domain_id']);
		}

		if($emptyentrys) {
			$this->msg(self::MSG_INFO, sprintf(_("%s were not added because content was empty."), $emptyentrys));
		}

		$this->msg(self::MSG_OK, _("Saved"));
		$this->cmd("reloadRecords(fields);");
	}

	public function toIpv6($ipv6) {
		$ipv6 = str_replace("::", ":X:", $ipv6);
		// remove heading and trailing :
		if(strpos($ipv6, "X") == strlen($ipv6)-2)
			$ipv6 = substr($ipv6, 0, -1);
		if(strpos($ipv6, "X") == 1)
			$ipv6 = substr($ipv6, 1);

		$ipv6e = explode(":", $ipv6);

		$ipfull = array();
		foreach($ipv6e as $element) {
			// element valid?
			if(strlen($element) > 4)
				return false;

			if($element != "X" && strlen($element) < 4)
				$element = str_pad($element, 4, "0", STR_PAD_LEFT);

			// insert fillers
			if($element == "X") {
				for($c = 0; $c <= (8-count($ipv6e)); $c++) {
					$ipfull[] = "0000";
				}
			}
			else {
				$ipfull[] = $element;
			}
		}
		$ipfull = implode(":", $ipfull);

		$check = explode(":", $ipfull);
		if(count($check) != 8)
			return false;

		return $ipfull;
	}


	private function findReverseZone($ptr) {
		$parts = explode(".", $ptr);

		while(count($parts)) {
			$zone_name = implode(".", $parts);
			$domain_id = $this->db->getOne("SELECT id FROM domains WHERE name = '".addslashes($zone_name)."'");

			if($domain_id)
				return array($domain_id, $zone_name);
			else
				$parts = array_slice($parts, 1);
		}

		return false;
	}

	private function getPTRName($ip) {
		if($this->isIpv4($ip)) {
			$ptr = explode(".", $ip);
			return implode(".", array_reverse($ptr)).".in-addr.arpa";
		}
		elseif($this->toIpv6($ip)) {
			$ipv6 = $this->toIpv6($ip);
			return	implode(
						".",
						str_split(
							str_replace(
								":",
								"",
								strrev(
									$ipv6
								)
							),
							1
						)
					).
					".ip6.arpa";
		}
		else
			return false;
	}

	private function createPTR($row) {
		$ptr = $this->getPTRName($row['content']);

		if($ptr) {
			list($domain_id, $zone_name) = $this->findReverseZone($ptr);

			// check if there is a PTR present if yes don´t create
			if($existant_name = $this->db->getOne("SELECT content FROM records WHERE name = '".addslashes($ptr)."'")) {
				$this->msg(self::MSG_WARN, "PTR not created, because ".$ptr." is already refering ".$existant_name."!");
				return;
			}

			if($domain_id) {
				// create reverse PTR
				$sql = "INSERT INTO records SET ";
				$sql.= "domain_id = ".(int)$domain_id.", ";
				$sql.= "name = '".addslashes($ptr)."', ";
				$sql.= "type = 'PTR', ";
				$sql.= "content = '".addslashes($row['name'])."', ";
				$sql.= "ttl = ".(int)$row['ttl'].", ";
				$sql.= "prio = 0, ";
				$sql.= "change_date = ".time();

				$this->db->query($sql);

				// $this->msg(self::MSG_INFO, "PTR ".$ptr." -> ".$row['name']." in ".$zone_name." erstellt!");
			}
		}
	}

	private function deletePTR($row) {
		$ptr = $this->getPTRName($row['content']);

		if($ptr) {
			list($domain_id, $zone_name) = $this->findReverseZone($ptr);

			if($domain_id) {
				$record_id = $this->db->getOne("SELECT id FROM records WHERE name = '".addslashes($ptr)."' AND content = '".addslashes($row['name'])."'");
				if($record_id) {
					$this->db->query("DELETE FROM records WHERE id = ".$record_id);
					// $this->msg(self::MSG_INFO, "PTR Record ".$ptr." -> ".$row['name']." in ".$zone_name." gelöscht!");
				}
			}
		}
	}


	/** HOOK POINTS **/
	private function preCreate($row) {

	}

	private function postCreate($row) {
		switch($row['type']) {
			case "A":
				$this->createPTR($row);
				break;
			case "AAAA":
				$this->createPTR($row);
				break;
			default:
				break;
		}
	}

	private function preUpdate($row) {
		$oldrow = $this->db->getRow("SELECT * FROM records WHERE id = ".(int)$row['id']);
		switch($row['type']) {
			case "A":
				$this->deletePTR($oldrow);
				break;
			case "AAAA":
				$this->deletePTR($oldrow);
				break;
			default:
				break;
		}
	}

	private function postUpdate($row) {
		switch($row['type']) {
			case "A":
				$this->createPTR($row);
				break;
			case "AAAA":
				$this->createPTR($row);
				break;
			default:
				break;
		}
	}

	private function preDelete($id) {
		$row = $this->db->getRow("SELECT * FROM records WHERE id = ".(int)$id);
		switch($row['type']) {
			case "A":
				$this->deletePTR($row);
				break;
			case "AAAA":
				$this->deletePTR($row);
				break;
		}
	}

	private function updateSoa($domain_id) {
		$db = $this->app->DB;

		$sql = "SELECT id, content FROM records WHERE domain_id = ".(int)$domain_id." AND type = 'SOA'";
		$row = $db->getRow($sql);
		if(!$row['id'])
			return;
		$soa = explode(" ", $row['content']);

		$now = date("Ymd", time())."01";

		if($now > $soa[2]) {
			$soa[2] = $now;
		}
		else {
			$soa[2]++;
		}

		$soa = implode(" ", $soa);

		$db->query("UPDATE records SET content = '".addslashes($soa)."' WHERE id = ".$row['id']);
		// $this->msg(self::MSG_INFO, "Neuer SOA Record: ".$soa);
	}


	private function checkRules($row) {
		$db = $this->app->DB;

		if(!$this->checkChars($row['name'])) {
			$this->msg(self::MSG_WARN, sprintf(_("%s (%s) contains invalid characters"), $row['name'], $row['type']));
		}
		switch($row['type']) {
			case "SOA":
				// is already present?
				$soacount = $db->getOne("SELECT COUNT(*) FROM records WHERE domain_id = ".(int)$row['domain_id']." AND type = 'SOA'");
				if($soacount == 1 && $row['action'] == "new") {
					$this->msg(self::MSG_ERROR, _("You can´t create a second SOA entry!"));
					return false;
				}
				break;
			case "MX":
			case "CNAME":
			case "NS":
				if($this->isIpv4($row['content']) || $this->isIpv6($row['content']))
					$this->msg(self::MSG_WARN, sprintf(_("The %s for %s should contain a hostname not a ip!"), $row['type'], $row['name']));
				break;
			case "A":
				if(!$this->isIpv4($row['content'])) {
					$this->msg(self::MSG_ERROR, sprintf(_("%s (A Record) doesn´t contain a ipv4 so no change was made!"), $row['name']));
					return false;
				}
				// this checks if there are already A records with the some content for this domain (this is helpfull if you have many hosts under one domain and use DNS for DHCP)
				$this->msg(self::MSG_OK, print_r($row, true));
				if($same_content_count = $db->getOne("SELECT COUNT(*) FROM records WHERE content = '".addslashes($row['content'])."'")) {
					if($row['action'] == "edit") {
						// if changed the result is correct
						if($row['content'] != $db->getOne("SELECT content FROM records WHERE id = ".$row['id'])) {
							$this->msg(self::MSG_WARN, "There are already ".$same_content_count." records refering the IP ".$row['content']);
						}
						// if not changed we found ourself and should check if more than one!
						else {
							$same_content_count--;
							if($same_content_count > 0) {
								$this->msg(self::MSG_WARN, sprintf(_("There are already %s records refering the IP %s"), $row['name'], $row['content']));
							}
						}
					}
					else {
						$this->msg(self::MSG_WARN, "There are already ".$same_content_count." records refering the IP ".$row['content']);
					}
				}
				break;
			case "AAAA":
				if(!$this->isIpv6($row['content'])) {
					$this->msg(self::MSG_ERROR, sprintf(_("%s (AAAA Record) doesn´t contain a ipv6 so no change was made!"), $row['name']));
					return false;
				}
				break;
		}

		return true;
	}

	private function isIpv4($ip) {
		$ip = explode(".", $ip);

		if(count($ip) != 4)
			return false;

		foreach($ip as $part) {
			if(!is_numeric($part))
				return false;

			if($part > 255 || $part < 0)
				return false;
		}

		return true;
	}

	private function isIpv6($ip)  {
  		if($this->toIpv6($ip))
  			return true;
  		else
  			return false;
	}

	private function checkChars($str) {
		return true;
	}

	private function checkDouble($domain_id, $name, $type) {
		$r = new Model_DomainRecords($this->app);
		$records = $r->getRecordlistByDomainId($domain_id);
		$c = 0;
		foreach($records as $record) {
			if($record['name'] == $name && $record['type'] == $type)
				$c++;
		}
		if($c > 1)
			return true;
	}
}