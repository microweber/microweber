<?

// $is_mysqli = function_exists('mysqli_connect');
$is_mysqli = false;
if ($is_mysqli != false) {
	$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);

	$result = $mysqli -> query($q);

	if (!$result) {
		$error['error'][] = $mysqli -> database -> error;

		return $error;
		// throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
	} else {

		if ($only_query == false) {
			$nwq = array();
			while ($row = $result -> fetch_array()) {

				$nwq[] = $row;
			}
			$q = $nwq;
		}
	}
} else {
	static $link;
	if ($link == false) {
		$link = mysql_connect($db['host'], $db['user'], $db['pass']);

		if (mysql_select_db($db['dbname']) == false) {
			$error['error'][] = 'Could not select database ' . $db['dbname'];
			return $error;
		}
	}
	if ($link == false) {
		$error['error'][] = 'Could not connect: ' . mysql_error();
		return $error;
	}

	// Performing SQL query

	$query = $q;
	$result = mysql_query($query);
	if (!$result) {
		$err = mysql_error();
		if (strstr($err, "doesn't exist")) {
			cache_clean_group('db');
			exec_action('mw_db_init_default');
			exec_action('mw_db_init_modules');
			$query = $q;
			$result = mysql_query($query);
			if (!$result) {
				$err = mysql_error();
				error('Query failed: ' . $err);
			}
		} else {

			error('Query failed: ' . $err);
		}
		return false;
	}
	$nwq = array();

	if (!$result) {
		$error['error'][] = 'Can\'t connect to the database';
		return $error;
	} else {
		if ($only_query == false) {
			if (is_bool($result)) {
				return $result;
			}
			if (!empty($result)) {
				//
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

					$nwq[] = $row;
				}
				$q = $nwq;

			}
		}
	}
	// Free resultset
	if ($only_query == false) {
		if (is_array($result)) {
			mysql_free_result($result);
		}
	}
	// Closing connection
	// mysql_close($link);
	// $result = null;
}
