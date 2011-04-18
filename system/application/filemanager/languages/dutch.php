<?php

// Dutch Language Module for joomlaXplorer
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d-m-Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "FOUT(EN)",
	"back"			=> "Ga Terug",
	
	// root
	"home"			=> "De thuis map bestaat niet, controleer uw instellingen.",
	"abovehome"		=> "De huidige map mag niet hoger liggen dan de thuis map.",
	"targetabovehome"	=> "De doel map mag niet hoger liggen dan de thuis map.",
	
	// exist
	"direxist"		=> "Deze map bestaat niet.",
	//"filedoesexist"	=> "Dit bestand bestaat al.",
	"fileexist"		=> "Dit bestand bestaat niet.",
	"itemdoesexist"		=> "Dit item bestaat al.",
	"itemexist"		=> "Dit item bestaat niet.",
	"targetexist"		=> "De doel map bestaat niet.",
	"targetdoesexist"	=> "Het doel item bestaat al.",
	
	// open
	"opendir"		=> "Kan map niet openen.",
	"readdir"		=> "Kan map niet lezen.",
	
	// access
	"accessdir"		=> "U hebt geen toegang tot deze map.",
	"accessfile"		=> "U hebt geen toegang tot dit bestand.",
	"accessitem"		=> "U hebt geen toegang tot dit item.",
	"accessfunc"		=> "U hebt geen rechten deze functie te gebruiken.",
	"accesstarget"		=> "U hebt geen toegang tot de doel map.",
	
	// actions
	"permread"		=> "Rechten opvragen mislukt.",
	"permchange"		=> "Rechten wijzigen mislukt.",
	"openfile"		=> "Bestand openen mislukt.",
	"savefile"		=> "Bestand opslaan mislukt.",
	"createfile"		=> "Bestand maken mislukt.",
	"createdir"		=> "Map maken mislukt.",
	"uploadfile"		=> "Bestand uploaden mislukt.",
	"copyitem"		=> "Kopieeren mislukt.",
	"moveitem"		=> "Verplaatsen mislukt.",
	"delitem"		=> "Verwijderen mislukt.",
	"chpass"		=> "Wachtwoord wijzigen mislukt.",
	"deluser"		=> "Gebruiker verwijderen mislukt.",
	"adduser"		=> "Gebruiker toevoegen mislukt.",
	"saveuser"		=> "Gebruiker opslaan mislukt.",
	"searchnothing"		=> "U moet iets te zoeken opgeven.",
	
	// misc
	"miscnofunc"		=> "Functie niet beschikbaar.",
	"miscfilesize"		=> "Bestand is groter dan de maximum grootte.",
	"miscfilepart"		=> "Bestand is maar gedeeltelijk geupload.",
	"miscnoname"		=> "U moet een naam opgeven.",
	"miscselitems"		=> "U hebt geen item(s) geselecteerd.",
	"miscdelitems"		=> "Weet u zeker dat u deze {0} item(s) wilt verwijderen?",
	"miscdeluser"		=> "Weet u zeker dat u gebruiker {0}' wilt verwijderen?",
	"miscnopassdiff"	=> "Het nieuwe wachtwoord verschilt niet van het huidige.",
	"miscnopassmatch"	=> "Wachtwoorden komen niet overeen.",
	"miscfieldmissed"	=> "U bent een belangrijk veld vergeten in te vullen.",
	"miscnouserpass"	=> "Gebruiker of wachtwoord onjuist.",
	"miscselfremove"	=> "U kunt zichzelf niet verwijderen.",
	"miscuserexist"		=> "De gebruiker bestaat al.",
	"miscnofinduser"	=> "Kan gebruiker niet vinden.",
	"extract_noarchive" => "Dit bestand is geen uitpakbaar archiefbestand.",
	"extract_unknowntype" => "Onbekend archiefbestand",
	
	'chmod_none_not_allowed' => 'Rechten veranderen in <none> is niet toegestaan',
	'archive_dir_notexists' => 'De aangegeven map om te bewaren bestaat niet.',
	'archive_dir_unwritable' => 'De aangegeven map om te bewaren is een alleen-lezen map.',
	'archive_creation_failed' => 'Bewaren van archiefbestand mislukt'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "RECHTEN WIJZIGEN",
	"editlink"		=> "BEWERKEN",
	"downlink"		=> "DOWNLOADEN",
	"uplink"		=> "OMHOOG",
	"homelink"		=> "THUIS",
	"reloadlink"		=> "VERNIEUWEN",
	"copylink"		=> "KOPIEEREN",
	"movelink"		=> "VERPLAATSEN",
	"dellink"		=> "VERWIJDEREN",
	"comprlink"		=> "ARCHIVEREN",
	"adminlink"		=> "BEHEER",
	"logoutlink"		=> "AFMELDEN",
	"uploadlink"		=> "UPLOADEN",
	"searchlink"		=> "ZOEKEN",
	"extractlink"	=> "Uitpakken archiefbestand",
	'chmodlink'		=> 'Verander rechten (chmod)(Map/Bestand(en))', // new mic
	'mossysinfolink'	=> 'Systeem Informatie (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Ga naar de joomlaXplorer Website (nieuw window)', // new mic
	
	// list
	"nameheader"		=> "Naam",
	"sizeheader"		=> "Grootte",
	"typeheader"		=> "Type",
	"modifheader"		=> "Gewijzigd",
	"permheader"		=> "Rechten",
	"actionheader"		=> "Acties",
	"pathheader"		=> "Pad",
	
	// buttons
	"btncancel"		=> "Annuleren",
	"btnsave"		=> "Opslaan",
	"btnchange"		=> "Wijzigen",
	"btnreset"		=> "Opnieuw",
	"btnclose"		=> "Sluiten",
	"btncreate"		=> "Maken",
	"btnsearch"		=> "Zoeken",
	"btnupload"		=> "Uploaden",
	"btncopy"		=> "Kopieeren",
	"btnmove"		=> "Verplaatsen",
	"btnlogin"		=> "Aanmelden",
	"btnlogout"		=> "Afmelden",
	"btnadd"		=> "Toevoegen",
	"btnedit"		=> "Bewerken",
	"btnremove"		=> "Verwijderen",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'HERNOEM',
	'confirm_delete_file' => 'Weet u zeker dat u dit bestand wilt verwijderen? \\n%s',
	'success_delete_file' => 'Item(s) succesvol verwijderd.',
	'success_rename_file' => 'De map/bestand %s is succesvol hernoemd naar %s.',
	
	
	// actions
	"actdir"		=> "Map",
	"actperms"		=> "Rechten wijzigen",
	"actedit"		=> "Bestand bewerken",
	"actsearchresults"	=> "Zoek resultaten",
	"actcopyitems"		=> "Item(s) kopieeren",
	"actcopyfrom"		=> "Kopieer van /%s naar /%s ",
	"actmoveitems"		=> "Item(s) verplaatsen",
	"actmovefrom"		=> "Verplaats van /%s naar /%s ",
	"actlogin"		=> "Aanmelden",
	"actloginheader"	=> "Meld u aan om QuiXplorer te gebruiken",
	"actadmin"		=> "Beheer",
	"actchpwd"		=> "Wachtwoord wijzigen",
	"actusers"		=> "Gebruikers",
	"actarchive"		=> "Item(s) archiveren",
	"actupload"		=> "Bestand(en) uploaden",
	
	// misc
	"miscitems"		=> "Item(s)",
	"miscfree"		=> "Beschikbaar",
	"miscusername"		=> "Gebruikersnaam",
	"miscpassword"		=> "Wachtwoord",
	"miscoldpass"		=> "Oud wachtwoord",
	"miscnewpass"		=> "Nieuw wachtwoord",
	"miscconfpass"		=> "Bevestig wachtwoord",
	"miscconfnewpass"	=> "Bevestig nieuw wachtwoord",
	"miscchpass"		=> "Wijzig wachtwoord",
	"mischomedir"		=> "Thuismap",
	"mischomeurl"		=> "Thuis URL",
	"miscshowhidden"	=> "Verborgen items weergeven",
	"mischidepattern"	=> "Verberg patroon",
	"miscperms"		=> "Rechten",
	"miscuseritems"		=> "(naam, thuis map, verborgen items weergeven, rechten, geactiveerd)",
	"miscadduser"		=> "gebruiker toevoegen",
	"miscedituser"		=> "gebruiker '%s' bewerken",
	"miscactive"		=> "Geactiveerd",
	"misclang"		=> "Taal",
	"miscnoresult"		=> "Geen resultaten beschikbaar.",
	"miscsubdirs"		=> "Zoek in subdirectories",
	"miscpermnames"		=> array("Alleen kijken","Wijzigen","Wachtwoord wijzigen",
					"Wijzigen & Wachtwoord wijzigen","Beheerder"),
	"miscyesno"		=> array("Ja","Nee","J","N"),
	"miscchmod"		=> array("Eigenaar", "Groep", "Publiek"),
	// from here all new by mic
	'miscowner'			=> 'Eigenaar',
	'miscownerdesc'		=> '<strong>Omschrijving:</strong><br />Gebruiker (UID) /<br />Groep (GID)<br />Huidige rechten:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'System Info',
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
	
	'extract_warning' => "Wilt u dit bestand echt hier uitpakken?\\nDit kan bestaande bestanden overschrijven!",
	'extract_success' => "Uitpakken was succesvol",
	'extract_failure' => "Uitpakken mislukt",
	
	'overwrite_files' => 'Bestaande bestand(en) overschrijven?',
	"viewlink"		=> "BEKIJK",
	"actview"		=> "Bekijk bron van het bestand",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Verdeel in onderliggende mappen?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Controleer voor nieuwste versie',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Hernoem een map of bestand...',
	'newname'		=>	'Nieuwe naam',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Terug naar map na bewaren?',
	'line'		=> 	'Regel',
	'column'	=>	'Kolom',
	'wordwrap'	=>	'Terugloop: (IE only)',
	'copyfile'	=>	'Kopieer bestand in deze bestandsnaam',
	
	// Bookmarks
	'quick_jump' => 'Snel naar',
	'already_bookmarked' => 'Deze map is al een favoriet',
	'bookmark_was_added' => 'Deze map is toegevoegd aan de favorieten lijst.',
	'not_a_bookmark' => 'Deze map is geen favoriet.',
	'bookmark_was_removed' => 'Deze map is verwijderd van de favorieten lijst.',
	'bookmarkfile_not_writable' => "Favoriet %s mislukt.\n Het favorieten bestand '%s' \nis niet schrijfbaar.",
	
	'lbl_add_bookmark' => 'Voeg deze map toe aan de favorieten lijst',
	'lbl_remove_bookmark' => 'Verwijder deze map van de favorieten lijst',
	
	'enter_alias_name' => 'Geef de naam voor deze favoriet',
	
	'normal_compression' => 'normale compressie',
	'good_compression' => 'goede compressie',
	'best_compression' => 'beste compressie',
	'no_compression' => 'geen compressie',
	
	'creating_archive' => 'Maken archiefbestand...',
	'processed_x_files' => 'Gedaan: %s van %s bestanden',
	
	'ftp_header' => 'Locale FTP Authenticatie',
	'ftp_login_lbl' => 'Vul de inlog gegevens in voor de FTP server',
	'ftp_login_name' => 'FTP Gebruikers naam',
	'ftp_login_pass' => 'FTP Wachtwoord',
	'ftp_hostname_port' => 'FTP Server Hostnaam en Poort <br />(Port is optional)',
	'ftp_login_check' => 'Controleren FTP connectie...',
	'ftp_connection_failed' => "De FTP server kon niet worden bereikt. \nControleer of de FTP server gestart is op uw server.",
	'ftp_login_failed' => "Het inloggen is mislukt. Controleer de gebruikersnaam en wachtwoord en probeer het opnieuw.",
		
	'switch_file_mode' => 'Huidige modus: <strong>%s</strong>. U kunt omschakelen naar de %s modus.',
	'symlink_target' => 'Doel van de Symbolische Link',
	
	"permchange"		=> "CHMOD Succes:",
	"savefile"		=> "Het bestand is bewaard.",
	"moveitem"		=> "Verplaatsen gelukt.",
	"copyitem"		=> "Kopieeren gelukt.",
	'archive_name' 	=> 'Naan van het archief bestand',
	'archive_saveToDir' 	=> 'Bewaar het archief in deze map',
	
	'editor_simple'	=> 'Simpele Editor Modus',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'	=> 'Nieuw bestand/Map',
	'show_directories' => 'Laat mappen zien',
	'actlogin_success' => 'Login succesvol!',
	'actlogin_failure' => 'Login mislukt, probeer opnieuw.',
	'directory_tree' => 'Mappen structuur',
	'browsing_directory' => 'Bladeren map',
	'filter_grid' => 'Filter',
	'paging_page' => 'Pagina',
	'paging_of_X' => 'van {0}',
	'paging_firstpage' => 'Eerste Pagina',
	'paging_lastpage' => 'Laatste Pagina',
	'paging_nextpage' => 'Volgende Pagina',
	'paging_prevpage' => 'Vorige Pagina',
	
	'paging_info' => 'Weergeven Items {0} - {1} van {2}',
	'paging_noitems' => 'Geen items om weer te geven',
	'aboutlink' => 'Over...',
	'password_warning_title' => 'Belangrijk - Wijzig uw wachtwoord!',
	'password_warning_text' => 'De gebruikers account waarmee u ingelogd bent (admin met wachtwoord admin) komt overeen met het standaard eXtplorer account. Uw eXtplorer installatie is open voor inbraak en u zou onmiddelijk dit beveiligingsgat moeten dichten!',
	'change_password_success' => 'Uw wachtwoord is veranderd!',
	'success' => 'Succes',
	'failure' => 'Mislukt',
	'dialog_title' => 'Website Dialoog',
	'upload_processing' => 'Upload bezig, wacht a.u.b....',
	'upload_completed' => 'Upload succesvol!',
	'acttransfer' => 'Overdracht van een andere server',
	'transfer_processing' => 'Bezig met Server-naar-Server overdracht, wacht a.u.b....',
	'transfer_completed' => 'Overdracht compleet!',
	'max_file_size' => 'Maximum bestands formaat',
	'max_post_size' => 'Maximum Upload Limiet',
	'done' => 'Klaar.',
	'permissions_processing' => 'Aanpassen rechten, wacht a.u.b....',
	'archive_created' => 'Het archief bestand is gemaakt!',
	'save_processing' => 'Bestand wordt bewaard...',
	'current_user' => 'Dit script draait momenteel met de rechten van de volgende gebruiker:',
	'your_version' => 'Uw versie',
	'search_processing' => 'Zoeken, wacht a.u.b....',
	'url_to_file' => 'URL van het bestand',
	'file' => 'Bestand'
);
?>