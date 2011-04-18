<?php
/**
* @version $Id: standalone.php 179 2011-01-04 12:15:17Z soeren $
* @package eXtplorer
* @copyright Copyright (C) 2007 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
define( 'EXT_STANDALONE', 1 );

if( defined( 'E_STRICT' ) ) { // Suppress Strict Standards Warnings
	error_reporting(E_ALL);
}

if (version_compare( phpversion(), '5.0' ) < 0) {
	require_once( dirname( __FILE__ ) . '/compat.php50x.php' );
}
require_once( dirname( __FILE__ ) .'/../include/users.php' );
@set_magic_quotes_runtime( 0 );

// platform neurtral url handling
if ( isset( $_SERVER['REQUEST_URI'] ) ) {
	$request_uri = $_SERVER['REQUEST_URI'];
} else {
	$request_uri = $_SERVER['SCRIPT_NAME'];
	// Append the query string if it exists and isn't null
	if ( isset( $_SERVER['QUERY_STRING'] ) && !empty( $_SERVER['QUERY_STRING'] ) ) {
		$request_uri .= '?' . $_SERVER['QUERY_STRING'];
	}
	$_SERVER['REQUEST_URI'] = $request_uri;
}
if( empty( $_SERVER['PHP_SELF'])) {
	$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
}

// current server time
$now = @date( 'Y-m-d H:i', time() );
DEFINE( '_CURRENT_SERVER_TIME', $now );
DEFINE( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' );

/**
* Joomla! Mainframe class
*
* Provide many supporting API functions
* @package Joomla
*/
class extMainFrame {
	/** @var array An array of page meta information */
	var $_head						= null;
	var $_userstate					= null;

	/**
	* Class constructor
	* @param database A database connection object
	* @param string The url option
	* @param string The path of the ext directory
	*/
	function extMainFrame() {
		session_name( 'eXtplorer' );
		if( !@is_writable(ini_get('session.save_path')) && ini_get('session.save_handler') == 'files') {
			ini_set('session.save_path', realpath( dirname( __FILE__ ).'/../ftp_tmp') );
		}
		session_start();
		if( !isset( $_SESSION['_userstate'])) {
			$_SESSION['_userstate'] = array();
			$this->_userstate = $_SESSION['_userstate'];
		} else {
			$this->_userstate = $_SESSION['_userstate'];
		}
		$this->_head = array();
		$this->_head['title'] 	= 'eXtplorer';
		$this->_head['meta'] 	= array();
		$this->_head['custom'] 	= array();

		$now = date( 'Y-m-d H:i:s', time() );
		$this->set( 'now', $now );
	}

	/**
	* @param string
	*/
	function setPageTitle( $title=null, $append=false ) {
		$title = trim( htmlspecialchars( $title ) );
		$title = stripslashes($title);
		if( $append ) {
			$this->_head['title'] .= ' - ' . $title;
		} else {
			$this->_head['title'] = $title.' - ' . $this->_head['title'];
		}

	}
	/**
	* @param string The value of the name attibute
	* @param string The value of the content attibute
	* @param string Text to display before the tag
	* @param string Text to display after the tag
	*/
	function addMetaTag( $name, $content, $prepend='', $append='' ) {
		$name = trim( htmlspecialchars( $name ) );
		$content = trim( htmlspecialchars( $content ) );
		$prepend = trim( $prepend );
		$append = trim( $append );
		$this->_head['meta'][] = array( $name, $content, $prepend, $append );
	}
	/**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	* Tags ordered in with Site Keywords and Description first
	*/
	function appendMetaTag( $name, $content ) {
		$name = trim( htmlspecialchars( $name ) );
		$n = count( $this->_head['meta'] );
		for ($i = 0; $i < $n; $i++) {
			if ($this->_head['meta'][$i][0] == $name) {
				$content = trim( htmlspecialchars( $content ) );
				if ( $content ) {
					if ( !$this->_head['meta'][$i][1] ) {
						$this->_head['meta'][$i][1] = $content ;
					} else {
						$this->_head['meta'][$i][1] = $content .', '. $this->_head['meta'][$i][1];
					}
				}
				return;
			}
		}
		$this->addMetaTag( $name , $content );
	}

	/**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	*/
	function prependMetaTag( $name, $content ) {
		$name = trim( htmlspecialchars( $name ) );
		$n = count( $this->_head['meta'] );
		for ($i = 0; $i < $n; $i++) {
			if ($this->_head['meta'][$i][0] == $name) {
				$content = trim( htmlspecialchars( $content ) );
				$this->_head['meta'][$i][1] = $content . $this->_head['meta'][$i][1];
				return;
			}
		}
		$this->addMetaTag( $name, $content );
	}
	/**
	* Adds a custom html string to the head block
	* @param string The html to add to the head
	*/
	function addCustomHeadTag( $html ) {
		$this->_head['custom'][] = trim( $html );
	}
	/**
	* @return string
	*/
	function getHead() {
		$head = array();
		$head[] = '<title>' . $this->_head['title'] . '</title>';
		foreach ($this->_head['meta'] as $meta) {
			if ($meta[2]) {
				$head[] = $meta[2];
			}
			$head[] = '<meta name="' . $meta[0] . '" content="' . $meta[1] . '" />';
			if ($meta[3]) {
				$head[] = $meta[3];
			}
		}
		foreach ($this->_head['custom'] as $html) {
			$head[] = $html;
		}
		return implode( "\n", $head ) . "\n";
	}


