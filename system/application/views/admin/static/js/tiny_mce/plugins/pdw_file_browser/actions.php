<?php

require("functions.php");

$action = $_POST["action"];
if ($allowedActions[$action] === FALSE)
{
    echo 'error||' . translate("Action not allowed!");
    exit();
}

switch($action){
	
	case "cut_paste":
		$files = $_POST["files"];
		$folder = urldecode($_POST["folder"]);
	
		if($folder = checkpath($folder, $uploadpath)){
			$folder = DOCUMENTROOT.$folder;
			if(count($files) && is_dir($folder)){
				
				$result = true;
				
				foreach($files as $key => $value){
					
					$value = urldecode($value);
					
					if(($file = checkpath($value, $uploadpath)) && (DOCUMENTROOT.$file != $folder)) { //Check if folder/file is valid
						
						$source = DOCUMENTROOT.$file;
						$dest = $folder;
						
						if (is_dir($source) && $source[strlen($source) - 1] != '/') # add '/' if necessary 
            				$source=$source."/"; 
							
						if ($dest[strlen($dest) - 1] != '/') # add '/' if necessary 
            				$dest=$dest."/"; 
							
						if(is_dir($source)){			
							$dest=$dest.basename(rtrim($source, '/'));
						} else {
							$dest=$dest.basename($source);	
						}
						
						if(!smartCopy($source, $dest))
							$result = false;
							
					} else {
						echo 'error||'.translate("The folder path was tampered with!");	
						break;	
					}
				}
				
				if($result){
					// Delete files since this is cut and paste
					foreach($files as $key => $value){
						
						$value = urldecode($value);
						
						if( $file = checkpath($value, $uploadpath) ) { //Check if folder/file is valid
							
							if(!recursiveDelete(DOCUMENTROOT . $file))
								$result = false;
								
						} else {
							echo 'error||'.translate("The folder path was tampered with!");	
							break;	
						}
						
						if($result){
							echo 'success||'.sprintf(translate("%d file(s) successfully removed!"), "1");
						} else {
							echo 'error||'.translate("The file or folder path was tampered with!");	
						}
					}
				} else {
					echo 'error||'.translate("The file or folder path was tampered with!");	
				}
				
			} elseif(!count($files)) {
				
				// Array is empty @return true
				echo 'success||'.sprintf(translate("%d file(s) successfully removed!"), count($files));
				
			} else {
				
				// DOCUMENTROOT.$folder isn't a folder
				echo 'error||'.translate("The file or folder path was tampered with!");	
				
			}
		} else {
			echo 'error||'.translate("The folder path was tampered with!");
		}
		break;
	
	case "copy_paste":
		$files = $_POST["files"];
		$folder = urldecode($_POST["folder"]);
	
		if($folder = checkpath($folder, $uploadpath)){
			$folder = DOCUMENTROOT.$folder;
			if(count($files) && is_dir($folder)){
				
				$result = true;
				
				foreach($files as $key => $value){
					
					$value = urldecode($value);
					
					if( ($file = checkpath($value, $uploadpath)) && (DOCUMENTROOT.$file != $folder) ) { //Check if folder/file is valid
						
						$source = DOCUMENTROOT.$file;
						$dest = $folder;
						
						if (is_dir($source) && $source[strlen($source) - 1] != '/') # add '/' if necessary 
            				$source=$source."/"; 
							
						if ($dest[strlen($dest) - 1] != '/') # add '/' if necessary 
            				$dest=$dest."/"; 
							
						if(is_dir($source)){			
							$dest=$dest.basename(rtrim($source, '/'));
						} else {
							$dest=$dest.basename($source);	
						}
						
						if(!smartCopy($source, $dest))
							$result = false;

					} else {
						echo 'error||'.translate("The folder path was tampered with!");	
						break;	
					}
				}
				
				if($result){
					echo 'success||'.translate("The files where successfully copied!");
				} else {
					echo 'error||'.translate("The file or folder path was tampered with!");	
				}
				
			} elseif(!count($files)) {
				
				// Array is empty @return true
				echo 'success||'.sprintf(translate("%d file(s) successfully removed!"), count($files));
				
			} else {
				
				// DOCUMENTROOT.$folder isn't a folder
				echo 'error||'.translate("The file or folder path was tampered with!");	
				
			}
		} else {
			echo 'error||'.translate("The folder path was tampered with!");
		}
		break;
		
	case "rename":
		$new_filename = urldecode($_POST["new_filename"]);
		$old_filename = urldecode($_POST["old_filename"]);
		$folderpath = urldecode($_POST["folder"]);
		$type = $_POST["type"];
		
		$result = false;
	
		if($folderpath = checkpath($folderpath, $uploadpath)){
			$folder = DOCUMENTROOT.$folderpath;
			
			if($new_filename != "" && $old_filename != "" && is_dir($folder)){
				
				$result = true;
				
				if ($folder[strlen($folder) - 1] != '/') # add '/' if necessary 
            		$folder=$folder."/"; 
				
				$source = $folder . $old_filename;
				$dest = $folder . $new_filename;
				
				//Check if new file already exists
				if($type == 'folder'){
					if(is_dir($dest)){
						echo "error||" . translate("Directory already exists!");
						break;
					} 			
				} else {
					if(is_file($dest)){
						echo "error||" . translate("File already exists!");
						break;
					}
				}
					
				if(@rename($source, $dest)){
					echo "success||" . translate("Name successfully changed!");
				} else {
					echo "error||" . translate("Rename failed!");	
				}
							
			}
			
		} else {
			echo 'error||' . translate("The folder path was tampered with!");	
			break;	
		}

		break;

	case "delete":
		$files = $_POST["files"];
		$result = true;
		
		foreach ($files as $key => $file) {
			
			// Check if file path is valid
			$file = urldecode($file);
			if (!($file = checkpath($file, $uploadpath))){
				echo 'error||'.translate("The folder path was tampered with!");
				break;
			}
			
			// Delete file
			if (!recursiveDelete(DOCUMENTROOT . $file))
				$result=false;
			
		}
		
		if($result){
			echo 'success||'.sprintf(translate("%d file(s) successfully removed!"), count($files));	
		} else {
			echo 'error||'.translate("Deleting file failed!");	
		}
		
		break;
		
	case "create_folder":
		$folderpath = urldecode($_POST["folderpath"]);
		$foldername = urldecode($_POST["foldername"]);

		//Check if foldername isn't empty
		if($foldername == ""){
			echo 'error||' . translate("Creating new folder failed!");
			break;
		}
		
		//Check if folder name is valid
		if(!checkFolderName($foldername)){
			echo 'error||' . translate("Creating new folder failed!");
			break;
		}
		
		//Check if folder path is valid
		if (!($folderpath = checkpath($folderpath, $uploadpath))){
			echo 'error||' . translate("The folder path was tampered with!");
			break;	
		}

		if (@mkdir(DOCUMENTROOT.$folderpath.$foldername)){
			echo 'success||' . translate("A new folder was created!");
		} else {
			echo 'error||' . translate("Creating the new folder failed!");
		}
		
		break;
		
	case "settings":
        echo translate('Settings saved!');
        break;
}
?>