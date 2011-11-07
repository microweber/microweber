<?php
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."directories(
		id INTEGER PRIMARY KEY auto_increment,
		name text,
		parent integer not null
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files(
		id INTEGER PRIMARY KEY auto_increment,
		name text,
		directory integer not null
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files_images(
		id INTEGER PRIMARY KEY auto_increment,
		caption text,
		file_id integer not null,
		width integer default 0,
		height integer default 0
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."files_images_thumbs(
		id INTEGER PRIMARY KEY auto_increment,
		image_id integer not null,
		width integer default 0,
		height integer default 0
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."parameters(
		name text,
		value text
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session (
		`id` int(11) NOT NULL auto_increment,
		`cookie` varchar(32) default NULL,
		`last_accessed` datetime default NULL,
		PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."session_vars (
		`session_id` int(11) default NULL,
		`varname` text,
		`varvalue` text,
		KEY `session_id` (`session_id`)
	) DEFAULT CHARSET=utf8");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."tagged_files(
		file_id	INTEGER,
		tag_id	INTEGER
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("CREATE TABLE ".KFM_DB_PREFIX."tags(
		id INTEGER PRIMARY KEY auto_increment,
		name text
	)DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

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
	
	$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters values('version','1.4')");
	$kfmdb->query("insert into ".KFM_DB_PREFIX."directories values(1,'root',0)");
	$kfmdb->query('INSERT INTO '.KFM_DB_PREFIX.'users (id, username, password, status) VALUES (1,"admin", "'.sha1('admin').'",1)');

	if(!PEAR::isError($kfmdb))$db_defined=1;
?>
