<?php
// $Id: polish.php 174 2010-08-20 09:27:45Z soeren $
// Polish Language Module for v2.3 (translated by l0co@wp.pl)
// Additional translation and corrections 2010-07-24 by marcin@nevada.pl

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"				=> "Błąd",
	"message"			=> "Komunikat(y)",
	"back"				=> "Wróć",

	// root
	"home"				=> "Katalog domowy nie istnieje. Sprawdź swoje ustawienia.",
	"abovehome"			=> "Bieżący katalog nie może być powyżej katalogu domowego.",
	"targetabovehome"	=> "Docelowy katalog nie może być powyżej katalogu domowego.",

	// exist
	"direxist"			=> "Katalog nie istnieje.",
	//"filedoesexist"	=> "Plik już istnieje.",
	"fileexist"			=> "Plik nie istnieje.",
	"itemdoesexist"		=> "Element już istnieje.",
	"itemexist"			=> "Element nie istnieje.",
	"targetexist"		=> "Katalog docelowy nie istnieje.",
	"targetdoesexist"	=> "Miejsce docelowe już istnieje.",

	// open
	"opendir"			=> "Nie można otworzyć katalogu.",
	"readdir"			=> "Nie można odczytać katalogu.",

	// access
	"accessdir"			=> "Nie masz prawa dostępu do tego katalogu.",
	"accessfile"		=> "Nie masz prawa dostępu do tego pliku.",
	"accessitem"		=> "Nie masz prawa dostępu do tego elementu.",
	"accessfunc"		=> "Nie masz prawa użyć tej funkcji.",
	"accesstarget"		=> "Nie masz prawa dostępu do docelowego katalogu.",

	// actions
	"permread"			=> "Pobieranie uprawnień nie powiodło się.",
	"permchange"		=> "Błąd CHMOD (zazwyczaj spowodowane jest to problemem z ustawieniami właściciela pliku - np. jeśli użytkownik HTTP ('www-data' lub 'nobody') i użytkownik FTP nie są tymi samymi użytkownikami)",
	"openfile"			=> "Otwarcie pliku nie powiodło się.",
	"savefile"			=> "Zapisanie pliku nie powiodło się.",
	"createfile"		=> "Utworzenie pliku nie powiodło się.",
	"createdir"			=> "Utworzenie katalogu nie powiodło się.",
	"uploadfile"		=> "Upload pliku nie powiódł się.",
	"copyitem"			=> "Kopiowanie nie powiodło się.",
	"moveitem"			=> "Przenoszenie nie powiodło się.",
	"delitem"			=> "Usuwanie nie powiodło się.",
	"chpass"			=> "Zmiana nazwa nie powiodła się.",
	"deluser"			=> "Usuwanie użytkownika nie powiodło się.",
	"adduser"			=> "Dodawanie użytkownika nie powiodło się.",
	"saveuser"			=> "Zapisywanie użytkownika nie powiodło się.",
	"searchnothing"		=> "Musisz wpisać frazę wyszukiwania.",

	// misc
	"miscnofunc"		=> "Funkcja nie jest dostępna.",
	"miscfilesize"		=> "Plik przekracza maksymalną wielkość.",
	"miscfilepart"		=> "Plik został załadowany tylko częściowo.",
	"miscnoname"		=> "Musisz wpisać nazwę.",
	"miscselitems"		=> "Nie wybrałeś żadnych elementów.",
	"miscdelitems"		=> "Na pewno chcesz usunąć {0} element(ów)?",
	"miscdeluser"		=> "Na pewno chcesz usunąć użytkownika '{0}'?",
	"miscnopassdiff"	=> "Nowe hasło nie różni się od bieżącego.",
	"miscnopassmatch"	=> "Hasło nie pasuje.",
	"miscfieldmissed"	=> "Nie wypełniłeś ważnego pola.",
	"miscnouserpass"	=> "Nieprawidłowe hasło lub nazwa użytkownika.",
	"miscselfremove"	=> "Nie możesz usunąć sam siebie.",
	"miscuserexist"		=> "Użytkownik już istnieje.",
	"miscnofinduser"	=> "Nie znaleziono użytkownika.",
	"extract_noarchive"		=> "Plik nie jest archiwum możliwym do rozpakowania.",
	"extract_unknowntype"	=> "Nieznany typ archiwum",

	'chmod_none_not_allowed'	=> 'Zmiana uprawnień na <none> nie jest dopuszczalna',
	'archive_dir_notexists'		=> 'Docelowy katalog zapisu który wybrałeś, nie istnieje.',
	'archive_dir_unwritable'	=> 'Proszę wybrać katalog z prawami do zapisu archiwum.',
	'archive_creation_failed'	=> 'Zapis archiwum nie powiódł się'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"			=> "Zmiana uprawnień",
	"editlink"			=> "Edycja",
	"downlink"			=> "Download",
	"uplink"			=> "W górę",
	"homelink"			=> "Katalog domowy",
	"reloadlink"		=> "Odśwież",
	"copylink"			=> "Kopiuj",
	"movelink"			=> "Przenieś",
	"dellink"			=> "Usuń",
	"comprlink"			=> "Dodaj do archiwum",
	"adminlink"			=> "Administrator",
	"logoutlink"		=> "Wyloguj",
	"uploadlink"		=> "Upload",
	"searchlink"		=> "Wyszukaj",
	'difflink'			=> 'Porównaj',
	"extractlink"		=> "Rozpakuj archiwum",
	'chmodlink'			=> 'Zmień uprawnienia (chmod)', // new mic
	'mossysinfolink'	=> 'Informacje o systemie', // new mic
	'logolink'			=> 'Skocz do strony joomlaXplorer (nowe okno)', // new mic

	// list
	"nameheader"		=> "Nazwa",
	"sizeheader"		=> "Rozmiar",
	"typeheader"		=> "Typ",
	"modifheader"		=> "Zmodyfikowano",
	"permheader"		=> "Prawa",
	"actionheader"		=> "Akcje",
	"pathheader"		=> "Ścieżka",

	// buttons
	"btncancel"			=> "Anuluj",
	"btnsave"			=> "Zapisz",
	"btnchange"			=> "Zmień",
	"btnreset"			=> "Resetuj",
	"btnclose"			=> "Zamknij",
	"btnreopen"			=> "Otwórz ponownie",
	"btncreate"			=> "Utwórz",
	"btnsearch"			=> "Szukaj",
	"btnupload"			=> "Upload",
	"btncopy"			=> "Kopiuj",
	"btnmove"			=> "Przenieś",
	"btnlogin"			=> "Zaloguj",
	"btnlogout"			=> "Wyloguj",
	"btnadd"			=> "Dodaj",
	"btnedit"			=> "Edytuj",
	"btnremove"			=> "Usuń",
	"btndiff"			=> "Porównaj",

	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'			=> 'Zmień nazwę',
	'confirm_delete_file'	=> 'Na pewno chcesz usunąć ten plik?<br />%s',
	'success_delete_file'	=> 'Element(y) zostały poprawnie usunięte.',
	'success_rename_file'	=> 'Nazwa katalogu/pliku %s została zmieniona na %s.',

	// actions
	"actdir"			=> "Katalog",
	"actperms"			=> "Zmiana praw",
	"actedit"			=> "Edycja pliku",
	"actsearchresults"	=> "Wyniki szukania",
	"actcopyitems"		=> "Kopiowanie element(ów)",
	"actcopyfrom"		=> "Kopiowanie z /%s do /%s ",
	"actmoveitems"		=> "Przenoszenie element(ów)",
	"actmovefrom"		=> "Przenoszenie z /%s do /%s ",
	"actlogin"			=> "Zaloguj",
	"actloginheader"	=> "Zaloguj się aby używać menedżera plików",
	"actadmin"			=> "Administracja",
	"actchpwd"			=> "Zmiana hasła",
	"actusers"			=> "Użytkownicy",
	"actarchive"		=> "Archiwizacja element(ów)",
	"actupload"			=> "Upload plik(ów)",

	// misc
	"miscitems"			=> "Element(y)",
	"miscfree"			=> "Wolny",
	"miscusername"		=> "Nazwa użytkownika",
	"miscpassword"		=> "Hasło",
	"miscoldpass"		=> "Poprzednie hasło",
	"miscnewpass"		=> "Nowe hasło",
	"miscconfpass"		=> "Potwierdź hasło",
	"miscconfnewpass"	=> "Potwierdź nowe hasło",
	"miscchpass"		=> "Zmień hasło",
	"mischomedir"		=> "Katalog domowy",
	"mischomeurl"		=> "Domowy adres URL",
	"miscshowhidden"	=> "Pokazuj elementy ukryte",
	"mischidepattern"	=> "Maska elementów ukrytych",
	"miscperms"			=> "Uprawnienia",
	"miscuseritems"		=> "(nazwa, katalog domowy, pokazywanie ukrytych elementów, uprawnienia, aktywność)",
	"miscadduser"		=> "dodaj użytkownika",
	"miscedituser"		=> "edycja użytkownika '%s'",
	"miscactive"		=> "Aktywny",
	"misclang"			=> "Język",
	"miscnoresult"		=> "Brak rezultatów.",
	"miscsubdirs"		=> "Przeszukaj podkatalogi",
	"miscpermnames"		=> array("Tylko przeglądanie","Modyfikacja","Zmiana hasła","Modyfikacja i zmiana hasła","Administrator"),
	"miscyesno"			=> array("Tak","Nie","T","N"),
	"miscchmod"			=> array("Właściciel", "Grupa", "Pozostali"),
	'misccontent'		=> "Zawartość pliku",

	// from here all new by mic
	'miscowner'			=> 'Właściciel',
	'miscownerdesc'		=> '<strong>Opis:</strong><br />Użytkownik (UID) /<br />Grupa (GID)<br />Prawa:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "Informacje o systemie",
	'sisysteminfo'		=> 'Informacja o systemie',
	'sibuilton'			=> 'System operacyjny',
	'sidbversion'		=> 'Wersja bazy danych',
	'siphpversion'		=> 'Wersja PHP',
	'siphpupdate'		=> 'INFORMACJA: <span style="color:red">Używana przez Ciebie wersja PHP <strong>nie</strong> jest aktualna!</span><br />Jeśli chcesz aby wszystkie funkcje i dodatki Mambo działały poprawnie,<br />musisz używać minimum wersji <strong>PHP 4.3</strong>!',
	'siwebserver'		=> 'Serwer web',
	'siwebsphpif'		=> 'Serwer web - interfejs PHP',
	'simamboversion'	=> 'Wersja eXtplorer\'a',
	'siuseragent'		=> 'Wersja przeglądarki',
	'sirelevantsettings'	=> 'Ważne ustawienia PHP',
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
	'sidisabledfuncs'	=> 'Nieaktywne funkcje',
	'sieditor'			=> 'Edytor WYSIWYG',
	'siconfigfile'		=> 'Plik konfiguracyjny',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'Informacje o PHP',
	'sipermissions'		=> 'Prawa',
	'sidirperms'		=> 'Prawa katalogu',
	'sidirpermsmess'	=> 'Aby zapewnić poprawne działanie wszystkich funkcji eXtplorer\'a, następujące katalogi powinny mieć ustawione prawa do zapisu [chmod 0777]',
	'sionoff'			=> array( 'Wł', 'Wył' ),

	'extract_warning'	=> "Czy na pewno chcesz rozpakować ten plik tutaj?<br />Może to spowodować nadpisanie istniejących plików!",
	'extract_success'	=> "Rozpakowanie powiodło się",
	'extract_failure'	=> "Rozpakowanie nie powiodło się",

	'overwrite_files'	=> 'Nadpisać istniejące pliki?',
	"viewlink"			=> "Podgląd",
	"actview"			=> "Pokaż źródło pliku",

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Ustawić dla wszystkich podkatalogów?',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'		=> 'Sprawdź ostatnią wersję',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'		=>	'Zmień nazwę katalogu lub pliku...',
	'newname'			=>	'Nowa nazwa',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'			=>	'Powrócić do katalogu po zapisaniu?',
	'line'				=> 	'Linia',
	'column'			=>	'Kolumna',
	'wordwrap'			=>	'Zawijanie wierszy: (tylko IE)',
	'copyfile'			=>	'Skopiuj plik pod tą nazwą',

	// Bookmarks
	'quick_jump'				=> 'Szybki skok do',
	'already_bookmarked'		=> 'Dla tego katalogu już istnieje zakładka',
	'bookmark_was_added'		=> 'Katalog został dodany do zakładek.',
	'not_a_bookmark'			=> 'Katalog nie jest zakładką.',
	'bookmark_was_removed'		=> 'Katalog został usunięty z zakładek.',
	'bookmarkfile_not_writable'	=> "Nie powiodło się dodanie do zakładek %s.\n Plik zakładek '%s' \nnie ma ustawionych praw do zapisu.",

	'lbl_add_bookmark'		=> 'Dodaj katalog jako zakładkę',
	'lbl_remove_bookmark'	=> 'Usuń katalog z listy zakładek',

	'enter_alias_name'		=> 'Wpisz alias dla zakładki',

	'normal_compression'	=> 'kompresja normalna (normal)',
	'good_compression'		=> 'kompresja dobra (good)',
	'best_compression'		=> 'kompresja najlepsza (best)',
	'no_compression'		=> 'brak kompresji',

	'creating_archive'	=> 'Tworzenie archiwum...',
	'processed_x_files'	=> 'Przetworzono %s z %s plików',

	'ftp_header'			=> 'Lokalna autoryzacja FTP',
	'ftp_login_lbl'			=> 'Proszę podać dane dostępowe do serwera FTP',
	'ftp_login_name'		=> 'Nazwa użytkownika',
	'ftp_login_pass'		=> 'Hasło',
	'ftp_hostname_port'		=> 'Serwer i port FTP<br />(port opcjonalnie)',
	'ftp_login_check'		=> 'Sprawdzanie połączenia FTP...',
	'ftp_connection_failed'	=> "Nie można połączyć się z serwerem FTP.<br />Proszę sprawdzić, czy serwer FTP jest aktywny na podanym hoście.",
	'ftp_login_failed'		=> "Nie można zalogować się do serwera FTP.<br />Proszę zweryfikować poprawność nazwy użytkownika i hasła i spróbować ponownie.",

	'switch_file_mode'	=> 'Aktualny tryb: <strong>%s</strong>. Możesz przełączyć się do trybu %s.',
	'symlink_target'	=> 'Punkt docelowy linku symbolicznego',

	"permchange"		=> "Zmiana uprawnień (chmod) powiodła się:",
	"savefile"			=> "Plik został zapisany.",
	"moveitem"			=> "Przenoszenie powiodło się.",
	"copyitem"			=> "Kopiowanie powiodło się.",
	'archive_name' 		=> 'Nazwa pliku archiwum',
	'archive_saveToDir'	=> 'Zapisz archiwum do katalogu',

	'editor_simple'				=> 'Tryb edytora: prosty',
	'editor_syntaxhighlight'	=> 'Tryb edytora: wyróżnianie składni',

	'newlink'			=> 'Nowy plik/katalog',
	'show_directories'	=> 'Pokaż katalogi',
	'actlogin_success'	=> 'Użytkownik został zalogowany!',
	'actlogin_failure'	=> 'Nieprawidłowy login bądź hasło. Spróbuj ponownie',
	'directory_tree'	=> 'Drzewko katalogów',
	'browsing_directory'	=> 'Przeglądany katalog',
	'filter_grid'		=> 'Filtr',
	'paging_page'		=> 'Strona',
	'paging_of_X'		=> 'z {0}',
	'paging_firstpage'	=> 'Pierwsza strona',
	'paging_lastpage'	=> 'Ostatnia strona',
	'paging_nextpage'	=> 'Następna strona',
	'paging_prevpage'	=> 'Poprzednia strona',

	'paging_info'				=> 'Wyświetlane elementy: {0} - {1} z {2}',
	'paging_noitems'			=> 'Brak elementów do wyświetlenia',
	'aboutlink'					=> 'O...',
	'password_warning_title'	=> 'Ważne - zmień swoje hasło!',
	'password_warning_text'		=> 'Konto użytkownika do którego właśnie się zalogowałeś (admin z hasłem admin) odpowiada domyślnym ustawieniom systemu. To sprawia, że potencjalnie każdy może zalogować się do Twojego konta administratora. Aby naprawić ten problem, zmień hasło administratora na swoje prywatne hasło!',
	'change_password_success'	=> 'Hasło zostało zmienione.',
	'success'					=> 'Sukces',
	'failure'					=> 'Błąd',
	'dialog_title'				=> 'Okno dialogowe',
	'upload_processing'			=> 'Upload plików, proszę czekać...',
	'upload_completed'			=> 'Upload plików powiódł się!',
	'acttransfer'				=> 'Transfer z innego serwera',
	'transfer_processing'		=> 'Transfer plików serwer-do-serwera, proszę czekać...',
	'transfer_completed'		=> 'Zakończono transfer!',
	'max_file_size'				=> 'Maksymalny rozmiar pliku',
	'max_post_size'				=> 'Maksymalny limit uploadu',
	'done'						=> 'Zakończono.',
	'permissions_processing'	=> 'Trwa zastosowywanie uprawnień, proszę czekać...',
	'archive_created'			=> 'Plik archiwum został utworzony.',
	'save_processing'			=> 'Zapis pliku...',
	'current_user'				=> 'Skrypt aktualnie jest wykonywany z prawami następującego użytkownika:',
	'your_version'				=> 'Twoja wersja',
	'search_processing'			=> 'Wyszukiwanie, proszę czekać...',
	'url_to_file'				=> 'Adres URL pliku',
	'file'						=> 'Plik'
);
?>