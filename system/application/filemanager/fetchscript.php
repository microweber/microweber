<?php
/**
* This file is used to send gzipped Javascripts and Stylesheets to the browser
* 
* It expects three parameters:
* 
* gzip (can be 1 or 0, for yes or no; default: 0)
* subdir[INDEX] (relative directory from /components/com_virtuemart/js)
* file[INDEX] (filename only)
* where INDEX is the actual number of the file to be included, so you can include multiple scripts at a time
* 
* @version $Id: fetchscript.php 156 2009-10-26 15:19:27Z soeren $
* @package eXtplorer
* @copyright Copyright (C) 2006-2007 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
* http://virtuemart.net
*/

/**
 * Initialise GZIP
 * @author Mambo / Joomla Project
 */
function initGzip() {
	global $do_gzip_compress;

	$gzip = isset( $_GET['gzip'] ) ? (boolean)$_GET['gzip'] : false;

	$do_gzip_compress = FALSE;
	if ($gzip) {
		$phpver 	= phpversion();
		$useragent 	= isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$canZip 	= isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';

		$gzip_check 	= 0;
		$zlib_check 	= 0;
		$gz_check		= 0;
		$zlibO_check	= 0;
		$sid_check		= 0;
		if ( strpos( $canZip, 'gzip' ) !== false) {
			$gzip_check = 1;
		}
		if ( extension_loaded( 'zlib' ) ) {
			$zlib_check = 1;
		}
		if ( function_exists('ob_gzhandler') ) {
			$gz_check = 1;
		}
		if ( ini_get('zlib.output_compression') ) {
			$zlibO_check = 1;
		}

		if ( $phpver >= '4.0.4pl1' && ( strpos($useragent,'compatible') !== false || strpos($useragent,'Gecko')	!== false ) ) {
			// Check for gzip header or norton internet securities
			if ( ( $gzip_check || isset( $_SERVER['---------------']) ) && $zlib_check && $gz_check && !$zlibO_check ) {
				// You cannot specify additional output handlers if
				// zlib.output_compression is activated here
				ob_start( 'ob_gzhandler' );

				return;
			}
		} else if ( $phpver > '4.0' ) {
			if ( $gzip_check ) {

				if ( $zlib_check ) {
					$do_gzip_compress = TRUE;
					ob_start();
					ob_implicit_flush(0);

					header( 'Content-Encoding: gzip' );
					return;
				}
			}
		}
	}
	ob_start();
}

/**
* Perform GZIP
* @author Mambo / Joomla! project
*/
function doGzip() {
	global $do_gzip_compress;
	if ( $do_gzip_compress ) {
		/**
		*Borrowed from php.net!
		*/
		$gzip_contents = ob_get_contents();
		ob_end_clean();

		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);

		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	} else {
		ob_end_flush();
	}
}

/**
* This function fixes the URLs used in the CSS file
* This is necessary, because this file is (usually) located somewhere else than the CSS file! That makes
* relative URL references point to wrong directories - so we need to fix that!
*/
function cssUrl( $ref, $subdir ) {
	$ref = str_replace( "'", '', stripslashes( $ref ));
	$ref = trim( str_replace( '"', '', $ref) );
	// Absolute References don't need to be fixed
	if( substr( $ref, 0, 4 ) == 'http' ) {
		return 'url( "'. $ref.'" )';
	}
	chdir( dirname( __FILE__ ).'/'.$subdir );
	$ref = str_replace( dirname( __FILE__ ), '', realpath( $ref ));
	$ref = str_replace( "\\", '/', $ref );
	return 'url( "'. substr( $ref, 1 ).'" )';

}
/**
 * Checks and sets HTTP headers for conditional HTTP requests
 * Borrowed from DokuWiki (/lib/exe/fetch.php)
 * @author Simon Willison <swillison@gmail.com>
 * @link   http://simon.incutio.com/archive/2003/04/23/conditionalGet
 */
