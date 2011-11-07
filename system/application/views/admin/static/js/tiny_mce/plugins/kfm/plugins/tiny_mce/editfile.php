<?php
require_once('../../initialise.php');
if(!isset($_GET['fid']))die('no fid given');
$f=new kfmFile($_GET['fid']);
?>
<html>
<head>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	plugins : "save,table,contextmenu,paste,layer,style,advlink,searchreplace",
	theme_advanced_buttons1: "save,code,undo,redo,cleanup,pastetext,pasteword,selectall,replace,help",
	theme_advanced_buttons2: "link,unlink,anchor,image,table,charmap,formatselect,fontselect,fontsizeselect,styleselect",
	theme_advanced_buttons3: "bold,italic,underline,strikethrough,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright,justifyfull,sub,sup,forecolor,backcolor,removeformat,insertlayer,styleprops",
	theme_advanced_buttons1_add_before : "save,separator",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	plugin_insertdate_dateFormat : "%Y-%m-%d",
	plugin_insertdate_timeFormat : "%H:%M:%S",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	file_browser_callback: 'kfmplugin_tiny_mce_select_file',
	convert_urls:false
});
function save_file(){
	parent.x_kfm_saveTextFile(<?php echo $f->id;?>,tinyMCE.activeEditor.getContent(), parent.kfm_showMessage);
}
var chooseFileBackup;
function kfmplugin_tiny_mce_select_file(field_name, url, type, win){
	parent.focus();
	chooseFileBackup=parent.kfm_file_bits.dblclick;
	var editorBookmark = tinyMCE.activeEditor.selection.getBookmark();
	parent.kfm_file_bits.dblclick=function(e){
		e=new parent.Event(e);
		var el=e.target;
		while(!el.file_id && el)el=el.parentNode;
		if(!el)return;
		var id=el.file_id;
		parent.kfm_selectNone();
		var fname;
		parent.kfm_pluginIframeShow();
		parent.x_kfm_getFileUrls([id],function(urls){
			var returl=urls[0];
			win.document.forms[0].elements[field_name].value = returl;
			fname=returl.replace(/^.*[\/\\]/g, '');
			win.document.forms[0].elements['alt'].value=fname;
			//win.document.forms[0].elements['title'].value=fname;
			tinyMCE.activeEditor.selection.moveToBookmark(editorBookmark);
			win.ImageDialog.update();
		});
		parent.kfm_file_bits.dblclick=chooseFileBackup;
	  parent.x_kfm_loadFiles(parent.kfm_cwd_id,true,parent.kfm_refreshFiles);
	}
	parent.kfm_pluginIframeHide();

}
</script>
<style type="text/css">
body{
	margin:0;
	padding:0;
}
</style>
</head>
<body>
<form action="javascript:save_file()">
<textarea style="margin:0px auto;width:100%;height:100%;"><?php echo $f->getContent(); ?></textarea>
</form>
</body>
</html>
