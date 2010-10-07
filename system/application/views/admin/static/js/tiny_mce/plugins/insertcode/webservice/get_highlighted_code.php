<?php

/**
 * Highlight the submitted code using Geshi
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
require_once(IC_GESHI_PATH."geshi.php");

// pretraitement des entrees
if( get_magic_quotes_gpc() ) {
	$codeToHighlight = stripslashes(trim($_POST['code']));
	$legend = strip_tags(stripslashes(trim($_POST['legend'])));
} else {
	$codeToHighlight = trim($_POST['code']);
	$legend = strip_tags(trim($_POST['legend']));
}

$codeToHighlight = str_replace("\t", "  ", $codeToHighlight);

$geshi =& new GeSHi($codeToHighlight, $_POST['lang']);

$geshi->set_header_type(GESHI_HEADER_PRE);

$geshi->enable_classes();

$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

$geshi->set_line_style('font: normal normal 95% \'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
$geshi->set_code_style('color: #000020;', 'color: #000020;');

$geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
$geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');

$geshi->set_header_content($legend);
$geshi->set_header_content_style('font-family: Verdana, Arial, sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');

$geshi->set_footer_content('');

$highlightedCode = $geshi->parse_code();

if($_POST['action']=="update") {
	$highlightedCode = preg_replace('/<pre ["a-z=]*>/', '', $highlightedCode);
	$highlightedCode = str_replace('</pre>', '', $highlightedCode);
}

print $highlightedCode;
?>