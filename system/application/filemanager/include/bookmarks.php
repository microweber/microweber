<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: bookmarks.php 164 2010-05-03 15:06:51Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2010
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
 * reads all bookmarks from the bookmark ini file
 *
 * @return array
 */


function read_bookmarks() {
	global $my, $mainframe;
	$bookmarkfile = _EXT_PATH.'/config/bookmarks_'.$GLOBALS['file_mode'].'_';
	if( empty( $my->id )) {
		if( class_exists('jfactory') ) {
			$user = JFactory::getUser();
			$bookmarkfile .= $user->get('id').'.php';
		} else {
			$bookmarkfile .= $mainframe->getUserName().'.php';
		}
	} else {
		$bookmarkfile .= $my->id . '.php';
	}
	if( file_exists( $bookmarkfile )) {
		return parse_ini_file( $bookmarkfile );
	}
	else {
		if( !is_writable( dirname( $bookmarkfile ) ) && !chmod( dirname( $bookmarkfile ), 0777 )) {
			return array( $GLOBALS['messages']['homelink'] => '' );
		} else {
			file_put_contents( $bookmarkfile, ";<?php if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' ); ?>\n{$GLOBALS['messages']['homelink']}=\n" );
			return array( $GLOBALS['messages']['homelink'] => '' );
		}
	}
}

function strip_invalid_key_char($s, $replacement ="") {
  return preg_replace('/[{}|&~![()"]/u', $replacement, $s);
}

/**
 * Adds a new bookmark to the bookmark ini file
 *
 * @param string $dir
 */
function modify_bookmark( $task, $dir ) {
	global $my, $user, $mainframe;
	$alias = substr( extGetParam($_REQUEST,'alias'), 0, 150 );
	$bookmarks = read_bookmarks();
		$bookmarkfile = _EXT_PATH.'/config/bookmarks_'.$GLOBALS['file_mode'].'_';
	if( empty( $my->id )) {
		if( class_exists('jfactory') ) {
			$user = JFactory::getUser();
			$bookmarkfile .= $user->get('id').'.php';
		} else {
			$bookmarkfile .= $mainframe->getUserName().'.php';
		}
	} else {
		$bookmarkfile .= $my->id . '.php';
	}
	while( @ob_end_clean() );

	header( "Status: 200 OK" );

	switch ( $task ) {
		case 'add':

			if( in_array( $dir, $bookmarks )) {
				echo ext_alertBox( $GLOBALS['messages']['already_bookmarked'] ); exit;
			}
			//$alias = preg_replace('~[^\w-.\/\\\]~','', $alias ); // Make the alias ini-safe by removing all non-word characters
			$alias = strip_invalid_key_char($alias, "_");
			$bookmarks[$alias] = $dir; //we deal with the flippped array here
			$msg = ext_successBox( $GLOBALS['messages']['bookmark_was_added'] );
			break;

		case 'remove':

			if( !in_array( $dir, $bookmarks )) {
				echo ext_alertBox( $GLOBALS['messages']['not_a_bookmark'] ); exit;
			}
			$bookmarks = array_flip( $bookmarks );
			unset( $bookmarks[$dir] );
			$bookmarks = array_flip( $bookmarks );
			$msg = ext_successBox( $GLOBALS['messages']['bookmark_was_removed'] );
	}

	$inifile = "; <?php if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' ); ?>\n";
	$inifile .= $GLOBALS['messages']['homelink']."=\n";

	foreach( $bookmarks as $alias => $directory ) { //changed by pokemon
		if( empty( $directory ) || empty( $alias ) ) continue;
		if( $directory[0] == $GLOBALS['separator']) $directory = substr( $directory, 1 );
		$inifile .= "$alias=$directory\n";
	}
	if( !is_writable( $bookmarkfile )) {
		echo ext_alertBox( sprintf( $GLOBALS['messages']['bookmarkfile_not_writable'], $task, $bookmarkfile ) ); exit;
	}
	file_put_contents( $bookmarkfile, $inifile );

	echo $msg;
	echo list_bookmarks($dir);
	exit;
}

/**
 * Lists all bookmarked directories in a dropdown list.
 *
 * @param string $dir
 */
function list_bookmarks( $dir ) {

	$bookmarks = read_bookmarks();
	$bookmarks = array_flip($bookmarks);

	foreach( $bookmarks as $bookmark ) {
		$len = strlen( $bookmark );
		if( $len > 40 ) {
			$first_part = substr( $bookmark, 0, 20 );
			$last_part = substr( $bookmark, -20 );
			$bookmarks[$bookmark] = $first_part . '...' . $last_part;
		}
	}


	$html = $GLOBALS['messages']['quick_jump'].': ';
	if( !empty($dir[0]) && @$dir[0] == '/' ) {
		$dir = substr( $dir, 1);
	}
	$html .= ext_selectList( 'favourites', $dir, $bookmarks, 1, '', 'onchange="chDir( this.options[this.options.selectedIndex].value);" style="max-width: 200px;"');
	$img_add = '<img src="'._EXT_URL.'/images/_bookmark_add.png" border="0" alt="'.$GLOBALS['messages']['lbl_add_bookmark'].'" align="absmiddle" />';
	$img_remove = '<img src="'._EXT_URL.'/images/_remove.png" border="0" alt="'.$GLOBALS['messages']['lbl_remove_bookmark'].'" align="absmiddle" />';

	$addlink=$removelink='';

	if( !isset( $bookmarks[$dir] ) && $dir != '' && $dir != '/' ) {
		$addlink = '<a href="'.make_link('modify_bookmark', $dir ).'&task=add" onclick="'
		.'Ext.Msg.prompt(\''.ext_Lang::msg('lbl_add_bookmark',true).'\', \''.ext_Lang::msg('enter_alias_name', true ).':\', '
		.'function(btn, text){ '
			.'if (btn == \'ok\') { '
				.'Ext.get(\'bookmark_container\').load({ '
					.'url: \''. basename( $GLOBALS['script_name']) .'\', '
					.'scripts: true, '
					.'params: { '
						.'action:\'modify_bookmark\', '
						.'task: \'add\', '
						.'requestType: \'xmlhttprequest\', '
						.'alias: text, '
						.'dir: \''.$dir.'\', '
						.'option: \'com_extplorer\' '
					.'} '
				.'}); '
			.'}'
		.'}); return false;" title="'.$GLOBALS['messages']['lbl_add_bookmark'].'" >'.$img_add.'</a>';
	} elseif( $dir != '' && $dir != '/' ) {
		$removelink = '<a href="'.make_link('modify_bookmark', $dir ).'&task=remove" onclick="'
		.'Ext.Msg.confirm(\''.ext_Lang::msg('lbl_remove_bookmark', true ).'\',\''.ext_Lang::msg('lbl_remove_bookmark', true ).'?\', '
		.'function(btn, text){ '
			.'if (btn == \'yes\') { '
				.'Ext.get(\'bookmark_container\').load({ '
					.'url: \''. basename( $GLOBALS['script_name']) .'\', '
					.'scripts: true, '
					.'params: { '
						.'action:\'modify_bookmark\', '
						.'task: \'remove\', '
						.'dir: \''.$dir.'\', '
						.'option: \'com_extplorer\' '
					.'} '
				.'}); '
			.'}'
		.'}); return false;" title="'.$GLOBALS['messages']['lbl_remove_bookmark'].'">'.$img_remove.'</a>';
	}

	$html .= $addlink .'&nbsp;'.$removelink;

	return $html;
}

?>