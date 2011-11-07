<?
define ("server"	, $server );
define ("db_user"	, $db_user);
define ("db_pass"	, $db_pass);
define ("only_db"	, $only_db);

class DB {
	var $srv = server;
	var $usr = db_user;
	var $pwd = db_pass;
	var $odb = only_db;
	var $pcn = false;
	var $doe = true;
	var $fel = true;
	var $error_log = "mysql_errors.log";

	function connect(){
		if (!$this->link){
			$this->link = ($this->pcn) ? mysql_pconnect($this->srv, $this->usr, $this->pwd) : 
										 mysql_connect( $this->srv, $this->usr, $this->pwd) or 
										 $this->error();
		}
		if (!mysql_select_db($this->odb,$this->link)) $this->error();
		if($this->link) mysql_query("SET NAMES 'utf8'");
		return ($this->link) ? true : false;
	}

	function query($sql){
		if (!$this->link && !$this->connect()) $this->error();
		if (!($this->result = mysql_query($sql, $this->link))) $this->error($sql);
		return ($this->result) ? true : false;
	}
	
	function nextr(){
		if (!$this->result) die ("No query pending");
		unset($this->row);
		$this->row = mysql_fetch_array($this->result, MYSQL_BOTH);
		return ($this->row) ? true : false ;
	}

	function f($index){
		return stripslashes($this->row[$index]);
	}

	function goto($row){
		if (!$this->result) die ("No query pending");
		if (!mysql_data_seek($this->result, $row)) $this->error();
	}

	function nf(){
		if ($numb = mysql_num_rows($this->result) === false) $this->error();
		return mysql_num_rows($this->result);
	}
	function af(){
		return mysql_affected_rows();
	}
	function error($string=""){
		echo $error = mysql_error();
		if ($this->fel){
			$fp = fopen($this->error_log,"a+");
			fwrite($fp, "[".date("Y-m-d H:i:s")."]<".$error.">$string\n");
			fclose($fp);
		}
		if ($this->doe){
			if (isset($this->result)) mysql_free_result($this->result);
			mysql_close($this->link);
			die();
		}
	}
	function insert_id(){
		if(!$this->link) return false;
		return mysql_insert_id();
	}
	function escape($string){
		if(!$this->link) return addslashes($string);
		return mysql_real_escape_string($string);
	}

	function destroy(){
		if (isset($this->result)) mysql_free_result($this->result);
		if (isset($this->link_id)) mysql_close($this->link_id);
	}


}

?>