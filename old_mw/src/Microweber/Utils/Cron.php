<?php
namespace Microweber\Utils;

/**
 * Makes virtual cronjobs from php
 *
 *
 * You can set custom function to be run at interval
 *
 * This class can simulate cronjob queues in PHP and it is useful to run various things in the background.
 * We use it in Microweber to make backups, sitemaps, send emails, etc.
 *
 * The jobs are executed with the register_shutdown_function and have minimum impact on the performance.
 *
 * @package Microweber\Utils
 * @link http://microweber.com
 * @author Peter Ivanov
 * @example
 * <pre>
 * $cron = new \Microweber\Utils\Cron();
 *
 * //you can execute any php function like that
 * $cron->job('another_job', '1 min', 'some_function', array('param1'=>'value'));
 *
 *
 * //you can run it on objects
 * $cron->job('run_something', '5 sec', array('myClass','Method'), array('some_param'=>'some_value'));
 *
 * //you can even run it on namespaced objects
 * $cron->job('make_full_backup', '5 sec', array('\Microweber\Utils\Backup','cronjob_exec'), array('param1'=>'value'));
 *
 * //you can run functions only once
 * $cron->job('run_something_once', 0, array('\Microweber\Utils\Backup','cronjob'));
 * </pre>
 *
 */
class Cron
{


    public $dir;
    public $ini_file = 'cron.ini.php';
    private $callbacks;

    // array to store user callbacks

    public function __construct()
    {

        if ($this->dir == false) {
            $this->setDir();
        }


        $this->callbacks = array();
        //register_shutdown_function(array($this, 'run'));
    }

    public function setDir($dir_name = false)
    {


        if ($dir_name == false and defined('MW_STORAGE_DIR') != false) {
            $dir_name = MW_STORAGE_DIR;
        } else if ($dir_name == false) {
            $dir_name = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        }

        if (!is_dir($dir_name)) {
            mkdir_recursive($dir_name);
        }


            $this->dir = $dir_name;


    }

    static function set($name, $interval, $callback, $params)
    {
        $obj = new Cron();
        $obj->job($name, $interval, $callback, $params);
    }

    public function delete_job($name)
    {

        if (is_dir($this->dir)) {

            $ini_file = $this->ini_file;
            $ini_file = $this->dir . $ini_file;
            if (is_file($ini_file)) {
                $ini_array = parse_ini_file($ini_file, true);
                //$ini_mtime = filemtime($ini_file);
                if (isset($ini_array[$name])) {
                    unset($ini_array[$name]);
                    $this->writeIni($ini_array);
                }

            }


        }
    }

    public function job($name, $interval, $callback, $params = false)
    {


        if (is_dir($this->dir)) {


            $ini_save = array();
            $ini_save[$name] = array();
            $ini_save[$name]['name'] = $name;
            $ini_save[$name]['interval'] = $interval;
            $ini_save[$name]['callback'] = $callback;

            if ($params != false) {
                $ini_save[$name]['params'] = $params;
            }

            return $this->writeIni($ini_save);

        }


    }

    function writeIni($array)
    {


        if (empty($array)) {
            return false;
        }

        $ini_file = $this->ini_file;


        $file = $this->dir . $ini_file;


        if (is_file($file)) {
            $ini_array = parse_ini_file($file, true);
        }
        $modify = false;
        if (isset($ini_array) and is_array($ini_array) and is_array($array)) {
            if ($ini_array != $array) {
                $ini_array = array_merge($ini_array, $array);
                $array = $ini_array;
                $modify = true;
            }
        } else {
            $array = $array;
            $modify = true;
        }


        if ($modify == true) {
            $res = array();
            foreach ($array as $key => $val) {
                if (is_array($val)) {
                    $res[] = "[$key]";
                    foreach ($val as $skey => $sval) {

                        if (is_array($sval)) {


                            if (is_array($sval)) {
                                foreach ($sval as $k => $v) {

                                    $res[] = "{$skey}[$k] = \"$v\"";
                                }
                            }


                        } else {
                            $res[] = "$skey = " . (is_numeric($sval) ? $sval : '"' . $sval . '"');

                        }
                    }
                } else {
                    $res[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
                }
                $res[] = "\r\n";
            }
            $ini_content = implode("\r\n", $res);
            $ini_content = '; <?php exit(); __halt_compiler(); ?> ' . "\r\n" . $ini_content;
            touch($file);
         //   d($file);
            file_put_contents($file, $ini_content);

        }

    }

    public function run()
    {

        ob_start();



        if (!defined('MW_CRON_EXEC')) {

            define('MW_CRON_EXEC', true);
        }

        $ini_mtime = false;

        if (is_dir($this->dir)) {

            $ini_file = $this->ini_file;
            $ini_file = $this->dir . $ini_file;
            if (is_file($ini_file)) {
                $ini_array = parse_ini_file($ini_file, true);
                //$ini_mtime = filemtime($ini_file);
            }


        }
        $allow = false;
        $now = time();
        if (isset($ini_array) and is_array($ini_array)) {
            $ini_save = array();
            $allow = false;
            foreach ($ini_array as $k => $item) {

                $only_once = false;
                if (isset($item['interval'])) {
                    if (intval($item['interval']) == 0) {
                        //  $allow = true;
                        //  $only_once = true;
                    } else {


                        $interval_time = strtotime('-' . $item['interval']);
                        $time_to_run = $now;
                        if (isset($item['last_run'])) {

                            $time_to_run = ($item['last_run']);
                        } else {
                            $time_to_run = 0;
                        }
                        // d($item);
                        if ($time_to_run < $interval_time) {
                            $allow = true;
                        } else {
                            $allow = false;
                        }

                    }
                } else {
                    $allow = false;
                }


                if ($allow == true) {
                    if (isset($item['callback'])) {
                        $callback = $item['callback'];
                        $handler = $callback;
                        $params = array();
                        if (isset($item['params']) and is_array($item['params'])) {
                            $params = $item['params'];
                        }
                        if (is_callable($handler)) {
                            if (is_array($handler)) {
                                $handler = new $callback[0];
                                $callback[0] = $handler;
                            }
                            call_user_func_array($callback, $params);
                        }
                    }
                    $item['last_run'] = $now;
                    $ini_save[$k] = $item;
                    if ($only_once == false) {

                    } else {
                        // unset($ini_save[$k]);
                        // $ini_save[$k] = array();
                    }
                }

            }

            // if (isset($allow) and $allow === true) {
            // d($ini_save);
            $this->writeIni($ini_save);
            // }
        }

        ob_end_clean();
    }


}