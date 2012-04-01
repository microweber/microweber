<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: functions.php 184 2011-01-11 16:57:43Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
 * 
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 * 
 */

/**
 * THESE ARE NUMEROUS HELPER FUNCTIONS FOR THE OTHER INCLUDE FILES
 */

function make_link($_action,$_dir,$_item=NULL,$_order=NULL,$_srt=NULL,$languages=NULL, $extra=null) {
	// make link to next page
	if($_action=="" || $_action==NULL) $_action="list";

	if($_item=="") $_item=NULL;
	if($_order==NULL) $_order=$GLOBALS["order"];
	if($_srt==NULL) $_srt=$GLOBALS["direction"];
	if($languages==NULL) $languages=(isset($GLOBALS["lang"])?$GLOBALS["lang"]:NULL);

	$link=$GLOBALS["script_name"]."?option=com_extplorer&action=".$_action;
	if(!is_null($_dir )) {
		$link.="&dir=".urlencode($_dir);
	}
	if($_item!=NULL) $link.="&item=".urlencode($_item);
	if($_order!=NULL) $link.="&order=".$_order;
	if($_srt!=NULL) $link.="&direction=".$_srt;
	if($languages!=NULL) $link.="&lang=".$languages;
	if(!is_null($extra)) {
		$link .= $extra;
	}
	return $link;
}
//------------------------------------------------------------------------------
function get_abs_dir($dir) {			// get absolute path
	if( ext_isFTPMode() ) {
		if( $dir != '' && $dir[0] != '/' && $dir[1] != ':') {
			$dir = '/'.$dir;
		}
		return $dir;
	}
	$abs_dir=$GLOBALS["home_dir"];

	if($dir!="" && !@stristr( $dir, $abs_dir )) $abs_dir.="/".$dir;
	elseif(@stristr( $dir, $abs_dir )) $abs_dir = "/".$dir;
	/*else {
		$abs_dir = $dir;
	}*/
	$realpath = str_replace('\\', '/', realpath($abs_dir) );
	if( $realpath == '') {
		return $abs_dir;
	}
	else {
		return $realpath;
	}

	return $realpath;
}
//------------------------------------------------------------------------------
function get_abs_item($dir, $item) {		// get absolute file+path
	if( is_array( $item )) {
		// FTP Mode
		$abs_item = '/' . get_abs_dir($dir)."/".$item['name'];
		if( get_is_dir($item)) $abs_item.='/';
		return extPathName($abs_item); 
	}
	return extPathName( get_abs_dir($dir)."/".$item );
}
/**
 * Returns the LS info array from an ftp directory listing
 *
 * @param unknown_type $dir
 * @param unknown_type $item
 * @return unknown
 */
function get_item_info( $dir, $item ) {
	$ls = getCachedFTPListing( $dir );
	if( empty($ls)) return false;
	foreach( $ls as $entry ) {
		if( $entry['name'] == $item ) {
			return $entry;
		}
	}
	if( $dir != '') {
		return $dir.'/'.$item;
	}
	return $item;
}
//------------------------------------------------------------------------------
function get_rel_item($dir,$item) {		// get file relative from home
	if($dir!="") return $dir."/".$item;
	else return $item;
}
//------------------------------------------------------------------------------
function get_is_file( $abs_item) {		// can this file be edited?
	if( ext_isFTPMode() && is_array( $abs_item )) {
		return empty($abs_item['is_dir']);
	} elseif( ext_isFTPMode() ) {
		$info = get_item_info( dirname($abs_item), basename($abs_item));
		return empty($info['is_dir']);
	}

	return @is_file($abs_item);
}
//------------------------------------------------------------------------------
function get_is_dir( $abs_item ) {		// is this a directory?
	if( ext_isFTPMode() && is_array( $abs_item )) {
		return !empty($abs_item['is_dir']);
	}
	elseif( ext_isFTPMode() ) {
		$info = get_item_info( dirname( $abs_item), basename( $abs_item ));
		return !empty($info['is_dir']);
	}
	return @is_dir( $abs_item );
}
//------------------------------------------------------------------------------
function parse_file_type( $abs_item ) {		// parsed file type (d / l / -)

	if(@get_is_dir($abs_item)) return "d";
	if(@is_link($abs_item)) return "l";
	return "-";
}
//------------------------------------------------------------------------------
function get_file_perms( $item) {		// file permissions
	if( ext_isFTPMode() && isset($item['rights']) ) {
		$perms = decoct( bindec( decode_ftp_rights($item['rights']) ) );
		return $perms;
	} elseif( is_numeric($item['mode'])) { //SFTP
		return @decoct($item['mode']  & 0777);
	}	
	return @decoct(@fileperms( $item ) & 0777);
}

function get_languages() {
	$langfiles = extReadDirectory( _EXT_PATH.'/languages' );
	$langs = array();
	foreach( $langfiles as $lang ) {
		if( stristr( $lang, '_mimes') || $lang == 'index.html') continue;
		$langs[basename( $lang, '.php' )] = ucwords(str_replace( '_', ' ', basename( $lang, '.php' )));
	}
	return $langs;
}
//------------------------------------------------------------------------------
function parse_file_perms($mode) {		// parsed file permisions

	if(strlen($mode)<3) return "---------";
	$parsed_mode="";
	for($i=0;$i<3;$i++) {
		// read
		if(($mode{$i} & 04)) $parsed_mode .= "r";
		else $parsed_mode .= "-";
		// write
		if(($mode{$i} & 02)) $parsed_mode .= "w";
		else $parsed_mode .= "-";
		// execute
		if(($mode{$i} & 01)) $parsed_mode .= "x";
		else $parsed_mode .= "-";
	}
	return $parsed_mode;
}

