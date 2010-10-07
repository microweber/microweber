<?php

/**
 * Retrieve the authorized options for tinyMCE insertcode plugin
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
 * DO NOT MODIFY, CONFIG IS HERE : tinyMCE_folder/plugins/insertcode/config/config.php
 *
 */
 
require_once("../config/config.php");

$output = "";
foreach ($icAllowedLanguages as $name => $longName) {
	$output .= "\t<option value=\"$name\">$longName</option>\n";
}

?>
<select name="codeType">
<?php echo $output?>
</select>