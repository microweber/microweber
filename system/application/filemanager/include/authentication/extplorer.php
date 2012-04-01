<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: ftp_authentication.php 143 2009-04-01 18:48:16Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
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
 */
 
/**
 * This file handles ftp authentication
 *
 */
class ext_extplorer_authentication {
	function onAuthenticate($credentials, $options=null ) {
		// Check Login
		//------------------------------------------------------------------------------

		$data=find_user($credentials['username'], $credentials['password'] );
		if($data==NULL) {
			return false;
		}
		// 	Set Login
		$_SESSION['credentials_extplorer']['username']	= $data[0];
		$_SESSION['credentials_extplorer']['password']	= $data[1];
		$_SESSION['file_mode'] = 'extplorer';
		$GLOBALS["home_dir"]	= str_replace( '\\', '/', $data[2] );
		$GLOBALS["home_url"]	= $data[3];
		$GLOBALS["show_hidden"]	= $data[4];
		$GLOBALS["no_access"]	= $data[5];
		$GLOBALS["permissions"]	= $data[6];
		
		return true;
	}
	
	function onShowLoginForm() {
		?>
	{
		xtype: "form",
		<?php if(!ext_isXHR()) { ?>renderTo: "adminForm", <?php } ?>
		title: "<?php echo ext_Lang::msg('actlogin') ?>",
		id: "simpleform",
		labelWidth: 125, // label settings here cascade unless overridden
		url: "<?php echo basename( $GLOBALS['script_name']) ?>",
		frame: true,
		keys: {
		    key: Ext.EventObject.ENTER,
		    fn  : function(){
				if (simple.getForm().isValid()) {
					Ext.get( "statusBar").update( "Please wait..." );
					Ext.getCmp("simpleform").getForm().submit({
						reset: false,
						success: function(form, action) { location.reload() },
						failure: function(form, action) {
							if( !action.result ) return;
							Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
							Ext.get( 'statusBar').update( action.result.error );
							form.findField( 'password').setValue('');
							form.findField( 'username').focus();
						},
						scope: Ext.getCmp("simpleform").getForm(),
						params: {
							option: "com_extplorer", 
							action: "login",
							type : "extplorer"
						}
					});
    	        } else {
        	        return false;
            	}
            }
		},
		items: [{
            xtype:"textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscusername', true ) ?>",
			name: "username",
			width:175,
			allowBlank:false
		},{
			xtype:"textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscpassword', true ) ?>",
			name: "password",
			inputType: "password",
			width:175,
			allowBlank:false
		}, new Ext.form.ComboBox({
			
			fieldLabel: "<?php echo ext_Lang::msg( 'misclang', true ) ?>",
			store: new Ext.data.SimpleStore({
		fields: ['language', 'langname'],
		data :	[
		<?php 
		$langs = get_languages();
		$i = 0; $c = count( $langs );
		foreach( $langs as $language => $name ) {
			echo "['$language', '$name' ]";
		if( ++$i < $c ) echo ',';
		}
		?>
			]
	}),
			displayField:"langname",
			valueField: "language",
			value: "<?php echo ext_Lang::detect_lang() ?>",
			hiddenName: "lang",
			disableKeyFilter: true,
			editable: false,
			triggerAction: "all",
			mode: "local",
			allowBlank: false,
			selectOnFocus:true
		}),
		{
			xtype: "displayfield",
			id: "statusBar"
		}
		],
		buttons: [{
			text: "<?php echo ext_Lang::msg( 'btnlogin', true ) ?>", 
			type: "submit",
			handler: function() {
				Ext.get( "statusBar").update( "Please wait..." );
				Ext.getCmp("simpleform").getForm().submit({
					reset: false,
					success: function(form, action) { location.reload() },
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						Ext.get( 'statusBar').update( action.result.error );
						form.findField( 'password').setValue('');
						form.findField( 'username').focus();
					},
					scope: Ext.getCmp("simpleform").getForm(),
					params: {
						option: "com_extplorer", 
						action: "login",
						type : "extplorer"
					}
				});
			}
		},<?php if(!ext_isXHR()) { ?>
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
		} ?>
		]
	}
	
	<?php
	}
	function onLogout() {
		logout();
	}
} 
?>