function decode_ftp_rights( $rights) {
	$parsed_mode="";
	for($i=0;$i<9;$i++) {
		// read
		if( $rights[$i] != '-' ) {
			$parsed_mode .= '1';
		}
		else {
			$parsed_mode.= '0';
		}
	}

	return $parsed_mode;
}
//------------------------------------------------------------------------------
function get_file_size( $abs_item) {		// file size
	return @$GLOBALS['ext_File']->filesize( $abs_item );
}
//------------------------------------------------------------------------------

function get_dir_size($dir) {
	if(is_file($dir)) return array('size'=>filesize($dir),'howmany'=>0);
	if($dh=opendir($dir)) {
		$size=0;
		$n = 0;
		while(($file=readdir($dh))!==false) {
			if($file=='.' || $file=='..') continue;
			$n++;
			$data = get_dir_size($dir.'/'.$file);
			$size += $data['size'];
			$n += $data['howmany'];
		}
		closedir($dh);
		return array('size'=>$size,'howmany'=>$n);
	}
	return array('size'=>0,'howmany'=>0);
}

function parse_file_size($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    if( !is_float($bytes)) {
    	$bytes = (float)sprintf("%u", $bytes);
    }
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
  
    $bytes /= pow(1024, $pow);
  
    return round($bytes, $precision) . ' ' . $units[$pow];
} 
//------------------------------------------------------------------------------
function get_file_date( $item) {		// file date
	return @$GLOBALS['ext_File']->filemtime( $item );
}
//------------------------------------------------------------------------------
function parse_file_date($date) {		// parsed file date
	if ($date) {
		return @date($GLOBALS["date_fmt"],$date);
	} else {
		return " (unknown) ";
	}
}
//------------------------------------------------------------------------------
function get_is_image( $abs_item ) {		// is this file an image?
	if(!get_is_file($abs_item)) return false;
	if( isset($abs_item['name'])) {
		$abs_item = $abs_item['name'];
	}
	return @eregi($GLOBALS["images_ext"], $abs_item);
}
//-----------------------------------------------------------------------------
function get_is_editable( $abs_item ) {		// is this file editable?
	if(!get_is_file( $abs_item )) return false;
	
	if( is_array( $abs_item ) ) {
		$abs_item = $abs_item['name'];
	}
	if(preg_match('/'.$GLOBALS["editable_ext"].'/i',$abs_item)) return true;

	return strpos( basename($abs_item), "." ) ? false : true;

}
//-----------------------------------------------------------------------------
function get_mime_type( $abs_item, $query) {	// get file's mimetype

	if(get_is_dir( $abs_item )) {			// directory
		$mime_type	= $GLOBALS["super_mimes"]["dir"][0];
		$image		= $GLOBALS["super_mimes"]["dir"][1];

		if($query=="img") return $image;
		else return $mime_type;
	}
	$extra = $GLOBALS['ext_File']->is_link( $abs_item ) ? ' ('.$GLOBALS['mimes']['symlink'].')' : '';
	if( ext_isFTPMode() && isset($abs_item['name']) ) {
		$abs_item=$abs_item['name'];
	}
				// mime_type
	foreach($GLOBALS["used_mime_types"] as $mime) {
		list($desc,$img,$ext)	= $mime;
		if(@eregi($ext,basename($abs_item) )) {
			$mime_type	= $desc;
			$image		= $img;
			if($query=="img") return $image;
			else return $mime_type . $extra;
		}
	}

	if((function_exists("is_executable") &&
		@is_executable( $abs_item )) ||
		@eregi($GLOBALS["super_mimes"]["exe"][2],$abs_item))
	{						// executable
		$mime_type	= $GLOBALS["super_mimes"]["exe"][0];
		$image		= $GLOBALS["super_mimes"]["exe"][1];
	} else {					// unknown file
		$mime_type	= $GLOBALS["super_mimes"]["file"][0];
		$image		= $GLOBALS["super_mimes"]["file"][1];
	}

	if($query=="img") 
	return $image;
	else 
	return $mime_type . $extra;
}
//------------------------------------------------------------------------------
function get_show_item($dir, $item) {		// show this file?
	if( is_array( $item )) {
		$item = $item['name'];
	}
	if($item == "." || $item == ".." ||
		(substr($item,0,1)=="." && $GLOBALS["show_hidden"]==false)) return false;

	if($GLOBALS["no_access"]!="" && @eregi($GLOBALS["no_access"],$item)) return false;

	if($GLOBALS["show_hidden"]==false) {
		$dirs=explode("/",$dir);
		foreach($dirs as $i) if(substr($i,0,1)==".") return false;
	}

	return true;
}
//------------------------------------------------------------------------------
function get_dir_list( $dir='' ) {
	if( ext_isFTPMode()) {
		$files = getCachedFTPListing(empty($dir) ? '.' : $dir);
	} else {
		$files = extReadDirectory( get_abs_dir( $dir), '.', false, true );
	}

	$dirs =array();
	foreach( $files as $item) {
		$itemname = ext_isFTPMode() ? (empty($dir) ? '' : $dir.'/') .$item['name'] :  $item;

		$itemname = str_replace( '\\', '/', $itemname );
		if( get_is_dir($item)) {
			$index = str_replace( 
						str_replace('\\', '/', $GLOBALS['home_dir'].$GLOBALS['separator']), 
							'', $itemname );

			$dirs[$index]= basename($index);
		}
	}

	return $dirs;
}
/**
 * Returns select lists with all the subdirectories along the current directory path
 *
 * @param string $dir
 * @return string
 */
