<?php
/**
 * Class used to import and restore the database or the userfiles directory
 *
 * You can use it to create import of the site. The import will contain na sql export of the database
 * and also a zip file with userfiles directory.
 *
 *
 * @package utils
 */


namespace Microweber\Utils;


use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


api_expose('Utils\Import\delete');
api_expose('Utils\Import\create');
api_expose('Utils\Import\download');
api_expose('Utils\Import\create_full');
api_expose('Utils\Import\move_uploaded_file_to_import');

api_expose('Utils\Import\restore');
api_expose('Utils\Import\cronjob');

class Import
{

    public $imports_folder = false;
    public $import_file = false;
    public $app;
    /**
     * The import class is used for making or restoring exported files from other CMS
     *
     * @category  mics
     * @package   utils
     */


    private $file_q_sep = '; /* MW_QUERY_SEPERATOR */';
    private $prefix_placeholder = '/* MW_PREFIX_PLACEHOLDER */';

    function __construct($app = null)
    {

 

        if (!defined('USER_IP')) {
            if (isset($_SERVER["REMOTE_ADDR"])) {
                define("USER_IP", $_SERVER["REMOTE_ADDR"]);
            } else {
                define("USER_IP", '127.0.0.1');

            }
        }


        // if (!is_object($this->app)) {

        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw('application');
        }

