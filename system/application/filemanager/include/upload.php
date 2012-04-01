<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: upload.php 185 2011-01-14 20:28:07Z soeren $
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
*/
/**
 * Uploads file(s)
 *
 */
class ext_Upload extends ext_Action {

	function execAction($dir) {

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('upload', false, ext_Lang::err('accessfunc'));
		}

		// Execute
		if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {
				
			if( isset($GLOBALS['__FILES']['Filedata'])) {
				// Re-Map the flash-uploaded file with the name "Filedata" to the "userfile" array
				$GLOBALS['__FILES']['userfile'] = array(
													'name' => array($GLOBALS['__FILES']['Filedata']['name']),
													'tmp_name' => array($GLOBALS['__FILES']['Filedata']['tmp_name']),
													'size' => array($GLOBALS['__FILES']['Filedata']['size']),
													'type' => array($GLOBALS['__FILES']['Filedata']['type']),
													'error' => array($GLOBALS['__FILES']['Filedata']['error'])
												);
			}
			$cnt=count($GLOBALS['__FILES']['userfile']['name']);
			$err=false;
			$err_available=isset($GLOBALS['__FILES']['userfile']['error']);

			// upload files & check for errors
			for($i=0;$i<$cnt;$i++) {
				$errors[$i]=NULL;
				$tmp = $GLOBALS['__FILES']['userfile']['tmp_name'][$i];
				$items[$i] = stripslashes($GLOBALS['__FILES']['userfile']['name'][$i]);
				if($err_available) $up_err = $GLOBALS['__FILES']['userfile']['error'][$i];
				else $up_err=(file_exists($tmp)?0:4);
				$abs = get_abs_item($dir,$items[$i]);

				if($items[$i]=="" || $up_err==4) continue;
				if($up_err==1 || $up_err==2) {
					$errors[$i]=ext_lang::err('miscfilesize');
					$err=true;	continue;
				}
				if($up_err==3) {
					$errors[$i]=ext_lang::err('miscfilepart');
					$err=true;	continue;
				}
				if(!@is_uploaded_file($tmp)) {
					$errors[$i]=ext_lang::err('uploadfile');
					$err=true;	continue;
				}
				if(@file_exists($abs) && empty( $_REQUEST['overwrite_files'])) {
					$errors[$i]=ext_lang::err('itemdoesexist');
					$err=true;	continue;
				}

				// Upload
				$ok = @$GLOBALS['ext_File']->move_uploaded_file($tmp, $abs);
				if($ok===false || PEAR::isError( $ok )) {
					$errors[$i]=ext_lang::err('uploadfile');
					if( PEAR::isError( $ok ) ) $errors[$i].= ' ['.$ok->getMessage().']';
					$err=true;	continue;
				}
				else {
					if( !ext_isFTPMode() ) {
						@$GLOBALS['ext_File']->chmod( $abs, 0644 );
					}
				}
			}

			if($err) {			// there were errors
				$err_msg="";
				for($i=0;$i<$cnt;$i++) {
					if($errors[$i]==NULL) continue;
					$err_msg .= $items[$i]." : ".$errors[$i]."\n";
				}
				ext_Result::sendResult('upload', false, $err_msg);
			}
		

			ext_Result::sendResult('upload', true, ext_Lang::msg('upload_completed'));
			return;
		}

