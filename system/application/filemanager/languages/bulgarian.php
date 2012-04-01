<?php
// $Id: bulgarian.php 2009-04-28 sloarch $
// Bulgarian Language Module for v2.4 (translated by the QuiX project)
// Additional translation by Ivo Apostolov, sloarch, mic, Paulino Michelazzo

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"				=> "ГРЕШКА(И)",
	"message"			=> "Съобщение(я)",
	"back"				=> "Назад",

	// root
	"home"				=> "Началната директория не съществува, проверете вашите настройки.",
	"abovehome"			=> "Текущата директория не може да бъде преди началната.",
	"targetabovehome"	=> "Целевата директория не може да бъде преди началната.",

	// exist
	"direxist"			=> "Директорията не съществува",
	//"filedoesexist"	=> "Файл с това име вече съществува",
	"fileexist"			=> "Такъв файл не съществува",
	"itemdoesexist"		=> "Такъв обект вече съществува",
	"itemexist"			=> "Такъв обект не съществува",
	"targetexist"		=> "Целевата директория не съществува",
	"targetdoesexist"	=> "Целевият обект не съшествува",

	// open
	"opendir"			=> "Директорията не може да бъде отворена",
	"readdir"			=> "Директорията не може да бъде прочетена",

	// access
	"accessdir"			=> "Нямате достъп до тази директория",
	"accessfile"		=> "Нямате достъп до този файл",
	"accessitem"		=> "Нямате достъп до този обект",
	"accessfunc"		=> "Нямате право да ползвате тази функция",
	"accesstarget"		=> "Нямате достъп до целевата директория",

	// actions
	"permread"			=> "Грешка при получаване на права за достъп",
	"permchange"		=> "Грешка при смяна права за достъп",
	"openfile"			=> "Грешка при отваряне на файл",
	"savefile"			=> "Грешка при запис на файл",
	"createfile"		=> "Грешка при създаване на файл",
	"createdir"			=> "Грешка при създаване на директория",
	"uploadfile"		=> "Грешка при качване на файл",
	"copyitem"			=> "Грешка при копиране",
	"moveitem"			=> "Грешка при преименуване",
	"delitem"			=> "Грешка при изтриване",
	"chpass"			=> "Грешка при промяна на парола",
	"deluser"			=> "Грешка при изтриване на потребител",
	"adduser"			=> "Грешка при създаване на потребител",
	"saveuser"			=> "Грешка при запис на потребител",
	"searchnothing"		=> "Попълнете полето за търсене",
	
	// misc
	"miscnofunc"		=> "Недостъпна функция",
	"miscfilesize"		=> "Превишен максимален размер на файла",
	"miscfilepart"		=> "Файла е качен частично",
	"miscnoname"		=> "Трябва да въведете име",
	"miscselitems"		=> "Не сте избрали обект(и)",
	"miscdelitems"		=> "Сигурни ли сте че искате да изтриете тези \"+num+\" обект(а)?",
	"miscdeluser"		=> "Сигурни ли сте че искате да изтриете потребител '\"+user+\"'?",
	"miscnopassdiff"	=> "Новата парола не се отличава от предишната",
	"miscnopassmatch"	=> "Паролите не съвпадат",
	"miscfieldmissed"	=> "Пропуснали сте да попълните важно поле",
	"miscnouserpass"	=> "Грешно име или парола",
	"miscselfremove"	=> "Не можете да изтриете собственият си акаунт",
	"miscuserexist"		=> "Потребителят вече съществува",
	"miscnofinduser"	=> "Потребителят не може да бъде открит",
	"extract_noarchive"		=> "Този файл не е извлечимото Архив.",
	"extract_unknowntype"	=> "Неизвестен Архив Тип",
	
	'chmod_none_not_allowed'	=> 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists'		=> 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable'	=> 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed'	=> 'СПАСЯВАНЕ НА архивен файл неуспешно'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"			=> "ПРОМЕНИ ПРАВА НА ДОСТЪП",
	"editlink"			=> "РЕДАКТИРАЙ",
	"downlink"			=> "ИЗТЕГЛИ",
	"uplink"			=> "НАГОРЕ",
	"homelink"			=> "НАЧАЛО",
	"reloadlink"		=> "ОБНОВИ",
	"copylink"			=> "КОПИРАЙ",
	"movelink"			=> "ПРЕМЕСТИ",
	"dellink"			=> "ИЗТРИЙ",
	"comprlink"			=> "АРХИВИРАЙ",
	"adminlink"			=> "АДМИНИСТРИРАНЕ",
	"logoutlink"		=> "ИЗХОД",
	"uploadlink"		=> "ПРИКАЧИ",
	"searchlink"		=> "ТЪРСИ",
	'difflink'     		=> 'Диференц',
	"extractlink"		=> "Извадка Архив",
	'chmodlink'			=> 'Change (chmod) Rights (Folder/File(s))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'			=> 'Go to the eXtplorer Website (new window)', // new mic

	// list
	"nameheader"		=> "Файл",
	"sizeheader"		=> "Размер",
	"typeheader"		=> "Тип",
	"modifheader"		=> "Променен",
	"permheader"		=> "Права",
	"actionheader"		=> "Действия",
	"pathheader"		=> "Път",
 
	// buttons
	"btncancel"			=> "Отмени",
	"btnsave"			=> "Съхрани",
	"btnchange"			=> "Промени",
	"btnreset"			=> "Изчисти",
	"btnclose"			=> "Затвори",
	"btnreopen"			=> "Откривам",
	"btncreate"			=> "Създай",
	"btnsearch"			=> "Търси",
	"btnupload"			=> "Прикачи",
	"btncopy"			=> "Копирай",
	"btnmove"			=> "Премести",
	"btnlogin"			=> "Вход",
	"btnlogout"			=> "Изход",
	"btnadd"			=> "Добави",
	"btnedit"			=> "Редактирай",
	"btnremove"			=> "Изтрий",
	"btndiff"			=> "Диференц",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'		=> 'Преименувайте',
	'confirm_delete_file' => 'Сигурни ли сте, че искате да изтриете този файл? <br />%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	// actions
	"actdir"		=> "Папка",
	"actperms"		=> "Промяна на права",
	"actedit"		=> "Редактирай файл",
	"actsearchresults"	=> "Резултати от търсене",
	"actcopyitems"		=> "Копирай обект(и)",
	"actcopyfrom"		=> "Копирай от /%s в /%s ",
	"actmoveitems"		=> "Премести обект(и)",
	"actmovefrom"		=> "Премести от /%s в /%s ",
	"actlogin"		=> "Вход",
	"actloginheader"	=> "Вход за да ползваш QuiXplorer",
	"actadmin"		=> "Администриране",
	"actchpwd"		=> "Смени парола",
	"actusers"		=> "Потребители",
	"actarchive"		=> "Архивирай объект(и)",
	"actupload"		=> "Прикачи файл(ове)",

	// misc
	"miscitems"		=> "Обект(и)",
	"miscfree"		=> "Свободно",
	"miscusername"		=> "Потребител",
	"miscpassword"		=> "Парола",
	"miscoldpass"		=> "Стара парола",
	"miscnewpass"		=> "Нова парола",
	"miscconfpass"		=> "Потвърдете парола",
	"miscconfnewpass"	=> "Потвърдете нова парола",
	"miscchpass"		=> "Промени парола",
	"mischomedir"		=> "Начална директория",
	"mischomeurl"		=> "Начален URL",
	"miscshowhidden"	=> "Показвай скрите обекти",
	"mischidepattern"	=> "Скрий файлове",
	"miscperms"			=> "Права",
	"miscuseritems"		=> "(име, начална директория, показвай скрити обекти, права за достъп, активен)",
	"miscadduser"		=> "добави потребител",
	"miscedituser"		=> "редактирай потребител '%и'",
	"miscactive"		=> "Активен",
	"misclang"			=> "Език",
	"miscnoresult"		=> "Няма резултати",
	"miscsubdirs"		=> "Търси в поддиректории",
	"miscpermnames"		=> array("Само да разглежда","Редактиране","Смяна на парола","Права и смяна на парола",
					"Администратор"),
	"miscyesno"		=> array("Да","Не","Д","Н"),
	"miscchmod"		=> array("Притежател", "Група", "Общодостъпен"),
	'misccontent'	=> "Съдържание на файла",

	// from here all new by mic
	'miscowner'			=> 'Собственик',
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
