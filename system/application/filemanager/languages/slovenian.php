<?php
// $Id: slovenian.php 149 2009-06-21 18:44:27Z soeren $
// Slovenian Language Module for v2.3 (translated by KSi)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Napaka(e)",
	"message"			=> "Sporočilo(a)",
	"back"			=> "Nazaj",

	// root
	"home"			=> "Domači direktorij ne obstaja, preverite nastavitve.",
	"abovehome"		=> "Trenutni direktorij mogoče ni nad domačim direktorijem.",
	"targetabovehome"	=> "Ciljni direktorij mogoče ni nad domačim direktorijem.",

	// exist
	"direxist"		=> "Ta direktorij ne obstaja.",
	//"filedoesexist"	=> "Ta datoteka že obstaja.",
	"fileexist"		=> "Ta datoteka ne obstaja.",
	"itemdoesexist"		=> "Ta element že obstaja.",
	"itemexist"		=> "Ta element ne obstaja.",
	"targetexist"		=> "Ciljni direktorij ne obstaja.",
	"targetdoesexist"	=> "Ciljni element že obstaja.",

	// open
	"opendir"		=> "Ne morem odpreti direktorija.",
	"readdir"		=> "Ne morem prebrati direktorija.",

	// access
	"accessdir"		=> "Nimate dovoljenja za dostop do tega direktorija.",
	"accessfile"		=> "Nimate dovoljenja za dostop do te datoteke.",
	"accessitem"		=> "Nimate dovoljenja za dostop do tega elementa.",
	"accessfunc"		=> "Nimate dovoljenja za uporabo te funkcije.",
	"accesstarget"		=> "Nimate dovoljenja za dostop do ciljnega direktorija.",

	// actions
	"permread"		=> "Pridobivanje dovoljenja ni uspelo.",
	"permchange"		=> "Sprememba dovoljenja (CHMOD) ni uspela.",
	"openfile"		=> "Odpiranje datoteke ni uspelo.",
	"savefile"		=> "Shranjevanje datoteke ni uspelo.",
	"createfile"		=> "Ustvarjanje datoteke ni uspelo.",
	"createdir"		=> "Ustvarjanje direktorija ni uspelo.",
	"uploadfile"		=> "Nalaganje datoteke ni uspelo.",
	"copyitem"		=> "Kopiranje ni uspelo.",
	"moveitem"		=> "Premikanje ni uspelo.",
	"delitem"		=> "Brisanje ni uspelo.",
	"chpass"		=> "Spreminjanje gesla ni uspelo.",
	"deluser"		=> "Odstranjevanje uporabnika ni uspelo.",
	"adduser"		=> "Dodajanje uporabnika ni uspelo.",
	"saveuser"		=> "Shranjevanje uporabnika ni uspelo.",
	"searchnothing"		=> "Vpisati morate element iskanja.",

	// misc
	"miscnofunc"		=> "Funkcija ni na voljo.",
	"miscfilesize"		=> "Datoteka presega dovoljeno velikost.",
	"miscfilepart"		=> "Datoteka je bila samo delno naložena.",
	"miscnoname"		=> "Vpisati morate ime.",
	"miscselitems"		=> "Niste izbrali nobenega elementa.",
	"miscdelitems"		=> "Ste prepričani, da bi radi izbrisali element(e) {0}?",
	"miscdeluser"		=> "Ste prepričani, da bi radi izbrisali uporabnika '{0}'?",
	"miscnopassdiff"	=> "Novo geslo se ne razlikuje od sedanjega.",
	"miscnopassmatch"	=> "Gesli se ne ujemata.",
	"miscfieldmissed"	=> "Izpustili ste pomembno polje.",
	"miscnouserpass"	=> "Nepravilno uporabniško ime ali geslo.",
	"miscselfremove"	=> "Ne morete odstraniti sebe.",
	"miscuserexist"		=> "Uporabnik že obstaja.",
	"miscnofinduser"	=> "Ne najdem uporabnika.",
	"extract_noarchive" => "Datoteka ni arhivske vrste.",
	"extract_unknowntype" => "Neznana arhivska vrsta",
	
	'chmod_none_not_allowed' => 'Spreminjanje dovoljenja v <none> ni dovoljeno',
	'archive_dir_notexists' => 'Direktorij, v katerega poskušate shraniti, ne obstaja.',
	'archive_dir_unwritable' => 'Izberite zapisljiv direktorij za shranitev arhiva.',
	'archive_creation_failed' => 'Shranjevanje arhiva ni uspelo'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Spremeni dovoljenja",
	"editlink"		=> "Uredi",
	"downlink"		=> "Download",
	"uplink"		=> "Gor",
	"homelink"		=> "Domov",
	"reloadlink"		=> "Ponovno naloži",
	"copylink"		=> "Kopiraj",
	"movelink"		=> "Premakni",
	"dellink"		=> "Izbriši",
	"comprlink"		=> "Arhiviraj",
	"adminlink"		=> "Admin",
	"logoutlink"		=> "Odjavi se",
	"uploadlink"		=> "Naloži",
	"searchlink"		=> "Išči",
	"extractlink"	=> "Razpakiraj arhiv",
	'chmodlink'		=> 'Spremeni dovoljenja (chmod)(Mapa/Datoteka(e))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Go to the joomlaXplorer Website (new window)', // new mic

	// list
	"nameheader"		=> "Ime",
	"sizeheader"		=> "Velikost",
	"typeheader"		=> "Vrsta",
	"modifheader"		=> "Popravljeno",
	"permheader"		=> "Dovoljenja",
	"actionheader"		=> "Akcije",
	"pathheader"		=> "Pot",

	// buttons
	"btncancel"		=> "Prekliči",
	"btnsave"		=> "Shrani",
	"btnchange"		=> "Spremeni",
	"btnreset"		=> "Ponastavi",
	"btnclose"		=> "Zapri",
	"btncreate"		=> "Ustvari",
	"btnsearch"		=> "Išči",
	"btnupload"		=> "Naloži",
	"btncopy"		=> "Kopiraj",
	"btnmove"		=> "Premakni",
	"btnlogin"		=> "Prijava",
	"btnlogout"		=> "Odjava",
	"btnadd"		=> "Dodaj",
	"btnedit"		=> "Uredi",
	"btnremove"		=> "Odstrani",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Preimenuj',
	'confirm_delete_file' => 'Ste prepričani, da želite izbrisati to datoteko? <br />%s',
	'success_delete_file' => 'Element(i) uspešno odstranjen(i).',
	'success_rename_file' => 'Direktorij/datoteka %s je bila uspešno preimenovana v %s.',
	
	// actions
	"actdir"		=> "Direktorij",
	"actperms"		=> "Spremeni dovoljenja",
	"actedit"		=> "Uredi datoteko",
	"actsearchresults"	=> "Rezultati iskanja",
	"actcopyitems"		=> "Kopiraj element(e)",
	"actcopyfrom"		=> "Kopiraj iz /%s v /%s ",
	"actmoveitems"		=> "Premakni element(e)",
	"actmovefrom"		=> "Premakni iz /%s v /%s ",
	"actlogin"		=> "Prijavi se",
	"actloginheader"	=> "Prijavi se za uporabo eXtplorerja",
	"actadmin"		=> "Administracija",
	"actchpwd"		=> "Spremeni geslo",
	"actusers"		=> "Uporabniki",
	"actarchive"		=> "Arhiviraj element(e)",
	"actupload"		=> "Naloži datoteko(e)",

	// misc
	"miscitems"		=> "Element(i)",
	"miscfree"		=> "Prosto",
	"miscusername"		=> "Uporabniško ime",
	"miscpassword"		=> "Geslo",
	"miscoldpass"		=> "Staro geslo",
	"miscnewpass"		=> "Novo geslo",
	"miscconfpass"		=> "Potrdi geslo",
	"miscconfnewpass"	=> "Potrdi novo geslo",
	"miscchpass"		=> "Spremeni geslo",
	"mischomedir"		=> "Domači direktorij",
	"mischomeurl"		=> "Domači URL",
	"miscshowhidden"	=> "Prikaži skrite elemente",
	"mischidepattern"	=> "Skrij vzorec",
	"miscperms"		=> "Dovoljenja",
	"miscuseritems"		=> "(ime, domači direktorij, prikaži skrite elemente, dovoljenja, aktiven)",
	"miscadduser"		=> "dodaj uporabnika",
	"miscedituser"		=> "uredi uporabnika '%s'",
	"miscactive"		=> "Aktiven",
	"misclang"		=> "Jezik",
	"miscnoresult"		=> "Ni zadetkov.",
	"miscsubdirs"		=> "Išči v poddirektorijih",
	"miscpermnames"		=> array("Samo vpogled","Popravi","Sprememba gesla","Popravljanje in sprememba gesla",
					"Administrator"),
	"miscyesno"		=> array("Da","Ne","D","N"),
	"miscchmod"		=> array("Lastnik", "Skupina", "Javno"),

	// from here all new by mic
	'miscowner'			=> 'Lastnik',
	'miscownerdesc'		=> '<strong>Opis:</strong><br />Uporabnik (UID) /<br />Skupina (GID)<br />Trenutne pravice:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

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
	
	'extract_warning' => "Si res želite razpakirati to datoteko? Tukaj?<br />Ob neprevidni uporabi bo to prepisalo obstoječe datoteke!",
	'extract_success' => "Datoteka uspešno razpakirana",
	'extract_failure' => "Razpakiranje ni uspelo",
	
	'overwrite_files' => 'Prepiši obstoječo datoteko(e)?',
	"viewlink"		=> "View",
	"actview"		=> "Showing source of file",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Preimenuj direktorij ali datoteko...',
	'newname'		=>	'Novo ime',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Return to directory after saving?',
	'line'		=> 	'Vrsta',
	'column'	=>	'Stolpec',
	'wordwrap'	=>	'Wordwrap: (IE only)',
	'copyfile'	=>	'Copy file into this filename',
	
	// Bookmarks
	'quick_jump' => 'Hitra pot do',
	'already_bookmarked' => 'Ta direktorij je že med zaznamki',
	'bookmark_was_added' => 'Direktorij je bil dodan med zaznamke.',
	'not_a_bookmark' => 'Ta direktorij ni med zaznamki.',
	'bookmark_was_removed' => 'Direktorij je bil odstranjen iz zaznamkov.',
	'bookmarkfile_not_writable' => "Zaznamek %s ni uspel.\n Zaznamek '%s' \n ni zapisljiv.",
	
	'lbl_add_bookmark' => 'Dodaj ta direktorij med zaznamke',
	'lbl_remove_bookmark' => 'Odstrani ta direktorij iz zaznamkov',
	
	'enter_alias_name' => 'Vpišite nadomestno ime za ta zaznamek',
	
	'normal_compression' => 'normal compression',
	'good_compression' => 'good compression',
	'best_compression' => 'best compression',
	'no_compression' => 'no compression',
	
	'creating_archive' => 'Ustvarjanje arhiva...',
	'processed_x_files' => 'Procesiranje %s od %s datotek',
	
	'ftp_header' => 'Local FTP Authentication',
	'ftp_login_lbl' => 'Please enter the login credentials for the FTP server',
	'ftp_login_name' => 'FTP User Name',
	'ftp_login_pass' => 'FTP Password',
	'ftp_hostname_port' => 'FTP Server Hostname and Port <br />(Port is optional)',
	'ftp_login_check' => 'Checking FTP connection...',
	'ftp_connection_failed' => "The FTP server could not be contacted. \nPlease check that the FTP server is running on your server.",
	'ftp_login_failed' => "The FTP login failed. Please check the username and password and try again.",
		
	'switch_file_mode' => 'Trenutni način: <strong>%s</strong>. Lahko zamenjate v %s način.',
	'symlink_target' => 'Target of the Symbolic Link',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "Datoteka shranjena.",
	"moveitem"		=> "Premikanje uspešno.",
	"copyitem"		=> "Kopiranje uspešno.",
	'archive_name' 	=> 'Ime arhivske datoteke',
	'archive_saveToDir' 	=> 'Shrani arhivsko datoteko v ta direktorij',
	
	'editor_simple'	=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'	=> 'Nova datoteka/Direktorij',
	'show_directories' => 'Prikaži direktorije',
	'actlogin_success' => 'Prijava je uspela!',
	'actlogin_failure' => 'Prijava ni uspela, poskusite ponovno.',
	'directory_tree' => 'Direktoriji',
	'browsing_directory' => 'Pregledovanje direktorija',
	'filter_grid' => 'Filter',
	'paging_page' => 'Stran',
	'paging_of_X' => 'od {0}',
	'paging_firstpage' => 'Prva stran',
	'paging_lastpage' => 'Zadnja stran',
	'paging_nextpage' => 'Naprej',
	'paging_prevpage' => 'Nazaj',
	
	'paging_info' => 'Prikaz elementov {0} - {1} od {2}',
	'paging_noitems' => 'Ni elementov za prikaz',
	'aboutlink' => 'About...',
	'password_warning_title' => 'Pomembno - Spremenite geslo!',
	'password_warning_text' => 'Uporabniški račun v katerega ste prijavljeni kot (admin z geslom admin)  predstavlja privzeti eXtplorer račun. Vaša eXtplorer namestitev je odprta za vdore, zato svetujemo, da čimprej odpravite to varnostno luknjo!',
	'change_password_success' => 'Vaše geslo je bilo spremenjeno!',
	'success' => 'Uspeh',
	'failure' => 'Ni uspelo',
	'dialog_title' => 'Spletni dialog',
	'upload_processing' => 'Nalaganje v postopku, prosimo počakajte...',
	'upload_completed' => 'Nalaganje uspešno!',
	'acttransfer' => 'Prenašanje iz drugega strežnika',
	'transfer_processing' => 'Prenos Strežnik-Strežnik v postopku, prosimo počakajte...',
	'transfer_completed' => 'Prenos končan!',
	'max_file_size' => 'Dovoljena velikost',
	'max_post_size' => 'Dovoljena velikost pri nalaganju',
	'done' => 'Done.',
	'permissions_processing' => 'Nastavljanje dovoljenj, prosimo počakajte...',
	'archive_created' => 'Arhivska datoteka je bila ustvarjena!',
	'save_processing' => 'Shranjevanje...',
	'current_user' => 'Ta skript trenutno teče z dovoljenji uporabnika:',
	'your_version' => 'Your Version',
	'search_processing' => 'Iščem, prosimo počakajte...',
	'url_to_file' => 'URL datoteke',
	'file' => 'Datoteka'
	
);
?>
