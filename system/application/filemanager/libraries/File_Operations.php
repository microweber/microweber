<?php
/** ensure this file is being included by a parent file */
if (!defined('_JEXEC') && !defined('_VALID_MOS')) die('Restricted access');
/**
 * This file allows to dynamically switch between file.system based mode and FTP based mode
 */

require_once(dirname(__FILE__).'/FTP.php');
if (!extension_loaded('ftp')) {
	require_once(dirname(__FILE__).'/FTP/Socket.php');
}

function ext_isFTPMode() {
	return $GLOBALS['file_mode'] == 'ftp' || $GLOBALS['file_mode'] == 'ssh2';
}

/**
 * This class is a wrapper for all of the needed filesystem functions.
 * It allows us to use the same function name for FTP and File System Mode
 *
 */
class ext_File {
	function chmod($item, $mode) {
		if (ext_isFTPMode()) {
			if (!empty($item['name'])) {
				$item = $item['name'];
			}
			return $GLOBALS['FTPCONNECTION']->chmod($item, $mode);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($item) == 'ASCII'){
					return @chmod(utf8_decode($item), $mode);
				} else {
					return @chmod($item, $mode);
				}
			} else {
				return @chmod(utf8_decode($item), $mode);
			}
		}
	}


	function chmodRecursive($item, $mode) {
		if (ext_isFTPMode()) {
			return $GLOBALS['FTPCONNECTION']->chmodRecursive($item, $mode);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($item) == 'ASCII'){
					return chmod_recursive(utf8_decode($item), $mode);
				} else {
					return chmod_recursive($item, $mode);
				}
			} else {
				return chmod_recursive(utf8_decode($item), $mode);
			}
		}
	}


	function copy($from, $to) {
		if (ext_isFTPMode()) {

			$local_file = ext_ftp_make_local_copy( $from );
			$res = $GLOBALS['FTPCONNECTION']->put($local_file, $to);
			unlink($local_file);
			return $res;
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($from) == 'ASCII'){
					if (mb_detect_encoding($to) == 'ASCII'){
						return copy(utf8_decode($from), utf8_decode($to));
					} else {
						return copy(utf8_decode($from), $to);
					}
				} else {
					if (mb_detect_encoding($to) == 'ASCII'){
						return copy($from, utf8_decode($to));
					} else {
						return copy($from, $to);
					}
				}
			} else {
				return copy(utf8_decode($from), utf8_decode($to));
			}
		}
	}


	function copy_dir($abs_item, $abs_new_item) {
		if (ext_isFTPMode()) {
			$tmp_dir = ext_ftp_make_local_copy($abs_item);
			$res = $GLOBALS['FTPCONNECTION']->putRecursive($tmp_dir, $abs_new_item);
			remove($tmp_dir);
			return $res;
		} else {
			return copy_dir($abs_item,$abs_new_item);
		}
	}


	function mkdir($dir, $perms) {
		if (ext_isFTPMode()) {
			$res = $GLOBALS['FTPCONNECTION']->mkdir($dir);
			return $res;
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($dir) == 'ASCII'){
					return mkdir(utf8_decode($dir), $perms);
				} else {
					return mkdir($dir, $perms);
				}
			} else {
				return mkdir(utf8_decode($dir), $perms);
			}
		}
	}


	function mkfile($file) {
		if (ext_isFTPMode()) {
			$tmp_file = tempnam(_EXT_FTPTMP_PATH, 'ext_ftp_dl_');
	
			if ($tmp_file == 'false') {
				ext_Result::sendResult('list', false, 'The /ftp_tmp Directory must be writable in order to use this functionality in FTP Mode.');
			}
			return $GLOBALS['FTPCONNECTION']->put($tmp_file, $file);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($file) == 'ASCII'){
					return @touch(utf8_decode($file));
				} else {
					return @touch($file);
				}
			} else {
				return @touch(utf8_decode($file));
			}
		}
	}


	function unlink($item) {
		if (ext_isFTPMode()) {
			return $GLOBALS['FTPCONNECTION']->rm($item);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($item) == 'ASCII'){
					return unlink(utf8_decode($item));
				} else {
					return unlink($item);
				}
			} else {
				return unlink(utf8_decode($item));
			}
		}
	}


	function rmdir($dir) {
		if (ext_isFTPMode()) {
			return $GLOBALS['FTPCONNECTION']->rm($item);
		} else {
			return rmdir($dir);
		}
	}


	function remove($item) {
		if (ext_isFTPMode()) {
			return $GLOBALS['FTPCONNECTION']->rm($item, true);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($item) == 'ASCII'){
					return remove(utf8_decode($item));
				} else {
					return remove($item);
				}
			} else {
				return remove(utf8_decode($item));
			}		}
	}


	function rename($oldname, $newname) {
		if (ext_isFTPMode()) {
			if (is_array($oldname)) {
				$oldname = $oldname['name'];
			}
			$oldname = str_replace('\\', '/', $oldname);
			$newname = str_replace('\\', '/', $newname);
			return $GLOBALS['FTPCONNECTION']->rename($oldname, $newname);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($oldname) == 'ASCII'){
					if (mb_detect_encoding($newname) == 'ASCII'){
						return rename(utf8_decode($oldname), utf8_decode($newname));
					} else {
						return rename(utf8_decode($oldname), $newname);
					}
				} else {
					if (mb_detect_encoding($newname) == 'ASCII'){
						return rename($oldname, utf8_decode($newname));
					} else {
						return rename($oldname, $newname);
					}
				}
			} else {
				return rename(utf8_decode($oldname), utf8_decode($newname));
			}
		}
	}


	function opendir($dir) {
		if (ext_isFTPMode()) {
			return getCachedFTPListing($dir);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($dir) == 'ASCII') {
					return opendir(utf8_decode($dir));
				} else {
					return opendir($dir);
				}
			} else {
				return opendir(utf8_decode($dir));
			}
		}
	}


	function readdir(&$handle) {
		if (ext_isFTPMode()) {
			$current = current($handle);next($handle);
			return $current;
		} else {
			return readdir($handle);
		}
	}


	function scandir($dir) {
		if (ext_isFTPMode()) {
			return getCachedFTPListing($dir);
		} else {
			return scandir($dir);
		}
	}


	function closedir(&$handle) {
		if (ext_isFTPMode()) {
			return;
		} else {
			return closedir($handle);
		}
	}


	function file_exists($file) {
		if (ext_isFTPMode()) {
			if ($file == '/') return true; // The root directory always exists

			$dir = $GLOBALS['FTPCONNECTION']->pwd();

			if (!is_array($file)) {
				$dir = dirname($file);
				$file = array('name' => basename($file));
			}

			$list = getCachedFTPListing($dir);

			if (is_array($list)) {
				foreach($list as $item) {
					if ($item['name'] == $file['name'])
						return true;
				}
			}
			return false;

		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($file) == 'ASCII') {
					return file_exists(utf8_decode($file));
				} else {
					return file_exists($file);
				}
			} else {
				return file_exists(utf8_decode($file));
			}
		}
	}


	function filesize($file) {
		if (ext_isFTPMode()) {
			if (isset($file['size'])) {
				return ($file['size']);
			}
			return $GLOBALS['FTPCONNECTION']->size($file);
		} else {
			return filesize($file);
		}
	}


	function fileperms($file) {
		if (ext_isFTPMode() && !isset($file['mode'])) {
			if (isset($file['rights'])) {
				$perms = $file['rights'];
			} else {
				$info = get_item_info(dirname($file), basename($file));
				$perms = $info['rights'];
			}
			return decoct(bindec(decode_ftp_rights($perms)));
		} else {
			return @fileperms(is_array($file) ? $file['mode'] : $file);
		}
	}


	function filemtime($file) {
		if (ext_isFTPMode()) {
			if (isset($file['stamp'])) {
				return $file['stamp'];
			}
			if (isset($file['mtime'])) {
				return $file['mtime'];
			}
			$res = $GLOBALS['FTPCONNECTION']->mdtm($file['name']);
			if (!PEAR::isError($res)) {
				return $res;
			}

		}else {
			return filemtime($file);
		}
	}


	function move_uploaded_file($uploadedfile, $to) {
		if (ext_isFTPMode()) {
			if (is_array($uploadedfile)) {
				$uploadedfile = $uploadedfile['name'];
			}

			$uploadedfile = str_replace("\\", '/', $uploadedfile);
			$to = str_replace("\\", '/', $to);
			$res = $GLOBALS['FTPCONNECTION']->put($uploadedfile, $to);
			return $res;
		} else {
			return move_uploaded_file($uploadedfile, $to);
		}
	}


	function file_get_contents($file) {
		if ($GLOBALS['file_mode'] == 'ftp') {
			$tmp_file = tempnam(_EXT_FTPTMP_PATH, 'ext_ftp_dl_');
	
			if ($tmp_file == 'false') {
				ext_Result::sendResult('list', false, 'The /ftp_tmp Directory must be writable in order to use this functionality in FTP Mode.');
			}

			$file = str_replace("\\", '/', $file);
			if ($file[0] != '/') $file = '/'. $file; 
			$res = $GLOBALS['FTPCONNECTION']->get($file, $tmp_file);

			if (PEAR::isError($res)) {
				return false;
			} else {
				$contents = file_get_contents($tmp_file);
				unlink($tmp_file);
				return $contents;
			}
		} elseif( $GLOBALS['file_mode'] == 'ssh2' ) {
			return $GLOBALS['FTPCONNECTION']->file_get_contents($file);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($file) == 'ASCII') {
					return file_get_contents(utf8_decode($file));
				} else {
					return file_get_contents($file);
				}
			} else {
				return file_get_contents(utf8_decode($file));
			}
		}
	}


	function file_put_contents($file, $data) {
		if ($GLOBALS['file_mode'] == 'ftp') {
			$tmp_file = tempnam(_EXT_FTPTMP_PATH, 'ext_ftp_dl_');
	
			if ($tmp_file == 'false') {
				ext_Result::sendResult('list', false, 'The /ftp_tmp Directory must be writable in order to use this functionality in FTP Mode.');
			}
			file_put_contents($tmp_file, $data);
			
			$res = $GLOBALS['FTPCONNECTION']->put($tmp_file, $file, true);

			unlink($tmp_file);
			return $res;
		} elseif( $GLOBALS['file_mode'] == 'ssh2' ) {
			return $GLOBALS['FTPCONNECTION']->file_put_contents($file, $data);
		} else {
			if ($GLOBALS['use_mb']) {
				if (mb_detect_encoding($file) == 'ASCII') {
					return file_put_contents(utf8_decode($file), $data);
				} else {
					return file_put_contents($file, $data);
				}
			} else {
				return file_put_contents(utf8_decode($file), $data);
			}
		}
	}


	function fileowner($file) {
		if (ext_isFTPMode()) {
			$info = posix_getpwnam($file['user']);
			return $info['uid'];
		} else {
			return fileowner($file);
		}
	}


	function geteuid() {
		if (ext_isFTPMode()) {
			$info = posix_getpwnam($_SESSION['ftp_login']);
			return $info['uid'];
		} else {
			return posix_geteuid();
		}
	}


	function is_link($abs_item) {
		if (ext_isFTPMode()) {
			return false;
		} else {
			return is_link($abs_item);
		}
	}


	function is_writable($file) {
		global $isWindows;
		if (ext_isFTPMode()) {

			if ($isWindows) return true;

			if (!is_array($file)) {
				$file = get_item_info(dirname($file), basename($file));
			}

			if (empty($file['rights'])) return true;
			$perms = $file['rights'];

			if ($_SESSION['ftp_login'] == $file['user']) {
				// FTP user is owner of the file
				return $perms[1] == 'w';
			}

			$fileinfo = posix_getpwnam($file['user']);
			$userinfo = posix_getpwnam($_SESSION['ftp_login']);

			if ($fileinfo['gid'] == $userinfo['gid']) {
				return $perms[4] == 'w';
			} else {
				return $perms[7] == 'w';
			}

		} else {
			return is_writable($file);
		}
	}


	function is_readable($file) {
		if (ext_isFTPMode()) {
			$perms = $file['rights'];
			if ($_SESSION['ftp_login'] == $file['user']) {
				// FTP user is owner of the file
				return $perms[0] == 'r';
			}
			$fileinfo = posix_getpwnam($file['user']);
			$userinfo = posix_getpwnam($_SESSION['ftp_login']);

			if ($fileinfo['gid'] == $userinfo['gid']) {
				return $perms[3] == 'r';
			}
			else {
				return $perms[6] == 'r';
			}
			
		} else {
			return is_readable($file);
		}
	}


	/**
	 * determines if a file is deletable based on directory ownership, permissions,
	 * and php safemode.
	 * 
	 * @param string $dir The full path to the file
	 * @return boolean
	 */
	function is_deletable($file) {
		global $isWindows;

		// Note that if the directory is not owned by the same uid as this executing script, it will
		// be unreadable and I think unwriteable in safemode regardless of directory permissions.
		if (ini_get('safe_mode') == 1 && @$GLOBALS['ext_File']->geteuid() != $GLOBALS['ext_File']->fileowner($file)) {
			return false;
		}

		// if dir owner not same as effective uid of this process, then perms must be full 777.
		// No other perms combo seems reliable across system implementations
		if (!$isWindows && @$GLOBALS['ext_File']->geteuid() !== @$GLOBALS['ext_File']->fileowner($file)) {
			return (substr(decoct(@fileperms($file)),-3) == '777' || @is_writable(dirname($file)));
		}

		if ($isWindows && $GLOBALS['ext_File']->geteuid() != $GLOBALS['ext_File']->fileowner($file)) {
			return (substr(decoct(fileperms($file)),-3) == '777');
		}

		// otherwise if this process owns the directory, we can chmod it ourselves to delete it
		return @is_writable(dirname($file));
	}



	function is_chmodable($file) {
		global $isWindows;

		if ($isWindows) {
			return true;
		}

		if (ext_isFTPMode()) {
			return $_SESSION['ftp_login'] == $file['user'];
		} else {
			return @$GLOBALS['ext_File']->fileowner($file) == @$GLOBALS['ext_File']->geteuid();
		}

	}
}

