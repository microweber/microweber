<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * MAIN FILE! (formerly known as index.php)
 * 
 * @version $Id: admin.extplorer.php 182 2011-01-06 09:34:30Z soeren $
 * 
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
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
 *
 * This is a component with full access to the filesystem of your joomla Site
 * I wouldn't recommend to let in Managers
 * allowed: Superadministrator
**/

// The eXtplorer version number
$GLOBALS['ext_version'] = '2.1.0';
$GLOBALS['ext_home'] = 'http://extplorer.sourceforge.net';

//------------------------------------------------------------------------------
if( defined( 'E_STRICT' ) ) { // Suppress Strict Standards Warnings
	error_reporting(E_ALL);
}
//------------------------------------------------------------------------------
umask(0002); // Added to make created files/dirs group writable
//------------------------------------------------------------------------------
require_once( dirname( __FILE__) . "/include/init.php" );	// Init

if( !@is_object($my) && is_callable(array('jfactory','getuser'))) {
	$my = JFactory::getUser();
}

if( @is_object($my)) {
	
	if( ext_isJoomla('1.6', '>=' )) {
		$access = $my->authorise('core.admin');
	} else {
		$access = $my->usertype == 'Super Administrator';
	}
	
	if (!$access) {
		$url = htmlspecialchars($_SERVER['PHP_SELF']);
		if (headers_sent()) {
			echo "<script>document.location.href='$url';</script>\n";
		} else {
			@ob_end_clean(); // clear output buffer
			header( 'HTTP/1.1 403 Forbidden' );
			header( "Location: ". $url );
		}
	}
}

/** Needed to keep the filelist in the XML installer file up-to-date
$path = dirname(__FILE__);
$filelist = extReadDirectory( $path, '.', true, true );
$contents ='';
foreach($filelist as $file ) {
	if( is_dir( $file ) || strstr(dirname($file), "scripts" )) continue;
	$filepath = str_replace( $path.'/', '', $file );
	$contents .= '<filename>'.$filepath."</filename>\n";
}
file_put_contents( 'extplorer_filelist.txt', $contents );
*/
//------------------------------------------------------------------------------
if( $action == "post" )
  $action = extGetParam( $_REQUEST, "do_action" );
elseif( empty( $action ))
  $action = "list";


if( $action != 'show_error') {
	ext_Result::init();
}

if( defined( '_LOGIN_REQUIRED')) return;

// Empty the output buffer if this is a XMLHttpRequest
if( ext_isXHR() ) {
	error_reporting(0);
	while( @ob_end_clean() );
}

if( file_exists( _EXT_PATH . '/include/'. strtolower(basename( $action )) .'.php') ) {
	require_once( _EXT_PATH . '/include/'. strtolower(basename( $action )) .'.php');
}
$classname = 'ext_'.$action;
if( class_exists(strtolower($classname)) && is_callable(array($classname,'execaction'))) {
	$handler = new $classname();
	$handler->execAction( $dir, $item );
} else {

	switch($action) {		// Execute actions, which are not in class file

	//------------------------------------------------------------------------------
	// COPY/MOVE FILE(S)/DIR(S)
	case "copy":	case "move":
		require_once( _EXT_PATH ."/include/copy_move.php" );
		copy_move_items($dir);
	break;

	//------------------------------------------------------------------------------
	// SEARCH FOR FILE(S)/DIR(S)
	case "search":
		require_once( _EXT_PATH ."/include/search.php" );
		search_items($dir);
	break;

	//------------------------------------------------------------------------------
	// USER-ADMINISTRATION
	case "admin":
		require_once( _EXT_PATH . "/include/admin.php" );
		show_admin($dir);
	break;

	//------------------------------------------------------------------------------
		// BOOKMARKS
	case 'modify_bookmark':
		$task = extGetParam( $_REQUEST, 'task' );
		require_once( _EXT_PATH.'/include/bookmarks.php' );
		modify_bookmark( $task, $dir );

		break;
	//------------------------------------------------------------------------------
	case 'show_error':
		ext_Result::sendResult('', false, '');
		break;
	case'get_about':
		require_once( _EXT_PATH . "/include/system_info.php" );
		system_info();
		break;
	//------------------------------------------------------------------------------
	// DEFAULT: LIST FILES & DIRS
	case "getdircontents":
			require_once( _EXT_PATH . "/include/list.php" );
			$requestedDir = stripslashes(str_replace( '_RRR_', '/', extGetParam( $_REQUEST, 'node' )));
			if( empty($requestedDir) || $requestedDir == 'ext_root') {
				$requestedDir = $dir;
			}
			
			send_dircontents( $requestedDir, extGetParam($_REQUEST,'sendWhat','files') );
			break;
	case 'get_dir_selects':
			echo get_dir_selects( $dir );
			break;
	case 'chdir_event':
			require_once( _EXT_PATH.'/include/bookmarks.php' );
			$response = Array( 'bookmarks' => list_bookmarks($dir) );
			$classname = class_exists('ext_Json') ? 'ext_Json' : 'Services_JSON';
			$json = new $classname();
			echo $json->encode( $response );
			break;
	case 'get_image':
			require_once( _EXT_PATH . "/include/view.php" );
			ext_View::sendImage( $dir, $item );
	case 'ftp_authentication': 
	case 'ssh2_authentication':
	case 'extplorer_authentication':
			$auth_info = explode('_', $action);
			$auth_classname = 'ext_'.$action;
			require_once(_EXT_PATH.'/include/authentication/'.$auth_info[0].'.php');
			$auth_plugin = new $auth_classname();
			$auth_plugin->onShowLoginForm();
			break;
	default:
		require_once( _EXT_PATH . "/include/list.php" );
		ext_List::execAction($dir);
	//------------------------------------------------------------------------------
	}
// end switch-statement
}
//------------------------------------------------------------------------------
// Disconnect from ftp server
if( ext_isFTPMode() ) {
	$GLOBALS['FTPCONNECTION']->disconnect();
}

// Empty the output buffer if this is a XMLHttpRequest
if( ext_isXHR() ) {
	ext_exit();
}
