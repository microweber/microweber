<?php
//iFrame(http://www.bin-co.com/php/scripts/iframe/) Common File.

if(!function_exists("findRelation")) {
//Find the relation between the page we are in and the root folder.
function findRelation() {
	$rel = "";
	$depth = 0;
	while($depth < 10) { //We don't want an infinite loop - do we?
		if(file_exists($rel . "configuration.php")) break;
		else $rel .= "../";
		$depth++;
	}
	if($depth == 10) return false;
	
	return $rel; 
}
}

$rel = findRelation();

// App's root path(absolute)
$iframe_folder = dirname(__FILE__) . DIRECTORY_SEPARATOR;
if($rel !== false) { //If the 'configuration.php' file is found, use that
	require($rel . "configuration.php");
	
	if($rel == '') $config['site_folder'] = realpath('.');
	else $config['site_folder'] = realpath($rel);
	$config['site_relative_path'] = $rel;
} else {
	require($iframe_folder . 'configuration.php');
	
	$config['site_folder'] = $config['iframe_folder'];
	$config['site_relative_path'] = '';
}
$config['iframe_folder'] = $iframe_folder;

require($config['iframe_folder'] . "includes/functions.php");

// This is $_REQUERST without the problems asssociated with magic quotes
$PARAM = unescapeQuery();
$QUERY = escapeQuery($PARAM,true);
if(!isset($QUERY['error']))	 {$QUERY['error'] = ''; $PARAM['error'] = '';}
if(!isset($QUERY['success'])){$QUERY['success'] ='';$PARAM['success'] = '';}

require(joinPath($config['iframe_folder'], "includes", "config.php"));

if(!isset($system_installed) or !$system_installed) {
	header('Location:'.$rel.'install/');
	exit;
}
