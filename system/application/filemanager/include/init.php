<?php
// ensure this file is being included by a parent file
if (!defined('_JEXEC') && !defined('_VALID_MOS')) die('Restricted access');
/**
 * @version $Id: init.php 182 2011-01-06 09:34:30Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
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
 * This file initializes most of the variables and constants we need in joomlaXplorer
 */
// Vars
if (isset($_SERVER)) {
	$GLOBALS['__GET']	= &$_GET;
	$GLOBALS['__POST']	= &$_POST;
	$GLOBALS['__SERVER'] = &$_SERVER;
	$GLOBALS['__FILES']  = &$_FILES;
} elseif (isset($HTTP_SERVER_VARS)) {
	$GLOBALS['__GET']	= &$HTTP_GET_VARS;
	$GLOBALS['__POST']	= &$HTTP_POST_VARS;
	$GLOBALS['__SERVER'] = &$HTTP_SERVER_VARS;
	$GLOBALS['__FILES']  = &$HTTP_POST_FILES;
} else {
	die("<strong>ERROR: Your PHP version is too old</strong><br/>".
	"You need at least PHP 5.0 to run eXtplorer; preferably PHP 5.2 or higher.");
}

//------------------------------------------------------------------------------
	// if gzcompress is available, we can use Zip, Tar and TGz
	if ( extension_loaded("zlib")) {
		$GLOBALS["zip"] = $GLOBALS["tgz"] = true;
	}
	else {
		$GLOBALS["zip"] = $GLOBALS["tgz"] = false;
	}

// the filename of the eXtplorer script: (you rarely need to change this)
if ($_SERVER['SERVER_PORT'] == 443 ) {
	$GLOBALS["script_name"] = "https://" . $GLOBALS['__SERVER']['HTTP_HOST'] . $GLOBALS['__SERVER']["PHP_SELF"];
	$GLOBALS['home_url']	= "https://" . $GLOBALS['__SERVER']['HTTP_HOST'] . dirname($GLOBALS['__SERVER']["PHP_SELF"]);
} else {
	$GLOBALS["script_name"] = "http://"  . $GLOBALS['__SERVER']['HTTP_HOST'] . $GLOBALS['__SERVER']["PHP_SELF"];
	$GLOBALS['home_url']	= "http://"  . $GLOBALS['__SERVER']['HTTP_HOST'] . dirname($GLOBALS['__SERVER']["PHP_SELF"]);
}

$GLOBALS['home_url'] = str_replace( '/administrator', '', $GLOBALS['home_url'] );
$GLOBALS['home_dir'] = !empty( $_SERVER['DOCUMENT_ROOT'] ) ? $_SERVER['DOCUMENT_ROOT'] : '.';

// Important Definitions!
define ("_EXT_PATH",		realpath(dirname( __FILE__ ) . '/..'));
define ("_EXT_FTPTMP_PATH", realpath(dirname( __FILE__ ) . '/../ftp_tmp'));

if (function_exists( 'mosGetParam') || class_exists( 'jconfig')) {
	define ("_EXT_URL", $GLOBALS['home_url']."/administrator/components/com_extplorer");
} else {
	define ("_EXT_URL", dirname($GLOBALS['script_name']));
}

require_once(_EXT_PATH . '/application.php');
require_once(_EXT_PATH . '/include/functions.php');

// check if we need login
	if ( !ext_isjoomla() ) {
		// login to use eXtplorer: (true/false)
		$GLOBALS["require_login"] = true;
	} else {
		$GLOBALS["require_login"] = false;
	}

if (!class_exists('InputFilter')) {
	require_once( _EXT_PATH . '/libraries/inputfilter.php' );
}
require_once(_EXT_PATH . "/config/conf.php");

$GLOBALS["separator"] = ext_getSeparator();

$action 			= stripslashes(extGetParam( $_REQUEST, "action" ));
$default_lang		= !empty( $GLOBALS['mosConfig_lang'] ) ? $GLOBALS['mosConfig_lang'] : ext_Lang::detect_lang();
if( !@is_object( $mainframe )) {
	$mainframe = JFactory::getApplication('administrator');
}
$GLOBALS["language"] = basename($mainframe->getUserStateFromRequest( 'language', 'lang', $default_lang ));

// Get Item
if (isset($_REQUEST["item"])) {
  $GLOBALS["item"]=$item = stripslashes(rawurldecode($_REQUEST["item"]));
} else {
  $GLOBALS["item"]=$item ="";
}

if (!empty( $GLOBALS['__POST']["selitems"] )) {
	// Arrayfi the string 'selitems' if necessary
	if (!is_array( $GLOBALS['__POST']["selitems"])) {
		$GLOBALS['__POST']["selitems"] = array($GLOBALS['__POST']["selitems"]);
	}

	foreach($GLOBALS['__POST']["selitems"] as $i => $myItem) {
		$GLOBALS['__POST']["selitems"][$i] = urldecode($myItem);
	}
}

// Get Sort
$GLOBALS["order"]	= extGetParam( $_REQUEST, 'order', 'name');
// Get Sortorder
$GLOBALS["direction"] = extGetParam( $_REQUEST, 'direction', 'ASC');
$GLOBALS["start"]	= extGetParam( $_REQUEST, 'start', 0);
$GLOBALS["limit"]	= extGetParam( $_REQUEST, 'limit', 50);

//------------------------------------------------------------------------------

