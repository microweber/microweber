<?php if(PHP_SAPI!=='cli')die();
// Do your command-line stuff safely here
define('T',microtime(TRUE));
define('M',memory_get_usage());
define('AJAX',0);
require('functions.php');
require('bootstrap.php');