        // }


    }

    static function bgworker_restore($params)
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }
        $url = site_url();
        // header("Location: " . $url);
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
        session_write_close();

        $back_log_action = "Restoring import";
        self::log_bg_action($back_log_action);
        $api = new \Microweber\Utils\Import();
        $api->exec_restore($params);

    }

    static function log_bg_action($back_log_action)
    {

        if ($back_log_action == false) {
            mw()->log->delete("is_system=y&rel=import&user_ip=" . USER_IP);
        } else {
            $check = mw()->log->get("order_by=created_on desc&one=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=import&user_ip=" . USER_IP);

            if (is_array($check) and isset($check['id'])) {
                mw()->log->save("is_system=y&field=action&rel=import&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
            } else {
                mw()->log->save("is_system=y&field=action&rel=import&value=" . $back_log_action . "&user_ip=" . USER_IP);
            }
        }

    }

    static function bgworker()
    {
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }
        $url = site_url();
        //header("Location: " . $url);
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
        session_write_close();

        //$back_log_action = "Creating full import";
        //self::log_bg_action($back_log_action);


        if (!defined('MW_import_BG_WORKER_STARTED')) {
            define('MW_import_BG_WORKER_STARTED', 1);
            $import_api = new \Microweber\Utils\Import();
            $import_api->exec_create_full();
            unset($import_api);
        } else {

        }

        //  exit();


    }

    function exec_create_full()
    {


        if (!defined('MW_IMPORT_STARTED')) {
            define('MW_IMPORT_STARTED', 1);
        } else {
            return false;
        }


        $start = microtime_float();
        if (defined('MW_CRON_EXEC')) {

        } else {
            only_admin_access();

        }

        @ob_end_clean();

        ignore_user_abort(true);
        $back_log_action = "Preparing to zip";
        $this->log_action($back_log_action);
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $here = $this->get_Import_location();
        $filename = $here . 'full_import_' . date("Y-M-d-His") . '_' . uniqid() . '' . '.zip';

        $userfiles_folder = MW_USERFILES;

        $locations = array();
        $locations[] = MW_USERFILES;
        //$locations[] = $filename2;
        $fileTime = date("D, d M Y H:i:s T");

        $db_file = $this->create();

        $zip = new \Microweber\Utils\Zip($filename);
        $zip->setZipFile($filename);
        $zip->setComment("Microweber import of the userfiles folder and db.
				\n The Microweber version at the time of import was {MW_VERSION}
				\nCreated on " . date('l jS \of F Y h:i:s A'));
        if (isset($db_file['filename'])) {
            $filename2 = $here . $db_file['filename'];
            if (is_file($filename2)) {
                $back_log_action = "Adding sql restore to zip";
                $this->log_action($back_log_action);
                $zip->addLargeFile($filename2, 'mw_sql_restore.sql', filectime($filename2), 'SQL Restore file');
                //  $zip->addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

            }
        }

        $this->log_action(false);

        $back_log_action = "Adding files to zip";
        $this->log_action($back_log_action);


        $zip->addDirectoryContent(MW_USERFILES, '', true);
        $back_log_action = "Adding userfiles to zip";
        $this->log_action($back_log_action);

        // $zip = $zip->finalize();
        $filename_to_return = $filename;
        $end = microtime_float();
        $end = round($end - $start, 3);

        $back_log_action = "import was created for $end sec!";
        $this->log_action($back_log_action);

        sleep(5);
        $back_log_action = "reload";
        $this->log_action($back_log_action);

        sleep(5);
        $this->log_action(false);
        return array('success' => "import was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);


    }

    function log_action($back_log_action)
    {

        if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {


            if ($back_log_action == false) {
                $this->app->log->delete("is_system=y&rel=import&user_ip=" . USER_IP);
            } else {
                $check = $this->app->log->get("order_by=created_on desc&one=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=import&user_ip=" . USER_IP);

                if (is_array($check) and isset($check['id'])) {
                    $this->app->log->save("is_system=y&field=action&rel=import&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
                } else {
                    $this->app->log->save("is_system=y&field=action&rel=import&value=" . $back_log_action . "&user_ip=" . USER_IP);
                }
            }
        }
    }

    function create($filename = false)
    {
        if (is_array($filename)) {
            $filename = false;
        }


        ignore_user_abort(true);
        $start = microtime_float();

        if (defined('MW_CRON_EXEC')) {

        } else {
            only_admin_access();

        }
        $temp_db = $db = $this->app->config('db');

        // Settings
        $table = '*';
        $host = $DBhost = $db['host'];
        $user = $DBuser = $db['user'];
        $pass = $DBpass = $db['pass'];
        $name = $DBName = $db['dbname'];

        // Set the suffix of the import filename
        if ($table == '*') {
            $extname = 'all';
        } else {
            $extname = str_replace(",", "_", $table);
            $extname = str_replace(" ", "_", $extname);
        }

        $here = $this->get_Import_location();

        if (!is_dir($here)) {
            if (!mkdir_recursive($here)) {

                $back_log_action = "Error the dir is not writable: " . $here;
                $this->log_action($back_log_action);


            } else {

            }
        }

        ini_set('memory_limit', '512M');
        set_time_limit(0);
        // Generate the filename for the import file
        $index1 = $here . 'index.php';
        if ($filename == false) {
            $filename_to_return = 'database_import_' . date("Y-M-d-His") . uniqid() . '_' . $extname . '.sql';
        } else {
            $filename_to_return = $filename;
        }

        $filess = $here . $filename_to_return;

        if (is_file($filess)) {
            return false;
        }


        touch($filess);
        touch($index1);

        $sql_bak_file = $filess;


        $hta = $here . '.htaccess';
        if (!is_file($hta)) {
            touch($hta);
            file_put_contents($hta, 'Deny from all');
        }

        $head = "/* Microweber database import exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
        $head .= "/* MW_TABLE_PREFIX: " . MW_TABLE_PREFIX . " */ \n\n\n";
        file_put_contents($sql_bak_file, $head);
        $return = "";
        $tables = '*';
        // Get all of the tables
        if ($tables == '*') {
            $tables = array();
            //$result = mysql_query('SHOW TABLES');
            $qs = 'SHOW TABLES';
            $result = mw('db')->query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
            //while ($row = mysql_fetch_row($result)) {
            //	$tables[] = $row[0];
            //}
            if (!empty($result)) {
                foreach ($result as $item) {
                    $item_vals = (array_values($item));
                    $tables[] = $item_vals[0];
                }
            }


        } else {
            if (is_array($tables)) {
                $tables = explode(',', $tables);
            }
        }

        $back_log_action = "Starting database import";
        $this->log_action($back_log_action);
        // Cycle through each provided table
        foreach ($tables as $table) {

            if (stristr($table, MW_TABLE_PREFIX)) {

                $back_log_action = "Backing up database table $table";
                $this->log_action($back_log_action);

                //$result = mysql_query('SELECT * FROM ' . $table);

                $qs = 'SELECT * FROM ' . $table;
                $result = mw('db')->query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);

                $num_fields = count($result[0]);
                //$num_fields = mysql_num_fields($result);
                $table_without_prefix = $this->prefix_placeholder . str_ireplace(MW_TABLE_PREFIX, "", $table);

                // First part of the output - remove the table
                //$return .= 'DROP TABLE IF EXISTS ' . $table_without_prefix . $this -> file_q_sep . "\n\n\n";
                $return = 'DROP TABLE IF EXISTS ' . $table_without_prefix . $this->file_q_sep . "\n\n\n";
                $this->append_string_to_file($sql_bak_file, $return);


                // Second part of the output - create table
//				$res_ch = mysql_query('SHOW CREATE TABLE ' . $table);
//				if ($res_ch == false) {
//					$err = mysql_error();
//					if ($err != false) {
//						return array('error' => 'Query failed: ' . $err);
//					}
//
//				}
//				$row2 = mysql_fetch_row($res_ch);


                $qs = 'SHOW CREATE TABLE ' . $table;
                $res_ch = mw('db')->query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
                $row2 = array_values($res_ch[0]);


                $create_table_without_prefix = str_ireplace(MW_TABLE_PREFIX, $this->prefix_placeholder, $row2[1]);

                //$return .= "\n\n" . $create_table_without_prefix . $this -> file_q_sep . "\n\n\n";


                $return = "\n\n" . $create_table_without_prefix . $this->file_q_sep . "\n\n\n";
                $this->append_string_to_file($sql_bak_file, $return);
                // Third part of the output - insert values into new table
                //for ($i = 0; $i < $num_fields; $i++) {

                $this->log_action(false);
                if (!empty($result)) {
                    foreach ($result as $row) {
                        $row = array_values($row);
                        $return = 'INSERT INTO ' . $table_without_prefix . ' VALUES(';
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
                        $return .= ")" . $this->file_q_sep . "\n\n\n";
                        $this->append_string_to_file($sql_bak_file, $return);
                        //$this->log_action(false);
                    }
                    //  }

//					while ($row = mysql_fetch_row($result)) {
//
//					}


                }
                $return = "\n\n\n";
                $this->append_string_to_file($sql_bak_file, $return);
            }

        }
        $this->log_action(false);
        $back_log_action = "Saving to file " . basename($filess);
        $this->log_action($back_log_action);
 

        //  unset($return);
        $end = microtime_float();
        $end = round($end - $start, 3);
        $this->log_action(false);

        //mysql_close($link);

        return array('success' => "import was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);
        // Close MySQL Connection
        //
    }

    function append_string_to_file($file_path, $string_to_append)
    {
        file_put_contents($file_path, $string_to_append, FILE_APPEND);

    }

    function cronjob_exec($params = false)
    {


        print 'import cronjob';


    }

    function restore($params)
    {


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

            return array('error' => "You have not provided a import to restore.");
            die();
        }

        /*

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
          session_write_close();

        */

        ob_start();
        $api = new \Microweber\Utils\Import();
        $this->app->cache->flush();
        $rest = $api->exec_restore($params);

        $this->app->cache->flush();

        ob_end_clean();
        return array('success' => "import was restored!");
        //$scheduler = new \Microweber\Utils\Events();

        // schedule a global scope function:
        // $scheduler->registerShutdownEvent("\Microweber\Utils\import::bgworker_restore", $params);

        return $rest;
    }

    function exec_restore($params = false)
    {
        if (!is_admin()) {
            return array('error' => "must be admin");

        }

        ignore_user_abort(true);

        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $loc = $this->import_file;

        // Get the provided arg
        if (isset($params['id'])) {
            $id = $params['id'];
        } else if (isset($_GET['filename'])) {
            $id = $params['filename'];
        } else if (isset($_GET['file'])) {
            $id = $params['file'];

        } else if ($loc != false) {
            $id = $loc;

        }

        // Check if the file has needed args
        if ($id == NULL) {

            return array('error' => "You have not provided a import to restore.");

        }

        $here = $this->get_Import_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        //	$filename = $here . $id . '.sql';
        $ext = get_file_extension($filename);
        $ext_error = false;

        $sql_file = false;

        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing import to restore.");
            die();
        }

        $temp_dir_restore = false;
        switch ($ext) {
            case 'zip' :
                $back_log_action = "Unzipping userfiles";
                $this->log_action($back_log_action);

                $exract_folder = md5(basename($filename));
                $unzip = new \Microweber\Utils\Unzip();
                $target_dir = MW_CACHE_DIR . 'import_restore' . DS . $exract_folder . DS;
                if (!is_dir($target_dir)) {
                    mkdir_recursive($target_dir);
                }

                $result = $unzip->extract($filename, $target_dir, $preserve_filepath = TRUE);

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
            $this->log_action($back_log_action);

            $db = $this->app->config('db');
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

            // Restore the import

            $f = fopen($filename, "r+");
            $sqlFile = fread($f, filesize($filename));
            $sqlArray = explode($this->file_q_sep, $sqlFile);

            // Process the sql file by statements
            foreach ($sqlArray as $stmt) {
                $stmt = str_replace('/* MW_TABLE_SEP */', ' ', $stmt);
                $stmt = str_ireplace($this->prefix_placeholder, MW_TABLE_PREFIX, $stmt);

                if (strlen($stmt) > 3) {
                    try {
                        mw('db')->q($stmt);
                        //	print $stmt;
                    } catch (Exception $e) {
                        print 'Caught exception: ' . $e->getMessage() . "\n";
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
                $this->log_action($back_log_action);

                print("Database restored successfully!\n");
                print("import used: " . $filename . "\n");
            } else {
                print("An error occurred while restoring import!<br><br>\n");
                print("Error code: $sqlErrorCode<br>\n");
                print("Error text: $sqlErrorText<br>\n");
                print("Statement:<br/> $sqlStmt<br>");
            }

            // Close the connection
            // mysql_close($con);

            // Change the filename from sql to zip
            //$filename = str_replace('.sql', '.zip', $filename);
            $back_log_action = "Database restored successfully!";
            $this->log_action($back_log_action);

            // Files restored successfully
            print("Files restored successfully!<br>\n");
            print("import used: " . $filename . "<br>\n");
            fclose($f);
            if ($temp_dir_restore != false) {
                unlink($filename);
            }

        }


        if (defined('MW_USERFILES')) {
            if (!is_dir(MW_USERFILES)) {
                mkdir_recursive(MW_USERFILES);
            }
        }


        if (defined('MW_MEDIA_DIR')) {
            if (!is_dir(MW_MEDIA_DIR)) {
                mkdir_recursive(MW_MEDIA_DIR);
            }
        }

        if ($temp_dir_restore != false and is_dir($temp_dir_restore)) {

            $srcDir = $temp_dir_restore;
            $destDir = MW_USERFILES;


            $this->copyr($srcDir, $destDir);

        }

        if (function_exists('mw_post_update')) {
            mw_post_update();
        }
        $back_log_action = "Cleaning up cache";
        $this->log_action($back_log_action);
        mw('cache')->clear();

        sleep(5);
        $this->log_action(false);

    }

    function copyr($source, $dest)
    {
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
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            if ($dest !== "$source/$entry") {
                $this->copyr("$source/$entry", "$dest/$entry");
            }
        }

        // Clean up
        $dir->close();
        return true;
    }

    function cronjob($params = false)
    {

        if (!defined("INI_SYSTEM_CHECK_DISABLED")) {
            define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
        }
        if (!defined("MW_NO_SESSION")) {
            define("MW_NO_SESSION", true);
        }

        if (!defined("IS_ADMIN")) {
            define("IS_ADMIN", true);
        }

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
            ini_set('memory_limit', '512M');
            //ini_set("set_time_limit", 600);

        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(600);
        }

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ignore_user_abort')) {
            ignore_user_abort();
        }

        $type = 'full';

        if (isset($params['type'])) {
            $type = trim($params['type']);
        }

        $cache_id = 'import_queue';
        // $cache_id_folders = 'import_cron_folders' . (USER_IP);
        $cache_id_loc = 'import_progress';
        $cache_state_id = 'import_zip_state';

        $cache_content = $this->app->cache->get($cache_id, 'import');
        $cache_lock = $this->app->cache->get($cache_id_loc, 'import');
        $cache_state = $this->app->cache->get($cache_state_id, 'import', 30);

        //$cache_folders = $this->app->cache->get($cache_id_folders, 'import');


        //$fileTime = date("D, d M Y H:i:s T");

        $time = time();
        $here = $this->get_Import_location();

        session_write_close();
        if ($cache_state == 'opened') {


            return true;
        }


        //   $filename2 = $here . 'test_' . date("Y-M-d-H") . '_' . crc32(USER_IP) . '' . '.zip';

        if ($cache_content == false or empty($cache_content)) {
            $this->app->cache->save(false, $cache_id_loc, 'import');
            $this->app->cache->save(false, $cache_id, 'import');

            $cron = new \Microweber\Utils\Cron();
            $cron->delete_job('make_full_import');
            return true;
        } else {


            $bak_fn = 'import_' . date("Y-M-d-His") . '_' . uniqid() . '';

            $filename = $here . $bak_fn . '.zip';

            if ($cache_lock == false or !is_array($cache_lock)) {


                $cache_lock = array();
                $cache_lock['processed'] = 0;
                $cache_lock['files_count'] = count($cache_content);
                $cache_lock['time'] = $time;
                $cache_lock['filename'] = $filename;
                $this->app->cache->save($cache_lock, $cache_id_loc, 'import');
                // return false;
            } else {
                if (isset($cache_lock['filename'])) {
                    $filename = $cache_lock['filename'];
                }

            }

            if (isset($cache_lock['time'])) {
                $time_sec = intval($cache_lock['time']);

                if (($time - 3) < $time_sec) {
                    // print 'time lock';
                    return false;
                }

            }


            $import_actions = $cache_content;

            global $mw_import_zip_obj;
            if (!is_object($mw_import_zip_obj)) {
                $mw_import_zip_obj = new  ZipArchive();
            }

            if (is_array($import_actions)) {
                $i = 0;

                $this->app->cache->save($filename, $cache_id_loc, 'import');


                if (!$mw_import_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                    return false;
                }
                $this->app->cache->save('opened', $cache_state_id, 'import');

                $limit_per_turn = 20;

                foreach ($import_actions as $key => $item) {
                    $flie_ext = strtolower(get_file_extension($item));

                    if ($flie_ext == 'php' or $flie_ext == 'css' or $flie_ext == 'js') {
                        $limit_per_turn = 150;

                    }


                    if ($i > $limit_per_turn or $cache_lock == $item) {
                        $mw_import_zip_obj->close();
                        $this->app->cache->save('closed', $cache_state_id, 'import');
                    } else {

                        $cache_lock['processed']++;
                        $cache_lock['time'] = time();
                        $cache_lock['filename'] = $filename;


                        $precent = ($cache_lock['processed'] / $cache_lock['files_count']) * 100;
                        $precent = round($precent);
                        $cache_lock['percent'] = $precent;


                        $back_log_action = "Progress  {$precent}% ({$cache_lock['processed']}/{$cache_lock['files_count']}) <br><small>" . basename($item) . "</small>";
                        $this->log_action($back_log_action);

                        $this->app->cache->save($cache_lock, $cache_id_loc, 'import');


                        if ($item == 'make_db_import') {

                            $limit_per_turn = 1;
                            $mw_import_zip_obj->close();
                            $this->app->cache->save('closed', $cache_state_id, 'import');


                            $db_file = $this->create($bak_fn . '.sql');


                            if (!$mw_import_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                                return false;
                            }
                            $this->app->cache->save('opened', $cache_state_id, 'import');


                            if (isset($db_file['filename'])) {
                                $filename2 = $here . $db_file['filename'];
                                if (is_file($filename2)) {
                                    $back_log_action = "Adding sql restore to zip";
                                    $this->log_action($back_log_action);
                                    $mw_import_zip_obj->addFile($filename2, 'mw_sql_restore.sql');
                                    //  $zip->addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

                                }
                            }
                        } else {
                            $relative_loc = str_replace(MW_USERFILES, '', $item);


                            $new_import_actions = array();


                            if (is_dir($item)) {
                                $mw_import_zip_obj->addEmptyDir($relative_loc);
                            } elseif (is_file($item)) {
                                // d($item);
                                //$relative_loc_dn = dirname($relative_loc);

                                //$mw_import_zip_obj->addFromString($relative_loc, file_get_contents($item));

                                $mw_import_zip_obj->addFile($item, $relative_loc);

                            }


                        }


                        unset($import_actions[$key]);


                        if (isset($new_import_actions) and !empty($new_import_actions)) {
                            $import_actions = array_merge($import_actions, $new_import_actions);
                            array_unique($import_actions);
                            $this->app->cache->save($import_actions, $cache_id, 'import');

                        } else {
                            $this->app->cache->save($import_actions, $cache_id, 'import');

                        }
                        //  d($import_actions[$key]);

                        if (empty($import_actions)) {
                            $this->app->cache->save(false, $cache_id, 'import');

                        }

                    }
                    $i++;
                }

                $mw_import_zip_obj->close();
                $this->app->cache->save('closed', $cache_state_id, 'import');
            }
        }

        // $this->app->cache->save(false, $cache_id_loc, 'import');
        if (empty($import_actions)) {
            $this->app->cache->save(false, $cache_id, 'import');

        }

    }


    function create_full()
    {

        if (!defined("INI_SYSTEM_CHECK_DISABLED")) {
            define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
        }


        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
            ini_set('memory_limit', '512M');
            //ini_set("set_time_limit", 600);

        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(600);
        }

        $cron = new \Microweber\Utils\Cron();

        $import_actions = array();
        $import_actions[] = 'make_db_import';

        $userfiles_folder = MW_USERFILES;


