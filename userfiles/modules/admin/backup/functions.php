<?

function mw_get_backups_location() {
	if (!is_admin()) {error("must be admin");
	};
	static $loc;

	if ($loc != false) {
		return $loc;
	}
	$here = MW_ROOTPATH . "backup" . DS;
	$here2 = module_option('backup_location', 'admin/backup');
	if ($here2 != false and is_string($here2) and trim($here2) != 'default') {
		$here2 = normalize_path($here2, true);

		if (!is_dir($here2)) {
			mkdir_recursive($here2);
		}

		if (is_dir($here2)) {
			$here = $here2;
		}
	}

	if (!is_dir($here)) {
		if (!mkdir($here)) {
			return false;
		}
	}
	$loc = $here;
	return $here;
}

function mw_backups_list() {
	if (!is_admin()) {error("must be admin");
	};
	$here = mw_get_backups_location();

	$dir = opendir($here);
	$backups = array();
	while (false !== ($file = readdir($dir))) {

		// Print the filenames that have .sql extension
		if (strpos($file, '.sql', 1)) {

			// Get time and date from filename
			$date = substr($file, 9, 10);
			$time = substr($file, 20, 8);

			// Remove the sql extension part in the filename
			$filenameboth = str_replace('.sql', '', $file);
			$bak = array();
			$bak['filename'] = $filenameboth;
			$bak['date'] = $date;
			$bak['time'] = $time;
			//$bak['size'] = filesize( );

			$backups[] = $bak;
		}
	}
	return $backups;

}

function mw_backups_restore_tables() {
	if (!is_admin()) {
		
		error("must be admin");
	};
	// Get the provided arg
	$id = $_GET['id'];
	$db = c('db');
	$table = '*';
	$host = $DBhost = $db['host'];
	$user = $DBuser = $db['user'];
	$pass = $DBpass = $db['pass'];
	$name = $DBName = $db['dbname'];


	// Check if the file has needed args
	if ($id == NULL) {

		print("You have not provided a backup to restore.");
		die();
	}

	$here = mw_get_backups_location();
	// Generate filename and set error variables
	$filename = $here . 'backup/' . $id . '.sql';
	$sqlErrorText = '';
	$sqlErrorCode = 0;
	$sqlStmt = '';

	// Restore the backup
	$con = mysql_connect($DBhost, $DBuser, $DBpass);
	if ($con !== false) {
		// Load and explode the sql file
		mysql_select_db("$DBName");
		$f = fopen($filename, "r+");
		$sqlFile = fread($f, filesize($filename));
		$sqlArray = explode(';<|||||||>', $sqlFile);

		// Process the sql file by statements
		foreach ($sqlArray as $stmt) {
			if (strlen($stmt) > 3) {
				$result = mysql_query($stmt);
			}
		}
	}

	// Print message (error or success)
	if ($sqlErrorCode == 0) {
		print("Database restored successfully!<br>\n");
		print("Backup used: " . $filename . "<br>\n");
	} else {
		print("An error occurred while restoring backup!<br><br>\n");
		print("Error code: $sqlErrorCode<br>\n");
		print("Error text: $sqlErrorText<br>\n");
		print("Statement:<br/> $sqlStmt<br>");
	}

	// Close the connection
	//mysql_close($con);

	// Change the filename from sql to zip
	$filename = str_replace('.sql', '.zip', $filename);

	// Files restored successfully
	print("Files restored successfully!<br>\n");
	print("Backup used: " . $filename . "<br>\n");

}

api_expose('admin_backup_db_tables');
function admin_backup_db_tables() {
	if (!is_admin()) {error("must be admin");
	};
	$db = c('db');

	// Settings
	$table = '*';
	$host = $DBhost = $db['host'];
	$user = $DBuser = $db['user'];
	$pass = $DBpass = $db['pass'];
	$name = $DBName = $db['dbname'];

	// Set the suffix of the backup filename
	if ($table == '*') {
		$extname = 'all';
	} else {
		$extname = str_replace(",", "_", $table);
		$extname = str_replace(" ", "_", $extname);
	}

	$here = mw_get_backups_location();

	if (!is_dir($here)) {
		if (!mkdir($here)) {
			return false;
		}
	}
	ini_set('memory_limit', '512M');
	set_time_limit(0);
	// Generate the filename for the backup file
	$index1 = $here . 'index.php';
	$filess = $here . 'dbbackup_' . date("d.m.Y_H_i_s") . uniqid() . '_' . $extname;
	touch($filess);
	touch($index1);

	$link = mysql_connect($host, $user, $pass);
	mysql_select_db($name, $link);
	$return = "";
	$tables = '*';
	// Get all of the tables
	if ($tables == '*') {
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while ($row = mysql_fetch_row($result)) {
			$tables[] = $row[0];
		}
	} else {
		if (is_array($tables)) {
			$tables = explode(',', $tables);
		}
	}

	// Cycle through each provided table
	foreach ($tables as $table) {

		$result = mysql_query('SELECT * FROM ' . $table);
		$num_fields = mysql_num_fields($result);

		// First part of the output - remove the table
		$return .= 'DROP TABLE ' . $table . ';<|||||||>';

		// Second part of the output - create table
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
		$return .= "\n\n" . $row2[1] . ";<|||||||>\n\n";

		// Third part of the output - insert values into new table
		for ($i = 0; $i < $num_fields; $i++) {
			while ($row = mysql_fetch_row($result)) {
				$return .= 'INSERT INTO ' . $table . ' VALUES(';
				for ($j = 0; $j < $num_fields; $j++) {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = str_replace("\n", "\\n", $row[$j]);
					if (isset($row[$j])) {
						$return .= '"' . $row[$j] . '"';
					} else {
						$return .= '""';
					}
					if ($j < ($num_fields - 1)) {
						$return .= ',';
					}
				}
				$return .= ");<|||||||>\n";
			}
		}
		$return .= "\n\n\n";
	}

	// Save the sql file
	$handle = fopen($filess . '.sql', 'w+');
	fwrite($handle, $return);
	fclose($handle);

	// Close MySQL Connection
	//	mysql_close($link);
}
