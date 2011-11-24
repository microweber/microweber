<head>
	<title>cc Simple Uploader</title>	
	<script type="text/javascript" src="../../tiny_mce_popup.js" ></script>
	<script type="text/javascript" src="editor_plugin.js" ></script>	
	<base target="_self" />
</head>	
<body>
<?php
	/*
		Name: uploader.php (for ccSimpleUploader tinyMCE plugin)
		Author: Timur Kovalev - www.creativecodedesign.com
		Distribution: Free for all to modify, distribute, and use, but I am not liable for any issues or problems.
		Note: THIS SCRIPT IS NOT SAFE FOR NON-AUTHENTICATED USE - There are no restrictions on types of files that can be uploaded!
		Someone could upload a malicious script that will destroy your files! User beware!
	*/

	$Action			= isset($_GET["q"]) ? $_GET["q"] : "none";						// no action by default
	$upload_dir		= isset($_GET["d"]) ? $_GET["d"] : "./";						// same directory by default
	$file_type		= isset($_GET["type"]) ? $_GET["type"] : "unknown";				// when called from tinyMCE advimg/advlink this will be set and can be used for filtering..
	$substitute_dir = isset($_GET["subs"]) ? $_GET["subs"] : "./";					// same substitution directory by default
	$ResultTargetID = isset($_GET["id"]) ? $_GET["id"] : "src";						// (obsolete) - target ID now is provided by the tinyMCE framework
	$ResizeSizeX	= isset($_GET["resize_x"]) ? $_GET["resize_x"] : 0;				// don't resize the width by default
	$ResizeSizeY	= isset($_GET["resize_y"]) ? $_GET["resize_y"] : 0;				// don't resize the height by default
	$ReplaceFile	= isset($_GET["replace_file"]) ? $_GET["replace_file"] : "yes";	// replace the file by default		
	
	if($Action=="none")	
		 display_upload_form();	
	else if($Action=="upload")	
		upload_content_file($upload_dir, $substitute_dir);	
?>
</body>
</html>

<?php

// displays the upload form
function display_upload_form()
{
	?>
		<form action="uploader.php?<?php echo $_SERVER["QUERY_STRING"]; ?>&q=upload" method="post" enctype="multipart/form-data" onsubmit="">
			File Name: <br/>
			<input type="file" size="70" name="upload_file" ID="File1"/><br/>
			<input type="submit" name="Upload File" value="Upload File" style="width: 150px;" onclick="document.getElementById('progress_div').style.visibility='visible';"/>			
			<div id="progress_div" style="visibility: hidden;"><img src="progress.gif" alt="wait..." style="padding-top: 5px;"></div>		
		</form>
	<?php
}
// uploads the file to the destination path, and returns a link with link path substituted for destination path
function upload_content_file($DestPath, $DestLinkPath)
{
	global $ResultTargetID,$ResizeSizeX,$ResizeSizeY,$ReplaceFile;
	$StatusMessage = "";
	$ActualFileName = "";	
	$FileObject = $_FILES["upload_file"];																// find data on the file
	if(!isset($FileObject) || $FileObject["size"]<=0)
	{		
		$StatusMessage = "Error! No valid file was specified for upload!";		
	}	
	else
	{	
		$ActualFileName = $DestPath . "/" . $FileObject['name'];										// formulate path to file
		if(file_exists($ActualFileName))																// check to see if the file already exists
		{
			if($ReplaceFile=="yes")
				$StatusMessage .= "Overwriting Existing File. ";										// if so, we'll let the user know
			else
				$StatusMessage . "Error! The file '$ActualFileName' already exists on the server!";
		}
		if($ReplaceFile=="yes")
		{
			if($ResizeSizeX!=0 || $ResizeSizeY!=0)														// if we need to resize the file
			{
				$uploadedfile = $FileObject['tmp_name'];												// get the handle to the file that was just uploaded
				if(ResizeImage($uploadedfile, $ResizeSizeX, $ResizeSizeY, $ActualFileName)!=TRUE)		// just process without resizing
				{
					$StatusMessage .= "Unable to resize the file to specified dimensions. ";
					move_uploaded_file($FileObject['tmp_name'], $ActualFileName);
				}
			}
			else
				move_uploaded_file($FileObject['tmp_name'], $ActualFileName);
			$StatusMessage .=  "File: " . $FileObject['name'] . " has been successfully uploaded!";		// to " . $ActualFileName . "!";						
			$ActualFileName = $DestLinkPath . $FileObject['name'];										// now create the link to the specified link path	
		}
	}	
	ShowPopUp($StatusMessage);																			// show the message to the user	
	CloseWindow($ResultTargetID, $ActualFileName);
}


function ResizeImage($uploadedfile, $ResizeSizeX, $ResizeSizeY, $ActualFileName)
{
	try
	{
		if(strpos($ActualFileName, ".jpg")==TRUE)
			$src = imagecreatefromjpeg($uploadedfile);																// Create an image object
		else if(strpos($ActualFileName, ".gif")==TRUE)
			$src = imagecreatefromgif($uploadedfile);
		else if(strpos($ActualFileName, ".png")==TRUE)
			$src = imagecreatefrompng($uploadedfile);
		else
			return FALSE;
		list($Originalwidth, $Originalheight) = getimagesize($uploadedfile);										// get current image size
		if($ResizeSizeY==0 && $ResizeSizeX!=0)																		// if only the width was specified
			$ResizeSizeY = ($Originalheight/$Originalwidth) * $ResizeSizeX;
		else if($ResizeSizeX==0 && $ResizeSizeY!=0)																	// if only the height was specified
			$ResizeSizeX = ($Originalwidth/$Originalheight) * $ResizeSizeY;
		$tmp = imagecreatetruecolor($ResizeSizeX, $ResizeSizeY);													// create new image with calculated dimensions	
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $ResizeSizeX, $ResizeSizeY, $Originalwidth, $Originalheight);	// resize the image and copy it into $tmp image		
		if(strpos($ActualFileName, ".jpg")==TRUE)
			imagejpeg($tmp, $ActualFileName, 100);
		else if(strpos($ActualFileName, ".gif")==TRUE)
			imagegif($tmp, $ActualFileName);
		else if(strpos($ActualFileName, ".png")==TRUE)
			imagepng($tmp, $ActualFileName, 100);		
		imagedestroy($src);
		imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request has completed.				
	}
	catch(Exception $e)
	{
		echo $e;
		return FALSE;
	}
	return TRUE;
}

function ShowPopUp($PopupText)
{
	echo "<script type=\"text/javascript\" language=\"javascript\">alert (\"$PopupText\");</script>";
}

function CloseWindow($FocusItemID, $FocusItemValue)
{
	?>
		<script language="javascript" type="text/javascript">	
			ClosePluginPopup('<?php echo $FocusItemValue; ?>');
		</script>
	<?php
}
?>