function get_dir_selects( $dir ) {
	$dirs = explode( "/", str_replace( "\\", '/', $dir ) );

	$subdirs = get_dir_list();
	if( sizeof( $subdirs ) > 0) {
		$subdirs = array_merge(Array('ext_disabled' => '-'), $subdirs );
	}

	if( empty($dirs[0]) ) array_shift($dirs);
	$dirsCopy = $dirs;
	$implode = '';
	$selectedDir = @$dirs[0];
	foreach( $subdirs as $index => $val ) {
		if ($GLOBALS['use_mb']) {
			if (mb_detect_encoding($val) == 'ASCII') {
				$subdirs[$index] = utf8_encode($val);
			} else {
				$subdirs[$index] = $val;
			}
		} else {
			$subdirs[$index] = utf8_encode($val);
		}
	}

	$dir_links = ext_selectList('dirselect1', $selectedDir, $subdirs, 1, '', 'onchange="theDir=this.options[this.selectedIndex].value;if(theDir!=\'ext_disabled\' ) chDir(theDir);"' );
	$i = 2;
	foreach( $dirs as $directory ) {
		if( $directory != "" ) {
			$implode .= $directory;
			$next = next($dirsCopy);
			$subdirs = get_dir_list( $implode );

			foreach( $subdirs as $index => $val ) {
				unset( $subdirs[$index]);
				if ($GLOBALS['use_mb']) {
					if (mb_detect_encoding($index) == 'ASCII') {
						if (mb_detect_encoding($val) == 'ASCII') {
							$subdirs[utf8_encode($index)] = utf8_encode($val);
						} else {
							$subdirs[utf8_encode($index)] = $val;
						}
					} else {
						if (mb_detect_encoding($val) == 'ASCII') {
							$subdirs[$index] = utf8_encode($val);
						} else {
							$subdirs[$index] = $val;
						}
					}
				} else {
					$subdirs[utf8_encode($index)] = utf8_encode($val);
				}
			}
			if( $next !== false ) {
				$selectedDir .= '/'.$next;
			} else {
				if( sizeof( $subdirs ) > 0) {
					$subdirs = array_merge(Array('ext_disabled' => '-'), $subdirs );
				}
			}
			$dir_links .= ' / '.ext_selectList('dirselect'.$i++, $selectedDir, $subdirs, 1, '', 'onchange="theDir=this.options[this.selectedIndex].value;if(theDir!=\'ext_disabled\' ) chDir(theDir);"' );
			$implode .= '/';
		}

	}
	//echo '<pre>'.htmlspecialchars($dir_links).'</pre>';exit;
	return $dir_links;
}
//------------------------------------------------------------------------------
function copy_dir($source,$dest) {		// copy dir
	$ok = true;
	$source = str_replace( '\\', '/', $source );
	$dest = str_replace( '\\', '/', $dest );
	if(!@mkdir($dest,0777)) return false;
	$itemlist = extReadDirectory( $source, '.', true, true );
	if( empty( $itemlist )) return true;

	foreach( $itemlist as $file ) {
		if(($file==".." || $file==".")) continue;
		$file = str_replace( '\\', '/', $file );
		$new_dest = str_replace( $source, $dest, $file );

		if(@is_dir($file)) {
			@mkdir($new_dest,0777);
		} else {
			$ok=@copy($file,$new_dest);
		}
	}

	return $ok;
}

