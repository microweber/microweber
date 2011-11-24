<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: result.class.php 176 2010-11-06 08:44:55Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
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
 * Allows to handle errors and send results in JSON format
 *
 */
class ext_Result {
	function init() {
		ext_Result::empty_errors();
		ext_Result::empty_messages();
	}
	function add_message($message, $type='general') {
		$_SESSION['ext_message'][$type][] = $message;
	}
	function empty_messages() {
		$_SESSION['ext_message'] = array();
	}
	function count_messages() {

		if( empty($_SESSION['ext_message'])) {
			return 0;
		}
		$count = 0;
		foreach( $_SESSION['ext_message'] as $type ) {
			if( !empty( $type ) && is_array( $type )) {
				$count += sizeof( $type );
			}
		}
		return $count;
	}
	function add_error($error, $type='general') {
		$_SESSION['ext_error'][$type][] = $error;
	}
	function empty_errors() {
		$_SESSION['ext_error'] = array();
	}
	function count_errors() {
		if( empty($_SESSION['ext_error'])) {
			return 0;
		}
		$count = 0;
		foreach( @$_SESSION['ext_error'] as $type ) {
			if( !empty( $type ) && is_array( $type )) {
				$count += sizeof( $type );
			}
		}
		return $count;
	}
	function sendResult( $action, $success, $msg,$extra=array() ) {		// show error-message
		
		if( ext_isXHR() ) {
			
			$success = (bool)$success;
			if( $success && ext_Result::count_errors() > 0 ) {
				$success = false;
				foreach( @$_SESSION['ext_error'] as $type ) {
					if( is_array( $type )) {
						foreach( $type as $error ) {
							$msg .= '<br />'.$error;
						}
					}
				}
			}
			$result = array('action' => $action,
							'message' => str_replace("'", "\\'", $msg ),
							'error' => str_replace("'", "\\'", $msg ),//.print_r($_POST,true),
							'success' => $success 
						);
			foreach( $extra as $key => $value ) {
				$result[$key] = $value;
			}
			$classname = class_exists('ext_Json') ? 'ext_Json' : 'Services_JSON';
			$json = new $classname();
			$jresult = $json->encode($result);
			if(strtolower(extGetParam($_POST,'requestType')) == 'xmlhttprequest') {
				header("Content-type: text/html");
			}
			print $jresult;
			ext_exit();
		}
		$messagetxt = '';
		if($extra != NULL) {
			$msg .= " - ".$extra;
		}
		if( $msg != '' ) {
			ext_Result::add_error( $msg );
		}

		if( ext_Result::count_messages() < 1 && ext_Result::count_errors() < 1 ) {
			return;
		}
			
			if( ext_Result::count_messages() > 0 ) {
				$messagetxt .= '<h3>'.$GLOBALS["error_msg"]["message"].':</h3>';

				foreach ( $_SESSION['ext_message'] as $msgtype ) {
					foreach ( $msgtype as $message ) {
						$messagetxt .= $message .'<br/>';
					}
					$messagetxt .= '<br /><hr /><br />';
				}
				ext_Result::empty_messages();

				if( !empty( $_REQUEST['extra'])) {
					$messagetxt .= ' - '.htmlspecialchars(urldecode($_REQUEST['extra']), ENT_QUOTES );
				}
				
			}

			if( !empty( $_SESSION['ext_error'])) {
				$messagetxt .= '<h3>'.$GLOBALS["error_msg"]["error"].':</h3>';

				foreach ( $_SESSION['ext_error'] as $errortype ) {
					foreach ( $errortype as $error ) {
						$messagetxt .= $error .'<br/>';
					}
					$messagetxt .= '<br /><hr /><br />';
				}
				ext_Result::empty_errors();
			}
			
			if( !empty( $_REQUEST['extra'])) {
				$messagetxt .= " - ".htmlspecialchars(urldecode($_REQUEST['extra']), ENT_QUOTES );
			}
			extHTML::loadExtJS();
			show_header();
			defined('EXPLORER_NOEXEC') or define('EXPLORER_NOEXEC', 1 );
			
			echo ext_scriptTag('', 'Ext.Msg.alert(\'Status\', \''.$messagetxt . '\')' );
		//}
		$GLOBALS['action'] = 'show_error';
	}
}
//------------------------------------------------------------------------------
?>
