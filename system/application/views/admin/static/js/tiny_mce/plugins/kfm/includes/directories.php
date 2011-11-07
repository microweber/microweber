<?php
function _createDirectory($parent,$name){
	global $kfm;
	if(!$kfm->setting('allow_directory_create'))return 'error: '.kfm_lang('permissionDeniedCreateDirectory');
	$dir=kfmDirectory::getInstance($parent);
	$dir->createSubdir($name);
	if($dir->hasErrors()) return;
	return kfm_loadDirectories($parent);
}
function _deleteDirectory($id,$recursive=0){
	$dir=kfmDirectory::getInstance($id);
	$dir->delete();
	return kfm_loadDirectories($dir->pid,$id);
}
function _getDirectoryDbInfo($id){
	global $kfmdb;
	if(!isset($_GLOBALS['cache_directories'][$id])){
		$_GLOBALS['cache_directories'][$id]=db_fetch_row("select * from ".KFM_DB_PREFIX."directories where id=".$id);
	}
	return $_GLOBALS['cache_directories'][$id];
}
function _getDirectoryParents($pid,$type=1){
	# type is 1:absolute, 2:relative to domain
	if($pid<2)return $GLOBALS['rootdir'];
	$db=_getDirectoryDbInfo($pid);
	return _getDirectoryParents($db['parent'],$type).$db['name'].'/';
}
function _getDirectoryParentsArr($dir,$path=array()){
	$db=_getDirectoryDbInfo($dir);
	if(!$db)return $path;
	$pdir=$db['parent'];
	array_unshift($path,$pdir);
	if($pdir>1)$path=_getDirectoryParentsArr($pdir,$path);
	return $path;
}
function _loadDirectories($pid,$oldpid=0){
	global $kfmdb;
	$dir=kfmDirectory::getInstance($pid);
	$pdir=str_replace($GLOBALS['rootdir'],'',$dir->path());
	$directories=array();
	foreach($dir->getSubdirs() as $subDir){
		$directories[]=array($subDir->name,$subDir->hasSubdirs(),$subDir->id,$subDir->maxWidth(),$subDir->maxHeight());
	}
	sort($directories);
	return array(
		'parent'=>$pid,
		'oldpid'=>$oldpid,
		'reqdir'=>$pdir,
		'directories'=>$directories,
		'properties'=>$dir->getProperties()
	);
}
function _moveDirectory($from,$to){
	global $kfm;
	if(!$kfm->setting('allow_directory_move'))return 'error: '.kfm_lang('permissionDeniedMoveDirectory');
	$dir=kfmDirectory::getInstance($from);
	$dir->moveTo($to);
	if($dir->hasErrors()) return;
	return _loadDirectories(1);
}
function _renameDirectory($fid,$newname){
	global $kfm;
	if(!$kfm->setting('allow_directory_edit'))return 'error: '.kfm_lang('permissionDeniedEditDirectory');
	$dir=kfmDirectory::getInstance($fid);
	$dir->rename($newname);
	return _loadDirectories($dir->pid);
}
function _rmdir($pid){
	return _deleteDirectory($pid);
}
function _setDirectoryMaxSizeImage($fid,$width,$height){
	$dir=kfmDirectory::getInstance($fid);
	$dir->setDirectoryMaxSizeImage($width,$height);
}
function kfm_rmMixed($files=array(), $directories=array()){
	$filecount=0;
	$dircount=0;
	foreach($files as $fid){
		$file=kfmFile::getInstance($fid);
		if($file->delete())$filecount++;
	}
	foreach($directories as $did){
		$dir=new kfmDirectory($did);
		if($dir->delete())$dircount++;
	}
}
