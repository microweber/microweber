<?php

/*

CalendarServer example

This server features CalDAV and ACL support

*/

// settings
date_default_timezone_set('Canada/Eastern');

// If you want to run the SabreDAV server in a custom location (using mod_rewrite for instance)
// You can override the baseUri here.
// $baseUri = '/';

/* Database */
$pdo = new PDO('sqlite:data/db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//Mapping PHP errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Files we need
require_once 'lib/Sabre/autoload.php';

// The object tree needs in turn to be passed to the server class
$server = new Sabre_CalDAV_Server($pdo);

if (isset($baseUri))
    $server->setBaseUri($baseUri);

// Support for html frontend
$browser = new Sabre_DAV_Browser_Plugin();
$server->addPlugin($browser);

// And off we go!
$server->exec();
