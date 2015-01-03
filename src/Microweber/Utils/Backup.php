<?php
/**
 * Class used to backup and restore the database or the userfiles directory
 *
 * You can use it to create backup of the site. The backup will contain na sql export of the database
 * and also a zip file with userfiles directory.
 *
 *
 * @package utils
 */


namespace Microweber\Utils;


use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


api_expose('Utils\Backup\delete');
api_expose('Utils\Backup\create');
api_expose('Utils\Backup\download');
api_expose('Utils\Backup\create_full');
api_expose('Utils\Backup\move_uploaded_file_to_backup');

api_expose('Utils\Backup\restore');
api_expose('Utils\Backup\cronjob');


api_expose('Microweber\Utils\Backup\delete');
api_expose('Microweber\Utils\Backup\create');
api_expose('Microweber\Utils\Backup\download');
api_expose('Microweber\Utils\Backup\create_full');
api_expose('Microweber\Utils\Backup\move_uploaded_file_to_backup');

api_expose('Microweber\Utils\Backup\restore');
api_expose('Microweber\Utils\Backup\cronjob');


if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}


class Backup
{

    public $backups_folder = false;
    public $backup_file = false;
    public $debug = false;
    public $app;
    /**
     * The backup class is used for making or restoring a backup
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
            $this->app = mw();
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
        //session_write_close();

        $back_log_action = "Restoring backup";
        self::log_bg_action($back_log_action);
        $api = new \Microweber\Utils\Backup();
        $api->exec_restore($params);

    }

    static function log_bg_action($back_log_action)
    {

        if ($back_log_action == false) {
            mw()->log_manager->delete("is_system=y&rel=backup&user_ip=" . USER_IP);
        } else {
            $check = mw()->log_manager->get("order_by=created_on desc&one=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);

            if (is_array($check) and isset($check['id'])) {
                mw()->log_manager->save("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
            } else {
                mw()->log_manager->save("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP);
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
        //session_write_close();

        //$back_log_action = "Creating full backup";
        //self::log_bg_action($back_log_action);


        if (!defined('MW_BACKUP_BG_WORKER_STARTED')) {
            define('MW_BACKUP_BG_WORKER_STARTED', 1);
            $backup_api = new \Microweber\Utils\Backup();
            $backup_api->exec_create_full();
            unset($backup_api);
        } else {

        }

        //  exit();


    }

    function exec_create_full()
    {


        if (!defined('MW_BACKUP_STARTED')) {
            define('MW_BACKUP_STARTED', 1);
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
        $here = $this->get_bakup_location();
        $filename = $here . 'full_backup_' . date("Y-M-d-His") . '_' . uniqid() . '' . '.zip';

        $userfiles_folder = userfiles_path();

        $locations = array();
        $locations[] = userfiles_path();
        //$locations[] = $filename2;
        $fileTime = date("D, d M Y H:i:s T");

        $db_file = $this->create();

        $zip = new \Microweber\Utils\Zip($filename);
        $zip->setZipFile($filename);
        $zip->setComment("Microweber backup of the userfiles folder and db.
				\n The Microweber version at the time of backup was {MW_VERSION}
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


        $zip->addDirectoryContent(userfiles_path(), '', true);
        $back_log_action = "Adding userfiles to zip";
        $this->log_action($back_log_action);

        // $zip = $zip->finalize();
        $filename_to_return = $filename;
        $end = microtime_float();
        $end = round($end - $start, 3);

        $back_log_action = "Backup was created for $end sec!";
        $this->log_action($back_log_action);

        sleep(5);
        $back_log_action = "reload";
        $this->log_action($back_log_action);

        sleep(5);
        $this->log_action(false);
        return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);


    }

    function cronjob_exec($params = false)
    {


        print 'backup cronjob';


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

            return array('error' => "You have not provided a backup to restore.");
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
          //session_write_close();

        */

        ob_start();
        $api = new \Microweber\Utils\Backup();
        $this->app->cache_manager->clear();
        $rest = $api->exec_restore($params);

        $this->app->cache_manager->clear();

        ob_end_clean();
        return array('success' => "Backup was restored!");
        //$scheduler = new \Microweber\Utils\Events();

        // schedule a global scope function:
        // $scheduler->registerShutdownEvent("\Microweber\Utils\Backup::bgworker_restore", $params);

