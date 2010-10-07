// This list may be created by a server logic page PHP/ASP/ASPX/JSP in some backend system.
// This may actually be preferred because, as can be seen, we have to do some string manipulation
// in order to preserve any formating we may have.

// The php code assets will be displayed as a dropdown in all php dialogs if the "php_external_list_url"
// option is defined in TinyMCE init.

var tinyMCEPHPList = new Array(
	// Name in dropdown, PHP code, Description
	
	["Sample #1", "\n\
<?php\n\
	function dir_list($path)\n\
	{\n\
		//open directory\n\
		$myDirectory = dir($path);\n\
\n\
		//get each entry\n\
		while(FALSE !== ($entryname = $myDirectory->read()))\n\
			{\n\
			$dir[$entryname] = $entryname;\n\
			}\n\
		//close directory\n\
		$myDirectory->close();\n\
\n\
	return($dir);\n\
	}\
\n\
?>\n\
", "This is an example of a code 'snipet' that can be pre-made for inclusion"],
	["Sample #2", "<?php print &quot;$test is a 'variable'&quot;; ?>", "This sample was designed to show how quotations must be encoded."]
);