//        $it = new RecursiveDirectoryIterator($userfiles_folder);
//
//        foreach(new RecursiveIteratorIterator($it) as $file) {
//            $import_actions[] = $file;
//           // echo $file . "\n";
//
//        }


        $folders = \rglob($userfiles_folder . '*', GLOB_NOSORT);
        if (!empty($folders)) {
            $text_files = array();


            foreach ($folders as $fold) {
                if (!stristr($fold, 'import')) {
                    if (stristr($fold, '.php') or stristr($fold, '.js')  or stristr($fold, '.css')) {
                        $text_files[] = $fold;
                    } else {
                        $import_actions[] = $fold;


                    }
                }

            }

            if (!empty($text_files)) {
                $import_actions = array_merge($text_files, $import_actions);
            }

            //    rsort($import_actions);

        }
        $cache_id = 'import_queue';
        $cache_id_loc = 'import_progress';

        $cache_state_id = 'import_zip_state';
        //$import_actions[] = 'makesdfsdf_db_import';
        $this->app->cache->save($import_actions, $cache_id, 'import');
        $this->app->cache->save(false, $cache_id_loc, 'import');
        $this->app->cache->save(false, $cache_state_id, 'import');
        //$cron->Register('make_full_import', 0, '\Microweber\Utils\import::cronjob_exec');
        //$cron->job('make_full_import', 0, array('\Microweber\Utils\import','cronjob_exec'));

        // $cron->job('run_something_once', 0, array('\Microweber\Utils\import','cronjob'));
        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }

        $cron->job('make_full_import', '25 sec', array('\Microweber\Utils\import', 'cronjob'), array('type' => 'full'));
        //  $cron->job('another_job', 10, 'some_function' ,array('param'=>'val') );
        exit();


        $this->log_action(false);
        //  $url = site_url();
        //header("Location: " . $url);
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
        //@ob_end_flush();
        //Send the output buffer and turn output buffering off.
        flush();
        //Yes... flush again.
        session_write_close();

        $scheduler = new \Microweber\Utils\Events();

        // schedule a global scope function:
        $scheduler->registerShutdownEvent("\Microweber\Utils\import::bgworker");

        exit();
    }

    function move_uploaded_file_to_Import($params)
    {
        only_admin_access();

        if (!isset($params['src'])) {

            return array('error' => "You have not provided src to the file.");

        }

        $check = url2dir(trim($params['src']));
        $here = $this->get_Import_location();
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

    public function get()
    {
        if (!is_admin()) {
            error("must be admin");
        }
       
        $here = $this->get_Import_location();

        $files = glob("$here{*.sql,*.zip}", GLOB_BRACE);

        usort($files, function ($a, $b) {
            return filemtime($a) < filemtime($b);
        });

        $imports = array();
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
                    $bak['time'] = str_replace('_', ':', $time);
                    ;
                    $bak['size'] = filesize($file);

                    $imports[] = $bak;
                }

            }

        }

        return $imports;

    }

    // Read a file and display its content chunk by chunk

    function get_Import_location()
    {

        if (defined('MW_CRON_EXEC')) {

        } else if (!is_admin()) {
            error("must be admin");
        }

        $loc = $this->imports_folder;

        if ($loc != false) {
            return $loc;
        }
        $here = MW_USERFILES . "import" . DS;

        if (!is_dir($here)) {
            mkdir_recursive($here);
            $hta = $here . '.htaccess';
            if (!is_file($hta)) {
                touch($hta);
                file_put_contents($hta, 'Deny from all');
            }
        }

        $here = MW_USERFILES . "import" . DS . MW_TABLE_PREFIX . DS;

        $here2 = mw('option')->get('import_location', 'admin/import');
        if ($here2 != false and is_string($here2) and trim($here2) != 'default' and trim($here2) != '') {
            $here2 = normalize_path($here2, true);

            if (!is_dir($here2)) {
                mkdir_recursive($here2);
            }

            if (is_dir($here2)) {
                $here = $here2;
            }
        }


        if (!is_dir($here)) {
            mkdir_recursive($here);
        }


        $loc = $here;


        $this->imports_folder = $loc;
        return $here;
    }

    function delete($params)
    {
        if (!is_admin()) {
            error("must be admin");
        }


        // Get the provided arg
        $id = $params['id'];

        // Check if the file has needed args
        if ($id == NULL) {

            return array('error' => "You have not provided filename to be deleted.");

        }

        $here = $this->get_Import_location();
        $filename = $here . $id;


        $id = str_replace('..', '', $id);
        $filename = str_replace('..', '', $filename);

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

    function download($params)
    {
        if (!is_admin()) {
            error("must be admin");
        }

        ini_set('memory_limit', '512M');
        set_time_limit(0);

        if (isset($params['id'])) {
            $id = $params['id'];
        } else if (isset($_GET['filename'])) {
            $id = $params['filename'];
        } else if (isset($_GET['file'])) {
            $id = $params['file'];
        }
        $id = str_replace('..', '', $id);


        // Check if the file has needed args
        if ($id == NULL) {
            return array('error' => "You have not provided filename to download.");

            die();
        }

        $here = $this->get_Import_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        $filename = str_replace('..', '', $filename);
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
            $this->readfile_chunked($filename);
        } else {
            die('File does not exist');
        }
    }

    function readfile_chunked($filename, $retbytes = TRUE)
    {


        $filename = str_replace('..', '', $filename);

        $chunk_size = 1024 * 1024;
        $buffer = "";
        $cnt = 0;
        // $handle = fopen($filename, "rb");
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }


        while (!feof($handle)) {
            $buffer = fread($handle, $chunk_size);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

}

$mw_import_zip_obj = false;