	?>
{
	"xtype": "tabpanel",
	"stateId": "upload_tabpanel",
	"activeTab": "uploadform",
	"dialogtitle": "<?php echo ext_Lang::msg('actupload') ?>",		
	"stateful": "true",
	
	"stateEvents": ["tabchange"],
	"getState": function() { return {
					activeTab:this.items.indexOf(this.getActiveTab())
				};
	},
	"listeners": {	"resize": {
						"fn": function(panel) {	
							panel.items.each( function(item) { item.setHeight(500);return true } );								
						}
					}
					
	},
	"items": [

		{
			"xtype": "swfuploadpanel",
			"title": "<?php echo Ext_Lang::msg('flashupload') ?>",
			"height": "300",
			"id": "swfuploader", 
			viewConfig: {
        		forceFit: true
			},
			"listeners": {	"allUploadsComplete": {
								"fn": function(panel) {	
									datastore.reload();	
									panel.destroy();
									Ext.getCmp("dialog").destroy();
									statusBarMessage('<?php echo ext_Lang::msg('upload_completed', true ) ?>', false );								
								}
							}
							
			},
			// Uploader Params				
			"upload_url": "<?php 
				echo _EXT_URL .'/uploadhandler.php';
					?>",
			"post_params": { 
				"<?php echo session_name()?>": "<?php echo session_id() ?>",
				"<?php echo get_cfg_var ('session.name') ?>": "<?php echo session_id() ?>",
				"session_name": "<?php echo session_name()?>",
				"user_agent": "<?php echo addslashes( $_SERVER['HTTP_USER_AGENT'] ) ?>",
				"option": "com_extplorer", 
				"action": "upload", 
				"dir": datastore.directory, 
				"requestType": "xmlhttprequest",
				"confirm": "true"
			},
			
<?php
		if ( $_SERVER['SERVER_NAME'] == 'localhost' ) echo '"debug": "true",';
?>				
			"flash_url": "<?php echo _EXT_URL ?>/scripts/extjs3-ext/ux.swfupload/swfupload.swf",
			"prevent_swf_caching": "false",
			"file_size_limit": "<?php echo get_max_file_size() ?>B",
			// Custom Params
			"single_file_select": false, // Set to true if you only want to select one file from the FileDialog.
			"confirm_delete": false, // This will prompt for removing files from queue.
			"remove_completed": false // Remove file from grid after uploaded.
		},
	{
		"xtype": "form",
		"autoScroll": "true",
		"autoHeight": "true",
		"id": "uploadform",
		"fileUpload": true,
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"title": "<?php echo ext_Lang::msg('standardupload') ?>",
		"tooltip": "<?php echo ext_Lang::msg('max_file_size').' = <strong>'. ((get_max_file_size() / 1024) / 1024).' MB<\/strong><br \/>'
				.ext_Lang::msg('max_post_size').' = <strong>'. ((get_max_upload_limit() / 1024) / 1024).' MB<\/strong><br \/>';
				?>",
		"frame": true,
		"items": [
		{
			"xtype": "displayfield",
			"value": "<?php echo ext_Lang::msg('max_file_size').' = <strong>'. ((get_max_file_size() / 1024) / 1024).' MB<\/strong><br \/>'
				.ext_Lang::msg('max_post_size').' = <strong>'. ((get_max_upload_limit() / 1024) / 1024).' MB<\/strong><br \/>';
				?>"
		},
		<?php
		for($i=0;$i<7;$i++) {
			echo '{
				"xtype": "fileuploadfield",
				"fieldLabel": "'.ext_Lang::msg('file', true ).' '.($i+1).'",
				"id": "userfile'.$i.'",
				"name": "userfile['.$i.']",
				"width":275,
				"buttonOnly": false
			},';
		}
		?>
		{	"xtype": "checkbox",
			"fieldLabel": "<?php echo ext_Lang::msg('overwrite_files', true ) ?>",
			"name": "overwrite_files",
			"checked": true
		}],
		"buttons": [{
			"text": "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
			"handler": function() {
				statusBarMessage( '<?php echo ext_Lang::msg( 'upload_processing', true ) ?>', true );
				form = Ext.getCmp("uploadform").getForm();
				form.submit({
					//reset: true,
					reset: false,
					success: function(form, action) {
						datastore.reload();
						statusBarMessage( action.result.message, false, true );
						Ext.getCmp("dialog").destroy();
					},
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.MessageBox.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					"scope": form,
					// add some vars to the request, similar to hidden fields
					"params": {
						"option": "com_extplorer", 
						"action": "upload", 
						"dir": datastore.directory,
						"requestType": "xmlhttprequest",
						"confirm": "true"
					}
				});
			}
		}, {
			"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			"handler": function() { Ext.getCmp("dialog").destroy(); } 
		}]
	},
	{
	
		"xtype": "form",
		"id": "transferform",
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"hidden": "true",
		"title": "<?php echo ext_Lang::msg('acttransfer') ?>",
		"autoHeight": "true",
		"labelWidth": 225,
		"frame": true,
		"items": [
		<?php
			for($i=0;$i<7;$i++) {
				echo '{
					"xtype": "textfield",
					"fieldLabel": "'.ext_Lang::msg('url_to_file', true ).'",
					"name": "userfile['.$i.']",
					"width":275
				},';
			}
			?>
			{	"xtype": "checkbox",
				"fieldLabel": "<?php echo ext_Lang::msg('overwrite_files', true ) ?>",
				"name": "overwrite_files",
				"checked": true
			}
		],
		"buttons": [{
	
			"text": "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
			"handler": function() {
				statusBarMessage( '<?php echo ext_Lang::msg( 'transfer_processing', true ) ?>', true );
				transfer = Ext.getCmp("transferform").getForm();
				transfer.submit({
					//reset: true,
					reset: false,
					success: function(form, action) {
						datastore.reload();
						statusBarMessage( action.result.message, false, true );
						Ext.getCmp("dialog").destroy();
					},
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.MessageBox.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					scope: transfer,
					// add some vars to the request, similar to hidden fields
					params: {
						"option": "com_extplorer", 
						"action": "transfer", 
						"dir": datastore.directory,
						"confirm": 'true'
					}
				});
			}
		},{
			"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			"handler": function() { Ext.getCmp("dialog").destroy(); }
		}]
	}]
}

	<?php

	}
}