        return $rest;
    }

    function exec_restore($params = false)
    {

        if (defined('MW_API_CALL')) {
            if (!is_admin()) {
                return array('error' => "must be admin");

            }
        }

        ignore_user_abort(true);

        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $loc = $this->backup_file;

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

            return array('error' => "You have not provided a backup to restore.");

        }

        $here = $this->get_bakup_location();
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
                $this->log_action($back_log_action);

                $exract_folder = md5(basename($filename));
                $unzip = new \Microweber\Utils\Unzip();
                $target_dir = mw_cache_path() . 'backup_restore' . DS . $exract_folder . DS;
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

            $db = $this->app->config_manager->get('db');
            $filename = $sql_file;
            // Settings


            $sqlErrorText = '';
            $sqlErrorCode = 0;
            $sqlStmt = '';

            // Restore the backup

            $f = fopen($filename, "r+");
            $sqlFile = fread($f, filesize($filename));
            $sqlArray = explode($this->file_q_sep, $sqlFile);
            if (!isset($sqlArray[1])) {
                $sqlArray = explode("\n", $sqlFile);

            }
            // Process the sql file by statements
            foreach ($sqlArray as $stmt) {
                $stmt = str_replace('/* MW_TABLE_SEP */', ' ', $stmt);
                $stmt = str_ireplace($this->prefix_placeholder, get_table_prefix(), $stmt);
                $stmt = str_replace("\xEF\xBB\xBF", '', $stmt);
                if ($this->debug) {
                    d($stmt);
                }

                if (strlen($stmt) > 3) {
                    try {
                        mw()->database->query($stmt);


                    } catch (Exception $e) {
                        print 'Caught exception: ' . $e->getMessage() . "\n";
                        $sqlErrorCode = 1;
                    }


                }
            }
            //}

            // Print message (error or success)
            if ($sqlErrorCode == 0) {
                $back_log_action = "Database restored successfully!";
                $this->log_action($back_log_action);

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
            $this->log_action($back_log_action);

            // Files restored successfully
            print("Files restored successfully!<br>\n");
            print("Backup used: " . $filename . "<br>\n");
            fclose($f);
            if ($temp_dir_restore != false) {
                @unlink($filename);
            }

        }


        if (userfiles_path()) {
            if (!is_dir(userfiles_path())) {
                mkdir_recursive(userfiles_path());
            }
        }


        if (media_base_path()) {
            if (!is_dir(media_base_path())) {
                mkdir_recursive(media_base_path());
            }
        }

        if ($temp_dir_restore != false and is_dir($temp_dir_restore)) {

            $srcDir = $temp_dir_restore;
            $destDir = userfiles_path();


            $copy = $this->copyr($srcDir, $destDir);


        }

        if (function_exists('mw_post_update')) {
            mw_post_update();
        }
        $back_log_action = "Cleaning up cache";
        $this->log_action($back_log_action);
        mw()->cache_manager->clear();


        $this->log_action(false);

    }

    function copyr($source, $dest)
    {
        // Simple copy for a file
        //$dest = normalize_path($dest,false);
        //$source = normalize_path($source,false);


        if (is_file($source)) {

            $dest = normalize_path($dest, false);
            $source = normalize_path($source, false);


            $dest_dir = dirname($dest);
            if (!is_dir($dest_dir)) {
                mkdir_recursive($dest_dir);
            }

            return copy($source, $dest);
        }

        // Make destination directory

        if (!is_dir($dest)) {
            mkdir_recursive($dest);
        }

        // Loop through the folder
        if (is_dir($source)) {
            $dir = dir($source);
            if ($dir != false) {
                while (false !== $entry = $dir->read()) {
                    // Skip pointers
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }

                    // Deep copy directories

                    if ($dest !== "$source/$entry" and $dest !== "$source" . DS . "$entry") {
                        $this->copyr("$source/$entry", "$dest/$entry");
                    }
                }
            }

            // Clean up
            $dir->close();
        }
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

        $cache_id = 'backup_queue';
        // $cache_id_folders = 'backup_cron_folders' . (USER_IP);
        $cache_id_loc = 'backup_progress';
        $cache_state_id = 'backup_zip_state';

        $cache_content = $this->app->cache_manager->get($cache_id, 'backup');
        $cache_lock = $this->app->cache_manager->get($cache_id_loc, 'backup');
        $cache_state = $this->app->cache_manager->get($cache_state_id, 'backup', 30);

        //$cache_folders = $this->app->cache_manager->get($cache_id_folders, 'backup');


        //$fileTime = date("D, d M Y H:i:s T");

        $time = time();
        $here = $this->get_bakup_location();

        //session_write_close();

        if ($cache_state == 'opened') {


            return $cache_content;
        }


        //   $filename2 = $here . 'test_' . date("Y-M-d-H") . '_' . crc32(USER_IP) . '' . '.zip';

        if ($cache_content == false or empty($cache_content)) {
            $this->app->cache_manager->save(false, $cache_id_loc, 'backup');
            $this->app->cache_manager->save(false, $cache_id, 'backup');


            return true;
        } else {


            $bak_fn = 'backup_' . date("Y-M-d-His") . '_' . uniqid() . '';

            $filename = $here . $bak_fn . '.zip';

            if ($cache_lock == false or !is_array($cache_lock)) {


                $cache_lock = array();
                $cache_lock['processed'] = 0;
                $cache_lock['files_count'] = count($cache_content);
                $cache_lock['time'] = $time;
                $cache_lock['filename'] = $filename;
                $this->app->cache_manager->save($cache_lock, $cache_id_loc, 'backup');
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
                    // return false;
                    return $cache_content;
                }

            }


            $backup_actions = $cache_content;

            global $mw_backup_zip_obj;
            if (!is_object($mw_backup_zip_obj)) {
                $mw_backup_zip_obj = new  ZipArchive();
            }
            $zip_opened = false;
            if (is_array($backup_actions)) {
                $i = 0;

                $this->app->cache_manager->save($filename, $cache_id_loc, 'backup');


                if (!$mw_backup_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                    $zip_opened = 1;
                    return false;
                }
                $this->app->cache_manager->save('opened', $cache_state_id, 'backup');

                $limit_per_turn = 20;

                foreach ($backup_actions as $key => $item) {
                    $flie_ext = strtolower(get_file_extension($item));

                    if ($flie_ext == 'php' or $flie_ext == 'css' or $flie_ext == 'js') {
                        $limit_per_turn = 150;

                    }

                    if ($i > $limit_per_turn or $cache_lock == $item) {
                        if (isset($mw_backup_zip_obj) and is_object($mw_backup_zip_obj)) {
                            if ($zip_opened == 1) {
                                $mw_backup_zip_obj->close();
                            }
                        }
                        $this->app->cache_manager->save('closed', $cache_state_id, 'backup');
                    } else {

                        $cache_lock['processed']++;
                        $cache_lock['time'] = time();
                        $cache_lock['filename'] = $filename;


                        $precent = ($cache_lock['processed'] / $cache_lock['files_count']) * 100;
                        $precent = round($precent);
                        $cache_lock['percent'] = $precent;


                        $back_log_action = "Progress  {$precent}% ({$cache_lock['processed']}/{$cache_lock['files_count']}) <br><small>" . basename($item) . "</small>";
                        $this->log_action($back_log_action);

                        $this->app->cache_manager->save($cache_lock, $cache_id_loc, 'backup');


                        if ($item == 'make_db_backup') {

                            $limit_per_turn = 1;
                            $mw_backup_zip_obj->close();
                            $this->app->cache_manager->save('closed', $cache_state_id, 'backup');


                            $db_file = $this->create($bak_fn . '.sql');


                            if (!$mw_backup_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                                $zip_opened = 1;
                                return false;
                            }
                            $this->app->cache_manager->save('opened', $cache_state_id, 'backup');


                            if (isset($db_file['filename'])) {
                                $filename2 = $here . $db_file['filename'];
                                if (is_file($filename2)) {
                                    $back_log_action = "Adding sql restore to zip";
                                    $this->log_action($back_log_action);
                                    $mw_backup_zip_obj->addFile($filename2, 'mw_sql_restore.sql');
                                    //  $zip->addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

                                }
                            }
                        } else {
                            $relative_loc = str_replace(userfiles_path(), '', $item);


                            $new_backup_actions = array();


                            if (is_dir($item)) {
                                $mw_backup_zip_obj->addEmptyDir($relative_loc);
                            } elseif (is_file($item)) {
                                // d($item);
                                //$relative_loc_dn = dirname($relative_loc);

                                //$mw_backup_zip_obj->addFromString($relative_loc, file_get_contents($item));

                                $mw_backup_zip_obj->addFile($item, $relative_loc);

                            }


                        }


                        unset($backup_actions[$key]);


                        if (isset($new_backup_actions) and !empty($new_backup_actions)) {
                            $backup_actions = array_merge($backup_actions, $new_backup_actions);
                            array_unique($backup_actions);
                            $this->app->cache_manager->save($backup_actions, $cache_id, 'backup');

                        } else {
                            $this->app->cache_manager->save($backup_actions, $cache_id, 'backup');

                        }
                        //  d($backup_actions[$key]);

                        if (empty($backup_actions)) {
                            $this->app->cache_manager->save(false, $cache_id, 'backup');

                        }

                    }
                    $i++;
                }

                $mw_backup_zip_obj->close();
                $this->app->cache_manager->save('closed', $cache_state_id, 'backup');
            }
        }

        // $this->app->cache_manager->save(false, $cache_id_loc, 'backup');
        if (empty($backup_actions)) {
            $this->app->cache_manager->save(false, $cache_id, 'backup');

        }
        return $cache_content;

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

        // Settings
        $table = '*';


        // Set the suffix of the backup filename
        if ($table == '*') {
            $extname = 'all';
        } else {
            $extname = str_replace(",", "_", $table);
            $extname = str_replace(" ", "_", $extname);
        }

        $here = $this->get_bakup_location();

        if (!is_dir($here)) {
            if (!mkdir_recursive($here)) {

                $back_log_action = "Error the dir is not writable: " . $here;
                $this->log_action($back_log_action);


            } else {

            }
        }

        ini_set('memory_limit', '512M');
        set_time_limit(0);
        // Generate the filename for the backup file
        $index1 = $here . 'index.php';
        if ($filename == false) {
            $filename_to_return = 'database_backup_' . date("Y-M-d-His") . uniqid() . '_' . $extname . '.sql';
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

        $head = "/* Microweber database backup exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
        $head .= "/* get_table_prefix(): " . get_table_prefix() . " */ \n\n\n";
        file_put_contents($sql_bak_file, $head);
        $return = "";
        $tables = '*';
        // Get all of the tables
        if ($tables == '*') {
            $tables = array();
            //$result = mysql_query('SHOW TABLES');
            $qs = 'SHOW TABLES';
            $result = mw()->database->query($qs, $cache_id = false, $cache_group = false, $only_query = false);
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

        $back_log_action = "Starting database backup";
        $this->log_action($back_log_action);
        // Cycle through each provided table
        foreach ($tables as $table) {
            $is_cms_table = false;

            if (get_table_prefix() == '') {
                $is_cms_table = 1;
            } elseif (stristr($table, get_table_prefix())) {
                $is_cms_table = 1;
            }


            if ($table != false and $is_cms_table) {
                $back_log_action = "Backing up database table $table";
                $this->log_action($back_log_action);
                //$result = mysql_query('SELECT * FROM ' . $table);
                $qs = 'SELECT * FROM ' . $table;
                $result = mw()->database->query($qs, $cache_id = false, $cache_group = false, $only_query = false);
                $num_fields = count($result[0]);
                //$num_fields = mysql_num_fields($result);
                $table_without_prefix = $this->prefix_placeholder . str_ireplace(get_table_prefix(), "", $table);
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
                $res_ch = mw()->database->query($qs, $cache_id = false, $cache_group = false, $only_query = false);
                $row2 = array_values($res_ch[0]);


                $create_table_without_prefix = str_ireplace(get_table_prefix(), $this->prefix_placeholder, $row2[1]);

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
        // Save the sql file
//		$handle = fopen($filess, 'w+');
//		$head = "/* Microweber database backup exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
//		$head .= "/* get_table_prefix(): " . get_table_prefix() . " */ \n\n\n";
//		$return = $head . $return;
//
//		fwrite($handle, $return);
//		fclose($handle);


        //  unset($return);
        $end = microtime_float();
        $end = round($end - $start, 3);
        $this->log_action(false);

        //mysql_close($link);

        return array('success' => "Backup was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);
        // Close MySQL Connection
        //
    }

    function append_string_to_file($file_path, $string_to_append)
    {
        file_put_contents($file_path, $string_to_append, FILE_APPEND);

    }


    function get_bakup_location()
    {

        if (defined('MW_API_CALL')) {
            if (defined('MW_CRON_EXEC')) {

            } else if (!is_admin()) {
                return ("must be admin");
            }
        }
        $loc = $this->backups_folder;

        if ($loc != false) {
            return $loc;
        }
        $here = userfiles_path() . "backup" . DS;

        if (!is_dir($here)) {
            mkdir_recursive($here);
            $hta = $here . '.htaccess';
            if (!is_file($hta)) {
                touch($hta);
                file_put_contents($hta, 'Deny from all');
            }
        }

        $here = userfiles_path() . "backup" . DS . get_table_prefix() . DS;

        $here2 = mw('option')->get('backup_location', 'admin/backup');
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


        $this->backups_folder = $loc;
        return $here;
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


        $backup_actions = array();
        $backup_actions[] = 'make_db_backup';

        $userfiles_folder = userfiles_path();
        $media_folder = media_base_path();


        $all_images = $this->app->media_manager->get('limit=1000000');

        if (!empty($all_images)) {
            foreach ($all_images as $image) {
                if (isset($image['filename']) and $image['filename'] != false) {
                    $fn = url2dir($image['filename']);
                    if (is_file($fn)) {
                        $backup_actions[] = $fn;
                    }
                }

            }
        }


        $host = (parse_url(site_url()));

        $host_dir = false;
        if (isset($host['host'])) {
            $host_dir = $host['host'];
            $host_dir = str_ireplace('www.', '', $host_dir);
            $host_dir = str_ireplace('.', '-', $host_dir);
        }


        $userfiles_folder_uploaded = $media_folder . DS . $host_dir . DS . 'uploaded' . DS;
        $userfiles_folder_uploaded = $media_folder . DS . $host_dir . DS;
        $userfiles_folder_uploaded = \normalize_path($userfiles_folder_uploaded);
        $folders = \rglob($userfiles_folder_uploaded . '*', GLOB_NOSORT);

        if (!is_array($folders)) {
            $folders = array();
        }
        $cust_css_dir = $userfiles_folder . 'css' . DS;
        if (is_dir($cust_css_dir)) {
            $more_folders = \rglob($cust_css_dir . '*', GLOB_NOSORT);
            if (!empty($more_folders)) {
                $folders = array_merge($folders, $more_folders);
            }
        }
        if (!empty($folders)) {
            $text_files = array();
            foreach ($folders as $fold) {
                if (!stristr($fold, 'backup')) {
                    if (stristr($fold, '.php') or stristr($fold, '.js') or stristr($fold, '.css')) {
                        $text_files[] = $fold;
                    } else {
                        $backup_actions[] = $fold;
                    }
                }
            }
            if (!empty($text_files)) {
                $backup_actions = array_merge($text_files, $backup_actions);
            }
        }

        $cache_id = 'backup_queue';
        $cache_id_loc = 'backup_progress';

        $cache_state_id = 'backup_zip_state';
        //$backup_actions[] = 'makesdfsdf_db_backup';
        $this->app->cache_manager->save($backup_actions, $cache_id, 'backup');
        $this->app->cache_manager->save(false, $cache_id_loc, 'backup');
        $this->app->cache_manager->save(false, $cache_state_id, 'backup');
        //$cron->Register('make_full_backup', 0, '\Microweber\Utils\Backup::cronjob_exec');
        //$cron->job('make_full_backup', 0, array('\Microweber\Utils\Backup','cronjob_exec'));

        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }

        return;


    }


    function log_action($back_log_action)
    {

        if (defined('MW_IS_INSTALLED') and MW_IS_INSTALLED == true) {


            if ($back_log_action == false) {
                $this->app->log_manager->delete("is_system=y&rel=backup&user_ip=" . USER_IP);
            } else {
                $check = $this->app->log_manager->get("order_by=created_on desc&one=true&is_system=y&created_on=[mt]30 min ago&field=action&rel=backup&user_ip=" . USER_IP);

                if (is_array($check) and isset($check['id'])) {
                    $this->app->log_manager->save("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
                } else {
                    $this->app->log_manager->save("is_system=y&field=action&rel=backup&value=" . $back_log_action . "&user_ip=" . USER_IP);
                }
            }
        }
    }

    function move_uploaded_file_to_backup($params)
    {
        only_admin_access();

        if (!isset($params['src'])) {

            return array('error' => "You have not provided src to the file.");

        }

        $check = url2dir(trim($params['src']));
        $here = $this->get_bakup_location();
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

    // Read a file and display its content chunk by chunk

    public function get()
    {
        if (!is_admin()) {
            error("must be admin");
        }

        $here = $this->get_bakup_location();

        $files = glob("$here{*.sql,*.zip}", GLOB_BRACE);
        if (is_array($files)) {
            usort($files, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });
        }
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
                    $bak['time'] = str_replace('_', ':', $time);;
                    $bak['size'] = filesize($file);

                    $backups[] = $bak;
                }

            }

        }

        return $backups;

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

        $here = $this->get_bakup_location();
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

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'memory_limit')) {
            ini_set('memory_limit', '512M');
        }
        if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'set_time_limit')) {
            set_time_limit(0);
        }

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

        $here = $this->get_bakup_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        $filename = str_replace('..', '', $filename);
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing filename to download.");

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

$mw_backup_zip_obj = false;