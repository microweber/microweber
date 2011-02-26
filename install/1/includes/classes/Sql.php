<?php
/** ************************************************************************************************
* Class  : Sql
* Author : Binny V A(binnyva@gmail.com | http://www.bin-co.com/)
* Date   : 1 Feb, 2007
***************************************************************************************************
* Creates a Database abstration layer - using the most commonly used functions.
* get* functions require a query as the argument
* fetch* functions requrire a sql resource as the argument.
***************************************************************************************************/
class Sql {
	//All Variables - Public
	public static $mode = 'd'; ///Mode - p = Production, d = Development and t = Testing

	//Private Variables
	private $_row  = "";
	private $_list = array();
	private $_db_connection;
	private $_resource;
	private static $_is_mysqli = false;

	/**
	 * Constructor
	 * Connects to the database with the details given as the argument. Exits with error if there are problems.
	 * Arguments :	$db_host - The database host server - eg. localhost
	 *				$db_user - Database user - eg. root
	 *				$db_password - The password for the given user - eg. ''
	 *				$db_name - The database that must be used.
	 */
	function __construct($db_host,$db_user,$db_password,$db_name,$options=array()) {
		$this->_is_mysqli = function_exists("mysqli_connect");
		if ($this->_is_mysqli) {
			$this->_db_connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
			if(!$this->_db_connection) $this->_error("Cannot connect to Database Host '".$db_host."': " . mysqli_connect_error());
		} else {
			$this->_db_connection = mysql_connect($db_host, $db_user, $db_password);
			if(!$this->_db_connection) $this->_error("Cannot connect to Database Host '".$db_host."'");
			mysql_select_db($db_name) or $this->_error("Cannot use Database '" . $db_name . "'.");
		}
	}
	
	/**
	 * Disconnects from the currently open database connection
	 */
	function disconnect() {
		if ($this->$_is_mysqli) {
			mysqli_close($this->_db_connection);
		} else {
			mysql_close($this->_db_connection);
		}
	}
	
	//////////////////////////////////////// Raw SQL Functions ///////////////////////////////////////
	/**
	 * Executes the given query and returns the resource. If an error has occured, passes the error data to $Sql->_error()
	 * Argument : $query - SQL query
	 * Return   : The SQL Resource of the given query
	 */
	function getSql($query) {
		if(self::$mode == 't') {
			print $query;
			return false;
		} else if(self::$mode == 'd') { // Log the query if we are in the Development mode.
			if($GLOBALS['Logger']) $GLOBALS['Logger']->log("Query: $query");
		}

		if(is_string($query)) {
			if ($this->_is_mysqli) {
				$this->_resource = mysqli_query($this->_db_connection, $query);
			} else {
				$this->_resource = mysql_query($query,$this->_db_connection);
			}
		} else $this->_resource = $query;
		
		if(!$this->_resource) {
			$this->_error($query);
			return false;
		}

		return $this->_resource;
	}

	/**
	 * Returns the first row of the result as an associative array - after stripslashing it
	 * Argument : $query - SQL query
	 * Return   : First row in the query result - as an associative array.
	 */ 
	function getAssoc($query, $options=array()) {
		$result = $this->getSql($query);
		if(!$result) return array();

		$row = $this->fetch_assoc($result);
		
		if(isset($options['strip_slashes']) and $options['strip_slashes'] == false) return $row;
		return $this->_stripSlashes($row);
	}

	/**
	 * Returns the first row of the result as a numeric array - or list
	 * Argument : $query - SQL Query
	 * Return   : First row in the query result - as an numeric array.
	 */
	function getList($query) {
		$result = $this->getSql($query);
		if(!$result) return array();
		$row = $this->fetch_row($result);
		return $this->_stripSlashes($row);
	}

	/**
	 * Runs a query and returns the first column of the first row
	 * Argument : $query - SQL Query
	 * Return   : The first column of the first row in the query result
	 */
	function getOne($query) {
		$result = $this->getSql($query);
		if(!$result) return array();
		$res = $this->fetch_row($result);
		if(!$res) return '';
		return stripslashes($res[0]);
	}

	/**
	 * Runs a query and returns all the data as an array
 	 * Argument : $query - SQL Query
 	 * Return : All the data is the result
	 */
	function getAll($query) {
		$result = $this -> getSql($query);
		if(!$result) return array();

		$arr = array();
		while ($row = $this->fetch_assoc($result)) {
			array_push($arr,$row);
		}
		return $this->_stripSlashes($arr);
	}

	/**
	 * Runs a query and returns the data from a single column
 	 * Argument : $query - SQL Query
 	 * Return : All the values in the given column - as a list
	 */
	function getCol($query) {
		$result = $this -> getSql($query);
		if(!$result) return array();

		$arr = array();
		while ($row = $this->fetch_row($result)) {
			array_push($arr,stripslashes($row[0]));
		}
		return $arr;
	}

