<?php
class DB{
	var $db=0;
	var $dbtype='';
	function DB($dsn=array()){
		switch($dsn['type']){
			case 'sqlitepdo':{
				require(KFM_BASE_PATH.'includes/db.sqlite.pdo.php');
				$this->db=new DB_SQLite_PDO($dsn);
				$db_defined=1;
				break;
			}
			default:{
				exit('error: unknown database type "'.$dsn['type'].'"');
			}
		}
	}
	function __construct($dsn=array()){
		$this->DB($dsn);
	}
	function exec($query){
		$this->db->query($query);
	}
	function fetchAll($query){
		return $this->db->fetchAll($query);
	}
	function fetchRow($query){
		return $this->db->fetchRow($query);
	}
	function lastInsertId($name=''){
		return $this->db->lastInsertId($name);
	}
	function query($query){
		return $this->db->query($query);
	}
}
function db_fetch_all($query){
	global $kfm_db_type,$kfmdb;
	if($kfm_db_type=='sqlitepdo'){
		return $kfmdb->fetchAll($query);
	}
	$q=$kfmdb->query($query);
	if(PEAR::isError($q))die('alert("'.$q->getMessage().'\n'.$query.'")');
	return $q->fetchAll();
}
function db_fetch_row($query){
	global $kfm_db_type,$kfmdb;
	if($kfm_db_type=='sqlitepdo'){
		return $kfmdb->fetchRow($query);
	}
	$q=$kfmdb->query($query);
	if(PEAR::isError($q))die('alert("'.$q->getMessage().'\n'.$query.'")');
	return $q->fetchRow();
}
?>
