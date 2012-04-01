<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: ftp_authentication.php 143 2009-04-01 18:48:16Z soeren $
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
	function onAuthenticate($credentials, $options=null ) {
		$ftp_login = $credentials['username'];
		$ftp_pass = $credentials['password'];

		if( $ftp_login != '' || $ftp_pass != '' ) {

			$ftp_host = empty($_SESSION['ftp_host']) ? extGetParam( $_POST, 'ftp_host', 'localhost:21' ) : $_SESSION['ftp_host'];
			$url = @parse_url( 'ftp://' . $ftp_host);
			if( empty( $url )) {
				ext_Result::sendResult('ftp_authentication', false, 'Unable to parse the specified Host Name. Please use a hostname in this format: hostname:21' );
			}
			$port = empty($url['port']) ? 21 : $url['port'];
			
			$GLOBALS['FTPCONNECTION'] = new Net_FTP( $url['host'], $port, 20 );

			$res = $GLOBALS['FTPCONNECTION']->connect();
			if( PEAR::isError( $res )) {
				ext_Result::sendResult('ftp_authentication', false, ext_Lang::msg('ftp_connection_failed').' ('.$url['host'].')' );
			}
			else {
				$res = $GLOBALS['FTPCONNECTION']->login( $ftp_login, $ftp_pass );
				
				if( PEAR::isError( $res )) {
					ext_Result::sendResult('ftp_authentication', false, ext_Lang::msg('ftp_login_failed') );

				}

				$_SESSION['credentials_ftp']['username'] = $ftp_login;
				$_SESSION['credentials_ftp']['password'] = $ftp_pass;
				$_SESSION['ftp_host'] = $ftp_host;
				$_SESSION['file_mode'] = 'ftp';

				return true;
			}

		}
		return false;
	}
	function onShowLoginForm() {
		
			?>
	{
		xtype: "form",
		<?php if(!ext_isXHR()) { ?>renderTo: "adminForm", <?php } ?>
		id: "simpleform",
		labelWidth: 125,
		url:"<?php echo basename( $GLOBALS['script_name']) ?>",
		dialogtitle: "<?php echo ext_Lang::msg('ftp_header') ?>",
		title: "<?php echo ext_Lang::msg('ftp_login_lbl') ?>",
		frame: true,
		keys: {
		    key: Ext.EventObject.ENTER,
		    fn : function(){
				if (Ext.getCmp("simpleform").getForm().isValid()) {
					Ext.get( 'statusBar').update( '<?php echo ext_Lang::msg('ftp_login_check', true ) ?>' );
					Ext.getCmp("simpleform").getForm().submit({
						reset: false,
						success: function(form, action) { location.reload() },
						failure: function(form, action) {
							if( !action.result ) return;
							Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
							Ext.get( 'statusBar').update( action.result.error );
						},
						scope: Ext.getCmp("simpleform").getForm(),
						params: {
							option: "com_extplorer", 
							action: "login",
							type: "ftp",
							file_mode: "ftp"
						}
					});
    	        } else {
        	        return false;
            	}
            }
		},
		items: [{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg('ftp_login_name', true ) ?>",
			name: "username",
			width:175,
			allowBlank:false
		},{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg('ftp_login_pass', true ) ?>",
			name: "password",
			inputType: "password",
			width:175,
			allowBlank:false
		},{
			xtype: "combo",
			fieldLabel: "<?php echo ext_Lang::msg('ftp_hostname_port', true ) ?>",
			hiddenName: "ftp_host",
			triggerAction: "all",
			value: "<?php echo extGetParam($_SESSION,'ftp_host') ?>",
			store: ["<?php echo implode('","', $GLOBALS['ext_conf']['remote_hosts_allowed'] )?>"],
			width:175,
			editable: false,
			forceSelection: true,
			allowBlank:false
		},
		{
			xtype: "displayfield",
			id: "statusBar"
		}],
		buttons: [{
			text: "<?php echo ext_Lang::msg( 'btnlogin', true ) ?>", 
			type: "submit",
			handler: function() {
				Ext.get( 'statusBar').update( '<?php echo ext_Lang::msg('ftp_login_check', true ) ?>' );
				Ext.getCmp("simpleform").getForm().submit({
					reset: false,
					success: function(form, action) { location.reload() },
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						Ext.get( 'statusBar').update( action.result.error );
					},
					scope: Ext.getCmp("simpleform").getForm(),
					params: {
						option: "com_extplorer", 
						action: "login",
						type: "ftp",
						file_mode: "ftp"
					}
				});
			}
		},
		<?php if(!ext_isXHR()) { ?>
		{
			text: '<?php echo ext_Lang::msg( 'btnreset', true ) ?>', 
			handler: function() { simple.getForm().reset(); } 
		}
		<?php 
		} else {?>
		{
			text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			handler: function() { Ext.getCmp("dialog").destroy(); }
		}
		<?php 
		} ?>]
	}
		<?php
	}
	function onLogout() {
		unset($_SESSION['credentials_ftp']);
		unset($_SESSION['ftp_host']);
		session_write_close();
		extRedirect( make_link(null, null, null, null, null, null, '&file_mode=extplorer') );
	}
}
