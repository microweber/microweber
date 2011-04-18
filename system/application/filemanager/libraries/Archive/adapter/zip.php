<?php
/**
 * @version		$Id: zip.php 6961 2007-03-15 16:06:53Z tcp $
 * @package		Joomla.Framework
 * @subpackage	FileSystem
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// Check to ensure this file is within the rest of the framework
if( ! defined( '_JEXEC' ) && ! defined( '_VALID_MOS' ) )
	die( 'Restricted access' ) ;

/**
 * ZIP format adapter for the JArchive class
 *
 * The ZIP compression code is partially based on code from:
 *   Eric Mueller <eric@themepark.com>
 *   http://www.zend.com/codex.php?id=535&single=1
 *
 *   Deins125 <webmaster@atlant.ru>
 *   http://www.zend.com/codex.php?id=470&single=1
 *
 * The ZIP compression date code is partially based on code from
 *   Peter Listiak <mlady@users.sourceforge.net>
 *
 * This class is inspired from and draws heavily in code and concept from the Compress package of
 * The Horde Project <http://www.horde.org>
 *
 * @contributor  Chuck Hagenbuch <chuck@horde.org>
 * @contributor  Michael Slusarz <slusarz@horde.org>
 * @contributor  Michael Cochrane <mike@graftonhall.co.nz>
 *
 * @package 	Joomla.Framework
 * @subpackage	FileSystem
 * @since		1.5
 */
class extArchiveZip {
	/**
	 * ZIP compression methods.
	 * @var array
	 */
	var $_methods = array (
		0x0 => 'None',
		0x1 => 'Shrunk',
		0x2 => 'Super Fast',
		0x3 => 'Fast',
		0x4 => 'Normal',
		0x5 => 'Maximum',
		0x6 => 'Imploded',
		0x8 => 'Deflated'
	);
	var $datasec = array();					// Compressed data
	var $ctrl_dir = array();					// Central directory
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";		// EOF directory record
	var $old_offset = 0;					// Last offset position
	/**
	 * Beginning of central directory record.
	 * @var string
	 */
	var $_ctrlDirHeader = "\x50\x4b\x01\x02";

	/**
	 * End of central directory record.
	 * @var string
	 */
	var $_ctrlDirEnd = "\x50\x4b\x05\x06\x00\x00\x00\x00";

	/**
	 * Beginning of file contents.
	 * @var string
	 */
	var $_fileHeader = "\x50\x4b\x03\x04";

	/**
	 * ZIP file data buffer
	 * @var string
	 */
	var $_data = null;

	/**
	 * ZIP file metadata array
	 * @var array
	 */
	var $_metadata = null;

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
		// Initialize variables
		$contents = array();
		$ctrldir  = array();
		$remove_path = '';
		if( !empty($options['remove_path'])) {
			$remove_path = $options['remove_path'];
		}
		$this->addFileList($files, $remove_path );
		return $this->save($archive);
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
		
