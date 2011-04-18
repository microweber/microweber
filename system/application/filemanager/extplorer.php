<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: extplorer.php 164 2010-05-03 15:06:51Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
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

require( dirname(__FILE__).'/configuration.ext.php' );

if( !$frontend_enabled || empty( $subdir ) || $subdir == '/' || $subdir == '\\' ) {
	echo _NOT_EXIST;
	return;
}

$GLOBALS["home_dir"] = $mosConfig_absolute_path . $subdir;
// the url corresponding with the home directory: (no trailing '/')
$GLOBALS["home_url"] = $mosConfig_live_site.'/downloads';

require( dirname(__FILE__).'/extplorer.init.php');
include( dirname(__FILE__).'/extplorer.list.php');

if( !empty($GLOBALS['ERROR']) || defined('EXPLORER_NOEXEC')) {
	echo '<h2>'.$GLOBALS['ERROR'].'</h2>';
	return;
}
if( !is_object( $database )) {
	$database = JFactory::getDBO();
}
$res = new StdClass();
$database->setQuery( 'SELECT id, name FROM `#__menu` WHERE link LIKE \'%option=com_extplorer%\' ORDER BY `id` LIMIT 1');
$database->loadObject( $res );
if( is_object( $res ) && !empty( $res->name )) {
	$name = $res->name;
}
else {
	$name = '';
}

if( $name || $dir ) {
	$mainframe->setPageTitle( $name.' - '.$dir );
}
$action = extGetParam( $_REQUEST, 'action', 'list');
$item = extGetParam( $_REQUEST, 'item', '');

// Here we allow *download* and *directory listing*, nothing more, nothing less
switch( $action ) {
	case 'download':
		require _EXT_PATH . "/include/download.php";
		ext_Download::execAction($dir, $item);
		exit;
	case 'list':
	default:
		list_dir($dir);
		break;
}

// A small nice footer. Remove if you don't want to give credit to the developer.
echo '<br style="clear:both;"/>
	<small>
	<a class="title" href="'.$GLOBALS['ext_home'].'" target="_blank">powered by eXtplorer</a>
	</small>
	';

?>
