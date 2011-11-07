<?
	class fileFolders{
		var $ext = array();
		var $allFiles = array();
		var $allFolders = array();
		
		function fileList($dir){
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						if($file != "." && $file != ".."){
							$myext = explode("." , $file);
							$myext = $myext[count($myext)-1];
							
							if(is_dir($dir.$file)){
								$this->allFolders = $dir.$file."/";
								$this->fileList($dir.$file."/");
							}else{
								if(in_array($myext , $this->ext) || count($this->ext)==0){
									$this->allFiles[] = $dir.$file;
								}
							}
						}	
					}
					closedir($dh);
				}
			}
			return $this->allFiles;
		}
		
		function saveFile($filename , $content){
		
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$filename = str_replace("/" , "\\" , $filename);	
			} 
		
			$expl = explode("/" , $filename);
			$filename = $expl[count($expl)-1];
			
			unset($expl[count($expl)-1]);
			
			$currpath = "";
			foreach($expl as $onefolder){
				if(trim($onefolder)){
					$currpath .= "$onefolder/";
					if(!is_dir($currpath)){
						@mkdir($currpath , 0777);
					}
				}
			}
			
			$fp = fopen($filename,"w");
			fwrite($fp , $content);
			fclose($fp);
		}
		
		function clear(){
			$this->allFiles = array();
			$this->allFolders = array();
		}
		
		function deleteFolder($folderName){
			$this->clear();
			$this->fileList($folderName);
			
			foreach($this->allFiles as $onefile){
				deleteFile($onefile);
			}
			
			foreach($this->allFolders as $onefolder){
				rmdir($onefolder);
			}
		}
		
		function deleteFile($filename){
			@unlink($filename);
		}
	}
?>