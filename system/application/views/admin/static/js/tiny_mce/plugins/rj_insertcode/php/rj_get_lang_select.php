<?php
/*******************************************************************************
  rj_get_lang_select.php
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file generation a <select> list from the languages supported by
  the GeSHi installation. This list is requested by the "rj_insertcode.js"
  file when the window is opened.
*******************************************************************************/

// Include files
require_once("rj_common.php");

// Extract parameters
$selectedValue = getPostedValue('selectedValue');

// Create a GeSHi instance.
$geshi = new GeSHi;

// Build a list of all the language php files located in the language
// directory
$languages = array();
if ($handle = opendir($geshi->language_path))
{
    while (($file = readdir($handle)) !== false)
	{
        $pos = strpos($file, '.');
        if ($pos > 0 && substr($file, $pos) == '.php')
		{
            $languages[] = substr($file, 0, $pos);
        }
    }
    closedir($handle);
}

// Sort the languages alphabetically
sort($languages);

// Output the start of the select block
echo '<select name="codeType" id="codeType">' . "\n";

// Output an option for each language and set the selected
// attribute if we were provided with a selectedValue
foreach ($languages as $lang)
{
    $geshi->set_language($lang);

	$selected = ' ';
	if( $selectedValue == $lang )
	{
        $selected = ' selected="selected"';
    }
	
	echo '<option value="' . $lang . '"' . $selected . '>' . $geshi->get_language_name() . '</option>' . "\n";
}

// Output the end of the select block
echo '</select>' . "\n";
?>
