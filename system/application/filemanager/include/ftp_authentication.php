<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: ftp_authentication.php 164 2010-05-03 15:06:51Z soeren $
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
 * This file handles ftp authentication
 *
 */
class ext_ftp_authentication {
	function execAction() {
		$ftp_login = extGetParam( $_POST, 'ftp_login_name', '' );
		$ftp_pass = extGetParam( $_POST, 'ftp_login_pass', '' );
		global $dir, $mosConfig_live_site;

		if( $ftp_login != '' || $ftp_pass != '' ) {

			$ftp_host = extGetParam( $_POST, 'ftp_hostname_port', 'localhost:21' );
			$url = @parse_url( 'ftp://' . $ftp_host);
			if( empty( $url )) {
				ext_Result::sendResult('ftp_authentication', false, 'Unable to parse the specified Host Name. Please use a hostname in this format: hostname:21' );
			}
			$port = empty($url['port']) ? 21 : $url['port'];
			$ftp = new Net_FTP( $url['host'], $port, 20 );

			$res = $ftp->connect();
			if( PEAR::isError( $res )) {
				ext_Result::sendResult('ftp_authentication', false, $GLOBALS['messages']['ftp_connection_failed'].' ('.$url['host'].')' );
			}
			else {
				$res = $ftp->login( $ftp_login, $ftp_pass );
				$ftp->disconnect();
				if( PEAR::isError( $res )) {
					ext_Result::sendResult('ftp_authentication', false, $GLOBALS['messages']['ftp_login_failed'] );

				}

				$_SESSION['ftp_login'] = $ftp_login;
				$_SESSION['ftp_pass'] = $ftp_pass;
				$_SESSION['ftp_host'] = $ftp_host;
				$_SESSION['file_mode'] = 'ftp';

				session_write_close();

				ext_Result::sendResult('ftp_authentication', true, ext_Lang::msg('actlogin_success') );
			}

		}
		else {
			?>
	{
		"xtype": "form",
		"id": "simpleform",
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"dialogtitle": "<?php echo $GLOBALS["messages"]["ftp_header"] ?>",
		"title": "<?php echo $GLOBALS["messages"]["ftp_login_lbl"] ?>",
		"frame": true,
		"keys": {
		    "key": Ext.EventObject.ENTER,
		    "fn" : function(){
				if (Ext.getCmp("simpleform").getForm().isValid()) {
					statusBarMessage( '<?php echo ext_Lang::msg('ftp_login_check', true ) ?>', true );
					Ext.getCmp("simpleform").getForm().submit({
						"reset": false,
						"success": function(form, action) { location.reload() },
						"failure": function(form, action) {
							if( !action.result ) return;
							Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
							statusBarMessage( action.result.error, false, false );
						},
						"scope": Ext.getCmp("simpleform").getForm(),
						"params": {
							"option": "com_extplorer", 
							"action": "ftp_authentication"
						}
					});
    	        } else {
        	        return false;
            	}
            }
		},
		"items": [{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('ftp_login_name', true ) ?>",
			"name": "ftp_login_name",
			"width":175,
			"allowBlank":false
		},{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('ftp_login_pass', true ) ?>",
			"name": "ftp_login_pass",
			"inputType": "password",
			"width":175,
			"allowBlank":false
		},{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('ftp_hostname_port', true ) ?>",
			"name": "ftp_hostname_port",
			"value": "<?php echo extGetParam($_SESSION,'ftp_host', 'localhost:21') ?>",
			"width":175,
			"allowBlank":false
		}],
		"buttons": [{
			"text": "<?php echo ext_Lang::msg( 'btnlogin', true ) ?>", 
			"type": "submit",
			"handler": function() {
				statusBarMessage( '<?php echo ext_Lang::msg('ftp_login_check', true ) ?>', true );
				Ext.getCmp("simpleform").getForm().submit({
					"reset": false,
					"success": function(form, action) { location.reload() },
					"failure": function(form, action) {
						if( !action.result ) return;
						Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					"scope": Ext.getCmp("simpleform").getForm(),
					"params": {
						"option": "com_extplorer", 
						"action": "ftp_authentication"
					}
				});
			}
		},{
			"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			"handler": function() { Ext.getCmp("dialog").destroy(); }
		}]
	}
		<?php
		}
	}
}
function ftp_logout() {
	unset($_SESSION['ftp_login']);
	unset($_SESSION['ftp_pass']);
	unset($_SESSION['ftp_host']);
	session_write_close();
	extRedirect( make_link(null, null, null, null, null, null, '&file_mode=file') );
}