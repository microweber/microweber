<?php

/**
 * Config File for tinyMCE insertcode plugin
 *
 * PHP version 4
 *
 * @package    insertcode
 * @author     Maxime Lardenois <maxime.lardenois@gmail.com>
 * @copyright  2006 Maxime Lardenois
 * @version    1.0
 * @modified   15/01/2006 - 00:57
 *
 * ********************************************************
 *
 */

/**
 * @var string The absolute path to your geshi main file
 * e.g. /var/www/my_site/classes/
 */
define('IC_GESHI_PATH', dirname(__FILE__)'/geshi/');

/**
 * @var array The languages that can be inserted in your editor.
 * WARNING : The name of the languages must match the ones in Geshi
 * @see Geshi documentation for more information
 */
$icAllowedLanguages = array(
		// e.g "name" => "My Code",
		"php" => "PHP",
		"javascript" => "Javascript",
		"xml" => "XML",
		"sql" => "SQL",
		"css" => "CSS",
		"smarty" => "Smarty Template",
		"html4strict" => "HTML"
);


?>