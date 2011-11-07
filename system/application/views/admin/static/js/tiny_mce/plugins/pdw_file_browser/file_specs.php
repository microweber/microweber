<?php

require_once('functions.php');

if(isset($_REQUEST["ajax"])){
	$uploadpath = urldecode($_REQUEST["path"]);
}

$type = isset($_REQUEST["type"]) ? $_REQUEST["type"] : "folder"; 

switch($type){
	case 'image':
		$filename = DOCUMENTROOT.$uploadpath;
		$image_info = getimagesize($filename);
		$file_modified = date($datetimeFormat, filemtime($filename));
		$file_size = filesize($filename);
        $file_size = $file_size < 1024  ? $file_size. ' '.translate('bytes') : $file_size < 1048576 ? number_format($file_size / 1024, 2, $dec_seperator, $thousands_separator) . ' '.translate('kB') : number_format($file_size / 1048576, 2, $dec_seperator, $thousands_separator) . ' '.translate('MB');
		$filename = array_pop((explode("/", $uploadpath)));
	
		printf('<div class="icon image"><img src="phpthumb/phpThumb.php?h=140&amp;w=140&amp;far=1&amp;src=%s&bg=0000FF" alt="%s" /></div>', urlencode($uploadpath), $filename);
   		printf('<div class="filename"><a href="%s" rel="lightbox">%s</a></div>', $uploadpath, $filename);
		printf('<div class="filetype">%s</div>', $image_info['mime']);
		printf('<div class="filemodified"><span>%s:&nbsp;</span>%s</div>',translate('Modified on'), $file_modified);
		printf('<div class="filesize"><span>%s:&nbsp;</span>%s</div>',translate('Size'), $file_size);
		printf('<div class="filedim"><span>%s:&nbsp;</span>%s x %s</div>',translate('Dimensions'), $image_info[0], $image_info[1]);
		break;
	case 'file':
		$filename = DOCUMENTROOT.$uploadpath;
		$file_modified = date($datetimeFormat, filemtime($filename));
		$file_type = mime_content_type($filename);
		$file_size = filesize($filename);
        $file_size = $file_size < 1024  ? $file_size. ' '.translate('bytes') : $file_size < 1048576 ? number_format($file_size / 1024, 2, $dec_seperator, $thousands_separator) . ' '.translate('kB') : number_format($file_size / 1048576, 2, $dec_seperator, $thousands_separator) . ' '.translate('MB');
		$filename = array_pop((explode("/", $uploadpath)));
		$fileext = array_pop((explode(".", $uploadpath)));
	
		printf('<div class="icon %s"></div>', $fileext);
   		printf('<div class="filename">%s</div>', $filename);
		printf('<div class="filetype">%s</div>', $file_type);
		printf('<div class="filemodified"><span>%s:&nbsp;</span>%s</div>',translate('Modified on'), $file_modified);
		printf('<div class="filesize"><span>%s:&nbsp;</span>%s</div>',translate('Size'), $file_size);
		break;
	default: // folder
		$foldername = DOCUMENTROOT.$uploadpath;
		$folder_modified = date($datetimeFormat, filemtime($foldername));
		$foldername = array_pop((explode("/", trim($uploadpath,"/"))));
	
		print('<div class="icon folder"></div>');
   		printf('<div class="filename">%s</div>', $foldername);
		printf('<div class="filetype">%s</div>', translate('Directory'));
		printf('<div class="filemodified"><span>%s:&nbsp;</span>%s</div>',translate('Modified on'), $folder_modified);
		break;
}

?>