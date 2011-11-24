<?php
/**
 * @version		$Id: folder.php 9764 2007-12-30 07:48:11Z ircmaxell $
 * @package		Joomla.Framework
 * @subpackage	FileSystem
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );

require_once (dirname( __FILE__ ) . '/path.php') ;

/**
 * A Folder handling class
 *
 * @static
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package 	Joomla.Framework
 * @subpackage	FileSystem
 * @since		1.5
 */
class extFolder {
	
	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param string $path Folder name relative to installation dir
	 * @return boolean True if path is a folder
	 * @since 1.5
	 */
	function exists( $path ) {
		return is_dir( extPath::clean( $path ) ) ;
	}
	
	/**
	 * Makes path name safe to use
	 *
	 * @access	public
	 * @param	string $path The full path to sanitise
	 * @return	string The sanitised string
	 * @since	1.5
	 */
	function makeSafe( $path ) {
		$ds = (DS == '\\') ? '\\' . DS : DS ;
		$regex = array( '#[^A-Za-z0-9:\_\-' . $ds . ' ]#' ) ;
		return preg_replace( $regex, '', $path ) ;
	}
}