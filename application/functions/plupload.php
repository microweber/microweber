<?php

if (!isset($_SESSION) or empty($_SESSION)) {
	//session_start();
}

if (is_admin() == false) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 99, "message": "Only admin can upload."}, "id" : "id"}');
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
$target_path = MEDIAFILES . 'uploaded' . DS;
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
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
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
exit ;

// Return JSON-RPC response
//die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
