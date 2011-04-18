<?php

// Hungarian Language Module for v2.3 (translated by Jozsef Tamas Herczeg a.k.a LocaLiceR www.joomlandia.eu)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y.m.d. H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Hibá(k)",
	"message"			=> "Információ",
	"back"			=> "Vissza",
	
	// root
	"home"			=> "A gyökérkönyvtár nem létezik, ellenőrizze a beállításokat.",
	"abovehome"		=> "A jelenlegi könyvtár nem lehet följebb a gyökérkönyvtárnál.",
	"targetabovehome"	=> "A célkönyvtár nem lehet följebb a gyökérkönyvtárnál.",
	
	// exist
	"direxist"		=> "Ez a könyvtár nem létezik.",
	//"filedoesexist"	=> "Ez a fájl már létezik.",
	"fileexist"		=> "Ez a fájl nem létezik.",
	"itemdoesexist"		=> "Ez az elem már létezik.",
	"itemexist"		=> "Ez az elem nem létezik.",
	"targetexist"		=> "A célkönyvtár nem létezik.",
	"targetdoesexist"	=> "A célelem már létezik.",
	
	// open
	"opendir"		=> "Nem nyitható meg a könyvtár.",
	"readdir"		=> "Nem olvasható a könyvtár.",
	
	// access
	"accessdir"		=> "Nem engedélyezett az Ön számára az ehhez a könyvtárhoz való hozzáférés.",
	"accessfile"		=> "Nem engedélyezett az Ön számára az ehhez a fájlhoz való hozzáférés.",
	"accessitem"		=> "Nem engedélyezett az Ön számára az ehhez az elemhez való hozzáférés.",
	"accessfunc"		=> "Ennek a funkciónak a használata nem engedélyezett az Ön számára.",
	"accesstarget"		=> "Nem engedélyezett a célkönyvtárhoz való hozzáférés.",
	
	// actions
	"permread"		=> "Az engedélyek lekérése nem sikerült.",
	"permchange"		=> "Az engedélymódosítás nem sikerült.",
	"openfile"		=> "Nem lehet megnyitni a fájlt.",
	"savefile"		=> "Nem lehet menteni a fájlt.",
	"createfile"		=> "Nem lehet létrehozni a fájlt.",
	"createdir"		=> "Nem lehet létrehozni a könyvtárt.",
	"uploadfile"		=> "A fájl feltöltése nem sikerült.",
	"copyitem"		=> "A másolás nem sikerült.",
	"moveitem"		=> "Az áthelyezés nem sikerült.",
	"delitem"		=> "A törlés nem sikerült.",
	"chpass"		=> "Nem sikerült megváltoztatni a jelszót.",
	"deluser"		=> "A felhasználó eltávolítása nem sikerült.",
	"adduser"		=> "A felhasználó hozzáadása nem sikerült.",
	"saveuser"		=> "A felhasználó mentése nem sikerült.",
	"searchnothing"		=> "Meg kell adnia a keresendő kulcsszót.",
	
	// misc
	"miscnofunc"		=> "Ez a funkció nem működik.",
	"miscfilesize"		=> "A fájl mérete nagyobb a megengedettnél.",
	"miscfilepart"		=> "Csak részben sikerült feltölteni a fájlt.",
	"miscnoname"		=> "Meg kell adnia egy nevet.",
	"miscselitems"		=> "Nem választott ki egy elemet sem.",
	"miscdelitems"		=> "Biztosan törölni akarja ezt a(z) {0} elemet?",
	"miscdeluser"		=> "Biztosan törölni akarja a következő felhasználót: '{0}'?",
	"miscnopassdiff"	=> "Az új jelszó ugyanaz, mint a jelenlegi.",
	"miscnopassmatch"	=> "Eltérőek a jelszavak.",
	"miscfieldmissed"	=> "Kihagyott egy fontos mezőt.",
	"miscnouserpass"	=> "Érvénytelen a felhasználónév vagy a jelszó.",
	"miscselfremove"	=> "Saját magát nem távolíthatja el.",
	"miscuserexist"		=> "A felhasználó már létezik.",
	"miscnofinduser"	=> "Nem található a felhasználó.",
	"extract_noarchive" => "A fájl nem kibontható archívum.",
	"extract_unknowntype" => "Ismeretlen típusú archívum",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Engedélyek módosítása",
	"editlink"		=> "Szerkesztés",
	"downlink"		=> "Letöltés",
	"uplink"		=> "Fel",
	"homelink"		=> "Gyökér",
	"reloadlink"		=> "Frissítés",
	"copylink"		=> "Másolás",
	"movelink"		=> "Áthelyezés",
	"dellink"		=> "Törlés",
	"comprlink"		=> "Archiválás",
	"adminlink"		=> "Admin",
	"logoutlink"		=> "Kilépés",
	"uploadlink"		=> "Feltöltés",
	"searchlink"		=> "Keresés",
	"extractlink"	=> "Archívum kibontása",
	'chmodlink'		=> 'Engedélyek módosítása (chmod) (Könyvtár/Fájl(ok))', // new mic
	'mossysinfolink'	=> $_VERSION->PRODUCT.' Rendszerinformáció ('.$_VERSION->PRODUCT.', Kiszolgáló, PHP, mySQL)', // new mic
	'logolink'		=> 'Ugrás a joomlaXplorer webhelyére (új ablak)', // new mic
	
	// list
	"nameheader"		=> "Név",
	"sizeheader"		=> "Méret",
	"typeheader"		=> "Típus",
	"modifheader"		=> "Módosítva",
	"permheader"		=> "Engedélyek",
	"actionheader"		=> "Műveletek",
	"pathheader"		=> "Útvonal",
	
	// buttons
	"btncancel"		=> "Mégse",
	"btnsave"		=> "Mentés",
	"btnchange"		=> "Módosítás",
	"btnreset"		=> "Alaphelyzet",
	"btnclose"		=> "Bezárás",
	"btncreate"		=> "Létrehozás",
	"btnsearch"		=> "Keresés",
	"btnupload"		=> "Feltöltés",
	"btncopy"		=> "Másolás",
	"btnmove"		=> "Áthelyezés",
	"btnlogin"		=> "Bejelentkezés",
	"btnlogout"		=> "Kijelentkezés",
	"btnadd"		=> "Hozzáadás",
	"btnedit"		=> "Szerkesztés",
	"btnremove"		=> "Eltávolítás",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Átnevezés',
	'confirm_delete_file' => 'Biztosan törölni akarja ezt a fájlt? \\n%s',
	'success_delete_file' => 'Az elem(ek) törlése sikerült.',
	'success_rename_file' => 'A(z) %s könyvtár/fájl átnevezése %s névre sikerült.',
	
	// actions
	"actdir"		=> "Könyvtár",
	"actperms"		=> "Engedélyek módosítása",
	"actedit"		=> "Fájl szerkesztése",
	"actsearchresults"	=> "A keresés eredménye",
	"actcopyitems"		=> "Elem(ek) másolása",
	"actcopyfrom"		=> "Másolás a(z) /%s könyvtárból a(z) /%s könyvtárba ",
	"actmoveitems"		=> "Elem(ek) áthelyezése",
	"actmovefrom"		=> "Áthelyezés a(z) /%s könyvtárból a(z) /%s könyvtárba ",
	"actlogin"		=> "Bejelentkezés",
	"actloginheader"	=> "Bejelentkezés a QuiXplorer használatára",
	"actadmin"		=> "Adminisztrálás",
	"actchpwd"		=> "A jelszó megváltoztatása",
	"actusers"		=> "Felhasználók",
	"actarchive"		=> "Elem(ek) archiválása",
	"actupload"		=> "Fájl(ok) feltöltése",
	
	// misc
	"miscitems"		=> "elem",
	"miscfree"		=> "Szabad terület",
	"miscusername"		=> "Felhasználónév",
	"miscpassword"		=> "Jelszó",
	"miscoldpass"		=> "A régi jelszó",
	"miscnewpass"		=> "Az új jelszó",
	"miscconfpass"		=> "A jelszó megerősítése",
	"miscconfnewpass"	=> "Az új jelszó megerősítése",
	"miscchpass"		=> "Jelszócsere",
	"mischomedir"		=> "Gyökérkönyvtár",
	"mischomeurl"		=> "Kezdő URL",
	"miscshowhidden"	=> "A rejtett elemek láthatók",
	"mischidepattern"	=> "Minta elrejtése",
	"miscperms"		=> "Engedélyek",
	"miscuseritems"		=> "(név, gyökérkönyvtár, rejtett elemek megjelenítése, engedélyek, aktív)",
	"miscadduser"		=> "új felhasználó",
	"miscedituser"		=> "'%s' felhasználó módosítása",
	"miscactive"		=> "Aktív",
	"misclang"		=> "Nyelv",
	"miscnoresult"		=> "A keresés eredménytelen.",
	"miscsubdirs"		=> "Keresés az alkönyvtárakban",
	"miscpermnames"		=> array("Csak nézet","Módosítás","Jelszócsere","Módosítás és jelszócsere",
					"Adminisztrátor"),
	"miscyesno"		=> array("Igen","Nem","I","N"),
	"miscchmod"		=> array("Tulajdonos", "Csoport", "Mindenki"),

	// from here all new by mic
	'miscowner'			=> 'Tulajdonos',
	'miscownerdesc'		=> '<strong>Leírás:</strong><br />Felhasználó (UID) /<br />Csoport (GID)<br />Jelenlegi engedélyek:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> $_VERSION->PRODUCT." rendszerinformáció",
	'sisysteminfo'		=> 'Rendszer',
	'sibuilton'			=> 'Operációs rendszer',
	'sidbversion'		=> 'Adatbázis verziószáma (MySQL)',
	'siphpversion'		=> 'PHP verziószáma',
	'siphpupdate'		=> 'INFORMÁCIÓ: <span style="color: red;">Az Ön által használt PHP verzió <strong>elavult</strong>!</span><br />A Mambo és kiegészítői valamennyi funkcióinak és szolgáltatásainak biztosításához<br />legalább <strong>PHP 4.3-as verziót</strong> kell használnia!',
	'siwebserver'		=> 'Webkiszolgáló',
	'siwebsphpif'		=> 'Webkiszolgáló - PHP felület',
	'simamboversion'	=> $_VERSION->PRODUCT.' verziószáma',
	'siuseragent'		=> 'Böngésző verziószáma',
	'sirelevantsettings' => 'Fontos PHP beállítások',
	'sisafemode'		=> 'Biztonságos mód',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP hibák',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Fájlfeltöltés',
	'simagicquotes'		=> 'Mágikus idézőjelek',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Kimeneti puffer',
	'sisesssavepath'	=> 'Munkamenet mentési útvonal',
	'sisessautostart'	=> 'Munkamenet automatikus indítása',
	'sixmlenabled'		=> 'XML engedélyezett',
	'sizlibenabled'		=> 'ZLIB engedélyezett',
	'sidisabledfuncs'	=> 'Nem engedélyezett funkciók',
	'sieditor'			=> 'WYSIWYG szerkesztő',
	'siconfigfile'		=> 'Konfigurációs fájl',
	'siphpinfo'			=> 'PHP',
	'siphpinformation'	=> 'PHP tulajdonságai',
	'sipermissions'		=> 'Engedélyek',
	'sidirperms'		=> 'Könyvtárengedélyek',
	'sidirpermsmess'	=> 'Ha azt akarja, hogy a '.$_VERSION->PRODUCT.' összes funkciója és szolgáltatása megfelelően működjön, akkor írhatóvá kell tennie a következő könyvtárakat [chmod 0777]',
	'sionoff'			=> array( 'Be', 'Ki' ),
	
	'extract_warning' => "Valóban ki akarja bontani ezt a fájlt? Ide?\\nFelül fogja írni a létező fájlokat, ha nem körültekintően használja!",
	'extract_success' => "A kibontás sikerült",
	'extract_failure' => "A kibontás nem sikerült",
	
	'overwrite_files' => 'A létező fájlok felülírása',
	"viewlink"		=> "Nézet",
	"actview"		=> "A fájl forrásának megtekintése",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Az alkönyvtárakon is',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Új verzió ellenőrzése',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Fájl/könyvtár átnevezése...',
	'newname'		=>	'Új név',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Mentés után vissza a könyvtárhoz',
	'line'		=> 	'Sor',
	'column'	=>	'Oszlop',
	'wordwrap'	=>	'Sortördelés: (csak IE)',
	'copyfile'	=>	'Másolat a fájlról az alábbi néven',
	
	// Bookmarks
	'quick_jump' => 'Gyors ugrás ide',
	'already_bookmarked' => 'Ehhez a könyvtárhoz már helyezett el könyvjelzőt',
	'bookmark_was_added' => 'A könyvjelző létrehozása ehhez a könyvtárhoz kész.',
	'not_a_bookmark' => 'Ez a könyvtár nem könyvjelző.',
	'bookmark_was_removed' => 'Ez a könyvtár eltávolításra került a könyvjelzők közül.',
	'bookmarkfile_not_writable' => "Nem sikerült %s a könyvjelzőt.\n A(z) '%s' könyvjelzőfájl \nnem írható.",
	
	'lbl_add_bookmark' => 'Könyvjelző készítése ehhez a könyvtárhoz',
	'lbl_remove_bookmark' => 'A könyvtár könyvjelzőjének eltávolítása',
	
	'enter_alias_name' => 'Kérjük, hogy írja be a könyvtár aliasnevét',
	
	'normal_compression' => 'normál tömörítés',
	'good_compression' => 'jó tömörítés',
	'best_compression' => 'legjobb tömörítés',
	'no_compression' => 'nincs tömörítés',
	
	'creating_archive' => 'Archív fájl létrehozása...',
	'processed_x_files' => 'Feldolgozva %s a(z) %s fájlból',
	
	'ftp_header' => 'Helyi FTP hitelesítés',
	'ftp_login_lbl' => 'Kérjük, hogy írja be az FTP kiszolgáló hitelesítési adatait',
	'ftp_login_name' => 'FTP felhasználónév',
	'ftp_login_pass' => 'FTP jelszó',
	'ftp_hostname_port' => 'FTP kiszolgáló neve és portja <br />(A port elhagyható)',
	'ftp_login_check' => 'FTP kapcsolat ellenőrzése...',
	'ftp_connection_failed' => "Nem lehet kapcsolódni az FTP kiszolgálóhoz. \nKérjük, ellenőrizze, hogy működik-e az FTP kiszolgáló a szerveren.",
	'ftp_login_failed' => "Sikertelen az FTP bejelentkezés. Kérjük, hogy ellenőrizze a felhasználónevet és a jelszót, majd próbálja újra.",
		
	'switch_file_mode' => 'Jelenlegi mód: <strong>%s</strong>. Átváltás %s módba.',
	'symlink_target' => 'A szimbolikus hivatkozás célja',
	
	"permchange"		=> "CHMOD siker:",
	"savefile"		=> "A fájl mentése kész.",
	"moveitem"		=> "Az áthelyezés sikerült.",
	"copyitem"		=> "A másolás sikerült.",
	'archive_name' 	=> 'Az archívumfájl neve',
	'archive_saveToDir' 	=> 'Az archívum mentése a könyvtárban',
	
	'editor_simple'	=> 'Egyszerű szerkesztő mód',
	'editor_syntaxhighlight'	=> 'Szintaxis-kiemeléses mód',

	'newlink'	=> 'Új fájl/könyvtár',
	'show_directories' => 'Könyvtárak megjelenítése',
	'actlogin_success' => 'A bejelentkezés sikerült!',
	'actlogin_failure' => 'A bejelentkezés nem sikerült, próbálja újra.',
	'directory_tree' => 'Könyvtárak',
	'browsing_directory' => 'Tallózandó könyvtár',
	'filter_grid' => 'Szűrő',
	'paging_page' => 'Oldal',
	'paging_of_X' => '/ {0}',
	'paging_firstpage' => 'Első oldal',
	'paging_lastpage' => 'Utolsó oldal',
	'paging_nextpage' => 'Következő oldal',
	'paging_prevpage' => 'Előző oldal',
	
	'paging_info' => 'Elemek megjelenítése: {0} - {1} / {2}',
	'paging_noitems' => 'Nincs megjelenített elem',
	'aboutlink' => 'Névjegy...',
	'password_warning_title' => 'Fontos - Változtassa meg jelszavát!',
	'password_warning_text' => 'A felhasználói fiók, melybe bejelentkezett (admin admin jelszóval) megegyezik az alapértelmezés szerinti eXtplorer engedélyezésű fiókéval. Az Ön eXtplorer telepítése így nyitott a betolakodók számára, ezért javítsa azonnal ezt a biztonsági rést!',
	'change_password_success' => 'Az Ön jelszava megváltozott!',
	'success' => 'Siker',
	'failure' => 'Sikertelen',
	'dialog_title' => 'Webhely párbeszédablak',
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
