<?php

if (!isset($_SESSION) or empty($_SESSION)) {
	//session_start();
}

 $fileName_ext = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
					 
							 
								 $is_ext = get_file_extension($fileName_ext);
									$is_ext = strtolower($is_ext);

									  switch($is_ext){
										case 'php':
										case 'php5':
										case 'php4':
										case 'php3':
										case 'ptml':
										case 'html':
										case 'xhtml':
										case 'shtml':
										case 'htm':
										case 'pl':
										case 'cgi':
										case 'rb':
										case 'py':
										case 'asp':
										case 'htaccess':
										case 'exe':
										case 'msi':
										case 'sh':
									    case 'bat':
									    case 'vbs':
										$are_allowed = false;
										die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You cannot upload scripts or executables"}}');

										break;
									  }


if (is_admin() == false) {
	
	if(isset($_REQUEST["rel"]) and isset($_REQUEST["custom_field_id"])  and trim($_REQUEST["rel"]) != '' and trim($_REQUEST["rel"]) != 'false'){

			$cfid = CustomFields::get_by_id(intval($_REQUEST["custom_field_id"]));
			if($cfid == false){
			 die('{"jsonrpc" : "2.0", "error" : {"code": 90, "message": "Custom field is not found"}}');

			}

			if($cfid != false and isset($cfid['custom_field_type'])){
				if($cfid['custom_field_type'] != 'upload'){
					 die('{"jsonrpc" : "2.0", "error" : {"code": 94, "message": "Custom field is not file upload type"}}');

				}
				if($cfid != false and (  !isset($cfid['options']) or !isset($cfid['options']['file_types']) )){
				  die('{"jsonrpc" : "2.0", "error" : {"code": 95, "message": "File types is not set."}}');

				}
				if($cfid != false and isset($cfid['file_types']) and empty($cfid['file_types'])){
					 die('{"jsonrpc" : "2.0", "error" : {"code": 96, "message": "File types cannot by empty."}}');
				}

				if($cfid != false and isset($cfid['options']) and isset($cfid['options']['file_types'])){

					$alloled_ft=  array_values( ($cfid['options']['file_types']));
					 if(empty($alloled_ft)){
						 die('{"jsonrpc" : "2.0", "error" : {"code": 97, "message": "File types cannot by empty."}}');
					 } else {
					 	 $are_allowed = '';
						 $fileName_ext = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
						 foreach($alloled_ft as $alloled_ft_item){
							 if(trim($alloled_ft_item) != '' and $fileName_ext != ''){
								 $is_ext = get_file_extension($fileName_ext);
									$is_ext = strtolower($is_ext);

									  switch($is_ext){
										case 'php':
										case 'php5':
										case 'php4':
										case 'php3':
										case 'ptml':
										case 'html':
										case 'xhtml':
										case 'shtml':
										case 'htm':
										case 'pl':
										case 'cgi':
										case 'rb':
										case 'py':
										case 'asp':
										case 'htaccess':
										case 'exe':
										case 'msi':
										case 'sh':
									    case 'bat':
									    case 'vbs':
										$are_allowed = false;
										die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": "You cannot upload scripts or executables"}}');

										break;
									  }


								   switch($alloled_ft_item){


										case 'img':
										case 'image':
										case 'images':
										  $are_allowed .= ',png,gif,jpg,jpeg,tiff,bmp,svg';
										  break;
										case 'video':
										case 'videos':
										  $are_allowed .= ',avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
										  break;
										case 'file':
										case 'files':
										  $are_allowed .= ',doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv,7z';
										  break;
										case 'documents':
										case 'doc':
										   $are_allowed .= ',doc,docx,log,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
										  break;
										case 'archives':
										case 'arc':
										case 'arch':
										   $are_allowed .= ',zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
										  break;
										case 'all':
										  $are_allowed .= ',*';
										  break;
										case '*':
										 $are_allowed .= ',*';
										  break;
										default:


											  $are_allowed .= ','.$alloled_ft_item;



										}
										$pass_type_check = false;
								 if($are_allowed != false){
									$are_allowed_a = explode(',',$are_allowed);
									 if(!empty($are_allowed_a)){
										  foreach($are_allowed_a as $are_allowed_a_item){
											  $are_allowed_a_item = strtolower(trim($are_allowed_a_item));
											  	$is_ext = strtolower(trim($is_ext));



									 		if($are_allowed_a_item == '*'){
 													$pass_type_check = 1;
									 		}

											  if( $are_allowed_a_item != '' and $are_allowed_a_item == $is_ext){
												  $pass_type_check = 1;
											  }
										  }

									 }
								 }
								if($pass_type_check == false){
									die('{"jsonrpc" : "2.0", "error" : {"code":103, "message": "You can only upload '.$are_allowed.' files."}}');

								} else {
								if (!isset($_REQUEST['captcha'])) {
									die('{"jsonrpc" : "2.0", "error" : {"code":99, "message": "Please enter the captcha answer!"}}');
									} else {
										$cap = session_get('captcha');
										if ($cap == false) {
										die('{"jsonrpc" : "2.0", "error" : {"code":100, "message": "You must load a captcha first!"}}');

										}
										if ($_REQUEST['captcha'] != $cap) {
										die('{"jsonrpc" : "2.0", "error" : {"code":101, "message": "Invalid captcha answer! "}}');

								 		} else {
								 			if(!isset($_REQUEST["path"])){
								 				$_REQUEST["path"] = 'media/user_uploads'.DS.$_REQUEST["rel"].DS;
								 			}
								 		}
									}


									//die('{"jsonrpc" : "2.0", "error" : {"code":98, "message": PECATA - Not finished yet."}}');

								}

							 }
						 }

					 }

				}

			}


			//d($cfid);
			//die('{"jsonrpc" : "2.0", "error" : {"code": 99, "message": "Not finished."}, "id" : "id"}');
	} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Only admin can upload."}, "id" : "id"}');

	}
}

