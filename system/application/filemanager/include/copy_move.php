<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: copy_move.php 176 2010-11-06 08:44:55Z soeren $
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
 *
 */

/**
 * File/Directory Copy & Move Functions
 */
function copy_move_items($dir) {		// copy/move file/dir
	$action = extGetParam( $_REQUEST, 'action' );
	if(($GLOBALS["permissions"]&01)!=01){
		ext_Result::sendResult( $action, false, $GLOBALS["error_msg"]["accessfunc"]);
	}

	// Vars
	$first = extGetParam($GLOBALS['__POST'], 'first' );
	if($first=="y") $new_dir=$dir;
	else $new_dir = stripslashes($GLOBALS['__POST']["new_dir"]);
	if($new_dir==".") $new_dir="";
	$cnt=count($GLOBALS['__POST']["selitems"]);

	if (!$new_dir) {
	    copy_move_dialog($dir);
	    return;
	}

	// DO COPY/MOVE

	// ALL OK?
	if(!@$GLOBALS['ext_File']->file_exists(get_abs_dir($new_dir))) {
		ext_Result::sendResult( $action, false, get_abs_dir($new_dir).": ".$GLOBALS["error_msg"]["targetexist"]);
	}
	if(!get_show_item($new_dir,"")) {
		ext_Result::sendResult( $action, false, $new_dir.": ".$GLOBALS["error_msg"]["accesstarget"]);
	}
	if(!down_home(get_abs_dir($new_dir))) {
		ext_Result::sendResult( $action, false, $new_dir.": ".$GLOBALS["error_msg"]["targetabovehome"]);
	}

	// copy / move files
	$err=false;
	for($i=0;$i<$cnt;++$i) {
		$tmp = basename(stripslashes($GLOBALS['__POST']["selitems"][$i]));
		$new = basename(stripslashes($GLOBALS['__POST']["selitems"][$i]));

		if( ext_isFTPMode() ) {
			$abs_item = get_item_info($dir,$tmp);
			$abs_new_item = $new_dir.'/'.$new;
		} else {
			$abs_item = get_abs_item($dir,$tmp);
			$abs_new_item = get_abs_item($new_dir,$new);
		}

		$items[$i] = $tmp;

		// Check
		if($new=="") {
			$error[$i]= $GLOBALS["error_msg"]["miscnoname"];
			$err=true;	continue;
		}
		if(!@$GLOBALS['ext_File']->file_exists($abs_item)) {
			$error[$i]= $GLOBALS["error_msg"]["itemexist"];
			$err=true;	continue;
		}
		if(!get_show_item($dir, $tmp)) {
			$error[$i]= $GLOBALS["error_msg"]["accessitem"];
			$err=true;	continue;
		}
		if(@$GLOBALS['ext_File']->file_exists($abs_new_item)) {
			$error[$i]= $GLOBALS["error_msg"]["targetdoesexist"];
			$err=true;	continue;
		}

		// Copy / Move
		if($action=="copy") {
			if(@is_link($abs_item) || get_is_file($abs_item)) {
				// check file-exists to avoid error with 0-size files (PHP 4.3.0)
				if( ext_isFTPMode() ) $abs_item = '/'.$dir.'/'.$abs_item['name'];
				$ok=@$GLOBALS['ext_File']->copy( $abs_item ,$abs_new_item); //||@file_exists($abs_new_item);

			}
			elseif(@get_is_dir($abs_item)) {
				$copy_dir = ext_isFTPMode() ? '/'.$dir.'/'.$abs_item['name'].'/' : $abs_item;
				if( ext_isFTPMode() ) $abs_new_item .= '/';

				$ok=$GLOBALS['ext_File']->copy_dir( $copy_dir, $abs_new_item);
			}
		}
		else {
			$ok= $GLOBALS['ext_File']->rename($abs_item,$abs_new_item);
		}

		if($ok===false || PEAR::isError( $ok ) ) {
			$error[$i]=($action=="copy"?
							$GLOBALS["error_msg"]["copyitem"]:
							$GLOBALS["error_msg"]["moveitem"]
						);
			if( PEAR::isError( $ok ) ) {
				$error[$i].= ' ['.$ok->getMessage().']';
			}
			$err=true;	continue;
		}

		$error[$i]=NULL;
	}

	if($err) {			// there were errors
		$err_msg="";
		for($i=0;$i<$cnt;++$i) {
			if($error[$i]==NULL) continue;

			$err_msg .= $items[$i]." : ".$error[$i]."\n";
		}
		ext_Result::sendResult( $action, false, $err_msg);
	}

	ext_Result::sendResult( $action, true, 'The File(s)/Directory(s) were successfully '.($action=='copy'?'copied':'moved').'.' );
}

function copy_move_dialog($dir='') {
    $action = extGetParam( $_REQUEST, 'action' );
    ?>
{
	"xtype": "form",
	"id": "simpleform",
	"labelWidth": 125,
	"width": "340",
	"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
	"dialogtitle": "<?php echo 'Copy/Move' ?>",
	"frame": true,
	"items": [{
		"xtype": "textfield",
        "fieldLabel": "Destination",
        "name": "new_dir",
        "value": "<?php echo $dir ?>/",
        "width":175,
        "allowBlank":false
    }],
    "buttons": [{
    	text: '<?php echo ext_Lang::msg( 'btncreate', true ) ?>', 
    	handler: function() {
    		form =  Ext.getCmp('simpleform').getForm();
			statusBarMessage( 'Please wait...', true );
		    var requestParams = getRequestParams();
		    requestParams.confirm = 'true';
		    requestParams.action  = '<?php echo $action ?>';
		    form.submit({
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
					Ext.MessageBox.alert('Error!', action.result.error);
					statusBarMessage( action.result.error, false, false );
		        },
		        scope: form,
		        // add some vars to the request, similar to hidden fields
		        params: requestParams
		    });
		  }
	},{
		text: '<?php echo ext_Lang::msg( 'btncancel', true ) ?>', 
		handler: function() { Ext.getCmp("dialog").destroy(); }
	}
	]
}
	<?php
}
?>