function http_conditionalRequest($timestamp){
	// A PHP implementation of conditional get, see 
	//	http://fishbowl.pastiche.org/archives/001132.html
	$last_modified = gmdate( 'D, d M Y H:i:s', $timestamp ) . ' GMT';
	$etag = '"'.md5($last_modified).'"';
	// Send the headers
	header("Last-Modified: $last_modified");
	header("ETag: $etag");
	// See if the client has provided the required headers
	$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ?
		stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) :
		false;
	$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
		stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : 
		false;
	if (!$if_modified_since && !$if_none_match) {
		return;
	}
	// At least one of the headers is there - check them
	if ($if_none_match && $if_none_match != $etag) {
		return; // etag is there but doesn't match
	}
	if ($if_modified_since && $if_modified_since != $last_modified) {
		return; // if-modified-since is there but doesn't match
	}
	// Nothing has changed since their last request - serve a 304 and exit
	header('HTTP/1.0 304 Not Modified');
	exit;
}

initGzip();

$base_dir = dirname( __FILE__ );
$subdirs = @$_GET['subdir'];
if( !is_array( $subdirs ) && !empty( $subdirs )) {
	$subdirs = array( $subdirs );
}

$files = @$_GET['file'];
if( !is_array( $files ) && !empty( $files )) {
	$files = array( $files );
}
if( empty( $files ) || sizeof($files) != sizeof( $subdirs )) {
	header("HTTP/1.0 400 Bad Request");
  	echo 'Bad request';
  	exit;
}
$countFiles = sizeof($files);
$newest_mdate = 0;

for( $i = 0; $i < $countFiles; $i++ ) {
	if( empty( $files[$i] )) continue;
	$file = $files[$i];
	$subdir = $subdirs[$i];

	$dir = realpath( $base_dir . '/' .	$subdir );
	$file = $dir . '/' . basename( $file );

	if( !file_exists( $file ) || (!stristr( $dir, $base_dir ) && !stristr( $dir, "/usr/share/javascript") && !stristr( $dir, "/usr/share/yui")) ) {
		if( $countFiles == 1 ) {
			header("HTTP/1.0 404 Not Found");
			echo 'Not Found';
			exit;
		}
		continue;
	}
	$newest_mdate = max( filemtime( $file ), $newest_mdate );
}

// This function quits the page load if the browser has a cached version of the requested script.
// It then returns a 304 Not Modified header
http_conditionalRequest( $newest_mdate );

// here we need to send the script or stylesheet
$processed_files = 0;
for( $i = 0; $i < $countFiles; $i++ ) {
	$file = $files[$i];
	$subdir = $subdirs[$i];

	$dir = realpath( $base_dir . '/' .	$subdir );
	$file = $dir . '/' . basename( $file );
	if( !file_exists( $file ) || (!stristr( $dir, $base_dir ) && !stristr( $dir, "/usr/share/javascript") && !stristr( $dir, "/usr/share/yui")) || !is_readable( $file )) {
		continue;
	}
	$processed_files++;
	$fileinfo = pathinfo( $file );
	switch ( $fileinfo['extension']) {
		case 'css': 
			$mime_type = 'text/css'; 
			header( 'Content-Type: '.$mime_type.';');
			$css = implode( '', file( $file ));

			$str_css =	preg_replace("/url\((.+?)\)/ie","cssUrl('\\1', '$subdir')", $css);
			echo $str_css;

			break;

		case 'js': 
			$mime_type = 'application/x-javascript'; 
			header( 'Content-Type: '.$mime_type.';');

			readfile( $file );

			break;

		default: 
			continue;

	}
}
if( $processed_files == 0 ) {
	if( !file_exists( $file ) ) {
		header("HTTP/1.0 404 Not Found");
		echo 'Not Found';
		exit;
	}
	if( !is_readable( $file ) ) {
		header("HTTP/1.0 500 Internal Server Error");
		echo "Could not read ".basename($file)." - bad permissions?";
		exit;
	}
}
// Tell the user agent to cache this script/stylesheet for an hour
$age = 3600;
header( 'Expires: '.gmdate( 'D, d M Y H:i:s', time()+ $age ) . ' GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', @filemtime( $file ) ) . ' GMT' );
header( 'Cache-Control: public, max-age='.$age.', must-revalidate, post-check=0, pre-check=0' );
header( 'Pragma: public' );

doGzip();

exit;

?>