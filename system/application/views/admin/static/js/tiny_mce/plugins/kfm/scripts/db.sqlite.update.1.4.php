<?php
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."users(
	id INTEGER PRIMARY KEY,
	username text,
	password text,
	status INTEGER default 2
)");
 
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."settings(
	id INTEGER PRIMARY KEY,
	name text,
	value text,
	user_id INTEGER not null,
	usersetting INTEGER default 0
)");
$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."plugin_extensions(
	id INTEGER PRIMARY KEY,
	extension text,
	plugin text,
	user_id INTEGER not null
)");

$kfmdb->query('INSERT INTO '.KFM_DB_PREFIX.'users (id, username, password, status) VALUES (1,"admin", "'.sha1('admin').'",1)');
