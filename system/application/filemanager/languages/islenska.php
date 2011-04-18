<?php

// Íslenska fyrir joomlaXplorer ver 1.2.1 (translated by gudjon@247.is)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Villa(ur)",
	"back"			=> "Bakka",

	// root
	"home"			=> "Mappa heimasvæðisins er ekki til, vinsamlegast kannaðu stillingarnar.",
	"abovehome"		=> "Þessi mappa getur ekki verið staðsett fyrir ofan heimasvæðið þitt.",
	"targetabovehome"	=> "Mappan getur ekki verið staðsett fyrir ofan heimasvæðið þitt.",

	// exist
	"direxist"		=> "Þessi mappa er ekki til.",
	//"filedoesexist"	=> "Þetta skjal er þegar til.",
	"fileexist"		=> "Þessi skrá er ekki til.",
	"itemdoesexist"		=> "Þessi hlutur er þegar til.",
	"itemexist"		=> "Þessi hlutur er ekki til.",
	"targetexist"		=> "Þessi mappa er ekki til.",
	"targetdoesexist"	=> "Þessi hlutur er þegar til.",

	// open
	"opendir"		=> "Gat ekki opnað möppuna.",
	"readdir"		=> "Gat ekki lesið möppuna.",

	// access
	"accessdir"		=> "Þú hefur ekki aðgang að þessari möppu.",
	"accessfile"		=> "Þú hefur ekki aðgang að þessari skrá.",
	"accessitem"		=> "Þú hefur ekki aðgang að þessum hlut.",
	"accessfunc"		=> "Þú hefur ekki aðgang að þessari skipun.",
	"accesstarget"		=> "Þú hefur ekki aðgang að þessari möppu.",

	// actions
	"permread"		=> "Gat ekki sótt aðgangsstýringar.",
	"permchange"		=> "Gat ekki breytt aðgangsstýringum.",
	"openfile"		=> "Gat ekki opnað skjalið.",
	"savefile"		=> "Vistun skjalsins mistókst.",
	"createfile"		=> "Gat ekki búið til skránna.",
	"createdir"		=> "Gat ekki búið til skránna.",
	"uploadfile"		=> "Gat ekki sótt skránna.",
	"copyitem"		=> "Afritun mistókst.",
	"moveitem"		=> "Ekki tókst að færa skránna.",
	"delitem"		=> "Ekki tókst að eyða skránni.",
	"chpass"		=> "Mistókst að breyta lykilorðinu.",
	"deluser"		=> "Gat ekki fjarlægt notanda.",
	"adduser"		=> "Gat ekki bætt inn notanda.",
	"saveuser"		=> "Saving user failed.",
	"searchnothing"		=> "You must supply something to search for.",

	// misc
	"miscnofunc"		=> "Virknin er ekki tiltæk.",
	"miscfilesize"		=> "Skráinn er of stór.",
	"miscfilepart"		=> "Hluti af skránni var sóttur.",
	"miscnoname"		=> "Vinsamlegast skráðu inn nafn.",
	"miscselitems"		=> "Þú hefur ekki valið neina hluti.",
	"miscdelitems"		=> "Ertu viss um að eyða þessum {0} hlut(um)?",
	"miscdeluser"		=> "Ertu viss um að vilja eyða þessum notanda '{0}'?",
	"miscnopassdiff"	=> "Nýa lykilorðið er eins.",
	"miscnopassmatch"	=> "Lykilorðin standast ekki.",
	"miscfieldmissed"	=> "Ekki voru allir reiti rétt útfylltir.",
	"miscnouserpass"	=> "Notendanafn eða lykilorð rangt.",
	"miscselfremove"	=> "Þú getur ekki fjarlægt sjáfan þig.",
	"miscuserexist"		=> "Notandi er þegar til.",
	"miscnofinduser"	=> "Finn ekki notanda.",
	"extract_noarchive" => "Skráinn er ekki þjöppuð safnskrá.",
	"extract_unknowntype" => "Óþekkt safnskrá",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "BREYTA AÐGANGSSTÝRINGUM",
	"editlink"		=> "BREYTA",
	"downlink"		=> "NIÐURHALA",
	"uplink"		=> "UPP",
	"homelink"		=> "HEIM",
	"reloadlink"		=> "ENDURHLAÐA",
	"copylink"		=> "AFRITA",
	"movelink"		=> "FÆRA",
	"dellink"		=> "EYÐA",
	"comprlink"		=> "GEYMA",
	"adminlink"		=> "ADMIN",
	"logoutlink"		=> "ÚTSKRÁ",
	"uploadlink"		=> "UPPHALA",
	"searchlink"		=> "LEIT",
	"extractlink"	=> "Afþjappa",
	'chmodlink'		=> 'Breyta (chmod) Aðgangsstýringum (möppu/skrá(a))', // new mic
	'mossysinfolink'	=> 'eXtplorer upplýsingar (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Fara á heimasíðu joomlaXplorer (new window)', // new mic

	// list
	"nameheader"		=> "Nafn",
	"sizeheader"		=> "Stærð",
	"typeheader"		=> "Gerð",
	"modifheader"		=> "Breytt",
	"permheader"		=> "Aðgangur",
	"actionheader"		=> "Aðgerðir",
	"pathheader"		=> "Slóð",

	// buttons
	"btncancel"		=> "Hætta",
	"btnsave"		=> "Vista",
	"btnchange"		=> "Breyta",
	"btnreset"		=> "Endurstilla",
	"btnclose"		=> "Loka",
	"btncreate"		=> "Búa til",
	"btnsearch"		=> "Leita",
	"btnupload"		=> "Upphala",
	"btncopy"		=> "Afrita",
	"btnmove"		=> "Færa",
	"btnlogin"		=> "Innskrá",
	"btnlogout"		=> "Útskrá",
	"btnadd"		=> "Bæta inn",
	"btnedit"		=> "Breyta",
	"btnremove"		=> "Taka út",

	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENAME',
	'confirm_delete_file' => 'Are you sure you want to delete this file? \\n%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	// actions
	"actdir"		=> "Mappa",
	"actperms"		=> "Breyta aðgangsstýringum",
	"actedit"		=> "Breyta skjali",
	"actsearchresults"	=> "Niðurstöður leitar",
	"actcopyitems"		=> "Afrita hlut(i)",
	"actcopyfrom"		=> "Afrita frá /%s til /%s ",
	"actmoveitems"		=> "Færa hlut(i)",
	"actmovefrom"		=> "Færa frá /%s til /%s ",
	"actlogin"		=> "Innskrá",
	"actloginheader"	=> "Innskrá til að nota QuiXplorer",
	"actadmin"		=> "Kerfisstjórn",
	"actchpwd"		=> "Breyta lykilorði",
	"actusers"		=> "Notendur",
	"actarchive"		=> "Geyma hlut(i)",
	"actupload"		=> "Upphala skrá(m)",

	// misc
	"miscitems"		=> "Hlut(i)",
	"miscfree"		=> "Frítt",
	"miscusername"		=> "Notendanafn",
	"miscpassword"		=> "Lykilorð",
	"miscoldpass"		=> "Gamla lykilorðið",
	"miscnewpass"		=> "Nýtt lykilorð",
	"miscconfpass"		=> "Staðfesta lykilorð",
	"miscconfnewpass"	=> "Staðfesta nýtt lykilorð",
	"miscchpass"		=> "Breyta lykilorði",
	"mischomedir"		=> "Heimasvæði",
	"mischomeurl"		=> "Slóð",
	"miscshowhidden"	=> "Sýna falda hluti",
	"mischidepattern"	=> "Hylja slóð",
	"miscperms"		=> "Aðgangsstýring",
	"miscuseritems"		=> "(nafn, heimasvæði, sýna falda hluti, aðgangsstýringar, virkur)",
	"miscadduser"		=> "Bæta við notenda",
	"miscedituser"		=> "breyta notanda '%s'",
	"miscactive"		=> "Virkur",
	"misclang"		=> "Tungumál",
	"miscnoresult"		=> "Engar niðurstöður fengust.",
	"miscsubdirs"		=> "Leita í undirmöppum",
	"miscpermnames"		=> array("Skoða eingöngu","Breyta","Breyta lykilorði","Breyta & Breyta lykiorði",
					"Administrator"),
	"miscyesno"		=> array("Já","Nei","J","N"),
	"miscchmod"		=> array("Eigandi", "Hópur", "Almennt"),

	// from here all new by mic
	'miscowner'			=> 'Eigandi',
	'miscownerdesc'		=> '<strong>Lýsing:</strong><br />Notandi (UID) /<br />Hópur (GID)<br />Leyfi:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'eXtplorer Upplýsingar',
	'sisysteminfo'		=> 'Kerfisupplýsingar',
	'sibuilton'			=> 'Stýrikerfi',
	'sidbversion'		=> 'Útgáfa gagnagrunns (MySQL)',
	'siphpversion'		=> 'PHP útgáfa',
	'siphpupdate'		=> 'Upplýsingar: <span style="color: red;">PHP sem þú ert að nota er <strong>ekki</strong> raunverulega!</span><br />To guarantee all functions and features of eXtplorer and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'eXtplorer útgáfa',
	'siuseragent'		=> 'Útgáfa Vafrara',
	'sirelevantsettings' => 'Important PHP Settings',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Errors',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Datei Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'Non enabled functions',
	'sieditor'			=> 'WYSIWYG Editor',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'Directory permissions',
	'sidirpermsmess'	=> 'To be shure that all functions and features of eXtplorer are working correct, following folders should have permission to write [chmod 0777]',
	'sionoff'			=> array( 'Á', 'Af' ),
	
	'extract_warning' => "Villtu afþjappa þessari skrá? Hér?\\nAðrar skrár gætu verið yfirskrifaðar ef ekki er farið varlega!",
	'extract_success' => "Aþjöppun tókst",
	'extract_failure' => "Afþjöppun mistókst",
	
	'overwrite_files' => 'Overwrite existing file(s)?',
	"viewlink"		=> "VIEW",
	"actview"		=> "Showing source of file",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Rename a directory or file...',
	'newname'		=>	'New Name',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Return to directory after saving?',
	'line'		=> 	'Line',
	'column'	=>	'Column',
	'wordwrap'	=>	'Wordwrap: (IE only)',
	'copyfile'	=>	'Copy file into this filename',
	
	// Bookmarks
	'quick_jump' => 'Quick Jump To',
	'already_bookmarked' => 'This directory is already bookmarked',
	'bookmark_was_added' => 'This directory was added to the bookmark list.',
	'not_a_bookmark' => 'This directory is not a bookmark.',
	'bookmark_was_removed' => 'This directory was removed from the bookmark list.',
	'bookmarkfile_not_writable' => "Failed to %s the bookmark.\n The Bookmark File '%s' \nis not writable.",
	
	'lbl_add_bookmark' => 'Add this Directory as Bookmark',
	'lbl_remove_bookmark' => 'Remove this Directory from the Bookmark List',
	
	'enter_alias_name' => 'Please enter the alias name for this bookmark',
	
	'normal_compression' => 'normal compression',
	'good_compression' => 'good compression',
	'best_compression' => 'best compression',
	'no_compression' => 'no compression',
	
	'creating_archive' => 'Creating Archive File...',
	'processed_x_files' => 'Processed %s of %s Files',
	
	'ftp_header' => 'Local FTP Authentication',
	'ftp_login_lbl' => 'Please enter the login credentials for the FTP server',
	'ftp_login_name' => 'FTP User Name',
	'ftp_login_pass' => 'FTP Password',
	'ftp_hostname_port' => 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check' => 'Checking FTP connection...',
	'ftp_connection_failed' => "The FTP server could not be contacted. \nPlease check that the FTP server is running on your server.",
	'ftp_login_failed' => "The FTP login failed. Please check the username and password and try again.",
		
	'switch_file_mode' => 'Current mode: <strong>%s</strong>. You could switch to %s mode.',
	'symlink_target' => 'Target of the Symbolic Link',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "The File was saved.",
	"moveitem"		=> "Moving succeeded.",
	"copyitem"		=> "Copying succeeded.",
	'archive_name' 	=> 'Name of the Archive File',
	'archive_saveToDir' 	=> 'Save the Archive in this directory',
	
	'editor_simple'	=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'	=> 'New File/Directory',
	'show_directories' => 'Show Directories',
	'actlogin_success' => 'Login successful!',
	'actlogin_failure' => 'Login failed, try again.',
	'directory_tree' => 'Directory Tree',
	'browsing_directory' => 'Browsing Directory',
	'filter_grid' => 'Filter',
	'paging_page' => 'Page',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => 'First Page',
	'paging_lastpage' => 'Last Page',
	'paging_nextpage' => 'Next Page',
	'paging_prevpage' => 'Previous Page',
	
	'paging_info' => 'Displaying Items {0} - {1} of {2}',
	'paging_noitems' => 'No items to display',
	'aboutlink' => 'About...',
	'password_warning_title' => 'Important - Change your Password!',
	'password_warning_text' => 'The user account you are logged in with (admin with password admin) corresponds to the default eXtplorer priviliged account. Your eXtplorer installation is open to intrusion and you should immediately fix this security hole!',
	'change_password_success' => 'Your Password has been changed!',
	'success' => 'Success',
	'failure' => 'Failure',
	'dialog_title' => 'Website Dialog',
	'upload_processing' => 'Processing Upload, please wait...',
	'upload_completed' => 'Upload successful!',
	'acttransfer' => 'Transfer from another Server',
	'transfer_processing' => 'Processing Server-to-Server Transfer, please wait...',
	'transfer_completed' => 'Transfer completed!',
	'max_file_size' => 'Maximum File Size',
	'max_post_size' => 'Maximum Upload Limit',
	'done' => 'Done.',
	'permissions_processing' => 'Applying Permissions, please wait...',
	'archive_created' => 'The Archive File has been created!',
	'save_processing' => 'Saving File...',
	'current_user' => 'This script currently runs with the permissions of the following user:',
	'your_version' => 'Your Version',
	'search_processing' => 'Searching, please wait...',
	'url_to_file' => 'URL of the File',
	'file' => 'File'
);
?>