	/**
	 * Runs a query and returns all the data as an array - with the first field as the key and the second as the value
 	 * Argument : $query - SQL Query
 	 * Return : All the data is the result - with the first field as the key and the second as the value
	 */
	function getById($query) {
		$result = $this -> getSql($query);
		if(!$result) return array();

		$arr = array();
		while ($row = $this->fetch_array($result)) {
			if(count($row) == 4) // Just 2 items actually - it is mysqli_fetch_array - so both index and name is there in the array
				$arr[$row[0]] = $this->_stripSlashes($row[1]);
			else // SELECT id, name, username FROM Users - will be handled by creating an array like {1:{"id":1, "name":"Binny", "username": "binnyva", "0":1, "1":"Binny", "2": "binnyva"}}
				$arr[$row[0]] = $row;
		}
		return $arr;
	}

	/**
	 * Function : runQuery()
	 * Arguments: $query - MySQL query
	 * Return   : Affected rows
	 * Runs a query in the currently open MySQL connection and gets the number of affected rows. Use
	 *	this while running update, insert etc. query that don't need to 'fetch' the results.
	 */ 
	function execQuery($query) {
		$this -> getSql($query);
		return $this->fetchAffectedRows();
	}	
	
	/////////////////////////////////// Editing Functions /////////////////////////////////
	/**
	 * Builds and executes an INSERT command based on the given data.
	 * Arguments :	$table - The name of the table
	 *				$fields- The names all the fields that should be inserted - as an array. Just give $_REQUEST as the data for this argument. This will not be stripslashed.
	 *				$values- All the values that must be inserted as an associative array. The key of the array must be the field name to which it is inserted.
	 * Example : insertFields('user',array('name','job','phone','email'),$_REQUEST);
	 */
	function insertFields($table,$fields,$values = array()) {
		if(!$values) {
			$values = ($GLOBALS['QUERY']) ? $GLOBALS['QUERY'] : $_REQUEST; //The $QUERY is iFrame Specific
		}
		$insert_query = "INSERT INTO `$table`(".join(',',$fields).") VALUES(";
		$insert_values = array();
		foreach($fields as $fld) {
			if(isset($values[$fld])) {
				$field_value = $values[$fld];
				if ($this->isKeyword($field_value)) { //If the is values has a special meaning - like NOW() give it special consideration
					$insert_values[] = $field_value;
				} else {
					$insert_values[] = "'$field_value'";
				}
			}
		}
		$insert_query .= implode(',', $insert_values);
		$insert_query .= ')';

		$this->getSql($insert_query);
		
		return $this->fetchInsertId();
	}

	/** 
	 * Builds and executes an UPDATE command based on the argument
	 * Arguments :	$table	- Table Name
	 *				$fields	- The names of all the fields that should be updated - as an array.
	 *				$values	- All the values that should be inserted - must be given as an associative array. Just give $_REQUEST as the data for this argument. This will not be stripslashed.
	 *				$where 	- The where condition that will decide where to do the update.
	 * Example : updateFields('user',array('name','job','phone','email'),$_REQUEST,"WHERE user_id=12");
	 */
	function updateFields($table,$fields,$values,$where) {
		$update_query = "UPDATE $table SET ";
		foreach($fields as $fld) {
			if(isset($values[$fld])) $update_query .= $fld . "='" . $values[$fld] . "',";
		}
		$update_query = substr($update_query,0,-1);
		
		if($where) {
			if(strpos($where,"where ") !== false)
				$update_query .= " $where";
			else
				$update_query .= " WHERE $where";
		}

		$this->getSql($update_query);
		
		return $this->fetchAffectedRows();
	}
	
	/**
	 * Builds and executes an INSERT command - by taking a table name and an array holding all the data in an associative array - the key being the field name and the value being the data.
	 * Arguments :	$table	- Name of the table
	 * 				$data	- An array holding all the data in an associative array - the key being the field name and the value being the data.
	 * Example : <pre>insert("Data",array(
	 *				'name' => 'Binny',
	 *				'age' => 12,
	 *				'year' => 2007,
	 *				'something' => 'Xrats'
	 *			));</pre>
	 */
	function insert($table,$data) {
		if(!$data or !$table) return;
	
		$fields = array_keys($data);
		$values = array_values($data);
		$insert_query = "INSERT INTO $table(`".join('`,`',$fields)."`) VALUES(";
		$insert_values = array();
		foreach($values as $field_value) {
			if ($this->isKeyword($field_value)) { //If the is values has a special meaning - like NOW() give it special consideration
				$insert_values[] = $field_value;
			} else {
				$insert_values[] = "'$field_value'";
			}
		}
		$insert_query .= implode(',', $insert_values);
		$insert_query .= ")";
	
		$this->getSql($insert_query);
		
		return $this->fetchInsertId();
	}

