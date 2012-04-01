<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: transfer.php 143 2009-04-01 18:48:16Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
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
 * Transfer file(s) from another server to this one
 *
 */

class ext_Transfer extends ext_Action {
	var $_downloadMethods;

	function execAction( $dir ) {

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('upload', false, $GLOBALS["error_msg"]["accessfunc"]);
		}
		$this->_downloadMethods = array(
			new CurlDownloader(),
			new WgetDownloader(),
			new FopenDownloader(),
			new FsockopenDownloader()
		);
		//DEBUG ext_Result::sendResult('transfer', false, $dir );
		// Execute
		if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {

			$cnt=count($GLOBALS['__POST']['userfile']);
			$err=false;
			foreach($this->_downloadMethods as $method ) {
				if( $method->isSupported()) {
					$downloader =& $method;
					break;
				}
			}
			// upload files & check for errors
			for($i=0;$i<$cnt;$i++) {
				$errors[$i]=NULL;

				$items[$i] = stripslashes(basename($GLOBALS['__POST']['userfile'][$i]));

				$abs = get_abs_item($dir,$items[$i]);

				if($items[$i]=="") continue;
				if(@file_exists($abs) && empty( $_REQUEST['overwrite_files'])) {
					$errors[$i]=$GLOBALS["error_msg"]["itemdoesexist"];
					$err=true;	continue;
				}

				// Upload
				$ok = $downloader->download($GLOBALS['__POST']['userfile'][$i], $abs);
				if($ok===true ) {
					$mode = ext_isFTPMode() ? 644 : 0644;
					@$GLOBALS['ext_File']->chmod( $abs, $mode );
				}
				else {
					$errors[$i]=$ok;
					$err=true;	continue;
				}
			}

			if($err) {			// there were errors
				$err_msg="";
				for($i=0;$i<$cnt;$i++) {
					if($errors[$i]==NULL) continue;
					$err_msg .= $items[$i]." : ".$errors[$i]."\n";
				}
				ext_Result::sendResult('transfer', false, $err_msg);
			}

			ext_Result::sendResult('transfer', true, ext_Lang::msg('transfer_completed'));
			return;
		}
	}
}
/**
 * Abstract DownloadMethod Class for implementing special types
 * @abstract 
 *
 */
class DownloadMethod {
	function download($url, $outputFile) {
		return false;
	}

	function isSupported() {
		return false;
	}

	function getName() {
		return '';
	}
}

class WgetDownloader extends DownloadMethod {
	function download($url, $outputFile) {
		$status = 0;
		$output = array();
		$wget = getBinaryPath('wget');
		exec("$wget -O$outputFile $url ", $output, $status);
		if ($status) {
			$msg = 'exec returned an error status ';
			$msg .= is_array($output) ? implode('<br>', $output) : '';
			return $msg;
		}
		return true;
	}

	function isSupported() {
		return isBinaryAvailable('wget');
	}

	function getName() {
		return 'Download with Wget';
	}
}

class FopenDownloader extends DownloadMethod {
	function download($url, $outputFile) {
		if (!is_writable( dirname( $outputFile ))) {
			return 'Unable to write to current working directory';
		}
		$start =time();

		@set_time_limit( 900 );

		$fh = @fopen($url, 'rb');
		if (empty($fh)) {
			return 'Unable to open url';
		}
		$ofh = @fopen($outputFile, 'wb');
		if (!$ofh) {
			fclose($fh);
			return 'Unable to open output file in writing mode';
		}

		$failed = $results = false;
		while (!feof($fh) && !$failed) {
			$buf = fread($fh, 4096);
			if (!$buf) {
				$results = 'Error during download';
				$failed = true;
				break;
			}
			if (fwrite($ofh, $buf) != strlen($buf)) {
				$failed = true;
				$results = 'Error during writing';
				break;
			}
			if (time() - $start > 55) {

				$start = time();
			}
		}
		fclose($ofh);
		fclose($fh);
		if ($failed) {
			return $results;
		}

		return true;
	}

	function isSupported() {
		$actual = ini_get('allow_url_fopen');
		if (in_array($actual, array(1, 'On', 'on')) && isPhpFunctionSupported('fopen')) {
			return true;
		}

		return false;
	}

	function getName() {
		return 'Download with PHP fopen()';
	}
}

