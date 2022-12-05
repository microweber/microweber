<?php
/**
 * Class used to export and restore the database or the userfiles directory
 *
 * You can use it to create export of the site. The export will contain na sql export of the database
 * and also a zip file with userfiles directory.
 *
 *
 * @package utils
 */


namespace admin\developer_tools\template_exporter;


use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


api_expose_admin('admin/developer_tools/template_exporter/Worker/create_full');
api_expose_admin('admin/developer_tools/template_exporter/Worker/download');
api_expose_admin('admin/developer_tools/template_exporter/Worker/delete');


if (defined("INI_SYSTEM_CHECK_DISABLED") == false) {
    define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));
}


class Worker
{

    public $exports_folder = false;
    public $export_file = false;
    public $debug = false;
    public $app;
    /**
     * The export class is used for making or restoring a export
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


        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }


    }


    static function log_bg_action($back_log_action)
    {

        if ($back_log_action == false) {
            mw()->log_manager->delete("is_system=y&rel_type=export&user_ip=" . USER_IP);
        } else {
            $check = mw()->log_manager->get("order_by=created_at desc&one=true&is_system=y&created_at=[mt]30 min ago&field=action&rel_type=export&user_ip=" . USER_IP);

            if (is_array($check) and isset($check['id'])) {
                mw()->log_manager->save("is_system=y&field=action&rel_type=export&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
            } else {
                mw()->log_manager->save("is_system=y&field=action&rel_type=export&value=" . $back_log_action . "&user_ip=" . USER_IP);
            }
        }

    }


    function exec_create_full()
    {


        if (!defined('MW_export_STARTED')) {
            define('MW_export_STARTED', 1);
        } else {
            return false;
        }


        $start = microtime_float();
        if (defined('MW_CRON_EXEC')) {

        } else {
            must_have_access();

        }

        @ob_end_clean();

        ignore_user_abort(true);
        $back_log_action = "Preparing to zip";
        $this->log_action($back_log_action);
        ini_set('memory_limit', '512M');
        set_time_limit(0);
        $here = $this->get_bakup_location();
        $filename = $here . 'full_export_' . date("Y-M-d-His") . '_' . uniqid() . '' . '.zip';

        $userfiles_folder = userfiles_path();

        $locations = array();
        $locations[] = userfiles_path();
        //$locations[] = $filename2;
        $fileTime = date("D, d M Y H:i:s T");

        $db_file = $this->create();

        $zip = new \Microweber\Utils\Zip($filename);
        $zip->setZipFile($filename);
        $zip->setComment("Microweber export of the userfiles folder and db.
				\n The Microweber version at the time of export was {MW_VERSION}
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

        $back_log_action = "export was created for $end sec!";
        $this->log_action($back_log_action);

        sleep(5);
        $back_log_action = "rel_typeoad";
        $this->log_action($back_log_action);

        sleep(5);
        $this->log_action(false);
        return array('success' => "export was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);


    }


    function copyr($source, $dest)
    {
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

        $cache_id = 'export_queue';
        // $cache_id_folders = 'export_cron_folders' . (USER_IP);
        $cache_id_loc = 'export_progress';
        $cache_state_id = 'export_zip_state';

        $cache_content = $this->app->cache_manager->get($cache_id, 'export');
        $cache_lock = $this->app->cache_manager->get($cache_id_loc, 'export');
        $cache_state = $this->app->cache_manager->get($cache_state_id, 'export', 30);


        $time = time();
        $here = $this->get_bakup_location();

        //session_write_close();

        if ($cache_state == 'opened') {

            return $cache_content;
        }


        if ($cache_content == false or empty($cache_content)) {
            $this->app->cache_manager->save(false, $cache_id_loc, 'export');
            $this->app->cache_manager->save(false, $cache_id, 'export');


            return true;
        } else {


            $bak_fn = 'export_' . date("Y-M-d-His") . '_' . uniqid() . '';

            $filename = $here . $bak_fn . '.zip';

            if ($cache_lock == false or !is_array($cache_lock)) {


                $cache_lock = array();
                $cache_lock['processed'] = 0;
                $cache_lock['files_count'] = count($cache_content);
                $cache_lock['time'] = $time;
                $cache_lock['filename'] = $filename;
                $this->app->cache_manager->save($cache_lock, $cache_id_loc, 'export');
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


            $export_actions = $cache_content;

            static $mw_export_zip_obj;
            if (!is_object($mw_export_zip_obj)) {
                $mw_export_zip_obj = new  \ZipArchive();
            }
            $zip_opened = false;
            if (is_array($export_actions)) {
                $i = 0;

                $this->app->cache_manager->save($filename, $cache_id_loc, 'export');


                if (!$mw_export_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                    $zip_opened = 1;
                    return false;
                }
                $this->app->cache_manager->save('opened', $cache_state_id, 'export');

                $limit_per_turn = 20;

                foreach ($export_actions as $key => $item) {
                    $flie_ext = strtolower(get_file_extension($item));

                    if ($flie_ext == 'php' or $flie_ext == 'css' or $flie_ext == 'js') {
                        $limit_per_turn = 150;

                    }

                    if ($i > $limit_per_turn or $cache_lock == $item) {
                        if (isset($mw_export_zip_obj) and is_object($mw_export_zip_obj)) {
                            if ($zip_opened == 1) {
                                $mw_export_zip_obj->close();
                            }
                        }
                        $this->app->cache_manager->save('closed', $cache_state_id, 'export');
                    } else {

                        $cache_lock['processed']++;
                        $cache_lock['time'] = time();
                        $cache_lock['filename'] = $filename;


                        $precent = ($cache_lock['processed'] / $cache_lock['files_count']) * 100;
                        $precent = round($precent);
                        $cache_lock['percent'] = $precent;


                        $back_log_action = "Progress  {$precent}% ({$cache_lock['processed']}/{$cache_lock['files_count']}) <br><small>" . basename($item) . "</small>";
                        $this->log_action($back_log_action);

                        $this->app->cache_manager->save($cache_lock, $cache_id_loc, 'export');


                        if ($item == 'make_db_export') {

                            $limit_per_turn = 1;
                            $mw_export_zip_obj->close();
                            $this->app->cache_manager->save('closed', $cache_state_id, 'export');


                            $db_file = $this->create($bak_fn . '.sql');


                            if (!$mw_export_zip_obj->open($filename, ZIPARCHIVE::CREATE)) {
                                $zip_opened = 1;
                                return false;
                            }
                            $this->app->cache_manager->save('opened', $cache_state_id, 'export');


                            if (isset($db_file['filename'])) {
                                $filename2 = $here . $db_file['filename'];
                                if (is_file($filename2)) {
                                    $back_log_action = "Adding sql restore to zip";
                                    $this->log_action($back_log_action);
                                    $mw_export_zip_obj->addFile($filename2, 'mw_sql_restore.sql');
                                    //  $zip->addFile(file_get_contents($filename2), 'mw_sql_restore.sql', filectime($filename2));

                                }
                            }
                        } else {
                            $rel_typeative_loc = str_replace(userfiles_path(), '', $item);


                            $new_export_actions = array();


                            if (is_dir($item)) {
                                $mw_export_zip_obj->addEmptyDir($rel_typeative_loc);
                            } elseif (is_file($item)) {
                                // d($item);
                                //$rel_typeative_loc_dn = dirname($rel_typeative_loc);

                                //$mw_export_zip_obj->addFromString($rel_typeative_loc, file_get_contents($item));

                                $mw_export_zip_obj->addFile($item, $rel_typeative_loc);

                            }


                        }


                        unset($export_actions[$key]);


                        if (isset($new_export_actions) and !empty($new_export_actions)) {
                            $export_actions = array_merge($export_actions, $new_export_actions);
                            array_unique($export_actions);
                            $this->app->cache_manager->save($export_actions, $cache_id, 'export');

                        } else {
                            $this->app->cache_manager->save($export_actions, $cache_id, 'export');

                        }
                        //  d($export_actions[$key]);

                        if (empty($export_actions)) {
                            $this->app->cache_manager->save(false, $cache_id, 'export');

                        }

                    }
                    $i++;
                }

                $mw_export_zip_obj->close();
                $this->app->cache_manager->save('closed', $cache_state_id, 'export');
            }
        }

        // $this->app->cache_manager->save(false, $cache_id_loc, 'export');
        if (empty($export_actions)) {

            $this->log_action('done');

            $this->app->cache_manager->save(false, $cache_id, 'export');

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
            must_have_access();

        }
        $temp_db = false;

        // Settings
        $table = '*';


        // Set the suffix of the export filename
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
        // Generate the filename for the export file
        $index1 = $here . 'index.php';
        if ($filename == false) {
            $filename_to_return = 'database_export_' . date("Y-M-d-His") . uniqid() . '_' . $extname . '.sql';
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
        $head = "\xEF\xBB\xBF";
        $head = "\n";
        //  $head = "/* Microweber database exported on: " . date('l jS \of F Y h:i:s A') . " */ \n";
        // $head .= "/* get_table_prefix(): " . get_table_prefix() . " */ \n\n\n";
        file_put_contents($sql_bak_file, $head);
        $return = "";
        $tables = '*';
        if ($tables == '*') {
            $tables = array();
            $result = mw()->database_manager->get_tables_list();
            if (!empty($result)) {
                foreach ($result as $item) {
                    $tables[] = $item;
                }
            }


        } else {
            if (is_array($tables)) {
                $tables = explode(',', $tables);
            }
        }

