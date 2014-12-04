<?php

namespace admin\backup;

api_expose('admin_backup_db_tables');
api_expose('mw/utils/Backup/delete');
api_expose('mw/utils/Backup/create');
api_expose('mw/utils/Backup/download');
api_expose('mw/utils/Backup/create_full');
api_expose('mw/utils/Backup/move_uploaded_file_to_backup');

api_expose('mw/utils/Backup/restore');

function mw_process_backup_in_background($a = '', $b = '') {

}

//api_expose('mw/utils/Backup/get_bakup_location');
class api {

	private $file_q_sep = '; /* MW_QUERY_SEPERATOR */';

	private $prefix_placeholder = '/* MW_PREFIX_PLACEHOLDER */';

	function __construct() {
		//var_dump($_SERVER);
		//	print 1;
	}

	function exec_restore($params) {
		if (!is_admin()) {error("must be admin");
		};
		ignore_user_abort(true);

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

		if (!is_file($filename)) {
			return array('error' => "You have not provided a existing backup to restore.");
			die();
		}

		$temp_dir_restore = false;
		switch ($ext) {
			case 'zip' :
				$back_log_action = "Unzipping userfiles";
				$this -> log_action($back_log_action);

				$exract_folder = md5(basename($filename));
				$unzip = new \mw\utils\Unzip();
				$target_dir = CACHEDIR . 'backup_restore' . DS . $exract_folder . DS;
				if (!is_dir($target_dir)) {
					mkdir_recursive($target_dir);
				}

				$result = $unzip -> extract($filename, $target_dir, $preserve_filepath = TRUE);

				$temp_dir_restore = $target_dir;

				$sql_restore = $target_dir . 'mw_sql_restore.sql';
				if (is_file($sql_restore)) {
					$sql_file = $sql_restore;
				}
				//return $result;
				break;

			case 'sql' :
				$sql_file = $filename;
				break;

			default :
				$ext_error = true;
				break;
		}

		if ($ext_error == true) {
			return array('error' => "Invalid file extension. The restore file must be .sql or .zip");
			die();
		}

		if ($sql_file != false) {
			$back_log_action = "Restoring database";
			$this -> log_action($back_log_action);

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
				$stmt = str_ireplace($this -> prefix_placeholder, MW_TABLE_PREFIX, $stmt);

				if (strlen($stmt) > 3) {
					try {
						//$result = mysql_query($stmt);

						db_q($stmt);
						//	print $stmt;
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
				$back_log_action = "Database restored successfully!";
				$this -> log_action($back_log_action);

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
			$back_log_action = "Database restored successfully!";
			$this -> log_action($back_log_action);

			// Files restored successfully
			print("Files restored successfully!<br>\n");
			print("Backup used: " . $filename . "<br>\n");
			fclose($f);
			if ($temp_dir_restore != false) {
				unlink($filename);
			}

		}

		if ($temp_dir_restore != false and is_dir($temp_dir_restore)) {

			$srcDir = $temp_dir_restore;
			$destDir = MW_USERFILES;

			$this -> copyr($srcDir, $destDir);

		}

		if (function_exists('mw_post_update')) {
			mw_post_update();
		}
		$back_log_action = "Cleaning up cache";
		$this -> log_action($back_log_action);
		clearcache();
		
		
		sleep(5);
 		$this -> log_action(false);

	}

	static function bgworker_restore($params) {
		if (!defined('MW_NO_SESSION')) {
			define('MW_NO_SESSION', 1);
		}
		$url = site_url();
		header("Location: " . $url);
		// redirect the url to the 'busy importing' page
		ob_end_clean();
		//Erase the output buffer
		header("Connection: close");
		//Tell the browser that the connection's closed
		ignore_user_abort(true);
		//Ignore the user's abort (which we caused with the redirect).
		set_time_limit(0);
		//Extend time limit
		ob_start();
		//Start output buffering again
		header("Content-Length: 0");
		//Tell the browser we're serious... there's really nothing else to receive from this page.
		ob_end_flush();
		//Send the output buffer and turn output buffering off.
		flush();
		//Yes... flush again.
		//session_write_close();

		$back_log_action = "Restoring backup";
		self::log_bg_action($back_log_action);
		$api = new \admin\backup\api();
		$api -> exec_restore($params);

	}

	function restore($params) {
		if (!defined('MW_NO_SESSION')) {
			define('MW_NO_SESSION', 1);
		}
		$id = null;
		if (isset($params['id'])) {
			$id = $params['id'];
		} else if (isset($_GET['filename'])) {
			$id = $params['filename'];
		} else if (isset($_GET['file'])) {
			$id = $params['file'];

		}

		if ($id == NULL) {

			return array('error' => "You have not provided a backup to restore.");
			die();
		}

		$url = site_url();
		header("Location: " . $url);
		// redirect the url to the 'busy importing' page
		ob_end_clean();
		//Erase the output buffer
		header("Connection: close");
		//Tell the browser that the connection's closed
		ignore_user_abort(true);
		//Ignore the user's abort (which we caused with the redirect).
		set_time_limit(0);
		//Extend time limit
		ob_start();
		//Start output buffering again
		header("Content-Length: 0");
		//Tell the browser we're serious... there's really nothing else to receive from this page.
		ob_end_flush();
		//Send the output buffer and turn output buffering off.
		flush();
		//Yes... flush again.
		//session_write_close();

		$scheduler = new \mw\utils\Events();

		// schedule a global scope function:
		$scheduler -> registerShutdownEvent("\admin\backup\api::bgworker_restore", $params);

		exit();
	}

	static function bgworker() {
		if (!defined('MW_NO_SESSION')) {
			define('MW_NO_SESSION', 1);
		}
		$url = site_url();
		header("Location: " . $url);
		// redirect the url to the 'busy importing' page
		ob_end_clean();
		//Erase the output buffer
		header("Connection: close");
		//Tell the browser that the connection's closed
		ignore_user_abort(true);
		//Ignore the user's abort (which we caused with the redirect).
		set_time_limit(0);
		//Extend time limit
		ob_start();
		//Start output buffering again
		header("Content-Length: 0");
		//Tell the browser we're serious... there's really nothing else to receive from this page.
		ob_end_flush();
		//Send the output buffer and turn output buffering off.
		flush();
		//Yes... flush again.
		//session_write_close();

		$back_log_action = "Creating full backup";
		self::log_bg_action($back_log_action);
		$api = new \admin\backup\api();
		$api -> exec_create_full();

	}

	function create_full() {
		if (!defined('MW_NO_SESSION')) {
			define('MW_NO_SESSION', 1);
		}

		$url = site_url();
		header("Location: " . $url);
		// redirect the url to the 'busy importing' page
		ob_end_clean();
		//Erase the output buffer
		header("Connection: close");
		//Tell the browser that the connection's closed
		ignore_user_abort(true);
		//Ignore the user's abort (which we caused with the redirect).
		set_time_limit(0);
		//Extend time limit
		ob_start();
		//Start output buffering again
		header("Content-Length: 0");
		//Tell the browser we're serious... there's really nothing else to receive from this page.
		ob_end_flush();
		//Send the output buffer and turn output buffering off.
		flush();
		//Yes... flush again.
		//session_write_close();

		$scheduler = new \mw\utils\Events();

		// schedule a global scope function:
		$scheduler -> registerShutdownEvent("\admin\backup\api::bgworker");

		exit();
	}

	function exec_create_full() {
		$start = microtime_float();
		if (defined('MW_CRON_EXEC')) {

		} else {
			only_admin_access();

		}
		$back_log_action = "Adding userfiles to zip";
		$this -> log_action($back_log_action);
		@ob_end_clean();

		ignore_user_abort(true);

		ini_set('memory_limit', '512M');
		set_time_limit(0);
		$here = $this -> get_bakup_location();
		$filename = $here . 'full_backup_' . date("Y-M-d-His") . '_' . uniqid() . '' . '.zip';

		$userfiles_folder = MW_USERFILES;

		$locations = array();
		$locations[] = MW_USERFILES;
		//$locations[] = $filename2;
		$fileTime = date("D, d M Y H:i:s T");

		$zip = new \mw\utils\Zip($filename);
		$zip -> setZipFile($filename);
		$zip -> setComment("Microweber backup of the userfiles folder and db.
				\n The Microweber version at the time of backup was {MW_VERSION}
				\nCreated on " . date('l jS \of F Y h:i:s A'));
		$zip -> addDirectoryContent(MW_USERFILES, '', true);

		$db_file = $this -> create();
		if (isset($db_file['filename'])) {
			$filename2 = $here . $db_file['filename'];
			if (is_file($filename2)) {

				$zip -> addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

			}

			$zip1 = $zip -> finalize();
			$filename_to_return = $filename;
			$end = microtime_float();
			$end = round($end - $start, 3);

			$back_log_action = "Backup was created for $end sec!";
			$this -> log_action($back_log_action);

			sleep(5);
			$back_log_action = "reload";
			$this -> log_action($back_log_action);

			sleep(5);
			$this -> log_action(false);
			return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);

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

		//static $link;
		//if ($link == false) {
		$link = mysql_connect($host, $user, $pass);
		mysql_select_db($name, $link);
		//}

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

		$back_log_action = "Starting database backup";
		$this -> log_action($back_log_action);
		// Cycle through each provided table
		foreach ($tables as $table) {

			if (stristr($table, MW_TABLE_PREFIX)) {

				$back_log_action = "Backing up database table $table";
				$this -> log_action($back_log_action);

				$result = mysql_query('SELECT * FROM ' . $table);
				$num_fields = mysql_num_fields($result);
				$table_without_prefix = $this -> prefix_placeholder . str_ireplace(MW_TABLE_PREFIX, "", $table);

				// First part of the output - remove the table
				$return .= 'DROP TABLE IF EXISTS ' . $table_without_prefix . $this -> file_q_sep . "\n\n\n";

				// Second part of the output - create table
				$res_ch = mysql_query('SHOW CREATE TABLE ' . $table);
				if ($res_ch == false) {
					$err = mysql_error();
					if ($err != false) {
						return array('error' => 'Query failed: ' . $err);
					}

				}
				$row2 = mysql_fetch_row($res_ch);

				$create_table_without_prefix = str_ireplace(MW_TABLE_PREFIX, $this -> prefix_placeholder, $row2[1]);

				$return .= "\n\n" . $create_table_without_prefix . $this -> file_q_sep . "\n\n\n";

				// Third part of the output - insert values into new table
				for ($i = 0; $i < $num_fields; $i++) {
					while ($row = mysql_fetch_row($result)) {
						$return .= 'INSERT INTO ' . $table_without_prefix . ' VALUES(';
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
		$back_log_action = "Saving to file " . basename($filess);
		$this -> log_action($back_log_action);
		// Save the sql file
		$handle = fopen($filess, 'w+');
		$head = "/* Microweber database backup exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
		$head .= "/* MW_TABLE_PREFIX: " . MW_TABLE_PREFIX . " */ \n\n\n";
		$return = $head . $return;

		fwrite($handle, $return);
		fclose($handle);
		$end = microtime_float();
		$end = round($end - $start, 3);
		$this -> log_action(false);

		mysql_close($link);

		return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);
		// Close MySQL Connection
		//
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

	function copyr($source, $dest) {
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			mkdir_recursive($dest);
		}

		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir -> read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}

			// Deep copy directories
			if ($dest !== "$source/$entry") {
				$this -> copyr("$source/$entry", "$dest/$entry");
			}
		}

		// Clean up
		$dir -> close();
		return true;
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

		if (!is_dir($here)) {
			mkdir_recursive($here);
			$hta = $here . '.htaccess';
			if (!is_file($hta)) {
				touch($hta);
				file_put_contents($hta, 'Deny from all');
			}
		}

		$here = MW_ROOTPATH . "backup" . DS . MW_TABLE_PREFIX . DS;

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

	static function log_bg_action($back_log_action) {

		if ($back_log_action == false) {
			delete_log("is_system=y&rel=backup&user_ip=" . USER_IP);
		} else {
			$check = get_log("order_by=created_at desc&one=true&is_system=y&created_at=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);

			if (isarr($check) and isset($check['id'])) {
				save_log("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
			} else {
				save_log("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP);
			}
		}

	}

	function log_action($back_log_action) {

		if ($back_log_action == false) {
			delete_log("is_system=y&rel=backup&user_ip=" . USER_IP);
		} else {
			$check = get_log("order_by=created_at desc&one=true&is_system=y&created_at=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);

			if (isarr($check) and isset($check['id'])) {
				save_log("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
			} else {
				save_log("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP);
			}
		}

	}

}
