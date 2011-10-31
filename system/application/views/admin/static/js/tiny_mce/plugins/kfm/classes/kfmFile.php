<?php
/**
 * Base file class
 */
class kfmFile extends kfmObject{
	static $instances = array();
	var $ctime        = '';
	var $directory    = '';
	var $exists       = 0;
	var $id           = -1;
	var $mimetype     = '';
	var $name         = '';
	var $parent       = 0;
	var $path         = '';
	var $size         = 0;
	var $writable     = false;
	var $type;
	function kfmFile(){
		global $kfm;
		if(func_num_args()==1){
			$this->id=(int)func_get_arg(0);
			parent::__construct();
			$filedata=db_fetch_row("SELECT id,name,directory FROM ".KFM_DB_PREFIX."files WHERE id=".$this->id);
			$this->name=$filedata['name'];
			$this->parent=$filedata['directory'];
			$dir=kfmDirectory::getInstance($this->parent);
      $this->dir = $dir;
			$this->directory=$dir->path();
			$this->path=$this->directory.'/'.$filedata['name'];
			if(!$this->exists()){
//				$this->error(kfm_lang('File cannot be found')); // removed because it is causing false errors
				$this->delete();
				return false;
			}
			$this->writable=$this->isWritable();
			$this->ctime=filemtime($this->path)+$GLOBALS['kfm_server_hours_offset']*3600;
			$this->modified=strftime($kfm->setting('date_format').' '.$kfm->setting('time_format'),filemtime($this->path));
			$mimetype=Mimetype::get($this->path);
			$pos=strpos($mimetype,';');
			$this->mimetype=($pos===false)?$mimetype:substr($mimetype,0,$pos);
			$this->type=trim(substr(strstr($this->mimetype,'/'),1));
		}
	}

	/**
	 * Deletes the file
	 * @return bool true opon success, false on error
	 */
	function delete(){
		global $kfm;
		if(!$kfm->setting('allow_file_delete'))return $this->error(kfm_lang('permissionDeniedDeleteFile'));
		if(!kfm_cmsHooks_allowedToDeleteFile($this->id))return $this->error(kfm_lang('CMSRefusesFileDelete',$this->path));
		if($this->exists() && !$this->writable)return $this->error(kfm_lang('fileNotMovableUnwritable',$this->name));
		if(!$this->exists() || unlink($this->path))$kfm->db->exec("DELETE FROM ".KFM_DB_PREFIX."files WHERE id=".$this->id);
		else return $this->error(kfm_lang('failedDeleteFile',$this->name));
		return true;
	}

	/**
	 * Checks if the file exists
	 * @return bool
	 * */
	function exists(){
		if($this->exists)return $this->exists;
		$this->exists=file_exists($this->path);
		return $this->exists;
	}

