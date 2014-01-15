<?php


//global $db_link;

$db_link = $this->db_link;

$failed_query = false;
if (defined('PDO::MYSQL_ATTR_LOCAL_INFILE')) {
    $is_pdo = true;
} else {
    $is_pdo = false;
}
$is_mysqli = function_exists('mysqli_connect');
$is_mysql = function_exists('mysql_connect');


if(!isset($db['pass'])){
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
          //  $db_link = new PDO('mysql:host=' . $db['host'] . ';port=' . $port_num . ';dbname=' . $db['dbname']. ';charset=utf8', $db['user'], $db['pass']);
            $db_link = new PDO('mysql:host=' . $db['host'] . ';port=' . $port_num . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);

        } catch (PDOException $e) {
            print "PDO Error!: " . $e->getMessage() . " ";
            die();
        }
    }
    $driver = $db_link->getAttribute(PDO::ATTR_DRIVER_NAME);
  //  $db_link->exec("set names utf8");
    $sth = $db_link->prepare($q);
    $sth->execute();
    if ($only_query == false) {
        $nwq = array();
        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $row) {

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
        if ($only_query == false) {
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
    }

    if ($only_query == false) {
        if (is_array($result)) {
            mysql_free_result($result);
        }
    }

} else {
    print 'Fatal error: Database connection function is not found';
    print "\n PDO: " . var_dump($is_pdo);
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

