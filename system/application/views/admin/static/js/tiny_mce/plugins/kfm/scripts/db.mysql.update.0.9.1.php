<?php
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."directories         DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."directories         DROP physical_address");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."directories         CHANGE name name text");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."files               DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."files               CHANGE name name text");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."files_images        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."files_images        CHANGE caption caption text");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."files_images_thumbs DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."parameters          DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."parameters          CHANGE name name text");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."parameters          CHANGE value value text");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."tagged_files        DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."tags                DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
	$kfmdb->query("ALTER TABLE ".KFM_DB_PREFIX."tags                CHANGE name name text");
?>
