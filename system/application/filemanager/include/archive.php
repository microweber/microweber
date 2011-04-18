<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: archive.php 164 2010-05-03 15:06:51Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
 * @license
 * @version $Id: archive.php 164 2010-05-03 15:06:51Z soeren $
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
 * Zip & TarGzip Functions
 *
 */
class ext_Archive extends ext_Action {

	function execAction( $dir ) {

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('archive', false, $GLOBALS["error_msg"]["accessfunc"]);
		}
		if(!$GLOBALS["zip"] && !$GLOBALS["tgz"]) {
			ext_Result::sendResult('archive', false, $GLOBALS["error_msg"]["miscnofunc"]);
		}
		
		$allowed_types = array( 'zip', 'tgz', 'tbz', 'tar' );

		// If we have something to archive, let's do it now
		if(extGetParam($_POST, 'confirm' ) == 'true' ) {
			$saveToDir = utf8_decode($GLOBALS['__POST']['saveToDir']);
			if( !file_exists( get_abs_dir($saveToDir ) )) {
				ext_Result::sendResult('archive', false, ext_Lang::err('archive_dir_notexists'));
			}
			if( !is_writable( get_abs_dir($saveToDir ) )) {
				ext_Result::sendResult('archive', false, ext_Lang::err('archive_dir_unwritable'));
			}
			require_once( _EXT_PATH .'/libraries/Archive/archive.php' );

			if( !in_array(strtolower( $GLOBALS['__POST']["type"] ), $allowed_types )) {
				ext_Result::sendResult('archive', false, ext_Lang::err('extract_unknowntype').': '.htmlspecialchars($GLOBALS['__POST']["type"]));

			}
			// This controls how many files are processed per Step (it's split up into steps to prevent time-outs)
			$files_per_step = 2000;

			$cnt=count($GLOBALS['__POST']["selitems"]);
			$abs_dir= get_abs_dir($dir);
			$name=basename(stripslashes($GLOBALS['__POST']["name"]));
			if($name=="") {
				ext_Result::sendResult('archive', false, $GLOBALS["error_msg"]["miscnoname"]);
			}

			$startfrom = extGetParam( $_REQUEST, 'startfrom', 0 );

			$dir_contents_cache_name = 'ext_'.md5(implode(null, $GLOBALS['__POST']["selitems"]));
			$dir_contents_cache_file = _EXT_FTPTMP_PATH.'/'.$dir_contents_cache_name.'.txt';

			$archive_name = get_abs_item($saveToDir,$name);
			$fileinfo = pathinfo( $archive_name );

			if( empty( $fileinfo['extension'] )) {
				$archive_name .= ".".$GLOBALS['__POST']["type"];
				$fileinfo['extension'] = $GLOBALS['__POST']["type"];

				foreach( $allowed_types as $ext ) {
					if( $GLOBALS['__POST']["type"] == $ext && @$fileinfo['extension'] != $ext ) {
						$archive_name .= ".".$ext;
					}
				}
			}
			if( $startfrom == 0 ) {
				for($i=0;$i<$cnt;$i++) {

					$selitem=stripslashes($GLOBALS['__POST']["selitems"][$i]);
					if( $selitem == 'ext_root') { 
						$selitem = '';
					}
					if( is_dir( utf8_decode($abs_dir ."/". $selitem ))) {
						$items = extReadDirectory(utf8_decode($abs_dir ."/".  $selitem), '.', true, true );
						foreach ( $items as $item ) {
							if( is_dir( $item ) || !is_readable( $item ) || $item == $archive_name ) continue;
							$v_list[] = str_replace('\\', '/', $item );
						}
					}
					else {
						$v_list[] = utf8_decode(str_replace('\\', '/', $abs_dir ."/". $selitem ));
					}
				}
				if( count($v_list) > $files_per_step ) {
					if( file_put_contents($dir_contents_cache_file, implode("\n", $v_list )) == false ) {
						ext_Result::sendResult('archive', false, 'Failed to create a temporary list of the directory contents' );
					}
				}
			} else {
				$file_list_string = file_get_contents($dir_contents_cache_file);
				if( empty( $file_list_string )) {
					ext_Result::sendResult('archive', false, 'Failed to retrieve the temporary list of the directory contents' );
				}
				$v_list = explode("\n", $file_list_string );
			}
			$cnt_filelist = count( $v_list );
			// Now we go to the right range of files and "slice" the array
			$v_list = array_slice( $v_list, $startfrom, $files_per_step-1  );

			$remove_path = $GLOBALS["home_dir"];
			if( $dir ) {
				$remove_path .= $dir;
			}
			$remove_path = str_replace( '\\', '/', realpath($remove_path) ).'/';
			$debug = 'Starting from: '.$startfrom."\n";
			$debug .= 'Files to process: '.$cnt_filelist."\n";
			$debug .= implode( "\n", $v_list );
			//file_put_contents( 'log.txt', $debug, FILE_APPEND );

			// Do some setup stuff
			ini_set('memory_limit', '128M');
			@set_time_limit( 0 );
			//error_reporting( E_ERROR | E_PARSE );
			$result = extArchive::create( $archive_name, $v_list, $GLOBALS['__POST']["type"], '', $remove_path	);

			if( PEAR::isError( $result ) ) {
				ext_Result::sendResult('archive', false, $name.': '.ext_Lang::err('archive_creation_failed').' ('.$result->getMessage().$archive_name.')' );
			}
			$classname = class_exists('ext_Json') ? 'ext_Json' : 'Services_JSON';
			$json = new $classname();
			if( $cnt_filelist > $startfrom+$files_per_step ) {

				$response = Array( 'startfrom' => $startfrom + $files_per_step,
				'totalitems' => $cnt_filelist,
				'success' => true,
				'action' => 'archive',
				'message' => sprintf( ext_Lang::msg('processed_x_files'), $startfrom + $files_per_step, $cnt_filelist )
				);
			}
			else {
				@unlink($dir_contents_cache_file);
				if( $GLOBALS['__POST']["type"] == 'tgz' || $GLOBALS['__POST']["type"] == 'tbz') {
					chmod( $archive_name, 0644 );
				}
				$response = Array( 'action' => 'archive',
				'success' => true,
				'message' => ext_Lang::msg('archive_created'),
				'newlocation' => make_link( 'download', $dir, basename($archive_name) )
				);

			}

			echo $json->encode( $response );
			ext_exit();
		}
		$default_archive_type = 'zip';
	?>
		{
		"xtype": "form",
		"id": "simpleform",
		"height": "200",
		"width": "350",
		"labelWidth": 125,
		"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
		"dialogtitle": "<?php echo $GLOBALS["messages"]["actarchive"] ?>",
		"frame": true,
		"items": [{
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('archive_name', true ) ?>",
			"name": "name",
			"value": "<?php echo $GLOBALS['item'] . '.'. $default_archive_type ?>",
			"width": "200"
		},
		{
			"xtype": "combo",
			"fieldLabel": "<?php echo ext_Lang::msg('typeheader', true ) ?>",
			"store": [
					['zip', 'Zip (<?php echo ext_Lang::msg('normal_compression', true ) ?>)'],
					['tgz', 'Tar/Gz (<?php echo ext_Lang::msg('good_compression', true ) ?>)'],
					<?php
					if(extension_loaded("bz2")) {
						echo "['tbz', 'Tar/Bzip2 (".ext_Lang::msg('best_compression', true ).")'],";
					}
					?>
					['tar', 'Tar (<?php echo ext_Lang::msg('no_compression', true ) ?>)']
					],
			"displayField":"typename",
			"valueField": "type",
			"name": "type",
			"value": "<?php echo $default_archive_type ?>",
			"triggerAction": "all",
			"hiddenName": "type",
			"disableKeyFilter": "true",
			"editable": "false",
			"mode": "local",
			"allowBlank": "false",
			"selectOnFocus":"true",
			"width": "200",
			"listeners": { "select": { 
							fn: function(o, record ) {
								form = Ext.getCmp("simpleform").getForm();
								var nameField = form.findField("name").getValue();								
								if( nameField.indexOf( '.' ) > 0 ) {
									form.findField('name').setValue( nameField.substring( 0, nameField.indexOf('.')+1 ) + o.getValue() );
								} else {
									form.findField('name').setValue( nameField + '.'+ o.getValue());
								}
							}
						  }
						}
		
		
		}, {
			"xtype": "textfield",
			"fieldLabel": "<?php echo ext_Lang::msg('archive_saveToDir', true ) ?>",
			"name": "saveToDir",
			"value": "<?php echo str_replace("'", "\'", $dir ) ?>",
			"width": "200"
		},{
			"xtype": "checkbox",
			"fieldLabel": "<?php echo ext_Lang::msg('downlink', true ) ?>?",
			"name": "download",
			"checked": "true"
		}
		],
		"buttons": [{
			"text": "<?php echo ext_Lang::msg( 'btncreate', true ) ?>", 
			"type": "submit", 
			"handler": function() { 
				Ext.ux.OnDemandLoad.load( "<?php echo $GLOBALS['script_name'] ?>?option=com_extplorer&action=include_javascript&file=archive.js", 
											function(options) { submitArchiveForm(0) } ); 
			}
		},{
			"text": "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			"handler": function() { Ext.getCmp("dialog").destroy() }
		}]
}

	<?php
	}
}
//------------------------------------------------------------------------------
?>