		if( $this->hasNativeSupport() ) {
			return $this->_extractNative( $archive, $destination, $options ) ;
		} else {
			return $this->_extract( $archive, $destination, $options ) ;
		}
	}

	/**
	 * Method to determine if the server has native zip support for faster handling
	 *
	 * @access	public
	 * @return	boolean	True if php has native ZIP support
	 * @since	1.5
	 */
	function hasNativeSupport() {
		return (function_exists( 'zip_open' ) && function_exists( 'zip_read' )) ;
	}
	
	/**
	 * Checks to see if the data is a valid ZIP file.
	 *
	 * @access	public
	 * @param	string	$data	ZIP archive data buffer
	 * @return	boolean	True if valid, false if invalid.
	 * @since	1.5
	 */
	function checkZipData(& $data) {
		if (strpos($data, $this->_fileHeader) === false) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Extract a ZIP compressed file to a given path using a php based algorithm that only requires zlib support
	 *
	 * @access	private
	 * @param	string	$archive		Path to ZIP archive to extract
	 * @param	string	$destination	Path to extract archive into
	 * @param	array	$options		Extraction options [unused]
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function _extract( $archive, $destination, $options ) {
		// Initialize variables
		$this->_data = null ;
		$this->_metadata = null ;

		if( ! extension_loaded( 'zlib' ) ) {
			return PEAR::raiseError( 'Zlib Not Supported' ) ;
		}
		
		if( ! $this->_data = file_get_contents( $archive ) ) {
			return PEAR::raiseError( 'Unable to read archive' ) ;
		}
		if( ! $this->_getZipInfo( $this->_data ) ) {
			return false ;
		}

		for( $i = 0, $n = count( $this->_metadata ) ; $i < $n ; $i ++ ) {
			if( substr( $this->_metadata[$i]['name'], - 1, 1 ) != '/' && substr( $this->_metadata[$i]['name'], - 1, 1 ) != '\\' ) {
				$buffer = $this->_getFileData( $i ) ;
				$path = extPath::clean( $destination . DS . $this->_metadata[$i]['name'] ) ;
				// Make sure the destination folder exists
				if( ! extMkdirR( dirname( $path ) ) ) {
					return PEAR::raiseError( 'Unable to create destination' ) ;
				}
				if( file_put_contents( $path, $buffer ) === false ) {
					return PEAR::raiseError( 'Unable to write entry' ) ;
				}
			}
		}
		return true;
	}

	/**
	 * Extract a ZIP compressed file to a given path using native php api calls for speed
	 *
	 * @access	private
	 * @param	string	$archive		Path to ZIP archive to extract
	 * @param	string	$destination	Path to extract archive into
	 * @param	array	$options		Extraction options [unused]
	 * @return	boolean	True if successful
	 * @since	1.5
	 */
	function _extractNative( $archive, $destination, $options ) {
		$zip = zip_open( $archive );
		
		if( is_resource( $zip ) ) {
			// Make sure the destination folder exists
			if( ! is_dir( $destination ) && ! mkdir( $destination ) ) {
				return PEAR::raiseError( 'Unable to create destination' ) ;
			}
			// Read files in the archive
			while( $file = @zip_read( $zip ) ) {
				if( zip_entry_open( $zip, $file, "r" ) ) {
					if( substr( zip_entry_name( $file ), strlen( zip_entry_name( $file ) ) - 1 ) != "/" ) {
						$buffer = zip_entry_read( $file, zip_entry_filesize( $file ) ) ;
						if( !extMkdirR(dirname($destination . DS . zip_entry_name( $file ))) || file_put_contents( $destination . DS . zip_entry_name( $file ), $buffer ) === false ) {
							return PEAR::raiseError( 'Unable to write entry: '.$destination . DS. zip_entry_name( $file ) ) ;
						}
						zip_entry_close( $file ) ;
					}
				} else {
					return PEAR::raiseError( 'Unable to read entry' ) ;
				}
			}
			@zip_close( $zip ) ;
			
		} else {
			return PEAR::raiseError( "Unable to open archive: ".extArchiveZip::zipFileErrMsg($zip) ) ;
		}
		return true ;
	}
	
	/**
	 * Get the list of files/data from a ZIP archive buffer.
	 *
	 * @access	private
	 * @param 	string	$data	The ZIP archive buffer.
	 * @return	array	Archive metadata array
	 * <pre>
	 * KEY: Position in zipfile
	 * VALUES: 'attr'    --  File attributes
	 *         'crc'     --  CRC checksum
	 *         'csize'   --  Compressed file size
	 *         'date'    --  File modification time
	 *         'name'    --  Filename
	 *         'method'  --  Compression method
	 *         'size'    --  Original file size
	 *         'type'    --  File type
	 * </pre>
	 * @since	1.5
	 */
	function _getZipInfo( & $data ) {
		// Initialize variables
		$entries = array( ) ;

		// Find the last central directory header entry
		$fhLast = strpos($data, $this->_ctrlDirEnd);
		do {
			$last = $fhLast;
		} while(($fhLast = strpos($data, $this->_ctrlDirEnd, $fhLast+1)) !== false);


		// Find the central directory offset
		$offset = 0;
		if ($last) {
			$endOfCentralDirectory = unpack('vNumberOfDisk/vNoOfDiskWithStartOfCentralDirectory/vNoOfCentralDirectoryEntriesOnDisk/vTotalCentralDirectoryEntries/VSizeOfCentralDirectory/VCentralDirectoryOffset/vCommentLength', substr($data, $last+4));
			$offset	= $endOfCentralDirectory['CentralDirectoryOffset'];
		}

		// Get details from Central directory structure.
		$fhStart = strpos($data, $this->_ctrlDirHeader, $offset);
		do {
			if( strlen( $data ) < $fhStart + 31 ) {
				return PEAR::raiseError( 'Invalid ZIP data' ) ;
			}
			$info = unpack( 'vMethod/VTime/VCRC32/VCompressed/VUncompressed/vLength', substr( $data, $fhStart + 10, 20 ) ) ;
			$name = substr( $data, $fhStart + 46, $info['Length'] ) ;

			$entries[$name] = array( 'attr' => null , 'crc' => sprintf( "%08s", dechex( $info['CRC32'] ) ) , 'csize' => $info['Compressed'] , 'date' => null , '_dataStart' => null , 'name' => $name , 'method' => $this->_methods[$info['Method']] , '_method' => $info['Method'] , 'size' => $info['Uncompressed'] , 'type' => null ) ;
			$entries[$name]['date'] = mktime( (($info['Time'] >> 11) & 0x1f), (($info['Time'] >> 5) & 0x3f), (($info['Time'] << 1) & 0x3e), (($info['Time'] >> 21) & 0x07), (($info['Time'] >> 16) & 0x1f), ((($info['Time'] >> 25) & 0x7f) + 1980) ) ;

			if( strlen( $data ) < $fhStart + 43 ) {
				return PEAR::raiseError( 'Invalid ZIP data' ) ;
			}
			$info = unpack('vInternal/VExternal/VOffset', substr($data, $fhStart +36, 10));

			$entries[$name]['type'] = ($info['Internal'] & 0x01) ? 'text' : 'binary';
			$entries[$name]['attr'] = (($info['External'] & 0x10) ? 'D' : '-') .
									  (($info['External'] & 0x20) ? 'A' : '-') .
									  (($info['External'] & 0x03) ? 'S' : '-') .
									  (($info['External'] & 0x02) ? 'H' : '-') .
									  (($info['External'] & 0x01) ? 'R' : '-');
			$entries[$name]['offset'] = $info['Offset'];

			// Get details from local file header since we have the offset
			$lfhStart = strpos($data, $this->_fileHeader, $entries[$name]['offset']);
			if (strlen($data) < $lfhStart +34) {
				return PEAR::raiseError( 'Invalid ZIP data' ) ;
			}
			$info = unpack('vMethod/VTime/VCRC32/VCompressed/VUncompressed/vLength/vExtraLength', substr($data, $lfhStart +8, 25));
			$name = substr($data, $lfhStart +30, $info['Length']);
			$entries[$name]['_dataStart'] = $lfhStart +30 + $info['Length'] + $info['ExtraLength'];
		} while ((($fhStart = strpos($data, $this->_ctrlDirHeader, $fhStart +46)) !== false));

		$this->_metadata = array_values($entries);
		return true;
	}

	/**
	 * Returns the file data for a file by offsest in the ZIP archive
	 *
	 * @access	private
	 * @param	int		$key	The position of the file in the archive.
	 * @return	string	Uncompresed file data buffer
	 * @since	1.5
	 */
	function _getFileData( $key ) {
		if( $this->_metadata[$key]['_method'] == 0x8 ) {
			// If zlib extention is loaded use it
			if( extension_loaded( 'zlib' ) ) {
				return @ gzinflate( substr( $this->_data, $this->_metadata[$key]['_dataStart'], $this->_metadata[$key]['csize'] ) ) ;
			}
		} elseif( $this->_metadata[$key]['_method'] == 0x0 ) {
			/* Files that aren't compressed. */
			return substr( $this->_data, $this->_metadata[$key]['_dataStart'], $this->_metadata[$key]['csize'] ) ;
		} elseif( $this->_metadata[$key]['_method'] == 0x12 ) {
			// Is bz2 extension loaded?  If not try to load it
			if( ! extension_loaded( 'bz2' ) ) {
				if( ext_isWindows() ) {
					@ dl( 'php_bz2.dll' ) ;
				} else {
					@ dl( 'bz2.so' ) ;
				}
			}
			// If bz2 extention is sucessfully loaded use it
			if( extension_loaded( 'bz2' ) ) {
				return bzdecompress( substr( $this->_data, $this->_metadata[$key]['_dataStart'], $this->_metadata[$key]['csize'] ) ) ;
			}
		}
		return '' ;
	}

	/**
	 * Converts a UNIX timestamp to a 4-byte DOS date and time format
	 * (date in high 2-bytes, time in low 2-bytes allowing magnitude
	 * comparison).
	 *
	 * @access	private
	 * @param	int	$unixtime	The current UNIX timestamp.
	 * @return	int	The current date in a 4-byte DOS format.
	 * @since	1.5
	 */
	function unix2DOSTime( $unixtime = null ) {
		$timearray = (is_null( $unixtime )) ? getdate() : getdate( $unixtime ) ;

		if( $timearray['year'] < 1980 ) {
			$timearray['year'] = 1980 ;
			$timearray['mon'] = 1 ;
			$timearray['mday'] = 1 ;
			$timearray['hours'] = 0 ;
			$timearray['minutes'] = 0 ;
			$timearray['seconds'] = 0 ;
		}
		return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1) ;
	}

	function add_data($data, $name, $time=0) {
		$name = str_replace('\\', '/', $name);
		$dtime = dechex($this->unix2dostime($time));
		$hexdtime = '\x'.$dtime[6].$dtime[7].'\x'.$dtime[4].$dtime[5].'\x'.$dtime[2].$dtime[3].'\x'.$dtime[0].$dtime[1];
        		eval('$hexdtime = "' . $hexdtime . '";');
		
		$fr   = "\x50\x4b\x03\x04";
		$fr   .= "\x14\x00";		// ver needed to extract
		$fr   .= "\x00\x00";		// gen purpose bit flag
		$fr   .= "\x08\x00";		// compression method
		$fr   .= $hexdtime;		// last mod time and date

		// "local file header" segment
		$unc_len	= strlen($data);
		$crc	= crc32($data);
		$zdata	= gzcompress($data);
		$zdata	= substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
		$c_len	= strlen($zdata);
		$fr	.= pack('V', $crc);		// crc32
		$fr	.= pack('V', $c_len);           // compressed filesize
		$fr	.= pack('V', $unc_len);	// uncompressed filesize
		$fr	.= pack('v', strlen($name));	// length of filename
		$fr	.= pack('v', 0);		// extra field length
		$fr	.= $name;

		// "file data" segment
		$fr .= $zdata;

		// "data descriptor" segment (optional but necessary if archive is not
		// served as file)
		$fr .= pack('V', $crc);		// crc32
		$fr .= pack('V', $c_len);		// compressed filesize
		$fr .= pack('V', $unc_len);		// uncompressed filesize

		// add this entry to array
		$this->datasec[] = $fr;
		$new_offset = strlen(implode('', $this->datasec));

		// now add to central directory record
		$cdrec = "\x50\x4b\x01\x02";
		$cdrec .= "\x00\x00";			// version made by
		$cdrec .= "\x14\x00";			// version needed to extract
		$cdrec .= "\x00\x00";			// gen purpose bit flag
		$cdrec .= "\x08\x00";			// compression method
		$cdrec .= $hexdtime;			// last mod time & date
		$cdrec .= pack('V', $crc);			// crc32
		$cdrec .= pack('V', $c_len);			// compressed filesize
		$cdrec .= pack('V', $unc_len);		// uncompressed filesize
		$cdrec .= pack('v', strlen($name));		// length of filename
		$cdrec .= pack('v', 0 );			// extra field length
		$cdrec .= pack('v', 0 );			// file comment length
		$cdrec .= pack('v', 0 );			// disk number start
		$cdrec .= pack('v', 0 );			// internal file attributes
		$cdrec .= pack('V', 32 );			// external file attributes - 'archive' bit set
		
		$cdrec .= pack('V', $this->old_offset );	// relative offset of local header
		$this->old_offset = $new_offset;
		
		$cdrec .= $name;

		// optional extra field, file comment goes here
		// save to central directory
		$this->ctrl_dir[] = $cdrec;
	}
	
	function contents() {
		$data = implode('', $this->datasec);
		$ctrldir = implode('', $this->ctrl_dir);
		return $data.$ctrldir.$this->eof_ctrl_dir.
			pack('v', sizeof($this->ctrl_dir)).	// total # of entries "on this disk"
			pack('v', sizeof($this->ctrl_dir)).	// total # of entries overall
			pack('V', strlen($ctrldir)).		// size of central dir
			pack('V', strlen($data)).		// offset to start of central dir
			"\x00\x00";			// .zip file comment length
	}
	function addFileList( $filelist, $removePath ) {
		
		foreach( $filelist as $file ) {
			$data = file_get_contents( $file );
			if( empty( $data )) continue;
			$cleaned_file = str_replace( $removePath. DIRECTORY_SEPARATOR, '', $file );
			$cleaned_file = str_replace( $removePath, '', $cleaned_file );
			$this->add_data($data, $cleaned_file, filemtime($file));
		}
		return true;
	}
