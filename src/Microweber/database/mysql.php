<?php
static $db_link;



$is_mysqli = function_exists('mysqli_connect');
//$is_mysqli = false;
if ($is_mysqli != false) {

    if ($db_link == false or $db_link == NULL) {
        if(isset($db['host'])){
            $port_check  = explode(":",$db['host']);
            if(isset($port_check[1])){
                $port_num = intval($port_check[1]);
                $db['host'] = $port_check[0];
            } else {
                $port_num = 3306;
            }
        }



        if (isset($db['pass']) and $db['pass'] != '') {
            if(isset($port_num) and $port_num != false){
                $db_link = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname'],$port_num);
            } else {
                $db_link = new mysqli($db['host'], $db['user'], $db['pass'], $db['dbname']);
            }


        } else {
            if(isset($port_num) and $port_num != false){
                $db_link = new mysqli($db['host'], $db['user'], false, $db['dbname'],$port_num);

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
        // d($result);
        $nwq = array();
        /* fetch associative array */
        if (is_object($result)) {
            while ($row = $result->fetch_assoc()) {
                $nwq[] = $row;
            }
            // mysqli_free_result($result);
            $result->free();
            // unset($result);




            $q = $nwq;
        } else {
            $q = $result;
        }
        /* free result set */


    }

    //$result = $db_link -> query($q);

    if (!$result) {
        $error['error'][] = $db_link->error;

        return $error;
        // throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
    } else {


//		if ($only_query == false) {
//			$nwq = array();
//			while ($row = $result -> fetch_array()) {
//
//				$nwq[] = $row;
//			}
//			$q = $nwq;
//		}
    }
} else {

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

    // Performing SQL query

    $query = $q;
    $result = mysql_query($query);
    if (!$result) {
        $err = mysql_error();
        if (strstr($err, "doesn't exist") or strstr($err, "not exist")) {
            if (function_exists('cache_clean_group')) {
                mw('cache')->delete('db');

            }

            if (function_exists('exec_action')) {
                event_trigger('mw_db_init');
                event_trigger('mw_db_init_default');
                event_trigger('mw_db_init_modules');
            }

            if (function_exists('re_init_modules_db')) {
                re_init_modules_db();
            }
            $query = $q;
            $result = mysql_query($query);
            if (!$result) {
                $err = mysql_error();
                mw_error('Query failed: ' . $err);
            }
        } else {

            mw_error('Query failed: ' . $err);
        }
        return false;
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
                //
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

                    $nwq[] = $row;
                }
                $q = $nwq;

            }
        }
    }
    // Free resultset
    if ($only_query == false) {
        if (is_array($result)) {
            mysql_free_result($result);
        }
    }
    // Closing connection
    // mysql_close($db_link);
    // $result = null;
}
