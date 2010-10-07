<?php

/**
 * Delete the hilighting from the code
 *
 * PHP version 4
 *
 * @package    insertcode
 * @author     Maxime Lardenois <maxime.lardenois@gmail.com>
 * @copyright  2006 Maxime Lardenois
 * @version    1.0
 * @modified   15/01/2006 - 19:09
 *
 * ********************************************************
 *
 * DO NOT MODIFY, CONFIG IS HERE : tinyMCE_root_folder/plugins/insertcode/config/config.php
 *
 */
 
require_once("../config/config.php");

// pretraitement des entrees
if( get_magic_quotes_gpc() ) {
	$codeToUnHighlight = stripslashes(trim($_POST['code']));
} else {
	$codeToUnHighlight = trim($_POST['code']);
}

$search = array("</li>","&nbsp;");
$replace = array("\n"," ");

$codeToUnHighlight = str_replace($search, $replace, $codeToUnHighlight);

print html_entity_decode(strip_tags($codeToUnHighlight));
?>