//------------------------------------------------------------------------------
function remove($item) {			// remove file / dir

	if( !is_link( $item )) {
		$item = realpath($item);
	}
	$ok = true;
	if( is_link($item) ||  is_file($item)) 
	$ok =  unlink($item);
	elseif( @is_dir($item)) {

		if(($handle= opendir($item))===false) 
		ext_Result::sendResult('delete', false, basename($item).": ".$GLOBALS["error_msg"]["opendir"]);

		while(($file=readdir($handle))!==false) {
			if(($file==".." || $file==".")) continue;

			$new_item = $item."/".$file;
			if(!file_exists($new_item)) 
			ext_Result::sendResult('delete', false, basename($item).": ".$GLOBALS["error_msg"]["readdir"]);
			//if(!get_show_item($item, $new_item)) continue;

			if( @is_dir($new_item)) {
				$ok=remove($new_item);
			} else {
				$ok= unlink($new_item);
			}
		}

		closedir($handle);
		$ok=@rmdir($item);
	}
	return $ok;
}
function chmod_recursive($item, $mode) {			// chmod file / dir
	$ok = true;

	if(@is_link($item) || @is_file($item)) {
		$ok=@chmod( $item, $mode );
		if($ok) ext_Result::add_message($GLOBALS['messages']['permchange'].' '.$new_item);
		else ext_Result::add_error($GLOBALS['error_msg']['permchange'].' '.$new_item);
	}
	elseif(@is_dir($item)) {
		if(($handle=@opendir($item))===false) {

			ext_Result::add_error(basename($item).": ".$GLOBALS["error_msg"]["opendir"]);
			return false; 
		}

		while(($file=readdir($handle))!==false) {
			if(($file==".." || $file==".")) continue;

			$new_item = $item."/".$file;
			if(!@file_exists($new_item)) {
				ext_Result::add_error(basename($item).": ".$GLOBALS["error_msg"]["readdir"]);
				continue; 
			}
			//if(!get_show_item($item, $new_item)) continue;

			if(@is_dir($new_item)) {
				$ok=chmod_recursive($new_item, $mode);
			} else {
				$ok=@chmod($new_item, $mode);
				if($ok) ext_Result::add_message($GLOBALS['messages']['permchange'].' '.$new_item);
				else ext_Result::add_error($GLOBALS['error_msg']['permchange'].' '.$new_item);
			}
		}
		closedir($handle);
		if( @is_dir( $item )) {
			$bin = decbin( $mode );
			// when we chmod a directory we must care for the permissions
			// to prevent that the directory becomes not readable (when the "execute bits" are removed)
			$bin = substr_replace( $bin, '1', 2, 1 ); // set 1st x bit to 1
			$bin = substr_replace( $bin, '1', 5, 1 );// set  2nd x bit to 1
			$bin = substr_replace( $bin, '1', 8, 1 );// set 3rd x bit to 1
			$mode = bindec( $bin ); 
		}
		$ok=@chmod( $item, $mode );
		if($ok) ext_Result::add_message($GLOBALS['messages']['permchange'].' '.$item);
		else ext_Result::add_error($GLOBALS['error_msg']['permchange'].' '.$item);
	}

	return $ok;
}
//------------------------------------------------------------------------------
function get_max_file_size() {			// get php max_upload_file_size
	return calc_php_setting_bytes( ini_get("upload_max_filesize") );
}
function get_max_upload_limit() {
	return calc_php_setting_bytes( ini_get('post_max_size'));
}

function calc_php_setting_bytes( $value ) {
	if(@eregi("G$",$value)) {
		$value = substr($value,0,-1);
		$value = round($value*1073741824);
	} elseif(@eregi("M$",$value)) {
		$value = substr($value,0,-1);
		$value = round($value*1048576);
	} elseif(@eregi("K$",$value)) {
		$value = substr($value,0,-1);
		$value = round($value*1024);
	}

	return $value;
}
//------------------------------------------------------------------------------
function down_home($abs_dir) {			// dir deeper than home?
	if( ext_isFTPMode() ) {
		return true;
	}
	$real_home = @realpath($GLOBALS["home_dir"]);
	$real_dir = @realpath($abs_dir);

	if($real_home===false || $real_dir===false) {
		if(@eregi("\\.\\.",$abs_dir)) return false;
	} else if(strcmp($real_home,@substr($real_dir,0,strlen($real_home)))) {
		return false;
	}
	return true;
}
//------------------------------------------------------------------------------
function id_browser() {
	$browser=$GLOBALS['__SERVER']['HTTP_USER_AGENT'];

	if(preg_match('/Opera(\/| )([0-9]\.[0-9]{1,2})/', $browser)) {
 		return 'OPERA';
	} else if(preg_match('/MSIE ([0-9]\.[0-9]{1,2})/', $browser)) {
 		return 'IE';
	} else if(preg_match('/OmniWeb\/([0-9]\.[0-9]{1,2})/', $browser)) {
 		return 'OMNIWEB';
	} else if(preg_match('/(Konqueror\/)(.*)/', $browser)) {
 		return 'KONQUEROR';
	} else if(preg_match('/Mozilla\/([0-9]\.[0-9]{1,2})/', $browser)) {
		return 'MOZILLA';
	} else {
		return 'OTHER';
	}
}
function ext_isArchive( $file ) {
  
	$file_info = pathinfo($file);
	$ext = @strtolower($file_info["extension"]);
	$archive_types = array("tar", "gz", "tgz", "zip", "bzip2", "bz2", "tbz", 'rar');
	if( in_array( $ext, $archive_types )) {
		return true;
	}
	return false;
}
if( !extension_loaded('posix') ) {
	function posix_geteuid() {
		return false;
	}
	function posix_getpwnam() {

	}
}

//------------------------------------------------------------------------------
/**
 * Checks if the User Agent String identifies the browser as Internet Explorer
 *
 * @return boolean
 */
function ext_isWindows() {
	if(empty($GLOBALS['isWindows'])) {
		$GLOBALS['isWindows'] = substr(PHP_OS, 0, 3) == 'WIN';
	}
	return $GLOBALS['isWindows'];
}
/**
 * Returns the valid directory separator for this OS & Webserver combination
 *
 * @return string
 */
