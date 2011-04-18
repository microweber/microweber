<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: mkitem.php 157 2009-11-09 15:10:45Z soeren $
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
 */
/**
 * Allows to create dirs, files and symlinks on a server
 *
 */
class ext_Mkitem extends ext_Action {

	function execAction($dir) {		// make new directory or file
		if(($GLOBALS["permissions"]&01)!=01) ext_Result::sendResult( 'mkitem', false, $GLOBALS["error_msg"]["accessfunc"]);

		if( extGetParam($_POST,'confirm') == 'true') {
			$mkname=$GLOBALS['__POST']["mkname"];
			$mktype=$GLOBALS['__POST']["mktype"];
			$symlink_target = $GLOBALS['__POST']['symlink_target'];

			$mkname=basename(stripslashes($mkname));
			if($mkname=="") ext_Result::sendResult( 'mkitem', false,  $GLOBALS["error_msg"]["miscnoname"] );

			$new = get_abs_item($dir,$mkname);

			if(@$GLOBALS['ext_File']->file_exists($new)) {
				ext_Result::sendResult( 'mkitem', false, $mkname.": ".$GLOBALS["error_msg"]["itemdoesexist"]);
			}
			$err = print_r( $_POST, true );
			if($mktype=="dir") {
				$ok=@$GLOBALS['ext_File']->mkdir($new, 0777);
				$err=$GLOBALS["error_msg"]["createdir"];
			} elseif( $mktype == 'file') {
				$ok=@$GLOBALS['ext_File']->mkfile($new);
				$err=$GLOBALS["error_msg"]["createfile"];
			} elseif( $mktype == 'symlink' ) {
				if( empty( $symlink_target )) {
					ext_Result::sendResult( 'mkitem', false, 'Please provide a valid <strong>target</strong> for the symbolic link.');
				}
				if( !file_exists($symlink_target) || !is_readable($symlink_target)) {
					ext_Result::sendResult( 'mkitem', false, 'The file you wanted to make a symbolic link to does not exist or is not accessible by PHP.');
				}
				$ok = symlink( $symlink_target, $new );
				$err = 'The symbolic link could not be created.';
			}

			if($ok==false || PEAR::isError( $ok )) {
				if( PEAR::isError( $ok ) ) $err.= $ok->getMessage();
				ext_Result::sendResult( 'mkitem', false, $err);
			}
			ext_Result::sendResult( 'mkitem', true, 'The item '.$new.' was created' );

			return;
		}
	?>
		{
		"xtype": "form",
		"id": "simpleform",
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"dialogtitle": "Create New File/Directory",
		"frame": true,
		"items": [{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( "nameheader", true ) ?>",
			"name": "mkname",
			"width":175,
			"allowBlank":false
			},{
			"xtype": "combo",
			"fieldLabel": "Type",
			"store": [["file", "<?php echo ext_Lang::mime( 'file', true ) ?>"],
						["dir", "<?php echo ext_Lang::mime( 'dir', true ) ?>"]
						<?php
						if( !ext_isFTPMode() && !$GLOBALS['isWindows']) { ?>
						,["symlink", "<?php echo ext_Lang::mime( 'symlink', true ) ?>"]
						<?php
						} ?>
					],
			displayField:"type",
			valueField: "mktype",
			value: "file",
			hiddenName: "mktype",
			disableKeyFilter: true,
			editable: false,
			triggerAction: "all",
			mode: "local",
			allowBlank: false,
			selectOnFocus:true
		},{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg( 'symlink_target', true ) ?>",
			"name": "symlink_target",
			"width":175,
			"allowBlank":true
		}],
		"buttons": [{
			"text": "<?php echo ext_Lang::msg( 'btncreate', true ) ?>", 
			"handler": function() {
				statusBarMessage( "Please wait...", true );
				Ext.getCmp("simpleform").getForm().submit({
					//reset: true,
					reset: false,
					success: function(form, action) {
						statusBarMessage( action.result.message, false, true );
						try{ 
							dirTree.getSelectionModel().getSelectedNode().reload(); 
						} catch(e) {}
						datastore.reload();
						Ext.getCmp("dialog").destroy();
					},
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.Msg.alert("Error!", action.result.error);
						statusBarMessage( action.result.error, false, false );
					},
					scope: Ext.getCmp("simpleform"),
					// add some vars to the request, similar to hidden fields
					params: {option: "com_extplorer", 
							action: "mkitem", 
							dir: datastore.directory, 
							confirm: "true"}
				})
			}
		},{
			"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			"handler": function() { Ext.getCmp("dialog").destroy(); }
		}]
	}
	<?php
	}
}

//------------------------------------------------------------------------------
