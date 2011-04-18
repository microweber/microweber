<?php
// $Id: english.php 149 2009-06-21 18:44:27Z soeren $
// English Language Module for v2.3 (translated by the QuiX project)
// Additional translation by sloarch, mic, Paulino Michelazzo

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"				=> "Error(s)",
	"message"			=> "Message(s)",
	"back"				=> "Go Back",

	// root
	"home"				=> "The home directory doesn't exist, check your settings.",
	"abovehome"			=> "The current directory may not be above the home directory.",
	"targetabovehome"	=> "The target directory may not be above the home directory.",

	// exist
	"direxist"			=> "This directory doesn't exist.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"			=> "This file doesn't exist.",
	"itemdoesexist"		=> "This item already exists.",
	"itemexist"			=> "This item doesn't exist.",
	"targetexist"		=> "The target directory doesn't exist.",
	"targetdoesexist"	=> "The target item already exists.",

	// open
	"opendir"			=> "Unable to open directory.",
	"readdir"			=> "Unable to read directory.",

	// access
	"accessdir"			=> "You are not allowed to access this directory.",
	"accessfile"		=> "You are not allowed to access this file.",
	"accessitem"		=> "You are not allowed to access this item.",
	"accessfunc"		=> "You are not allowed to use this function.",
	"accesstarget"		=> "You are not allowed to access the target directory.",

	// actions
	"permread"			=> "Getting permissions failed.",
	"permchange"		=> "CHMOD Failure (This is usually because of a file ownership problem - e.g. if the HTTP user ('wwwrun' or 'nobody') and the FTP user are not the same)",
	"openfile"			=> "File opening failed.",
	"savefile"			=> "File saving failed.",
	"createfile"		=> "File creation failed.",
	"createdir"			=> "Directory creation failed.",
	"uploadfile"		=> "File upload failed.",
	"copyitem"			=> "Copying failed.",
	"moveitem"			=> "Moving failed.",
	"delitem"			=> "Deleting failed.",
	"chpass"			=> "Changing password failed.",
	"deluser"			=> "Removing user failed.",
	"adduser"			=> "Adding user failed.",
	"saveuser"			=> "Saving user failed.",
	"searchnothing"		=> "You must supply something to search for.",

	// misc
	"miscnofunc"		=> "Function unavailable.",
	"miscfilesize"		=> "File exceeds maximum size.",
	"miscfilepart"		=> "File was only partially uploaded.",
	"miscnoname"		=> "You must supply a name.",
	"miscselitems"		=> "You haven't selected any item(s).",
	"miscdelitems"		=> "Are you sure you want to delete these {0} item(s)?",
	"miscdeluser"		=> "Are you sure you want to delete user '{0}'?",
	"miscnopassdiff"	=> "New password doesn't differ from current.",
	"miscnopassmatch"	=> "Passwords don't match.",
	"miscfieldmissed"	=> "You missed an important field.",
	"miscnouserpass"	=> "Username or password incorrect.",
	"miscselfremove"	=> "You can't remove yourself.",
	"miscuserexist"		=> "User already exists.",
	"miscnofinduser"	=> "Can't find user.",
	"extract_noarchive"		=> "The File is not an extractable Archive.",
	"extract_unknowntype"	=> "Unknown Archive Type",
	
	'chmod_none_not_allowed'	=> 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists'		=> 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable'	=> 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed'	=> 'Failed saving the Archive File'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"			=> "Change Permissions",
	"editlink"			=> "Edit",
	"downlink"			=> "Download",
	"uplink"			=> "Up",
	"homelink"			=> "Home",
	"reloadlink"		=> "Reload",
	"copylink"			=> "Copy",
	"movelink"			=> "Move",
	"dellink"			=> "Delete",
	"comprlink"			=> "Archive",
	"adminlink"			=> "Admin",
	"logoutlink"		=> "Logout",
	"uploadlink"		=> "Upload",
	"searchlink"		=> "Search",
	'difflink'     		=> 'Diff',
	"extractlink"		=> "Extract Archive",
	'chmodlink'			=> 'Change (chmod) Rights (Folder/File(s))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'			=> 'Go to the eXtplorer Website (new window)', // new mic

	// list
	"nameheader"		=> "Name",
	"sizeheader"		=> "Size",
	"typeheader"		=> "Type",
	"modifheader"		=> "Modified",
	"permheader"		=> "Perms",
	"actionheader"		=> "Actions",
	"pathheader"		=> "Path",

	// buttons
	"btncancel"			=> "Cancel",
	"btnsave"			=> "Save",
	"btnchange"			=> "Change",
	"btnreset"			=> "Reset",
	"btnclose"			=> "Close",
	"btnreopen"			=> "Reopen",
	"btncreate"			=> "Create",
	"btnsearch"			=> "Search",
	"btnupload"			=> "Upload",
	"btncopy"			=> "Copy",
	"btnmove"			=> "Move",
	"btnlogin"			=> "Login",
	"btnlogout"			=> "Logout",
	"btnadd"			=> "Add",
	"btnedit"			=> "Edit",
	"btnremove"			=> "Remove",
	"btndiff"			=> "Diff",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'		=> 'Rename',
	'confirm_delete_file' => 'Are you sure you want to delete this file? <br />%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	// actions
	"actdir"			=> "Directory",
	"actperms"			=> "Change permissions",
	"actedit"			=> "Edit file",
	"actsearchresults"	=> "Search results",
	"actcopyitems"		=> "Copy item(s)",
	"actcopyfrom"		=> "Copy from /%s to /%s ",
	"actmoveitems"		=> "Move item(s)",
	"actmovefrom"		=> "Move from /%s to /%s ",
	"actlogin"			=> "Login",
	"actloginheader"	=> "Login to use eXtplorer",
	"actadmin"			=> "Administration",
	"actchpwd"			=> "Change password",
	"actusers"			=> "Users",
	"actarchive"		=> "Archive item(s)",
	"actupload"			=> "Upload file(s)",

	// misc
	"miscitems"			=> "Item(s)",
	"miscfree"			=> "Free",
	"miscusername"		=> "Username",
	"miscpassword"		=> "Password",
	"miscoldpass"		=> "Old password",
	"miscnewpass"		=> "New password",
	"miscconfpass"		=> "Confirm password",
	"miscconfnewpass"	=> "Confirm new password",
	"miscchpass"		=> "Change password",
	"mischomedir"		=> "Home directory",
	"mischomeurl"		=> "Home URL",
	"miscshowhidden"	=> "Show hidden items",
	"mischidepattern"	=> "Hide pattern",
	"miscperms"			=> "Permissions",
	"miscuseritems"		=> "(name, home directory, show hidden items, permissions, active)",
	"miscadduser"		=> "add user",
	"miscedituser"		=> "edit user '%s'",
	"miscactive"		=> "Active",
	"misclang"			=> "Language",
	"miscnoresult"		=> "No results available.",
	"miscsubdirs"		=> "Search subdirectories",
	"miscpermnames"		=> array("View only","Modify","Change password","Modify & Change password","Administrator"),
	"miscyesno"			=> array("Yes","No","Y","N"),
	"miscchmod"			=> array("Owner", "Group", "Public"),
	'misccontent'		=> "File Contents",

	// from here all new by mic
	'miscowner'			=> 'Owner',
	'miscownerdesc'		=> '<strong>Description:</strong><br />User (UID) /<br />Group (GID)<br />Current rights:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorer System Info",
	'sisysteminfo'		=> 'System Info',
	'sibuilton'			=> 'Operating System',
	'sidbversion'		=> 'Database Version (MySQL)',
	'siphpversion'		=> 'PHP Version',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">The PHP version you use is <strong>not</strong> actual!</span><br />To guarantee all functions and features of Mambo and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'eXtplorer Version',
	'siuseragent'		=> 'Browser Version',
	'sirelevantsettings'	=> 'Important PHP Settings',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Errors',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'File Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'Disabled functions',
	'sieditor'			=> 'WYSIWYG Editor',
	'siconfigfile'		=> 'Config file',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'Directory permissions',
	'sidirpermsmess'	=> 'To be shure that all functions and features of eXtplorer are working correct, following folders should have permission to write [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning'	=> "Do you really want to extract this file? Here?<br />This will overwrite existing files when not used carefully!",
	'extract_success'	=> "Extraction was successful",
	'extract_failure'	=> "Extraction failed",
	
	'overwrite_files'	=> 'Overwrite existing file(s)?',
	"viewlink"			=> "View",
	"actview"			=> "Showing source of file",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'		=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'		=>	'Rename a directory or file...',
	'newname'			=>	'New Name',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'			=>	'Return to directory after saving?',
	'line'				=> 	'Line',
	'column'			=>	'Column',
	'wordwrap'			=>	'Wordwrap: (IE only)',
	'copyfile'			=>	'Copy file into this filename',
	
	// Bookmarks
	'quick_jump' 		=> 'Quick Jump To',
	'already_bookmarked' => 'This directory is already bookmarked',
	'bookmark_was_added' => 'This directory was added to the bookmark list.',
	'not_a_bookmark'	=> 'This directory is not a bookmark.',
	'bookmark_was_removed' => 'This directory was removed from the bookmark list.',
	'bookmarkfile_not_writable' => "Failed to %s the bookmark.\n The Bookmark File '%s' \nis not writable.",
	
	'lbl_add_bookmark'	=> 'Add this Directory as Bookmark',
	'lbl_remove_bookmark' => 'Remove this Directory from the Bookmark List',
	
	'enter_alias_name'	=> 'Please enter the alias name for this bookmark',
	
	'normal_compression' => 'normal compression',
	'good_compression'	=> 'good compression',
	'best_compression'	=> 'best compression',
	'no_compression'	=> 'no compression',
	
	'creating_archive'	=> 'Creating Archive File...',
	'processed_x_files'	=> 'Processed %s of %s Files',
	
	'ftp_header'		=> 'Local FTP Authentication',
	'ftp_login_lbl'		=> 'Please enter the login credentials for the FTP server',
	'ftp_login_name'	=> 'FTP User Name',
	'ftp_login_pass'	=> 'FTP Password',
	'ftp_hostname_port'	=> 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check'	=> 'Checking FTP connection...',
	'ftp_connection_failed' => "The FTP server could not be contacted. \nPlease check that the FTP server is running on your server.",
	'ftp_login_failed'	=> "The FTP login failed. Please check the username and password and try again.",
		
	'switch_file_mode'	=> 'Current mode: <strong>%s</strong>. You could switch to %s mode.',
	'symlink_target'	=> 'Target of the Symbolic Link',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"			=> "The File was saved.",
	"moveitem"			=> "Moving succeeded.",
	"copyitem"			=> "Copying succeeded.",
	'archive_name'	 	=> 'Name of the Archive File',
	'archive_saveToDir'	=> 'Save the Archive in this directory',
	
	'editor_simple'		=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'			=> 'New File/Directory',
	'show_directories'	=> 'Show Directories',
	'actlogin_success'	=> 'Login successful!',
	'actlogin_failure'	=> 'Login failed, try again.',
	'directory_tree'	=> 'Directory Tree',
	'browsing_directory'	=> 'Browsing Directory',
	'filter_grid'		=> 'Filter',
	'paging_page'		=> 'Page',
	'paging_of_X'		=> 'of {0}',
	'paging_firstpage'	=> 'First Page',
	'paging_lastpage'	=> 'Last Page',
	'paging_nextpage'	=> 'Next Page',
	'paging_prevpage'	=> 'Previous Page',
	
	'paging_info'		=> 'Displaying Items {0} - {1} of {2}',
	'paging_noitems'	=> 'No items to display',
	'aboutlink'			=> 'About...',
	'password_warning_title'	=> 'Important - Change your Password!',
	'password_warning_text'		=> 'The user account you are logged in with (admin with password admin) corresponds to the default eXtplorer priviliged account. Your eXtplorer installation is open to intrusion and you should immediately fix this security hole!',
	'change_password_success'	=> 'Your Password has been changed!',
	'success'			=> 'Success',
	'failure'			=> 'Failure',
	'dialog_title'		=> 'Website Dialog',
	'upload_processing'	=> 'Processing Upload, please wait...',
	'upload_completed'	=> 'Upload successful!',
	'acttransfer'		=> 'Transfer from another Server',
	'transfer_processing'	=> 'Processing Server-to-Server Transfer, please wait...',
	'transfer_completed'	=> 'Transfer completed!',
	'max_file_size'		=> 'Maximum File Size',
	'max_post_size'		=> 'Maximum Upload Limit',
	'done'				=> 'Done.',
	'permissions_processing' => 'Applying Permissions, please wait...',
	'archive_created'	=> 'The Archive File has been created!',
	'save_processing'	=> 'Saving File...',
	'current_user'		=> 'This script currently runs with the permissions of the following user:',
	'your_version'		=> 'Your Version',
	'search_processing'	=> 'Searching, please wait...',
	'url_to_file'		=> 'URL of the File',
	'file'				=> 'File'
	
);
?>