	/**
	* @return string
	*/
	function getPageTitle() {
		return $this->_head['title'];
	}

	/**
	* @return string
	*/
	function getCustomPathWay() {
		return $this->_custom_pathway;
	}

	function appendPathWay( $html ) {
		$this->_custom_pathway[] = $html;
	}
	function getUserName() {
		return @$_SESSION['credentials_'.$GLOBALS['file_mode']]['username'];
	}
	function getPassword() {
		return @$_SESSION['credentials_'.$GLOBALS['file_mode']]['password'];
	}
  /**
	* Gets the value of a user state variable
	* @param string The name of the variable
	*/
	function getUserState( $var_name ) {
		if (is_array( $this->_userstate )) {
			return extGetParam( $this->_userstate, $var_name, null );
		} else {
			return null;
		}
	}
	/**
	* Gets the value of a user state variable
	* @param string The name of the user state variable
	* @param string The name of the variable passed in a request
	* @param string The default value for the variable if not found
	*/
	function getUserStateFromRequest( $var_name, $req_name, $var_default=null ) {

		if (is_array( $this->_userstate )) {

			if (isset( $_REQUEST[$req_name] )) {
				$this->setUserState( $var_name, $_REQUEST[$req_name] );
			} else if (!isset( $this->_userstate[$var_name] )) {
				$this->setUserState( $var_name, $var_default );
			}

			// filter input
			$iFilter = new InputFilter();
			$this->_userstate[$var_name] = $iFilter->process( $this->_userstate[$var_name] );

			return $this->_userstate[$var_name];
		} else {
			return null;
		}
	}
	/**
	* Sets the value of a user state variable
	* @param string The name of the variable
	* @param string The value of the variable
	*/
	function setUserState( $var_name, $var_value ) {
		if (is_array( $this->_userstate )) {
			$this->_userstate[$var_name] = $var_value;
			$_SESSION['_userstate'] = $this->_userstate;
		}
	}


	/**
	* @param string The name of the property
	* @param mixed The value of the property to set
	*/
	function set( $property, $value=null ) {
		$this->$property = $value;
	}

	/**
	* @param string The name of the property
	* @param mixed	The default value
	* @return mixed The value of the property
	*/
	function get($property, $default=null) {
		if(isset($this->$property)) {
			return $this->$property;
		} else {
			return $default;
		}
	}
}


/**
 * Initialise GZIP
 */
function extInitGzip() {
	global $do_gzip_compress;
	if( $GLOBALS['use_gzip'] ) {
		$do_gzip_compress = FALSE;
		$phpver 	= phpversion();
		$useragent 	= extGetParam( $_SERVER, 'HTTP_USER_AGENT', '' );
		$canZip 	= extGetParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

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
		if ( ini_get('session.use_trans_sid') ) {
			$sid_check = 1;
		}

		if ( $phpver >= '4.0.4pl1' && ( strpos($useragent,'compatible') !== false || strpos($useragent,'Gecko')	!== false ) ) {
			// Check for gzip header or northon internet securities or session.use_trans_sid
			if ( ( $gzip_check || isset( $_SERVER['---------------']) ) && $zlib_check && $gz_check && !$zlibO_check && !$sid_check ) {
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
*/
function extDoGzip() {
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
* Replaces &amp; with & for xhtml compliance
*
* Needed to handle unicode conflicts due to unicode conflicts
*/
function ampReplace( $text ) {
	$text = str_replace( '&&', '*--*', $text );
	$text = str_replace( '&#', '*-*', $text );
	$text = str_replace( '&amp;', '&', $text );
	$text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
	$text = str_replace( '*-*', '&#', $text );
	$text = str_replace( '*--*', '&&', $text );

	return $text;
}
$mainframe = new extMainFrame();

$mypath = realpath( dirname(__FILE__).'/..');
$archive_name = $mypath.'/scripts.tar.gz';
if( file_exists( $archive_name ) && !file_exists( $mypath .'/scripts/functions.js.php')) {
	require_once($mypath . "/include/functions.php");
	require_once($mypath . "/libraries/Archive/archive.php");

	ext_RaiseMemoryLimit( '16M' );
	error_reporting( E_ALL ^ E_NOTICE );

	$extract_dir = $mypath.'/';

	$result = extArchive::extract( $archive_name, $extract_dir );
	if( !PEAR::isError( $result )) {
		unlink( $archive_name );
	} else {
		die( '<html><head><title>eXtplorer - Error!</title></head>
			<body><h2>Installation needs to be completed!</h2>
			<p>To complete the eXtplorer Installation you need to extract the contents of the file <strong>scripts.zip</strong>
 (you can find this file in the extplorer package) and upload it to the subdirectory <strong>/scripts</strong> of your eXtplorer installation.
	<br/>
	Please just upload the contents of the extracted folder &quot;/scripts&quot; into the subdirectory <strong>/scripts</strong> and do not create a subdirectory like &quot;/scripts/scripts/&quot;. 
</p>
			</body></html>');
	}
}
?>
