<?php
// $Id: swedish.php 149 2009-06-21 18:44:27Z soeren $
// Swedish Language Module for v2.3 (translated by the Olle Zettergren)
global $_VERSION;

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y-m-d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Fel",
	"message"		=> "Meddelande(n)",
	"back"			=> "Tillbaka",

	// root
	"home"			=> "Hemkatalogen finns inte, kontrollera dina inställningar.",
	"abovehome"		=> "Den aktuella katalogen kan inte vara ovanför hemkatalogen.",
	"targetabovehome"	=> "Målkatalogen kan inte vara ovanför hemkatalogen.",

	// exist
	"direxist"		=> "Den här katalogen finns inte.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "Den här filen finns inte.",
	"itemdoesexist"		=> "Det här objektet finns redan.",
	"itemexist"		=> "Det här objektet finns inte.",
	"targetexist"		=> "Målkatalogen finns inte.",
	"targetdoesexist"	=> "Målobjektet finns redan.",

	// open
	"opendir"		=> "Det går inte att öppna katalogen.",
	"readdir"		=> "Det går inte att läsa katalogen.",

	// access
	"accessdir"		=> "Du har inte tillträde till den här katalog.",
	"accessfile"	=> "Du har inte tillträde till den här filen.",
	"accessitem"	=> "Du har inte tillträde till det här objektet.",
	"accessfunc"	=> "Du har inte tillträde till den här funktionen.",
	"accesstarget"	=> "Du har inte tillträde till målkatalogen.",

	// actions
	"permread"		=> "Misslyckades med att läsa filtillståndet.",
	"permchange"	=> "CHMOD-fel (Det här beror oftast på ett problem med vem som äger filen - t.ex. att HTTP-användaren är ('wwwrun' eller 'nobody') och FTP-användaren inte är densamma.)",
	"openfile"		=> "Gick inte att öppna filen.",
	"savefile"		=> "Gick inte att spara filen.",
	"createfile"	=> "Gick inte att skapa filen.",
	"createdir"		=> "Gick inte att skapa katalogen.",
	"uploadfile"	=> "Gick inte att ladda upp filen.",
	"copyitem"		=> "Gick inte ta kopiera.",
	"moveitem"		=> "Gick inte att flytta.",
	"delitem"		=> "Gick inte att ta bort.",
	"chpass"		=> "Gick inte att byta lösenord.",
	"deluser"		=> "Gick inte att ta bort användare.",
	"adduser"		=> "Gick inte att lägga till användare.",
	"saveuser"		=> "Gick inte att spara användare.",
	"searchnothing"	=> "Du måste ange någon att söka efter.",

	// misc
	"miscnofunc"		=> "Funktionen otillgänglig.",
	"miscfilesize"		=> "Filen överskrider maximalt tillåten storlek.",
	"miscfilepart"		=> "Filen laddades bara upp delvis.",
	"miscnoname"		=> "Du måste ange ett namn.",
	"miscselitems"		=> "Du har inte valt något/några objekt.",
	"miscdelitems"		=> "är du säker på att du vill ta bort dessa {0} objekt?",
	"miscdeluser"		=> "är du säker på att du vill ta bort användare '{0}'?",
	"miscnopassdiff"	=> "Det nya lösenordet skiljer sig inte från det aktuella.",
	"miscnopassmatch"	=> "Lösenorden matchar inte.",
	"miscfieldmissed"	=> "Du missade ett viktigt fält.",
	"miscnouserpass"	=> "Användarnamn eller lösenord felaktiga.",
	"miscselfremove"	=> "Du kan inte ta bort dig själv.",
	"miscuserexist"		=> "Användaren finns redan.",
	"miscnofinduser"	=> "Kan inte hitta användaren.",
	"extract_noarchive" => "Den här filen är inte en uppackningsbar arkivfil.",
	"extract_unknowntype" => "Okänd akrivtyp",
	
	'chmod_none_not_allowed' => 'ändra rättigheter till <none> är inte tillåtet',
	'archive_dir_notexists' => 'Den katalog du har angivit att spara till finns inte.',
	'archive_dir_unwritable' => 'Ange en skrivbar katalog att spara arkivet till.',
	'archive_creation_failed' => 'Misslyckades att spara akrivfilen'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "ändra rättigheter",
	"editlink"		=> "Redigera",
	"downlink"		=> "Ladda ner",
	"uplink"		=> "Upp",
	"homelink"		=> "Hem",
	"reloadlink"	=> "Ladda om",
	"copylink"		=> "Kopiera",
	"movelink"		=> "Flytta",
	"dellink"		=> "Ta bort",
	"comprlink"		=> "Arkiv",
	"adminlink"		=> "Admin",
	"logoutlink"	=> "Logga ut",
	"uploadlink"	=> "Ladda upp",
	"searchlink"	=> "Sök",
	"extractlink"	=> "Packa upp akrivet",
	'chmodlink'		=> 'ändra (chmod) rättigheter (Mapp/Fil(er))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Information (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Gå till siten för joomlaXplorer (öppnas i nytt fönster)', // new mic

	// list
	"nameheader"		=> "Namn",
	"sizeheader"		=> "Storlek",
	"typeheader"		=> "Type",
	"modifheader"		=> "ändrad",
	"permheader"		=> "Rättigheter",
	"actionheader"		=> "Händelser",
	"pathheader"		=> "Sökväg",

	// buttons
	"btncancel"		=> "Avbryt",
	"btnsave"		=> "Spara",
	"btnchange"		=> "ändra",
	"btnreset"		=> "återställ",
	"btnclose"		=> "Stäng",
	"btncreate"		=> "Skapa",
	"btnsearch"		=> "Sök",
	"btnupload"		=> "Ladda upp",
	"btncopy"		=> "Kopiera",
	"btnmove"		=> "Flytta",
	"btnlogin"		=> "Logga in",
	"btnlogout"		=> "Logga ut",
	"btnadd"		=> "Lägg till",
	"btnedit"		=> "Redigera",
	"btnremove"		=> "Ta bort",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Byt namn',
	'confirm_delete_file' => 'Are you sure you want to delete this file? <br />%s',
	'success_delete_file' => 'Item(s) successfully deleted.',
	'success_rename_file' => 'The directory/file %s was successfully renamed to %s.',
	
	// actions
	"actdir"			=> "Katalog",
	"actperms"			=> "ändra rättigheter",
	"actedit"			=> "Redigera fil",
	"actsearchresults"	=> "Sökresultat",
	"actcopyitems"		=> "Kopiera objekt",
	"actcopyfrom"		=> "Kopiera från /%s till /%s ",
	"actmoveitems"		=> "Flytta objekt",
	"actmovefrom"		=> "Flytta från /%s till /%s ",
	"actlogin"			=> "Logga in",
	"actloginheader"	=> "Logga in för att använda eXtplorer",
	"actadmin"			=> "Administration",
	"actchpwd"			=> "ändra lösenord",
	"actusers"			=> "Användare",
	"actarchive"		=> "Arkivera objekt",
	"actupload"			=> "Ladda upp fil(er)",

	// misc
	"miscitems"			=> "Objekt",
	"miscfree"			=> "Fri",
	"miscusername"		=> "Användarnamn",
	"miscpassword"		=> "Lösenord",
	"miscoldpass"		=> "Gammalt lösenord",
	"miscnewpass"		=> "Nytt lösenord",
	"miscconfpass"		=> "Bekräfta lösenord",
	"miscconfnewpass"	=> "Bekräfta nytt lösenord",
	"miscchpass"		=> "Byt lösenord",
	"mischomedir"		=> "Hemkatalog",
	"mischomeurl"		=> "Hem-URL",
	"miscshowhidden"	=> "Visa dolda objekt",
	"mischidepattern"	=> "Hide pattern",
	"miscperms"			=> "Rättigheter",
	"miscuseritems"		=> "(namn, hemkatalog, visa dolda objekt, rättigheter, aktiv)",
	"miscadduser"		=> "lägg till användare",
	"miscedituser"		=> "redigera användare'%s'",
	"miscactive"		=> "Aktiv",
	"misclang"			=> "Språk",
	"miscnoresult"		=> "Inga resultat tillängliga.",
	"miscsubdirs"		=> "Sök i underkataloger",
	"miscpermnames"		=> array("Visa bara","ändra","ändra lösenord","ändra & Byt lösenord",	"Administratör"),
	"miscyesno"			=> array("Ja","Nej","J","N"),
	"miscchmod"			=> array("ägare", "Grupp", "Publik"),

	// from here all new by mic
	'miscowner'			=> 'ägare',
	'miscownerdesc'		=> '<strong>Beskrivning:</strong><br />Användare (UID) /<br />Grupp (GID)<br />Aktuella rättigheter:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorer systeminfo",
	'sisysteminfo'		=> 'Systeminfo',
	'sibuilton'			=> 'Operativsystem',
	'sidbversion'		=> 'Databasversion (MySQL)',
	'siphpversion'		=> 'PHP-version',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">PHP-version du använder är<strong>inte</strong> aktuell!</span><br />För att garantera att alla funktioner och möjligheter ska fungera,<br />bör du använda minst <strong>PHP-version 4.3</strong>!',
	'siwebserver'		=> 'Webbserver',
	'siwebsphpif'		=> 'Webberverns PHP-interface',
	'simamboversion'	=> 'eXtplorer-version',
	'siuseragent'		=> 'Browserversion',
	'sirelevantsettings' => 'Viktiga PHP-inställningar',
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
	'sieditor'			=> 'WYSIWYG-editor',
	'siconfigfile'		=> 'Configfil',
	'siphpinfo'			=> 'PHP-info',
	'siphpinformation'	=> 'PHP-information',
	'sipermissions'		=> 'Rättigheter',
	'sidirperms'		=> 'Katalogrättigheter',
	'sidirpermsmess'	=> 'För att vara säker att alla funktioner och möjligheter i eXtplorer ska fungera korrekt, ska följande kataloger ha skrivrättigheter [chmod 0777]',
	'sionoff'			=> array( 'Av', 'På' ),
	
	'extract_warning' => "Vill du verkigen packa upp denna fil här?<br />Detta kan komma att resultera i att befintliga filer skrivs över om du är oförsiktig!",
	'extract_success' => "Uppackningen lyckades",
	'extract_failure' => "Uppackningen misslyckades",
	
	'overwrite_files' => 'Skriv över befintliga fil(er)?',
	"viewlink"		=> "Visa",
	"actview"		=> "Visa filen",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Tillämpa på underkataloger?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Kontrollera om det finns uppdateringar',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Byt namn på katalog eller fil...',
	'newname'		=>	'Nytt namn',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'återvänd till katalog efter spara?',
	'line'		=> 	'Linje',
	'column'	=>	'Kolumn',
	'wordwrap'	=>	'Radbrytning (endast IE)',
	'copyfile'	=>	'Kopiera fil in i detta filnamn',
	
	// Bookmarks
	'quick_jump' => 'Hoppa till',
	'already_bookmarked' => 'Denna katalog är redan bokmärkt',
	'bookmark_was_added' => 'Denna katalog lades till på bokmärkeslistan.',
	'not_a_bookmark' => 'Denna katalog är inte bokmärkt.',
	'bookmark_was_removed' => 'Denna katalog togs bort från bokmärkeslistan.',
	'bookmarkfile_not_writable' => "Det gick inte att bokmärka .\n Bokmärkesfilen '%s' är inte skrivbar \n.",
	
	'lbl_add_bookmark' => 'Lägg till denna katalog som bokmärke',
	'lbl_remove_bookmark' => 'Ta bort denna katalog från bokmärkeslistan',
	
	'enter_alias_name' => 'Ange ett alias för detta bokmärke',
	
	'normal_compression' => 'normal komprimering',
	'good_compression' => 'god komprimering',
	'best_compression' => 'bästa komprimering',
	'no_compression' => 'inge komprimering',
	
	'creating_archive' => 'Skapa arkivfil...',
	'processed_x_files' => 'Prossessat %s av %s filer',
	
	'ftp_header' => 'Lokala FTP-inställningar',
	'ftp_login_lbl' => 'Ange dina loginuppgifter för FTP-servern',
	'ftp_login_name' => 'FTP-användarnamn ',
	'ftp_login_pass' => 'FTP-lösenord',
	'ftp_hostname_port' => 'FTP-host och port<br />(Port är en valfri uppgift)',
	'ftp_login_check' => 'Kontrollerar FTP-anslutning...',
	'ftp_connection_failed' => "Det gick inte att koppla upp mot FTP-servern. \nKontrollera att FTP-servern är igång på din server.",
	'ftp_login_failed' => "FTP-inloggningen misslyckades. Kontrollera användarnamn och lösenord och försök igen.",
		
	'switch_file_mode' => 'Aktuellt läge är [%s] <br /> ändra till [%s] läge.',
	'symlink_target' => 'Mål för Symbolic Link',
	
	"permchange"		=> "CHMOD lyckades:",
	"savefile"		=> "Filen sparades.",
	"moveitem"		=> "Flyttning lyckades.",
	"copyitem"		=> "Kopiering lyckades.",
	'archive_name' 	=> 'Namn på arkivfil',
	'archive_saveToDir' 	=> 'Spara arkivet i denna katalog',
	
	'editor_simple'	=> 'Editorläge',
	'editor_syntaxhighlight'	=> 'Läge för kodmarkering',

	'newlink'	=> 'Ny Fil/Katalog',
	'show_directories' => 'Visa kataloger',
	'actlogin_success' => 'Inloggning lyckades!',
	'actlogin_failure' => 'Inloggning misslyckades, försök igen.',
	'directory_tree' => 'Katalogträd',
	'browsing_directory' => 'Bläddra i katalogerna',
	'filter_grid' => 'Filter',
	'paging_page' => 'Sida',
	'paging_of_X' => 'av {0}',
	'paging_firstpage' => 'Första sidan',
	'paging_lastpage' => 'Sista sidan',
	'paging_nextpage' => 'Nästa sidan',
	'paging_prevpage' => 'Föregående sidan',
	
	'paging_info' => 'Visa objekt {0} - {1} av {2}',
	'paging_noitems' => 'Inga objekt att visa',
	'aboutlink' => 'Om...',
	'password_warning_title' => 'Viktigt - ändra ditt lösenord!',
	'password_warning_text' => 'Användarkontot du loggade in med (admin med lösenord admin) är standardkontot för administratörer i eXtplorer. Din eXtplorer-installation ligger öppen för angrepp och du måste omedelbart rätta till detta säkerhetshål. Byt lösenord nu!',
	'change_password_success' => 'Ditt lösenord har ändrats!',
	'success' => 'Lyckades',
	'failure' => 'Misslyckades',
	'dialog_title' => 'Webbsitedialog',
	'upload_processing' => 'Processar uppladdning, vänta...',
	'upload_completed' => 'Uppladdning lyckades!',
	'acttransfer' => 'överför från en annan server',
	'transfer_processing' => 'Processar överföring från Server-till-Server, vänta...',
	'transfer_completed' => 'överföring klar!',
	'max_file_size' => 'Maximal filstorlek',
	'max_post_size' => 'Maximal uppladdningsgräns',
	'done' => 'Klart.',
	'permissions_processing' => 'Applicerar rättigheter, vänta...',
	'archive_created' => 'Arkivfilen har skapats!',
	'save_processing' => 'Sparar fil...',
	'current_user' => 'Detta skript körs just nu med rättigheter för följande användare:',
	'your_version' => 'Din version',
	'search_processing' => 'Söker, vänta...',
	'url_to_file' => 'Filens URL',
	'file' => 'Fil'
	
);
?>
