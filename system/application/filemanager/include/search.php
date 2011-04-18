<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: search.php 143 2009-04-01 18:48:16Z soeren $
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
 * File-Search Functions
 */

function find_item($dir,$pat,&$list,$recur, $content) {	// find items
	$homedir = realpath($GLOBALS['home_dir']);
	$handle = @$GLOBALS['ext_File']->opendir(get_abs_dir($dir));

	if($handle===false && $dir=="") {
		$handle = @$GLOBALS['ext_File']->opendir($homedir . $GLOBALS['separator']);
	}

	if($handle===false) {
		ext_Result::sendResult('search', false, $dir.": ".$GLOBALS["error_msg"]["opendir"]);
	}

	while(($new_item=$GLOBALS['ext_File']->readdir($handle))!==false) {
		if( is_array( $new_item ))	{
			$abs_new_item = $new_item;
		} else {
			$abs_new_item = get_abs_item($dir, $new_item);
		}
		if(!$GLOBALS['ext_File']->file_exists($abs_new_item)) continue;

		if(!get_show_item($dir, $new_item)) continue;

		$isDir = get_is_dir($abs_new_item);
		// match?
		if(@eregi($pat,$new_item)) {
		    $list[]=array($dir,$new_item);
		} else if (!$isDir) {
		    if ($content && $GLOBALS['ext_File']->filesize($abs_new_item) < 524288) {

    		  $data = $GLOBALS['ext_File']->file_get_contents( $abs_new_item );
              //$data = fread($handle, 524288); // Only read first 512kb
    		  if (@eregi($pat, $data)) {
    		      $list[]=array($dir,$new_item);
    		  }
		    }
		}

		// search sub-directories
		if($isDir && $recur) {
			find_item($abs_new_item,$pat,$list,$recur, $content);
		}
	}

	$GLOBALS['ext_File']->closedir($handle);

}
//------------------------------------------------------------------------------
function make_list($dir,$item,$subdir, $content) {	// make list of found items
	// convert shell-wildcards to PCRE Regex Syntax
	$pat=str_replace("?",".",str_replace("*",".*",str_replace(".","\\.",$item)));

	// search
	find_item($dir,$pat,$list,$subdir, $content);
	if(is_array($list)) sort($list);
	return $list;
}
//------------------------------------------------------------------------------
function get_result_table($list) {			// print table of found items
	if(!is_array($list)) return;

	$cnt = count($list);
	$response = '';
	for($i=0;$i<$cnt;++$i) {
		$dir = $list[$i][0];	$item = $list[$i][1];
		$s_dir=$dir;	if(strlen($s_dir)>65) $s_dir=substr($s_dir,0,62)."...";
		$s_item=$item;	if(strlen($s_item)>45) $s_item=substr($s_item,0,42)."...";
		$link = "";	$target = "";

		if(get_is_dir($dir,$item)) {
			$img = "dir.png";
			$link = make_link("list",get_rel_item($dir, $item),NULL);
		} else {
			$img = get_mime_type( $item, "img");
			//if(get_is_editable($dir,$item) || get_is_image($dir,$item)) {
			$link = $GLOBALS["home_url"]."/".get_rel_item($dir, $item);
			$target = "_blank";
			//}
		}

		$response .= "<tr><td>" . "<img border=\"0\" width=\"22\" height=\"22\" ";
		$response .= "align=\"absmiddle\" src=\""._EXT_URL."/images/" . $img . "\" alt=\"\" />&nbsp;";
		/*if($link!="")*/
		$response .= "<a href=\"".$link."\" target=\"".$target."\">";
		//else echo "<a>";
		$response .= $s_item."</a></td><td><a href=\"" . make_link("list",$dir,null)."\"> /";
		$response .= $s_dir."</a></td></tr>\n";
	}
	return $response;
}
function get_result_array($list) {			// print table of found items
	if(!is_array($list)) return;

	$cnt = count($list);
	$array = array();
	for($i=0;$i<$cnt;++$i) {
		$dir = $list[$i][0];	$item = $list[$i][1];
		$s_dir=str_replace($GLOBALS['home_dir'], '', $dir );	
		if(strlen($s_dir)>65) $s_dir=substr($s_dir,0,62)."...";
		$s_item=$item;	if(strlen($s_item)>45) $s_item=substr($s_item,0,42)."...";
		$link = "";	$target = "";
		
		
		if(get_is_dir($dir,$item)) {
			$img = "dir.png";
			$link = make_link("list",get_rel_item($dir, $item),NULL);
		} else {
			$img = get_mime_type( $item, "img");
			//if(get_is_editable($dir,$item) || get_is_image($dir,$item)) {
			$link = $GLOBALS["home_url"]."/".get_rel_item($dir, $item);
			$target = "_blank";
			//}
		}
		$array[$i]['last_mtime'] = $GLOBALS['ext_File']->filemtime($GLOBALS['home_dir'].'/'.$dir.'/'.$item);
		$array[$i]['file_id'] = md5($s_dir.$s_item);
		$array[$i]['dir'] = str_replace($GLOBALS['home_dir'], '', $dir );
		$array[$i]['s_dir'] = empty($s_dir) ? '' : $s_dir;
		$array[$i]['file'] = $s_item;
		$array[$i]['link'] = $link;
		$array[$i]['icon'] = _EXT_URL."/images/$img";
	}
	return $array;
}
//------------------------------------------------------------------------------
function search_items($dir) {	// search for item
    if( empty($dir) && !empty($GLOBALS['__POST']["item"]) ) {
        $dir = $GLOBALS['__POST']["item"];
    }
	if(isset($GLOBALS['__POST']["searchitem"])) {

		$searchitem=stripslashes($GLOBALS['__POST']["searchitem"]);
		$subdir= !empty( $GLOBALS['__POST']["subdir"] );
        $content = $GLOBALS['__POST']["content"];
		$list=make_list($dir,$searchitem,$subdir, $content);
	} else {
		$searchitem=NULL;
		$subdir=true;
	}

	if( empty( $searchitem)) {
		show_searchform($dir);
		return;
	}	

	// Results in JSON	
	$items['totalCount'] = count($list);
	$result = get_result_array($list);
	$start = (int)$GLOBALS['__POST']["start"];
	$limit = (int)$GLOBALS['__POST']["limit"];
	
	if( $start < $items['totalCount'] && $limit < $items['totalCount'] ) {
		$result = array_splice($result, $start, $limit );
	}
	
	$items['items'] = $result;
	$json = new ext_Json();
	
	while( @ob_end_clean() );
	
	echo $json->encode($items);

}