function ext_getSeparator() {
	if( defined( 'DIRECTORY_SEPARATOR')) {
		return DIRECTORY_SEPARATOR;
	}
	elseif (@preg_match('/Microsoft|WebSTAR|Xitami/', $_SERVER['SERVER_SOFTWARE']) ) {
		return '\\';
	} else {
		return '/';
	}
}
/**
 * Checks if the User Agent String identifies the browser as Internet Explorer
 *
 * @return boolean
 */
function ext_isIE() {
	return (preg_match('/MSIE ([0-9]\.[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT']));
}

/**
 * Prints an HTML dropdown box named $name using $arr to
 * load the drop down.	If $value is in $arr, then $value
 * will be the selected option in the dropdown.
 * @author gday
 * @author soeren
 * 
 * @param string $name The name of the select element
 * @param string $value The pre-selected value
 * @param array $arr The array containting $key and $val
 * @param int $size The size of the select element
 * @param string $multiple use "multiple=\"multiple\" to have a multiple choice select list
 * @param string $extra More attributes when needed
 * @return string HTML drop-down list
 */
function ext_selectList($name, $value, $arr, $size=1, $multiple="", $extra="") {
	$html = '';
	if( !empty( $arr ) ) {
		$html = "<select class=\"inputbox\" name=\"$name\" id=\"$name\" size=\"$size\" $multiple $extra>\n";

		while (list($key, $val) = each($arr)) {
			$selected = "";
			if( is_array( $value )) {
				if( in_array( $key, $value )) {
					$selected = "selected=\"selected\"";
				}
			}
			else {
				if(strtolower($value) == strtolower($key) ) {
					$selected = "selected=\"selected\"";
				}
			}
			if( $val == '-') {
				//$selected .= ' disabled="disabled"';
				$val = '- - - - -';
			}
			$html .= "<option value=\"$key\" $selected>$val";
			$html .= "</option>\n";
		}

		$html .= "</select>\n";
	}
	return $html;
}
function ext_scriptTag( $src = '', $script = '') {
	if( $src!='') {
		return '<script type="text/javascript" src="'.$src.'"></script>';
	}
	if( $script != '') {
		return '<script type="text/javascript">'.$script.'</script>';
	}
}
function ext_alertBox( $msg ) {
	return ext_scriptTag('', 'Ext.Msg.alert( \''.$GLOBALS["error_msg"]['message'].'\', \''. @mysql_escape_string( $msg ) .'\' );' );
}
function ext_successBox( $msg ) {
	return ext_scriptTag('', 'Ext.msgBoxSlider.msg( \''.ext_Lang::msg('success', true ).'\', \''. @mysql_escape_string( $msg ) .'\' );' );
}
function ext_docLocation( $url ) {
	return ext_scriptTag('', 'document.location=\''. $url .'\';' );
}
function ext_isXHR() {
	return strtolower(extGetParam($_SERVER,'HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest'
		|| strtolower(extGetParam($_POST,'requestType')) == 'xmlhttprequest';
}
function ext_exit() {
	global $mainframe;

	if( is_callable( array( $mainframe, 'close' ) ) ) {
		$mainframe->close();
	} else {
		session_write_close();
		exit;
	}
}
function ext_isJoomla( $version='', $operator='=', $compare_minor_versions=true) {
	$this_version = '';
	if( !empty($GLOBALS['_VERSION']) && is_object($GLOBALS['_VERSION'])) {
		$jversion =& $GLOBALS['_VERSION'];
		$this_version = $jversion->RELEASE .'.'. $jversion->DEV_LEVEL;
	}
	elseif ( class_exists('JVersion') ) {
		$jversion = new JVersion();
		$this_version = $jversion->RELEASE .'.'. $jversion->DEV_LEVEL;
	} else {
		return false;
	}
	if( empty( $version ) ) {
		return !empty($this_version);
	}
	$allowed_operators = array( '<', 'lt', '<=', 'le', '>', 'gt', '>=', 'ge', '==', '=', 'eq', '!=', '<>', 'ne' );

	if( $compare_minor_versions ) {
		if( $jversion->RELEASE != substr($version, 0, 3 ) ) {
			return false;
		}
	}
	if( in_array($operator, $allowed_operators )) {
		return version_compare( $this_version, $version, $operator );
	}
	return false;
}
/**
 * Raise the memory limit when it is lower than the needed value
 *
 * @param string $setLimit Example: 16M
 */
function ext_RaiseMemoryLimit( $setLimit ) {

	$memLimit = @ini_get('memory_limit');

	if( stristr( $memLimit, 'k') ) {
		$memLimit = str_replace( 'k', '', str_replace( 'K', '', $memLimit )) * 1024;
	}
	elseif( stristr( $memLimit, 'm') ) {
		$memLimit = str_replace( 'm', '', str_replace( 'M', '', $memLimit )) * 1024 * 1024;
	}

	if( stristr( $setLimit, 'k') ) {
		$setLimitB = str_replace( 'k', '', str_replace( 'K', '', $setLimit )) * 1024;
	}
	elseif( stristr( $setLimit, 'm') ) {
		$setLimitB = str_replace( 'm', '', str_replace( 'M', '', $setLimit )) * 1024 * 1024;
	}

	if( $memLimit < $setLimitB ) {
		@ini_set('memory_limit', $setLimit );
	}
}
/**
 * Reads a file and sends them in chunks to the browser
 * This should overcome memory problems
 * http://www.php.net/manual/en/function.readfile.php#54295
 *
 * @since 1.4.1
 * @param string $filename
 * @param boolean $retbytes
 * @return mixed
 */
function readFileChunked($filename,$retbytes=true) {
	$chunksize = 1*(1024*1024); // how many bytes per chunk
	$buffer = '';
	$cnt =0;
	// $handle = fopen($filename, 'rb');
	$handle = fopen($filename, 'rb');
	if ($handle === false) {
		return false;
	}
	while (!feof($handle)) {
		$buffer = fread($handle, $chunksize);
		echo $buffer;
		sleep(1);
		ob_flush();
		flush();
		if ($retbytes) {
			$cnt += strlen($buffer);
		}
	}
	$status = fclose($handle);
	if ($retbytes && $status) {
		return $cnt; // return num. bytes delivered like readfile() does.
	}
	return $status;
}
//implements file_put_contents function for compatability with PHP < 4.3
if ( ! function_exists('file_put_contents') ) {
	function file_put_contents ( $filename, $filecont ){
		$handle = fopen( $filename, 'w' );
		if ( is_array($filecont) ) {
			$size = 0;
			foreach ( $filecont as $filestring ) {
				fwrite( $handle, $filestring );
				$size += strlen( $filestring );
			}
			fclose($handle);
			return $size;
		} else {
			fwrite( $handle, $filecont );
			fclose($handle);
			return strlen( $filecont );
		}
	}
}
if ( ! function_exists('scandir') ) {
function scandir($dir,$listDirectories=false, $skipDots=true) {
	$dirArray = array();
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if (($file != "." && $file != "..") || $skipDots == true) {
				if($listDirectories == false) { if(@is_dir($file)) { continue; } }
				array_push($dirArray,basename($file));
			}
		}
		closedir($handle);
	}
	return $dirArray;
}
}

