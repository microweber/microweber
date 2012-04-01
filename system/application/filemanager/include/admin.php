<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: admin.php 176 2010-11-06 08:44:55Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
 * @license
 * @version $Id: admin.php 176 2010-11-06 08:44:55Z soeren $
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
 * Comment:
 * Administrative Functions
 * 
 *
 * 
/**
 * Creates a form to manage users + passwords
 *
 * @param boolean $admin
 * @param string $dir
 */
function admin($admin, $dir) {			
	if( $GLOBALS["permissions"] < 7 || $_SESSION['credentials_extplorer']['username'] == 'admin' && $_SESSION['credentials_extplorer']['password'] == extEncodePassword('admin') ) {
		$activeTab = '0';
	} else {
		$activeTab = '1';
	}
	?>
{
	"xtype": "tabpanel",
	"width": "450",
	"id": "dialog_tabpanel",
	"dialogtitle": "<?php echo ext_Lang::msg('actadmin') ?>",
	"listeners": {
		"afterrender": {
			fn: function(cmp) {
					cmp.activate(<?php echo $activeTab ?>);
			}
		}
	},
	"items":
	[{
		"xtype": "form",
		"id": "passform",
	"autoHeight": "true",
		"headerAsText": false,
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"title": "<?php echo ext_Lang::msg('actchpwd', true) ?>",
		"frame": true,
		"items": [{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscoldpass', true ) ?>",
			"name": "oldpwd",
			"inputType": "password",
			"allowBlank":false
		},
		{	"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscnewpass', true ) ?>",
			"name": "newpwd1",
			"hiddenName": "newpwd1",
			"inputType": "password",
			"allowBlank":false
		},
		{ 	"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscconfnewpass', true ) ?>",
			"name": "newpwd2",
			"hiddenName": "newpwd2",
			"inputType": "password",
			"allowBlank":false
		}],
		"buttons": [{
			"text": "<?php echo ext_Lang::msg( 'btnchange', true ) ?>", 
			"handler": function() {
						frm = Ext.getCmp("passform").getForm();
						if(frm.findField('newpwd1').getValue() != frm.findField('newpwd2').getValue() ) {
							Ext.Msg.alert("Error!", "<?php echo ext_Lang::msg('miscnopassmatch', true ); ?>");
							return false;
						}
						if(frm.findField('oldpwd').getValue() ==frm.findField('newpwd1').getValue()) {
							Ext.Msg.alert("Error!", "<?php echo ext_Lang::err('miscnopassdiff', true ); ?>");
							return false;
						}
						
						statusBarMessage( "Please wait...", true );
						frm.submit({
							//reset: true,
							reset: false,
							"success": function(form, action) {
								statusBarMessage( action.result.message, false, true );
								Ext.getCmp("dialog").destroy();
							},
							"failure": function(form, action) {
								if( !action.result ) return;
								Ext.MessageBox.alert("Error!", action.result.error);
								statusBarMessage( action.result.error, false, false );
							},
							"scope": Ext.getCmp("passform"),
							// add some vars to the request, similar to hidden fields
							"params": {
								option: "com_extplorer", 
								"action": "admin",
								"action2": "chpwd"
							}
						})
						}
			}]
			
	
	<?php
	if($admin) {
		?>
		},{
		"xtype": "form",
		"id": "userlist",
		"autoHeight": "true",
		"headerAsText": false,
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		title: "<?php echo ext_Lang::msg('actusers', true) ?>",
		
		"frame": true,
		"items": [{
		<?php 
		$cnt=count($GLOBALS["users"]);
		for($i=0;$i<$cnt;++$i) {

			// Username & Home dir:
			$user=$GLOBALS["users"][$i][0];	if(strlen($user)>15) $user=substr($user,0,12)."...";
			$home=$GLOBALS["users"][$i][2];	if(strlen($home)>30) $home=substr($home,0,27)."...";
			?>

			"xtype": "radio",
			"name": "nuser",
			"inputValue": "<?php echo $GLOBALS["users"][$i][0] ?>",
			"fieldLabel": "<?php echo $user ?>",
			"boxLabel": "<?php echo '<strong>Homedir:</strong> '.$home.'; '
					.($GLOBALS["users"][$i][4] ? $GLOBALS["messages"]["miscyesno"][2]:$GLOBALS["messages"]["miscyesno"][3]).'; '
					.$GLOBALS["users"][$i][6].'; '
					.($GLOBALS["users"][$i][7] ? $GLOBALS["messages"]["miscyesno"][2]:$GLOBALS["messages"]["miscyesno"][3]);
				?>"
			}
			<?php 
			echo $i+1<$cnt ? ', {' : '';
		}
		?>
			],
			"buttons": [{
		
				"text": "<?php echo ext_Lang::msg( 'btnadd', true ) ?>", 
				"handler": function() {
							Ext.Ajax.request( { url: "<?php echo basename($GLOBALS['script_name']) ?>",
								"params": { "option": "com_extplorer","action": "admin","action2": "adduser" },	
								"callback": function(oElement, bSuccess, oResponse) {
											if( !bSuccess ) {
												Ext.Msg.alert( "Ajax communication failure!");
											}
											if( oResponse && oResponse.responseText ) {
												try{ json = Ext.decode( oResponse.responseText );
													if( json.error && typeof json.error != 'xml' ) {
														Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", json.error );
														dialog.destroy();
														return false;
													}
												} catch(e) { return false; }
												
												Ext.getCmp("dialog_tabpanel").add( json );
												Ext.getCmp("dialog_tabpanel").activate(json.id);
												Ext.getCmp("dialog").syncSize();
											}
										  } 
							
							});
						}
			},
			{
				"text": "<?php echo ext_Lang::msg( 'btnedit', true ) ?>", 
				"handler": function() {
							frm =  Ext.getCmp("userlist").getForm();
							try {
								theUser = frm.findField(0).getGroupValue();
							} catch(e) {
								Ext.Msg.alert( "Error", "<?php echo ext_Lang::err('miscselitems', true ) ?>" );
								return;
							}
							Ext.Ajax.request( { url: "<?php echo basename($GLOBALS['script_name']) ?>",
								"params": { option: "com_extplorer","action": "admin","action2": "edituser","nuser":theUser },	
								"callback": function(oElement, bSuccess, oResponse) {
											if( !bSuccess ) {
												Ext.Msg.alert( "Ajax communication failure!");
											}
											if( oResponse && oResponse.responseText ) {
												try{ json = Ext.decode( oResponse.responseText );
													if( json.error && typeof json.error != 'xml' ) {
														Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", json.error );
														dialog.destroy();
														return false;
													}
												} catch(e) { return false; }
												
												Ext.getCmp("dialog_tabpanel").add( json );
												Ext.getCmp("dialog_tabpanel").activate(json.id);
												Ext.getCmp("dialog").syncSize();
											}
										  } 
							
							});
						}
			},
			{
				"text": "<?php echo ext_Lang::msg( 'btnremove', true ) ?>", 
				"handler": function() {
							frm =  Ext.getCmp("userlist").getForm();
							try {
								theUser = frm.findField(0).getGroupValue();
							} catch(e) {
								Ext.Msg.alert( "Error", "<?php echo ext_Lang::err('miscselitems', true ) ?>" );
								return;
							}
					
							Ext.Msg.confirm( "", String.format( "<?php echo ext_Lang::err('miscdeluser', true ) ?>", theUser ), function( btn ) {
								if( btn != 'yes') return;
								statusBarMessage( "Please wait...", true );
								frm.submit({
									"success": function(form, action) {
										statusBarMessage( action.result.message, false, true );
									},
									"failure": function(form, action) {
										if( !action.result ) return;
										Ext.MessageBox.alert("Error!", action.result.error);
										statusBarMessage( action.result.error, false, false );
									},
									"scope": Ext.getCmp("userlist").getForm(),
									// add some vars to the request, similar to hidden fields
									"params": {
										"option": "com_extplorer", 
										"action": "admin",
										"action2": "rmuser",
										"user": theUser
									}
								});
							});
						}
			}
		]

		<?php
	}
	?>

	}]
}
<?php
}
//------------------------------------------------------------------------------
function changepwd($dir) {			// Change Password
	$pwd=extEncodePassword(stripslashes($GLOBALS['__POST']["oldpwd"]));
	if($GLOBALS['__POST']["newpwd1"]!=$GLOBALS['__POST']["newpwd2"]) {
		ext_Result::sendResult('changepwd', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
	}

	$data=find_user($GLOBALS['__SESSION']['credentials_extplorer']['username'],$pwd);
	if($data==NULL) {
		ext_Result::sendResult('changepwd', false, $GLOBALS["error_msg"]["miscnouserpass"]);
	}

	$data[1]=extEncodePassword(stripslashes($GLOBALS['__POST']["newpwd1"]));
	if(!update_user($data[0],$data)) {
		ext_Result::sendResult('changepwd', false, $data[0].": ".$GLOBALS["error_msg"]["chpass"]);
	}
	require_once(_EXT_PATH.'/include/authentication/extplorer.php');
	$auth = new ext_extplorer_authentication();
	$auth->onAuthenticate(array('username'=>$data[0],'password'=>$data[1]));

	ext_Result::sendResult('changepwd', true, ext_Lang::msg('change_password_success'));
}
//------------------------------------------------------------------------------
function adduser($dir) {			// Add User
	if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {
		$user=stripslashes($GLOBALS['__POST']["nuser"]);
		if($user=="" || $GLOBALS['__POST']["home_dir"]=="") {
			ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscfieldmissed"]);
		}
		if($GLOBALS['__POST']["pass1"]!=$GLOBALS['__POST']["pass2"]) {
			ext_Result::sendResult('adduser', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
		}
		$data=find_user($user,NULL);
		if($data!=NULL) {
			ext_Result::sendResult('adduser', false, $user.": ".$GLOBALS["error_msg"]["miscuserexist"]);
		}

		$data=array($user,extEncodePassword(stripslashes($GLOBALS['__POST']["pass1"])),
			stripslashes($GLOBALS['__POST']["home_dir"]),stripslashes($GLOBALS['__POST']["home_url"]),
			$GLOBALS['__POST']["show_hidden"],stripslashes($GLOBALS['__POST']["no_access"]),
			$GLOBALS['__POST']["permissions"],$GLOBALS['__POST']["active"]);

		if(!add_user($data)) {
			ext_Result::sendResult('adduser', false, $user.": ".$GLOBALS["error_msg"]["adduser"]);
		}
		ext_Result::sendResult('adduser', true, $user.": The user has been added");
		return;
	}

	show_userform();

}
//------------------------------------------------------------------------------
function edituser($dir) {			// Edit User
	$user=stripslashes($GLOBALS['__POST']["nuser"]);
	$data=find_user($user,NULL);
	if($data==NULL) {
		ext_Result::sendResult('edituser', false, $user.": ".$GLOBALS["error_msg"]["miscnofinduser"]);
	}

	if($self=($user==$GLOBALS['__SESSION']['credentials_extplorer']['username'])) $dir="";

	if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {

		$nuser=stripslashes($GLOBALS['__POST']["nuser"]);
		if($nuser=="" || $GLOBALS['__POST']["home_dir"]=="") {
			ext_Result::sendResult('edituser', false, $GLOBALS["error_msg"]["miscfieldmissed"]);
		}
		if(isset($GLOBALS['__POST']["chpass"]) && $GLOBALS['__POST']["chpass"]=="true")	{
			if($GLOBALS['__POST']["pass1"]!=$GLOBALS['__POST']["pass2"]) ext_Result::sendResult('edituser', false, $GLOBALS["error_msg"]["miscnopassmatch"]);
			$pass=extEncodePassword(stripslashes($GLOBALS['__POST']["pass1"]));
		} else {
			$pass=$data[1];
		}

		if($self) $GLOBALS['__POST']["active"]=1;

		$data=array($nuser,$pass,stripslashes($GLOBALS['__POST']["home_dir"]),
			stripslashes($GLOBALS['__POST']["home_url"]),$GLOBALS['__POST']["show_hidden"],
			stripslashes($GLOBALS['__POST']["no_access"]),$GLOBALS['__POST']["permissions"],$GLOBALS['__POST']["active"]);

		if(!update_user($user,$data)) {
			ext_Result::sendResult('edituser', false, $user.": ".$GLOBALS["error_msg"]["saveuser"]);
		}
		/*if($self) {
			activate_user($nuser,NULL);
		}*/
		ext_Result::sendResult('edituser', true, $user.": ".ext_Lang::msg('User Profile has been updated'));
	}

	show_userform( $data);
}

function show_userform( $data = null ) {
	if( $data == null ) { $data = array('', '', '', '', '', '', '' ); }
	$formname = @$data[0] ? 'frmedituser' : 'frmadduser';
	?>
{
	"xtype": "form",
	"id" : "<?php echo $formname ?>",
	"renderTo": Ext.getCmp("dialog_tabpanel").getEl(),
	"hidden": true,
	"closable":true,
	"autoHeight": "true",
	"labelWidth": 125,
	"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
	"title": "<?php
		if( !empty( $data[0] )) {
			printf($GLOBALS["messages"]["miscedituser"],$data[0]);

		} else {
			echo $GLOBALS["messages"]["miscadduser"];
		}
		?>"	,
		
	items: [{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscusername', true ) ?>",
			"name": "nuser",
			"value": "<?php echo @$data[0] ?>",
			"width":175,
			"allowBlank":false
		},{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscconfpass', true ) ?>",
			"name": "pass1",
			"inputType": "password",
			"width":175
		},
		{	"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscconfnewpass', true ) ?>",
			"name": "pass2",
			"inputType": "password",
			"width":175
		},
		<?php
		if( !empty($data[0])) { ?>
			{	"xtype": "checkbox",
				"fieldLabel": "<?php echo ext_Lang::msg( 'miscchpass', true ) ?>",
				"name": "chpass",
				"hiddenValue": "true"
			},
			<?php 
		} ?>
		{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'mischomedir', true ) ?>",
			"name": "home_dir",
			"value": "<?php echo !empty($data[2]) ? $data[2] : $_SERVER['DOCUMENT_ROOT'] ?>",
			"width":175,
			"allowBlank":false
		},
		{ 	"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'mischomeurl', true ) ?>",
			"name": "home_url",
			"value": "<?php echo !empty($data[3]) ? $data[3] : $GLOBALS["home_url"] ?>",
			"width":175,
			"allowBlank":false
		},{
			"xtype": "combo",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscshowhidden', true ) ?>",
			"store": [
					["1", "<?php echo ext_Lang::msg( array('miscyesno' => 0), true ) ?>"],
					["0", "<?php echo ext_Lang::msg( array('miscyesno' => 1), true ) ?>"]
				   ],
			"hiddenName": "show_hidden",
			"disableKeyFilter": true,
			"value": "<?php echo ( !empty($data[4]) ? $data[4] : (int)$data[4] ) ?>",
			"editable": false,
			"triggerAction": "all",
			"mode": "local",
			"allowBlank": false,
			"selectOnFocus":true
		},
		{ 	"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'mischidepattern', true ) ?>",
			"name": "no_access",
			"value": "<?php echo @$data[5] ?>",
			"width":175,
			"allowBlank":true
		},
		{
			"xtype": "combo",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscperms', true ) ?>",
			"store": [<?php
						$permvalues = array(0,1,2,3,7);
						$permcount = count($GLOBALS["messages"]["miscpermnames"]);
						for($i=0;$i<$permcount;++$i) {
							if( $permvalues[$i]==7) $index = 4;
							else $index = $i;
							echo '["'.$permvalues[$i].'", "'.ext_lang::msg( array('miscpermnames' => $index)).'" ]'."\n";
							if( $i+1<$permcount) echo ',';
						}
						?>
					],
			"hiddenName": "permissions",
			"disableKeyFilter": true,
			"value": "<?php echo (int)@$data[6] ?>",
			"editable": false,
			"triggerAction": "all",
			"mode": "local"
		},
		{ 	"xtype": "combo",
			"fieldLabel": "<?php echo ext_Lang::msg( 'miscactive', true ) ?>",
			"store": [
					["1", "<?php echo ext_Lang::msg( array('miscyesno' => 0), true ) ?>"],
					["0", "<?php echo ext_Lang::msg( array('miscyesno' => 1), true ) ?>"]
				   ],
			"hiddenName": "active",
			"disableKeyFilter": true,
			"value": "<?php echo ( !empty($data[7]) ? $data[7] : 0 ) ?>",
			"disabled": <?php echo !empty($self) ? 'true' : 'false' ?>,
			"editable": false,
			"triggerAction": "all",
			"mode": "local",
			"allowBlank": false,
			"selectOnFocus":true
		}
	],
	
	"buttons": [ {
		"text": "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
		"handler": function() {
					userform = Ext.getCmp("<?php echo $formname ?>").getForm();
					if(userform.findField('nuser').getValue()=="" || userform.findField('home_dir').getValue()=="") {
						Ext.Msg.alert('Status', "<?php echo ext_Lang::err('miscfieldmissed', true ); ?>");
						return false;
					}
					if( userform.findField('chpass') ) {
						if(userform.findField('chpass').getValue() &&
							userform.findField('pass1').getValue() != userform.findField('pass2').getValue())
						{
							Ext.Msg.alert('Status', "<?php echo ext_Lang::err('miscnopassmatch', true ); ?>");
							return false;
						}
					}
					statusBarMessage( 'Please wait...', true );
					userform.submit({
						"success": function(form, action) {
							statusBarMessage( action.result.message, false, true );
							Ext.getCmp("dialog_tabpanel").remove("<?php echo $formname ?>");
						},
						"failure": function(form, action) {
							if( !action.result ) return;
							Ext.Msg.alert('Error!', action.result.error);
							statusBarMessage( action.result.error, false, true );
						},
						"scope": userform,
						// add some vars to the request, similar to hidden fields
						"params": {option: 'com_extplorer', 
								user: "<?php echo @$data[0] ?>",
								"action": 'admin', 
								"action2": "<?php echo @$data[0] ? 'edituser' : 'adduser' ?>",
								"confirm": "true"
						}
					})
				}
	},{
		"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
		"handler": function() { Ext.getCmp("dialog_tabpanel").remove("<?php echo $formname ?>"); }
	}]
}
	<?php
}
//------------------------------------------------------------------------------
function removeuser($dir) {			// Remove User
	$user=stripslashes($GLOBALS['__POST']["user"]);
	if($user==$GLOBALS['__SESSION']['credentials_extplorer']['username']) {
		ext_Result::sendResult('removeuser', false, $GLOBALS["error_msg"]["miscselfremove"]);
	}
	if(!remove_user($user)) {
		ext_Result::sendResult('removeuser', false, $user.": ".$GLOBALS["error_msg"]["deluser"]);
	}
	ext_Result::sendResult('removeuser', true, $user." was successfully removed." );

}
//------------------------------------------------------------------------------
function show_admin($dir) {			// Execute Admin Action
	$pwd=(($GLOBALS["permissions"]&2)==2);
	$admin=(($GLOBALS["permissions"]&4)==4);

	if(!$GLOBALS["require_login"]) ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["miscnofunc"]);
	if(!$pwd && !$admin) ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]);

	if(isset($GLOBALS['__GET']["action2"])) $action2 = $GLOBALS['__GET']["action2"];
	elseif(isset($GLOBALS['__POST']["action2"])) $action2 = $GLOBALS['__POST']["action2"];
	else $action2="";

	switch($action2) {
	case "chpwd":
		changepwd($dir);
	break;
	case "adduser":
		if(!$admin) ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]);
		adduser($dir);
	break;
	case "edituser":
		if(!$admin) ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]);
		edituser($dir);
	break;
	case "rmuser":
		if(!$admin) ext_Result::sendResult('admin', false, $GLOBALS["error_msg"]["accessfunc"]);
		removeuser($dir);
	break;
	default:
		admin($admin,$dir);
	}
}
//------------------------------------------------------------------------------

