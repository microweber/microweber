<?php
/*
PDW File Browser
Date: October 19, 2010
Url: http://www.neele.name

Copyright (c) 2010 Guido Neele
*/

require_once('config.php');

/**
 * Check if a foldername doesn't have invalid characters
 * 
 * @param String $str
 * @return Bool
 */
function checkFolderName($str){
	return !( preg_match('#\^|\\\|\/|\.|\?|\*|"|\'|\<|\>|\:|\|#', $str) ? TRUE : FALSE );
}

/**
 * Check the given path to make sure the path is not tampered with
 * 
 * @param String $currentpath
 * @param String $uploadpath
 * @return $currentpath or FALSE
 */
function checkpath($currentpath, $uploadpath){
	// ../ not allowed
	$currentpath = str_replace("..","",$currentpath);
	// ./ not allowed
	$currentpath = str_replace("./","",$currentpath);
	// Remove //
	$currentpath = str_replace("//","/",$currentpath);
	
	// $uploadpath needs to exist in the path
	$pos = strpos($currentpath, $uploadpath);
	if ($pos === FALSE){
		return false;		
	} elseif($pos == 0) {
		return $currentpath;	
	} else {
		return false;	
	}
}

/**
 * translate returns a translated value or if the translation doesn't exist
 * a default value in English.
 * 
 * @param String $str
 * @return String
 */
function translate($str){
	
	if(array_key_exists($str, $GLOBALS['lang'])):
		return $GLOBALS['lang'][$str];
	else:
		return $str;
	endif;
}

/**
 * getDirTree returns an array of files and folders
 * 
 * $dir of the folder you want to list, be sure to have an ending "/"
 * $showfiles set to 'true' includes files
 * $iterateSubDirectories set to 'true' also includes the subdirectories
 * 
 * regex ^\..* detects files starting with a dot, .htaccess for example
 * 
 * @param String $dir
 * @param Bool $showfiles [optional]
 * @param Bool $iterateSubDirectories [optional]
 * @return Array $x
 */
function getDirTree($dir, $showfiles=true, $iterateSubDirectories=true) {
	$d = dir($dir);
	$x = array();
	while (false !== ($r = $d->read())) {
		if($r != "." && $r != ".." && ((!preg_match('/^\..*/', $r) && !is_dir($dir.$r)) || is_dir($dir.$r)) && (($showfiles == false && is_dir($dir.$r)) || $showfiles == true)) {
			$x[$r] = (is_dir($dir.$r)?array():(is_file($dir.$r)?true:false));
		}
	}
	foreach ($x as $key => $value) {
		if (is_dir($dir.$key."/") && $iterateSubDirectories) {
			$x[$key] = getDirTree($dir.$key."/",$showfiles);
		} else {
			$x[$key] = is_file($dir.$key) ? (preg_match("/\.([^\.]+)$/", $key, $matches) ? str_replace(".","",$matches[0]) : 'file') : "folder";
		}
	}
	uksort($x, "strnatcasecmp"); // Sort keys in case insensitive alphabetical order
	return $x;
}

/**
 * Delete a file or recursively delete a directory
 *
 * @param string $path Path to file or directory
 */
function recursiveDelete($path){
	if(is_file($path)){
    	return @unlink($path);
    }
    elseif(is_dir($path)){
    	$scan = glob(rtrim($path,'/').'/*');
        
		foreach($scan as $index=>$tmppath){
        	recursiveDelete($tmppath);
        }
        return @rmdir($path);
	}
}

/**
 * Display a list of directories
 * 
 * @param Array $dirs
 * @param String $rootpath
 * @return 
 */
function renderTree($dirs, $rootpath){
	$html = "\t\t<ul>\n";
	
	foreach ($dirs as $key => $value) {
		
		$html .= "\t\t\t<li><a href=\"".$rootpath.$key."/".'" class="folder">'.$key.'</a>';
		
		if (count($dirs[$key]) > 0) {
			$html .= "\n" . renderTree($dirs[$key], $rootpath.$key."/") . "\t\t";
		}
		
		$html .= "</li>\n";
	}
	
	$html .= "\t\t</ul>\n";
	return $html;
}

/**
 * Creates a directory and returns true if folder already exists OR has been made.
 *
 * @param  String $path
 * @return bool
 *
function rmkdir($path, $mode = 0755) {
    $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
    $e = explode("/", ltrim($path, "/"));
    if(substr($path, 0, 1) == "/") {
        $e[0] = "/".$e[0];
    }
    $c = count($e);
    $cp = $e[0];
    for($i = 1; $i < $c; $i++) {
        if(!is_dir($cp) && !@mkdir($cp, $mode)) {
            return false;
        }
        $cp .= "/".$e[$i];
    }
	return @mkdir($path, $mode);
} 
*/

/**
 * Copy a file or complete directory to a given location
 * 
 * source=file & dest=dir => copy file from source-dir to dest-dir 
 * source=file & dest=file / not there yet => copy file from source-dir to dest and overwrite a file there, if present 
 * source=dir & dest=dir => copy all content from source to dir 
 * source=dir & dest not there yet => copy all content from source to a, yet to be created, dest-dir 
 * 
 * @param String $source
 * @param String $dest
 * @param int $folderPermission [optional]
 * @param int $filePermission [optional]
 * @return 
 */
function smartCopy($source, $dest, $folderPermission=0755,$filePermission=0644){ 
	
    $result=false; 
    
    if (is_file($source)) { // $source is file 
        if(is_dir($dest)) { // $dest is folder 
            if ($dest[strlen($dest)-1]!='/') // add '/' if necessary 
                $__dest=$dest."/"; 
            $__dest .= basename($source); 
        } 
        else { // $dest is (new) filename 
            $__dest=$dest; 
        } 
        $result=copy($source, $__dest); 
        chmod($__dest,$filePermission); 
    } 
    elseif(is_dir($source)) { // $source is dir 
        if(!is_dir($dest)) { // dest-dir not there yet, create it 
            @mkdir($dest,$folderPermission); 
            chmod($dest,$folderPermission); 
        } 
        if ($source[strlen($source)-1]!='/') // add '/' if necessary 
            $source=$source."/"; 
        if ($dest[strlen($dest)-1]!='/') // add '/' if necessary 
            $dest=$dest."/"; 

        // find all elements in $source 
        $result = true; // in case this dir is empty it would otherwise return false 
        $dirHandle=opendir($source); 
        while($file=readdir($dirHandle)) { // note that $file can also be a folder 
            if($file!="." && $file!="..") { // filter starting elements and pass the rest to this function again 
				// echo "$source$file ||| $dest$file<br />\n"; 
                $result=smartCopy($source.$file, $dest.$file, $folderPermission, $filePermission); 
      	    } 
        } 
        closedir($dirHandle); 
    } 
    else { 
        $result=false; 
	} 
    return $result; 
} 

/**
 * This function returns a mime type by looking at the extension of the passed filename
 * 
 * @param String $filename
 * @return String mime type
 */
if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
			'docx' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/ms-excel',
			'xlsx' => 'application/ms-excel',
            'ppt' => 'application/ms-powerpoint',
			'pptx' => 'application/ms-powerpoint',

            // open office
            'odt' => 'application/opendocument.text',
            'ods' => 'application/opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop((explode('.',$filename))));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}
?>