/**
 * Page generation time
 * @package Joomla
 */
class extProfiler {
	/** @var int Start time stamp */
	var $start=0;
	/** @var string A prefix for mark messages */
	var $prefix='';

	/**
	* Constructor
	* @param string A prefix for mark messages
	*/
	function extProfiler( $prefix='' ) {
		$this->start = $this->getmicrotime();
		$this->prefix = $prefix;
	}

	/**
	* @return string A format message of the elapsed time
	*/
	function mark( $label ) {
		return sprintf ( "\n<div class=\"profiler\">$this->prefix %.3f $label</div>", $this->getmicrotime() - $this->start );
	}

	/**
	* @return float The current time in milliseconds
	*/
	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
}
/**
* Utility class for all HTML drawing classes
* @package eXtplorer
*/
class extHTML {
	function loadExtJS() {
		$scriptTag = '
		<script type="text/javascript" src="'. _EXT_URL . '/fetchscript.php?'
			.'&amp;subdir[]=scripts/editarea/&amp;file[]=edit_area_full_with_plugins.js'
			.'&amp;subdir[]=scripts/extjs3/adapter/ext/&amp;file[]=ext-base.js'
			.'&amp;subdir[]=scripts/extjs3/&amp;file[]=ext-all.js'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.ondemandload/&amp;file[]=scriptloader.js'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.editareaadapater/&amp;file[]=ext-editarea-adapter.js'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.statusbar/&amp;file[]=ext-statusbar.js'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.fileuploadfield/&amp;file[]=ext-fileUploadField.js'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.locationbar/&amp;file[]=Ext.ux.LocationBar.js'
			.'&amp;gzip=1"></script>
		<script type="text/javascript" src="'. $GLOBALS['script_name'].'?option=com_extplorer&amp;action=include_javascript&amp;file=functions.js"></script>
		<script type="text/javascript" >editAreaLoader.baseURL = "'. _EXT_URL .'/scripts/editarea/";</script>
		<link rel="stylesheet" href="'. _EXT_URL . '/fetchscript.php?'
			.'subdir[]=scripts/extjs3/resources/css/&amp;file[]=ext-all.css'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.locationbar/&amp;file[]=LocationBar.css'
			.'&amp;subdir[]=scripts/extjs3-ext/ux.fileuploadfield/&amp;file[]=fileuploadfield.css'
			.'&amp;gzip=1" />';

	
		if (defined('EXT_STANDALONE')) {
			$GLOBALS['mainframe']->addcustomheadtag($scriptTag);
		} else {
			echo $scriptTag;
		}
	}
	
	function makeOption( $value, $text='', $value_name='value', $text_name='text' ) {
		$obj = new stdClass;
		$obj->$value_name = $value;
		$obj->$text_name = trim( $text ) ? $text : $value;
		return $obj;
	}

  function writableCell( $folder, $relative=1, $text='', $visible=1 ) {
	$writeable 		= '<b><font color="green">Writeable</font></b>';
	$unwriteable 	= '<b><font color="red">Unwriteable</font></b>';

  	echo '<tr>';
  	echo '<td class="item">';
	echo $text;
	if ( $visible ) {
		echo $folder . '/';
	}
	echo '</td>';
  	echo '<td align="left">';
	if ( $relative ) {
		echo is_writable( "../$folder" ) 	? $writeable : $unwriteable;
	} else {
		echo is_writable( "$folder" ) 		? $writeable : $unwriteable;
	}
	echo '</td>';
  	echo '</tr>';
  }

