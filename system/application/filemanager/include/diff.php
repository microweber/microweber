<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: edit.php 129 2009-01-26 16:14:53Z ryu_ms $
 * @package eXtplorer
 * @copyright Geoffrey Tran 2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The  The QuiX project (http://quixplorer.sourceforge.net)
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
 * File-Diff Functions
 *
 */
class ext_Diff extends ext_Action {
	var	$lang_tbl = Array(
		'czech' => 'cs',
		'german' => 'de',
		'danish' => 'dk',
		'english' => 'en',
		'esperanto' => 'eo',
		'spanish' => 'es',
		'french' => 'fr',
		'croatian' => 'hr',
		'italian' => 'it',
		'japanese' => 'ja',
		'macedonian' => 'mk',
		'dutch' => 'nl',
		'polish' => 'pl',
		'portuguese' => 'pt',
		'russian' => 'ru',
		'slovenian' => 'sk'
	);

	function execAction($dir, $item) {

		if(($GLOBALS["permissions"]&01)!=01) {
			ext_Result::sendResult('diff', false, ext_Lang::err('accessfunc' ));
		}
		$fname = get_abs_item($dir, $item);

		if(!get_is_file(utf8_decode($fname)))  {
			ext_Result::sendResult('diff', false, $item.": ".ext_Lang::err('fileexist' ));
		}
		if(!get_show_item($dir, $item)) {
			ext_Result::sendResult('diff', false, $item.": ".ext_Lang::err('accessfile' ));
		}
		$cnt = 0;
		if( !empty($GLOBALS['__POST']["selitems"])) {
			$cnt=count($GLOBALS['__POST']["selitems"]);
		}
		$item2 = extGetParam( $_POST, 'item2');
		if ($item2 !== null) {
	        $fname2 = get_abs_item('', utf8_decode($item2));
		}
		elseif( $cnt >= 2 ) {
			$item2 = $GLOBALS['__POST']["selitems"][1];
			$fname2 = get_abs_item( $dir, $item2);
		}
		
		if ($item2 !== null) {
            if(!get_is_file($fname2))  {
                ext_Result::sendResult('diff', false, $item2.": ".ext_Lang::err('fileexist' ));
            }

            if(!get_show_item('', $item2)) {
                ext_Result::sendResult('diff', false, $item2.": ".ext_Lang::err('accessfile' ));
            }
		} elseif( empty( $cnt ) && extGetParam($_POST, 'confirm') == 'true' ) {
			ext_Result::sendResult('diff', false, 'Please select a second file to diff to');
		}
		if ($item2 || $cnt >= 2) {
			// Show File In TextArea
			$content = $GLOBALS['ext_File']->file_get_contents( $fname );			
			$content2 = $GLOBALS['ext_File']->file_get_contents( $fname2 );
			
			//$content = nl2br(str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", htmlentities($content)));
			//$content2 = nl2br(str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", htmlentities($content2)));
			$diff = $this->inline_diff($content, $content2);
			if( empty( $diff )) {
				ext_Result::sendResult('diff', true, 'Both Files are identical');
			}
			$diff = utf8_encode( nl2br( $diff ) );
			echo '{ "xtype": "panel", "dialogtitle": "Diff Result", "html": "'.str_replace( array("\n", "\r"), array('',''), $diff).'" }';
			exit;
		}
           
	?>
{
	"xtype": "form",
	"id": "simpleform",
	"width": "700",
	"labelWidth": 125,
	"url":"<?php echo basename( $GLOBALS['script_name']) ?>",
	"dialogtitle": "Diff <?php echo htmlentities($item); ?><?php if ($item2) { echo ' and ' . htmlentities($item2); } ?>",
	"title": "Diff",
	"items": [{
		xtype: "textfield",
		fieldLabel: 'File to Compare',
		name: 'item2',
		value: "<?php echo $dir ?>/",
		width:175,
		allowBlank:false
		}],
    buttons: [{
		"text": "<?php echo ext_Lang::msg( 'btndiff', true ) ?>", 
		"handler": function() {
			statusBarMessage( 'Please wait...', true );
			form = Ext.getCmp("simpleform").getForm();
			form.submit({
				//reset: true,
				reset: false,
				success: function(form, action) {
					Ext.getCmp("dialog").setContent( action.result.message, true );
				},
				failure: function(form, action) {
					if( !action.result ) return;
					Ext.MessageBox.alert('Error!', action.result.error);
					statusBarMessage( action.result.error, false, true );
				},
				scope: form,
				// add some vars to the request, similar to hidden fields
				params: {
					"option": "com_extplorer", 
					"action": "diff", 
					"dir": "<?php echo stripslashes($GLOBALS['__POST']["dir"]) ?>", 
					"item": "<?php echo $item ?>",
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

	/**
	 * Inline diff
	 *
	 * @param string $text1
	 * @param string $text2
	 * @return string
	 */
	function inline_diff($text1, $text2)
	{
	    $hlines1 = explode("\n", $text1);
        $hlines2 = explode("\n", $text2);

        include_once dirname(dirname(__FILE__)).'/libraries/Text/Diff.php';
		// create the diff object
        $diff = new Text_Diff($hlines1, $hlines2);

        // get the diff in unified format
        // you can add 4 other parameters, which will be the ins/del prefix/suffix tags
		include_once dirname(dirname(__FILE__)).'/libraries/Text/Diff/Renderer/unified.php';
        $renderer = new Text_Diff_Renderer_unified();
		//include_once dirname(dirname(__FILE__)).'/libraries/Text/Diff/Renderer/inline.php';
        //$renderer = new Text_Diff_Renderer_Inline(50000);

        return $renderer->render($diff);
    }
}