<?php
/**
 * KFM - Kae's File Manager
 *
 * get.php - retrieves a specified file
 * can also resize an image on demand
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link     http://kfm.verens.com/
 */
require_once 'initialise.php';
if (isset($_SERVER['REDIRECT_QUERY_STRING'])&&$_SERVER['REDIRECT_QUERY_STRING']) {
    $arr = explode(',', $_SERVER['REDIRECT_QUERY_STRING']);
    foreach ($arr as $r) {
        $arr2           = explode('=', $r);
        if(count($arr2)>1)$_GET[$arr2[0]] = $arr2[1];
    }
}
// { rebuild $_GET (in case it's been mangled by something)
$uri   = $_SERVER['REQUEST_URI'];
if (strpos($uri,'?')===false) $uri=str_replace('/get.php/','/get.php?',$uri);
$uri2  = explode('?', $uri);
$parts = count($uri2)>1 ? explode('&', $uri2[1]) : array();
foreach ($parts as $part) {
    $arr = explode('=', $part);
    if (!(count($arr)>1)) continue;
    list($varname, $varval) = $arr;
    $_GET[$varname]         = urldecode($varval);
}
// }
if(isset($_GET['uri'])){
	$bits=explode('/',$_GET['uri']);
	$fname=array_pop($bits);
	$dir=0;
	$dirs                   = explode(DIRECTORY_SEPARATOR, trim(join('/',$bits), ' '.DIRECTORY_SEPARATOR));
	$subdir                 = kfmDirectory::getInstance(1);
	$startup_sequence_array = array();
	foreach ($dirs as $dirname) {
		$subdir = $subdir->getSubdir($dirname);
		if(!$subdir)break;
		$dir= $subdir->id;
	}
	foreach($subdir->getFiles() as $file){
		if($file->name==$fname){
			$_GET['id']=$file->id;
			break;
		}
	}
}
$id=@$_GET['id'];
if (!is_numeric($id)) {
    echo kfm_lang('errorInvalidID');
    exit;
}
$extension = 'unknown';
if (isset($_GET['type'])&&$_GET['type']=='thumb') {
    $path = WORKPATH.'thumbs/'.$id;
    $name = $id;
} else {
    if (isset($_GET['width'])&&isset($_GET['height'])) {
        $width  = (int)$_GET['width'];
        $height = (int)$_GET['height'];
        $image  = kfmImage::getInstance($id);
        if (!$image) {
            echo kfm_lang('errorFileIDNotFound', $id);
            exit;
        }
				if($width>$image->width)$width=$image->width;
				if($height>$image->height)$height=$image->height;
				$h=0;$s=0;$l=0;
				if(isset($_GET['hsl'])){
					list($h,$s,$l)=explode(':',$_GET['hsl']);
				}
        $image->setThumbnail($width, $height,$h,$s,$l);
        $name      = $image->thumb_id;
        $path      = $image->thumb_path;
        $extension = $image->getExtension();
    } else {
        $file = kfmFile::getInstance($id);
        if (!$file) {
            echo kfm_lang('errorFileIDNotFound', $id);
            exit;
        }
        $path      = $file->path;
        $name      = $file->name;
        $extension = $file->getExtension();
    }
}
// { headers
if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) $name = preg_replace('/\./', '%2e', $name, substr_count($name, '.')-1);
@set_time_limit(0);
header('Cache-Control: max-age = 2592000');
header('Expires-Active: On');
header('Expires: Fri, 1 Jan 2500 01:01:01 GMT');
header('Pragma:');
$filesize=filesize($path);
header('Content-Length: '.(string)(filesize($path)));
if (isset($_GET['forcedownload'])) {
    header('Content-Type: force/download');
    header('Content-Disposition: attachment; filename="'.$name.'"');
} else header('Content-Type: '.Mimetype::get($extension));
header('Content-Transfer-Encoding: binary');
// }
if ($file = fopen($path, 'rb')) { // send file
    while ((!feof($file))&&(connection_status()==0)) {
        print(fread($file, 1024*8));
        flush();
    }
    fclose($file);
}
if(file_exists('api/log_retrieved_file.php'))require 'api/log_retrieved_file.php';
return((connection_status()==0) and !connection_aborted());
