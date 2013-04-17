<?

namespace admin\backup;

api_expose('admin_backup_db_tables');
api_expose('admin\backup\api\delete');
api_expose('admin\backup\api\create');
api_expose('admin\backup\api\download');
api_expose('admin\backup\api\create_full');
api_expose('admin\backup\api\move_uploaded_file_to_backup');

 api_expose('admin\backup\api\restore');
//api_expose('admin\backup\api\get_bakup_location');
class api {

	private $file_q_sep = '; /* MW_QUERY_SEPERATOR */';

	function __construct() {
		//var_dump($_SERVER);
		//	print 1;
	}

	function api() {

	}

	function get_bakup_location() {

		if (defined('MW_CRON_EXEC')) {

		} else if (!is_admin()) {error("must be admin");
		}

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

	public function get() {
		if (!is_admin()) {error("must be admin");
		};
		$here = $this -> get_bakup_location();

		$files = glob("$here{*.sql,*.zip}", GLOB_BRACE);

		usort($files, function($a, $b) {
			return filemtime($a) < filemtime($b);
		});

		$backups = array();
		if (!empty($files)) {
			foreach ($files as $file) {

				if (strpos($file, '.sql', 1) or strpos($file, '.zip', 1)) {
					$mtime = filemtime($file);
					// Get time and date from filename
					$date = date("F d Y", $mtime);
					$time = date("H:i:s", $mtime);
					// Remove the sql extension part in the filename
					//	$filenameboth = str_replace('.sql', '', $file);
					$bak = array();
					$bak['filename'] = basename($file);
					$bak['date'] = $date;
					$bak['time'] = str_replace('_', ':', $time); ;
					$bak['size'] = filesize($file);

					$backups[] = $bak;
				}

			}

		}

		return $backups;

	}

	function delete($params) {
		if (!is_admin()) {error("must be admin");
		};

		// Get the provided arg
		$id = $params['id'];

		// Check if the file has needed args
		if ($id == NULL) {

			return array('error' => "You have not provided filename to be deleted.");

		}

		$here = $this -> get_bakup_location();
		$filename = $here . $id;

		if (is_file($filename)) {

			unlink($filename);
			return array('success' => "$id was deleted!");
		} else {

			$filename = $here . $id . '.sql';
			if (is_file($filename)) {
				unlink($filename);
				return array('success' => "$id was deleted!");
			}
		}

		//d($filename);
	}

	function download($params) {
		if (!is_admin()) {error("must be admin");
		};
		ini_set('memory_limit', '512M');
		set_time_limit(0);

		if (isset($params['id'])) {
			$id = $params['id'];
		} else if (isset($_GET['filename'])) {
			$id = $params['filename'];
		} else if (isset($_GET['file'])) {
			$id = $params['file'];
		}

		// Check if the file has needed args
		if ($id == NULL) {
			return array('error' => "You have not provided filename to download.");

			die();
		}

		$here = $this -> get_bakup_location();
		// Generate filename and set error variables

		$filename = $here . $id;
		if (!is_file($filename)) {
			return array('error' => "You have not provided a existising filename to download.");

			die();
		}
		// Check if the file exist.
		if (file_exists($filename)) {
			// Add headers
			$name = basename($filename);
			$type = 'sql';
			header('Cache-Control: public');
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename=' . $name);
			header('Content-Length: ' . filesize($filename));
			// Read file
			readfile($filename);
		} else {
			die('File does not exist');
		}
	}

	function move_uploaded_file_to_backup($params) {
		only_admin_access();

		if (!isset($params['src'])) {

			return array('error' => "You have not provided src to the file.");

		}

		$check = url2dir(trim($params['src']));
		$here = $this -> get_bakup_location();
		if (is_file($check)) {
			$fn = basename($check);
			if (copy($check, $here . $fn)) {
				@unlink($check);
				return array('success' => "$fn was uploaded!");

			} else {
				return array('error' => "Error moving uploaded file!");

			}

		} else {
			return array('error' => "Uploaded file is not found!");

		}

	}

	function restore($params) {
		if (!is_admin()) {error("must be admin");
		};

		ini_set('memory_limit', '512M');
		set_time_limit(0);

		// Get the provided arg
		if (isset($params['id'])) {
			$id = $params['id'];
		} else if (isset($_GET['filename'])) {
			$id = $params['filename'];
		} else if (isset($_GET['file'])) {
			$id = $params['file'];

		}

		// Check if the file has needed args
		if ($id == NULL) {

			return array('error' => "You have not provided a backup to restore.");
			die();
		}

		$here = $this -> get_bakup_location();
		// Generate filename and set error variables

		$filename = $here . $id;
		//	$filename = $here . $id . '.sql';
		$ext = get_file_extension($filename);
		$ext_error = false;

		$sql_file = false;

		switch ($ext) {
			case 'zip' :
				$exract_folder = md5(basename($filename));
				$unzip = new \mw\utils\Unzip();
				$target_dir = CACHEDIR.'backup_restore'.DS.$exract_folder.DS;
				if(!is_dir($target_dir)){
					mkdir_recursive($target_dir);
				}
				
				
				
				
				$result = $unzip -> extract($filename, $target_dir, $preserve_filepath = TRUE);
				
				return $result;
				break;

			case 'sql' :
				break;

			default :
				$ext_error = true;
				break;
		}

		if ($ext_error == true) {
			return array('error' => "Invalid file extension. The restore file must be .sql or .zip");
			die();
		}

		if (!is_file($filename)) {
			return array('error' => "You have not provided a existising backup to restore.");
			die();
		}

		if ($sql_file != false) {
			$db = c('db');
			$filename = $sql_file;
			// Settings
			$table = '*';
			$host = $DBhost = $db['host'];
			$user = $DBuser = $db['user'];
			$pass = $DBpass = $db['pass'];
			$name = $DBName = $db['dbname'];

			$sqlErrorText = '';
			$sqlErrorCode = 0;
			$sqlStmt = '';

			// Restore the backup
			//	$con = mysql_connect($DBhost, $DBuser, $DBpass);
			//if ($con !== false) {
			// Load and explode the sql file
			//	mysql_select_db("$DBName");
			$f = fopen($filename, "r+");
			$sqlFile = fread($f, filesize($filename));
			$sqlArray = explode($this -> file_q_sep, $sqlFile);

			// Process the sql file by statements
			foreach ($sqlArray as $stmt) {
				$stmt = str_replace('/* MW_TABLE_SEP */', ' ', $stmt);
				if (strlen($stmt) > 3) {
					try {
						//$result = mysql_query($stmt);

						db_q($stmt);
						print $stmt;
					} catch (Exception $e) {
						print 'Caught exception: ' . $e -> getMessage() . "\n";
						$sqlErrorCode = 1;
					}

					//d($stmt);
					//
				}
			}
			//}

			// Print message (error or success)
			if ($sqlErrorCode == 0) {
				print("Database restored successfully!\n");
				print("Backup used: " . $filename . "\n");
			} else {
				print("An error occurred while restoring backup!<br><br>\n");
				print("Error code: $sqlErrorCode<br>\n");
				print("Error text: $sqlErrorText<br>\n");
				print("Statement:<br/> $sqlStmt<br>");
			}

			// Close the connection
			// mysql_close($con);

			// Change the filename from sql to zip
			//$filename = str_replace('.sql', '.zip', $filename);

			// Files restored successfully
			print("Files restored successfully!<br>\n");
			print("Backup used: " . $filename . "<br>\n");
			clearcache();

		}

	}

	function create_full() {
		$start = microtime_float();
		if (defined('MW_CRON_EXEC')) {

		} else {
			only_admin_access();

		}

		ignore_user_abort(true);

		ini_set('memory_limit', '512M');
		set_time_limit(0);
		$here = $this -> get_bakup_location();
		$filename = $here . 'full_backup_' . date("Y-M-d-His") . '_' . uniqid() . '' . '.zip';

		$userfiles_folder = MW_USERFILES;

		$db_file = $this -> create();

		if (isset($db_file['filename'])) {
			$filename2 = $here . $db_file['filename'];
			if (is_file($filename2)) {
				$locations = array();
				$locations[] = MW_USERFILES;
				$locations[] = $filename2;
				$fileTime = date("D, d M Y H:i:s T");

				$zip = new \mw\utils\zip($filename);

				$zip -> setZipFile($filename);
				$zip -> setComment("Microweber backup of the userfiles folder and db.
				\n The Microweber version at the time of backup was {MW_VERSION}
				\nCreated on " . date('l jS \of F Y h:i:s A'));
				$zip -> addDirectoryContent(MW_USERFILES, '', true);
				$zip -> addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

				$zip1 = $zip -> finalize();
				$filename_to_return = $filename;
				$end = microtime_float();
				$end = round($end - $start, 3);
				return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);

			}
		}

	}

	function create() {
		ignore_user_abort(true);
		$start = microtime_float();

		if (defined('MW_CRON_EXEC')) {

		} else {
			only_admin_access();

		}
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

		$here = $this -> get_bakup_location();

		if (!is_dir($here)) {
			if (!mkdir($here)) {
				return false;
			}
		}
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		// Generate the filename for the backup file
		$index1 = $here . 'index.php';

		$filename_to_return = 'database_backup_' . date("Y-M-d-His") . uniqid() . '_' . $extname . '.sql';
		$filess = $here . $filename_to_return;
		touch($filess);
		touch($index1);

		$hta = $here . '.htaccess';
		if (!is_file($hta)) {
			touch($hta);
			file_put_contents($hta, 'Deny from all');
		}

		static $link;
		if ($link == false) {
			$link = mysql_connect($host, $user, $pass);
			mysql_select_db($name, $link);
		}

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

			if (stristr($table, MW_TABLE_PREFIX)) {

				$result = mysql_query('SELECT * FROM ' . $table);
				$num_fields = mysql_num_fields($result);

				// First part of the output - remove the table
				$return .= 'DROP TABLE ' . $table . $this -> file_q_sep . "\n\n\n";

				// Second part of the output - create table
				$res_ch = mysql_query('SHOW CREATE TABLE ' . $table);
				if ($res_ch == false) {
					$err = mysql_error();
					if ($err != false) {
						return array('error' => 'Query failed: ' . $err);
					}

				}
				$row2 = mysql_fetch_row($res_ch);
				$return .= "\n\n" . $row2[1] . $this -> file_q_sep . "\n\n\n";

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
						$return .= ")" . $this -> file_q_sep . "\n\n\n"; ;
					}
				}
				$return .= "\n\n\n";
			}
		}
		// Save the sql file
		$handle = fopen($filess, 'w+');
		$head = "/* Microweber database backup exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
		$head .= "/* MW_TABLE_PREFIX: " . MW_TABLE_PREFIX . " */ \n\n\n";
		$return = $head . $return;

		fwrite($handle, $return);
		fclose($handle);
		$end = microtime_float();
		$end = round($end - $start, 3);
		return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);
		// Close MySQL Connection
		//	mysql_close($link);
	}

}
