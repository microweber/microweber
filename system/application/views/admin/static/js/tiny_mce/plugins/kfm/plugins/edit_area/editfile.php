<?php
require_once('../../initialise.php');
$file_ids = $kfm_session->get('editarea_files');
if(!isset($_GET['fid']))die ('no file id specified');
if(!$file_ids){
  $file_ids = explode(",", $_GET['fid']);
}else{
  $file_ids = explode(',', $file_ids);
  $file_ids = array_merge($file_ids, explode(",", $_GET['fid']));
}
$file_ids = array_unique($file_ids);
$kfm_session->set('editarea_files', implode(',', $file_ids));

$f=new kfmFile($_GET['fid']);
if(!$f)die ('error retrieving file');
function getSyntax($ext){
	switch($ext){
		case 'html':
			$lang='html';
			break;
		case 'c':
			$lang='c';
			break;
		case 'cpp':
			$lang='cpp';
			break;
		case 'css':
			$lang='css';
			break;
		case 'js':
			$lang='js';
			break;
		case 'pas':
			$lang='pas';
			break;
		case 'php':
			$lang='php';
			break;
		case 'python':
			$lang='python';
			break;
		case 'sql':
			$lang='sql';
			break;
		case 'vb':
			$lang='vb';
			break;
		case 'xml':
			$lang='xml';
			break;
		default:
			$lang='';
			break;
	}
  return $lang;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>EditArea - the code editor in a textarea</title>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
	<script language="Javascript" type="text/javascript" src="edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
		editAreaLoader.init({
			id: "editareacontent"
			,start_highlight: true
			,allow_resize: "both"
			,allow_toggle: true
			,language: "en"
			,toolbar: "save,charmap,|,search,go_to_line,|,undo,redo,|,select_font,|,syntax_selection,|, change_smooth_selection,highlight,reset_highlight,|,help"
			,save_callback:"save_document"
			,plugins: "charmap"
			,charmap_default: "arrows"
      ,is_multi_files: true
      ,EA_load_callback: "load_ea_files"
      ,EA_file_close_callback: "close_ea_file"
		});
		function save_document(id, content){
      var f = editAreaLoader.getCurrentFile("editareacontent");
			parent.x_kfm_saveTextFile(f.id, content, function(res){
        editAreaLoader.setFileEditedMode("editareacontent", String(f.id), false);
				//parent.kfm_pluginIframeMessage('document saved');	
			});
		}
    function load_ea_files(element_id){
      <?php foreach($file_ids as $fid){
        $f = kfmFile::getInstance($fid);
        print "
        editAreaLoader.openFile('editareacontent', {id:$fid,text:".json_encode($f->getContent()).",syntax:'".getSyntax($f->getExtension())."',title:'".$f->name."'});";
      }?>
    }
    function close_ea_file(f){
      jQuery.get("remove_file.php?fid="+f.id, function(res){eval(res);});
      return true;
    }
	</script>
<style type="text/css">
*{
	margin:0;
	padding:0;
}
</style>
<head>
<body>
<textarea id="editareacontent" style="width:100%;height:90%;"></textarea>
</body>
</html>