	/**
	* Generates an HTML select list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function selectList( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL ) {
		// check if array
		if ( is_array( $arr ) ) {
			reset( $arr );
		}

		$html 	= "\n<select name=\"$tag_name\" $tag_attribs>";
		$count 	= count( $arr );

		for ($i=0, $n=$count; $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " selected=\"selected\"" : '');
			}
			$html .= "\n\t<option value=\"".$k."\"$extra>" . $t . "</option>";
		}
		$html .= "\n</select>\n";

		return $html;
	}

	/**
	* Writes a select list of integers
	* @param int The start integer
	* @param int The end integer
	* @param int The increment
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The printf format to be applied to the number
	* @returns string HTML for the select list
	*/
	function integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format="" ) {
		$start 	= intval( $start );
		$end 	= intval( $end );
		$inc 	= intval( $inc );
		$arr 	= array();

		for ($i=$start; $i <= $end; $i+=$inc) {
			$fi = $format ? sprintf( "$format", $i ) : "$i";
			$arr[] = extHTML::makeOption( $fi, $fi );
		}

		return extHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Writes a select list of month names based on Language settings
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function monthSelectList( $tag_name, $tag_attribs, $selected ) {
		$arr = array(
			extHTML::makeOption( '01', _JAN ),
			extHTML::makeOption( '02', _FEB ),
			extHTML::makeOption( '03', _MAR ),
			extHTML::makeOption( '04', _APR ),
			extHTML::makeOption( '05', _MAY ),
			extHTML::makeOption( '06', _JUN ),
			extHTML::makeOption( '07', _JUL ),
			extHTML::makeOption( '08', _AUG ),
			extHTML::makeOption( '09', _SEP ),
			extHTML::makeOption( '10', _OCT ),
			extHTML::makeOption( '11', _NOV ),
			extHTML::makeOption( '12', _DEC )
		);

		return extHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Writes a yes/no select list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function yesnoSelectList( $tag_name, $tag_attribs, $selected, $yes=_CMN_YES, $no=_CMN_NO ) {
		$arr = array(
		extHTML::makeOption( '0', $no ),
		extHTML::makeOption( '1', $yes ),
		);

		return extHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Generates an HTML radio list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radioList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );
		$html = "";
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = ( isset($arr[$i]->id) ? @$arr[$i]->id : null);

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"".$k."\"$extra $tag_attribs />";
			$html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
		}
		$html .= "\n";

		return $html;
	}

	/**
	* Writes a yes/no radio list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes=_CMN_YES, $no=_CMN_NO ) {
		$arr = array(
			extHTML::makeOption( '0', $no ),
			extHTML::makeOption( '1', $yes )
		);

		return extHTML::radioList( $arr, $tag_name, $tag_attribs, $selected );
	}

	/**
	* Cleans text of all formating and scripting code
	*/
	function cleanText ( &$text ) {
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
		$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
		$text = preg_replace( '/<!--.+?-->/', '', $text );
		$text = preg_replace( '/{.+?}/', '', $text );
		$text = preg_replace( '/&nbsp;/', ' ', $text );
		$text = preg_replace( '/&amp;/', ' ', $text );
		$text = preg_replace( '/&quot;/', ' ', $text );
		$text = strip_tags( $text );
		$text = htmlspecialchars( $text );

		return $text;
	}
}
/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _MOS_NOTRIM prevents trim, _MOS_ALLOWHTML allows safe html, _MOS_ALLOWRAW allows raw input
 */
define( "_ext_NOTRIM", 0x0001 );
define( "_ext_ALLOWHTML", 0x0002 );
define( "_ext_ALLOWRAW", 0x0004 );
function extGetParam( &$arr, $name, $def=null, $mask=0 ) {
	static $noHtmlFilter 	= null;
	static $safeHtmlFilter 	= null;

	$return = null;
	if (isset( $arr[$name] )) {
		$return = $arr[$name];

		if (is_string( $return )) {
			// trim data
			if (!($mask&_ext_NOTRIM)) {
				$return = trim( $return );
			}

			if ($mask&_ext_ALLOWRAW) {
				// do nothing
			} else if ($mask&_ext_ALLOWHTML) {
				// do nothing - compatibility mode
			} else {
				// send to inputfilter
				if (is_null( $noHtmlFilter )) {
					$noHtmlFilter = new InputFilter( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */ );
				}
				$return = $noHtmlFilter->process( $return );

				if (empty($return) && is_numeric($def)) {
				// if value is defined and default value is numeric set variable type to integer
					$return = intval($return);
				}
			}

			// account for magic quotes setting
			if (!get_magic_quotes_gpc()) {
				$return = stripslashes( $return );
			}
		}

		return $return;
	} else {
		return $def;
	}
}

/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function extStripslashes( &$value ) {
	$ret = '';
	if (is_string( $value )) {
		$ret = stripslashes( $value );
	} else {
		if (is_array( $value )) {
			$ret = array();
			foreach ($value as $key => $val) {
				$ret[$key] = extStripslashes( $val );
			}
		} else {
			$ret = $value;
		}
	}
	return $ret;
}
/**
 * Recursively creates a new directory
 *
 * @param unknown_type $path
 * @param unknown_type $rights
 * @return unknown
 */
