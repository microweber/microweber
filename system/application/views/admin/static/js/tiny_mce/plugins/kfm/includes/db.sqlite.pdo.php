<?php
class DB_SQLite_PDO{
	var $connection=0;
	function __construct($dsn=array()){
		if(!isset($dsn['database']))exit('no SQLite database set in configuration');
		try {
			$db=new PDO('sqlite:'.$dsn['database']);
			$this->connection=$db;
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage();
			die();
		}
	}
	function fetchAll($query){
		$sth=$this->connection->prepare($query);
		if(!$sth)return false;
		$sth->execute();
		return $sth->fetchAll();
	}
	function fetchRow($query){
		$sth=$this->connection->prepare($query);
		if(!$sth)return false;
		$sth->execute();
		return $sth->fetch();
	}
	function lastInsertId($name=''){
		return $this->connection->lastInsertId($name);
	}
	function query($query){
		return $this->connection->exec($query);
	}
}
