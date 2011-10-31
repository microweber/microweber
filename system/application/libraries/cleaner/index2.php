<?php
	set_time_limit(0);

	include('class.folders.php');
	include('class.cleaner.php');
	
	$cleanerObj = new codeCleaner;
	$folderObj = new fileFolders;
	
	$allfiles = $folderObj->fileList( dirname(__FILE__)."/clearthese/");
	
	foreach($allfiles as $onefile){
		echo "<b>Working on :</b> $onefile <br>";
		$content = $cleanerObj->cleanFile($onefile);
		$newfilename = str_replace(dirname(__FILE__)."/clearthese/" , dirname(__FILE__)."/output/" , $onefile);
		
		$folderObj->saveFile($newfilename , $content);
	}

?>