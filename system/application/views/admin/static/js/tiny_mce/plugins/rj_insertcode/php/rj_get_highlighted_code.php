<?php
/*******************************************************************************
  rj_get_highlighted_code.php
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file generates GeSHi highlighted code from the following parameter list.
  
  Parameters:
	codeType		GeSHi language name
	codeHeader		Header text
	codeContent		Code text
	startLine		Line number to start counting from
	useClasses		Set "true" for class based styles instead of inline styles

  This file has been derived from a file with the following header:
	Highlight the submitted code using Geshi
	PHP version 4
	package    insertcode
	author     Maxime Lardenois <maxime.lardenois@gmail.com>
	copyright  2006 Maxime Lardenois
	version    1.0
	modified   15/01/2006 - 19:09
*******************************************************************************/

// Include files
require_once("rj_common.php");

// Extract parameters
$codeType		= getPostedValue('codeType');
$codeHeader		= getPostedValue('codeHeader');
$codeContent	= getPostedValue('codeContent');
$lineNumbers	= getPostedValue('lineNumbers');
$startLine		= getPostedValue('startLine');
$useClasses		= getPostedValue('useClasses');


//===
// Note: The old InsertCode plugin did some extra adjustments to the strings here.
//       The equivalent code and my reasons for commenting it out are listed below.	
//
// The code content had leading and trailing whitespace removed. I'm assuming that
// if you entered whitespace you wanted to keep it
//		$codeContent	= trim($codeContent);
//
// The header content had leading and trailing whitespace removed along with HTML
// tags being stripped from it. Once again, I'm assuming you said information was
// added for a reason.
//		$codeHeader		= strip_tags(trim($codeHeader));
//
// A manual tab replacement was done, but GeSHi contains logic to do tab processing
// appropriate for the format parametersgiven to it.
//		$codeContent	= str_replace("\t", "  ", $codeContent);
//

//===
// initialize GeSHi
$geshi =& new GeSHi($codeContent, $codeType);

//===
// Tell GeSHi to use stylesheets. Note that this should be the fist thing
// called when stylesheets are going to be used.
if( $useClasses == "true" )
{
	$geshi->enable_classes();
}


//===
// We could set an overall class here, but it isn't necessary due to the
// fact we already surround the output in <div> tags
//$geshi->set_overall_class("rj_insertcode");

//===
// Select the header type
//$geshi->set_header_type(GESHI_HEADER_DIV);
//$geshi->set_header_type(GESHI_HEADER_PRE);
//$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
//$geshi->set_header_type(GESHI_HEADER_NONE);

//===
// Select the line number method
if( $lineNumbers == "true" )
{
	$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	//$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 5);
}
else
{
	$geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
}

//===
// Set where to start counting line numbers from
$geshi->start_line_numbers_at( (int)$startLine );

//===
// Set the tab processing (only used for non-pre header types)
$geshi->set_use_language_tab_width(true);
$geshi->set_tab_width(4);

//===
// Set the base styles
initGeshiStyles ( $geshi );

//===
// Set the header and footer
// Note: As of this moment the footer doesn't seem to work right with the table
//       output format so we are leaving it empty.
$geshi->set_header_content($codeHeader);
$geshi->set_footer_content('');

//===
// Process the code
$highlightedCode = $geshi->parse_code();

//===
// Note: The old InsertCode plugin did some extra adjustments to the output string
//       here. It removed the div wrapping the output and is below for reference.	
//
//	$highlightedCode = preg_replace('/^\s*<div ["a-z=]*>/', '', $highlightedCode);
//	$highlightedCode = str_replace('</div>\s*$', '', $highlightedCode);
//

//===
// Output the result
print $highlightedCode;

?>
