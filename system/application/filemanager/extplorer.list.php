<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: extplorer.list.php 139 2009-01-30 22:02:54Z ryu_ms $
 * @package eXtplorer
 * @copyright soeren 2007
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
// HELPER FUNCTIONS (USED BY MAIN FUNCTION 'list_dir', SEE BOTTOM)
function make_list($_list1, $_list2) {		// make list of files
	$list = array();

	if($GLOBALS["direction"]=="ASC") {
		$list1 = $_list1;
		$list2 = $_list2;
	} else {
		$list1 = $_list2;
		$list2 = $_list1;
	}

	if(is_array($list1)) {
		while (list($key, $val) = each($list1)) {
			$list[$key] = $val;
		}
	}

	if(is_array($list2)) {
		while (list($key, $val) = each($list2)) {
			$list[$key] = $val;
		}
	}

	return $list;
}
//------------------------------------------------------------------------------
function make_tables($dir, &$dir_list, &$file_list, &$tot_file_size, &$num_items)
{						// make table of files in dir
	// make tables & place results in reference-variables passed to function
	// also 'return' total filesize & total number of items
	$homedir = realpath($GLOBALS['home_dir']);
	$tot_file_size = $num_items = 0;
	// Open directory
	$handle = @opendir(get_abs_dir($dir));
	if($handle===false && $dir=="") {
	$handle = @opendir($homedir . $GLOBALS['separator']);
	}

	if($handle===false)
	ext_Result::sendResult('', false, $dir.": ".$GLOBALS["error_msg"]["opendir"]);

	// Read directory
	while(($new_item = readdir($handle))!==false) {

		$abs_new_item = get_abs_item($dir, $new_item);

		if ($new_item == "." || $new_item == "..") continue;
		if(!file_exists($abs_new_item)) //ext_Result::sendResult('', false, $dir."/$abs_new_item: ".$GLOBALS["error_msg"]["readdir"]);
		if(!get_show_item($dir, $new_item)) continue;

		$new_file_size = @filesize($abs_new_item);
		$tot_file_size += $new_file_size;
		$num_items++;

		if(get_is_dir($abs_new_item)) {
			if($GLOBALS["order"]=="mod") {
				$dir_list[$new_item] =
					@filemtime($abs_new_item);
			} else {	// order == "size", "type" or "name"
				$dir_list[$new_item] = $new_item;
			}
		} else {
			if($GLOBALS["order"]=="size") {
				$file_list[$new_item] = $new_file_size;
			} elseif($GLOBALS["order"]=="mod") {
				$file_list[$new_item] =
					@filemtime($abs_new_item);
			} elseif($GLOBALS["order"]=="type") {
				$file_list[$new_item] =
					get_mime_type($abs_new_item, "type");
			} else {	// order == "name"
				$file_list[$new_item] = $new_item;
			}
		}
	}
	closedir($handle);


	// sort
	if(is_array($dir_list)) {
		if($GLOBALS["order"]=="mod") {
			if($GLOBALS["direction"]=="ASC") arsort($dir_list);
			else asort($dir_list);
		} else {	// order == "size", "type" or "name"
			if($GLOBALS["direction"]=="ASC") ksort($dir_list);
			else krsort($dir_list);
		}
	}

	// sort
	if(is_array($file_list)) {
		if($GLOBALS["order"]=="mod") {
			if($GLOBALS["direction"]=="ASC") arsort($file_list);
			else asort($file_list);
		} elseif($GLOBALS["order"]=="size" || $GLOBALS["order"]=="type") {
			if($GLOBALS["direction"]=="ASC") asort($file_list);
			else arsort($file_list);
		} else {	// order == "name"
			if($GLOBALS["direction"]=="ASC") ksort($file_list);
			else krsort($file_list);
		}
	}
}
//------------------------------------------------------------------------------
function print_table($dir, $list, $allow) {	// print table of files
	global $dir_up;
	if(!is_array($list)) return;
	if( $dir != "" || strstr( $dir, _EXT_PATH ) ) {
	echo "<tr class=\"sectiontableentry1\"><td valign=\"baseline\"><a href=\"".make_link("list",$dir_up,NULL)."\">";
	echo "<img border=\"0\" align=\"absmiddle\" src=\""._EXT_URL."/images/_up.png\" ";
	echo "alt=\"".$GLOBALS["messages"]["uplink"]."\" title=\"".$GLOBALS["messages"]["uplink"]."\"/>&nbsp;&nbsp;..</a></td>\n";
	echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
	echo "</tr>";
	}
	$i = 0;

	while(list($item,) = each($list)){
		if( $item == 'index.html') {
			continue;
		}
		$abs_item=get_abs_item($dir,$item);

		$is_writable = is_writable( $abs_item );
		$is_chmodable = $GLOBALS['ext_File']->is_chmodable( $abs_item );
		$is_readable = is_readable( $abs_item );
		$is_deletable = $GLOBALS['ext_File']->is_deletable( $abs_item );

		$file_info = @stat( $abs_item );

		$is_file = false;
		//if(is_link($abs_item)) $extra=" -> ".@readlink($abs_item);
		if(@is_dir($abs_item)) {
			$link = make_link("list",get_rel_item($dir, $item),NULL);
		} else { //if(get_is_editable($dir,$item) || get_is_image($dir,$item)) {
			$link = make_link("download",$dir,$item);
			$is_file = true;
		} //else $link = "";

		$class = $i % 2 ? 'sectiontableentry1' : 'sectiontableentry2';

		//echo "<tr class=\"rowdata\">"
		echo '<tr class="'.$class.'">';

	// Icon + Link
		echo "<td nowrap=\"nowrap\">";
		if($is_readable) {
			echo "<a href=\"".$link."\""; if( $is_file ) echo " title=\"".$GLOBALS["messages"]["downlink"].": ".$item."\"";
			echo ">";
		}
		//else echo "<A>";
		echo "<img border=\"0\" ";
		echo "align=\"absmiddle\" vspace=\"5\" hspace=\"5\" src=\""._EXT_URL."/images/".get_mime_type($abs_item, "img")."\" alt=\"\">&nbsp;";
		$s_item=$item;
		if(strlen($s_item)>50) $s_item=substr($s_item,0,47)."...";
		$s_item = htmlspecialchars($s_item);
		if( !$is_file ) {
			echo '<strong>'.$s_item.'</strong>';
		}
		else {
			echo $s_item;
		}
		if( $is_readable ) {
			echo "</a>";	// ...$extra...
		}
		echo "</td>\n";
	// Size
		echo "<td>".parse_file_size(get_file_size($abs_item))."</td>\n";
	// type
		echo "<td>".get_mime_type($abs_item, "type")."</td>\n";
	// modified
		echo "<td>".parse_file_date(get_file_date($abs_item))."</td>\n";

		// actions
		echo "</tr>\n";
		$i++;
	}
}
//------------------------------------------------------------------------------
// MAIN FUNCTION
function list_dir($dir) {			// list directory contents
	global $dir_up, $mosConfig_live_site, $_VERSION;

	$allow=($GLOBALS["permissions"]&01)==01;
	$admin=((($GLOBALS["permissions"]&04)==04) || (($GLOBALS["permissions"]&02)==02));

	$dir_up = dirname($dir);
	if($dir_up==".") $dir_up = "";

	if(!get_show_item($dir_up,basename($dir))) ext_Result::sendResult('', false, $dir." : ".$GLOBALS["error_msg"]["accessdir"]);

	// make file & dir tables, & get total filesize & number of items
	make_tables($dir, $dir_list, $file_list, $tot_file_size, $num_items);


	$dirs = explode( "/", $dir );
	$implode = "";
	$dir_links = "<a href=\"".make_link( "list", "", null )."\">..</a>&nbsp;/&nbsp;";
	foreach( $dirs as $directory ) {
	if( $directory != "" ) {
		$implode .= $directory."/";
		$dir_links .= "<a href=\"".make_link( "list", $implode, null )."\">$directory</a>&nbsp;/&nbsp;";
	}
	}
	echo '<div class="componentheading">'.$GLOBALS["messages"]["actdir"].": ".$dir_links.'</div>';

	// Sorting of items
	$images = "&nbsp;<img width=\"10\" height=\"10\" border=\"0\" align=\"absmiddle\" src=\""._EXT_URL."/images/";
	if($GLOBALS["direction"]=="ASC") {
		$_srt = "DESC";	$images .= "_arrowup.gif\" alt=\"^\">";
	} else {
		$_srt = "ASC";	$images .= "_arrowdown.gif\" alt=\"v\">";
	}

	// Toolbar
	/*echo "<br><table width=\"95%\"><tr><td><table><tr>\n";

	// PARENT DIR
	echo "<td>";
	if( $dir != "" ) {
	echo "<a href=\"".make_link("list",$dir_up,NULL)."\">";
	echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" src=\""._EXT_URL."/images/_up.png\" ";
	echo "alt=\"".$GLOBALS["messages"]["uplink"]."\" title=\"".$GLOBALS["messages"]["uplink"]."\"></a>";
	}
	echo "</td>\n";
	// HOME DIR
	echo "<td><a href=\"".make_link("list",NULL,NULL)."\">";
	echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" src=\""._EXT_URL."/images/_home.gif\" ";
	echo "alt=\"".$GLOBALS["messages"]["homelink"]."\" title=\"".$GLOBALS["messages"]["homelink"]."\"></a></td>\n";
	// RELOAD
	echo "<td><a href=\"javascript:location.reload();\"><img border=\"0\" width=\"22\" height=\"22\" ";
	echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_refresh.gif\" alt=\"".$GLOBALS["messages"]["reloadlink"];
	echo "\" title=\"".$GLOBALS["messages"]["reloadlink"]."\"></A></td>\n";
	// SEARCH
	echo "<td><a href=\"".make_link("search",$dir,NULL)."\">";
	echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" src=\""._EXT_URL."/images/_search.gif\" ";
	echo "alt=\"".$GLOBALS["messages"]["searchlink"]."\" title=\"".$GLOBALS["messages"]["searchlink"];
	echo "\"></a></td>\n";

	echo "<td><img src=\"images/menu_divider.png\" height=\"22\" width=\"2\" border=\"0\" alt=\"|\" /></td>";

	// Joomla Sysinfo
	echo "<td><a href=\"".make_link("sysinfo",$dir,NULL)."\">";
	echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" src=\""._EXT_URL."/images/systeminfo.gif\" ";
	echo "alt=\"" . $GLOBALS['messages']['mossysinfolink'] . "\" title=\"" .$GLOBALS['messages']['mossysinfolink'] . "\"></a></td>\n";

	echo "<td><img src=\"images/menu_divider.png\" height=\"22\" width=\"2\" border=\"0\" alt=\"|\" /></td>";

	if($allow) {
		// COPY
		echo "<td><a href=\"javascript:Copy();\"><img border=\"0\" width=\"22\" height=\"22\" ";
		echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_copy.gif\" alt=\"".$GLOBALS["messages"]["copylink"];
		echo "\" title=\"".$GLOBALS["messages"]["copylink"]."\"></a></td>\n";
		// MOVE
		echo "<td><a href=\"javascript:Move();\"><img border=\"0\" width=\"22\" height=\"22\" ";
		echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_move.gif\" alt=\"".$GLOBALS["messages"]["movelink"];
		echo "\" title=\"".$GLOBALS["messages"]["movelink"]."\"></A></td>\n";
		// DELETE
		echo "<td><a href=\"javascript:Delete();\"><img border=\"0\" width=\"22\" height=\"22\" ";
		echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_delete.gif\" alt=\"".$GLOBALS["messages"]["dellink"];
		echo "\" title=\"".$GLOBALS["messages"]["dellink"]."\"></A></td>\n";
		// CHMOD
		echo "<td><a href=\"javascript:Chmod();\"><img border=\"0\" width=\"22\" height=\"22\" ";
		echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_chmod.gif\" alt=\"chmod\" title=\"" . $GLOBALS['messages']['chmodlink'] . "\"></a></td>\n";
		// UPLOAD
		if(ini_get("file_uploads")) {
			echo "<td><a href=\"".make_link("upload",$dir,NULL)."\">";
			echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
			echo "src=\""._EXT_URL."/images/_upload.gif\" alt=\"".$GLOBALS["messages"]["uploadlink"];
			echo "\" title=\"".$GLOBALS["messages"]["uploadlink"]."\"></A></td>\n";
		} else {
			echo "<td><img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
			echo "src=\""._EXT_URL."/images/_upload_.gif\" alt=\"".$GLOBALS["messages"]["uploadlink"];
			echo "\" title=\"".$GLOBALS["messages"]["uploadlink"]."\"></td>\n";
		}
		// ARCHIVE
		if($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]) {
			echo "<td><a href=\"javascript:Archive();\"><img border=\"0\" width=\"22\" height=\"22\" ";
			echo "align=\"absmiddle\" src=\""._EXT_URL."/images/_archive.gif\" alt=\"".$GLOBALS["messages"]["comprlink"];
			echo "\" title=\"".$GLOBALS["messages"]["comprlink"]."\"></A></td>\n";
		}
	} else {
		// COPY
		echo "<td><img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
		echo "src=\""._EXT_URL."/images/_copy_.gif\" alt=\"".$GLOBALS["messages"]["copylink"]."\" title=\"";
		echo $GLOBALS["messages"]["copylink"]."\"></td>\n";
		// MOVE
		echo "<td><img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
		echo "src=\""._EXT_URL."/images/_move_.gif\" alt=\"".$GLOBALS["messages"]["movelink"]."\" title=\"";
		echo $GLOBALS["messages"]["movelink"]."\"></td>\n";
		// DELETE
		echo "<td><img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
		echo "src=\""._EXT_URL."/images/_delete_.gif\" alt=\"".$GLOBALS["messages"]["dellink"]."\" title=\"";
		echo $GLOBALS["messages"]["dellink"]."\"></td>\n";
		// UPLOAD
		echo "<td><img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
		echo "src=\""._EXT_URL."/images/_upload_.gif\" alt=\"".$GLOBALS["messages"]["uplink"];
		echo "\" title=\"".$GLOBALS["messages"]["uplink"]."\"></td>\n";
	}

	// ADMIN & LOGOUT
	if($GLOBALS["require_login"]) {
		echo "<td>::</td>";
		// ADMIN
		if($admin) {
			echo "<td><a href=\"".make_link("admin",$dir,NULL)."\">";
			echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
			echo "src=\""._EXT_URL."/images/_admin.gif\" alt=\"".$GLOBALS["messages"]["adminlink"]."\" title=\"";
			echo $GLOBALS["messages"]["adminlink"]."\"></A></td>\n";
		}
		// LOGOUT
		echo "<td><a href=\"".make_link("logout",NULL,NULL)."\">";
		echo "<img border=\"0\" width=\"22\" height=\"22\" align=\"absmiddle\" ";
		echo "src=\""._EXT_URL."/images/_logout.gif\" alt=\"".$GLOBALS["messages"]["logoutlink"]."\" title=\"";
		echo $GLOBALS["messages"]["logoutlink"]."\"></a></td>\n";
	}
	// Logo
	echo "<td style=\"padding-left:10px;\">";
	//echo "<div style=\"margin-left:10px;float:right;\" width=\"305\" >";
	echo "<a href=\"".$GLOBALS['ext_home']."\" target=\"_blank\" title=\"joomlaXplorer Project\"><img border=\"0\" align=\"absmiddle\" id=\"ext_logo\" style=\"filter:alpha(opacity=10);-moz-opacity:.10;opacity:.10;\" onmouseover=\"opacity('ext_logo', 60, 99, 500);\" onmouseout=\"opacity('ext_logo', 100, 60, 500);\" ";
	echo "src=\""._EXT_URL."/images/logo.gif\" align=\"right\" alt=\"" . $GLOBALS['messages']['logolink'] . "\"></a>";
	//echo "</div>";
	echo "</td>\n";

	echo "</tr></table></td>\n";

	// Create File / Dir

	if($allow && is_writable($GLOBALS['home_dir'].'/'.$dir)) {
		echo "<td align=\"right\"><table><form action=\"".make_link("mkitem",$dir,NULL)."\" method=\"post\">\n<tr><td>";
		echo "<select name=\"mktype\"><option value=\"file\">".$GLOBALS["mimes"]["file"]."</option>";
		echo "<option value=\"dir\">".$GLOBALS["mimes"]["dir"]."</option></select>\n";
		echo "<input name=\"mkname\" type=\"text\" size=\"15\">";
		echo "<input type=\"submit\" value=\"".$GLOBALS["messages"]["btncreate"];
		echo "\"></td></tr></form></table></td>\n";
	}

	echo "</tr></table>\n";
	*/
	// End Toolbar


	// Begin Table + Form for checkboxes
	echo"<table width=\"95%\" cellpadding=\"5\" cellspacing=\"2\"><tr class=\"sectiontableheader\">\n";
	echo "<th width=\"44%\"><b>\n";
	if($GLOBALS["order"]=="name") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<a href=\"".make_link("list",$dir,NULL,"name",$new_srt)."\">".$GLOBALS["messages"]["nameheader"];
	if($GLOBALS["order"]=="name") echo $images;
	echo "</a></b></td>\n<th width=\"10%\"><b>";
	if($GLOBALS["order"]=="size") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<a href=\"".make_link("list",$dir,NULL,"size",$new_srt)."\">".$GLOBALS["messages"]["sizeheader"];
	if($GLOBALS["order"]=="size") echo $images;
	echo "</a></b></th>\n<th width=\"12%\" ><b>";
	if($GLOBALS["order"]=="type") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<a href=\"".make_link("list",$dir,NULL,"type",$new_srt)."\">".$GLOBALS["messages"]["typeheader"];
	if($GLOBALS["order"]=="type") echo $images;
	echo "</a></b></th>\n<th width=\"12%\"><b>";
	if($GLOBALS["order"]=="mod") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<a href=\"".make_link("list",$dir,NULL,"mod",$new_srt)."\">".$GLOBALS["messages"]["modifheader"];
	if($GLOBALS["order"]=="mod") echo $images;
	echo "</a></b></th></tr>\n";

	// make & print Table using lists
	print_table($dir, make_list($dir_list, $file_list), $allow);

	// print number of items & total filesize
	echo "<tr><td colspan=\"4\"><hr/></td></tr><tr>\n<td>&nbsp;</td>";
	echo "<td>".$num_items." ".$GLOBALS["messages"]["miscitems"]." ".parse_file_size($tot_file_size)."</td>\n";
	echo "<td>&nbsp;</td><td>&nbsp;</td>";
	echo "</tr>\n<tr><td colspan=\"4\"><hr/></td></tr></table>\n";

}
//------------------------------------------------------------------------------
?>
