<?php
//Create the database only if it does not exist
//See if the database exists
$tables_sql = mysql_query("SHOW TABLES") or die(mysql_error());
$necessary_tables = array('Context','Project','Reminder','Setting','Task','TaskContext','User');
// add prefix automatically
foreach ($necessary_tables as $i => $value) {
    $necessary_tables[$i] = $_REQUEST['db_prefix'].$value;
}
// compare two tables: expected and real
while($table = mysql_fetch_row($tables_sql)) {
	$necessary_tables = array_diff($necessary_tables,array($table[0])); //Remove the table from the array if it exists
}

//If there are no tables in the array that means that the all the necessary tables are present in the Database
//If some tables are missing, that means we have to create those tables...
if($necessary_tables) {
	$quries = <<<END
CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}Context` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}Project` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_on` datetime NOT NULL,
  `status` enum('1','0') NOT NULL default '1',
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;
INSERT INTO `${_REQUEST['db_prefix']}Project` (`name`, `created_on`, `status`, `user_id`) VALUES('Misc',NOW(),'1',1);

CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}Reminder` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `day` date NOT NULL,
  `description` mediumtext NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}Setting` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `data` mediumtext NOT NULL,
  `amount` float NOT NULL,
  `status` enum('1','0','') NOT NULL,
  `user_id` int(11) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;
INSERT INTO `${_REQUEST['db_prefix']}Setting` (`id`, `name`, `value`, `data`, `amount`, `status`, `user_id`) VALUES (1, 'SingleUser', '1', '', 0, '1', 0);
INSERT INTO `${_REQUEST['db_prefix']}Setting` (`id`, `name`, `value`, `data`, `amount`, `status`, `user_id`) VALUES (2, 'Theme', 'silk', '', 0, '1', 0);

CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}Task` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `type` enum('Idea','Immediately','Someday/Maybe','Waiting','This Week','This Month','This Year','Done','Misc') default 'Immediately',
  `url` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_on` datetime NOT NULL,
  `completed_on` datetime default NULL,
  `due_on` date default '0000-00-00',
  `project_id` int(11) unsigned NOT NULL,
  `sort_order` int(5) NOT NULL default '100',
  `file` varchar(150) NOT NULL,
  `status` enum('1','0') NOT NULL default '1',
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}TaskContext` (
  `task_id` int(11) unsigned default NULL,
  `context_id` int(11) unsigned default NULL,
  `user_id` int(11) unsigned NOT NULL
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `${_REQUEST['db_prefix']}User` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(72) NOT NULL,
  `email` varchar(100) NOT NULL,
  `added_on` datetime NOT NULL,
  `default_project` INT( 11 ) UNSIGNED NOT NULL,
  `status` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;
INSERT INTO `${_REQUEST['db_prefix']}User` (`id`, `name`, `username`, `password`, `email`, `added_on`, `default_project`, `status`) VALUES (1, 'Default User', 'user', 'password', '', '2007-01-09 11:54:22', '1', '1');
END;

	$all_quires = explode(";",$quries);
	$query_count = 0;
	foreach($all_quires as $query) {
		$query = trim($query);
		if($query) {
			@mysql_query($query);
			$query_count++;
		}
	}
	$QUERY['success'][] = "Database Populated.";
} else {
	$QUERY['error'][] = "Tables already in Database - I did not overwrite it. If you want to remove the old data, please delete all the tables and run the installer script again.";
}
