<?php
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
?>
