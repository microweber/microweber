<?php
	$kfmdb->query("alter table directories rename to ".KFM_DB_PREFIX."directories");
	$kfmdb->query("alter table files rename to ".KFM_DB_PREFIX."files");
	$kfmdb->query("alter table image_captions rename to ".KFM_DB_PREFIX."image_captions");
	$kfmdb->query("alter table ".KFM_DB_PREFIX."image_captions rename to ".KFM_DB_PREFIX."files_images");
	$kfmdb->query("alter table parameters rename to ".KFM_DB_PREFIX."parameters");
	$kfmdb->query("alter table tags rename to ".KFM_DB_PREFIX."tags");
	$kfmdb->query("alter table tagged_files rename to ".KFM_DB_PREFIX."tagged_files");
	$kfmdb->query("alter table ".KFM_DB_PREFIX."files_images add width integer default 0");
	$kfmdb->query("alter table ".KFM_DB_PREFIX."files_images add height integer default 0");
	$kfmdb->query("create table ".KFM_DB_PREFIX."files_images_thumbs(
		id serial,
		image_id integer not null,
		width integer default 0,
		height integer default 0,
		primary key (id),
		foreign key (image_id) references ".KFM_DB_PREFIX."files_images(id)
	)");
	$kfmdb->query("update ".KFM_DB_PREFIX."parameters set value='0.7.2' where name='version'");
?>
