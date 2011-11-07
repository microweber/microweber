<?php
function md5_of_dir($folder) {
  $dircontent = scandir($folder);
	$ret='';
  foreach($dircontent as $filename) {
    if ($filename != '.' && $filename != '..') {
      if (filemtime($folder.$filename) === false) return false;
      $ret.=date("YmdHis", filemtime($folder.$filename)).$filename;
    }
  }
  return md5($ret);
}
function delete_old_md5s($folder) {
	$olddate=time()-3600;
  $dircontent = scandir($folder);
  foreach($dircontent as $filename) {
    if (strlen($filename)==32 && filemtime($folder.$filename) && filemtime($folder.$filename)<$olddate) unlink($folder.$filename);
  }
}
