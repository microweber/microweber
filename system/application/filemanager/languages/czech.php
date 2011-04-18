<?php
// $Id: czech.php 115 2009-12-15 09:46:58 GMT soeren $
// Czech Language Module for v2.3 (translated by Raskolnikoff)
global $_verze;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Chyba",
	"message"			=> "Zpr&#225;va",
	"back"			=> "Zp&#283;t",

	// root
	"home"			=> "Home slo&#382;ka neexistuje zkontrolujte nastaven&#237;.",
	"abovehome"		=> "Aktu&#225;ln&#237; slo&#382;ka nen&#237; nad&#345;azen&#225; home slo&#382;kou.",
	"targetabovehome"	=> "C&#237;lov&#225; slo&#382;ka nen&#237; nad&#345;azen&#225; home slo&#382;ce.",

	// exist
	"direxist"		=> "Tato slo&#382;ka neexistuje.",
	//"filedoesexist"	=> "Tento soubor ji&#382; existuje.",
	"fileexist"		=> "Tento soubor neexistuje.",
	"itemdoesexist"		=> "Tato polo&#382;ka ji&#382; existuje.",
	"itemexist"		=> "Tato polo&#382;ka ji&#382; existuje.",
	"targetexist"		=> "C&#237;lov&#225; slo&#382;ka neexistuje.",
	"targetdoesexist"	=> "C&#237;lov&#225; polo&#382;ka ji&#382; existuje.",

	// open
	"opendir"		=> "Nen&#237; mo&#382;n&#233; otev&#345;&#237;t slo&#382;ku.",
	"readdir"		=> "Nen&#237; mo&#382;n&#233; &#269;&#237;st slo&#382;ku.",

	// access
	"accessdir"		=> "Nem&#225;te opr&#225;vn&#283;n&#237; k t&#233;to slo&#382;ce.",
	"accessfile"		=> "Nem&#225;te opr&#225;vn&#283;n&#237; p&#345;istupovat k tomuto souboru.",
	"accessitem"		=> "Nem&#225;te opr&#225;vn&#283;n&#237; pro tuto polo&#382;ku.",
	"accessfunc"		=> "Nem&#225;te opr&#225;vn&#283;n&#237; pou&#382;&#237;vat tuto funkci.",
	"accesstarget"		=> "Nem&#225;te opr&#225;vn&#283;n&#237; the target directory.",

	// actions
	"permread"		=> "Nastaven&#237; p&#345;&#237;stup&#367; selhalo.",
	"permchange"		=> "CHMOD selh&#225;n&#237;",
	"openfile"		=> "Otev&#237;r&#225;n&#237; dokumentu selhalo.",
	"savefile"		=> "Ukl&#225;d&#225;n&#237; souboru selhalo.",
	"createfile"		=> "Vytvo&#345;en&#237; dokumentu selhalo.",
	"createdir"		=> "Vytvo&#345;en&#237; slo&#382;ky selhalo.",
	"uploadfile"		=> "Nahr&#225;n&#237; souboru selhalo.",
	"copyitem"		=> "Kop&#237;rov&#225;n&#237; selhalo.",
	"moveitem"		=> "P&#345;esunut&#237; selhalo.",
	"delitem"		=> "Maz&#225;n&#237; selhalo.",
	"chpass"		=> "Zm&#283;na hesla selhala.",
	"deluser"		=> "Odstran&#283;n&#237; u&#382;ivatele selhalo.",
	"adduser"		=> "P&#345;id&#225;n&#237; u&#382;ivatele selhalo.",
	"saveuser"		=> "Ulo&#382;en&#237;  u&#382;ivatele selhalo.",
	"searchnothing"		=> "Mus&#237;te vlo&#382;it vyhled&#225;vac&#237; slovo.",

	// misc
	"miscnofunc"		=> "Funkce nedostupn&#225;.",
	"miscfilesize"		=> "Soubor p&#345;es&#225;hl maxim&#225;ln&#237; povolenou velikost.",
	"miscfilepart"		=> "Soubor byl nahr&#225;n pouze &#269;&#225;ste&#269;n&#283;.",
	"miscnoname"		=> "Mus&#237;te vlo&#382;it jm&#233;no.",
	"miscselitems"		=> "Nezvolili jste &#382;&#225;dn&#233; polo&#382;ky.",
	"miscdelitems"		=> "Jste si jisti, &#382;e chcete smazat {0} polo&#382;ek?",
	"miscdeluser"		=> "Jste si jisti, &#382;e chcete snazat u&#382;ivatele '{0}'?",
	"miscnopassdiff"	=> "Nov&#233; heslo se neli&#353;&#237; od p&#367;vodn&#237;ho.",
	"miscnopassmatch"	=> "Hesla se neshoduj&#237;.",
	"miscfieldmissed"	=> "Nevyplnili jste povinn&#233; pole.",
	"miscnouserpass"	=> "Neplatn&#233; u&#382;ivatelsk&#233; jm&#233;no a heslo.",
	"miscselfremove"	=> "Nem&#367;&#382;ete odstranit sebe.",
	"miscuserexist"		=> "U&#382;ivatel ji&#382; existuje.",
	"miscnofinduser"	=> "U&#382;ivatel nenalezen.",
	"extract_noarchive" => "Soubor je neextraktovateln&#253;m archivem.",
	"extract_unknowntype" => "Nezn&#225;m&#253; typ archivu",
	
	'chmod_none_not_allowed' => 'Zm&#283;na p&#345;&#237;stup&#367; na <none> nen&#237; povoleno',
	'archive_dir_notexists' => 'Ukl&#225;d&#225;n&#237; do souboru, kter&#253; neexistuje nen&#237; mo&#382;n&#233;.',
	'archive_dir_unwritable' => 'Zvolte slo&#382;ku do kter&#233; se m&#225; soubor ulo&#382;it.',
	'archive_creation_selhalo' => 'Selhalo ukl&#225;d&#225;n&#237; archivu'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Zm&#283;nit opr&#225;vn&#283;n&#237;",
	"editlink"		=> "Editovat",
	"downlink"		=> "St&#225;hnout",
	"uplink"		=> "Nahoru",
	"homelink"		=> "Home",
	"reloadlink"		=> "Aktualizovat",
	"copylink"		=> "Kop&#237;rovat",
	"movelink"		=> "P&#345;esunout",
	"dellink"		=> "Smazat",
	"comprlink"		=> "Archiv",
	"adminlink"		=> "Administr&#225;tor",
	"logoutlink"		=> "Odhl&#225;sit",
	"uploadlink"		=> "Nahr&#225;t",
	"searchlink"		=> "Hledat",
	"extractlink"	=> "Rozbalit archiv",
	'chmodlink'		=> 'Zm&#283;nit (chmod) opr&#225;vn&#283;n&#237; (Slo&#382;ka/Soubor(y))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Nav&#353;t&#237;vit eXtplorer str&#225;nky (nov&#233; okno)', // new mic

	// list
	"nameheader"		=> "Jm&#233;no",
	"sizeheader"		=> "Velikost",
	"typeheader"		=> "Typ",
	"modifheader"		=> "Modifikov&#225;no",
	"permheader"		=> "Opr&#225;vn&#283;n&#237;",
	"actionheader"		=> "Akce",
	"pathheader"		=> "Cesta",

	// buttons
	"btncancel"		=> "Zru&#353;it",
	"btnsave"		=> "Ulo&#382;it",
	"btnchange"		=> "Zm&#283;nit",
	"btnreset"		=> "Resetovat",
	"btnclose"		=> "Zav&#345;&#237;t",
	"btncreate"		=> "Vytvo&#345;it",
	"btnsearch"		=> "Hledat",
	"btnupload"		=> "Nahr&#225;t",
	"btncopy"		=> "Kop&#237;rovat",
	"btnmove"		=> "P&#345;esunout",
	"btnlogin"		=> "P&#345;ihl&#225;sit",
	"btnlogout"		=> "Odhl&#225;sit",
	"btnadd"		=> "P&#345;idat",
	"btnedit"		=> "Editovat",
	"btnremove"		=> "Odstranit",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'P&#345;ejmenovat',
	'confirm_delete_file' => 'Jste si jisti, &#382;e chcete smazat tento soubor <br />%s',
	'success_delete_file' => 'Polo&#382;ka úsp&#283;&#353;n&#283; smaz&#225;na',
	'success_rename_file' => 'Slo&#382;ka/soubor úsp&#283;&#353;n&#283; p&#345;ejmenov&#225;na %s.',
	
	// actions
	"actdir"		=> "Slo&#382;ka",
	"actperms"		=> "Zm&#283;nit opr&#225;vn&#283;n&#237;",
	"actedit"		=> "Editovat soubor",
	"actsearchresults"	=> "V&#253;sledky hled&#225;n&#237;",
	"actcopyitems"		=> "Kop&#237;rovat polo&#382;ky",
	"actcopyfrom"		=> "Kop&#237;rovat z /%s do /%s ",
	"actmoveitems"		=> "P&#345;esunout",
	"actmovefrom"		=> "P&#345;esunout z /%s do /%s ",
	"actlogin"		=> "P&#345;ihl&#225;sit",
	"actloginheader"	=> "P&#345;ihl&#225;sit",
	"actadmin"		=> "Administrace",
	"actchpwd"		=> "Zm&#283;nit heslo",
	"actusers"		=> "U&#382;ivatel&#233;",
	"actarchive"		=> "Archivy",
	"actupload"		=> "Nahr&#225;t",

	// misc
	"miscitems"		=> "Polo&#382;ky",
	"miscfree"		=> "Free",
	"miscusername"		=> "U&#382;ivatel",
	"miscpassword"		=> "Heslo",
	"miscoldpass"		=> "P&#367;vodn&#237; heslo",
	"miscnewpass"		=> "Nov&#233; heslo",
	"miscconfpass"		=> "Potvrdit heslo",
	"miscconfnewpass"	=> "Potvrdit nov&#233; heslo",
	"miscchpass"		=> "Zm&#283;nit heslo",
	"mischomedir"		=> "Home slo&#382;ka",
	"mischomeurl"		=> "Home URL",
	"miscshowhidden"	=> "Uk&#225;zat skryt&#233; soubory",
	"mischidepattern"	=> "Schovat cestu",
	"miscperms"		=> "Opr&#225;vn&#283;n&#237;",
	"miscuseritems"		=> "(jm&#233;no, home slo&#382;ka, uk&#225;zat skryt&#233; soubory, opr&#225;vn&#283;n&#237;, aktivn&#237;)",
	"miscadduser"		=> "p&#345;idat u&#382;ivatele",
	"miscedituser"		=> "editovat u&#382;ivatele '%s'",
	"miscactive"		=> "Aktivn&#237;",
	"misclang"		=> "Jazyk",
	"miscnoresult"		=> "&#382;&#225;dn&#233; v&#253;sledky.",
	"miscsubdirs"		=> "Hledat v podslo&#382;k&#225;ch",
	"miscpermnames"		=> array("Pouze &#269;&#237;st","M&#283;nit","Zm&#283;na hesla","Zm&#283;nit heslo",
					"Administr&#225;tor"),
	"miscyesno"		=> array("Ano","Ne","Y","N"),
	"miscchmod"		=> array("Vlatn&#237;k", "Skupina", "Ve&#345;ejn&#253;"),

	// from here all new by mic
	'miscowner'			=> 'Vlatn&#237;k',
	'miscownerdesc'		=> '<strong>Popis:</strong><br />U&#382;ivatel (UID) /<br />Skupina (GID)<br />Aktu&#225;ln&#237; opr&#225;vn&#283;n&#237;:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "Informace o syst&#233;mu",
	'sisysteminfo'		=> 'System Info',
	'sibuilton'			=> 'Opera&#269;n&#237; syst&#233;m',
	'sidbverze'		=> 'Datab&#225;ze (MySQL)',
	'siphpverze'		=> 'PHP verze',
	'siphpupdate'		=> 'INFORMACE: <span style="color: red;">Verze php, kterou pou&#382;&#237;v&#225;te <strong>not</strong> aktu&#225;ln&#237;!</span><br />To guarantee all functions and features of Mambo and addons,<br />you should use as minimum <strong>PHP.verze 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP rozhran&#237;',
	'simamboverze'	=> 'eXtplorer verze',
	'siuseragent'		=> 'Prohl&#237;&#382;e&#269; verze',
	'sirelevantsettings' => 'D&#367;le&#382;it&#233; PHP nastaven&#237;',
	'sisafemode'		=> 'Nouzov&#253; re&#382;im',
	'sibasedir'			=> 'Otev&#345;&#237;t z&#225;kladn&#237; slo&#382;ku',
	'sidisplayerrors'	=> 'PHP chyby',
	'sishortopentags'	=> 'Kratk&#233; tagy',
	'sifileuploads'		=> 'Nahr&#225;n&#237; souboru',
	'simagicquotes'		=> 'Kouzeln&#225; formule',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Ukl&#225;dac&#237; cesta sezen&#237;',
	'sisessautostart'	=> 'Auto start sezen&#237;',
	'sixmlenabled'		=> 'XML povoleno',
	'sizlibenabled'		=> 'ZLIB povoleno',
	'sidisabledfuncs'	=> 'Zak&#225;zan&#233; funkce',
	'sieditor'			=> 'WYSIWYG editor',
	'siconfigfile'		=> 'Konfigura&#269;n&#237; soubor',
	'siphpinfo'			=> 'PHP info',
	'siphpinformation'	=> 'PHP informace',
	'sipermissions'		=> 'Opr&#225;vn&#283;n&#237;',
	'sidirperms'		=> 'Opr&#225;vn&#283;n&#237; slo&#382;ky',
	'sidirpermsmess'	=> 'Aby aplikace fungovala tak jak m&#225; mus&#237; b&#253;t opr&#225;vn&#283;n&#237; uvedebn&#253;ch soubor&#367; nastaveny na zapisovateln&#233; [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Skute&#269;n&#283; chcete rozbalit tento soubor? Zde?<br />Toto p&#345;ep&#237;&#353;e v&#353;echny soubory!",
	'extract_success' => "Soubor byl úsp&#283;&#353;n&#283; rozbalen",
	'extract_failure' => "Extrakce selhalo",
	
	'overwrite_files' => 'P&#345;epsat existuj&#237;c&#237; soubory?',
	"viewlink"		=> "N&#225;hled",
	"actview"		=> "N&#225;hled na zdroj souboru",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Pou&#382;&#237;t na v&#353;echny soubory a slo&#382;ky?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_verze'	=> 'Zkontrolovat posledn&#237; verzi',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'P&#345;ejmenovat soubory a slo&#382;ky...',
	'newname'		=>	'Nov&#253; n&#225;zev',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Zp&#283;t do slo&#382;ky po editaci?',
	'line'		=> 	'&#344;&#225;dek',
	'column'	=>	'Sloupec',
	'wordwrap'	=>	'Wordwrap: (IE only)',
	'copyfile'	=>	'Kop&#237;rovat soubor do tohoto adres&#225;&#345;e',
	
	// Bookmarks
	'quick_jump' => 'Rychl&#253; skok na',
	'already_bookmarked' => 'Tento adres&#225;&#345; je ji&#382; p&#345;id&#225;n mezi obl&#237;ben&#233;',
	'bookmark_was_added' => 'Adres&#225;&#345; byl p&#345;id&#225;n mezi obl&#237;ben&#233;.',
	'not_a_bookmark' => 'Adres&#225;&#345; nen&#237; z&#225;lo&#382;ka.',
	'bookmark_was_removed' => 'Adres&#225;&#345; byl odstran&#283;n z obl&#237;ben&#253;ch.',
	'bookmarkfile_not_writable' => "Selhalo do %s obl&#237;ben&#253;ch.Soubor z&#225;lo&#382;ek '%s' nen&#237; zapisovateln&#253;.",
	
	'lbl_add_bookmark' => 'P&#345;idat adres&#225;&#345; jako z&#225;lo&#382;ku',
	'lbl_remove_bookmark' => 'Odstranit adres&#225;&#345; jako z&#225;lo&#382;ku',
	
	'enter_alias_name' => 'Vlo&#382;te jm&#233;no pro tuto z&#225;lo&#382;ku',
	
	'normal_compression' => 'norm&#225;ln&#237; komprese',
	'good_compression' => 'dobr&#225; komprese',
	'best_compression' => 'nejlep&#353;&#237; komprese',
	'no_compression' => 'bez komprese',
	
	'creating_archive' => 'Vytv&#225;&#345;&#237;m soubor archivu...',
	'processed_x_files' => 'Zpracov&#225;no %s z %s soubor&#367;',
	
	'ftp_header' => 'FTP autorizace',
	'ftp_login_lbl' => 'Vlo&#382;te p&#345;ihla&#353;ovac&#237; údaje pro FTP server',
	'ftp_login_name' => 'FTP U&#382;iatel',
	'ftp_login_pass' => 'FTP Heslo',
	'ftp_hostname_port' => 'FTP Server hostname a port <br />(port nen&#237; povinn&#253;)',
	'ftp_login_check' => 'Kontroluji FTP spojen&#237;...',
	'ftp_connection_selhalo' => "FTP server nem&#367;&#382;e b&#253;t kontaktov&#225;n. \nZkontrolujte zda je server aktivn&#237;.",
	'ftp_login_selhalo' => "FTP p&#345;ihl&#225;&#353;en&#237; selhalo. Zkontrolujte u&#382;ivatelsk&#233; jm&#233;no a heslo a zkuste znova.",
		
	'switch_file_mode' => 'Aktu&#225;ln&#237; m&#243;d: <strong>%s</strong>. M&#367;&#382;ete se p&#345;epnout do %s m&#243;du.',
	'symlink_target' => 'C&#237;l symbolick&#233;ho odkazu',
	
	"permchange"		=> "CHMOD úsp&#283;ch:",
	"savefile"		=> "Soubor byl ulo&#382;en.",
	"moveitem"		=> "P&#345;esunut&#237; dokon&#269;eno.",
	"copyitem"		=> "Kop&#237;rov&#225;n&#237; dokon&#269;eno.",
	'archive_name' 	=> 'Jm&#233;no souboru archivu',
	'archive_saveToDir' 	=> 'Ulo&#382;it archiv do tohoto adres&#225;&#345;e',
	
	'editor_simple'	=> 'M&#243;d jednoduch&#233;ho editoru',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted m&#243;d',

	'newlink'	=> 'Nov&#253; soubor/adres&#225;&#345;',
	'show_directories' => 'Uk&#225;zat adres&#225;&#345;e',
	'actlogin_success' => 'Úsp&#283;&#353;n&#233; p&#345;ihl&#225;&#353;en&#237;!',
	'actlogin_failure' => 'P&#345;ihl&#225;&#353;en&#237; selhalo, zkuste znova.',
	'directory_tree' => 'Struktura',
	'browsing_directory' => 'Proch&#225;zet slo&#382;ky',
	'filter_grid' => 'Filtr',
	'paging_page' => 'Str&#225;nka',
	'paging_of_X' => 'z {0}',
	'paging_firstpage' => 'Prvn&#237; str&#225;nka',
	'paging_lastpage' => 'Posledn&#237; str&#225;nka',
	'paging_nextpage' => 'Dal&#353;&#237; str&#225;nka',
	'paging_prevpage' => 'P&#345;edchoz&#237; str&#225;nka',
	
	'paging_info' => 'Ukazuji polo&#382;ky {0} - {1} z {2}',
	'paging_noitems' => '&#382;&#225;dn&#233; polo&#382;ky',
	'aboutlink' => 'O...',
	'password_warning_title' => 'D&#367;le&#382;it&#233; - zm&#283;&#328;te svoje heslo!',
	'password_warning_text' => 'U&#382;ivatelsk&#253; ú&#269;et se kter&#253;m jste p&#345;ihl&#225;&#353;eni (admin s heslem admin) odpov&#237;d&#225; defaultn&#237;mu nastaven&#237; aplikace. Va&#353;e instalace je tak vlmi zraniteln&#225;!',
	'change_password_success' => 'Va&#353;e heslo bylo zm&#283;n&#283;no!',
	'success' => 'Usp&#283;ch',
	'failure' => 'Selh&#225;n&#237;',
	'dialog_title' => 'Website dialog',
	'upload_processing' => 'Nahr&#225;v&#225;n&#237;, pros&#237;m &#269;ekejte...',
	'upload_completed' => 'Nahr&#225;n&#237; prob&#283;hlo OK!',
	'acttransfer' => 'Transfer z jin&#233;ho serveru',
	'transfer_processing' => 'Prov&#225;d&#237;m Server-Server transfer, pros&#237;m &#269;ekejte...',
	'transfer_completed' => 'Transfer dokon&#269;en!',
	'max_file_size' => 'Max velikost souboru',
	'max_post_size' => 'Max velikost nahr&#225;n&#237;',
	'done' => 'Dokon&#269;eno.',
	'permissions_processing' => 'Upravuji opr&#225;vn&#283;n&#237;, pros&#237;m &#269;ekejte...',
	'archive_created' => 'Soubor archivu byl vytvo&#345;en!',
	'save_processing' => 'Ukl&#225;d&#225;m soubor...',
	'current_user' => 'Tato aplikace moment&#225;ln&#283; b&#283;&#382;&#225;&#237; pod t&#237;mto u&#382;ivatelsk&#253;m opr&#225;vn&#283;n&#237;m:',
	'your_verze' => 'Va&#353;e verze',
	'search_processing' => 'Hled&#225;m, &#269;ekejte pros&#237;m...',
	'url_to_file' => 'URL souboru',
	'file' => 'Soubor'
	
);
?>