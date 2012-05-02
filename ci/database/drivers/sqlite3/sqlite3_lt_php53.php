<?php
if (!defined('SQLITE3_ASSOC')) define('SQLITE3_ASSOC', 1);
if (!defined('SQLITE3_NUM')) define('SQLITE3_NUM', 2);
if (!defined('SQLITE3_BOTH')) define('SQLITE3_BOTH', 3);
if (!defined('SQLITE3_OPEN_READONLY')) define('SQLITE3_OPEN_READONLY', 0);
if (!defined('SQLITE3_OPEN_READWRITE')) define('SQLITE3_OPEN_READWRITE', 1);
if (!defined('SQLITE3_OPEN_CREATE')) define('SQLITE3_OPEN_CREATE', 2);

if (!class_exists('SQLite3')) {
	class SQLite3 {
		private $conn;

		public function changes ( ) {
			return sqlite3_changes($this->conn);
		}

		public function close ( ) {
			return sqlite3_close($this->conn);
		}

		function __construct ( $filename , $flags = 0, $encryption_key = NULL) {
			$this->open( $filename, $flags, $encryption_key);
		}

		public function createAggregate ( $name , $step_callback , $final_callback , $argument_count = -1 ) {
			return FALSE;
		}
		public function createFunction ( $name , $callback , $argument_count = -1 ) {
			return sqlite3_create_function($this->conn_id, $name, $argument_count, $callback);
		}

		public function escapeString ( $value ) {
			if (function_exists('sqlite_escape_')) return sqlite_escape_($value);
			else return str_replace('\'', '\'\'', $value); // FIXME: something else to escape?
		}

		public function exec ( $query ) {
			return sqlite3_exec($this->conn, $query);
		}

		public function lastErrorCode ( ) {
			return -1; /* not supported */
		}

		public function lastErrorMsg ( ) {
			return sqlite3_error($this->conn);
		}

		public function lastInsertRowID ( ) {
			return sqlite3_last_insert_rowid($this->conn);
		}

		public function loadExtension ( $shared_library ) {
			/* not supported */
			return FALSE;
		}

		public function open ( $filename , $flags, $encryption_key ) {
			$this->conn = sqlite3_open($filename);
			return $this->conn != NULL;
		}

		public function prepare ( $query ) {
			return NULL; /* not implemented */
		}

		public function query ( $query ) {
			if (strtoupper(substr(trim($query),0,6)) != 'SELECT') {
				return $this->exec($query);
			}
			$cursor = sqlite3_query($this->conn, $query);
			return new SQLite3Result($query, $cursor);
		}

		public function querySingle ( $query , $entire_row = false ) {
			$result = $this->query($query);
			if ($entire_row) $return = $result->fetchArray(SQLITE3_ASSOC);
			else list($return) = $result->fetchArray(SQLITE3_NUM);
			return $return;
		}

		public function version ( ) {
			$version = sqlite3_libversion();
			return array(
				'versionString' => $version,
				'versionNumber' => (int)str_replace($version, '.', '00') // FIXME: what's the logic here?
			);
		}
	}
}
if (!class_exists('SQLite3Result')) {
	class SQLite3Result {
		private $query;
		private $sql;

		public function __construct($sql, $query) {
			$this->sql = $sql;
			$this->query = $query;
		}

		public function columnName ( $column_number ) {
			return sqlite3_column_name($this->query, $column_number);
		}

		public function columnType ( $column_number ) {
			return sqlite3_column_type($this->query, $column_number);
		}

		public function fetchArray ( $mode = SQLITE3_BOTH ) {
			switch ($mode) {
				case SQLITE3_BOTH:
					$result = sqlite3_fetch_array($this->query);
					return array_merge($result, array_values($result));
				case SQLITE3_ASSOC:
					return sqlite3_fetch_array($this->query);
				case SQLITE3_NUM:
					return sqlite3_fetch($this->query);
			}
		}

		public function finalize ( ) {
			return sqlite3_query_close($this->query);
		}

		public function numColumns ( ) {
			return sqlite3_column_count($this->query);
		}

		public function reset ( ) {
			return FALSE; /* not implemented */
		}
	}
}
