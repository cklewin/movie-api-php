<?php

if ((empty($_ENV['MYSQL_HOST_WRITE']) && empty($_ENV['MYSQL_HOST_WRITE']))
	|| empty($_ENV['MYSQL_USER'])
	|| empty($_ENV['MYSQL_PASSWORD'])) {
	die('missing environment variables for database connection');
}

class Database {
	private $mysqli = null;
	private $connection_type = null;

	function __construct($connection_type='read') {
		if (!empty($this->connection_type) && $this->connection_type == 'write') {
			error_log('connection already established');
			return true;
		}
		$this->connection_type = $connection_type;

		if ($this->connection_type === 'write') {
			$host = $_ENV['MYSQL_HOST_WRITE'];
		} else if ($this->connection_type === 'read') {
			$host = $_ENV['MYSQL_HOST_READ'];
		} else {
			error_log('database connection aborted: invalid connection type');
			return false;
		}

		$this->mysqli = mysqli_init();
		$this->mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
		$this->mysqli->real_connect($host, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);
  		$this->mysqli->set_charset("utf8mb4");

		return $this->verifyConnection();
	}

	private function verifyConnection() { 
		if ($this->mysqli->connect_error) {
			die("database connection failure: " . $this->mysqli->connect_error);
		}
		return true;
	}

	private function prepareBindExecute($sql, $types=null, $params=array()) {
		if (!$stmt = $this->mysqli->prepare($sql)) {
			error_log($this->mysqli->error);
			return false;
		}
		if ($types && !$stmt->bind_param($types, ...$params)) {
			error_log('prepared statement bind_param failed');
			return false;
		}
		if (!$stmt->execute()) {
			error_log($stmt->error);
			return false;
		}
		return $stmt;
	}

	public function read($sql, $types=null, $params=array()) { 
		if (!$stmt = $this->prepareBindExecute($sql, $types, $params)) {
			error_log('prepared statement failed, aborting database read');
			error_log('SQL query: ' . sprintf(str_replace('?', '%s', $sql), ...$params));
			return false;
		}

		$results = array();
		$res = $stmt->get_result();
		if ($res->num_rows) {
			$results = $res->fetch_all(MYSQLI_ASSOC);
		}

		$stmt->close();
		return $results;
	}

	public function write($sql, $types=null, $params=array()) { 
		if (!$stmt = $this->prepareBindExecute($sql, $types, $params)) {
			error_log('prepared statement failed, aborting database write');
			error_log('SQL query: ' . sprintf(str_replace('?', '%s', $sql), ...$params));
			return false;
		}

		$stmt->close();
		return $this->mysqli->insert_id;
	}
}

?>
