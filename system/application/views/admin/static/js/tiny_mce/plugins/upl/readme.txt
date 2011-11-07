Tiny Upload

A callback script that allows simple file uploas and visual image selection, for tinymce.

------Licence------

You are free to do as you wish with these files, including but not limited to, altering the scripts,
re-branding, selling, distribution without inclusion of this licence, redistribution under different
licence (as long as you do not compromise other derived works freedom to do the same.), develop your
own works (derived in whole or in part) from this one.

------Disclaimer------
This solution is provided as is and the author takes no responsibility for; any damage caused 
directly or indirectly, support or maintenance or continued development.


------Setup------

There are two file you need to upload:
	tinyupload.js
	tinyupload.php
	
There are paths you need to set in each file, open them up for editing and at the top of each file
you will see the paths.

tinyupload.php:
	//###### Config ######
	$absPthToSlf = 'http://www.example.com/images/tinyupload.php';
	$absPthToDst = 'http://www.example.com/images/userimages/';
	$absPthToDstSvr = '/home/5586/example/www.example.com/public_html/images/userimages/';

	The first is the absolute path (from the clients POV) to tinyupload.php.
	The second is the absolute path (from the clients POV) to the destination folder.
	The third is the absolute path (from the servers POV) to the destination folder. You will need to 
		set permissions for this directory, '0777' worked ok for me.
	
tinyupload.js:
	
	/*
	CONFIG
	*/
	var pathToPhp = 'http://www.example.com/images/tinyupload.php';
	
	This is the path to tinyupload.php.