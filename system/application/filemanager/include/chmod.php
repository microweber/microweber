<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: chmod.php 163 2009-12-08 01:58:11Z soeren $
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
 * Permission-Change Functions
 *
 */
class ext_Chmod extends ext_Action {
	function execAction($dir, $item) {		// change permissions

		if(($GLOBALS["permissions"]&01)!=01) ext_Result::sendResult( 'chmod', false, $GLOBALS["error_msg"]["accessfunc"]);

		if( !empty($GLOBALS['__POST']["selitems"])) {
			$cnt=count($GLOBALS['__POST']["selitems"]);

		}
		else {
			$GLOBALS['__POST']["selitems"][]  = $item;
			$cnt = 1;
		}
		if( !empty($GLOBALS['__POST']['do_recurse'])) {
			$do_recurse = true;
		}
		else {
			$do_recurse = false;
		}

		// Execute
		if(isset($GLOBALS['__POST']["confirm"]) && $GLOBALS['__POST']["confirm"]=="true") {
			$bin='';
			for($i=0;$i<3;$i++) for($j=0;$j<3;$j++) {
				$tmp="r_".$i.$j;
				if(!empty($GLOBALS['__POST'][$tmp]) ) {
					$bin.='1';
				} else {
					$bin.='0';
				}
			}
			if( $bin == '0') { // Changing permissions to "none" is not allowed
				ext_Result::sendResult('chmod', false, $item.": ".ext_Lang::err('chmod_none_not_allowed'));
			}
			$old_bin = $bin;
			for($i=0;$i<$cnt;++$i) {
				if( ext_isFTPMode() ) {
					$mode = decoct(bindec($bin));
				} else {
					$mode = bindec($bin);
				}
				$item = $GLOBALS['__POST']["selitems"][$i];
				if( ext_isFTPMode() ) {
					$abs_item = get_item_info( $dir,$item);
				} else {
					$abs_item = get_abs_item($dir,$item);
				}
				if(!$GLOBALS['ext_File']->file_exists( $abs_item )) {
					ext_Result::sendResult('chmod', false, $item.": ".$GLOBALS["error_msg"]["fileexist"]);
				}
				if(!get_show_item($dir, $item)) {
					ext_Result::sendResult('chmod', false, $item.": ".$GLOBALS["error_msg"]["accessfile"]);
				}
				if( $do_recurse ) {
					$ok = $GLOBALS['ext_File']->chmodRecursive( $abs_item, $mode );
				}
				else {
					if( get_is_dir( $abs_item )) {
						// when we chmod a directory we must care for the permissions
						// to prevent that the directory becomes not readable (when the "execute bits" are removed)
						$bin = substr_replace( $bin, '1', 2, 1 ); // set 1st x bit to 1
						$bin = substr_replace( $bin, '1', 5, 1 );// set  2nd x bit to 1
						$bin = substr_replace( $bin, '1', 8, 1 );// set 3rd x bit to 1
						if( ext_isFTPMode() ) {
							$mode = decoct(bindec($bin));
						} else {
							$mode = bindec($bin);
						}
					}
					//ext_Result::sendResult('chmod', false, $GLOBALS['FTPCONNECTION']->pwd());
					$ok = @$GLOBALS['ext_File']->chmod( $abs_item, $mode );
				}

				$bin = $old_bin;
			}

			if($ok===false || PEAR::isError( $ok ) ) {
				$msg = $item.": ".$GLOBALS["error_msg"]["permchange"];
				$msg .= PEAR::isError( $ok ) ? ' [' . $ok->getMessage().']' : '';
				ext_Result::sendResult('chmod', false, $msg );
			}
			ext_Result::sendResult('chmod', true, ext_Lang::msg('permchange') );
			return;
		}
		if( ext_isFTPMode() ) {
			$abs_item = get_item_info( $dir, $GLOBALS['__POST']["selitems"][0]);
		} else {
			$abs_item = get_abs_item( $dir, $GLOBALS['__POST']["selitems"][0]);
			$abs_item = utf8_decode($abs_item);
		}

		$mode = parse_file_perms(get_file_perms( $abs_item ));
		if($mode===false) {
			ext_Result::sendResult('chmod', false, $item.": ".$GLOBALS["error_msg"]["permread"]);
		}
		$pos = "rwx";
		$text = "";
		for($i=0;$i<$cnt;++$i) {
			$s_item=get_rel_item($dir,$GLOBALS['__POST']["selitems"][$i]);
			if(strlen($s_item)>50) $s_item="...".substr($s_item,-47);
			$text .= $s_item.($i+1<$cnt ? ', ':'');
		}
		?>
		{
		"xtype": "form",
		"id": "simpleform",
		"width": "300",
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"dialogtitle": "<?php echo ext_Lang::msg('actperms') ?>",
		"title" : "<?php echo $text  ?>",
		"frame": true,
		"items": [{
			"layout": "column",
			"items": [{
	<?php
		// print table with current perms & checkboxes to change
		for($i=0;$i<3;++$i) {
			?>
			"width":80, 
			"title":"<?php echo ext_Lang::msg(array('miscchmod'=> $i ), true ) ?>",					
			"items": [{
				<?php
				for($j=0;$j<3;++$j) {
					?>
					"xtype": "checkbox",
					"boxLabel":"<?php echo $pos{$j}  ?>",
					<?php if($mode{(3*$i)+$j} != "-") echo '"checked":true,' ?>
						"name":"<?php echo "r_". $i.$j ?>"
					}	<?php
					if( $j<2 ) echo ',{';
				}
				?>	
				]
			}
		<?php 
			if( $i<2 ) echo ',{';
		}
		?>,{
			"width":400, 
			"style":"margin-left:10px", 
			"clear":true,
			"html": "&nbsp;"
		}]

	},{
		"xtype": "checkbox",
		"fieldLabel":"<?php echo ext_Lang::msg('recurse_subdirs', true ) ?>",
		"name":"do_recurse"
	}],
	"buttons": [{
		"text": "<?php echo ext_Lang::msg( 'btnsave', true ) ?>", 
		"handler": function() {
			statusBarMessage( '<?php echo ext_Lang::msg( 'permissions_processing', true ) ?>', true );
			form = Ext.getCmp("simpleform").getForm();
			form.submit({
				//reset: true,
				reset: false,
				success: function(form, action) {
					statusBarMessage( action.result.message, false, true );
					datastore.reload();
					Ext.getCmp("dialog").destroy();
				},
				failure: function(form, action) {
					statusBarMessage( action.result.error, false, false );
					Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error);
				},
				scope: form,
				params: {
					"option": "com_extplorer", 
					"action": "chmod", 
					"dir": "<?php echo stripslashes($GLOBALS['__POST']["dir"]) ?>", 
					"selitems[]": ['<?php echo implode("','", $GLOBALS['__POST']["selitems"]) ?>'], 
					confirm: 'true'
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
//------------------------------------------------------------------------------
?>