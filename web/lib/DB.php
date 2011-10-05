<?php

/**
 * Generic DB Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2009 Timo Witte
 * @licence GPLv3
 * 
 * @version 1.2
 */
class DB {

	private $cfg;
	private $connection;
	private $debug;

	/**
	 * Consructor please pass DB Config!
	 *
	 * @param unknown_type $cfg
	 */
	function __construct($cfg) {
    	$this->cfg =& $cfg;
    }

    public function get($db = "default") {
    	include 'configs/db.php';

    	return new DB($cfg['db'][$db]);
    }

    /**
     * Desctructor
     */
    function __destruct() {
    	// sessionhandlerprobs
    	#$this->disconnect();
    }

    /**
     * Connects to Database if not already connected
     */
    private function connect() {
    	if($this->connection)
    		return;
    	$this->connection = @mysql_connect($this->cfg['host'], $this->cfg['username'], $this->cfg['password']);
    	if($error = mysql_error())
    		die(__("Database connection failed: ").$error);
    	mysql_select_db($this->cfg['database'], $this->connection);
    	if($error = mysql_error($this->connection))
    		die(__("Database connection failed: ").$error);

	// we use unicode so get into utf8 mode ;)
    	mysql_query("SET NAMES 'utf8'", $this->connection);
   	mysql_query("SET CHARACTER SET 'utf8'", $this->connection);
    }

    /**
     * Disconnects
     */
    private function disconnect() {
    	if($this->connection)
    		mysql_close($this->connection);
    }

    /**
     * Do a normal Query.. Returns DBResult Object
     *
     * @param string $sql
     * @return Object DBResult
     */
    public function query($sql) {
		return $this->_doQuery($sql);
    }

    /**
     * Executes Query on DB Server
     *
     * @param string $sql
     * @return object DBResult
     */
    private function _doQuery($sql) {
    	$this->connect();
    	$start = microtime();
    	$query = mysql_query($sql, $this->connection);
    	$this->debug[] = array($sql, (microtime()-$start)*100000);
    	if($error = mysql_error($this->connection))
    		throw new Exception("Error in SQL Query: {$sql} ({$error})");
    	return new DB_Result($query);
    }

    /**
     * Returns single field from a single row as a String
     *
     * @param string $sql
     * @return string
     */
    public function getOne($sql) {
    	$r = $this->_doQuery($sql);
    	if ($r instanceof DB_Result) {
    		$d = $r->fetchRow(true);
    		return $d[0];
    	}
    	else
    		return $r;
    }

    /**
     * returns a single Row
     *
     * @param string $sql
     * @return array
     */
    public function getRow($sql) {
    	$r = $this->_doQuery($sql);
    	return $r->fetchRow();
    }

    /**
     * Returns asozive array for each row
     *
     * @param string $sql
     * @return array / multidimensional
     */
    public function getAll($sql) {
    	$r = $this->_doQuery($sql);
    	while($row = $r->fetchRow())
    		$ret[] = $row;
    	return isset($ret) ? $ret : array();
    }

    /**
     * Get the primary key value from the last insert operation
     *
     * @return int
     */
    public function insertId() {
    	return mysql_insert_id($this->connection);
    }

    /**
     * Return affected rows
     *
     * @return int
     */
    public function affectedRows() {
    	return mysql_affected_rows($this->connection);
    }

    /**
     * Get the Debug entry
     *
     * @return array
     */
    public function getDebug() {
    	return $this->debug;
    }

    /**
     * Clean the Debug
     *
     * @return void11
     */
    public function cleanDebug() {
    	unset($this->debug);
    }
}

class DB_Result {
	private $qr;

	function __construct($qr) {
		$this->qr = $qr;
	}

	public function fetchRow($num = 0) {
		if($num)
			return mysql_fetch_row($this->qr);
		else
			return mysql_fetch_assoc($this->qr);
	}
}
?>
