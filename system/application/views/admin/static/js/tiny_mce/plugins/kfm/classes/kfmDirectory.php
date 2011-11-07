<?php
class kfmDirectory extends kfmObject{
	static $instances=array();
	var $subDirs=array();
	private $maxWidth;
	private $maxHeight;
	public $relpath = false; // Cache for path relative to kfm root
	public $relpath_user = false; // Cache for path relative to user root
	function __construct($id=1){
		parent::__construct();
		$this->id=$id;
		if(!$id)return;
		$res=db_fetch_row("SELECT * FROM ".KFM_DB_PREFIX."directories WHERE id=".$this->id);
		if(!$res)return $this->id=0;
		$this->name=$res['name'];
		$this->pid=(int)$res['parent'];
		$this->maxWidth=(int)$res['maxwidth'];
		$this->maxHeight=(int)$res['maxheight'];
	}
	function addFile($file){
		global $kfm;
		if(!$kfm->setting('allow_file_create'))return $this->error(kfm_lang('permissionDeniedCreateFile'));
		if(is_numeric($file))$file=kfmFile::getInstance($file);
		if(!$this->isWritable())return $this->error(kfm_lang('fileNotCreatedDirUnwritable',$file->name));
		copy($file->path,$this->path().'/'.$file->name);
		$id=$file->addToDb($file->name,$this->id);
		if($file->isImage()){
			$file=kfmImage::getInstance($file->id);
			$newFile=kfmImage::getInstance($id);
			$newFile->setCaption($file->caption);
			if($this->maxWidth>0 && $this->maxHeight>0 && ($newFile->width>$this->maxWidth || $newFile->height>$this->maxHeight)){
				$newFile->resize($this->maxWidth,$this->maxHeight);
			}
		}
		else $newFile=kfmFile::getInstance($id);
		$newFile->setTags($file->getTags());
		return true;
	}
	function addSubdirToDb($name){
		global $kfm;
		$sql="INSERT INTO ".KFM_DB_PREFIX."directories (name,parent) VALUES('".sql_escape($name)."',".$this->id.")";
		$kfm->db->exec($sql);
		return $kfm->db->lastInsertId(KFM_DB_PREFIX.'directories','id');
	}
	function checkAddr($addr){
		return (
			strpos($addr,'..')===false&&
			strpos($addr,'.')!==0&&
			strpos($addr,'/.')===false);
	}
	function checkName($file=false){
		global $kfm;
		if($file===false)$file=$this->name;
		if(trim($file)==''|| trim($file)!=$file)return false;
		if($file=='.'||$file=='..')return false;
		foreach($kfm->setting('banned_folders') as $ban){
			if(($ban[0]=='/' || $ban[0]=='@')&&preg_match($ban,$file))return false;
			elseif($ban==strtolower(trim($file)))return false;
		}
		if(count($kfm->setting('allowed_folders'))){
			foreach($kfm->setting('allowed_folders') as $allow){
				if($allow[0]=='/' || $allow[0]=='@'){
					if(preg_match($allow, $file))return true;
				}else if($allow==strtolower($file)) return true;
			}
			return false;
		}
		return true;
	}
	function createSubdir($name){
		global $kfm;
		if(!$kfm->setting('allow_directory_create'))return $this->error(kfm_lang('permissionDeniedCreateDirectory'));
		$physical_address=$this->path().$name;
		$short_version=str_replace($GLOBALS['rootdir'],'',$physical_address);
		if(!$this->checkAddr($physical_address) || !$this->checkName($name)){
			$this->error(kfm_lang('illegalDirectoryName',$short_version));
			return false;
		}
		if(file_exists($physical_address)){
			$this->error(kfm_lang('alreadyExists',$short_version));
			return false;
		}
		mkdir($physical_address);
		if(!file_exists($physical_address)){
			$this->error(kfm_lang('failedCreateDirectoryCheck',$name));
			return false;
		}
		chmod($physical_address,octdec('0'.$kfm->setting('default_directory_permission')));
		return $this->addSubdirToDb($name);
	}
	function delete(){
		global $kfm;
		if(!$kfm->setting('allow_directory_delete'))return $this->error(kfm_lang('permissionDeniedDeleteDirectory'));
		$files=$this->getFiles();
		foreach($files as $f){
			if(!$f->delete())return false;
		}
		$subdirs=$this->getSubdirs();
		foreach($subdirs as $subdir){
			if(!$subdir->delete())return false;
		}
		rmdir($this->path());
		if(is_dir($this->path()))return $this->error('failed to delete directory '.$this->path());
		$kfm->db->exec("delete from ".KFM_DB_PREFIX."directories where id=".$this->id);
		return true;
	}
	function getCssSprites(){
		$groupby=16;
		$thumbsize=64;
		$files=$this->getFiles();
		$images=array();
		$i=-1;
		$j=0;
		if(!is_dir(WORKPATH.'css_sprites'))mkdir(WORKPATH.'css_sprites');
		foreach($files as $file){
			if(!$file->isImage() || !$file->thumb_path)continue;
			if(!($j%$groupby)){
				$i++;
				$images[$i]=array();
				$j=0;
			}
			$images[$i][$j]=$file->id;
			$j++;
		}
		$sprites=array();
		foreach($images as $igroup){
			$md5=md5($this->id.'_'.join(',',$igroup));
			$sprites[]=array('sprite'=>$md5,'files'=>$igroup);
			if(!file_exists(WORKPATH.'css_sprites/'.$md5.'.png')){
				$thumbs=array();
				for($i=0;$i<count($igroup);++$i){
					$fid=$igroup[$i];
					$file=kfmFile::getInstance($fid);
					$file->setThumbnail($thumbsize,$thumbsize);
					$thumbs[]="'".$file->thumb_path."'";
				}
				$cli=dirname(IMAGEMAGICK_PATH)."/montage -background transparent -geometry $thumbsize".'x'."$thumbsize -tile $groupby"."x1 ".join(' ',$thumbs).' '.WORKPATH.'css_sprites/'.$md5.'.png';
				$arr=array();
				exec($cli,$arr,$retval);
			}
		}
		return $sprites;
	}
	function getFiles(){
		$filesdb=db_fetch_all("select * from ".KFM_DB_PREFIX."files where directory=".$this->id);
		$fileshash=array();
		if(is_array($filesdb))foreach($filesdb as $r)$fileshash[$r['name']]=$r['id'];
		// { get files from directoryIterator, then sort them
		$tmp=array();
		foreach(new directoryIterator($this->path()) as $f){
			if($f->isDot())continue;
			if(is_file($this->path().$f) && kfmFile::checkName($f))$tmp[]=$f.'';
		}
		natsort($tmp);
		// }
		// { load file details from database
		$files=array();
		foreach($tmp as $filename){
			if(!isset($fileshash[$filename]))$fileshash[$filename]=kfmFile::addToDb($filename,$this->id);
			$file=kfmFile::getInstance($fileshash[$filename]);
			if(!$file)continue;
			if($file->isImage()){
				$file=kfmImage::getInstance($fileshash[$filename]);
				if($this->maxWidth>0 && $this->maxHeight>0 && ($file->width>$this->maxWidth || $file->height>$this->maxHeight)){
					$file->resize($this->maxWidth,$this->maxHeight);
				}
			}
			$files[]=$file;
			unset($fileshash[$filename]);
		}
		// }
		return $files;
	}
	static function getInstance($id=1){
		$id=(int)$id;
		if($id<1)return;
		if (!@array_key_exists($id,self::$instances)) {
			$dir=new kfmDirectory($id);
			if($dir->id==0)return false;
			self::$instances[$id]=$dir;
		}
		return self::$instances[$id];
	}

