<?php

// Greek Language file for joomlaXplorer
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "ERROR(S)",
	"back"			=> "Επιστροφή",
	
	// root
	"home"			=> "Ο μητρικός κατάλογος δέν υπάρχει, παρακαλώ ελέξτε τις ρυθμίσεις σας.",
	"abovehome"		=> "Ο τρέχων κατάλογος δέν μπορεί να υπερβαίνει τον μητρικό κατάλογο.",
	"targetabovehome"	=> "Ο επιλεγμένος κατάλογος δέν μπορεί να υπερβαίνει τον μητρικό κατάλογο.",
	
	// exist
	"direxist"		=> "Αυτός ο κατάλογος δέν υπάρχει.",
	//"filedoesexist"	=> "Αυτό το αρχείο δέν υπάρχει.",
	"fileexist"		=> "Αυτό το αρχείο δέν υπάρχει.",
	"itemdoesexist"		=> "Αυτό το αντικείμενο υπάρχει ήδη.",
	"itemexist"		=> "Αυτό το αντικείμενο υπάρχει ήδη.",
	"targetexist"		=> "Ό επιλεγμένος κατάλογος δέν υπάρχει.",
	"targetdoesexist"	=> "Το επιλεγμένο αντικείμενο υπάρχει ήδη.",
	
	// open
	"opendir"		=> "Είναι αδύνατον να ανοιχτεί ο κατάλογος.",
	"readdir"		=> "Είναι αδύνατον να διαβαστεί ο κατάλογος.",
	
	// access
	"accessdir"		=> "Δέν σας επιτρέπεται πρόσβαση σ' αυτόν τον κατάλογο.",
	"accessfile"		=> "Δέν σας επιτρέπεται πρόσβαση σ' αυτό το αρχείο.",
	"accessitem"		=> "Δέν σας επιτρέπεται πρόσβαση σ' αυτό το αντικείμενο.",
	"accessfunc"		=> "Δέν σας επιτρέπεται πρόσβαση σ' αυτή την λειτουργία.",
	"accesstarget"		=> "Δέν σας επιτρέπεται πρόσβαση σ' αυτόν τον κατάλογο.",
	
	// actions
	"permread"		=> "Η διαδικασία ανάκτησης προσβάσεων δέν κατέστει δυνατό να εκτελεστεί.",
	"permchange"		=> "Η διαδικασία μετατροπής προσβάσεων απέτυχε.",
	"openfile"		=> "Η διαδικασία ανοίγματος αρχείου απέτυχε.",
	"savefile"		=> "Η διαδικασία αποθήκευσης αρχείου απέτυχε.",
	"createfile"		=> "Η διαδικασία δημιουργίας αρχείου απέτυχε.",
	"createdir"		=> "Η διαδικασία δημιουργίας καταλόγου απέτυχε.",
	"uploadfile"		=> "Η διαδικασία φόρτωσης αρχείου απέτυχε.",
	"copyitem"		=> "Η διαδικασία αντιγραφής απέτυχε.",
	"moveitem"		=> "Η διαδικασία μετακίνησης απέτυχε.",
	"delitem"		=> "Η διαδικασία διαγραφής απέτυχε.",
	"chpass"		=> "Η διαδικασία μεταβολής κωδικού πρόσβασης απέτυχε.",
	"deluser"		=> "Η διαδικασία απομάκρυνσης χρήστη απέτυχε.",
	"adduser"		=> "Η διαδικασία προσθήκης χρήστη απέτυχε.",
	"saveuser"		=> "Η διαδικασία αποθήκευσης των στοιχείων του χρήστη απέτυχε.",
	"searchnothing"		=> "Είναι απαραίτητο να ορίσετε κάποια φράση για την οποία θα εκτελεστεί η αναζήτηση.",
	
	// misc
	"miscnofunc"		=> "Η διαδικασία δέν είναι διαθέσιμη.",
	"miscfilesize"		=> "Το αρχείο υπερβαίνει το μέγιστο επιτρεπτό μέγεθος.",
	"miscfilepart"		=> "Το αρχείο φορτώθηκε αποσπασματικά.",
	"miscnoname"		=> "Πρέπει να ορίσετε ένα όνομα.",
	"miscselitems"		=> "Δέν έχετε επιλέξει αντικείμενο(α).",
	"miscdelitems"		=> "Θέλετε να προχωρήσετε στην διαγραφή {0} αντικειμένου(ων);",
	"miscdeluser"		=> "Θέλετε να προχωρήσετε στην διαγραφή του χρήστη '{0}';",
	"miscnopassdiff"	=> "Ο νέος κωδικός πρόσβασης δέν παρουσιάζει διαφορά από τον προηγούμενο.",
	"miscnopassmatch"	=> "Οι κωδικοί δέν ταιριάζουν μεταξύ τους.",
	"miscfieldmissed"	=> "Παραλείψατε ένα σημαντικό πεδίο.",
	"miscnouserpass"	=> "Το όνομα χρήστη ή ο κωδικός πρόσβασης δέν είναι σωστά.",
	"miscselfremove"	=> "Δέν μπορείτε να αφαιρέσετε τον εαυτό σας.",
	"miscuserexist"		=> "Ο χρήστης υπάρχει ήδη.",
	"miscnofinduser"	=> "Ο χρήστης δέν μπορεί να βρεθεί.",
	"extract_noarchive" => "The File is no extractable Archive.",
	"extract_unknowntype" => "Unknown Archive Type",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "ΑΛΛΑΓΗ ΔΙΚΑΙΩΜΑΤΩΝ ΠΡΟΣΒΑΣΗΣ",
	"editlink"		=> "ΕΠΕΞΕΡΓΑΣΙΑ",
	"downlink"		=> "ΑΠΟΘΗΚΕΥΣΗ",
	"uplink"		=> "ΠΑΝΩ",
	"homelink"		=> "ΑΡΧΙΚΗ ΣΕΛΙΔΑ",
	"reloadlink"		=> "ΑΝΑΝΕΩΣΗ",
	"copylink"		=> "ΑΝΤΙΓΡΑΦΗ",
	"movelink"		=> "ΜΕΤΑΚΙΝΗΣΗ",
	"dellink"		=> "ΔΙΑΓΡΑΦΗ",
	"comprlink"		=> "ΣΥΜΠΙΕΣΗ",
	"adminlink"		=> "ΔΙΑΧΕΙΡΙΣΤΗΣ",
	"logoutlink"		=> "ΕΞΟΔΟΣ",
	"uploadlink"		=> "ΦΟΡΤΩΣΗ",
	"searchlink"		=> "ΑΝΑΖΗΤΗΣΗ",
	"extractlink"	=> "Extract Archive",
	'chmodlink'		=> 'Change (chmod) Rights (Folder/File(s))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Go to the eXtplorer Website (new window)', // new mic
	
	// list
	"nameheader"		=> "Όνομα",
	"sizeheader"		=> "Μέγεθος",
	"typeheader"		=> "Τύπος",
	"modifheader"		=> "Τροποποίηση",
	"permheader"		=> "Δικ/τα",
	"actionheader"		=> "Ενέργειες",
	"pathheader"		=> "Διαδρομή",
	
	// buttons
	"btncancel"		=> "'Ακυρο",
	"btnsave"		=> "Αποθήκυεση",
	"btnchange"		=> "Αλλαγή",
	"btnreset"		=> "Αναίρεση",
	"btnclose"		=> "Κλείσιμο",
	"btncreate"		=> "Δημιουργία",
	"btnsearch"		=> "Αναζήτηση",
	"btnupload"		=> "Φόρτωση",
	"btncopy"		=> "Αντιγραφή",
	"btnmove"		=> "Μετακίνηση",
	"btnlogin"		=> "Είσοδος",
	"btnlogout"		=> "Έξοδος",
	"btnadd"		=> "Προσθήκη",
	"btnedit"		=> "Επεξεργασία",
	"btnremove"		=> "Αφαίρεση",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENAME',
	'confirm_delete_file' => 'Are you sure you want to delete this file? \\n%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	
	// actions
	"actdir"		=> "Κατάλογος",
	"actperms"		=> "Αλλαγή δικαιωμάτων πρόσβασης",
	"actedit"		=> "Επεξεργασία Αρχείου",
	"actsearchresults"	=> "Αποτελέσματα Αναζήτησης",
	"actcopyitems"		=> "Αντιγραφή Αντικειμένου(ων)",
	"actcopyfrom"		=> "Αντιγραφή από /%s σε /%s ",
	"actmoveitems"		=> "Μετακίνηση Αντικειμένου(ων)",
	"actmovefrom"		=> "Μετακίνηση από /%s σε /%s ",
	"actlogin"		=> "Είσοδος",
	"actloginheader"	=> "Όνομα που χρησιμοποιεί το QuiXplorer",
	"actadmin"		=> "Διαχείριση",
	"actchpwd"		=> "Αλλαγή Κωδικού Πρόσβασης",
	"actusers"		=> "Χρήστες",
	"actarchive"		=> "Συμπίεση Αντικειμένου(ων)",
	"actupload"		=> "Φόρτωση Αρχείου(ων)",
	
	// misc
	"miscitems"		=> "Αντικείμενο(α)",
	"miscfree"		=> "Ελεύθερο",
	"miscusername"		=> "Όνομα Χρήστη",
	"miscpassword"		=> "Κωδικός Πρόσβασης",
	"miscoldpass"		=> "Παλιός Κωδικός",
	"miscnewpass"		=> "Νέος Κωδικός",
	"miscconfpass"		=> "Επαλήθευση Κωδικού",
	"miscconfnewpass"	=> "Επαλήθευση Νέου Κωδικού",
	"miscchpass"		=> "Αλλαγή Κωδικού",
	"mischomedir"		=> "Αρχικός Κατάλογος",
	"mischomeurl"		=> "URL Αρχικού Καταλόγου",
	"miscshowhidden"	=> "Εμφάνιση κρυφών αντικειμένων",
	"mischidepattern"	=> "Απόκρυψη μορφής",
	"miscperms"		=> "Δικαιώματα πρόσβασης",
	"miscuseritems"		=> "(όνομα, μητρικός κατάλογος, εμφάνιση κρυφών αντικειμένων, δικαιώματα, ενεργό)",
	"miscadduser"		=> "προσθήκη χρήστη",
	"miscedituser"		=> "επεξεργασία χρήστη '%s'",
	"miscactive"		=> "Ενεργό",
	"misclang"		=> "Γλώσσα",
	"miscnoresult"		=> "Δέν υπάρχουν αποτελέσματα.",
	"miscsubdirs"		=> "Αναζήτηση σε υποκαταλόγους",
	"miscpermnames"		=> array("Μόνο για θέαση","Μεταβολή","Μεταβολή Κωδικού Πρόσβασης","Μεταβολή Κωδικού Πρόσβασης",
					"Διαχειριστής"),
	"miscyesno"		=> array("Ναί","Όχι","Ν","Ο"),
	"miscchmod"		=> array("Ιδιοκτήτης", "Ομάδα", "Δημόσιο"),
	// from here all new by mic
	'miscowner'			=> 'Owner',
	'miscownerdesc'		=> '<strong>Description:</strong><br />User (UID) /<br />Group (GID)<br />Current rights:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'eXtplorer System Info',
	'sisysteminfo'		=> 'System Info',
	'sibuilton'			=> 'Operating System',
	'sidbversion'		=> 'Database Version (MySQL)',
	'siphpversion'		=> 'PHP Version',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">The PHP version you use is <strong>not</strong> actual!</span><br />To guarantee all functions and features of eXtplorer and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'eXtplorer Version',
	'siuseragent'		=> 'Browser Version',
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
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Do you really want to extract this file? Here?\\nThis will overwrite existing files when not used carefully!",
	'extract_success' => "Extraction was successful",
	'extract_failure' => "Extraction failed",
	
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
