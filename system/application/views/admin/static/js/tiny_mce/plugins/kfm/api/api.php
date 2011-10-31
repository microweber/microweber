<?php
# see ../license.txt for licensing

function kfm_api_createDirectory($parent,$name){
	$r=kfm_createDirectory($parent,$name);
	foreach($r['directories'] as $dir)if($dir[0]==$name)return $dir[2];
	return 0;
}
function kfm_api_getDirectoryId($address){
	global $kfmdb;
	$arr=explode('/',$address);
	$curdir=1;
	if($arr[count($arr)-1]==''&&count($arr)>1)array_pop($arr);
	foreach($arr as $n){
		$r=db_fetch_row("select id from ".KFM_DB_PREFIX."directories where parent=".$curdir." and name='".sql_escape($n)."'");
		if($r===false || !count($r))return 0;
		$curdir=$r['id'];
	}
	return $curdir;
}
function kfm_api_removeFile($id){
	$f=kfmFile::getInstance($id);
	$p=$f->parent;
	$f->delete();
	return kfm_loadFiles($p);
}
$kfm_api_auth_override=1;