	/**
	 * Builds and executes an UPDATE command - by taking a table name and an array holding all the data in an associative array - the key being the field name and the value being the data.
	 * Arguments :	$table	- Name of the table
	 * 				$data	- An array holding all the data in an associative array - the key being the field name and the value being the data.
	 *				$where	- The WHERE clause should be given here.
	 * Example : <pre>update("Data",array(
	 *				'name' => 'Binny',
	 *				'age' => 12,
	 *				'year' => 2007,
	 *				'something' => 'Xrats'
	 *			),'id=14');</pre>
	 */
	function update($table,$data,$where) {
		if(!$data or !$table) return;
	
		$update_query = "UPDATE $table SET ";
		$update_fields = array();
		foreach($data as $field=>$value) {
			if ($this->isKeyword($value)) { //If the is values has a special meaning - like NOW() give it special consideration
				$update_fields[] = "$field=$value";
			} else {
				$update_fields[] = "$field='$value'";
			}
		}
		$update_query .= implode(',',$update_fields);
	
		if($where) {
			if(strpos(strtolower($where),"where ") !== false)
				$update_query .= " $where";
			else
				$update_query .= " WHERE $where";
		}

		$this->getSql($update_query);
		
		return $this->fetchAffectedRows();
	}

	/**
	 * To emulate the functioning of prepare and execute command - if we are on a PHP 5/MySQL 5 system, we should NOT use this
	 * Arguments :	$query - The SQL Query to be executed.
	 *				Data that should be used in the query
	 * Example : prepExec("INSERT INTO rats(name,text) VALUES(?,?)","Name",23);
	 */
	function prepExec() {
		$args = func_get_args();
		$qry = $args[0];
		$datas = array_slice($args,1);
		
		//If there is only one argument and it is an array, set it as the data provider.
		if(count($datas) == 1 and is_array($datas)) {
			$datas = $datas[0];
		}
		
		//Go thru each available value and insert it at the position of the '?'
		foreach($datas as $value) {
			$pos = strpos($qry,'?');
			if($pos === false) break;
			$value = $this->escape($value);

			if(is_string($value)) {
				$value = "'".$value."'";
			}
			$qry = substr($qry,0,$pos) . $value . substr($qry,$pos+1);
		}
		
		$this->getSql($qry);
	}

