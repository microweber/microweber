<?php
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/*------------------------------------------------------------------------------
     The contents of this file are subject to the Mozilla Public License
     Version 1.1 (the "License"); you may not use this file except in
     compliance with the License. You may obtain a copy of the License at
     http://www.mozilla.org/MPL/

     Software distributed under the License is distributed on an "AS IS"
     basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
     License for the specific language governing rights and limitations
     under the License.

     The Original Code is lib_zip.php, released on 2003-03-07.

     The Initial Developer of the Original Code is The QuiX project.

     Alternatively, the contents of this file may be used under the terms
     of the GNU General Public License Version 2 or later (the "GPL"), in
     which case the provisions of the GPL are applicable instead of
     those above. If you wish to allow use of your version of this file only
     under the terms of the GPL and not to allow others to use
     your version of this file under the MPL, indicate your decision by
     deleting  the provisions above and replace  them with the notice and
     other provisions required by the GPL.  If you do not delete
     the provisions above, a recipient may use your version of this file
     under either the MPL or the GPL."
------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------
Author: The QuiX project
	quix@free.fr
	http://www.quix.tk
	http://quixplorer.sourceforge.net
	
Comment:
	ZipFile class
	
	Have Fun...
------------------------------------------------------------------------------*/
//------------------------------------------------------------------------------
class ZipFile {
//------------------------------------------------------------------------------
// Internal  vars
	var $datasec = array();					// Compressed data
	var $ctrl_dir = array();					// Central directory
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";		// EOF directory record
	var $old_offset = 0;					// Last offset position
//------------------------------------------------------------------------------
// Internal function
	function unix2dos_time($unixtime=0) {
		//	Convert an Unix timestamp to a four byte DOS date and time format
		//	(date in high two bytes, time in low two bytes allowing magnitude comparison).
		$timearray = ($unixtime==0)?getdate():getdate($unixtime);
		if ($timearray['year'] < 1980) {
			$timearray['year'] = 1980;
			$timearray['mon'] = 1;
			$timearray['mday'] = 1;
			$timearray['hours'] = 0;
			$timearray['minutes'] = 0;
			$timearray['seconds'] = 0;
		}
		return (($timearray['year']-1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
			($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
	}
//------------------------------------------------------------------------------
// Data functions
	function add_data($data, $name, $time=0) {
		$name = str_replace('\\', '/', $name);
		$dtime = dechex($this->unix2dos_time($time));
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
}
//------------------------------------------------------------------------------
?>
