<?php
	$kfmdb->query('create table tags(
		id INTEGER PRIMARY KEY,
		name text
	)');
	$kfmdb->query('create table tagged_files(
		file_id INTEGER,
		tag_id  INTEGER,
		foreign key(file_id) references files(id),
		foreign key(tag_id) references tags(id)
	)');

	$kfmdb->query("update parameters set value='0.7.1' where name='version'");
?>