/** @var $GLOBALS['file_mode'] Can be 'file' or 'ftp' */
if (!isset($_REQUEST['file_mode']) && !empty($_SESSION['file_mode'])) {
	$GLOBALS['file_mode'] = extGetParam($_SESSION, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default']);
} else {
	if (@$_REQUEST['file_mode'] != @$_SESSION['file_mode'] && in_array($_REQUEST['file_mode'], $GLOBALS['ext_conf']['authentication_methods_allowed'])) {
		if ($action != 'login' && empty($_SESSION['credentials_'.extGetParam($_REQUEST, 'file_mode')])) {
			extRedirect(make_link( 'login','',null,null,null,null,'&type='.urlencode(extGetParam($_REQUEST, 'file_mode'))));
		} else {
			$GLOBALS['file_mode'] = $_SESSION['file_mode'] = extGetParam($_REQUEST, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default']);
		}
	} elseif (isset($_REQUEST['file_mode'])) {
		$GLOBALS['file_mode'] = $_SESSION['file_mode'] = extGetParam($_REQUEST, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default']);
	} else {
		$GLOBALS['file_mode'] = extGetParam($_SESSION, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default']);
	}
}

// Necessary files
require_once(_EXT_PATH."/languages/english.php");
if (file_exists(_EXT_PATH."/languages/".$GLOBALS["language"].".php")) {
	require_once(_EXT_PATH."/languages/".$GLOBALS["language"].".php" );
}

require_once(_EXT_PATH . "/languages/english_mimes.php");
if ( file_exists(_EXT_PATH . "/languages/" . $GLOBALS["language"]."_mimes.php")) {
	require_once(_EXT_PATH . "/languages/" . $GLOBALS["language"]."_mimes.php");
}

require_once(_EXT_PATH . "/config/mimes.php");
require_once(_EXT_PATH . '/libraries/JSON.php');
require_once(_EXT_PATH . "/libraries/File_Operations.php");
require_once(_EXT_PATH . "/include/header.php");
require_once(_EXT_PATH . "/include/result.class.php");

if( $action == 'include_javascript' ) {
  	while (@ob_end_clean());
  	header("Content-Type: text/javascript; charset=".strtolower($GLOBALS["charset"]));
  	include( _EXT_PATH.'/scripts/'.basename(extGetParam($_REQUEST, 'file' )).'.php');
  	ext_exit();
}
//------------------------------------------------------------------------------

// Raise Memory Limit
ext_RaiseMemoryLimit( '8M' );

$GLOBALS['ext_File'] = new ext_File();


//------------------------------------------------------------------------------
if ($GLOBALS["require_login"]) {	// LOGIN

	require(_EXT_PATH."/include/login.php");

	if ($GLOBALS["action"]=="logout") {
		$auth->onLogout();
	} else {
		if ($GLOBALS["action"]=="login") {
			$GLOBALS["dir"] = $dir = extGetParam( $_SESSION,'ext_'.$GLOBALS['file_mode'].'dir', '' );
		}
		login();
		if ($GLOBALS["action"]=="login" || empty($_SESSION['credentials_'.$authentication_type]['username'])) {
			return;
		}
	}
}

if (ext_isWindows()) {
	if ( strstr($GLOBALS['home_dir'], ':')) {
		$GLOBALS['home_dir'][0] = strtoupper($GLOBALS['home_dir'][0]);
	}
}

//------------------------------------------------------------------------------
if ( !isset( $_REQUEST['dir'] ) ) {

	$GLOBALS["dir"] = $dir = extGetParam( $_SESSION,'ext_'.$GLOBALS['file_mode'].'dir', '' );
	if (!empty($dir)) {
		$dir = @$dir[0] == '/' ? substr( $dir, 1 ) : $dir;
	}
	if( @$GLOBALS["action"]!="login") {
		$try_this = ext_isFTPMode() ? '/'.$dir : $GLOBALS['home_dir'].'/'.$dir;
		if (!empty($dir) && !$GLOBALS['ext_File']->file_exists($try_this)) {
			$dir = '';
		}
	}
} else {
	$GLOBALS["dir"] = $dir = urldecode(stripslashes(extGetParam($_REQUEST, "dir")));
}

if ($dir == 'ext_root') {
	$GLOBALS["dir"] = $dir = '';
}

if (ext_isFTPMode() && $dir != '') {
	$GLOBALS['FTPCONNECTION']->cd( $dir );
}

$abs_dir=get_abs_dir($GLOBALS["dir"]);

if (!file_exists($GLOBALS["home_dir"])) {
  if (!file_exists($GLOBALS["home_dir"].$GLOBALS["separator"])) {
	if ($GLOBALS["require_login"]) {
		$extra = "<a href=\"" . make_link("logout",NULL,NULL) . "\">" . $GLOBALS["messages"]["btnlogout"] . "</a>";
	} else {
		$extra = NULL;
	}
	ext_Result::sendResult('', false, $GLOBALS["error_msg"]["home"]." (".$GLOBALS["home_dir"].")",$extra);
  }
}

if (!$GLOBALS['ext_conf']['symlink_allow_abovehome']) {
	if (!down_home($abs_dir)) {
		ext_Result::sendResult('', false, $GLOBALS["dir"]." : ".$GLOBALS["error_msg"]["abovehome"]);
		$dir = '';
	}
}

if (!get_is_dir(utf8_decode($abs_dir)) && !get_is_dir($abs_dir.$GLOBALS["separator"])) {
	ext_Result::sendResult('', false, '"'.$abs_dir.'" - '.$GLOBALS["error_msg"]["direxist"]);
	$dir = '';
}

$_SESSION['ext_'.$GLOBALS['file_mode'].'dir'] = $dir;
//------------------------------------------------------------------------------
