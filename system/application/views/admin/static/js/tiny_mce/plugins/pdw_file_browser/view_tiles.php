<?php

require_once('functions.php');

if(isset($_REQUEST["ajax"])){
	$selectedpath = urldecode($_REQUEST["path"]);
	if($selectedpath = checkpath($selectedpath, $uploadpath)){
		$dirs = getDirTree(DOCUMENTROOT.$selectedpath, true, false);
	} else {
		die('0||'.translate("The folder path was tampered with!"));
	}
} else {
	$selectedpath = $uploadpath;	
}

print '<ul id="tiles" class="files clear">'."\n";

$htmlFiles = '';
$htmlFolders = '';
					
foreach($dirs as $key => $value){
	if($value != "folder"){
		if(strtolower($value) == "png" || strtolower($value) == "jpg" || strtolower($value) == "jpeg" || strtolower($value) == "gif" || strtolower($value) == "bmp"){
							
			$filename = DOCUMENTROOT.$selectedpath.$key;
			$image_info = getimagesize($filename);
			$file_modified = date($datetimeFormat, filemtime($filename));
				
			$htmlFiles .= sprintf('                    <li>
                        <a href="%1$s" title="%2$s" class="image">
                            <span class="begin"></span>
                            <span class="filename">%2$s</span>
                            <span class="filetype">%8$s</span>
                            <span class="filedim">%6$s x %7$s</span>
                            <span class="icon image"><img src="phpthumb/phpThumb.php?h=48&w=48&src=%4$s&far=1&bg=0000FF" /></span>
                        </a>
                    </li>'."\n", 
                    $selectedpath.$key, 
                    $key, 
                    $value, 
                    urlencode($selectedpath.$key),
                    translate("Dimensions"),
                    $image_info[0],
                    $image_info[1],
                    $image_info['mime']);	
		} else {
									
			$filename = DOCUMENTROOT.$selectedpath.$key;
			//$file_modified = date($datetimeFormat, filemtime($filename));
			$file_size = filesize($filename);
			$file_type = mime_content_type($filename);
			$file_size = $file_size < 1024  ? $file_size. ' '.translate('bytes') : $file_size < 1048576 ? number_format($file_size / 1024, 2, $dec_seperator, $thousands_separator) . ' '.translate('kB') : number_format($file_size / 1048576, 2, $dec_seperator, $thousands_separator) . ' '.translate('MB');
									
			$htmlFiles .= sprintf('                    <li>
                        <a href="%1$s" title="%2$s" class="file">
                            <span class="begin"></span>
                            <span class="filename">%2$s</span>
                            <span class="filetype">%4$s</span>
                            <span class="filesize">%5$s</span>
                            <span class="icon %3$s"></span>
                        </a>
                    </li>'."\n", 
                    $selectedpath.$key, 
                    $key, 
                    $value,
                    $file_type,
                    $file_size);	
		}
	} else {
								
		$htmlFolders .= sprintf('                    <li>
                        <a href="%1$s" title="%2$s" class="folder">
                            <span class="begin"></span>
                            <span class="filename">%2$s</span>
                            <span class="filetype">%3$s</span>
                            <span class="icon folder"></span>
                        </a>
                    </li>'."\n", 
                    $selectedpath.$key."/", 
                    $key,
                    translate("Directory"));
	}
}

print $htmlFolders;
print $htmlFiles;
print '                </ul>'."\n";
?>