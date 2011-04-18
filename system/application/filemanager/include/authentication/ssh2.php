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
 require_once(_EXT_PATH.'/libraries/SSH2.php');
/**
 * This file handles ftp authentication
 *
 */
class ext_ssh2_authentication {
	
    
	function onAuthenticate($credentials, $options=null ) {
		$ssh2_user = $credentials['username'];
		$ssh2_pass = $credentials['password'];

		if( $ssh2_user != '' || $ssh2_pass != '' ) {

			$ssh2_host = empty($_SESSION['ssh2_host']) ? extGetParam( $_POST, 'ssh2_host', 'localhost:22' ) : $_SESSION['ssh2_host'];
			$url = @parse_url( 'ssh2.sftp://' . $ssh2_host);
			if( empty( $url )) {
				ext_Result::sendResult('ssh2_authentication', false, 'Unable to parse the specified Host Name. Please use a hostname in this format: hostname:22' );
			}
			$port = empty($url['port']) ? 22 : $url['port'];
			
			$GLOBALS['FTPCONNECTION'] = new SFTPConnection();
			
			$res = $GLOBALS['FTPCONNECTION']->connect($url['host'], $port);
			
			if( PEAR::isError( $res )) {
				return $res;
			}
      
			$res = $GLOBALS['FTPCONNECTION']->login( $ssh2_user, $ssh2_pass );
				
			if( PEAR::isError( $res )) {
				return $res;
			}
			$_SESSION['credentials_ssh2']['username'] = $ssh2_user;
			$_SESSION['credentials_ssh2']['password'] = $ssh2_pass;
			$_SESSION['ssh2_host'] = $ssh2_host;
			$_SESSION['file_mode'] = 'ssh2';

			return true;
			

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
		dialogtitle: "<?php echo ext_Lang::msg('ssh2_header') ?>",
		title: "<?php echo ext_Lang::msg('ssh2_login_lbl') ?>",
		frame: true,
		keys: {
		    key: Ext.EventObject.ENTER,
		    fn : function(){
				if (Ext.getCmp("simpleform").getForm().isValid()) {
					statusBarMessage( '<?php echo ext_Lang::msg('ssh2_login_check', true ) ?>', true );
					Ext.getCmp("simpleform").getForm().submit({
						reset: false,
						success: function(form, action) { location.href = '<?php echo basename( $GLOBALS['script_name']) ?>?ssh2' },
						failure: function(form, action) {
							if( !action.result ) return;
							msgbox = Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
							msgbox.setIcon( Ext.MessageBox.ERROR );	
							statusBarMessage( action.result.error, false, false );
						},
						scope: Ext.getCmp("simpleform").getForm(),
						params: {
							option: "com_extplorer", 
							action: "ssh2_authentication"
						}
					});
    	        } else {
        	        return false;
            	}
            }
		},
		items: [{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg('username', true ) ?>",
			name: "username",
			width:175,
			allowBlank:false
		},{
			xtype: "textfield",
			fieldLabel: "<?php echo ext_Lang::msg('password', true ) ?>",
			name: "password",
			inputType: "password",
			width:175,
			allowBlank:false
		},{
			xtype: "combo",
			fieldLabel: "<?php echo ext_Lang::msg('ftp_hostname_port', true ) ?>",
			name: "ssh2_host",
			value: "<?php echo extGetParam($_SESSION,'ssh2_host') ?>",
			store: ["<?php echo implode('","', $GLOBALS['ext_conf']['remote_hosts_allowed'] )?>"],
			width:175,
			triggerAction: "all",
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
						msgbox = Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						msgbox.setIcon( Ext.MessageBox.ERROR );						
						Ext.get( 'statusBar').update( action.result.error );
					},
					scope: Ext.getCmp("simpleform").getForm(),
					params: {
						option: "com_extplorer", 
						action: "login",
						type : "ssh2"
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
		unset($_SESSION['credentials_ssh2']);
		unset($_SESSION['ssh2_host']);
		session_write_close();
		extRedirect( make_link(null, null, null, null, null, null, '&file_mode=file') );
	}
}
