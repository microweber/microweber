<?php if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );

// This is the file to create a "webdav" database with 2 tables
// so you can use WebDAV with eXtplorer
// Just copy the SQL code below and execute it using a Database Management Script like phpMyAdmin
?>
CREATE DATABASE webdav;
USE webdav;

--
-- Table structure for table 'locks'
--

CREATE TABLE locks (
  token varchar(255) NOT NULL default '',
  path varchar(200) NOT NULL default '',
  expires int(11) NOT NULL default '0',
  owner varchar(200) default NULL,
  recursive int(11) default '0',
  writelock int(11) default '0',
  exclusivelock int(11) NOT NULL default 0,
  PRIMARY KEY  (token),
  UNIQUE KEY token (token),
  KEY path (path),
  KEY expires (expires)
) TYPE=MyISAM;

--
-- Dumping data for table 'locks'
--


--
-- Table structure for table 'properties'
--

CREATE TABLE properties (
  path varchar(255) NOT NULL default '',
  name varchar(120) NOT NULL default '',
  ns varchar(120) NOT NULL default 'DAV:',
  value text,
  PRIMARY KEY ( `ns` ( 100 ) , `path` ( 100 ) , `name` ( 50 ) ),
  KEY path (path)
) TYPE=MyISAM;

--
-- Dumping data for table 'properties'
--


