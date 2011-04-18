<?php
// $Id: english.php 96 2008-02-03 18:13:22Z soeren $
// English Language Module for v2.3 (translated by the QuiX project)
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Eroare(i)",
	"message"			=> "Mesaj(e)",
	"back"			=> "Inapoi",

	// root
	"home"			=> "Directorul radacina nu exista, verificati setarile.",
	"abovehome"		=> "Directorul curent nu poate fi inaintea directorului radacina.",
	"targetabovehome"	=> "Directorul tinta nu poate fi inaintea directorului radacina.",

	// exist
	"direxist"		=> "Drectorul nu exista.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "Fisierul nu exista.",
	"itemdoesexist"		=> "Elementul exista deja.",
	"itemexist"		=> "Elementul nu exista.",
	"targetexist"		=> "Directoul tinra nu exista.",
	"targetdoesexist"	=> "Elementul tinta deja exista .",

	// open
	"opendir"		=> "Nu pot deschide directorul.",
	"readdir"		=> "Nu pot citi directorul.",

	// access
	"accessdir"		=> "Nu aveti dreptul de a accesa acest director.",
	"accessfile"		=> "Nu aveti dreptul de a accesa acest fisier.",
	"accessitem"		=> "Nu aveti dreptul de a accesa acest element.",
	"accessfunc"		=> "Nu aveti dreptul de a folosi aceasta functie.",
	"accesstarget"		=> "Nu aveti dreptul de a accesa directorul tinta.",

	// actions
	"permread"		=> "Schimbarea permisiunilor esuata.",
	"permchange"		=> "Eroare CHMOD (de obicei datorita proprietatii unui fisier - ex. daca utilizatorul HTTP ('wwwrun' or 'nobody') si utilizatorul FTP sunt diferiti)",
	"openfile"		=> "Deschiderea fisierului esuata.",
	"savefile"		=> "Salvarea fisierului esuata.",
	"createfile"		=> "Crearea fisierului esuata.",
	"createdir"		=> "Crearea directorului esuata.",
	"uploadfile"		=> "Urcarea fisierului esuata.",
	"copyitem"		=> "Copiere esuata.",
	"moveitem"		=> "Mutare esuata.",
	"delitem"		=> "Stergere esuata.",
	"chpass"		=> "Schimbarea parolei esuata.",
	"deluser"		=> "Stergerea utilizatorului esuata.",
	"adduser"		=> "Adaugarea utilizatorului esuata.",
	"saveuser"		=> "Salvarea utilizatorului esuata.",
	"searchnothing"		=> "Trebuie sa introduceti ceva pt. cautare .",

	// misc
	"miscnofunc"		=> "Functie inaccesibila.",
	"miscfilesize"		=> "Fisierul depaseste marimea maxima.",
	"miscfilepart"		=> "Fisier urcat partial.",
	"miscnoname"		=> "Trebuie sa introduceti un nume.",
	"miscselitems"		=> "Nu ati selectat nici un element.",
	"miscdelitems"		=> "Sunteti sigur ca doriti stergerea acestui(acestor) {0}elemnt(e)?",
	"miscdeluser"		=> "Sunteti sigur ca doriti stergerea utilizatorului '{0}'?",
	"miscnopassdiff"	=> "Parola noua nu difera de cea curenta.",
	"miscnopassmatch"	=> "Parolele nu sunt identice.",
	"miscfieldmissed"	=> "Ati omis un camp important.",
	"miscnouserpass"	=> "Utilizator sau parola incorect(a).",
	"miscselfremove"	=> "Nu va puteti sterge pe dvs..",
	"miscuserexist"		=> "Utilizatorul deja exista.",
	"miscnofinduser"	=> "Nu gasesc utilizatorul.",
	"extract_noarchive" => "Fisierul nu este o arhiva pt. a putea fi extras.",
	"extract_unknowntype" => "Arhiva necunoscuta",

	'chmod_none_not_allowed' => 'Schimbarea Permisiunilor pt <none> nepermisa',
	'archive_dir_notexists' => 'Directorul Salveaza-Acolo specificat nu exista.',
	'archive_dir_unwritable' => 'Specificati un director tinta cu permisiune de scriere pt. salvarea arhivei.',
	'archive_creation_failed' => 'Salvarea fisierului Arhiva esuata'

);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Schimbarea permisiunilor",
	"editlink"		=> "Editare",
	"downlink"		=> "Descarcare",
	"uplink"		=> "Sus",
	"homelink"		=> "Acasa",
	"reloadlink"		=> "Reincarcare",
	"copylink"		=> "Copiere",
	"movelink"		=> "Mutare",
	"dellink"		=> "Stergere",
	"comprlink"		=> "Arhivare",
	"adminlink"		=> "Admin",
	"logoutlink"		=> "Deconectare",
	"uploadlink"		=> "Incarcare",
	"searchlink"		=> "Cautare",
	"extractlink"	=> "Extragere Arhiva",
	'chmodlink'		=> 'Schimbare drepturile (chmod) (Director/Fisie(e))', // new mic
	'mossysinfolink'	=> 'Sistemul de Informatii eXtplorer (eXtplorer, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Mergi la site-ul joomlaXplorer (fereastra noua)', // new mic

	// list
	"nameheader"		=> "Nume",
	"sizeheader"		=> "Marime",
	"typeheader"		=> "Tip",
	"modifheader"		=> "Modificat",
	"permheader"		=> "Permisiuni",
	"actionheader"		=> "Actiuni",
	"pathheader"		=> "Cale",

	// buttons
	"btncancel"		=> "Anuleaza",
	"btnsave"		=> "Salvare",
	"btnchange"		=> "Schimba",
	"btnreset"		=> "Reset",
	"btnclose"		=> "Inchide",
	"btncreate"		=> "Creaza",
	"btnsearch"		=> "Cauta",
	"btnupload"		=> "Urca",
	"btncopy"		=> "Copiaza",
	"btnmove"		=> "Muta",
	"btnlogin"		=> "Conectare",
	"btnlogout"		=> "Deconectare",
	"btnadd"		=> "Adaugare",
	"btnedit"		=> "Editare",
	"btnremove"		=> "Stergere",

	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Redenumire',
	'confirm_delete_file' => 'Sigur stergeti acest fisier ? <br />%s',
	'success_delete_file' => 'Element(e) sters(e).',
	'success_rename_file' => 'Directorul/fisierul %s a fost redenumit in %s.',

	// actions
	"actdir"		=> "Director",
	"actperms"		=> "Schimba permisiuni",
	"actedit"		=> "Editeaza fisierul",
	"actsearchresults"	=> "Cauta rezultate",
	"actcopyitems"		=> "Copiaza elementul(e)",
	"actcopyfrom"		=> "Copiaza din /%s in /%s ",
	"actmoveitems"		=> "Muta elementul(e)",
	"actmovefrom"		=> "Muta din /%s in /%s ",
	"actlogin"		=> "Conectare",
	"actloginheader"	=> "Conectare pt. folosirea eXtplorer",
	"actadmin"		=> "Administrare",
	"actchpwd"		=> "Schimba pasrola",
	"actusers"		=> "Utilizatori",
	"actarchive"		=> "Element(e) Arciva",
	"actupload"		=> "Urcare fisier(e)",

	// misc
	"miscitems"		=> "Element(e)",
	"miscfree"		=> "Liber",
	"miscusername"		=> "Utilizator",
	"miscpassword"		=> "Parola",
	"miscoldpass"		=> "Parola veche",
	"miscnewpass"		=> "Parola noua",
	"miscconfpass"		=> "Confirma parola",
	"miscconfnewpass"	=> "Confirma noua parola",
	"miscchpass"		=> "Schimba parola",
	"mischomedir"		=> "Directorul radacina",
	"mischomeurl"		=> "URL radacina",
	"miscshowhidden"	=> "Afiseaza elementele ascunse",
	"mischidepattern"	=> "Sablon de ascundere",
	"miscperms"		=> "Permisiuni",
	"miscuseritems"		=> "(nume, director radacina, afiseaza elementele ascunse, permisiuni, activ)",
	"miscadduser"		=> "adauga utilizator",
	"miscedituser"		=> "editeaza utilizatorul '%s'",
	"miscactive"		=> "Activ",
	"misclang"		=> "Limba",
	"miscnoresult"		=> "Nici un rezultate.",
	"miscsubdirs"		=> "Cauta subdirectori",
	"miscpermnames"		=> array("Doar vedere","Modifica","Schimba parola","Modifica si Schimba parola",
					"Administrator"),
	"miscyesno"		=> array("DA","Nu","D","N"),
	"miscchmod"		=> array("Proprietar", "Grup", "Public"),

	// from here all new by mic
	'miscowner'			=> 'Proprietar',
	'miscownerdesc'		=> '<strong>Descriere:</strong><br />Utilizator (UID) /<br />Grup (GID)<br />Drepturi curente:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "Informatii sistem eXtplorer",
	'sisysteminfo'		=> 'Informatii Sistem',
	'sibuilton'			=> 'Sistem de Operare',
	'sidbversion'		=> 'Versiunea Bazei de Date(MySQL)',
	'siphpversion'		=> 'Versiunea PHP',
	'siphpupdate'		=> 'INFORMATII: <span style="color: red;">Versiunea PHP folosita<strong>nu</strong>este actualizata!</span><br />Pentru garantarea tuturor facilitatilor Mambo si a pachetelor auxiliare,<br />aveti nevoie de cel putin <strong>PHP.Versiunea 4.3</strong>!',
	'siwebserver'		=> 'Server Web',
	'siwebsphpif'		=> 'Server Web - Interfata PHP',
	'simamboversion'	=> 'Versiunea eXtplorer',
	'siuseragent'		=> 'Versiunea Browser-ului',
	'sirelevantsettings' => 'Setari PHP Importante',
	'sisafemode'		=> 'Mod de siguranta',
	'sibasedir'			=> 'Deschide directorul radacina',
	'sidisplayerrors'	=> 'Erori PHP',
	'sishortopentags'	=> 'Deschide Tag-uri scurte',
	'sifileuploads'		=> 'Urcare Fisiere',
	'simagicquotes'		=> 'Ghilimele Magice',
	'siregglobals'		=> 'Inregistreaza variabile globale',
	'sioutputbuf'		=> 'Afiseaza Buffer-ul',
	'sisesssavepath'	=> 'Calea de salvare a sesiunilor',
	'sisessautostart'	=> 'Pornire automata a sesiunilor',
	'sixmlenabled'		=> 'XML activat',
	'sizlibenabled'		=> 'ZLIB activat',
	'sidisabledfuncs'	=> 'Functii dezactivate',
	'sieditor'			=> 'WYSIWYG Editor',
	'siconfigfile'		=> 'Fisier configurare',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'Informatii PHP',
	'sipermissions'		=> 'Permisiuni',
	'sidirperms'		=> 'Permisiunile Directorului',
	'sidirpermsmess'	=> 'Pt. a fi sigur(a) ca toate functiile si facilitatile eXtplorer sunt executate corect, urmatori directori ttrebuie sa aiba permisiune de scriere [chmod 0777]',
	'sionoff'			=> array( 'Pornit', 'Oprit' ),

	'extract_warning' => "Sigur doriti extragerea fisierului ? Aici?<br />Aceasta va suprascrie fisiere existente daca nu exista atentie!",
	'extract_success' => "Extragere reusita",
	'extract_failure' => "Extragere nereusita",

	'overwrite_files' => 'Suprascrie fisierul(e) existent(e)?',
	"viewlink"		=> "Vizualizare",
	"actview"		=> "Afiseaza suersa fisierului",

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Accesez in adancime subdirectorii?',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Caut ultima versiune',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Redenumesc un director sau fisier...',
	'newname'		=>	'Nume nou',

	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Revin in director dupa salvare?',
	'line'		=> 	'Linie',
	'column'	=>	'Coloana',
	'wordwrap'	=>	'Wordwrap: (doar in IE)',
	'copyfile'	=>	'Copiez continutul in acess fisier',

	// Bookmarks
	'quick_jump' => 'Salt rapid la',
	'already_bookmarked' => 'Director deja marcat',
	'bookmark_was_added' => 'Director adaugat in lista de marcaje.',
	'not_a_bookmark' => 'Directorul nu este un marcaj.',
	'bookmark_was_removed' => 'Director scos din lista de marcaje.',
	'bookmarkfile_not_writable' => "Eroare de marcare a %s.\n Fisierul de marcaje '%s' \nnu permite scrierea.",

	'lbl_add_bookmark' => 'Adauga acest Director ca Marcaj',
	'lbl_remove_bookmark' => 'Sterge acest Director din lista de marcaje',

	'enter_alias_name' => 'Introduceti un alias pentru acest marcaj',

	'normal_compression' => 'compresie normala',
	'good_compression' => 'compresie buna',
	'best_compression' => 'cea mai buna compresie',
	'no_compression' => 'fara compresie',

	'creating_archive' => 'Creea Arhiva...',
	'processed_x_files' => 'Am procesat %s din %s fisiere',

	'ftp_header' => 'Autentificare FTP locala',
	'ftp_login_lbl' => 'Introduceti datele de autentificare pentru serverul FTP',
	'ftp_login_name' => 'Utilizator FTP',
	'ftp_login_pass' => 'Parola FTP',
	'ftp_hostname_port' => 'Server FTP Hostname si Port <br />(Portul este optional)',
	'ftp_login_check' => 'Verificarea conexiunii FTP...',
	'ftp_connection_failed' => "Serverul FTP nu poate fi contactat. \nVerificati daca serverul FTP ruleaza pe serverul dvs.",
	'ftp_login_failed' => "Autentificarea FTP esuata. Verificati utilizatorul si parola si reincercati.",

	'switch_file_mode' => 'Mod curent: <strong>%s</strong>. Puteti schimba in modul %s.',
	'symlink_target' => 'Tinta Legaturii Simbolice',

	"permchange"		=> "CHMOD Reusit:",
	"savefile"		=> "Fisier salvat.",
	"moveitem"		=> "Mutare reusita.",
	"copyitem"		=> "Copiere reusita.",
	'archive_name' 	=> 'Numele fisierului Arhiva',
	'archive_saveToDir' 	=> 'Salveaza Arhiva in acest director',

	'editor_simple'	=> 'Editor in Mod Simplu',
	'editor_syntaxhighlight'	=> 'Mod Sintaxa-Evidentiata',

	'newlink'	=> 'Fisier/Director nou',
	'show_directories' => 'Afiseaza Directorii',
	'actlogin_success' => 'Conectare reusita!',
	'actlogin_failure' => 'Conectare esuata, reincercati.',
	'directory_tree' => 'Structura de directori',
	'browsing_directory' => 'Navigare in Directori',
	'filter_grid' => 'Filtru',
	'paging_page' => 'Pagina',
	'paging_of_X' => 'din {0}',
	'paging_firstpage' => 'Prima Pagina',
	'paging_lastpage' => 'Ultima Pagina',
	'paging_nextpage' => 'Urmatoarea Pagina',
	'paging_prevpage' => 'Pagina anterioara',

	'paging_info' => 'Afisez Elementele {0} - {1} din {2}',
	'paging_noitems' => 'Nici un element de afisat',
	'aboutlink' => 'Despre...',
	'password_warning_title' => 'Important - Schimbati parola!',
	'password_warning_text' => 'Utilizatorul cu are sunteti autentificat (admin cu parola: admin) corespunde setarilor implicite eXtplorer. Instalarea eXtplorer este vulneravila intrusilor si trebuie imediat sa fixati aceasta lucru!',
	'change_password_success' => 'Parola schimbata!',
	'success' => 'Succes',
	'failure' => 'Eroare',
	'dialog_title' => 'Site de dialog',
	'upload_processing' => 'Procesez Urcarea, asteptati...',
	'upload_completed' => 'Urcare reusita!',
	'acttransfer' => 'Transfer de la alt Server',
	'transfer_processing' => 'Procesez un transfer Server-Server, asteptati...',
	'transfer_completed' => 'Transfer terminat!',
	'max_file_size' => 'Marime maxima fisier',
	'max_post_size' => 'Limita de urcare maxima',
	'done' => 'Am terminat.',
	'permissions_processing' => 'Aplic permisiunile, asteptati...',
	'archive_created' => 'Arhiva creata!',
	'save_processing' => 'Salvez Fisierul...',
	'current_user' => 'Scruptul ruleaza momentan cu permisiunile utilizatorului:',
	'your_version' => 'Versiunea Dvs.',
	'search_processing' => 'Caut, asteptati...',
	'url_to_file' => 'Adresa URL a fisierului',
	'file' => 'Fisier'

);
?>
