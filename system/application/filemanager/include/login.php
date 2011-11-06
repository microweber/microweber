<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: login.php 187 2011-01-18 15:25:24Z soeren $
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
 * User Authentication Functions
 */

//------------------------------------------------------------------------------
require_once _EXT_PATH."/include/users.php";
load_users();
//------------------------------------------------------------------------------

$GLOBALS['__SESSION']=&$_SESSION;
if( !empty($_REQUEST['type'])) {
	$GLOBALS['authentication_type'] = basename(extGetParam($_REQUEST, 'type', $GLOBALS['ext_conf']['authentication_method_default']));
} else {
	$GLOBALS['authentication_type'] = $GLOBALS['file_mode'];
}
if($GLOBALS['authentication_type'] == 'file') {
	$GLOBALS['authentication_type'] = 'extplorer';
}
if( !in_array($GLOBALS['authentication_type'],$GLOBALS['ext_conf']['authentication_methods_allowed'])) {
	$GLOBALS['authentication_type'] = extgetparam( $_SESSION, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default'] );
	if( !in_array($GLOBALS['authentication_type'],$GLOBALS['ext_conf']['authentication_methods_allowed'])) {
		$GLOBALS['authentication_type'] = $_SESSION['file_mode'] = $GLOBALS['ext_conf']['authentication_method_default'];
	}
}

if( file_exists(_EXT_PATH.'/include/authentication/'.$authentication_type.'.php')) {
		require_once(_EXT_PATH.'/include/authentication/'.$authentication_type.'.php');
		$classname = 'ext_'.$authentication_type.'_authentication';
		if( class_exists($classname)) {
			$GLOBALS['auth'] = new $classname();
		}
}
	
//------------------------------------------------------------------------------
function login() {
	global $auth, $authentication_type;
	if( !is_object($auth)) {
		return false;
	}
	if( !empty($GLOBALS['__POST']['username']) || !empty($_SESSION['credentials_'.$authentication_type])) {
		
		if( !empty($GLOBALS['__POST']['username'])) {
			$username = $GLOBALS['__POST']['username'];
			$password = $GLOBALS['__POST']['password'];
			if( $authentication_type == 'extplorer') $password = extEncodePassword($password);
		} else {
			$username = $_SESSION['credentials_'.$authentication_type]['username'];
			$password = $_SESSION['credentials_'.$authentication_type]['password'];
		}
		
		$res = $auth->onAuthenticate( array('username' => $username, 'password' => $password) );
		if( !PEAR::isError($res) && $res !== false ) {
			if( @$GLOBALS['__POST']['action'] == 'login' && ext_isXHR() ) {
				session_write_close();
				ext_Result::sendResult('login', true, ext_Lang::msg('actlogin_success') );
			}
			return true;
		} else {
			if( ext_isXHR() ) {
				$errmsg = PEAR::isError($res) ? $res->getMessage() : ext_Lang::msg( 'actlogin_failure' );
				
				ext_Result::sendResult('login', false, $errmsg );
			}
			return false;
		}
		
	}
	if( ext_isXHR() && $GLOBALS['action'] != 'login') {
		echo '<script type="text/javascript>document.location="'._EXT_URL.'/index.php";</script>';
		exit();
	}
	session_write_close();
	session_id( get_session_id() );
	session_start();
	// Ask for Login
	$GLOBALS['mainframe']->setPageTitle( ext_Lang::msg('actlogin') );
	$GLOBALS['mainframe']->addcustomheadtag( '
		<script type="text/javascript" src="'. _EXT_URL . '/fetchscript.php?'
			.'subdir[0]=scripts/extjs3/adapter/ext/&amp;file[0]=ext-base.js'
			.'&amp;subdir[1]=scripts/extjs3/&amp;file[1]=ext-all.js&amp;gzip=1"></script>
		<script type="text/javascript" src="'. $GLOBALS['script_name'].'?option=com_extplorer&amp;action=include_javascript&amp;file=functions.js"></script>
		<link rel="stylesheet" href="'. _EXT_URL . '/fetchscript.php?subdir[0]=scripts/extjs3/resources/css/&file[0]=ext-all.css&amp;subdir[1]=scripts/extjs3/resources/css/&file[1]=xtheme-blue.css&amp;gzip=1" />');

			
			?>
		<div style="width: 400px;" id="formContainer">
			<div id="ext_logo" style="text-align:center;">
			<a href="http://extplorer.sourceforge.net" target="_blank">
				<img src="<?php echo _EXT_URL ?>/images/eXtplorer-horizontal2.png" align="middle" alt="eXtplorer Logo" style="border:none;" />
			</a>
			</div>
			<noscript>
				<div style="width:400px;text-align:center;">
					<h1>eXtplorer Login</h1>
					<p style="color:red;">Oh, Javascript is disabled!</p>
					<p>Find out <a target="_blank" href="https://www.google.com/adsense/support/bin/answer.py?hl=en&answer=12654">how you can enable Javascript in your browser.</a>
					</p>
				</div>
			</noscript>
			<div id="adminForm"></div>
			
	</div>
	<script type="text/javascript">
Ext.onReady( function() {
	var simple = new Ext.FormPanel(<?php $auth->onShowLoginForm() ?>);
	
	Ext.get( 'formContainer').center();
	Ext.get( 'formContainer').setTop(100);
	simple.getForm().findField('username').focus();
});
</script><?php
			define( '_LOGIN_REQUIRED', 1 );
		}
	


