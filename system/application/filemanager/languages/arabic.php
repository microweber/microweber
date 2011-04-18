<?php
// $Id: $
// English Language Module for v2.3 (translated by the QuiX project)
global $_VERSION;




$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "خطاء",
	"message"			=> "رسالة",
	"back"			=> "إلى السابق",




	// root
	"home"			=> "ألمجلد الرئيسي ليس موجود, راجع إلإعدادات.",
	"abovehome"		=> "المجلد الحالى قد لا يكون أعلى من المجلد الرئيسى.",
	"targetabovehome"	=> "لدليل المراد ليس اعلى من الدليل الرئيسى .",




	// exist
	"direxist"		=> "هذا المجلد ليس موجود",
	//"filedoesexist"	=> "هذا الملف موجود بالفعل",
	"fileexist"		=> "هذا الملف ليس",
	"itemdoesexist"		=> "هذا الصنف موجود بالفعل",
	"itemexist"		=> "هذا الصنف موجود بالفعل",
	"targetexist"		=> "المجلد المراد ليس موجود",
	"targetdoesexist"	=> "البتد المراد ليس موجود",




	// open
	"opendir"		=> "ليمكن فتح هذا المجلد",
	"readdir"		=> "لا يمكن قراءة المجلد",




	// access
	"accessdir"		=> "ليس مسموح لك بفتح  المجلد",
	"accessfile"		=> "ليس مسموح لك لفتح الملف",
	"accessitem"		=> "ليس مسموح لك بالوصول لهذا الصنف",
	"accessfunc"		=> "ليس مسموح لك بإستخدام هذه الوظيفة",
	"accesstarget"		=> "ليس مسموح لك للوصول للمجلد المستهدف",




	// actions
	"permread"		=> "فشل فى الحصول على التصريحات",
	"permchange"		=> "فشل فى - CHMOD (وذلك لوجود مشكلة فى ملف الملكية مثل . بروتوكول انتقال النص المتشعب اذا كان المستخدم ( 'wwwrun' او 'لا احد') ، وبروتوكول نقل الملفات المستخدم ليست هي نفسها) ",
	"openfile"		=> "فشل فى فتح الملف",
	"savefile"		=> "فشل فى حفظ الملف",
	"createfile"		=> "فشل فى إنشاء الملف",
	"createdir"		=> "فشل فى إنشاء المجلد",
	"uploadfile"		=> "فشل فى رفع الملف",
	"copyitem"		=> "فشل فى النسخ",
	"moveitem"		=> "فشل فى النقل",
	"delitem"		=> "فشل فى الحذف",
	"chpass"		=> "فشل فى تغيير كلمة المرور",
	"deluser"		=> "فشل فى حذف مستخدم",
	"adduser"		=> "فشل فى إضافة مستخدم",
	"saveuser"		=> "فسل فى حفظ المستخدم",
	"searchnothing"		=> "يجب كتابة شئ للبحث عنه",




	// misc
	"miscnofunc"		=> "وظيفة غير موجودة",
	"miscfilesize"		=> "الملف أكبر من  المسموح به",
	"miscfilepart"		=> ".تم تحميل الملف بشكل جزئى ",
	"miscnoname"		=> "يجب كتابة الأسم",
	"miscselitems"		=> "لم تختار اى صنف",
	"miscdelitems"		=> "هل انت متأكد من حذف  {0} صنف ?",
	"miscdeluser"		=> "هل انت متأكد من حذف  {0} مستخدم ?",
	"miscnopassdiff"	=> "كلمة المرور غير مختلفة عن الحالية",
	"miscnopassmatch"	=> "كلمة المرور غير متطابقة",
	"miscfieldmissed"	=> "لقد فاتك حقول هامة",
	"miscnouserpass"	=> ". كلمة المرور أو إسم المستخدم غير صحيح",
	"miscselfremove"	=> ".لايمكنك حذف نفسك",
	"miscuserexist"		=> "المسنخدم موجود بالفعل",
	"miscnofinduser"	=> ".لم يتم العثور على المستخدم",
	"extract_noarchive" => "ملف ليس مضغوط",
	"extract_unknowntype" => "نوع الضغط غير معروف",
	
	'chmod_none_not_allowed' => 'تغيير التصاريح للاشئ غير مسموح',
	'archive_dir_notexists' => 'الحفظ - الى الدليل الذي قمت بتحديده لا وجود له.',
	'archive_dir_unwritable' => 'يرجى تحديد مجلد قابل للكتابة لحفظ الملف المضغوط به',
	'archive_creation_failed' => 'فشل فى حفظ الملف المضغوط'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "تغيير الصلاحيات",
	"editlink"		=> "تعديل",
	"downlink"		=> "تنزيل",
	"uplink"		=> "لأعلى",
	"homelink"		=> "البداية",
	"reloadlink"		=> "إعادة تحميل",
	"copylink"		=> "نسخ",
	"movelink"		=> "نقل",
	"dellink"		=> "حذف",
	"comprlink"		=> "ضغط",
	"adminlink"		=> "Admin",
	"uploadlink"		=> "رفع ملف",
	"searchlink"		=> "بحث",
	"extractlink"	=> "فك الضغط",
	'chmodlink'		=> '(CHOMD) تغيير حقوق الملفات', // new mic
	'mossysinfolink'	=> 'ملفات نظام إدارة الملفات ( Server, PHP, mySQL)', // new mic
	'logolink'		=> 'فتح الموقع الخاص بإدارة الملفات فى صفحة جديدة', // new mic




	// list
	"nameheader"		=> "الأسم",
	"sizeheader"		=> "الحمج",
	"typeheader"		=> "النوع",
	"modifheader"		=> "التعديل",
	"permheader"		=> "Prem",
	"actionheader"		=> "الحركات",
	"pathheader"		=> "المسار",




	// buttons
	"btncancel"		=> "إلغاء",
	"btnsave"		=> "حفظ",
	"btnchange"		=> "تغيير",
	"btnreset"		=> "مسح",
	"btnclose"		=> "إغلاق",
	"btncreate"		=> "إنشاء",
	"btnsearch"		=> "بحث",
	"btnupload"		=> "رفع",
	"btncopy"		=> "نسخ",
	"btnmove"		=> "نقل",
	"btnlogin"		=> "تسجيل دخول",
	"btnlogout"		=> "تسجيل خروج",
	"btnadd"		=> "إضافة",
	"btnedit"		=> "تعديل",
	"btnremove"		=> "حذف",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'إعادة تسمية',
	'confirm_delete_file' => 'Are you sure you want to delete this file? <br />%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	// actions
	"actdir"		=> "مجلد",
	"actperms"		=> "تغيير الصلاحيات",
	"actedit"		=> "تعديل ملف",
	"actsearchresults"	=> "نتائج البحث",
	"actcopyitems"		=> "نسخ ملفات",
	"actcopyfrom"		=> "نسح من/%s إلى/%s ",
	"actmoveitems"		=> "نقل(s)",
	"actmovefrom"		=> "نقل من/%s إلى/%s ",
	"actlogin"		=> "تسجيل دخول",
	"actloginheader"	=> "تسجيل دخول لوحة التحكم",
	"actadmin"		=> "الإدارة",
	"actchpwd"		=> "تغيير كلمة المرور",
	"actusers"		=> "مستخدمين",
	"actarchive"		=> "اصناف مضغوطة",
	"actupload"		=> "رفع الملفات",




	// misc
	"miscitems"		=> "الأصناف",
	"miscfree"		=> "فارغ",
	"miscusername"		=> "إسم المستخدم",
	"miscpassword"		=> "كلمة المرور",
	"miscoldpass"		=> "كلمة المرور القديمة",
	"miscnewpass"		=> "كلمة المرور الجديدة",
	"miscconfpass"		=> "تأكيد على كلمة المرور",
	"miscconfnewpass"	=> "تأكيد على كلمة المرور الجديدة",
	"miscchpass"		=> "تغيير كلمة المرور",
	"mischomedir"		=> "المجلد الرئيسى",
	"mischomeurl"		=> "الرئيسى URL",
	"miscshowhidden"	=> "إظهار الملفات المخفية",
	"mischidepattern"	=> "اخفاء نمط",
	"miscperms"		=> "الصلاحيات",
	"miscuseritems"		=> "(الاسم ، المجلد الئيسى ، تظهر الاصناف الخفيه ، والتصاريح ، نشط)",
	"miscadduser"		=> "إضافة مستخدمين",
	"miscedituser"		=> "تعديل مستخدمين '%s'",
	"miscactive"		=> "نشط",
	"misclang"		=> "اللغة",
	"miscnoresult"		=> "لا يوجد نتائج متاحة",
	"miscsubdirs"		=> "بحث فى امجلدات الفرعية",
	"miscpermnames"		=> array("تصفح فقط","تعديل","تغيير كلمة المرور","تعديل وتغيير كلمة المرور",
					"مدير"),
	"miscyesno"		=> array("نعم","لا","Y","N"),
	"miscchmod"		=> array("مالك", "مجموعة", "عام"),




	// from here all new by mic
	'miscowner'			=> 'مالك',
	'miscownerdesc'		=> '<strong> وصف :</strong><br /> مستخدم  (UID) /<br /> مجموعة  (GID)<br />الحقوق الحالية:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',




	// sysinfo (new by mic)
	'simamsysinfo'		=> "بيانات نظام مدير الملفات",
	'sisysteminfo'		=> 'نبيانات النظام',
	'sibuilton'			=> 'نظام التشغيل',
	'sidbversion'		=> 'إصدار قاعدة البيانات (MySQL)',
	'siphpversion'		=> 'PHP إصدار',
	'siphpupdate'		=> 'بيانات: <span style="color: red;">The PHP version you use is <strong>not</strong> actual!</span><br />To guarantee all functions and features of Mambo and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'eXtplorer Version',
	'siuseragent'		=> 'Browser Version',
	'sirelevantsettings' => 'Important PHP Settings',
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
	
	'extract_warning' => "Do you really want to extract this file? Here?<br />This will overwrite existing files when not used carefully!",
	'extract_success' => "Extraction was successful",
	'extract_failure' => "Extraction failed",
	
	'overwrite_files' => 'Overwrite existing file(s)?',
	"viewlink"		=> "View",
	"actview"		=> "Showing source of file",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Rename a directory or file...',
	'newname'		=>	'New Name',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'الرجوع إلى المجلد بعد الحفظ?',
	'line'		=> 	'سطر',
	'column'	=>	'عمود',
	'wordwrap'	=>	' (الاكسبلورر فقط :(تساوى السطر',
	'copyfile'	=>	'نسخ الملف بهذا الأسم',
	
	// Bookmarks
	'quick_jump' => 'الأنتقال السريع إلى',
	'already_bookmarked' => 'هذا المجلد تم  تعليمه من قبل',
	'bookmark_was_added' => 'هذا المجلد تم تعليمه من قبل فى القائمة',
	'not_a_bookmark' => 'هذا الدليل لم يتم تعليمه من قبل',
	'bookmark_was_removed' => 'هذا المجلد تم حذفه من قائمة التعليم',
	'bookmarkfile_not_writable' => "فشل فى  %s التعليم.\n ملف التعليم '%s' \n ليس قابل للكتابة",
	
	'lbl_add_bookmark' => 'إضافة هذا المجلد للتعليم',
	'lbl_remove_bookmark' => 'حذف هذا المجلد من قائمة التعليم',
	
	'enter_alias_name' => 'من فضلك ادخل اسم مستعار لقائمة التعليم',
	
	'normal_compression' => 'ضغط عادى',
	'good_compression' => 'ضغط جيد',
	'best_compression' => 'ضغط فائق',
	'no_compression' => 'بدون ضغط',
	
	'creating_archive' => '... إنشاء ملف مضغوط',
	'processed_x_files' => '  الجاهزة %s من %s الملفات ',
	
	'ftp_header' => 'محلى FTP المرور ',
	'ftp_login_lbl' => 'الرجاء ادخال اعتماد تسجيل الدخول لخادم بروتوكول نقل الملفات FTP ',
	'ftp_login_name' => 'FTP إسم المستخدم',
	'ftp_login_pass' => 'FTP كلمة المرور',
	'ftp_hostname_port' => 'FTP إسم الخادم والبورت <br />(البورت إختيارى)',
	'ftp_login_check' => 'يتمك فحص FTP الإتصال ب...',
	'ftp_connection_failed' => "لا يمكن الإتصال بالخادم \n من فضلك تأكد من الخدمة لل FTP تعمل على خادمك أو تحقق من البيانات المدخلة",
	'ftp_login_failed' => " لا يمكن الدخول على خادم FTP من فضلك تأكد من كلمة المرور الخاصة بك أو إسم المسشتخدم",
		
	'switch_file_mode' => 'ألوضع الحالى: <strong>%s</strong>. يمكنك التحويل إلى  %s mode.',
	'symlink_target' => 'الهدف من ارتباط رمزي',
	
	"permchange"		=> "تم التغيير بنجاح",
	"savefile"		=> "تم حفظ الملف",
	"moveitem"		=> "تم النقل بنجاح",
	"copyitem"		=> "تم النسخ بنجاح",
	'archive_name' 	=> 'إسم الملف المضغوط',
	'archive_saveToDir' 	=> 'حفظ الملف المضغوط فى المجلد',
	
	'editor_simple'	=> 'وضع التعديل المبسط',
	'editor_syntaxhighlight'	=> 'وضع البروز اللغوى',




	'newlink'	=> 'ملف جديد/مجلد جديد',
	'show_directories' => 'إظهار المجلدات',
	'actlogin_success' => '! الدخول بنجاح ',
	'actlogin_failure' => 'فشل فى الدخول, حاول مرة أخرى',
	'directory_tree' => 'شجرة المجلدات',
	'browsing_directory' => 'تصفح المجلد',
	'filter_grid' => 'تنقية',
	'paging_page' => 'صفحة',
	'paging_of_X' => 'من {0}',
	'paging_firstpage' => 'الصفحة الأولى',
	'paging_lastpage' => 'الصفحة الاخيرة',
	'paging_nextpage' => 'الصفحة التالية',
	'paging_prevpage' => 'الصفحة السابقة',
	
	'paging_info' => ' (0) - (1) من  (2) عرض الاصناف',
	'paging_noitems' => 'لا يوجد اصناف لعرضها',
	'aboutlink' => '.. عن ',
	'password_warning_title' => 'هام جداً - يجب تغيير كلمة المرور !',
	'password_warning_text' => 'الحساب الذى تم الدخول به حساب إفتراضى ويعتبر ثغرة أمنية فى برنامج إدارة الملفات إن لم تغيير كلمة المرور فيجب عليك فوراً تغيير كلمة المرور لسد هذه الثغرة',
	'change_password_success' => '!تم تغيير كلمة المرور بنجاح',
	'success' => 'ناجح',
	'failure' => 'فاشل',
	'dialog_title' => 'صندوق حوارى للموقع',
	'upload_processing' => 'يتم رفع الملفات , من فضلك الإنتظار ...',
	'upload_completed' => 'تم رفع الملفات بنجاح',
	'acttransfer' => 'نقل من خادم آخر',
	'transfer_processing' => '- تجهيز النقل من خادم لخادم ، يرجى الانتظار...',
	'transfer_completed' => 'تم النقل',
	'max_file_size' => 'اقصى حجم للملف',
	'max_post_size' => 'رفع الحد الأقصى',
	'done' => '. تم ',
	'permissions_processing' => '... يتم تطبيق الصلاحيات من فضلك إنتظر ',
	'archive_created' => 'تم إنشاء الملف المضغوط',
	'save_processing' => '.. يتم حفظ الملف',
	'current_user' => 'هذا النص يتعارض حاليا مع تصاريح المستخدم التالية .. للمستخدم الحالى:',
	'your_version' => 'إصدار نسختك هو',
	'search_processing' => 'يتم البحث, برجاء الإنتظار ...',
	'url_to_file' => 'URL الملفات',
	'file' => 'ملف'
	
);
?>