	///////////////////////////////////// Other Functions /////////////////////////////////////
	function isKeyword($value) {
		$value = strtoupper($value);
		if(preg_match('/^\s*(\w+)\((.*)\)\s*$/',$value,$match)) {
			$function_name = $match[1];

			//A list of all mysql functions - taken from http://dev.mysql.com/doc/refman/4.1/en/func-op-summary-ref.html
			$mysql_functions = array('ABS','ACOS','ADDDATE','ADDTIME','AES_DECRYPT','AES_ENCRYPT','ASCII','ASIN',
				'ATAN2,','ATAN','AVG','BENCHMARK','BIN','BIT_AND','BIT_COUNT','BIT_LENGTH','BIT_OR','BIT_XOR','CAST',
				'CEILING,','CHAR_LENGTH','CHAR','CHARACTER_LENGTH','CHARSET','COALESCE','COERCIBILITY','COLLATION',
				'COMPRESS','CONCAT_WS','CONCAT','CONNECTION_ID','CONV','CONVERT_TZ','COS','COT','COUNT','COUNT',
				'CRC32','CURDATE','CURRENT_DATE,','CURRENT_TIME,','CURRENT_TIMESTAMP,','CURRENT_USER,','CURTIME',
				'DATABASE','DATE_ADD','DATE_FORMAT','DATE_SUB','DATE','DATEDIFF','DAY','DAYNAME','DAYOFMONTH',
				'DAYOFWEEK','DAYOFYEAR','DECODE','DEFAULT','DEGREES','DES_DECRYPT','DES_ENCRYPT','DIV','ELT',
				'ENCODE','ENCRYPT','EXP','EXPORT_SET','FIELD','FIND_IN_SET','FLOOR','FORMAT','FOUND_ROWS',
				'FROM_DAYS','FROM_UNIXTIME','GET_FORMAT','GET_LOCK','GREATEST','GROUP_CONCAT','HEX','HOUR','IF',
				'IFNULL','INET_ATON','INET_NTOA','INSERT','INSTR','INTERVAL','IS_FREE_LOCK','IS_USED_LOCK','ISNULL',
				'LAST_DAY','LAST_INSERT_ID','LCASE','LEAST','LEFT','LENGTH','LN','LOAD_FILE','LOCALTIME,',
				'LOCALTIMESTAMP,','LOCATE','LOG10','LOG2','LOG','LOWER','LPAD','LTRIM','MAKE_SET','MAKEDATE',
				'MAKETIME','MASTER_POS_WAIT','MAX','MD5','MICROSECOND','MID','MIN','MINUTE','MOD','MONTH','MONTHNAME',
				'NOW','NULLIF','OCT','OCTET_LENGTH','OLD_PASSWORD','ORD','PASSWORD','PERIOD_ADD','PERIOD_DIFF','PI',
				'POSITION','POW,','QUARTER','QUOTE','RADIANS','RAND','RELEASE_LOCK','REPEAT','REPLACE','REVERSE',
				'RIGHT','ROUND','RPAD','RTRIM','SEC_TO_TIME','SECOND','SESSION_USER','SHA1,','SIGN','SIN','SOUNDEX',
				'SOUNDS','SPACE','SQRT','STD,','STR_TO_DATE','STRCMP','SUBDATE','SUBSTRING_INDEX','SUBSTRING,',
				'SUBTIME','SUM','SYSDATE','SYSTEM_USER','TAN','TIME_FORMAT','TIME_TO_SEC','TIME','TIMEDIFF',
				'TIMESTAMP','TO_DAYS','TRIM','TRUNCATE','UCASE','UNCOMPRESS','UNCOMPRESSED_LENGTH','UNHEX',
				'UNIX_TIMESTAMP','UPPER','USER','UTC_DATE','UTC_TIME','UTC_TIMESTAMP','UUID','VALUES','VARIANCE',
				'WEEK','WEEKDAY','WEEKOFYEAR','YEAR','YEARWEEK');

			if(in_array($function_name, $mysql_functions)) { //The function is a valid mysql keyword
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Handles the SQL errors depending on what mode we are in.
	 * Argument : $query - The SQL Query in which the error occured.
	 */
	private function _error($query) {
		if ($this->_is_mysqli) {
			$error_message = "MySQL Error : <code>" . mysqli_error($this->_db_connection) . "</code><br /><br /><u>In Query...</u><br /><code>" . $query . "</code>";
		} else {
			$error_message = "MySQL Error : <code>" . mysqlierror($this->_db_connection) . "</code><br /><br /><u>In Query...</u><br /><code>" . $query . "</code>";
		}
		if(self::$mode == 'd') {
			die($error_message);
		} elseif(self::$mode == 't') {
			print($error_message);
		}
	}
	
	/**
	 * Do a stripslash on every element of the array and return it.
	 * Arguments: $arr - The array that should be stripslashed
	 * Return	: The array given in the argument - stripslashed
	 */
	function _stripSlashes($arr) {
		if(is_array($arr)) {
			foreach($arr as $key=>$value) {
				$arr[$key] = $this->_stripSlashes($value);// :RECURSION:
			}
		} else {
			$arr = stripslashes($arr);
		}
		return $arr;
	}
	
	/*****************************************************************************/
	function escape($string) {
		if ($this->_is_mysqli) {
			return mysqli_real_escape_string($this->_db_connection, $string);
		} else {
			return mysql_real_escape_string($string);
		}
	}
	
	function fetchAssoc($resource = false) {
		if(!$resource) $resource = $this->_resource;
		return $this->fetch_assoc($resource);
	}
	function fetchRow($resource = false) {
		if(!$resource) $resource = $this->_resource;
		return $this->fetch_row($resource);
	}
	function fetchNumRows($resource = false) {
		if(!$resource) $resource = $this->_resource;
		if ($this->_is_mysqli) {
			return mysqli_num_rows($resource);
		} else {
			return mysql_num_rows($resource);
		}
	}
	function fetchInsertId() {
		if ($this->_is_mysqli) {
			return mysqli_insert_id($this->_db_connection);
		} else {
			return mysql_insert_id();
		}
	}
	function fetchAffectedRows() {
		if ($this->_is_mysqli) {
			return mysqli_affected_rows($this->_db_connection);
		} else {
			return mysql_affected_rows();
		}
	}

	function fetch_row($resource) {
		if ($this->_is_mysqli) {
			return mysqli_fetch_row($resource);
		} else {
			return mysql_fetch_row($resource);
		}
	}
	function fetch_array($resource) {
		if ($this->_is_mysqli) {
			return mysqli_fetch_array($resource);
		} else {
			return mysql_fetch_array($resource);
		}
	}
	function fetch_assoc($resource) {
		if ($this->_is_mysqli) {
			return mysqli_fetch_assoc($resource);
		} else {
			return mysql_fetch_assoc($resource);
		}
	}
}

/*
 * :TODO:
 * Change this to OOPS mysqli - not procedural. Do something like 'class Sql extends mysqli {'
 * Combine insertFields() and insert() - add the $fields list as the third argument of insert(). Also combine update() and updateFields()
 * The options argument - as in the getAssoc() function.
 */