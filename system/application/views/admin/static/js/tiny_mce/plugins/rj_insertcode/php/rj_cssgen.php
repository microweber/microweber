<?php
/*******************************************************************************
  rj_cssgen.php
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file allows the user to select a language and generate the appropriate
  CSS style sheet from it. The user can also generate a compiled style sheet of
  all languages.
*******************************************************************************/

// Include files
require_once("rj_common.php");

// Extract parameters
$stage			= getPostedValue('stage');
$selectedLang	= getPostedValue('selectedLang');
$outputAll		= getPostedValue('outputAll');

// Create a GeSHi instance.
$geshi = new GeSHi;

// Set the base styles
initGeshiStyles ( $geshi );

// Build a list of all the language php files located in the language directory
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
	   
// Process our current state
if ( !$stage || $stage == 1 )
{
	// During stage one we output the form for the user to fill out. Form submission will move us onto stage two.	
	echo '<html>' . "\n";
	echo '<head><title>RJ_InsertCode CSS Generator</title></head>' . "\n";
	echo '<body>' . "\n";	
	echo '<h1>RJ_InsertCode CSS Generator</h1><br />' . "\n";
    echo '<form action="rj_cssgen.php" method="post">' . "\n";
	echo '<input type="hidden" name="stage" value="2" />' . "\n";
	echo 'Choose single language:<br />' . "\n";
	echo '<select name="selectedLang">' . "\n";
	   
	// Output a sect option for each language
	foreach ($languages as $lang)
	{
		$geshi->set_language($lang);
		echo '<option value="' . $lang . '">' . $geshi->get_language_name() . '</option>' . "\n";
	}

	echo '</select><br /><br />' . "\n";
	echo 'Output all languages: <input type="checkbox" name="outputAll" value="true"/><br /><br />' . "\n";
	echo '<input type="submit" value="Create style sheet" />' . "\n";
	echo '</form>' . "\n";
	echo '</body>' . "\n";
	echo '</html>' . "\n";
}
elseif ( $stage == 2 )
{
	// During stage two we output the css file based on the options selected in stage one.	
	if( $outputAll == "true" )
	{
		// set the file type and name
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="all_languages.css"');

		// print the concatenated style sheets of all the languages
		$trimComments = 0;
		foreach ($languages as $lang)
		{
			$geshi->set_language($lang);
			$output = $geshi->get_stylesheet(false);
					 
			if( $trimComments == 0 )
			{
				// output with header comments
				echo $geshi->get_stylesheet(false);
				$trimComments = 1;
			}
			else
			{
				// remove header comments on all stylesheets after the first one
				echo preg_replace('/^\/\*\*.*?\*\//s', '', $geshi->get_stylesheet(false));
			}
		}
	}
	else
	{
		// set the file type and name
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $selectedLang . '.css"');

		// print the style sheet of the selected language
		$geshi->set_language($selectedLang);
		echo $geshi->get_stylesheet(false);
	}
}
?>
