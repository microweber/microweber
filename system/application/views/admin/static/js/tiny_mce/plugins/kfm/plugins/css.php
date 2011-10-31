<?php
// get list of plugins and show their CSS files
header('Content-type: text/css');
header('Expires: '.gmdate("D, d M Y H:i:s", time() + 3600*24*365).' GMT');

$h	   = opendir('./');
while (false!==($plugin=readdir($h))) {
	if($plugin[0]=='.' || !is_dir($plugin))continue;
	if(file_exists($plugin.'/plugin.css'))echo file_get_contents($plugin.'/plugin.css');
}	
