<?php
	$kfmdb->query('create table tags(
		id INTEGER PRIMARY KEY auto_increment,
		name text
	)');
	$kfmdb->query('create table tagged_files(
		file_id	INTEGER,
		tag_id	INTEGER
	)');

	$kfmdb->query("update parameters set value='0.7.1' where name='version'");
?>
