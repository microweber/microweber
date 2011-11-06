<?php
/*******************************************************************************
  rj_common.php
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file contains a set of common logic used by the other php files.
*******************************************************************************/

//===
// Include the GeSHi file.
// Note: The RJ_InsertCode plugin uses a modified version of GeSHi to fix an
//       issue regarding tab indentation so it is recommended that you do not
//       modify it.
require_once("../geshi/geshi.php");

//===
// getPostedValue(string)
// Extract a posted variable's value given its name
function getPostedValue ( $name )
{
	if ( isset($_POST[$name]) )
	{
		$value = $_POST[$name];
			  
		// when magic_quotes_gpc is enabled, we need to use stripslashes() to
		// remove all of the escape characters.
		if( get_magic_quotes_gpc() )
		{
			$value = stripslashes($value);
		}
		 
		return $value;
	}
	 
	return null;
}

//===
// initGeshiStyles(string)
// Set our default GeSHi styles.
function initGeshiStyles ( $geshi )
{	
	$geshi->set_overall_style( 'border-collapse: collapse; width: 100%; border: 1px solid #054b6e; background: #f8f8f8;', false );

	// line pre, code data cell and code pre
	$geshi->set_code_style(	'margin:0; background:none; vertical-align:top; padding: 0px 4px; font-size: 12px;', false );

	// Header style
	$geshi->set_header_content_style('background: #dddddd; color: #054b6e; padding: 2px 0px; text-align:center; font: bold italic 12px Verdana, Geneva, Arial, Helvetica, sans-serif;');

	// GeSHi doesn't expose a function to set the "ln" style from code, so we are
	// accessing the member directly
	$geshi->table_linenumber_style = 'width: 1px; background: #f0f0f0; vertical-align:top; color: #676f73; border-right:1px dotted #dddddd; font-size: 12px; text-align:right;'; 
}

?>
