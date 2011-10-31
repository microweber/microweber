<?php
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
		KEY `session_id` (`session_id`),
		CONSTRAINT `".KFM_DB_PREFIX."session_vars_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `".KFM_DB_PREFIX."session` (`id`)
	)");
?>
