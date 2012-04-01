<?php

// German Language Module for joomlaXplorer (translated by the QuiX project)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d.m.Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Fehler",
	"back"			=> "zurück",
	
	// root
	"home"			=> "Das Home-Verzeichnis existiert nicht, kontrollieren sie ihre Einstellungen.",
	"abovehome"		=> "Das aktuelle Verzeichnis darf nicht höher liegen als das Home-Verzeichnis.",
	"targetabovehome"	=> "Das Zielverzeichnis darf nicht höher liegen als das Home-Verzeichnis.",
	
	// exist
	"direxist"		=> "Dieses Verzeichnis existiert nicht.",
	//"filedoesexist"	=> "Diese Datei existiert bereits.",
	"fileexist"		=> "Diese Datei existiert nicht.",
	"itemdoesexist"		=> "Dieses Objekt existiert bereits.",
	"itemexist"		=> "Dieses Objekt existiert nicht.",
	"targetexist"		=> "Das Zielverzeichnis existiert nicht.",
	"targetdoesexist"	=> "Das Zielobjekt existiert bereits.",
	
	// open
	"opendir"		=> "Kann Verzeichnis nicht öffnen.",
	"readdir"		=> "Kann Verzeichnis nicht lesen",
	
	// access
	"accessdir"		=> "Zugriff auf dieses Verzeichnis verweigert.",
	"accessfile"		=> "Zugriff auf diese Datei verweigert.",
	"accessitem"		=> "Zugriff auf dieses Objekt verweigert.",
	"accessfunc"		=> "Zugriff auf diese Funktion verweigert.",
	"accesstarget"		=> "Zugriff auf das Zielverzeichnis verweigert.",
	
	// actions
	"permread"		=> "Rechte lesen fehlgeschlagen.",
	"permchange"		=> "Rechte ändern fehlgeschlagen.",
	"openfile"		=> "Datei öffnen fehlgeschlagen.",
	"savefile"		=> "Datei speichern fehlgeschlagen.",
	"createfile"		=> "Datei anlegen fehlgeschlagen.",
	"createdir"		=> "Verzeichnis anlegen fehlgeschlagen.",
	"uploadfile"		=> "Datei hochladen fehlgeschlagen.",
	"copyitem"		=> "kopieren fehlgeschlagen.",
	"moveitem"		=> "verschieben fehlgeschlagen.",
	"delitem"		=> "löschen fehlgeschlagen.",
	"chpass"		=> "Passwort ändern fehlgeschlagen.",
	"deluser"		=> "Benutzer löschen fehlgeschlagen.",
	"adduser"		=> "Benutzer hinzufügen fehlgeschlagen.",
	"saveuser"		=> "Benutzer speichern fehlgeschlagen.",
	"searchnothing"		=> "Sie müssen etwas zum suchen eintragen.",
	
	// misc
	"miscnofunc"		=> "Funktion nicht vorhanden.",
	"miscfilesize"		=> "Datei ist größer als die maximale Größe.",
	"miscfilepart"		=> "Datei wurde nur zum Teil hochgeladen.",
	"miscnoname"		=> "Sie müssen einen Namen eintragen",
	"miscselitems"		=> "Sie haben keine Objekt(e) ausgewählt.",
	"miscdelitems"		=> "Sollen die {0} markierten Objekt(e) gelöscht werden?",
	"miscdeluser"		=> "Soll der Benutzer '{0}' gelöscht werden?",
	"miscnopassdiff"	=> "Das neue und das heutige Passwort sind nicht verschieden.",
	"miscnopassmatch"	=> "Passwörter sind nicht gleich.",
	"miscfieldmissed"	=> "Sie haben ein wichtiges Eingabefeld vergessen auszufüllen",
	"miscnouserpass"	=> "Benutzer oder Passwort unbekannt.",
	"miscselfremove"	=> "Sie können sich selbst nicht löschen.",
	"miscuserexist"		=> "Der Benutzer existiert bereits.",
	"miscnofinduser"	=> "Kann Benutzer nicht finden.",
	"extract_noarchive" 	=> "Dieses Datei ist leider kein Archiv.",
	"extract_unknowntype" 	=> "Archivtyp unbekannt",
	
	'chmod_none_not_allowed' => 'Dateirechte können nicht leer sein!',
	'archive_dir_notexists' => 'Das Verzeichnis zum Speichern des Archivs existiert nicht.',
	'archive_dir_unwritable' => 'Bitte geben Sie ein beschreibbares Verzeichnis an!',
	'archive_creation_failed' => 'Speichern des Archivs fehlgeschlagen'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Rechte ändern",
	"editlink"		=> "Bearbeiten",
	"downlink"		=> "Herunterladen",
	"uplink"		=> "Höher",
	"homelink"		=> "Startseite",
	"reloadlink"		=> "Aktualisieren",
	"copylink"		=> "Kopieren",
	"movelink"		=> "Verschieben",
	"dellink"		=> "Löschen",
	"comprlink"		=> "Archivieren",
	"adminlink"		=> "Administration",
	"logoutlink"		=> "Abmelden",
	"uploadlink"		=> "Hochladen",
	"searchlink"		=> "Suchen",
	"extractlink"		=> "Entpacken",
	'chmodlink'		=> 'Rechte (chmod) ändern (Verzeichnisse)/Datei(en))', // new mic
	'mossysinfolink'	=> 'eXtplorer System Informationen (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Gehe zur eXtplorer Webseite (neues Fenster)', // new mic
	
	// list
	"nameheader"		=> "Name",
	"sizeheader"		=> "Größe",
	"typeheader"		=> "Typ",
	"modifheader"		=> "Geändert",
	"permheader"		=> "Rechte",
	"actionheader"		=> "Aktionen",
	"pathheader"		=> "Pfad",
	
	// buttons
	"btncancel"		=> "Abbrechen",
	"btnsave"		=> "Speichern",
	"btnchange"		=> "Ändern",
	"btnreset"		=> "Zurücksetzen",
	"btnclose"		=> "Schließen",
	"btncreate"		=> "Anlegen",
	"btnsearch"		=> "Suchen",
	"btnupload"		=> "Hochladen",
	"btncopy"		=> "Kopieren",
	"btnmove"		=> "Verschieben",
	"btnlogin"		=> "Anmelden",
	"btnlogout"		=> "Abmelden",
	"btnadd"		=> "Hinzufügen",
	"btnedit"		=> "Ändern",
	"btnremove"		=> "Löschen",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'		=> 'Umbenennen',
	'confirm_delete_file' 	=> 'Are you sure you want to delete this file? \\n%s',
	'success_delete_file' 	=> 'Item(s) successfully deleted.',
	'success_rename_file' 	=> 'The directory/file %s was successfully renamed to %s.',
	
	
	// actions
	"actdir"		=> "Verzeichnis",
	"actperms"		=> "Rechte ändern",
	"actedit"		=> "Datei bearbeiten",
	"actsearchresults"	=> "Suchergebnisse",
	"actcopyitems"		=> "Objekt(e) kopieren",
	"actcopyfrom"		=> "kopiere von /%s nach /%s ",
	"actmoveitems"		=> "Objekt(e) verschieben",
	"actmovefrom"		=> "verschiebe von /%s nach /%s ",
	"actlogin"		=> "anmelden",
	"actloginheader"	=> "Melden sie sich an um QuiXplorer zu benutzen",
	"actadmin"		=> "Administration",
	"actchpwd"		=> "Passwort ändern",
	"actusers"		=> "Benutzer",
	"actarchive"		=> "Objekt(e) archivieren",
	"actupload"		=> "Datei(en) hochladen",
	
	// misc
	"miscitems"		=> "Objekt(e)",
	"miscfree"		=> "Freier Speicher",
	"miscusername"		=> "Benutzername",
	"miscpassword"		=> "Passwort",
	"miscoldpass"		=> "Altes Passwort",
	"miscnewpass"		=> "Neues Passwort",
	"miscconfpass"		=> "Bestätige Passwort",
	"miscconfnewpass"	=> "Bestätige neues Passwort",
	"miscchpass"		=> "Ändere Passwort",
	"mischomedir"		=> "Home-Verzeichnis",
	"mischomeurl"		=> "Home URL",
	"miscshowhidden"	=> "Versteckte Objekte anzeigen",
	"mischidepattern"	=> "Versteck-Filter",
	"miscperms"		=> "Rechte",
	"miscuseritems"		=> "(Name, Home-Verzeichnis, versteckte Objekte anzeigen, Rechte, aktiviert)",
	"miscadduser"		=> "Benutzer hinzufügen",
	"miscedituser"		=> "Benutzer '%s' ändern",
	"miscactive"		=> "Aktiviert",
	"misclang"		=> "Sprache",
	"miscnoresult"		=> "Suche ergebnislos.",
	"miscsubdirs"		=> "Suche in Unterverzeichnisse",
	"miscpermnames"		=> array("Nur ansehen","Ändern","Passwort ändern","Ändern & Passwort ändern","Administrator"),
	"miscyesno"		=> array("Ja","Nein","J","N"),
	"miscchmod"		=> array("Besitzer", "Gruppe", "Publik"),
	
	'miscowner'		=> 'Inhaber',
	'miscownerdesc'		=> '<strong>Erklärung:</strong><br />Benutzer (UID) /<br />Gruppe (GID)<br />Aktuelle Besitzerrechte:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>', // new mic

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'eXtplorer System Info',
	'sisysteminfo'		=> 'System Info',
	'sibuilton'		=> 'Betriebssystem',
	'sidbversion'		=> 'Datenbankversion (MySQL)',
	'siphpversion'		=> 'PHP Version',
	'siphpupdate'		=> 'HINWEIS: <font style="color: red;">Die verwendete PHP Version ist <strong>nicht</strong> aktuell!</font><br />Um ein ordnungsgemässes Funktionieren von eXtplorer bzw. dessen Erweiterungen zu gewährleisten,<br />sollte mindestens <strong>PHP.Version 4.3</strong> eingesetzt werden!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - PHP Schnittstelle',
	'simamboversion'	=> 'eXtplorer Version',
	'siuseragent'		=> 'Browserversion',
	'sirelevantsettings'	=> 'Wichtige PHP Einstellungen',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'		=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP Fehleranzeige',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Datei Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Ausgabebuffer',
	'sisesssavepath'	=> 'Session Sicherungspfad',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML aktiviert',
	'sizlibenabled'		=> 'ZLIB aktiviert',
	'sidisabledfuncs'	=> 'Nicht aktivierte Funktionen',
	'sieditor'		=> 'WYSIWYG Bearbeitung (Editor)',
	'siconfigfile'		=> 'Konfigurationsdatei',
	'siphpinfo'		=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Rechte',
	'sidirperms'		=> 'Verzeichnisrechte',
	'sidirpermsmess'	=> 'Damit alle Funktionen und Zusätze einwandfrei arbeiten können, sollten folgende Verzeichnisse Schreibrechte [chmod 0777] besitzen',
	'sionoff'		=> array( 'Ein', 'Aus' ),
	
	'extract_warning' 	=> "Soll dieses Datei wirklich entpackt werden? Hier?\\nBeim Entpacken werden evtl. vorhandene Dateien überschrieben!",
	'extract_success' 	=> "Das Entpacken des Archivs war erfolgreich.",
	'extract_failure' 	=> "Das Entpacken des Archivs ist fehlgeschlagen.",
	
	'overwrite_files' 	=> 'vorhandene Datei(en) überschreiben?',
	"viewlink"		=> "anzeigen",
	"actview"		=> "Zeige Quelltext der Datei",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Auch Unterverzeichnisse mit einbeziehen?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'		=> 'Prüfe auf aktuellste Version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'		=>	'Umbenennen eines Verzeichnisses oder einer Datei...',
	'newname'		=>	'Neuer Name',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'		=>	'zurück zum Verzeichnis nach dem Speichern?',
	'line'			=> 	'Zeile',
	'column'		=>	'Spalte',
	'wordwrap'		=>	'Zeilenumbruch: (IE only)',
	'copyfile'		=>	'Kopiere diese Datei zu folgendem Dateinamen',
	
	// Bookmarks
	'quick_jump'		=> 'Springe zu',
	'already_bookmarked' 	=> 'Dieses Verzeichnis ist bereits als Lesezeichen eingetragen.',
	'bookmark_was_added' 	=> 'Das Verzeichnis wurde als Lesezeichen hinzugefügt.',
	'not_a_bookmark' 	=> 'Dieses Verzeichnis ist kein Lesezeichen und kann nicht entfernt werden.',
	'bookmark_was_removed' 	=> 'Das Verzeichnis wurde von der Liste der Lesezeichen entfernt.',
	'bookmarkfile_not_writable' => "Die Aktion (%) ist fehlgeschlagen. Die Lesezeichendatei '%s' \nist nicht beschreibbar.",
	
	'lbl_add_bookmark' 	=> 'Füge dieses Verzeichnis als Lesezeichen hinzu',
	'lbl_remove_bookmark' 	=> 'Entferne dieses Verzeichnis von der Liste der Lesezeichen',
	
	'enter_alias_name' 	=> 'Bitte gib einen Aliasnamen für dieses Lesezeichen an',
	
	'normal_compression' 	=> 'normale Kompression, schnell',
	'good_compression' 	=> 'gute Kompression, CPU-intensiv',
	'best_compression' 	=> 'beste Kompression, CPU-intensiv',
	'no_compression' 	=> 'keine Kompression, sehr schnell',
	
	'creating_archive' 	=> 'Das Archiv wird erstellt...',
	'processed_x_files' 	=> 'Es wurden %s von %s Dateien bearbeitet',
	
	'ftp_login_lbl' 	=> 'Please enter the login credentials for the FTP server',
	'ftp_login_name' 	=> 'FTP Benutzername',
	'ftp_login_pass' 	=> 'FTP Passwort',
	'ftp_hostname_port' 	=> 'FTP Hostname und Port <br />(Port ist optional)',
	'ftp_login_check' 	=> 'Überprüfe die FTP Verbindung...',
	'ftp_connection_failed' => "Der FTP Server konnte nicht erreicht werden. \nBitte überprüfen Sie, daß der FTP Server auf ihrem System läft.",
	'ftp_login_failed' 	=> "Anmeldung am FTP Server fehlgeschlagen. Bitte überprüfen Sie Benutzername und Passwort und versuchen es nochmal.",
	
	'switch_file_mode' 	=> 'Aktueller Modus: <strong>%s</strong>. Modus wechseln zu: %s.',
	'symlink_target' 	=> 'Ziel des symbolischen Links',
	
	"permchange"		=> "CHMOD Erfolg:",
	"savefile"		=> "Die Datei wurde gespeichert.",
	"moveitem"		=> "Das Verschieben war erfolgreich.",
	"copyitem"		=> "Das Kopieren war erfolgreich.",
	'archive_name' 	=> 'Name des Archivs',
	'archive_saveToDir' 	=> 'Speichere das Archiv in folgendem Verzeichnis',
	
	'editor_simple'	=> 'Einfacher Editormodus',
	'editor_syntaxhighlight'	=> 'Syntax-Hervorhebungsmodus',

	'newlink'	=> 'Neue Datei/Verzeichnis',
	'show_directories' => 'Zeige Verzeichnisse',
	'actlogin_success' => 'Anmeldung erfolreich!',
	'actlogin_failure' => 'Anmeldung fehlgeschlagen, bitte erneut versuchen.',
	'directory_tree' => 'Verzeichnisbaum',
	'browsing_directory' => 'Zeige Verzeichnis',
	'filter_grid' => 'Filter',
	'paging_page' => 'Seite',
	'paging_of_X' => 'von {0}',
	'paging_firstpage' => 'Erste Seite',
	'paging_lastpage' => 'Letzte Seite',
	'paging_nextpage' => 'Nächste Seite',
	'paging_prevpage' => 'Vorherige Seite',
	'paging_info' => 'Zeige Einträge {0} - {1} von {2}',
	'paging_noitems' => 'keine Einträge zum anzeigen',
	'aboutlink' => 'Über..',
	'password_warning_title' => 'Wichtig - Ändern Sie Ihr Passwort!',
	'password_warning_text' => 'Das Benutzerkonto, mit dem Du angemeldet bist (admin mit Passwort admin) entspricht dem des Standard-eXtplorer Administratorkontos. Wenn diese eXtplorer Installation mit diesen Einstellungen betrieben, so können Unbefugte leicht von außen auf sie zugreifen. Du solltest diese Sicherheitslücke unbedingt schließen!',
	'change_password_success' => 'Dein Passwort wurde geändert!',
	'success' => 'Erfolg',
	'failure' => 'Fehlgeschlagen',
	'dialog_title' => 'Webseiten-Dialog',
	'acttransfer' => 'Übertragen von einem anderen Server',
	'transfer_processing' => 'Übertragung ist im Gange, bitte warten Sie...',
	'transfer_completed' => 'Übertragung vollständig!',
	'max_file_size' => 'Maximale Dateigröße',
	'max_post_size' => 'Maximale Upload-Größe',
	'done' => 'Fertig.',
	'permissions_processing' => 'Rechte werden angepasst, bitte warten Sie...',
	'archive_created' => 'Das Archiv wurde erstellt!',
	'save_processing' => 'Datei wird gespeichert...',
	'current_user' => 'Diese Anwendung läft gegenwärtig mit den Rechten des folgenden Nutzers:',
	'your_version' => 'Ihre Version',
	'search_processing' => 'Suche läft, bitte warten Sie...',
	'url_to_file' => 'Adresse der Datei',
	'file' => 'Datei'
);
?>
