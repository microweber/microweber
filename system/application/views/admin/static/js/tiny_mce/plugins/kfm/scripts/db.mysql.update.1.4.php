<?php
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."users(
	id INTEGER PRIMARY KEY auto_increment,
	username varchar(16),
	password varchar(40),
	status INTEGER(1) default 2
) DEFAULT CHARSET=utf8");
 
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."settings(
	id INTEGER PRIMARY KEY auto_increment,
	name varchar(128),
	value varchar(256),
	user_id INTEGER(8),
	usersetting INTEGER(1) default 0
) DEFAULT CHARSET=utf8");

$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."plugin_extensions(
	id INTEGER PRIMARY KEY auto_increment,
	extension varchar(64),
	plugin varchar(64),
	user_id INTEGER(8)
) DEFAULT CHARSET=utf8");

$kfmdb->query('INSERT INTO '.KFM_DB_PREFIX.'users (id, username, password, status) VALUES (1,"admin", "'.sha1('admin').'",1)');
