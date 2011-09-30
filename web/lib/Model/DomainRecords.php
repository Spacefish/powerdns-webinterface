<?php

class Model_DomainRecords extends Model {
	private $typeOrder = array(
		"SOA" => 100,
		"NS" => 90,
		"MX" => 80,
		"TXT" => 70,
		"SPF" => 60,
		"CNAME" => 50,
		"AAAAA" => 40,
		"A"	=> 30,
	);
	public function getRecordlistByDomainId($domain_id) {
		$data = $this->db->getAll("SELECT * FROM records WHERE domain_id = ".(int)$domain_id." ORDER BY name ASC");
		usort($data, array($this, "valcmp"));
		
		return $this->postProcess($data, $this->getDomainName($domain_id));
	}

	private function getDomainName($id) {
		return $this->db->getOne("SELECT name FROM domains WHERE id = ".(int)$id);
	}

	private function postProcess($data, $domainname) {
		foreach($data as $key => $value) {
			if($value['name'] == $domainname)
				$data[$key]['name'] = "";
			else
				$data[$key]['name'] = str_replace(".".$domainname, "", $value["name"]);
		}
		return $data;
	}

	/**
	 * Advanced Sorting,
	 * 	1. sort by Type (as defined above)
	 *  2. natural sort by name
	 *  3. natural sort by content
	 *  4. pure amazing randomness ;)
	 * @param unknown_type $a
	 * @param unknown_type $b
	 * @return string|string|string|string|string|string
	 */
	private function valcmp($a, $b) {
		$ao = $this->typeOrder[$a['type']];
		$bo = $this->typeOrder[$b['type']];
		if($ao < $bo)
			return true;
		elseif($ao == $bo) {
			// echo "same type a:".$a['name']." b:".$b['name']."<br />";
			$natcmpName = strnatcasecmp($a['name'], $b['name']);
			if($natcmpName == 1)
				return true;
			// both the same
			elseif($natcmpName == 0) {
				$natcmpContent = strnatcasecmp($a['content'], $b['content']);
				if($natcmpContent == 1)
					return true;
				elseif($natcmpContent == 0)
					return rand(0,1) ? true : false;
				else
					return false;
			}
			else
				return false;
		}
		else
			return false;
	}

	public function getRecordlist($order = "name", $dir = "ASC") {
		return $this->db->getAll(
			"SELECT
			r.id, r.name, r.type, r.content, r.ttl, r.prio, r.change_date,
			d.name as domain_name, d.type as domain_type, d.id as domain_id
			FROM records r
			LEFT JOIN domains d ON d.id = r.domain_id
			ORDER BY ".$order." ".$dir
		);
	}

	public function searchRecordlist($search, $order, $dir) {
		$data = $this->db->getAll(
			"SELECT
			r.id, r.name, r.type, r.content, r.ttl, r.prio, r.change_date,
			d.name as domain_name, d.type as domain_type, r.domain_id
			FROM records r
			LEFT JOIN domains d ON d.id = r.domain_id
			WHERE r.name LIKE '%".addslashes($search)."%' OR r.content LIKE '%".addslashes($search)."%' OR r.type LIKE '%".addslashes($search)."%'
			ORDER BY ".$order." ".$dir
		);

		if(is_array($data)) {
			foreach($data as $key => $entry) {
				foreach($entry as $colname => $colvalue) {
					$data[$key][$colname] = str_replace($search, '<span class="search_highlight">'.$search.'</span>', $colvalue);
				}
			}
		}

		return $data;
	}

	public function newRecord($data) {
		$domainName = $this->getDomainName($data['domain_id']);

		$sql = "INSERT INTO records SET ";
		$sql.= "domain_id = ".(int)$data['domain_id'].", ";
		$sql.= "name = '".addslashes($data['name']).($data['name'] ? "." : "").$domainName."', ";
		$sql.= "type = '".addslashes($data['type'])."', ";
		$sql.= "content = '".addslashes($data['content'])."', ";
		$sql.= "ttl = ".(int)$data['ttl'].", ";
		$sql.= "prio = ".(int)$data['prio'].", ";
		$sql.= "change_date = ".(int)$data['change_date']." ";

		$this->db->query($sql);
	}

	public function updateRecord($id, $data) {
		$domainName = $this->getDomainName($data['domain_id']);

		$sql = "UPDATE records SET ";
		$sql.= "name = '".addslashes($data['name']).($data['name'] ? "." : "").$domainName."', ";
		$sql.= "type = '".addslashes($data['type'])."', ";
		$sql.= "content = '".addslashes($data['content'])."', ";
		$sql.= "ttl = ".(int)$data['ttl'].", ";
		$sql.= "prio = ".(int)$data['prio'].", ";
		$sql.= "change_date = ".(int)$data['change_date']." ";
		$sql.= "WHERE id = ".(int)$id;

		$this->db->query($sql);
	}

	public function deleteRecord($id) {
		$sql = "DELETE FROM records WHERE id = ".(int)$id;
		$this->db->query($sql);
	}
}

?>