//------------------------------------------------------------------------------
// File functions
	function add($dir, $name) {
		$item=$dir."/".$name;
		
		if(@is_file($item)) {
			if(($fp=fopen($item,"rb"))===false) return false;
			$item_size = filesize($item);
			if( $item_size == 0 ) {
				return true;
			}
			$data=fread($fp, $item_size);
			fclose($fp);
			$this->add_data($data,$name,filemtime($item));
			return true;
		} elseif(@is_dir($item)) {
			if(($handle=opendir($item))===false) return false;
			while(($file=readdir($handle))!==false) {
				if(($file==".." || $file==".")) continue;
				if(!$this->add($dir,$name."/".$file)) return false;
			}
			closedir($handle);
			return true;
		}
		
		return false;
	}
	
	function save($name) {
		$result = file_put_contents($name, $this->contents());
		return $result;
	}
	
	function zipFileErrMsg($errno) {
		 // using constant name as a string to make this function PHP4 compatible
		 $zipFileFunctionsErrors = array(
		   'ZIPARCHIVE::ER_MULTIDISK' => 'Multi-disk zip archives not supported.',
		   'ZIPARCHIVE::ER_RENAME' => 'Renaming temporary file failed.',
		   'ZIPARCHIVE::ER_CLOSE' => 'Closing zip archive failed', 
		   'ZIPARCHIVE::ER_SEEK' => 'Seek error',
		   'ZIPARCHIVE::ER_READ' => 'Read error',
		   'ZIPARCHIVE::ER_WRITE' => 'Write error',
		   'ZIPARCHIVE::ER_CRC' => 'CRC error',
		   'ZIPARCHIVE::ER_ZIPCLOSED' => 'Containing zip archive was closed',
		   'ZIPARCHIVE::ER_NOENT' => 'No such file.',
		   'ZIPARCHIVE::ER_EXISTS' => 'File already exists',
		   'ZIPARCHIVE::ER_OPEN' => 'Can\'t open file', 
		   'ZIPARCHIVE::ER_TMPOPEN' => 'Failure to create temporary file.', 
		   'ZIPARCHIVE::ER_ZLIB' => 'Zlib error',
		   'ZIPARCHIVE::ER_MEMORY' => 'Memory allocation failure', 
		   'ZIPARCHIVE::ER_CHANGED' => 'Entry has been changed',
		   'ZIPARCHIVE::ER_COMPNOTSUPP' => 'Compression method not supported.', 
		   'ZIPARCHIVE::ER_EOF' => 'Premature EOF',
		   'ZIPARCHIVE::ER_INVAL' => 'Invalid argument',
		   'ZIPARCHIVE::ER_NOZIP' => 'Not a zip archive',
		   'ZIPARCHIVE::ER_INTERNAL' => 'Internal error',
		   'ZIPARCHIVE::ER_INCONS' => 'Zip archive inconsistent', 
		   'ZIPARCHIVE::ER_REMOVE' => 'Can\'t remove file',
		   'ZIPARCHIVE::ER_DELETED' => 'Entry has been deleted',
		 );
		 
		 foreach ($zipFileFunctionsErrors as $constName => $errorMessage) {
		   if (defined($constName) and constant($constName) === $errno) {
		     return 'Zip File Function error: '.$errorMessage;
		   }
		 }
		 return 'Zip File Function error: unknown';
	}
}
