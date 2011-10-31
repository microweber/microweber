To install phpimage version 1.0:



1. Unzip the file to the plugins directory of TinyMCE e.g.

/Jscript/tiny_mce/plugins


2. Find the code line 'tinyMCE.init({'.


3. Add the following 'phpimage' to TinyMCE plugins event caller e.g.

plugins : "phpimage,safari,spellchecker,inlinepopups,media,contextmenu,paste",


4. Add the following 'phpimage' to TinyMCE theme button event caller e.g.

theme_advanced_buttons1 : "phpimage,bold,italic,underline,strikethrough",


5. You may also want to disable the default image buttons e.g. 

theme_advanced_disable: "image,advimage",


6. Try running the script if it fails open the config.php in the phpimage folder under /Jscript/tiny_mce/plugins


7. Change $server_image_directory, $url_image_directory to suit your own server setup or create a folder under your main web directory called uploads/images.


8. If your running a unix server you may have to chmod the directory to 777 to allow  uploading of files.

ENJOY!!!


NOTES:

The default config setup resizes images down to a maximum of 400 pixels in height or width... This stops people from uploading huge images and consuming large amounts of harddisk and bandwidth.

Images with the same filename are renamed...

Also the script doesn't handle any functions for deleting the images !!!USER BEWARE!!!

Please keep and eye on the size of your image upload directory.