class FsockopenDownloader extends DownloadMethod {
	function download($url, $outputFile, $maxRedirects=10) {
		/* Code from WebHelper_simple.class */

		if ($maxRedirects < 0) {
			return "Error too many redirects. Last URL: $url";
		}

		$components = parse_url($url);
		$port = empty($components['port']) ? 80 : $components['port'];

		$errno = $errstr = null;
		$fd = @fsockopen($components['host'], $port, $errno, $errstr, 2);
		if (empty($fd)) {
			return "Error $errno: '$errstr' retrieving $url";
		}

		$get = $components['path'];
		if (!empty($components['query'])) {
			$get .= '?' . $components['query'];
		}

		$start = time();

		/* Read the web file into a buffer */
		$ok = fwrite($fd, sprintf("GET %s HTTP/1.0\r\n" .
		"Host: %s\r\n" .
		"\r\n",
		$get,
		$components['host']));
		if (!$ok) {
			return 'Download request failed (fwrite)';
		}
		$ok = fflush($fd);
		if (!$ok) {
			return 'Download request failed (fflush)';
		}

		/*
		* Read the response code. fgets stops after newlines.
		* The first line contains only the status code (200, 404, etc.).
		*/
		$headers = array();
		$response = trim(fgets($fd, 4096));

		/* Jump over the headers but follow redirects */
		while (!feof($fd)) {
			$line = trim(fgets($fd, 4096));
			if (empty($line)) {
				break;
			}

			/* Normalize the line endings */
			$line = str_replace("\r", '', $line);
			list ($key, $value) = explode(':', $line, 2);
			if (trim($key) == 'Location') {
				fclose($fd);
				return $this->download(trim($value), $outputFile, --$maxRedirects);
			}
		}

		$success = false;
		$ofd = fopen($outputFile, 'wb');
		if ($ofd) {
			/* Read the body */
			$failed = false;
			while (!feof($fd) && !$failed) {
				$buf = fread($fd, 4096);
				if (fwrite($ofd, $buf) != strlen($buf)) {
					$failed = true;
					break;
				}
				if (time() - $start > 55) {
					set_time_limit(600);
					$start = time();
				}
			}
			fclose($ofd);
			if (!$failed) {
				$success = true;
			}
		} else {
			return "Could not open $outputFile in write mode";
		}
		fclose($fd);

		/* if the HTTP response code did not begin with a 2 this request was not successful */
		if (!preg_match("/^HTTP\/\d+\.\d+\s2\d{2}/", $response)) {
			return "Download failed with HTTP status: $response";
		}

		return true;
	}

	function isSupported() {
		return isPhpFunctionSupported('fsockopen');
	}

	function getName() {
		return 'Download with PHP fsockopen()';
	}
}

class CurlDownloader extends DownloadMethod {
	function download($url, $outputFile) {
		$ch = curl_init();
		$ofh = fopen($outputFile, 'wb');
		if (!$ofh) {
			fclose($ch);
			return 'Unable to open output file in writing mode';
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FILE, $ofh);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_exec($ch);

		$errorString = curl_error($ch);
		$errorNumber = curl_errno($ch);
		curl_close($ch);

		if ($errorNumber != 0) {
			if (!empty($errorString)) {
				return $errorString;
			} else {
				return 'CURL download failed';
			}
		}

		return true;
	}

	function isSupported() {
		foreach (array('curl_init', 'curl_setopt', 'curl_exec', 'curl_close', 'curl_error') as $functionName) {
			if (!isPhpFunctionSupported($functionName)) {
				return false;
			}
		}
		if( ini_get('open_basedir') != '' || strtolower(ini_get('safe_mode')) == 'on') {
			return false;
		}
		return true;
	}

	function getName() {
		return 'Download with PHP cURL()';
	}
}
function isPhpFunctionSupported($functionName) {
	if (in_array($functionName, split(',\s*', ini_get('disable_functions'))) || !function_exists($functionName)) {
		return false;
	} else {
		return true;
	}
}
/* Check if a specific command line tool is available */
function isBinaryAvailable($binaryName) {
	$binaryPath = getBinaryPath($binaryName);
	return !empty($binaryPath);
}

/* Return the path to a binary or false if it's not available */
function getBinaryPath($binaryName) {
	if (!isPhpFunctionSupported('exec')) {
		return false;
	}

	/* First try 'which' */
	$ret = array();
	exec('which ' . $binaryName, $ret);
	if (strpos(join(' ',$ret), $binaryName) !== false && @is_executable(join('',$ret))) {
		return $binaryName; // it's in the path
	}

	/* Try a bunch of likely seeming paths to see if any of them work. */
	$paths = array();
	if (!strncasecmp(PHP_OS, 'win', 3)) {
		$separator = ';';
		$slash = "\\";
		$extension = '.exe';
		$paths[] = "C:\\Program Files\\$binaryName\\";
		$paths[] = "C:\\apps\$binaryName\\";
		$paths[] = "C:\\$binaryName\\";
	} else {
		$separator = ':';
		$slash = "/";
		$extension = '';
		$paths[] = '/usr/bin/';
		$paths[] = '/usr/local/bin/';
		$paths[] = '/bin/';
		$paths[] = '/sw/bin/';
	}
	$paths[] = './';

	foreach (explode($separator, getenv('PATH')) as $path) {
		$path = trim($path);
		if (empty($path)) {
			continue;
		}
		if ($path{strlen($path)-1} != $slash) {
			$path .= $slash;
		}
		$paths[] = $path;
	}

	/* Now try each path in turn to see which ones work */
	foreach ($paths as $path) {
		$execPath = $path . $binaryName . $extension;
		if (@file_exists($execPath) && @is_executable($execPath)) {
			/* We have a winner */
			return $execPath;
		}
	}

	return false;
}
?>