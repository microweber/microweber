CREATE TABLE {TABLE_PREFIX}module_stats_users_online ( 
  id int(11) NOT NULL auto_increment, 
  created_by integer, 
  view_count integer, 
  user_ip varchar(15) NOT NULL default '', 
  visit_date date default NULL,
  visit_time time NOT NULL default '00:00:00',
  last_page varchar(35) NOT NULL default '',
  PRIMARY KEY  (id), 
  UNIQUE KEY id (id) 
);