function extMkdirR($path, $rights = 0777) {

	$folder_path = array(
	strstr($path, '.') ? dirname($path) : $path);

	while(!@is_dir(dirname(end($folder_path)))
		&& dirname(end($folder_path)) != '/'
		&& dirname(end($folder_path)) != '.'
		&& dirname(end($folder_path)) != '') {
		array_push($folder_path, dirname(end($folder_path)));
	}

	while($parent_folder_path = array_pop($folder_path)) {
		@mkdir($parent_folder_path, $rights);
	}
	@mkdir( $path );
	return is_dir( $path );
}
/**
* Utility function to read the files in a directory
* @param string The file system path
* @param string A filter for the names
* @param boolean Recurse search into sub-directories
* @param boolean True if to prepend the full path to the file name
*/
function extReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false	) {
	$arr = array();
	if (!@is_dir( $path )) {
		return $arr;
	}
	$handle = opendir( $path );

	while ($file = readdir($handle)) {
		if( is_array( $file )) $file = $file['name'];
		$dir = extPathName( $path.'/'.$file, false );
		$isDir = @is_dir( $dir );
		if (($file != ".") && ($file != "..")) {
			if (preg_match( "/$filter/", $file )) {
				if ($fullpath) {
					$arr[] = trim( extPathName( $path.'/'.$file, false ) );
				} else {
					$arr[] = trim( $file );
				}
			}
			if ($recurse && $isDir) {
				$arr2 = extReadDirectory( $dir, $filter, $recurse, $fullpath );
				$arr = array_merge( $arr, $arr2 );
			}
		}
	}
	closedir($handle);
	asort($arr);
	return $arr;
}
/**
* Function to strip additional / or \ in a path name
* @param string The path
* @param boolean Add trailing slash
*/
function extPathName($p_path,$p_addtrailingslash = false) {
	$retval = "";

	$isWin = (substr(PHP_OS, 0, 3) == 'WIN');

	if ($isWin)	{
		$retval = str_replace( '/', '\\', $p_path );
		if ($p_addtrailingslash) {
			if (substr( $retval, -1 ) != '\\') {
				$retval .= '\\';
			}
		}

		// Check if UNC path
		$unc = substr($retval,0,2) == '\\\\' ? 1 : 0;

		// Remove double \\
		$retval = str_replace( '\\\\', '\\', $retval );

		// If UNC path, we have to add one \ in front or everything breaks!
		if ( $unc == 1 ) {
			$retval = '\\'.$retval;
		}
	} else {
		$retval = str_replace( '\\', '/', $p_path );
		if ($p_addtrailingslash) {
			if (substr( $retval, -1 ) != '/') {
				$retval .= '/';
			}
		}

		// Check if UNC path
		$unc = substr($retval,0,2) == '//' ? 1 : 0;

		// Remove double //
		$retval = str_replace('//','/',$retval);

		// If UNC path, we have to add one / in front or everything breaks!
		if ( $unc == 1 ) {
			$retval = '/'.$retval;
		}
	}

	return $retval;
}
/**
* Utility function redirect the browser location to another url
*
* Can optionally provide a message.
* @param string The file system path
* @param string A filter for the names
*/
function extRedirect( $url, $msg='' ) {

   global $mainframe;

	// specific filters
	$iFilter = new InputFilter();
	$url = $iFilter->process( $url );
	if (!empty($msg)) {
		$msg = $iFilter->process( $msg );
	}

	if ($iFilter->badAttributeValue( array( 'href', $url ))) {
		$url = $GLOBALS['home_dir'];
	}

	if (trim( $msg )) {
		if (strpos( $url, '?' )) {
			$url .= '&extmsg=' . urlencode( $msg );
		} else {
			$url .= '?extmsg=' . urlencode( $msg );
		}
	}

	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ". $url );
	}
	exit();
}
/**
* Random password generator
* @return password
*/
function extMakePassword($length=8) {
	$salt 		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$makepass	= '';
	mt_srand(10000000*(double)microtime());
	for ($i = 0; $i < $length; $i++)
		$makepass .= $salt[mt_rand(0,61)];
	return $makepass;
}
/**
 * Wrapper Function to encode passwords (maybe sometimes we don't use md5 anymore?)
 *
 * @param string $pass
 * @return string
 */
function extEncodePassword( $pass ) {
	return md5( $pass );
}

if (!function_exists('html_entity_decode')) {
	/**
	* html_entity_decode function for backward compatability in PHP
	* @param string
	* @param string
	*/
	function html_entity_decode ($string, $opt = ENT_COMPAT) {

		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);

		if ($opt & 1) { // Translating single quotes
			// Add single quote to translation table;
			// doesn't appear to be there by default
			$trans_tbl["&apos;"] = "'";
		}

		if (!($opt & 2)) { // Not translating double quotes
			// Remove double quote from translation table
			unset($trans_tbl["&quot;"]);
		}

		return strtr ($string, $trans_tbl);
	}
}
//------------------------------------------------------------------------------
function logout() {
	session_destroy();
	session_write_close();
	header("Location: ".$GLOBALS["script_name"]);
}
//------------------------------------------------------------------------------
/**
 * Returns an IP- and BrowserID- based Session ID
 *
 * @param string $id
 * @return string
 */
function get_session_id( $id=null ) {
	return extMakePassword( 32 );
}