function ext_ftp_make_local_copy( $abs_item ) {

	if (get_is_dir($abs_item)) {
		$tmp_dir = _EXT_FTPTMP_PATH.'/'.uniqid('ext_tmpdir_').'/';
		$res = $GLOBALS['FTPCONNECTION']->getRecursive($abs_item, $tmp_dir, true);
		if (PEAR::isError($res)) {
			ext_Result::sendResult('list', false, 'Failed to fetch the directory via FTP: '.$res->getMessage());
		}
		return $tmp_dir;
	}

	$abs_item = str_replace("\\", '/', $abs_item);
	if ($abs_item[0] != '/') $abs_item = '/'. $abs_item; 

	$tmp_file = tempnam(_EXT_FTPTMP_PATH, 'ext_ftp_dl_');

	if ($tmp_file == 'false') {
		ext_Result::sendResult('list', false, 'The /ftp_tmp Directory must be writable in order to use this functionality in FTP Mode.');
	}

	$res = $GLOBALS['FTPCONNECTION']->get($abs_item, $tmp_file, true);
	if (PEAR::isError($res)) {
		ext_Result::sendResult('list', false, 'Failed to fetch the file via filehandle from FTP: '.$res->getMessage());
	}	

	return $tmp_file;

}

function &getCachedFTPListing($dir, $force_refresh=false) {
	if ($dir == '\\') $dir = '.';
	$dir = str_replace('\\', '/', $dir);

	$dir = str_replace($GLOBALS['home_dir'], '', $dir);
	if ($dir != '' && $dir[0] != '/') {
		$dir = '/'.$dir;
	}
	if( !@is_object($GLOBALS['FTPCONNECTION'])) {
		$return = array('');
		return $return;
	}
	if (empty($GLOBALS['ftp_ls'][$dir]) || $force_refresh) {

		if ($dir == $GLOBALS['FTPCONNECTION']->pwd()) {
			$dir = '';
		}

		$GLOBALS['ftp_ls'][$dir] = $GLOBALS['FTPCONNECTION']->ls(empty($dir) ? '.' : $dir);

		if (PEAR::isError($GLOBALS['ftp_ls'][$dir])) {
			//ext_Result::sendResult('list', false, $GLOBALS['ftp_ls'][$dir]->getMessage().': '.$dir);
		}
	}

	return $GLOBALS['ftp_ls'][$dir];
}
?>