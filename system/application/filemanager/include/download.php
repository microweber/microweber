<?php
// ensure this file is being included by a parent file
if (!defined('_JEXEC') && !defined('_VALID_MOS')) die('Restricted access');
/**
 * @version $Id: download.php 171 2010-06-11 06:39:15Z soeren $
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
 * File-Download Functions
 *
 */
class ext_Download extends ext_Action {

	// download file
	function execAction($dir, $item, $unlink=false) {

		// Security Fix:
		$item = basename($item);

		while (@ob_end_clean());
		ob_start();

		if (ext_isFTPMode()) {
			$abs_item = $dir . '/' . $item;
		} else {
			$abs_item = get_abs_item($dir,$item);
			//if( !strstr( $abs_item, $GLOBALS['home_dir']) )
			//	$abs_item = realpath($GLOBALS['home_dir']).$abs_item;
		}

		if (!$GLOBALS['ext_File']->file_exists($abs_item)) {
			ext_Result::sendResult( 'download', false, $item.": ".$GLOBALS["error_msg"]["fileexist"]);
		}

		if (!get_show_item($dir, $item)) {
			ext_Result::sendResult( 'download', false, $item.": ".$GLOBALS["error_msg"]["accessfile"]);
		}

		@set_time_limit( 0 );
		
		if (ext_isFTPMode()) {
			$abs_item = ext_ftp_make_local_copy( $abs_item );
			$unlink = true;
		}

		$browser = id_browser();

		header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize(realpath($abs_item)));
		//header("Content-Encoding: none");
		
		if( isset($_GET['action2']) && $_GET['action2'] == 'view' ) {
			$content_disposition = 'inline';
			include_once( _EXT_PATH.'/libraries/Archive/file.php');
			$extension = extFile::getExt($item);
			switch( strtolower($extension) ) {
				case 'doc':
				case 'dot': $extension = 'msword'; break;
				case 'docx':
				case 'dotx': $extension = 'vnd.openxmlformats-officedocument.wordprocessingml.template';break;
				case 'docm': $extension = 'vnd.ms-word.document.macroEnabled.12';break;
				case 'docm': $extension = 'vnd.ms-word.template.macroEnabled.12';break;
				case 'xls': 
				case 'xlt': 
				case 'xla': 
						$extension = 'vnd.ms-excel';break;
				case 'xlsx': $extension = 'vnd.openxmlformats-officedocument.spreadsheetml.sheet';break;
				case 'xltx': $extension = 'vnd.openxmlformats-officedocument.spreadsheetml.template';break;
				case 'xlsm': $extension = 'vnd.ms-excel.sheet.macroEnabled.12';break;
				case 'xltm': $extension = 'vnd.ms-excel.template.macroEnabled.12';break;
				case 'xlam': $extension = 'vnd.ms-excel.addin.macroEnabled.12';break;
				case 'xlsb': $extension = 'vnd.ms-excel.sheet.binary.macroEnabled.12';break;
				case 'ppt': 
				case 'pot': 
				case 'pps': 
				case 'ppa': 
						$extension = 'vnd.ms-powerpoint';break;
				case 'pptx': $extension = 'vnd.openxmlformats-officedocument.presentationml.presentation';break;
				case 'potx': $extension = 'vnd.openxmlformats-officedocument.presentationml.template';break;
				case 'ppsx': $extension = 'vnd.openxmlformats-officedocument.presentationml.slideshow';break;
				case 'ppam': $extension = 'vnd.ms-powerpoint.addin.macroEnabled.12';break;
				case 'pptm': $extension = 'vnd.ms-powerpoint.presentation.macroEnabled.12';break;
				case 'potm': $extension = 'vnd.ms-powerpoint.template.macroEnabled.12';break;
				case 'ppsm': $extension = 'vnd.ms-powerpoint.slideshow.macroEnabled.12';break;
				case 'rtf': $extension = 'application/rtf';break;

			}
			header('Content-Type: application/'.$extension.'; Charset='  . $GLOBALS["system_charset"]);
			
		} else {
			$content_disposition = 'attachment';
				if ($browser=='IE' || $browser=='OPERA') {
					header('Content-Type: application/octetstream; Charset='  . $GLOBALS["system_charset"]);
				} else {
					header('Content-Type: application/octet-stream; Charset=' . $GLOBALS["system_charset"]);
				}
		}
		if($browser=='IE') {
			// http://support.microsoft.com/kb/436616/ja
			header('Content-Disposition: '.$content_disposition.'; filename="'.urlencode($item).'"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: '.$content_disposition.'; filename="'.$item.'"');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
		}

 		if($GLOBALS['use_mb']) {
 			if (mb_detect_encoding($abs_item) == 'ASCII') {
 				@readFileChunked(utf8_decode($abs_item));
 			} else {
 				@readFileChunked($abs_item);
 			}
 		} else {
 			@readFileChunked(utf8_decode($abs_item));
 		}
		if( $unlink==true ) {
			unlink( utf8_decode($abs_item) );
		}

		ob_end_flush();
		ext_exit();

	}
}
//------------------------------------------------------------------------------
?>
