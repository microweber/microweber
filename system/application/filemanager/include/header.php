<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: header.php 187 2011-01-18 15:25:24Z soeren $
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
 * This is the file, which prints the header row with the Logo
 */
function show_header($dirlinks='') {
	$url = str_replace( array('&dir=', '&action=', '&file_mode='), 
						array('&a=','&b=','&c='), 
						$_SERVER['REQUEST_URI'] );
	
	$url_appendix = strpos($url, '?') === false ? '?' : '&amp;';
	
	echo "<link rel=\"stylesheet\" href=\""._EXT_URL."/style/style.css\" type=\"text/css\" />\n";
	echo "<div id=\"ext_header\">\n";
	echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"5\">\n";
	$mode = extGetParam( $_SESSION, 'file_mode', $GLOBALS['ext_conf']['authentication_method_default'] );
	$logoutlink = ' <a href="'.$GLOBALS['script_name'].'?option=com_extplorer&amp;action=logout" title="'.$GLOBALS['messages']['logoutlink'].'">['.$GLOBALS['messages']['logoutlink'].']</a>';
	$alternate_modes = array();
	foreach( $GLOBALS['ext_conf']['authentication_methods_allowed'] as $method ) {
		if( $method != $mode ) {
			$onclick = '';
			if( empty($_SESSION['credentials_'.$method])) {
				 $onclick = "onclick=\"openActionDialog('switch_file_mode', '".$method."_authentication');return false;\"";
			}
			$alternate_modes[] = "<a $onclick href=\"$url".$url_appendix."file_mode=$method\">$method</a>"; 
		}
	}
	echo '<tr><td width="20%">';
	if( is_object( $GLOBALS['_VERSION'] ) || class_exists( 'jversion')) {
		echo '<a href="'.basename($_SERVER['SCRIPT_NAME']).'">Back to '.( !empty($GLOBALS['_VERSION']->PRODUCT) ? @$GLOBALS['_VERSION']->PRODUCT : 'Joomla!' ).'</a>';

	} else {
		echo ext_selectList('language_selector', $GLOBALS['language'], get_languages(), 1, '', 'onchange="document.location.href=\''.$GLOBALS['script_name'].'?lang=\' + this.options[this.selectedIndex].value;"');
	}
	// Logo
	echo "</td><td style=\"color:black;\" width=\"10%\">";
	//echo "<div style=\"margin-left:10px;float:right;\" width=\"305\" >";
	echo "<a href=\"".$GLOBALS['ext_home']."\" target=\"_blank\" title=\"eXtplorer Project\">
		<img src=\""._EXT_URL."/images/eXtplorer_logo.png\" alt=\"eXtplorer Logo\" border=\"0\" /></a>
		</td>";
	//echo "</div>";
	echo "<td style=\"padding-left: 15px; color:black;\" id=\"bookmark_container\" width=\"35%\"></td>\n";
	echo "<td width=\"25%\" style=\"padding-left: 15px; color:black;\">"
		.sprintf( $GLOBALS['messages']['switch_file_mode'], $mode . $logoutlink, implode(', ', $alternate_modes ) ). "
	</td>\n";

	echo '</tr></table>';
	echo '</div>';
}
//------------------------------------------------------------------------------
?>