   /**
   * Returns true if $string is valid UTF-8 and false otherwise.
   *
   * @since        1.14
   * @param [mixed] $string     string to be tested
   * @subpackage
   */
   function is_utf8($string) {
      // From http://w3.org/International/questions/qa-forms-utf-8.html
      return preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]            # ASCII
          | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
          |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
          | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
          |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
          |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
          | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
          |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
      )*$%xs', $string);
   }

   /**
    * Returns the file contents or false on error
    */
   function getContent(){
      return ($this->id == -1) ? false : $this->is_utf8($this->path) ? file_get_contents($this->path) : utf8_encode(file_get_contents($this->path));
   }

	/**
	 * Function that returns the extension of the file.
	 * if a parameter is given, the extension of that parameters is returned
	 * returns false on error.
	 */
	function getExtension(){
		if(func_num_args()==1){
			$filename=trim(func_get_arg(0));
		}else{
			if($this->id==-1)return false;
			$filename=trim($this->name);
		}
		$dotext=strrchr($filename,'.');
		if($dotext === false) return false;
		return strtolower(substr($dotext,1));
	}
	/**
	 * Returns the url of the file as specified by the configuration
	 */
	function getUrl($x=0,$y=0){
		global $kfm;
		//$this->url=$this->directory==$kfm->setting('files_root_path')?'':str_replace($rootdir,'',$this->directory);
		//global $rootdir, $kfm_userfiles_output,$kfm_workdirectory;
		if(!$this->exists())return 'javascript:alert("missing file")';
		/* The following if should be depricated in the future in favor of the secure method.
       The url can be constructed since kfm_url should be given and get.php is in that root */
    global $kfm_userfiles_output;
		if(preg_replace('/.*(get\.php)$/','$1',$kfm_userfiles_output)=='get.php'){
			if($kfm_userfiles_output=='get.php')$url=preg_replace('/\/[^\/]*$/','/get.php?id='.$this->id.GET_PARAMS,$_SERVER['REQUEST_URI']);
			else $url=$kfm_userfiles_output.'?id='.$this->id;
			if($x&&$y)$url.='&width='.$x.'&height='.$y;
		}
    /* end deprication block */
		if($this->isImage()&&$x&&$y){ // thumbnail is requested
			$img=kfmImage::getInstance($this);
			$img->setThumbnail($x,$y);
			return WORKPATH.'thumbs/'.$img->thumb_id;
		}
		if($kfm->setting('file_url')=='secure'){
			$url=$kfm->setting('kfm_url').'get.php?id='.$this->id.GET_PARAMS;
		}
		else{
      $url = $kfm->setting('files_url').$this->dir->relativePath().'/'.$this->name;
		}
		return $url; # this was "return preg_replace('/([^:])?\/{2,}/','$1/',$url);"
		             # but that caused URLs such as "http:/example.com/test.jpg"
								 # instead of "http://example.com/test.jpg"
	}

	/**
	 * Returns the file instance. This is preferred above new kfmFile($id)
	 * @param int $file_id
	 * @return Object file or image
	 */
	function getInstance($id=0){
		if(is_object($id))$id=$id->id;
		$id=(int)$id;
		if($id<1)return;
		if (!@array_key_exists($id,self::$instances)) self::$instances[$id]=new kfmFile($id);
		if (self::$instances[$id]->isImage()) return kfmImage::getInstance($id);
		return self::$instances[$id];
	}

	/**
	 * returns the file size
	 */
	function getSize(){
		if(!$this->size)$this->size=filesize($this->path);
		return $this->size;
	}

	/**
	 * Get the tags of the file
	 * @return Array
	 */
	function getTags(){
		global $kfm;
		$arr=array();
		if(!$kfm->isPlugin('tags'))return $arr;
		$tags=db_fetch_all("select tag_id from ".KFM_DB_PREFIX."tagged_files where file_id=".$this->id);
		foreach($tags as $r)$arr[]=$r['tag_id'];
		return $arr;
	}

	/**
	 * Check of file is an image
	 * @return bool
	 */
	function isImage(){
		return in_array($this->getExtension(),array('jpg', 'jpeg', 'gif', 'png', 'bmp'));
	}

	/**
	 * Check if the file is writable
	 * @return bool true when writable, false if not
	 */
	function isWritable(){
		return (($this->id==-1)||!is_writable($this->path))?false:true;
	}

	/**
	 * Moves the file
	 * @param int $new_directoryparent_id
	 */
	function move($dir_id){
		global $kfmdb,$kfm;
		if($dir_id==$kfm->setting('root_folder_id') && !$kfm->setting('allow_files_in_root'))return $this->error('Cannot move files to the root directory');
		if(!$this->writable)return $this->error(kfm_lang('fileNotMovableUnwritable',$this->name));
		$dir=kfmDirectory::getInstance($dir_id);
		if(!$dir)return $this->error(kfm_lang('failedGetDirectoryObject'));
		if(!rename($this->path,$dir->path().'/'.$this->name))return $this->error(kfm_lang('failedMoveFile',$this->name));
		$q=$kfmdb->query("update ".KFM_DB_PREFIX."files set directory=".$dir_id." where id=".$this->id);
	}

	/**
	 * Rename the file
	 * @param string $newName new file name
	 */
	function rename($newName){
		global $kfm;
		if(!$kfm->setting('allow_file_edit'))return $this->error(kfm_lang('permissionDeniedEditFile'));
		if(!$this->checkName($newName))return $this->error(kfm_lang('cannotRenameFromTo',$this->name,$newName));
		$newFileAddress=$this->directory.$newName;
		if(file_exists($newFileAddress))return $this->error(kfm_lang('fileAlreadyExists'));
		rename($this->path,$newFileAddress);
		$this->name=$newName;
		$this->path=$newFileAddress;
		$kfm->db->query("UPDATE ".KFM_DB_PREFIX."files SET name='".sql_escape($newName)."' WHERE id=".$this->id);
	}

	/**
	 * Write content to the file
	 * @param mixed $content
	 */
	function setContent($content){
		global $kfm;
		if(!$kfm->setting('allow_file_edit'))return $this->error(kfm_lang('permissionDeniedEditFile'));
		$result=file_put_contents($this->path,$content);
		if(!$result)return $this->error(kfm_lang('errorSettingFileContent'));
		return true;
	}

	/**
	 * Set tags of the file
	 * @param array $tags
	 */
	function setTags($tags){
		global $kfm;
		if(!count($tags))return;
		$kfm->db->exec("DELETE FROM ".KFM_DB_PREFIX."tagged_files WHERE file_id=".$this->id);
		foreach($tags as $tag)$kfm->db->exec("INSERT INTO ".KFM_DB_PREFIX."tagged_files (file_id,tag_id) VALUES(".$this->id.",".$tag.")");
	}

	/**
	 * Get the filezise in a human-readable way
	 * @param int $size optional
	 * @return string $size
	 */
	function size2str(){
		$size=func_num_args()?func_get_arg(0):$this->getSize();
		if(!$size)return '0';
		$format=array("B","KB","MB","GB","TB","PB","EB","ZB","YB");
		$n=floor(log($size)/log(1024));
		return $n?round($size/pow(1024,$n),1).' '.$format[$n]:'0 B';
	}

	/**
	 * Add the file to the database
	 * @param string $filename name of the file
	 * @param int $directory_id id of the directory in which the file is located
	 * @return int $file_id id assigned to the file
	 */
	function addToDb($filename,$directory_id){
		global $kfmdb;
		if(!$directory_id)return $this->error('Directory ID not supplied');
		$sql="insert into ".KFM_DB_PREFIX."files (name,directory) values('".sql_escape($filename)."',".$directory_id.")";
		$q=$kfmdb->query($sql);
		return $kfmdb->lastInsertId(KFM_DB_PREFIX.'files','id');
	}

	/**
	 * Check if the filename is authorized by the system according to the configuration
	 * @return bool $authorized true when authorized, false if not
	 */
	function checkName($filename=false){
		global $kfm;
		if($filename===false)$filename=$this->name;
		$filename=trim($filename);

		if(
			$filename=='' || 
			preg_match('#/|\.$#',$filename)
		)return false;

		$exts=explode('.',$filename);
		for($i=1;$i<count($exts);++$i){
			$ext=$exts[$i];
			if(in_array($ext,$kfm->setting('banned_extensions')))return false;
		}
		
		foreach($kfm->setting('banned_files') as $ban){
			if(($ban[0]=='/' || $ban[0]=='@')&&preg_match($ban,$filename))return false;
			elseif($ban==strtolower($filename))return false;
		}

		if(count($kfm->setting('allowed_files'))){
			foreach($kfm->setting('allowed_files') as $allow){
				if($allow[0]=='/' || $allow[0]=='@'){
					if(preg_match($allow, $filename))return true;
				}else if($allow==strtolower($filename)) return true;
			}
			return false;
		}
		return true;
	}
}
