<?php
/**
* @version $Id: standalone.php 154 2009-07-28 19:27:23Z soeren $
* @package eXtplorer
* @copyright Copyright (C) 2010 soeren. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
if( ! defined( '_JEXEC' ) && ! defined( '_VALID_MOS' ) )
	die( 'Restricted access' ) ;

/**
 * Rar format adapter for the extArchive class
 *
 * The RAR compression method requires the RAR PECL module
 */
class extArchiveRar {
	
	/**
	 * Create a ZIP compressed file from an array of file data.
	 *
	 * @todo	Finish Implementation
	 *
	 * @access	public
	 * @param	string	$archive	Path to save archive
	 * @param	array	$files		Array of files to add to archive
	 * @param	array	$options	Compression options [unused]
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function create($archive, $files, $options = array ()) {
		
		return PEAR::raiseError( 'RAR creation not supported by PHP at all' );
		
	}

	/**
	 * Extract a ZIP compressed file to a given path
	 *
	 * @access	public
	 * @param	string	$archive		Path to ZIP archive to extract
	 * @param	string	$destination	Path to extract archive into
	 * @param	array	$options		Extraction options [unused]
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function extract( $archive, $destination, $options = array () ) {
		if( ! is_file( $archive ) ) {
			return PEAR::raiseError( 'Archive does not exist' ) ;
		}
		
		if( !$this->isSupported() ) {
			return PEAR::raiseError( 'RAR Extraction not supported by your PHP installation.' ) ;
		}
		$arch = rar_open($archive);
		if ($arch === FALSE)
			return PEAR::raiseError("Cannot open the rar archive");
			
		$entries = rar_list($arch);
		if ($entries === FALSE) {
			return PEAR::raiseError("Cannot retrieve entries");
		}

		foreach( $entries as $file ) {
			$file->extract( $destination );
		}
		
		return true;
		
	}

	/**
	 * Method to determine if the server has native zip support for faster handling
	 *
	 * @access	public
	 * @return	boolean	True if php has native ZIP support
	 * @since	1.5
	 */
	function isSupported() {
		return (extension_loaded( 'rar' ) && class_exists( 'RarArchive' )) ;
	}
	
}
