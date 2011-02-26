<?php

define('SABRE_MYSQLDSN','mysql:host=127.0.0.1;dbname=sabredav');
define('SABRE_MYSQLUSER','root');
define('SABRE_MYSQLPASS','');

set_include_path(dirname(__FILE__) . PATH_SEPARATOR . dirname(__FILE__) . '/../lib/' . PATH_SEPARATOR . get_include_path());

include 'Sabre.autoload.php';

date_default_timezone_set('GMT');

define("SABRE_TEMPDIR",dirname(__FILE__) . '/temp/');

// If sqlite is not available, this constant is used to skip the relevant
// tests
define('SABRE_HASSQLITE',in_array('sqlite',PDO::getAvailableDrivers()));
define('SABRE_HASMYSQL', in_array('mysql',PDO::getAvailableDrivers()) && defined('SABRE_MYSQLDSN') && defined('SABRE_MYSQLUSER') && defined('SABRE_MYSQLPASS'));

if (!file_exists(SABRE_TEMPDIR)) mkdir(SABRE_TEMPDIR);
if (file_exists('.sabredav')) unlink('.sabredav');
