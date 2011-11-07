<?php
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."users(
	id serial,
	username varchar(16),
	password varchar(40),
	status INTEGER default 2
)");
 
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."settings(
	id serial,
	name varchar(128),
	value varchar(256),
	user_id INTEGER,
	usersetting INTEGER default 0
)");

$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."plugin_extensions(
	id serial,
	extension varchar(64),
	plugin varchar(64),
	user_id INTEGER
)");

$kfmdb->query('INSERT INTO '.KFM_DB_PREFIX.'users (id, username, password, status) VALUES (1,"admin", "'.sha1('admin').'",1)');
