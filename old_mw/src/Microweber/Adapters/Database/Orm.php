<?php
namespace Microweber\Adapters\Database;

use Microweber\Application;




class Orm
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

        $this->callbacks = array();
        register_shutdown_function(array($this, 'disconnect'));
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
     *  $table = 'my_full_table_name';
     *  $sql = "SELECT id FROM $table WHERE id=1   ORDER BY id DESC LIMIT 0,1 ";
     *  $q = $this->query($sql);
     *
     * </code>
     *
     *
     *
     */
    public function query($q, $connection_settings = false)
    {

        $return = false;
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
        $is_mysql = false;
        $is_mysqli = false;
        $failed_query = false;
        if (defined('\PDO::MYSQL_ATTR_LOCAL_INFILE')) {
            $is_pdo = true;
        } else {
            $is_pdo = false;
            $is_mysqli = function_exists('mysqli_connect');
            $is_mysql = function_exists('mysql_connect');
        }

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



        $people = ORM::for_table('person')->raw_query('SELECT p.* FROM person p JOIN role r ON p.role_id = r.id WHERE r.name = :role', array('role' => 'janitor'))->find_many();


        return $return;

    }
    public function querysssss($q, $connection_settings = false)
    {

        $return = false;
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
        $is_mysql = false;
        $is_mysqli = false;
        $failed_query = false;
        if (defined('\PDO::MYSQL_ATTR_LOCAL_INFILE')) {
            $is_pdo = true;
        } else {
            $is_pdo = false;
            $is_mysqli = function_exists('mysqli_connect');
            $is_mysql = function_exists('mysql_connect');
        }

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

        if (isset($port_num) and $port_num != false) {
            $db_link = new \mysqli($db['host'], $db['user'], false, $db['dbname'], $port_num);
        } else {
            $db_link = new \mysqli($db['host'], $db['user'], false, $db['dbname']);
        }

        $people = ORM::for_table('person')->raw_query('SELECT p.* FROM person p JOIN role r ON p.role_id = r.id WHERE r.name = :role', array('role' => 'janitor'))->find_many();




        if ($is_pdo != false) {
            if ($db_link == false or $db_link == NULL) {
                try {
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
            $results_to_return = array();
            $arr = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if (is_array($arr)) {
                foreach ($arr as $row) {
                    $results_to_return[] = $row;
                }
                $return = $results_to_return;
            }

        } elseif ($is_mysqli != false) {
            if ($db_link == false or $db_link == NULL) {
                if (isset($db['pass']) and $db['pass'] != '') {
                    if (isset($port_num) and $port_num != false) {
                        $db_link = new \mysqli($db['host'], $db['user'], $db['pass'], $db['dbname'], $port_num);
                    } else {
                        $db_link = new \mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);
                    }
                } else {
                    if (isset($port_num) and $port_num != false) {
                        $db_link = new \mysqli($db['host'], $db['user'], false, $db['dbname'], $port_num);
                    } else {
                        $db_link = new \mysqli($db['host'], $db['user'], false, $db['dbname']);
                    }
                }
            }
            if (mysqli_connect_errno()) {
                $error['error'][] = ("Connect failed: " . mysqli_connect_error());
                return $error;
            }
            if ($result = $db_link->query($q)) {
                $results_to_return = array();
                if (is_object($result)) {
                    while ($row = $result->fetch_assoc()) {
                        $results_to_return[] = $row;
                    }
                    $result->free();
                    $return = $results_to_return;
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
                    $db_link = \mysql_connect($db['host'], $db['user'], $db['pass']);
                } else {
                    $db_link = \mysql_connect($db['host'], $db['user']);
                }
                if (mysql_select_db($db['dbname']) == false) {
                    $error['error'][] = 'Could not select database ' . $db['dbname'];
                    return $error;
                }
            }
            if ($db_link == false) {
                $error['error'][] = 'Could not connect: ' . \mysql_error();
                return $error;
            }

            $query = $q;
            $result = \mysql_query($query);
            if (!$result) {
                $err = \mysql_error();
                $failed_query = $err;
            }
            $results_to_return = array();

            if (!$result) {
                $error['error'][] = 'Can\'t connect to the database';
                return $error;
            } else {

                if (is_bool($result)) {
                    return $result;
                }
                if (!empty($result)) {

                    while ($row = \mysql_fetch_array($result, MYSQL_ASSOC)) {

                        $results_to_return[] = $row;
                    }
                    $return = $results_to_return;

                }

            }
            if (is_array($result)) {
                \mysql_free_result($result);
            }

        } else {
            print 'Fatal error: Database connection function is not found';
            print "\n PDO: " . var_dump($is_pdo);
            print "\n mysqli_connect: " . var_dump($is_mysqli);
            print "\n mysql_connect: " . var_dump($is_mysql);
            print("\n Please install at least one of those functions");
            die();
        }
        if (is_bool($return) or $return == false) {
            return $return;
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
        return $return;

    }

    public function disconnect()
    {

        $links = $this->db_links;
        if (is_array($links) and !empty($links)) {
            foreach ($links as $key => $link) {
                if (is_object($link)) {
                    $inst = get_class($link);
                    if ($inst == 'PDO') {
                        $this->db_links[$key] = null;
                        unset($this->db_links[$key]);
                    } elseif ($inst == 'mysqli') {
                        @mysqli_close($link);
                    }
                } elseif (is_resource($link)) {
                    $inst = get_resource_type($link);
                    if ($inst == 'mysql link') {
                        @mysql_close($link);
                    }
                }
            }

        }
    }

}