<?php

// Russian Language Module for joomlaXplorer (translated by Mikhail M. Pigulsky - mikhail@mikhail.pp.ru)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
      // error
      "error"                  => "ОШИБКА(И)",
      "back"                  => "Вернуться",
      
      // root
      "home"                  => "Домашняя директория не существует! Проверьте настройки.",
      "abovehome"            => "Текущая директория не может находится выше домашнего каталога.",
      "targetabovehome"      => "Запрошенная директория не может находится выше домашнего каталога.",

      // exist
      "direxist"            => "Директория не существует",
      //"filedoesexist"      => "Такой файл уже существует",
      "fileexist"            => "Такого файла не существует",
      "itemdoesexist"            => "Такой объект уже существует",
      "itemexist"            => "Такого объекта существует",
      "targetexist"            => "Назначенной директории не существует",
      "targetdoesexist"      => "Назначенного объекта не существует",
      
      // open
      "opendir"            => "Невозможно открыть директорию",
      "readdir"            => "Невозможно прочитать директорию",

      // access
      "accessdir"            => "Вам запрещено заходить в данную директорию",
      "accessfile"            => "Вам запрещено использовать данный файл",
      "accessitem"            => "Вам запрещено использовать данный объект",
      "accessfunc"            => "Вам запрещено использовать данную функцию",
      "accesstarget"            => "Вам запрещено входить в заданную директорию",

      // actions
      "permread"            => "Ошибка в получении прав доступа",
      "permchange"            => "Ошибка в смене прав доступа",
      "openfile"            => "Провал в открытии файла",
      "savefile"            => "Провал в сохранении файла",
      "createfile"            => "Провал в создании файла",
      "createdir"            => "Провал в создании директории",
      "uploadfile"            => "Провал в загрузке файла",
      "copyitem"            => "Провал в копировании",
      "moveitem"            => "Провал в переименовании",
      "delitem"            => "Провал в удалении",
      "chpass"            => "Провал в смене пароля",
      "deluser"            => "Провал в удалении пользователя",
      "adduser"            => "Провал в удалении пользователя",
      "saveuser"            => "Провал в сохранении пользователя",
      "searchnothing"            => "Строка поиска не должна быть пустой",
      
      // misc
      "miscnofunc"            => "Функция недоступна",
      "miscfilesize"            => "Файл превышает максимальный размер",
      "miscfilepart"            => "Файл был загружен частично",
      "miscnoname"            => "Вы должны дать задать имя",
      "miscselitems"            => "Вы не выбрали объект(ы)",
      "miscdelitems"            => "Вы уверены, что хотите удалить {0} объект(а/ов)?",
      "miscdeluser"            => "Вы уверены, что хотите удалить пользователя '{0}'?",
      "miscnopassdiff"      => "Новый пароль не отличается от текущего",
      "miscnopassmatch"      => "Пароли не совпадают",
      "miscfieldmissed"      => "Вы пропустили важное поле",
      "miscnouserpass"      => "Имя пользователя или пароль не правильны",
      "miscselfremove"      => "Вы не можете удалить самого себя",
      "miscuserexist"            => "Такой пользователь уже существует",
      "miscnofinduser"      => "Невозможно найти пользователя",
	"extract_noarchive" => "The File is no extractable Archive.",
	"extract_unknowntype" => "Unknown Archive Type",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
      // links
      "permlink"            => "ПОМЕНЯТЬ ПРАВА ДОСТУПА",
      "editlink"            => "РЕДАКТИРОВАТЬ",
      "downlink"            => "СКАЧАТЬ",
      "uplink"            => "НАВЕРХ",
      "homelink"            => "ДОМОЙ",
      "reloadlink"            => "ОБНОВИТЬ",
      "copylink"            => "КОПИРОВАТЬ",
      "movelink"            => "ПЕРЕМЕСТИТЬ",
      "dellink"            => "УДАЛИТЬ",
      "comprlink"            => "АРХИВИРОВАТЬ",
      "adminlink"            => "АДМИНИСТРИРОВАНИЕ",
      "logoutlink"            => "ВЫЙТИ",
      "uploadlink"            => "ЗАКАЧАТЬ",
      "searchlink"            => "ПОИСК",
	"extractlink"	=> "Extract Archive",
	'chmodlink'		=> 'Change (chmod) Rights (Folder/File(s))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Go to the joomlaXplorer Website (new window)', // new mic
      
      // list
      "nameheader"            => "Файл",
      "sizeheader"            => "Размер",
      "typeheader"            => "Тип",
      "modifheader"            => "Изменен",
      "permheader"            => "Права",
      "actionheader"            => "Действия",
      "pathheader"            => "Путь",
      
      // buttons
      "btncancel"            => "Отменя",
      "btnsave"            => "Сохранить",
      "btnchange"            => "Изменить",
      "btnreset"            => "Очистить",
      "btnclose"            => "Закрыть",
      "btncreate"            => "Создать",
      "btnsearch"            => "Поиск",
      "btnupload"            => "Закачать",
      "btncopy"            => "Копировать",
      "btnmove"            => "Переместить",
      "btnlogin"            => "Войти",
      "btnlogout"            => "Выйти",
      "btnadd"            => "Добавить",
      "btnedit"            => "Редактировать",
      "btnremove"            => "Удалить",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENAME',
	'confirm_delete_file' => 'Are you sure you want to delete this file? \\n%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
      
      // actions
      "actdir"            => "Папка",
      "actperms"            => "Поменять права",
      "actedit"            => "Правит файл",
      "actsearchresults"      => "Результаты поиска",
      "actcopyitems"            => "Копировать объект(ы)",
      "actcopyfrom"            => "Копировать из /%s в /%s ",
      "actmoveitems"            => "Переместить объект(ы)",
      "actmovefrom"            => "Переместить из /%s в /%s ",
      "actlogin"            => "Войти",
      "actloginheader"      => "Войти, чтобы начать использовать QuiXplorer",
      "actadmin"            => "Администрирование",
      "actchpwd"            => "Сменить пароль",
      "actusers"            => "Пользователи",
      "actarchive"            => "Заархивировать объект(ы)",
      "actupload"            => "Закачать файл(ы)",
      
      // misc
      "miscitems"            => "Объект(а/ов)",
      "miscfree"            => "Свободно",
      "miscusername"            => "Пользователь",
      "miscpassword"            => "Пароль",
      "miscoldpass"            => "Старый пароль",
      "miscnewpass"            => "Новый пароль",
      "miscconfpass"            => "Подтвердите пароль",
      "miscconfnewpass"      => "Подтвердите новый пароль",
      "miscchpass"            => "Поменять пароль",
      "mischomedir"            => "Домашняя директория",
      "mischomeurl"            => "Домашний URL",
      "miscshowhidden"      => "Показывать спрятанные объекты",
      "mischidepattern"      => "Прятать файлы",
      "miscperms"            => "Права",
      "miscuseritems"            => "(имя, домашняя директория, показывать спрятанные объекты, права досутпа, активен)",
      "miscadduser"            => "добавить пользователя",
      "miscedituser"            => "редактировать пользователя '%s'",
      "miscactive"            => "Активен",
      "misclang"            => "Язык",
      "miscnoresult"            => "Нет результатов",
      "miscsubdirs"            => "Искать в поддиректориях",
      "miscpermnames"            => array("Только просмотр","Редактирование","Сменя пароля","Правка и смена пароля",
                              "Администратор"),
      "miscyesno"            => array("Да","Нет","Д","Н"),
      "miscchmod"            => array("Владелец", "Группа", "Интернет"),
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
