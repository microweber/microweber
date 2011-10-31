<?php
	$kfmdb->query("create table ".KFM_DB_PREFIX."parameters(name text, value text)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."directories(
		id serial,
		name text,
		parent integer not null,
		primary key (id)
	)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."files(
		id serial,
		name text,
		directory integer not null,
		primary key (id),
		foreign key (directory) references ".KFM_DB_PREFIX."directories(id)
	)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."files_images(
		id serial,
		caption text,
		file_id integer not null,
		width integer default 0,
		height integer default 0,
		primary key (id),
		foreign key (file_id) references ".KFM_DB_PREFIX."files(id)
	)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."files_images_thumbs(
		id serial,
		image_id integer not null,
		width integer default 0,
		height integer default 0,
		primary key (id),
		foreign key (image_id) references ".KFM_DB_PREFIX."files_images(id)
	)");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session(
		id serial,
		cookie varchar(32) default NULL,
		last_accessed timestamp default NULL,
		PRIMARY KEY  (id)
	)");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session_vars(
		session_id integer default NULL,
		varname text,
		varvalue text,
		foreign key (session_id) references ".KFM_DB_PREFIX."session(id)
	)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."tags(
		id serial,
		name text,
		primary key (id)
	)");
	$kfmdb->query("create table ".KFM_DB_PREFIX."tagged_files(
		file_id INTEGER,
		tag_id  INTEGER,
		foreign key (file_id) references ".KFM_DB_PREFIX."files (id),
		foreign key (tag_id) references ".KFM_DB_PREFIX."tags (id)
	)");

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
	$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters values('version','1.3')");
	$kfmdb->query("insert into ".KFM_DB_PREFIX."directories values(1,'root',0)");
	if(!PEAR::isError($kfmdb))$db_defined=1;
