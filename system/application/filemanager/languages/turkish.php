<?php

// Turkish Language for joomlaXplorer (Translated by Sinan Ata, Tolga'(cumla.blogspot.com))
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Hata(lar)",
	"message"		=> "Mesaj(lar)",
	"back"			=> "Geri Gel",
	
	// root
	"home"			=> "Ana klasör oluşturulmamış ayarlarınızı kontrol edin.",
	"abovehome"		=> "Bu klasör ana klasörün üstünde olmamalıdır.",
	"targetabovehome"	=> "Hedef klasör ana klasörün üstünde olmamalıdır.",
	
	// exist
	"direxist"		=> "Bu klasör oluşturulamadı.",
	//"filedoesexist"	=> "Bu dosya zaten var.",
	"fileexist"		=> "Bu dosya oluşturulamadı.",
	"itemdoesexist"		=> "Bu öğe zaten var.",
	"itemexist"		=> "Bu öğe mevcut değil.",
	"targetexist"		=> "Hedef klasör mevcut değil.",
	"targetdoesexist"	=> "Hedef öğe zaten mevcut.",
	
	// open
	"opendir"		=> "Klasör Açılamaz.",
	"readdir"		=> "Klasör Okunamaz.",
	
	// access
	"accessdir"		=> "Bu klasöre ulaşmak için izinleriniz yetersiz.",
	"accessfile"		=> "Bu dosyaya ulaşmak için izinleriniz yetersiz.",
	"accessitem"		=> "Bu öğeye ulaşmak için izinleriniz yetersiz.",
	"accessfunc"		=> "Bu fonksiyonu kullanmak için izinleriniz yetersiz.",
	"accesstarget"		=> "Hedef klasöre ulaşmak için izinleriniz yetersiz.",
	
	// actions
	"permread"		=> "İzin gösterimi başarısız.",
	"permchange"		=> "İzin değiştirme başarısız. (sebebi dosya sahiplik sorunu olabilir)",
	"openfile"		=> "Dosya açılması başarısız.",
	"savefile"		=> "Dosya kaydı başarısız.",
	"createfile"		=> "Dosya oluşturma başarısız.",
	"createdir"		=> "Klasör oluşturma başarısız.",
	"uploadfile"		=> "Dosya yüklemesi başarısız.",
	"copyitem"		=> "Kopyalama başarısız.",
	"moveitem"		=> "Taşıma başarısız.",
	"delitem"		=> "Silme başarısız.",
	"chpass"		=> "Şifre değiştirme başarısız.",
	"deluser"		=> "Kullanıcı kaldırma başarısız.",
	"adduser"		=> "Kullanıcı ekleme başarısız.",
	"saveuser"		=> "Kullanıcı değişiklik kaydı başarısız.",
	"searchnothing"		=> "Aramak için bir değer girmelisiniz.",
	
	// misc
	"miscnofunc"		=> "Fonksiyon kullanılabilir.",
	"miscfilesize"		=> "Dosya maksimum boyutu aştı.",
	"miscfilepart"		=> "Dosyanın yalnızca bir kısmı yüklenebildi.",
	"miscnoname"		=> "Bir isim girmelisiniz.",
	"miscselitems"		=> "Hiçbir öğe(ler) seçmediniz.",
	"miscdelitems"		=> " {0} ogeyi silmek istediginizden eminmisiniz?",
	"miscdeluser"		=> "'{0}' kullanıcısını silmek istediğinizden eminmisiniz?",
	"miscnopassdiff"	=> "Yeni şifre eskisinden farklı değil.",
	"miscnopassmatch"	=> "Şifreler eşleşmiyor.",
	"miscfieldmissed"	=> "Gerekli bir boşluğu atladınız.",
	"miscnouserpass"	=> "Kullanıcı adı yada şifreniz yanlış.",
	"miscselfremove"	=> "Kendinizi silemezsiniz.",
	"miscuserexist"		=> "Kullanıcı zaten var.",
	"miscnofinduser"	=> "Kullanıcı bulunamadı.",
	"extract_noarchive" => "The File is no extractable Archive.",
	"extract_unknowntype" => "Bilinmeyen arşiv türü",

	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "İzinleri Değiştir",
	"editlink"		=> "Değiştir",
	"downlink"		=> "İndir",
	"uplink"		=> "Yukarı",
	"homelink"		=> "Başa Dön",
	"reloadlink"		=> "Yenile",
	"copylink"		=> "Kopyala",
	"movelink"		=> "Taşı",
	"dellink"		=> "Sil",
	"comprlink"		=> "Depola",
	"adminlink"		=> "Yönetici",
	"logoutlink"		=> "Çıkış",
	"uploadlink"		=> "Yükle",
	"searchlink"		=> "Ara",
	"extractlink"	=> "Extract Archive",
	'chmodlink'		=> 'İzinleri (chmod) Değiştir (Klasör/Dosya(lar))', // new mic
	'mossysinfolink'	=> 'eXtplorer Sistem Bilgisi (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'JoomlaXplorer Websitesine git (yeni pencerede)', // new mic
	
	// list
	"nameheader"		=> "İsim",
	"sizeheader"		=> "Boyut",
	"typeheader"		=> "Tip",
	"modifheader"		=> "Değiştirme",
	"permheader"		=> "İzinler",
	"actionheader"		=> "Hareketler",
	"pathheader"		=> "Yol",
	
	// buttons
	"btncancel"		=> "Çıkış",
	"btnsave"		=> "Kaydet",
	"btnchange"		=> "Değiştir",
	"btnreset"		=> "Sıfırla",
	"btnclose"		=> "Kapat",
	"btncreate"		=> "Oluştur",
	"btnsearch"		=> "Ara",
	"btnupload"		=> "Yükle",
	"btncopy"		=> "Kopyala",
	"btnmove"		=> "Taşı",
	"btnlogin"		=> "Giriş",
	"btnlogout"		=> "Çıkış",
	"btnadd"		=> "Ekle",
	"btnedit"		=> "Değiştir",
	"btnremove"		=> "Kaldır",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Yeniden Adlandır',
	'confirm_delete_file' => 'Bu dosyayı silmek istediğinize eminmisiniz? <br />%s',
	'success_delete_file' => 'Öğe(ler) başarıyla silindi.',
	'success_rename_file' => 'Dizin/dosya %s başarıyla yeniden adlandırıldı  %s.',
	
	// actions
	"actdir"		=> "Klasör",
	"actperms"		=> "İzinleri Değiştir",
	"actedit"		=> "Dosyayı Düzenle",
	"actsearchresults"	=> "Arama Sonuçları",
	"actcopyitems"		=> "öğe(ler)i kopyala",
	"actcopyfrom"		=> " /%s dan /%s ya ",
	"actmoveitems"		=> "öğe(ler)i taşı",
	"actmovefrom"		=> " /%s dan /%s ya taşı ",
	"actlogin"		=> "Giriş",
	"actloginheader"	=> "eXtplorer kullanmak için giriş yapın",
	"actadmin"		=> "Yönetim",
	"actchpwd"		=> "Şifre Değiştir",
	"actusers"		=> "Kullanıcılar",
	"actarchive"		=> "öğe(ler)i Yedekle",
	"actupload"		=> "Dosya(ları) Yükle",
	
	// misc
	"miscitems"		=> "öğe(ler)",
	"miscfree"		=> "Serbest",
	"miscusername"		=> "Kullanıcı Adı",
	"miscpassword"		=> "Şifre",
	"miscoldpass"		=> "Eski Şifre",
	"miscnewpass"		=> "Yeni Şifre",
	"miscconfpass"		=> "Şifreyi Onayla",
	"miscconfnewpass"	=> "Yeni Şifeyi Onayla",
	"miscchpass"		=> "Şifre Değiştir",
	"mischomedir"		=> "Ana Klasör",
	"mischomeurl"		=> "Baş URL",
	"miscshowhidden"	=> "Gizli öğeleri Göster",
	"mischidepattern"	=> "Resim Gizle",
	"miscperms"		=> "İzinler",
	"miscuseritems"		=> "(isim, ana klasör, gizli öğeleri göster, izinler, Aktif)",
	"miscadduser"		=> "Kullanıcı ekle",
	"miscedituser"		=> "'%s' kullanıcısını değiştir",
	"miscactive"		=> "Aktif",
	"misclang"		=> "Dil",
	"miscnoresult"		=> "Hiç sonuç yok.",
	"miscsubdirs"		=> "Alt kategorileri de ara",
	"miscpermnames"		=> array("Sadece bakılabilir","Modifiye","Şifre değiştir","Modifiye & Şifre Değiştir",
					"Yönetici"),
	"miscyesno"		=> array("Evet","Hayır","E","H"),
	"miscchmod"		=> array("Sahip", "Grup", "Halk"),

	// from here all new by mic
	'miscowner'			=> 'Sahip',
	'miscownerdesc'		=> '<strong>Açıklama:</strong><br />User (UID) /<br />Group (GID)<br />Current rights:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorer Sistem Bilgisi",
	'sisysteminfo'		=> 'Sistem Bilgisi',
	'sibuilton'			=> 'İşletim Sistemi',
	'sidbversion'		=> 'Veritabanı Sürümü (MySQL)',
	'siphpversion'		=> 'PHP Sürümü',
	'siphpupdate'		=> 'Bilgi: <span style="color: red;">The PHP version you use is <strong>not</strong> actual!</span><br />To guarantee all functions and features of '.$_VERSION->PRODUCT.' and addons,<br />you should use as minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Interface',
	'simamboversion'	=> 'eXtplorer Sürümü',
	'siuseragent'		=> 'Tarayıcı Sürümü',
	'sirelevantsettings' => 'Önemli PHP Ayarları',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Hataları',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'File Uploads',
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
	'siphpinfo'			=> 'PHP Bilgisi',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'İzinler',
	'sidirperms'		=> 'Dizin izinleri',
	'sidirpermsmess'	=> 'To be shure that all functions and features of '.$_VERSION->PRODUCT.' are working correct, following folders should have permission to write [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Gerçekten bu dosyayı çıkartmak istiyormusunuz? Buraya?\\nThis will overwrite existing files when not used carefully!",
	'extract_success' => "Extraction was successful",
	'extract_failure' => "Extraction failed",
	
	'overwrite_files' => 'Varolan dosyanın üzerine yaz?',
	"viewlink"		=> "Göster",
	"actview"		=> "Kaynak dosyasını göster",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Alt klasörlerede uygula?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Son sürümü kontrol et',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Dizini yada dosyayı yenidien adlandır...',
	'newname'		=>	'Yeni İsim',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Kaydettikten sonra dizine geri dön?',
	'line'		=> 	'Line',
	'column'	=>	'Column',
	'wordwrap'	=>	'Wordwrap: (IE only)',
	'copyfile'	=>	'Copy file into this filename',
	
	// Bookmarks
	'quick_jump' => 'Hızlı Atlama',
	'already_bookmarked' => 'Bu dizin zaten yer imlerinde',
	'bookmark_was_added' => 'Bu dizin yer imleri listesine eklendi.',
	'not_a_bookmark' => 'Bu dizin yer imlerinde değil.',
	'bookmark_was_removed' => 'Bu dizin yer imleri listesinden çıkartıldı.',
	'bookmarkfile_not_writable' => "Failed to %s the bookmark.\n The Bookmark File '%s' \nis not writable.",
	
	'lbl_add_bookmark' => 'Bu dizini yerimlerine ekle',
	'lbl_remove_bookmark' => 'Bu dizini yer imleri listesinden çıkart',
	
	'enter_alias_name' => 'Lütfen bu yer imi için takma ad girin',
	
	'normal_compression' => 'normal sıkıştırma',
	'good_compression' => 'iyi sıkıştırma',
	'best_compression' => 'en iyi sıkıştırma',
	'no_compression' => 'sıkıştırma yok',
	
	'creating_archive' => 'Arşiv dosyası oluştur...',
	'processed_x_files' => 'Processed %s of %s Files',
	
	'ftp_header' => 'Local FTP Authentication',
	'ftp_login_lbl' => 'Please enter the login credentials for the FTP server',
	'ftp_login_name' => 'FTP Kullanıcı Adı',
	'ftp_login_pass' => 'FTP Şifresi',
	'ftp_hostname_port' => 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check' => 'FTP bağlantısı Kontrol ediliyor...',
	'ftp_connection_failed' => "FTP servera bağlanılamadı. \nPlease check that the FTP server is running on your server.",
	'ftp_login_failed' => "FTP girişinde hata. Lütfen kullanıcı adı ve şifrenizi kontrol ederek tekrar deneyin.",
		
	'switch_file_mode' => 'Şimdiki mod: <strong>%s</strong>. Dilerseniz %s moduna geçebilirsiniz.',
	'symlink_target' => 'Target of the Symbolic Link',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "Dosya kaydedildi.",
	"moveitem"		=> "Taşıma Tamamlandı.",
	"copyitem"		=> "Kopyalama Tamamlandı.",
	'archive_name' 	=> 'Arşiv dosyası ismi',
	'archive_saveToDir' 	=> 'Arşivi bu dizine kaydet',
	
	'editor_simple'	=> 'Basit Editör Modu',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'	=> 'Yeni Dosya/Dizin',
	'show_directories' => 'Dizinleri Göster',
	'actlogin_success' => 'Giriş başarılı!',
	'actlogin_failure' => 'Giriş hatalı, tekrar deneyin.',
	'directory_tree' => 'Dizin Ağacı',
	'browsing_directory' => 'Dizinlere Gözat',
	'filter_grid' => 'Filtrele',
	'paging_page' => 'Sayfa',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => 'İlk Sayfa',
	'paging_lastpage' => 'Son Sayfa',
	'paging_nextpage' => 'Sonraki Sayfa',
	'paging_prevpage' => 'Önceki Sayfa',
	
	'paging_info' => 'Gösterilen Öğeler {0} - {1} of {2}',
	'paging_noitems' => 'Gösterilecek öğe yok',
	'aboutlink' => 'Hakkında...',
	'password_warning_title' => 'Important - Change your Password!',
	'password_warning_text' => 'The user account you are logged in with (admin with password admin) corresponds to the default eXtplorer priviliged account. Your eXtplorer installation is open to intrusion and you should immediately fix this security hole!',
	'change_password_success' => 'Your Password has been changed!',
	'success' => 'Basarili',
	'failure' => 'Hata',
	'dialog_title' => 'Website Dialog',
	'upload_processing' => 'Yükleniyor, lütfen bekleyin...',
	'upload_completed' => 'Yükleme Başarılı!',
	'acttransfer' => 'Başka Sunucudan Transfer Et',
	'transfer_processing' => 'Sunucudan sunucuya transfer sürüyor, lütfen bekleyin...',
	'transfer_completed' => 'Transfer tamamlandı!',
	'max_file_size' => 'Maksimum Dosya Boyutu',
	'max_post_size' => 'Maksimum Yükleme Sınırı',
	'done' => 'Tamam.',
	'permissions_processing' => 'İzinler uygulanıyor, lütfen bekleyin...',
	'archive_created' => 'The Archive File has been created!',
	'save_processing' => 'Saving File...',
	'current_user' => 'This script currently runs with the permissions of the following user:',
	'your_version' => 'Sizin Sürümünüz',
	'search_processing' => 'Aranıyor, lütfen bekleyin...',
	'url_to_file' => 'Dosya adresi',
	'file' => 'Dosya'

);
?>
