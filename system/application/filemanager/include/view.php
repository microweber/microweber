<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: view.php 164 2010-05-03 15:06:51Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
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
 * Allows to view sourcecode (formatted by GeSHi or unformatted) and images
 *
 */
class ext_View extends ext_Action {

	function execAction($dir, $item) {		// show file contents
		global $action;
		
		if( @eregi($GLOBALS["images_ext"], $item)) {
			$html =  '<img src="'.make_link( 'get_image', $dir, rawurlencode($item)).'" alt="'.$GLOBALS["messages"]["actview"].": ".$item.'" /><br /><br />';
		}

		elseif( @eregi($GLOBALS["editable_ext"], $item)) {

			$geshiFile = _EXT_PATH . '/libraries/geshi/geshi.php';

			ext_RaiseMemoryLimit('32M'); // GeSHi 1.0.7 is very memory-intensive
			include_once( $geshiFile );
			// Create the GeSHi object that renders our source beautiful
			$geshi = new GeSHi( '', '', dirname( $geshiFile ).'/geshi' );
			$file = get_abs_item($dir, $item);
			$pathinfo = pathinfo( $file );
			if( ext_isFTPMode() ) {
				$file = ext_ftp_make_local_copy( $file );
			}
			if( is_callable( array( $geshi, 'load_from_file'))) {
				$geshi->load_from_file( $file );
			}
			else {
				$geshi->set_source( file_get_contents( $file ));
			}
			if( is_callable( array($geshi,'get_language_name_from_extension'))) {
				$lang = $geshi->get_language_name_from_extension( $pathinfo['extension'] );
			}
			else {
				$pathinfo = pathinfo($item);
				$lang = $pathinfo['extension'];
			}

			$geshi->set_language( $lang );
			$geshi->enable_line_numbers( GESHI_NORMAL_LINE_NUMBERS );

			$langs = $GLOBALS["language"];
			if ($langs == "japanese"){
				$enc_list = Array("ASCII", "ISO-2022-JP", "UTF-8", "EUCJP-WIN", "SJIS-WIN");
				$_e0 = strtoupper(mb_detect_encoding($geshi->source, $enc_list, true));
				if ($_e0 == "SJIS-WIN"){
					$_encoding = "Shift_JIS";
				} elseif ($_e0 == "EUCJP-WIN"){
					$_e0 = "EUC-JP";
				} elseif ($_e0 == "ASCII"){
					$_e0 = "UTF-8";
				} else {
					$_encoding = $_e0;
				}
				$geshi->set_encoding( $_encoding );
			}

			$html = $geshi->parse_code();

			if ($langs == "japanese"){
				if (empty($lang) || strtoupper(mb_detect_encoding($html, $enc_list)) != "UTF-8"){
					$html = mb_convert_encoding($html, "UTF-8", $_e0 );
				}
			}


			if( ext_isFTPMode() ) {
				unlink( $file );
			}
			
			$html .= '<hr /><div style="line-height:25px;vertical-align:middle;text-align:center;" class="small">Rendering Time: <strong>'.$geshi->get_time().' Sec.</strong></div>';
			

		} else {
			$html = '
			<iframe src="'. make_link('download', $dir, $item, null, null, null, '&action2=view' ) .'" id="iframe1" width="100%" height="100%" frameborder="0"></iframe>';
			
		}
		$html = str_replace(Array("\r", "\n"), Array('\r', '\n') , addslashes($html));
		?>
		{

	"dialogtitle": "<?php echo $GLOBALS['messages']['actview'].": ".$item ?>",
	"height": 500,
	"autoScroll": true,
	"html": "<?php echo $html	?>"

}
		<?php
	}
	function sendImage( $dir, $item ) {
		$abs_item = get_abs_item( $dir, $item );
		if( $GLOBALS['ext_File']->file_exists( $abs_item )) {
  			if(!@eregi($GLOBALS["images_ext"], $item)) return;
  			while( @ob_end_clean() );
  
  			$pathinfo = pathinfo( $item );
			switch(strtolower($pathinfo['extension'])) {
				case "gif":
					header ("Content-type: image/gif");
					break;
				case "jpg":
				case "jpeg":
					header ("Content-type: image/jpeg");
					break;
				case "png":
					header ("Content-type: image/png");
					break;
			}

			echo $GLOBALS['ext_File']->file_get_contents( $abs_item );

		}
		exit;
	}
}
?>