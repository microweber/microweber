<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version		$Id: archive.php 13314 2009-10-24 07:09:41Z eddieajau $
 * @package		Joomla.Framework
 * @subpackage	FileSystem
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
if( !class_exists('PEAR')) {
	@include_once 'PEAR.php';
	if( !class_exists('PEAR')) {
		require_once(dirname(__FILE__).'/../PEAR.php');
	}
}
/**
 * An Archive handling class
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	FileSystem
 * @since		1.5
 */
class extArchive {
	/**
	 * @param	string	The name of the archive file
	 * @param	string	Directory to unpack into
	 * @return	boolean	True for success
	 */
	function extract( $archivename, $extractdir ) {
		require_once( dirname(__FILE__). '/file.php' ) ;
		require_once( dirname(__FILE__). '/folder.php' ) ;

		$untar = false ;
		$result = false ;
		$ext = extFile::getExt( strtolower( $archivename ) ) ;
		// check if a tar is embedded...gzip/bzip2 can just be plain files!
		if( extFile::getExt( extFile::stripExt( strtolower( $archivename ) ) ) == 'tar' ) {
			$untar = true ;
		}
		
		switch( $ext) {
			case 'tar' :
			case 'tgz' :
			case 'gz' : // This may just be an individual file (e.g. sql script)
			case 'gzip' :
			case 'tbz' :
			case 'tbz2' :
			case 'bz2' : // This may just be an individual file (e.g. sql script)
			case 'bzip2' :
					require_once( dirname(__FILE__).'/../Tar.php' ) ;
					$archive = new Archive_Tar( $archivename );
					$result = $archive->extract( $extractdir );
				
			break ;
			default :
				$adapter = & extArchive::getAdapter( $ext ) ;
				if( $adapter ) {
					$result = $adapter->extract( $archivename, $extractdir ) ;
				} else {
					return PEAR::raiseError('Unknown Archive Type: '.$ext );
				}
			break ;
		}
		return $result;
	}
	
	function &getAdapter( $type ) {
		static $adapters ;
		
		if( ! isset( $adapters ) ) {
			$adapters = array( ) ;
		}
		
		if( ! isset( $adapters[$type] ) ) {
			// Try to load the adapter object
			$class = 'extArchive' . ucfirst( $type ) ;
			
			if( ! class_exists( $class ) ) {
				$path = dirname( __FILE__ )  . '/adapter/' . strtolower( $type ) . '.php' ;
				if( file_exists( $path ) ) {
					require_once ($path) ;
				} else {
					echo 'Unknown Archive Type: '.$class;
					ext_Result::sendResult('archive', false, 'Unable to load archive' ) ;
				}
			}
			
			$adapters[$type] = new $class( ) ;
		}
		return $adapters[$type] ;
	}
	
	/**
	 * @param	string	The name of the archive
	 * @param	mixed	The name of a single file or an array of files
	 * @param	string	The compression for the archive
	 * @param	string	Path to add within the archive
	 * @param	string	Path to remove within the archive
	 * @param	boolean	Automatically append the extension for the archive
	 * @param	boolean	Remove for source files
	 */
	function create( $archive, $files, $compress = 'tar', $addPath = '', $removePath = '', $autoExt = false ) {
		$compress = strtolower( $compress );
		if( $compress == 'tgz' || $compress == 'tbz' || $compress == 'tar') {
			
			require_once( _EXT_PATH.'/libraries/Tar.php' ) ;
			
			if( is_string( $files ) ) {
				$files = array( $files ) ;
			}
			if( $autoExt ) {
				$archive .= '.' . $compress ;
			}
			if( $compress == 'tgz'  ) $compress = 'gz';
			if( $compress == 'tbz'  ) $compress = 'bz2';
			
			$tar = new Archive_Tar( $archive, $compress ) ;
			$tar->setErrorHandling( PEAR_ERROR_PRINT ) ;
			$result = $tar->addModify( $files, $addPath, $removePath ) ;
			
			return $result;
		}
		elseif( $compress == 'zip' ) {
		    $adapter = & extArchive::getAdapter( 'zip' ) ;
			if( $adapter ) {
				$result = $adapter->create( $archive, $files, array('remove_path' => $removePath ) ) ;
			}
			if($result == false ) {
				return PEAR::raiseError( 'Unrecoverable ZIP Error' );
			}
		}
	}
}