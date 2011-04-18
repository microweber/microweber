<?php

//check if there are files uploaded
if((isset($_FILES['value']['error']) && $_FILES['value'] == 0) ||	
   (!empty($_FILES['value']['tmp_name']) && $_FILES['value']['tmp_name'] != 'none')) {			

       if (0 == @filesize($_FILES['value']['tmp_name'])) {
       	print "Empty or invalid file.";    
       	die();
       }

	print "File Name: " . $_FILES['value']['name'];
	print " File Size: " . @filesize($_FILES['value']['tmp_name']);
	//for security reason, we force to remove all uploaded file
	@unlink($_FILES['value']);
} else {			
	print "No file has been uploaded.";
	die();
}
