ABOUT

   This is the PHP plugin for TinyMCE.
   This is an extremely BETA version.

   The code may contain some commented out blocks of
   unused code and probably contains some code that may
   do nothing at all.

   Though it has been tested, it has not been tested in any way
   that even comes close to all of the possible configurations of 
   TinyMCE, nor has it been tested against all possible combinations 
   of php and html/xhtml.
   
   Please use caution and protect your code!!!  I can not be responsible
   if this plugin mangles something you wrote so as always HAVE A BACKUP.
   
   Please post comments, suggestions and findings on the TinyMCE forum
   in this thread:  http://tinymce.moxiecode.com/punbb/viewtopic.php?pid=12080#p12080

INSTALL

   Copy the php folder to your installation of TinyMCE plugins folder.

   Add the php plugin to the plugins line in your tinyMCE.init

   At this time there is one configurable option which is the url
   to the list of pre-programmed "snipets" of php code which you wish
   to have appear in the php popup dialog.  The path shown is to the 
   example I have included with this distribution.
   
   	tinyMCE.init({
   		plugins : "php",
		php_external_list_url : "./tinymce/jscripts/tiny_mce/plugins/php/examples/example_php_list.js",
});

This plugin was developed with TinyMCE Version: 2.0.6.1 (2006-05-04).

Please help make it better and discuss this at the forum like above.

2006-05-31
Brian Guilfoil
gforce301
