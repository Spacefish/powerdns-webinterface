<?php

// DOMAINS table
class Install {
	protected $app;
	protected $db;

	private $tables;
	
	public function __construct(Application $app) {
		$this->app = $app;
		$this->db = $this->app->DB;
		
		$this->tables = array(
			// pdns specific
			"domains" => array(
				"create" =>
					"create table domains ( ".
					"id INT auto_increment, ".
					"name VARCHAR(255) NOT NULL, ".
					"master VARCHAR(128) DEFAULT NULL, ".
					"last_check INT DEFAULT NULL, ".
					"type VARCHAR(6) NOT NULL, ".
					"notified_serial INT DEFAULT NULL, ".
					"account VARCHAR(40) DEFAULT NULL, ".
					"primary key (id) ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
				"fields" => array(),
				"index" => array(
					"name_index" => "CREATE UNIQUE INDEX name_index ON domains(name)"
				)
			),
			"records" => array(
				"create" =>
					"CREATE TABLE records ( ".
					"id INT auto_increment, ".
					"domain_id INT DEFAULT NULL, ".
					"name VARCHAR(255) DEFAULT NULL, ".
					"type VARCHAR(10) DEFAULT NULL, ".
					"content VARCHAR(64000) DEFAULT NULL, ".
					"ttl INT DEFAULT NULL, ".
					"prio INT DEFAULT NULL, ".
					"change_date INT DEFAULT NULL, ".
					"primary key(id) ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
				"fields" => array(
					"ordername" => "alter table records add ordername VARCHAR(255) BINARY",
					"auth" => "alter table records add auth bool"
				),
				"index" => array(
						"rec_name_index" => "CREATE INDEX rec_name_index ON records(name)",
						"nametype_index" => "CREATE INDEX nametype_index ON records(name,type)",
						"domain_id" => "CREATE INDEX domain_id ON records(domain_id)",
						"recordorder" => "create index recordorder on records (domain_id, ordername)"
				),
				"always" => array(
					"alter table records change column type type VARCHAR(10)"
				)
			),
			"supermasters" => array(
				"create" =>
					"create table supermasters ( ".
					"ip VARCHAR(25) NOT NULL, ".
					"nameserver VARCHAR(255) NOT NULL, ".
					"account VARCHAR(40) DEFAULT NULL ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci"
			),
			"domainmetadata" => array(
				"create" => 
					"create table domainmetadata ( ".
					"id INT auto_increment, ".
					"domain_id INT NOT NULL, ".
					"kind VARCHAR(16), ".
					"content TEXT, ".
					"primary key(id) ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
				"index" => array(
					"domainmetaidindex" => "create index domainmetaidindex on domainmetadata(domain_id)"
				)
			),
			"cryptokeys" => array(
				"create" =>
					"create table cryptokeys ( ".
					"id INT auto_increment, ".
					"domain_id INT NOT NULL, ".
					"flags INT NOT NULL, ".
					"active BOOL, ".
					"content TEXT, ".
					"primary key(id) ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
				"index" => array(
					"domainidindex" => "create index domainidindex on cryptokeys(domain_id)"
				)
			),
			"tsigkeys" => array(
				"create" => 
					"create table tsigkeys ( ".
					"id INT auto_increment, ".
					"name VARCHAR(255), ".
					"algorithm VARCHAR(50), ".
					"secret VARCHAR(255), ".
					"primary key(id) ".
					") Engine=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
				"index" => array(
					"namealgoindex" => "create unique index namealgoindex on tsigkeys(name, algorithm)"
				)
			),
			
			// webinterface specific
			"actionlog" => array(
				"create" => 
					"CREATE TABLE `actionlog` ( ".
					"`id` int(11) NOT NULL AUTO_INCREMENT, ".
					"`date` datetime NOT NULL, ".
					"`username` varchar(200) COLLATE utf8_unicode_ci NOT NULL, ".
					"`facility` text COLLATE utf8_unicode_ci NOT NULL, ".
					"`msg` text COLLATE utf8_unicode_ci NOT NULL, ".
					"PRIMARY KEY (`id`) ".
					") ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
			),
			"user" => array(
				"create" =>
					"CREATE TABLE `user` ( ".
					"`id` int(11) NOT NULL AUTO_INCREMENT, ".
					"`username` varchar(255) COLLATE utf8_unicode_ci NOT NULL, ".
					"`password` char(32) COLLATE utf8_unicode_ci NOT NULL, ".
					"`isAdmin` tinyint(4) NOT NULL, ".
					"`canCreateDomain` tinyint(4) NOT NULL, ".
					"`lastLogin` datetime NOT NULL, ".
					"`lastIp` char(15) COLLATE utf8_unicode_ci NOT NULL, ".
					"PRIMARY KEY (`id`) ".
					") ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
			),
			"session" => array(
				"create" => 
					"CREATE TABLE `session` ( ".
					"`sid` char(32) COLLATE utf8_unicode_ci NOT NULL, ".
					"`data` text COLLATE utf8_unicode_ci NOT NULL, ".
					"`lastchange` datetime NOT NULL, ".
					"PRIMARY KEY (`sid`) ".
					") ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci",
			),
			// "template" => array(),
			"perm" => array(
				"create" => 
					"CREATE TABLE `perm` ( ".
					"`userid` int(11) NOT NULL, ".
					"`domain_id` int(11) NOT NULL,".
					"PRIMARY KEY (`userid`,`domain_id`) ".
					") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
			)
		);
	}
	
	public function updateSchema() {
		$existingTablesQR = $this->db->getAll("SHOW TABLES");
		$existingTables = array();
		foreach($existingTablesQR as $table) {
			$existingTables[] = array_shift($table);
		}
		foreach($this->tables as $tablename => $tableinfo) {
			// if table does not exist, create it
			if(!in_array($tablename, $existingTables)) {
				$this->db->query($tableinfo["create"]);
			}
			
			// alter table to create non existing fields
			if(isset($tableinfo["fields"])) {
				$existingColumnsQR = $this->db->getAll("SHOW COLUMNS FROM ".$tablename.";");
				$existingColumns = array();
				foreach($existingColumnsQR as $column) {
					$existingColumns[] = $column["Field"];
				}
				foreach($tableinfo["fields"] as $fieldname => $fieldCreate) {
					if(!in_array($fieldname, $existingColumns)) {
						$this->db->query($fieldCreate);
					}
				}
			}
			
			// check if indexes exist, otherwise create them
			if(isset($tableinfo["index"])) {
				$existingIndexesQR = $this->db->getAll("SHOW INDEX FROM ".$tablename.";");
				$existingIndexes = array();
				foreach($existingIndexesQR as $index) {
					$existingIndexes[] = $index["Key_name"];
				}
				foreach($tableinfo["index"] as $indexname => $indexCreate) {
					if(!in_array($indexname, $existingIndexes)) {
						$this->db->query($indexCreate);
					}
				}
			}
		}
	}
}