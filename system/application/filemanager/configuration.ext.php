<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );

/**
 * Configuration settings for frontend file browsing
 */

// ALLOW FRONTEND BROWSING ? Change to
//$frontend_enabled = true; // If needed!
$frontend_enabled = false;

// THE SUBDIRECTORY USERS CAN BROWSE INCLUDING ALL SUBDIRECTORIES
// relative to your physical Joomla root path ($mosConfig_absolute_path)!
// Please note: You currently can't exclude directories or files within
// the specified directory. All files and directories will be visible and downloadable
$subdir = '/dmdocuments';

?>