/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */
// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Settings
$target_path = MEDIAFILES . DS;
$target_path = normalize_path($target_path, 0);

$path_restirct   =   MW_USERFILES; // the path the script should access
if(isset($_REQUEST["path"]) and trim($_REQUEST["path"]) != '' and trim($_REQUEST["path"]) != 'false'){
 	$path = urldecode($_REQUEST["path"]);
	$path = normalize_path($path, 0);
	$path = str_replace('..','',$path);
	$path = str_replace($path_restirct,'',$path);
	$target_path = MW_USERFILES.DS.$path;
	$target_path = normalize_path($target_path, 1);
}



$targetDir = $target_path;
if (!is_dir($targetDir)) {
	mkdir_recursive($targetDir);
}
//$targetDir = 'uploads';

$cleanupTargetDir = true;
// Remove old files
$maxFileAge = 5 * 3600;
// Temp file age in seconds
// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);
// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
$fileName = str_replace('..', '.', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files
if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {

			@unlink($tmpfilePath);
		}
	}

	closedir($dir);
} else {
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
}

if (isset($_SERVER["CONTENT_LENGTH"]) and isset($_FILES['file'])) {
	$filename_log = url_title($fileName);
	$check = get_log("one=true&no_cache=true&is_system=y&created_on=[mt]30 min ago&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&user_ip=" . USER_IP);
	$upl_size_log = $_SERVER["CONTENT_LENGTH"];
	if (isarr($check) and isset($check['id'])) {
		$upl_size_log = intval($upl_size_log) + intval($check['value']);
		save_log("no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&value=" . $upl_size_log . "&user_ip=" . USER_IP. "&id=" . $check['id']);
	} else {
		save_log("no_cache=true&is_system=y&field=upload_size&rel=uploader&rel_id=" . $filename_log . "&value=" . $upl_size_log . "&user_ip=" . USER_IP);
	}
}

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	
	
	
	
	
	 if ($_FILES['file']['error'] === UPLOAD_ERR_OK) { 
//uploading successfully done 
} else { 
throw new UploadException($_FILES['file']['error']); 
}  
	
	


	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
	} else{
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	
 
 
	
	}
} else {
	// Open temp file
	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off
	rename("{$filePath}.part", $filePath);
	delete_log("is_system=y&rel=uploader&created_on=[lt]30 min ago");
		delete_log("is_system=y&rel=uploader&session_id=" . session_id());

}
$f_name = explode(DS, $filePath);

$rerturn = array();
$rerturn['src'] = pathToURL($filePath);
$rerturn['name'] = end($f_name);

if(isset($upl_size_log) and $upl_size_log >0){
$rerturn['bytes_uploaded'] = $upl_size_log;
}
//$rerturn['ORIG_REQUEST'] = $_GET;



print json_encode($rerturn);
if(isset($_SESSION) and !empty($_SESSION)){
//@session_write_close();

}






class UploadException extends Exception 
{ 
    public function __construct($code) { 
        $message = $this->codeToMessage($code); 
        parent::__construct($message, $code); 
    } 

    private function codeToMessage($code) 
    { 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
    } 
} 


exit ;

// Return JSON-RPC response
//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
