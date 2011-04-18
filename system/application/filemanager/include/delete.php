<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: delete.php 135 2009-01-27 21:57:15Z ryu_ms $
 * @package eXtplorer
 * @copyright soeren 2007
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
 * File-Delete Functions
 *
 */
class ext_Delete extends ext_Action {

	function execAction($dir) {
		// delete files/dirs
		if(($GLOBALS["permissions"]&01)!=01) 
		ext_Result::sendResult('delete', false, $GLOBALS["error_msg"]["accessfunc"]);

		$cnt = count($GLOBALS['__POST']["selitems"]);
		$err = false;

		// delete files & check for errors
		for($i=0;$i<$cnt;++$i) {
			$items[$i] = basename(stripslashes($GLOBALS['__POST']["selitems"][$i]));
			if( ext_isFTPMode() ) {
				$abs = get_item_info( $dir,$items[$i]);
			} else {
				$abs = get_abs_item($dir,$items[$i]);
			}

			if(!@$GLOBALS['ext_File']->file_exists( $abs )) {
				$error[$i] = $GLOBALS["error_msg"]["itemexist"];
				$err=true;	continue;
			}
			if(!get_show_item($dir, $items[$i])) {
				$error[$i] = $GLOBALS["error_msg"]["accessitem"];
				$err=true;	continue;
			}

			// Delete
			if( ext_isFTPMode() ) $abs = str_replace('\\', '/', get_abs_item($dir,$abs) );

			$ok= $GLOBALS['ext_File']->remove( $abs );

			if($ok===false || PEAR::isError( $ok )) {
				$error[$i]=$GLOBALS["error_msg"]["delitem"];
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

				$err_msg .= $items[$i]." : ".$error[$i].".\n";
			}
			ext_Result::sendResult('delete', false, $err_msg);
		}
		ext_Result::sendResult('delete', true, $GLOBALS['messages']['success_delete_file'] );
	}
}
//------------------------------------------------------------------------------
?>