function show_searchform($dir='') {
	?>
{
     "title":"<?php echo $GLOBALS["messages"]["searchlink"] ?>",
     "height":300,
     "autoScroll":true,

     items: new Ext.DataView({
     	"id": "dataview",
         tpl: new Ext.XTemplate(
	        '<tpl for=".">',
	        '<div class="search-item">',
	            '<h3>',
	            '<a onclick="selectFile(\'{dir}\', \'{file}\');Ext.getCmp(\'dialog\').destroy();return false;" href="#" target="_blank">{s_dir}/{file}, {lastModified:date("M j, Y")}</a>',
	            '</h3>',
	        '</div></tpl>'
	    ),
         store: new Ext.data.Store({
		        proxy: new Ext.data.HttpProxy({
		            url: "<?php echo $GLOBALS['script_name'] ?>"
		        }),
		        reader: new Ext.data.JsonReader({
		            root: 'items',
		            totalProperty: 'totalCount',
		            id: 'file_id'
		        }, [			            
		            {name: 'fileId', mapping: 'file_id'},
		            {name: 'file', mapping: 'file'},
		            {name: 'dir', mapping: 'dir'},
		            {name: 's_dir', mapping: 's_dir'},
		            {name: 'lastModified', mapping: 'last_mtime', type: 'date', dateFormat: 'timestamp'}
		        ]),
		        baseParams: {
		        	limit:20, 
		        	option: "com_extplorer",
		        	action:"search",
		        	dir: "<?php echo $dir ?>",
		        	content: '0',
	        		subdir: '1'
		        }
		    }),
         itemSelector: 'div.search-item'
     }),

     tbar: [
         'Search: ', ' ',
         new Ext.app.SearchField({
             store: Ext.getCmp("dataview").store,
             width:420
         }),' ',' ',' ',
         {	
         	"id": "searchsubdir",
			text: "Subdirs",
			tooltip: "<?php echo ext_Lang::msg( 'miscsubdirs', true ) ?>?",
			name: "subdir",
			"listeners": {
				"toggle": {
					fn: function(btn, pressed) {
							Ext.getCmp("dataview").store.baseParams.subdir = (pressed ? '1' : '0');
					}
				}
			},
			 enableToggle: true,
			 pressed: true
		},' ',' ',' ',{
			
			"id": "searchcontent",
			text: "Content",
			tooltip: "<?php echo ext_Lang::msg( 'misccontent', true ) ?>?",
			name: 'content',
			"listeners": {
				"toggle": {
					fn: function(btn, pressed) {
							Ext.getCmp("dataview").store.baseParams.content = (pressed ? '1' : '0');
							
					}
				}
			},
			 enableToggle: true,
			 pressed: false
		}
     ],

     bbar: new Ext.PagingToolbar({
         store: Ext.getCmp("dataview").store,
         pageSize: 20,
         displayInfo: true,
         displayMsg: 'Results {0} - {1} of {2}',
         emptyMsg: "No files to display"
     })
}

<?php 
	
}
//------------------------------------------------------------------------------