        $back_log_action = "Starting database export";
        $this->log_action($back_log_action);
        // Cycle through each provided table
        foreach ($tables as $table) {
            $is_cms_table = false;

            if (get_table_prefix() == '') {
                $is_cms_table = 1;
            } elseif (stristr($table, get_table_prefix())) {
                $is_cms_table = 1;
            }

            if ($table == get_table_prefix() . 'users') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'content_fields_drafts') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'modules') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'elements') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'system_licenses') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'stats_users_online') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'rating') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'log') {
                $is_cms_table = false;
            } else if ($table == get_table_prefix() . 'countries') {
                $is_cms_table = false;
            }

            $is_the_table_empty = 'SELECT count(*) as qty FROM ' . $table;
            $is_the_table_empty = mw()->database_manager->query($is_the_table_empty, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
            if (!isset($is_the_table_empty[0]) or !isset($is_the_table_empty[0]['qty']) or $is_the_table_empty[0]['qty'] == 0) {
                $is_cms_table = false;
            }


            if ($table != false and $is_cms_table) {
                $back_log_action = "Backing up database table $table";
                $this->log_action($back_log_action);
                //$result = mysql_query('SELECT * FROM ' . $table);
                $qs = 'SELECT * FROM ' . $table;

                if ($table == get_table_prefix() . 'categories_items' or $table == get_table_prefix() . 'categories') {
                    $qs = 'SELECT * FROM ' . $table . " where rel_type='content' ";
                } else {
                    $qs = 'SELECT * FROM ' . $table;
                }


                $result = mw()->database_manager->query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
                $num_fields = count($result[0]);

                $table_without_prefix = $this->prefix_placeholder . str_ireplace(get_table_prefix(), "", $table);


//                $qs = 'SHOW CREATE TABLE ' . $table;
//                $res_ch = mw()->database_manager->query($qs, $cache_id = false, $cache_group = false, $only_query = false, $temp_db);
//                $row2 = array_values($res_ch[0]);
//
//
//                $create_table_without_prefix = str_ireplace(get_table_prefix(), $this->prefix_placeholder, $row2[1]);
//
//                $create_table_without_prefix = str_ireplace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $create_table_without_prefix);
//                $return = "\n\n" . $create_table_without_prefix . $this->file_q_sep . "\n\n\n";
//
//
//                $this->append_string_to_file($sql_bak_file, $return);
                // Third part of the output - insert values into new table
                //for ($i = 0; $i < $num_fields; $i++) {

                $this->log_action(false);
                if (!empty($result)) {
                    $table_accos = str_replace(get_table_prefix(), '', $table);
                    $columns = $this->app->database_manager->get_fields($table_accos);
                    foreach ($result as $row) {
                        $row = array_values($row);
                        $columns = array_values($columns);

                        $columns_q = false;
                        $columns_temp = array();
                        foreach ($columns as $column) {
                            //$columns_temp[] = "'" . $column . "'";
                            $columns_temp[] = $column ;
                        }
                        if (!empty($columns_temp)) {
                            $columns_q = implode(',', $columns_temp);
                            $columns_q = "(".$columns_q.")";
                        }

                        $return = 'REPLACE INTO ' . $table_without_prefix . ' ' . $columns_q . ' VALUES(';
                        for ($j = 0; $j < $num_fields; $j++) {
                            //$row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("'", "&rsquo;", $row[$j]);
                            //$row[$j] = str_replace("\n", "\\n", $row[$j]);
                            if (isset($row[$j])) {
                                $return .= "'" . $row[$j] . "'";
                                //$return .= '"' . $row[$j] . '"';
                            } else {
                                //$return .= '""';
                                $return .= "''";
                            }
                            if ($j < ($num_fields - 1)) {
                                $return .= ',';
                            }
                        }
                        $return .= ")" . $this->file_q_sep . "\n\n\n";
                        $this->append_string_to_file($sql_bak_file, $return);
                    }

                }
                $return = "\n\n\n";
                $this->append_string_to_file($sql_bak_file, $return);
            }

        }
        $this->log_action(false);
        $back_log_action = "Saving to file " . basename($filess);
        $this->log_action($back_log_action);

        $end = microtime_float();
        $end = round($end - $start, 3);
        $this->log_action(false);

        //mysql_close($link);

        return array('success' => "export was created for $end sec! $filename_to_return", 'filename' => $filename_to_return, 'runtime' => $end);
        // Close MySQL Connection
        //
    }

    function append_string_to_file($file_path, $string_to_append)
    {
        file_put_contents($file_path, $string_to_append, FILE_APPEND);

    }


    function get_bakup_location()
    {

        if (defined('MW_CRON_EXEC')) {

        } else if (!is_admin()) {
            error("must be admin");
        }

        $loc = $this->exports_folder;

        if ($loc != false) {
            return $loc;
        }
        $here = userfiles_path() . "export" . DS;

        if (!is_dir($here)) {
            mkdir_recursive($here);
            $hta = $here . '.htaccess';
            if (!is_file($hta)) {
                touch($hta);
                file_put_contents($hta, 'Deny from all');
            }
        }

        $here = userfiles_path() . "export" . DS . get_table_prefix() . DS;

        if (!is_dir($here)) {
            mkdir_recursive($here);
        }


        $loc = $here;


        $this->exports_folder = $loc;
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


        $export_actions = array();
        $export_actions[] = 'make_db_export';

        $userfiles_folder = userfiles_path();
        $media_folder = media_base_path();


        $all_images = $this->app->media_manager->get('limit=1000000');

        if (!empty($all_images)) {
            foreach ($all_images as $image) {
                if (isset($image['filename']) and $image['filename'] != false) {
                    $fn = url2dir($image['filename']);
                    if (is_file($fn)) {
                        $export_actions[] = $fn;
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

        $cust_css_dir = $media_folder . DS . 'content' . DS;
        if (is_dir($cust_css_dir)) {
            $more_folders = \rglob($cust_css_dir . '*', GLOB_NOSORT);
            if (!empty($more_folders)) {
                $folders = array_merge($folders, $more_folders);
            }
        }

//        $cust_css_dir = mw()->template->dir();
//        if (is_dir($cust_css_dir)) {
//            $more_folders = \rglob($cust_css_dir . '*', GLOB_NOSORT);
//            if (!empty($more_folders)) {
//                $folders = array_merge($folders, $more_folders);
//            }
//        }


        if (!empty($folders)) {
            $text_files = array();
            foreach ($folders as $fold) {
                if (!stristr($fold, 'export')) {
                    if (stristr($fold, '.php') or stristr($fold, '.js') or stristr($fold, '.css')) {
                        $text_files[] = $fold;
                    } else {
                        $export_actions[] = $fold;
                    }
                }
            }
            if (!empty($text_files)) {
                $export_actions = array_merge($text_files, $export_actions);
            }
        }

        $cache_id = 'export_queue';
        $cache_id_loc = 'export_progress';

        $cache_state_id = 'export_zip_state';

        $this->app->cache_manager->save($export_actions, $cache_id, 'export');
        $this->app->cache_manager->save(false, $cache_id_loc, 'export');
        $this->app->cache_manager->save(false, $cache_state_id, 'export');

        if (!defined('MW_NO_SESSION')) {
            define('MW_NO_SESSION', 1);
        }


    }


    function log_action($back_log_action)
    {

        if (mw_is_installed() == true) {


            if ($back_log_action == false) {
                $this->app->log_manager->delete("is_system=y&rel_type=export&user_ip=" . USER_IP);
            } else {
                $check = $this->app->log_manager->get("order_by=created_at desc&one=true&is_system=y&created_at=[mt]30 min ago&field=action&rel_type=export&user_ip=" . USER_IP);

                if (is_array($check) and isset($check['id'])) {
                    $this->app->log_manager->save("is_system=y&field=action&rel_type=export&value=" . $back_log_action . "&user_ip=" . USER_IP . "&id=" . $check['id']);
                } else {
                    $this->app->log_manager->save("is_system=y&field=action&rel_type=export&value=" . $back_log_action . "&user_ip=" . USER_IP);
                }
            }
        }
    }

    function move_uploaded_file_to_export($params)
    {
        must_have_access();

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
        $exports = array();
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
                    $bak['size'] = filesize($file);

                    $exports[] = $bak;
                }

            }

        }

        return $exports;

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


        $id = sanitize_path($id);
        $filename = sanitize_path($filename);

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
        $id = sanitize_path($id);


        // Check if the file has needed args
        if ($id == NULL) {
            return array('error' => "You have not provided filename to download.");

            die();
        }

        $here = $this->get_bakup_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        $filename = sanitize_path($filename);
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing filename to download.");

            die();
        }
        if (is_file($filename)) {
            $dl = new \MicroweberPackages\Utils\System\Files();
            return $dl->download_to_browser($filename);
        }
    }


}

$mw_export_zip_obj = false;
