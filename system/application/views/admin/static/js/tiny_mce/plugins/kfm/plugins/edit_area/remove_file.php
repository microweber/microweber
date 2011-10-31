<?php
require_once('../../initialise.php');
if(!isset($_GET['fid']))die ('alert("no file id specified");');
$file_ids = $kfm_session->get('editarea_files');
if($file_ids){
  $ids = explode(",",$file_ids);
  for($i = 0; $i < count($ids); $i++){
    if($ids[$i]==$_GET['fid']) unset($ids[$i]);
  }
  $new_ids = trim(implode(',', $ids));
  $kfm_session->set('editarea_files', $new_ids);
  if($new_ids == ''){
    print "parent.kfm_pluginIframeHide();";
  }
}
?>