	/**
		Full path of directory
		*/
	function getPath(){
		global $kfm;
		$pathTmp=$this->name.'/';
		$pid=$this->pid;
		if(!$pid)return $GLOBALS['rootdir'];
		while($pid>1){
			$p=kfmDirectory::getInstance($pid);
			$pathTmp=$p->name.'/'.$pathTmp;
			$pid=$p->pid;
		}
		return file_join($kfm->files_root_path, $pathTmp);
	}
	/**
		* At the moment the path property is loaded for every directory instance. Maybe this is not required.
		* A cached function should be more efficient for the future. Therefor every $dir->path call should be
		* replaced by $dir->path();
		*/
	function path(){
		if(!isset($this->cached_path) || !$this->cached_path) $this->cached_path = $this->getPath();
		return $this->cached_path;
	}
	/**
		Path of directory relative to root.
		If $to_user_root is set to true, support for custom root folders is activated.
		The $to_user_root option should not be used for file retrieval but solely for display purposes.
		*/
	function relativePath($to_user_root = false){
		global $kfm;
		// Determin root_id and check for cashed path
		if($to_user_root){
			if($this->relpath_user) return $this->relpath_user;
			$root_id = $kfm->setting('root_folder_id');
		}else{
			if($this->relpath) return $this->relpath;
			$root_id = 1;
		}
		$pathTmp=''; // Return empty if we are the root folder
		$pid=$this->pid;
		if(!$pid)return $pathTmp;
		if($this->id == $root_id) return $pathTmp;
		$pathTmp = $this->name; // If not root, we need our name
		while($pid != $root_id ){
			$p=kfmDirectory::getInstance($pid);
			$pathTmp=$p->name.DIRECTORY_SEPARATOR.$pathTmp;
			$pid=$p->pid;
		}
		if($to_user_root){
			$this->relpath_user = $pathTmp;
		}else{
			$this->relpath = $pathTmp;
		}
		return $pathTmp;
	}
	function getProperties(){
		return array(
			'allowed_file_extensions' => '',
			'name'										=> $this->name,
			'path'										=> $this->relativePath(), #str_replace($_SERVER['DOCUMENT_ROOT'],'',$this->path()),
			'parent'									=> $this->pid,
			'writable'								=> $this->isWritable(),
			'maxWidth'								=> $this->maxWidth,
			'maxHeight'							 => $this->maxHeight
		);
	}
	function getSubdir($dirname, $create_unless_exists = false){
		global $kfm;
		$res=db_fetch_row('select id from '.KFM_DB_PREFIX.'directories where name="'.sql_escape($dirname).'" and parent='.$this->id);
		if($res)return kfmDirectory::getInstance($res['id']);
		else if(is_dir($this->path().$dirname)){
			$id = $this->addSubdirToDb($dirname);
			return kfmDirectory::getInstance($id);
		}elseif($create_unless_exists){
			$id = $this->createSubdir($dirname);
			return kfmDirectory::getInstance($id);
		}
		return false;
	}
	function getSubdirs(){
		global $kfm;
		$dir_iterator=new DirectoryIterator($this->path());
		$dirsdb=db_fetch_all("select id,name from ".KFM_DB_PREFIX."directories where parent=".$this->id);
		$dirshash=array();
		if(is_array($dirsdb))foreach($dirsdb as $r)$dirshash[$r['name']]=$r['id'];
		$directories=array();
		foreach($dir_iterator as $file){
			if($file->isDot())continue;
			if(!$file->isDir())continue;
			$filename=$file->getFilename();
			if(is_dir($this->path().$filename)&&$this->checkName($filename)){
				if(!array_key_exists($filename,$dirshash)){
					$this->addSubdirToDb($filename);
					$dirshash[$filename]=$kfm->db->lastInsertId(KFM_DB_PREFIX.'directories','id');
				}
				$directories[]=kfmDirectory::getInstance($dirshash[$filename]);
				unset($dirshash[$filename]);
			}
		}
		return $directories;
	}
	function hasSubdirs(){
		$dirs=new DirectoryIterator($this->path());
		foreach($dirs as $dir){
			if($dir->isDot())continue;
			if($dir->isDir())return true;
		}
		return false;
	}
	function isWritable(){
		return is_writable($this->path());
	}
	function isLink(){
		return is_link($this->path());
	}
	function moveTo($newParent){
		global $kfm;
		if(is_numeric($newParent))$newParent=kfmDirectory::getInstance($newParent);
		// { check for errors
			if($this->isLink()) return $this->error(kfm_lang('cannotMoveLink'));
			if(!$kfm->setting('allow_directory_move'))return $this->error(kfm_lang('permissionDeniedMoveDirectory'));
			if(strpos($newParent->path(),$this->path())===0) return $this->error(kfm_lang('cannotMoveIntoSelf'));
			if(file_exists(file_join($newParent->path(),$this->name)))return $this->error(kfm_lang('alreadyExists',$newParent->path().$this->name));
			if(!$newParent->isWritable())return $this->error(kfm_lang('isNotWritable',$newParent->path()));
		// }
		// { do the move and check that it was successful
			rename(rtrim($this->path(), ' /'),$newParent->path().'/'.$this->name);
			if(!file_exists($newParent->path().$this->name))return $this->error(kfm_lang('couldNotMoveDirectory',$this->path(),$newParent->path().$this->name));
		// }
		// { update database and kfmDirectory object
			$kfm->db->query("update ".KFM_DB_PREFIX."directories set parent=".$newParent->id." where id=".$this->id) or die('error: '.print_r($kfmdb->errorInfo(),true));
			$this->pid=$newParent->id;
			$this->cached_path=$this->getPath();
			$this->clearCache();
		// }
	}
	function rename($newname){
		global $kfm,$kfmDirectoryInstances;
		if(!$kfm->setting('allow_directory_edit'))return $this->error(kfm_lang('permissionDeniedEditDirectory'));
		if($this->isLink()) return $this->error(kfm_lang('cannotRenameLink'));
		if(!$this->isWritable())return $this->error(kfm_lang('permissionDeniedRename',$this->name));
		if(!$this->checkName($newname))return $this->error(kfm_lang('cannotRenameFromTo',$this->name,$newname));
		$parent=kfmDirectory::getInstance($this->pid);
		if(file_exists($parent->path().$newname))return $this->error(kfm_lang('aDirectoryNamedAlreadyExists',$newname));
		rename(rtrim($this->path(),' /'),file_join($parent->path(),rtrim($newname, ' /')));
		if(file_exists($this->path()))return $this->error(kfm_lang('failedRenameDirectory'));
		$kfm->db->query("update ".KFM_DB_PREFIX."directories set name='".sql_escape($newname)."' where id=".$this->id);
		$this->name=$newname;
		$this->cached_path=$this->path();
		$kfmDirectoryInstances[$this->id]=$this;
	}
	function setDirectoryMaxSizeImage($width=0,$height=0){
		global $kfm;
		$width=(int)$width;
		$height=(int)$height;
		if($width<0)$width=0;
		if($height<0)$height=0;
		if($width==$this->maxWidth && $height==$this->maxHeight)return;
		$this->maxWidth=$width;
		$this->maxHeight=$height;
		$kfm->db->exec("UPDATE ".KFM_DB_PREFIX."directories SET maxwidth=$width,maxheight=$height WHERE id=".$this->id);
	}
	/*
	 * Return the max image height for the directory if set, the default otherwise
	 */
	function maxHeight(){
		global $kfm;
		return $this->maxHeight > 0 ? $this->maxHeight : $kfm->setting('max_image_upload_height');
	}
	/*
	 * Return the max image width for the directory if set, the default otherwise
	 */
	function maxWidth(){
		global $kfm;
		return $this->maxWidth > 0 ? $this->maxWidth : $kfm->setting('max_image_upload_width');
	}
	/**
		Clear cashed properties
		*/
	function clearCache(){
		$this->relpath = false; # remove relative path cache
		$this->relpath_user = false;
	}
}
