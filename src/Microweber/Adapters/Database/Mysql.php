<?php
namespace Microweber\Adapters\Database;
     

class Mysql
{

    /**
     * An instance of the Microweber Application class
     *
     * @var $app
     */
    public $app;
    /**
     * An instances of the db link
     *
     * @var $db_links
     */
    public $db_links = array();
    /**
     * Latest db link
     *
     * @var $db_link
     */
    public $db_link = false;
    /**
     * Array with the connection settings
     *
     * @var $connection_settings
     * $connection_settings['host']
     * $connection_settings['user']
     * $connection_settings['pass']
     * $connection_settings['dbname']
     */


    public $connection_settings = array();
    public $table_prefix = false;

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = \Microweber\Application::getInstance();
            }
        }

        $this->setup();



        // $this->set_connection();
    }

    function setup($connection_settings = false)
    {

        if ($this->table_prefix == false) {
            $this->table_prefix = $this->app->config('table_prefix');
        }

        if ($this->table_prefix == false and defined("MW_TABLE_PREFIX")) {
            $this->table_prefix = MW_TABLE_PREFIX;
        }


        if ($connection_settings != false and is_array($connection_settings) and !empty($connection_settings)) {
            $db = $connection_settings;
        } elseif (!empty($this->connection_settings)) {
            $db = $this->connection_settings;
        } else {
            $db = $this->app->config('db');
        }


        if (function_exists('mw_var')) {
            $temp_db = mw_var('temp_db');
            if ((!isset($db) or $db == false or $db == NULL) and $temp_db != false) {
                $db = $temp_db;
            }
        }

        // if we didnt set the connection settings will try to get them from global constants
        if (!isset($db) or $db == false or $db == NULL) {
            $db = array();
            if (defined("DB_HOST")) {
                $db['host'] = DB_HOST;
            }
            if (defined("DB_USER")) {
                $db['user'] = DB_USER;
            }
            if (defined("DB_PASS")) {
                $db['pass'] = DB_PASS;
            }
            if (defined("DB_NAME")) {
                $db['dbname'] = DB_NAME;
            }
        }
        if ($this->table_prefix == false) {
            if (isset($db['table_prefix'])) {
                $this->table_prefix = $db['table_prefix'];
            }
        }

        $this->connection_settings = $db;
    }

    /**
     * Executes plain query in the database.
     *
     * You can use this function to make queries in the db by writing your own sql
     * The results are returned as array or `false` if nothing is found
     *
     *
     * @note Please ensure your variables are escaped before calling this function.
     * @package Database
     * @function $this->query
     * @desc Executes plain query in the database.
     *
     * @param string $q Your SQL query
     * @param array|bool $connection_settings
     * @return array|bool|mixed
     *
     * @example
     *  <code>
     *  //make plain query to the db
     * $table = $this->table_prefix.'content';
     *    $sql = "SELECT id FROM $table WHERE id=1   ORDER BY updated_on DESC LIMIT 0,1 ";
     *  $q = $this->query($sql);
     *
     * </code>
     *
     *
     *
     */

    public function query($q, $connection_settings = false)
    {


        $db_link = $this->db_link;
        if ($connection_settings == false and is_array($connection_settings) and isset($connection_settings['host'])) {
            $db = $connection_settings;
        } else {
            $db = $this->connection_settings;
        }
        if ($connection_settings != false) {
            $link_hash = 'link' . crc32(serialize($connection_settings));
            if (isset($this->db_links[$link_hash])) {
                $db_link = $this->db_links[$link_hash];
            }
        }

        $failed_query = false;
        if (defined('\PDO::MYSQL_ATTR_LOCAL_INFILE')) {
            $is_pdo = true;
        } else {
            $is_pdo = false;
        }
        $is_mysqli = function_exists('mysqli_connect');
        $is_mysql = function_exists('mysql_connect');


        if (!isset($db['pass'])) {
            $db['pass'] = '';
        }


        if (isset($db['host'])) {
            $port_check = explode(":", $db['host']);
            if (isset($port_check[1])) {
                $port_num = intval($port_check[1]);
                $db['host'] = $port_check[0];
            } else {
                $port_num = 3306;
            }
        }


        if ($is_pdo != false) {

            if ($db_link == false or $db_link == NULL) {
                try {
                    //  $db_link = new \PDO('mysql:host=' . $db['host'] . ';port=' . $port_num . ';dbname=' . $db['dbname']. ';charset=utf8', $db['user'], $db['pass']);
                    $db_link = new \PDO('mysql:host=' . $db['host'] . ';port=' . $port_num . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);

                } catch (\PDOException $e) {
                    print "\PDO Error!: " . $e->getMessage() . " ";
                    die();
                }
            }
            $driver = $db_link->getAttribute(\PDO::ATTR_DRIVER_NAME);
            //  $db_link->exec("set names utf8");
            $sth = $db_link->prepare($q);
            $sth->execute();
            $nwq = array();
            $arr = $sth->fetchAll(\PDO::FETCH_ASSOC);

            if (is_array($arr)) {
                foreach ($arr as $row) {

                    $nwq[] = $row;
                }
                $q = $nwq;
            }


        } elseif ($is_mysqli != false) {
            if ($db_link == false or $db_link == NULL) {
                if (isset($db['pass']) and $db['pass'] != '') {
                    if (isset($port_num) and $port_num != false) {
                        $db_link = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname'], $port_num);
                    } else {
                        $db_link = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);
                    }
                } else {
                    if (isset($port_num) and $port_num != false) {
                        $db_link = new mysqli($db['host'], $db['user'], false, $db['dbname'], $port_num);
                    } else {
                        $db_link = new mysqli($db['host'], $db['user'], false, $db['dbname']);
                    }
                }
            }

            if (mysqli_connect_errno()) {
                $error['error'][] = ("Connect failed: " . mysqli_connect_error());
                return $error;
            }
            if ($result = $db_link->query($q)) {
                $nwq = array();
                if (is_object($result)) {
                    while ($row = $result->fetch_assoc()) {
                        $nwq[] = $row;
                    }
                    $result->free();
                    $q = $nwq;
                } else {
                    $q = $result;
                }
            }
            if (!$result) {
                $failed_query = true;
                $failed_query = $db_link->error;
            }
        } elseif ($is_mysql != false) {

            if ($db_link == false or $db_link == NULL) {
                if (isset($db['pass']) and $db['pass'] != '') {
                    $db_link = mysql_connect($db['host'], $db['user'], $db['pass']);
                } else {
                    $db_link = mysql_connect($db['host'], $db['user']);
                }
                if (mysql_select_db($db['dbname']) == false) {
                    $error['error'][] = 'Could not select database ' . $db['dbname'];
                    return $error;
                }
            }
            if ($db_link == false) {
                $error['error'][] = 'Could not connect: ' . mysql_error();
                return $error;
            }

            $query = $q;
            $result = mysql_query($query);
            if (!$result) {
                $err = mysql_error();
                $failed_query = $err;
            }
            $nwq = array();

            if (!$result) {
                $error['error'][] = 'Can\'t connect to the database';
                return $error;
            } else {

                if (is_bool($result)) {
                    return $result;
                }
                if (!empty($result)) {

                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

                        $nwq[] = $row;
                    }
                    $q = $nwq;

                }

            }
            if (is_array($result)) {
                mysql_free_result($result);
            }

        } else {
            print 'Fatal error: Database connection function is not found';
            print "\n \PDO: " . var_dump($is_pdo);
            print "\n mysqli_connect: " . var_dump($is_mysqli);
            print "\n mysql_connect: " . var_dump($is_mysql);
            print("\n Please install at least one of those functions");
            die();
        }


        if ($failed_query != false) {
            $error = array();
            $error['error'][] = $failed_query;
            return $error;
        }
        $this->db_link = $db_link;
        if ($connection_settings != false) {
            $this->db_links[$link_hash] = $this->db_link;
        }
        return $q;

    }
}