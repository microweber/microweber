<?php
/***************************************************************************
 *
 *             Encode Explorer
 *
 *             Author / Autor : Marek Rei (marek ät siineiolekala dot net)
 *
 *             Version / Versioon : 6.3
 *
 *             Last change / Viimati muudetud: 23.09.2011
 *
 *             Homepage / Koduleht: encode-explorer.siineiolekala.net
 *
 *
 *             NB!: Comments are in english.
 *                  Comments needed for configuring are in both estonian and english.
 *                  If you change anything, save with UTF-8! Otherwise you may
 *                  encounter problems, especially when displaying images.
 *                  
 *             NB!: Kommentaarid on inglise keeles.
 *                  Seadistamiseks vajalikud kommentaarid on eesti ja inglise keeles.
 *                  Kui midagi muudate, salvestage UTF-8 formaati! Vastasel juhul
 *                  võivad probleemid tekkida, eriti piltide kuvamisega.
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This is free software and it's distributed under GPL Licence.
 *
 *   Encode Explorer is written in the hopes that it can be useful to people.
 *   It has NO WARRANTY and when you use it, the author is not responsible
 *   for how it works (or doesn't).
 *   
 *   The icon images are designed by Mark James (http://www.famfamfam.com) 
 *   and distributed under the Creative Commons Attribution 3.0 License.
 *
 ***************************************************************************/

/***************************************************************************/
/*   SIIN ON SEADED                                                        */
/*                                                                         */
/*   HERE ARE THE SETTINGS FOR CONFIGURATION                               */
/***************************************************************************/

//
// Algväärtustame muutujad. Ära muuda.
//
// Initialising variables. Don't change these.
//

$_CONFIG = array();
$_ERROR = "";
$_START_TIME = microtime(TRUE);

/* 
 * GENERAL SETTINGS
 */

//
// Vali sobiv keel. Allpool on näha võimalikud valikud. Vaikimisi: en
//
// Choose a language. See below in the language section for options.
// Default: $_CONFIG['lang'] = "en";
//
$_CONFIG['lang'] = "en";

//
// Kuva pildifailidele eelvaated. Vaikimisi: true
// Levinumad pildifailide tüübid on toetatud (jpeg, png, gif).
// Pdf failid on samuti toetatud kuid ImageMagick peab olema paigaldatud.
//
// Display thumbnails when hovering over image entries in the list.
// Common image types are supported (jpeg, png, gif).
// Pdf files are also supported but require ImageMagick to be installed.
// Default: $_CONFIG['thumbnails'] = true;
//
$_CONFIG['thumbnails'] = true;

//
// Eelvaadete maksimumsuurused pikslites. Vaikimisi: 200, 200
//
// Maximum sizes of the thumbnails.
// Default: $_CONFIG['thumbnails_width'] = 200;
// Default: $_CONFIG['thumbnails_height'] = 200;
//
$_CONFIG['thumbnails_width'] = 300;
$_CONFIG['thumbnails_height'] = 300;

//
// Mobillidele mõeldud kasutajaliides. true/false
// Vaikimisi: true
// 
// Mobile interface enabled. true/false
// Default: $_CONFIG['mobile_enabled'] = true;
//
$_CONFIG['mobile_enabled'] = true;

//
// Mobiilidele mõeldud kasutajaliides avaneb automaatselt. true/false
// Vaikimisi: false
//
// Mobile interface as the default setting. true/false
// Default: $_CONFIG['mobile_default'] = false;
//
$_CONFIG['mobile_default'] = false;

/*
 * USER INTERFACE
 */

//
// Kas failid avatakse uues aknas? true/false
//
// Will the files be opened in a new window? true/false 
// Default: $_CONFIG['open_in_new_window'] = false;
//
$_CONFIG['open_in_new_window'] = false;

//
// Kui sügavalt alamkataloogidest suurust näitav script faile otsib? 
// Määra see nullist suuremaks, kui soovid kogu kasutatud ruumi suurust kuvada.
// Vaikimisi: 0
//
// How deep in subfolders will the script search for files? 
// Set it larger than 0 to display the total used space.
// Default: $_CONFIG['calculate_space_level'] = 0;
//
$_CONFIG['calculate_space_level'] = 0;

//
// Kas kuvatakse lehe päis? true/false
// Vaikimisi: true;
//
// Will the page header be displayed? 0=no, 1=yes. 
// Default: $_CONFIG['show_top'] = true;
//
$_CONFIG['show_top'] = true;

//
// Veebilehe pealkiri
//
// The title for the page
// Default: $_CONFIG['main_title'] = "Encode Explorer";
//
$_CONFIG['main_title'] = "Encode Explorer";

//
// Pealkirjad, mida kuvatakse lehe päises, suvalises järjekorras.
//
// The secondary page titles, randomly selected and displayed under the main header.
// For example: $_CONFIG['secondary_titles'] = array("Secondary title", "&ldquo;Secondary title with quotes&rdquo;");
// Default: $_CONFIG['secondary_titles'] = array();
//
$_CONFIG['secondary_titles'] = array();

//
// Kuva asukoht kataloogipuus. true/false
// Vaikimisi: true
//
// Display breadcrumbs (relative path of the location).
// Default: $_CONFIG['show_path'] = true;
//
$_CONFIG['show_path'] = true;

//
// Kuva lehe laadimise aega. true/false
// Vaikimisi: false
// 
// Display the time it took to load the page.
// Default: $_CONFIG['show_load_time'] = true;
//
$_CONFIG['show_load_time'] = true;

//
// Formaat faili muutmise aja kuvamiseks.
//
// The time format for the "last changed" column.
// Default: $_CONFIG['time_format'] = "d.m.y H:i:s";
//
$_CONFIG['time_format'] = "d.m.y H:i:s";

//
// Kodeering, mida lehel kasutatakse. 
// Tuleb panna sobivaks, kui täpitähtedega tekib probleeme. Vaikimisi: UTF-8
//
// Charset. Use the one that suits for you. 
// Default: $_CONFIG['charset'] = "UTF-8";
//
$_CONFIG['charset'] = "UTF-8";

/*
* PERMISSIONS
*/

//
// Kaustade varjamine. Kaustade nimed mida lehel ei kuvata.
// Näiteks: CONFIG['hidden_dirs'] = array("ikoonid", "kaustanimi", "teinekaust");
//
// The array of folder names that will be hidden from the list.
// Default: $_CONFIG['hidden_dirs'] = array();
//
$_CONFIG['hidden_dirs'] = array();

//
// Failide varjamine. Failide nimed mida lehel ei kuvata.
// NB! Märgitud nimega failid ja kaustad varjatakse kõigis alamkaustades.
//
// Filenames that will be hidden from the list.
// Default: $_CONFIG['hidden_files'] = array(".ftpquota", "index.php", "index.php~", ".htaccess", ".htpasswd");
//
$_CONFIG['hidden_files'] = array(".ftpquota", "index.php", "index.php~", ".htaccess", ".htpasswd");

//
// Määra kas lehe nägemiseks peab sisse logima.
// 'false' tähendab, et leht on avalik
// 'true' tähendab, et kasutaja peab sisestama parooli (vaata allpool).
//
// Whether authentication is required to see the contents of the page.
// If set to false, the page is public.
// If set to true, you should specify some users as well (see below).
// Important: This only prevents people from seeing the list.
// They will still be able to access the files with a direct link.
// Default: $_CONFIG['require_login'] = false;
//
$_CONFIG['require_login'] = false;

//
// Kasutajanimed ja paroolid, lehele ligipääsu piiramiseks.
// Näiteks: $_CONFIG['users'] = array(array("user1", "pass1"), array("user2", "pass2"));
// Võimalik lehte kaitsta ka ainult üldise parooliga.
// Näiteks: $_CONFIG['users'] = array(array(null, "pass"));
// Kui ühtegi kasutajat märgitud ei ole, siis parooli ei küsita.
//
// Usernames and passwords for restricting access to the page.
// The format is: array(username, password, status)
// Status can be either "user" or "admin". User can read the page, admin can upload and delete.
// For example: $_CONFIG['users'] = array(array("username1", "password1", "user"), array("username2", "password2", "admin"));
// You can also keep require_login=false and specify an admin. 
// That way everyone can see the page but username and password are needed for uploading.
// For example: $_CONFIG['users'] = array(array("username", "password", "admin"));
// Default: $_CONFIG['users'] = array();
//
$_CONFIG['users'] = array();

//
// Seaded uploadimiseks, uute kaustade loomiseks ja kustutamiseks.
// Valikud kehtivad ainult andmin kontode jaoks, tavakasutajatel pole need kunagi lubatud.
//
// Permissions for uploading, creating new directories and deleting.
// They only apply to admin accounts, regular users can never perform these operations.
// Default:
// $_CONFIG['upload_enable'] = true;
// $_CONFIG['newdir_enable'] = true;
// $_CONFIG['delete_enable'] = false;
//
$_CONFIG['upload_enable'] = true;
$_CONFIG['newdir_enable'] = true;
$_CONFIG['delete_enable'] = false;

/*
 * UPLOADING
 */

//
// Nimekiri kaustadest kuhu on lubatud uploadida ja uusi kaustu luua.
// Näiteks: $_CONFIG['upload_dirs'] = array("./myuploaddir1/", "./mydir/upload2/");
// Kausta asukoht peab olema määratud põhikausta suhtes, algama "./" ja lõppema "/" märgiga.
// Kõik kaustad märgitute all on automaatselt kaasa arvatud.
// Kui nimekiri on tühi (vaikimisi), siis on kõikidesse kaustadesse failide lisamine lubatud.
//
// List of directories where users are allowed to upload. 
// For example: $_CONFIG['upload_dirs'] = array("./myuploaddir1/", "./mydir/upload2/");
// The path should be relative to the main directory, start with "./" and end with "/".
// All the directories below the marked ones are automatically included as well.
// If the list is empty (default), all directories are open for uploads, given that the password has been set.
// Default: $_CONFIG['upload_dirs'] = array();
//
$_CONFIG['upload_dirs'] = array();

//
// MIME failitüübid mis on uploadimiseks lubatud.
// Näiteks: $_CONFIG['upload_allow_type'] = array("image/png", "image/gif", "image/jpeg");
//
// MIME type that are allowed to be uploaded.
// For example, to only allow uploading of common image types, you could use:
// $_CONFIG['upload_allow_type'] = array("image/png", "image/gif", "image/jpeg");
// Default: $_CONFIG['upload_allow_type'] = array();
//
$_CONFIG['upload_allow_type'] = array();

//
// Uploadimiseks keelatud faililaiendid
//
// File extensions that are not allowed for uploading.
// For example: $_CONFIG['upload_reject_extension'] = array("php", "html", "htm");
// Default: $_CONFIG['upload_reject_extension'] = array();
//
$_CONFIG['upload_reject_extension'] = array("php");

/*
 * LOGGING
 */

//
// Failide lisamisest teatamise e-maili aadress.
// Kui määratud, siis saadetakse sellele aadressile e-mail iga kord kui keegi lisab uue faili või kausta.
//
// Upload notification e-mail.
// If set, an e-mail will be sent every time someone uploads a file or creates a new dirctory.
// Default: $_CONFIG['upload_email'] = "";
//
$_CONFIG['upload_email'] = "";

//
// Logifail. Kui määratud, siis kirjutatakse kaustade ja failide avamise kohta logi faili.
// Näiteks: $_CONFIG['log_file'] = ".log.txt";
//
// Logfile name. If set, a log line will be written there whenever a directory or file is accessed.
// For example: $_CONFIG['log_file'] = ".log.txt";
// Default: $_CONFIG['log_file'] = "";
//
$_CONFIG['log_file'] = "";

/*
 * SYSTEM
 */

//
// Algkataloogi suhteline aadress. Reeglina ei ole vaja muuta. 
// Kasutage ainult suhtelisi alamkatalooge!
// Vaikimisi: .
//
// The starting directory. Normally no need to change this.
// Use only relative subdirectories! 
// For example: $_CONFIG['starting_dir'] = "./mysubdir/";
// Default: $_CONFIG['starting_dir'] = ".";
//
$_CONFIG['starting_dir'] = ".";

//
// Asukoht serveris. Tavaliselt ei ole vaja siia midagi panna kuna script leiab ise õige asukoha. 
// Mõnes serveris tuleb piirangute tõttu see aadress ise teistsuguseks määrata.
// See fail peaks asuma serveris aadressil [AADRESS]/index.php
// Aadress võib olla näiteks "/www/data/www.minudomeen.ee/minunimi"
//
// Location in the server. Usually this does not have to be set manually.
// Default: $_CONFIG['basedir'] = "";
//
$_CONFIG['basedir'] = "";

//
// Suured failid. Kui sul on failiruumis väga suured failid (>4GB), siis on see vajalik
// faili suuruse õigeks määramiseks. Vaikimisi: false
//
// Big files. If you have some very big files (>4GB), enable this for correct
// file size calculation.
// Default: $_CONFIG['large_files'] = false;
//
$_CONFIG['large_files'] = false;

//
// Küpsise/sessiooni nimi. 
// Anna sellele originaalne väärtus kui soovid samas ruumis kasutada mitut koopiat
// ning ei taha, et toimuks andmete jagamist nende vahel.
// Väärtus tohib sisaldada ainult tähti ja numbreid. Näiteks: MYSESSION1
//
// The session name, which is used as a cookie name. 
// Change this to something original if you have multiple copies in the same space
// and wish to keep their authentication separate. 
// The value can contain only letters and numbers. For example: MYSESSION1
// More info at: http://www.php.net/manual/en/function.session-name.php
// Default: $_CONFIG['session_name'] = "";
//
$_CONFIG['session_name'] = "";

/***************************************************************************/
/*   TÕLKED                                                                */
/*                                                                         */
/*   TRANSLATIONS.                                                         */
/***************************************************************************/

$_TRANSLATIONS = array();

// Albanian
$_TRANSLATIONS["al"] = array(
	"file_name" => "Emri Skedarit",
	"size" => "Madhësia",
	"last_changed" => "Ndryshuar",
	"total_used_space" => "Memorija e përdorur total",
	"free_space" => "Memorija e lirë",
	"password" => "Fjalëkalimi",
	"upload" => "Ngarko skedarë",
	"failed_upload" => "Ngarkimi i skedarit dështoi!",
	"failed_move" => "Lëvizja e skedarit në udhëzuesin e saktë deshtoi!",
	"wrong_password" => "Fjalëkalimi i Gabuar!!",
	"make_directory" => "New dir",
	"new_dir_failed" => "Failed to create directory",
	"chmod_dir_failed" => "Failed to change directory rights",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// Dutch
$_TRANSLATIONS["nl"] = array(
	"file_name" => "Bestandsnaam",
	"size" => "Omvang",
	"last_changed" => "Laatst gewijzigd",
	"total_used_space" => "Totaal gebruikte ruimte",
	"free_space" => "Beschikbaar",
	"password" => "Wachtwoord",
	"upload" => "Upload",
	"failed_upload" => "Fout bij uploaden van bestand!",
	"failed_move" => "Fout bij het verplaatsen van tijdelijk uploadbestand!",
	"wrong_password" => "Fout wachtwoord!",
	"make_directory" => "Nieuwe folder",
	"new_dir_failed" => "Fout bij aanmaken folder!",
	"chmod_dir_failed" => "Rechten konden niet gewijzigd worden!",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// English
$_TRANSLATIONS["en"] = array(
	"file_name" => "File name",
	"size" => "Size",
	"last_changed" => "Last changed",
	"total_used_space" => "Total used space",
	"free_space" => "Free space",
	"password" => "Password",
	"upload" => "Upload",
	"failed_upload" => "Failed to upload the file!",
	"failed_move" => "Failed to move the file into the right directory!",
	"wrong_password" => "Wrong password",
	"make_directory" => "New dir",
	"new_dir_failed" => "Failed to create directory",
	"chmod_dir_failed" => "Failed to change directory rights",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root",
	"log_file_permission_error" => "The script does not have permissions to write the log file.",
	"upload_not_allowed" => "The script configuration does not allow uploading in this directory.",
	"upload_dir_not_writable" => "This directory does not have write permissions.",
	"mobile_version" => "Mobile view",
	"standard_version" => "Standard view",
	"page_load_time" => "Page loaded in %.2f ms",
	"wrong_pass" => "Wrong username or password",
	"username" => "Username",
	"log_in" => "Log in",
	"upload_type_not_allowed" => "This file type is not allowed for uploading.",
	"del" => "Del", // short for Delete
	"log_out" => "Log out"
);

// Estonian
$_TRANSLATIONS["et"] = array(
	"file_name" => "Faili nimi",
	"size" => "Suurus",
	"last_changed" => "Viimati muudetud",
	"total_used_space" => "Kokku kasutatud",
	"free_space" => "Vaba ruumi",
	"password" => "Parool",
	"upload" => "Uploadi",
	"failed_upload" => "Faili ei &otilde;nnestunud serverisse laadida!",
	"failed_move" => "Faili ei &otilde;nnestunud &otilde;igesse kausta liigutada!",
	"wrong_password" => "Vale parool",
	"make_directory" => "Uus kaust",
	"new_dir_failed" => "Kausta loomine ebaõnnestus",
	"chmod_dir_failed" => "Kausta õiguste muutmine ebaõnnestus",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Asukoht",
	"root" => "Peakaust"
);

// Finnish
$_TRANSLATIONS["fi"] = array(
	"file_name" => "Tiedoston nimi",
	"size" => "Koko",
	"last_changed" => "Muokattu",
	"total_used_space" => "Yhteenlaskettu koko",
	"free_space" => "Vapaa tila",
	"password" => "Salasana",
	"upload" => "Lisää tiedosto",
	"failed_upload" => "Tiedoston lisäys epäonnistui!",
	"failed_move" => "Tiedoston siirto kansioon epäonnistui!",
	"wrong_password" => "Väärä salasana",
	"make_directory" => "Uusi kansio",
	"new_dir_failed" => "Uuden kansion luonti epäonnistui!",
	"chmod_dir_failed" => "Kansion käyttäjäoikeuksien muuttaminen epäonnistui!",
	"unable_to_read_dir" => "Kansion sisältöä ei voi lukea.",
	"location" => "Paikka",
	"root" => "Juurihakemisto",
	"log_file_permission_error" => "Ohjelman ei ole sallittu kirjoittaa lokiin.",
	"upload_not_allowed" => "Ohjelman asetukset eivät salli tiedoston lisäämistä tähän kansioon.",
	"upload_dir_not_writable" => "Kansioon tallentaminen epäonnistui.",
	"mobile_version" => "Mobiilinäkymä",
	"standard_version" => "Tavallinen näkymä",
	"page_load_time" => "Sivu ladattu %.2f ms:ssa",
	"wrong_pass" => "Väärä käyttäjätunnus tai salasana",
	"username" => "Käyttäjätunnus",
	"log_in" => "Kirjaudu sisään",
	"log_out" => "Kirjaudu ulos",
	"upload_type_not_allowed" => "Tämän tiedostotyypin lisääminen on estetty.",
	"del" => "Poista"
);

// French
$_TRANSLATIONS["fr"] = array(
	"file_name" => "Nom de fichier",
	"size" => "Taille",
	"last_changed" => "Ajout&eacute;",
	"total_used_space" => "Espace total utilis&eacute;",
	"free_space" => "Espace libre",
	"password" => "Mot de passe",
	"upload" => "Envoyer un fichier",
	"failed_upload" => "Erreur lors de l'envoi",
	"failed_move" => "Erreur lors du changement de dossier",
	"wrong_password" => "Mauvais mot de passe",
	"make_directory" => "Nouveau dossier",
	"new_dir_failed" => "Erreur lors de la cr&eacute;ation du dossier",
	"chmod_dir_failed" => "Impossible de changer les permissions du dossier",
	"unable_to_read_dir" => "Impossible de lire le dossier",
	"location" => "Localisation",
	"root" => "Racine"
);

// German
$_TRANSLATIONS["de"] = array(
	"file_name" => "Dateiname",
	"size" => "Größe",
	"last_changed" => "Letzte Änderung",
	"total_used_space" => "Benutzter Speicher",
	"free_space" => "Freier Speicher",
	"password" => "Passwort",
	"upload" => "Upload",
	"failed_upload" => "Upload ist fehlgeschlagen!",
	"failed_move" => "Verschieben der Datei ist fehlgeschlagen!",
	"wrong_password" => "Falsches Passwort",
	"make_directory" => "Neuer Ordner",
	"new_dir_failed" => "Erstellen des Ordners fehlgeschlagen",
	"chmod_dir_failed" => "Veränderung der Zugriffsrechte des Ordners fehlgeschlagen",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// Greek
$_TRANSLATIONS["el"] = array(
	"file_name" => "Όνομα αρχείου",
	"size" => "Μέγεθος",
	"last_changed" => "Τροποποιημένο",
	"total_used_space" => "Χρησιμοποιημένος χώρος",
	"free_space" => "Ελεύθερος χώρος",
	"password" => "Εισάγεται κωδικό",
	"upload" => "Φόρτωση",
	"failed_upload" => "Αποτυχία φόρτωσης αρχείου!",
	"failed_move" => "Αποτυχία μεταφοράς αρχείου στον κατάλληλο φάκελο!",
	"wrong_password" => "Λάθος κωδικός",
	"make_directory" => "Δημιουργία νέου φακέλου",
	"new_dir_failed" => "Αποτυχία δημιουργίας νέου φακέλου",
	"chmod_dir_failed" => "Αποτυχία τροποποίησης δικαιωμάτων φακέλου",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// Hungarian
$_TRANSLATIONS["hu"] = array(
	"file_name" => "Fájl név",
	"size" => "Méret",
	"last_changed" => "Utolsó módosítás",
	"total_used_space" => "Összes elfoglalt terület",
	"free_space" => "Szabad terület",
	"password" => "Jelszó",
	"upload" => "Feltöltés",
	"failed_upload" => "A fájl feltöltése nem sikerült!",
	"failed_move" => "A fájl mozgatása nem sikerült!",
	"wrong_password" => "Hibás jelszó",
	"make_directory" => "Új mappa",
	"new_dir_failed" => "A mappa létrehozása nem sikerült",
	"chmod_dir_failed" => "A mappa jogainak megváltoztatása nem sikerült",
	"unable_to_read_dir" => "A mappa nem olvasható",
	"location" => "Hely",
	"root" => "Gyökér",
	"log_file_permission_error" => "A log fájl írása jogosultsági okok miatt nem sikerült.",
	"upload_not_allowed" => "Ebbe a mappába a feltöltés nem engedélyezett.",
	"upload_dir_not_writable" => "A mappa nem írható.",
	"mobile_version" => "Mobil nézet",
	"standard_version" => "Web nézet",
	"page_load_time" => "Letöltési id? %.2f ms",
	"wrong_pass" => "Rossz felhasználónév vagy jelszó",
	"username" => "Felhasználónév",
	"log_in" => "Belépés",
	"upload_type_not_allowed" => "A fájltípus feltöltése tiltott."
);

// Italian
$_TRANSLATIONS["it"] = array(
	"file_name" => "Nome file",
	"size" => "Dimensione",
	"last_changed" => "Ultima modifica",
	"total_used_space" => "Totale spazio usato",
	"free_space" => "Spazio disponibile",
	"password" => "Parola chiave",
	"upload" => "Caricamento file",
	"failed_upload" => "Caricamento del file fallito!",
	"failed_move" => "Spostamento del file nella cartella fallito!",
	"wrong_password" => "Password sbagliata",
	"make_directory" => "Nuova cartella",
	"new_dir_failed" => "Creazione cartella fallita!",
	"chmod_dir_failed" => "Modifica dei permessi della cartella fallita!",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// Norwegian
$_TRANSLATIONS["no"] = array(
	"file_name" => "Navn",
	"size" => "Størrelse",
	"last_changed" => "Endret",
	"total_used_space" => "Brukt plass",
	"free_space" => "Resterende plass",
	"password" => "Passord",
	"upload" => "Last opp",
	"failed_upload" => "Opplasting gikk galt",
	"failed_move" => "Kunne ikke flytte objektet",
	"wrong_password" => "Feil passord",
	"make_directory" => "Ny mappe",
	"new_dir_failed" => "Kunne ikke lage ny mappe",
	"chmod_dir_failed" => "Kunne ikke endre rettigheter",
	"unable_to_read_dir" => "Kunne ikke lese mappen",
	"location" => "Område",
	"root" => "Rot"
);

//Polish
$_TRANSLATIONS["pl"] = array(
	"file_name" => "Nazwa Pliku",
	"size" => "Rozmiar",
	"last_changed" => "Data Zmiany",
	"total_used_space" => "Total used space",
	"free_space" => "Wolnego obszaru",
	"password" => "Haslo",
	"upload" => "Przeslij",
	"failed_upload" => "Przeslanie pliku nie powiodlo sie",
	"failed_move" => "Przenosienie pliku nie powidlo sie!",
	"wrong_password" => "Niepoprawne haslo",
	"make_directory" => "Nowy folder",
	"new_dir_failed" => "Blad podczas tworzenia nowego foldera",
	"chmod_dir_failed" => "Blad podczas zmiany uprawnienia foldera",
	"unable_to_read_dir" => "Odczytanie foldera nie powiodlo sie",
	"location" => "Miejsce",
	"root" => "Root",
	"log_file_permission_error" => "Brak uprawnien aby utowrzyc dziennik dzialan.",
	"upload_not_allowed" => "Konfiguracja zabrania przeslanie pliku do tego foldera.",
	"upload_dir_not_writable" => "Nie mozna zapisac pliku do tego foldera.",
	"mobile_version" => "Wersja Mobile",
	"standard_version" => "Widok standardowy",
	"page_load_time" => "Zaladowano w %.2f ms",
	"wrong_pass" => "Nie poprawna nazwa uzytkownika lub hasla",
	"username" => "Uzytkownik",
	"log_in" => "Zaloguj sie",
	"upload_type_not_allowed" => "Ten rodazaj pliku jest zabrioniony."
);

// Portuguese (Brazil)
$_TRANSLATIONS["pt_BR"] = array(
	"file_name" => "Nome do arquivo",
	"size" => "Tamanho",
	"last_changed" => "Modificado em",
	"total_used_space" => "Total de espaço utilizado",
	"free_space" => "Espaço livre",
	"password" => "Senha",
	"upload" => "Enviar",
	"failed_upload" => "Falha ao enviar o arquivo!",
	"failed_move" => "Falha ao mover o arquivo para o diretório correto!",
	"wrong_password" => "Senha errada",
	"make_directory" => "Nova pasta",
	"new_dir_failed" => "Falha ao criar diretório",
	"chmod_dir_failed" => "Falha ao mudar os privilégios do diretório",
	"unable_to_read_dir" => "Não foi possível ler o diretório",
	"location" => "Localização",
	"root" => "Raíz",
	"log_file_permission_error" => "O script não tem permissão para escrever o arquivo de log.",
	"upload_not_allowed" => "A configuração do script não permite envios neste diretório.",
	"upload_dir_not_writable" => "Não há permissão para escrita neste diretório.",
	"mobile_version" => "Versão Móvel",
	"standard_version" => "Versão Padrão",
	"page_load_time" => "Página carregada em %.2f ms",
	"wrong_pass" => "Nome de usuário ou senha errados",
	"username" => "Nome de Usuário",
	"log_in" => "Log in",
	"upload_type_not_allowed" => "Não é permitido envio de arquivos deste tipo."
);

// Romanian
$_TRANSLATIONS["ro"] = array(
	"file_name" => "Nume fisier",
	"size" => "Marime",
	"last_changed" => "Ultima modificare",
	"total_used_space" => "Spatiu total utilizat",
	"free_space" => "Spatiu disponibil",
	"password" => "Parola",
	"upload" => "Incarcare fisier",
	"failed_upload" => "Incarcarea fisierului a esuat!",
	"failed_move" => "Mutarea fisierului in alt director a esuat!",
	"wrong_password" => "Parol gresita!",
	"make_directory" => "Director nou",
	"new_dir_failed" => "Eroare la crearea directorului",
	"chmod_dir_failed" => "Eroare la modificarea drepturilor pe director",
	"unable_to_read_dir" => "Nu s-a putut citi directorul",
	"location" => "Locatie",
	"root" => "Root"
);

// Russian
$_TRANSLATIONS["ru"] = array(
    "file_name" => "Имя файла",
    "size" => "Размер",
    "last_changed" => "Последнее изменение",
    "total_used_space" => "Всего использовано",
    "free_space" => "Свободно",
    "password" => "Пароль",
    "upload" => "Загрузка",
    "failed_upload" => "Не удалось загрузить файл!",
    "failed_move" => "Не удалось переместить файл в нужную папку!",
    "wrong_password" => "Неверный пароль",
    "make_directory" => "Новая папка",
    "new_dir_failed" => "Не удалось создать папку",
    "chmod_dir_failed" => "Не удалось изменить права доступа к папке",
    "unable_to_read_dir" => "Не возможно прочитать папку",
    "location" => "Расположение",
    "root" => "Корневая папка",
    "log_file_permission_error" => "Скрипт не имеет прав для записи лога файла.",
    "upload_not_allowed" => "Загрузка в эту папку запрещена в настройках скрипта",
    "upload_dir_not_writable" => "В эту папку запрещена запись",
    "mobile_version" => "Мобильный вид",
    "standard_version" => "Обычный вид",
    "page_load_time" => "Страница загружена за %.2f мс.",
    "wrong_pass" => "Неверное имя пользователя или пароль",
    "username" => "Имя пользователя",
    "log_in" => "Войти",
    "upload_type_not_allowed" => "Этот тип файла запрещено загружать"
);

// Slovensky
$_TRANSLATIONS["sk"] = array(
	"file_name" => "Meno súboru",
	"size" => "Ve?kos?",
	"last_changed" => "Posledná zmena",
	"total_used_space" => "Použité miesto celkom",
	"free_space" => "Vo?né miesto",
	"password" => "Heslo",
	"upload" => "Nahranie súborov",
	"failed_upload" => "Chyba nahrávania súboru!",
	"failed_move" => "Nepodarilo sa presunú? súbor do vybraného adresára!",
	"wrong_password" => "Neplatné heslo!",
	"make_directory" => "Nový prie?inok",
	"new_dir_failed" => "Nepodarilo sa vytvori? adresár!",
	"chmod_dir_failed" => "Nepodarilo sa zmeni? práva adresára!",
	"unable_to_read_dir" => "Nemôžem ?íta? adresár",
	"location" => "Umiestnenie",
	"root" => "Domov"
);

// Spanish
$_TRANSLATIONS["es"] = array(
	"file_name" => "Nombre de archivo",
	"size" => "Medida",
	"last_changed" => "Ultima modificaciÃ³n",
	"total_used_space" => "Total espacio usado",
	"free_space" => "Espacio libre",
	"password" => "ContraseÃ±a",
	"upload" => "Subir el archivo",
	"failed_upload" => "Error al subir el archivo!",
	"failed_move" => "Error al mover el archivo al directorio seleccionado!",
	"wrong_password" => "ContraseÃ±a incorrecta",
	"make_directory" => "New dir",
	"new_dir_failed" => "Failed to create directory",
	"chmod_dir_failed" => "Failed to change directory rights",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

// Swedish
$_TRANSLATIONS["sv"] = array(
	"file_name" => "Filnamn",
	"size" => "Storlek",
	"last_changed" => "Senast andrad",
	"total_used_space" => "Totalt upptaget utrymme",
	"free_space" => "Ledigt utrymme",
	"password" => "Losenord",
	"upload" => "Ladda upp",
	"failed_upload" => "Fel vid uppladdning av fil!",
	"failed_move" => "Fel vid flytt av fil till mapp!",
	"wrong_password" => "Fel losenord",
	"make_directory" => "Ny mapp",
	"new_dir_failed" => "Fel vid skapande av mapp",
	"chmod_dir_failed" => "Fel vid andring av mappens egenskaper",
	"unable_to_read_dir" => "Kan inte lasa den filen",
	"location" => "Plats",
	"root" => "Hem"
);

// Turkish
$_TRANSLATIONS["tr"] = array(
	"file_name" => "Dosya ismi",
	"size" => "Boyut",
	"last_changed" => "gecmis",
	"total_used_space" => "Toplam dosya boyutu",
	"free_space" => "Bos alan",
	"password" => "Sifre",
	"upload" => "Yükleyen",
	"failed_upload" => "Hatali dosya yüklemesi!",
	"failed_move" => "Hatali dosya tasimasi!",
	"wrong_password" => "Yeniden sifre",
	"make_directory" => "Yeni dosya",
	"new_dir_failed" => "Dosya olusturalamadi",
	"chmod_dir_failed" => "Dosya ayari deqistirelemedi",
	"unable_to_read_dir" => "Unable to read directory",
	"location" => "Location",
	"root" => "Root"
);

/***************************************************************************/
/*   CSS KUJUNDUSE MUUTMISEKS                                              */
/*                                                                         */
/*   CSS FOR TWEAKING THE DESIGN                                           */
/***************************************************************************/


function css()
{
?>
<style type="text/css">

/* General styles */

BODY {
	background-color:#FFFFFF;
	font-family:Verdana;
	font-size:small;
}

A {
	color: #000000;
	text-decoration: none;
}

A:hover {
	text-decoration: underline;
}

#top {
	width:100%;
	padding-bottom: 20px;
}

#top a span, #top a:hover, #top a span:hover{
	color:#68a9d2;
	font-weight:bold;
	text-align:center;
	font-size:large;
}

#top a {
	display:block;
	padding:20px 0 0 0;
}

#top span {
	display:block;
}

div.subtitle{
	width:80%;
	margin: 0 auto;
	color:#68a9d2;
	text-align:center;
}

#frame {
	border: 1px solid #CDD2D6;
	text-align:left;
	position: relative;
	margin: 0 auto;
	max-width:680px;
	overflow:hidden;
}

#error {
	max-width:450px;
	background-color:#FFE4E1;
	color:#000000;
	padding:7pt;
	position: relative;
	margin: 10pt auto;
	text-align:center;
	border: 1px dotted #CDD2D6;
}

input {
	border: 1px solid #CDD2D6;
}

.bar{
	width:100%;
	clear:both;
	height:1px;
}

/* File list */

table.table {
	width:100%;
	border-collapse: collapse; 
}

table.table td{
	padding:3px;
}

table.table tr.row.two {
	background-color:#fcfdfe;
}

table.table tr.row.one {
	background-color:#f8f9fa;
}

table.table tr.row td.icon {
	width:25px;
	padding-top:3px;
	padding-bottom:1px;
}

table.table td.del {
	width:25px;
}

table.table tr.row td.size {
	width: 100px; 
	text-align: right;
}

table.table tr.row td.changed {
	width: 150px;
	text-align: center;
}

table.table tr.header img {
	vertical-align:bottom;
}

table img{
	border:0;
}

/* Info area */ 

#info {
	color:#000000;
	font-family:Verdana;
	max-width:680px;
	position: relative;
	margin: 0 auto;
	text-align:center;
}

/* Thumbnail area */

#thumb {
	position:absolute;
	border: 1px solid #CDD2D6;
	background:#f8f9fa;
	display:none;
	padding:3px;
}

#thumb img {
	display:block;
}

/* Login bar (at the bottom of the page) */
#login_bar {
	margin: 0 auto;
	margin-top:2px;
	max-width:680px;
}

#login_bar input.submit{
	float:right;
}

/* Upload area */

#upload {
	margin: 0 auto;
	margin-top:2px;
	max-width:680px;
}

#upload #password_container {
	margin-right:20px;
}

#upload #newdir_container, #upload #password_container {
	float:left;
}

#upload #upload_container{
	float:right;
}

#upload input.upload_dirname, #upload input.upload_password{
	width:140px;
}

#upload input.upload_file{
	font-size:small;
}

/* Breadcrumbs */

div.breadcrumbs {
	display:block;
	padding:1px 3px;
	color:#cccccc;
	font-size:x-small;
}

div.breadcrumbs a{
	display:inline-block;
	color:#cccccc;
	padding:2px 0;
	font-size:small;
}

/* Login area */

#login {
	max-width:280px;
	text-align:right;
	margin:15px auto 50px auto;
}

#login div {
	display:block;
	width:100%;
	margin-top:5px;
}

#login label{
	width: 120px;
	text-align: right;
}

/* Mobile interface */

body.mobile #frame, body.mobile #info, body.mobile #upload {
	max-width:none;
}

body.mobile {
	font-size:medium;
}

body.mobile a.item {
	display:block;
	padding:10px 0;
}

body.mobile a.item span.size {
	float:right;
	margin-left:10px;
}

body.mobile table.table {
	margin-bottom:30px;
}

body.mobile table.table tr td {
	border-top: 1px solid #CDD2D6;
}

body.mobile table.table tr.last td {
	border-bottom: 1px solid #CDD2D6;
}

body.mobile #top {
	padding-bottom:3px;
}

body.mobile #top a {
	padding-top:3px;
}

body.mobile #upload #password_container, body.mobile #upload #upload_container, body.mobile #upload #newdir_container {
	float:none;
	margin-top:5px;
}

body.mobile #upload input.upload_dirname, body.mobile #upload input.upload_password{
	width:240px;
}

body.mobile #upload {
	margin-bottom:15px;
}

</style>

<?php
}

/***************************************************************************/
/*   PILTIDE KOODID                                                        */
/*   Saad neid ise oma piltidest juurde genereerida base64 konverteriga    */
/*   Näiteks siin: http://www.motobit.com/util/base64-decoder-encoder.asp  */
/*   Või siin: http://www.greywyvern.com/code/php/binary2base64            */
/*   Või kasuta lihtsalt PHP base64_encode() funktsiooni                   */
/*                                                                         */
/*   IMAGE CODES IN BASE64                                                 */
/*   You can generate your own with a converter                            */
/*   Like here: http://www.motobit.com/util/base64-decoder-encoder.asp     */
/*   Or here: http://www.greywyvern.com/code/php/binary2base64             */
/*   Or just use PHP base64_encode() function                              */
/***************************************************************************/


$_IMAGES = array();

$_IMAGES["arrow_down"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAABbSURBVCjPY/jPgB8yDCkFB/7v+r/5/+r/
i/7P+N/3DYuC7V93/d//fydQ0Zz/9eexKFgtsejLiv8b/8/8X/WtUBGrGyZLdH6f8r/sW64cTkdW
SRS+zpQbgiEJAI4UCqdRg1A6AAAAAElFTkSuQmCC";
$_IMAGES["arrow_up"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAABbSURBVCjPY/jPgB8yDDkFmyVWv14kh1PB
eoll31f/n/ytUw6rgtUSi76s+L/x/8z/Vd8KFbEomPt16f/1/1f+X/S/7X/qeSwK+v63/K/6X/g/
83/S/5hvQywkAdMGCdCoabZeAAAAAElFTkSuQmCC";
$_IMAGES["del"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJdSURBVDjLpZP7S1NhGMf9W7YfogSJboSE
UVCY8zJ31trcps6zTI9bLGJpjp1hmkGNxVz4Q6ildtXKXzJNbJRaRmrXoeWx8tJOTWptnrNryre5
YCYuI3rh+8vL+/m8PA/PkwIg5X+y5mJWrxfOUBXm91QZM6UluUmthntHqplxUml2lciF6wrmdHri
I0Wx3xw2hAediLwZRWRkCPzdDswaSvGqkGCfq8VEUsEyPF1O8Qu3O7A09RbRvjuIttsRbT6HHzeb
sDjcB4/JgFFlNv9MnkmsEszodIIY7Oaut2OJcSF68Qx8dgv8tmqEL1gQaaARtp5A+N4NzB0lMXxo
n/uxbI8gIYjB9HytGYuusfiPIQcN71kjgnW6VeFOkgh3XcHLvAwMSDPohOADdYQJdF1FtLMZPmsl
vhZJk2ahkgRvq4HHUoWHRDqTEDDl2mDkfheiDgt8pw340/EocuClCuFvboQzb0cwIZgki4KhzlaE
6w0InipbVzBfqoK/qRH94i0rgokSFeO11iBkp8EdV8cfJo0yD75aE2ZNRvSJ0lZKcBXLaUYmQrCz
DT6tDN5SyRqYlWeDLZAg0H4JQ+Jt6M3atNLE10VSwQsN4Z6r0CBwqzXesHmV+BeoyAUri8EyMfi2
FowXS5dhd7doo2DVII0V5BAjigP89GEVAtda8b2ehodU4rNaAW+dGfzlFkyo89GTlcrHYCLpKD+V
7yeeHNzLjkp24Uu1Ed6G8/F8qjqGRzlbl2H2dzjpMg1KdwsHxOlmJ7GTeZC/nesXbeZ6c9OYnuxU
c3fmBuFft/Ff8xMd0s65SXIb/gAAAABJRU5ErkJggg==";


$_IMAGES["archive"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJmSURBVDjLhZNNS5RRGIav8+HMvDNO5ZhO
YqXQF2FgVNRCCKM2EbQ1ok2b/AG16F/0ge5qUwitghbWooikiIhI3AQVFER+VsyMztg7vuecp4U2
ORH5wLM5cK7n5r65lYgAoJTaDhQBw/9nAfgiIgEAEWENcjiO43KSJN45J//aOI5lZGTkBtALaBFp
AhxNksRXq1Wp1WqNrVQqUiqVZH5+XpxzMjs7K6Ojow2Imri9Z1Dntjwo2dObZr7vpKXFoDVAwFpN
vR6za9du+vr6KRQKrKysEEJgbGzs5vDw8DX1/N6Rrx0HOrpfvOqnWs0CCgQkaJTJEkIAHENDFygW
i01mWGuP2Vw+KnT3djPUM0eLzZO4L6ikztQz6Dl2i4ePxgk+IYoylMtlQgg45+js7FyFKKUk/llh
evplg9zTtR8RC0AmSlGtrGCMxVqF9x5j/gRlRQLZbIbt3fvW4lwmpS0IhCA4FwgEjDForVFK/Ta9
oYDa8jdmpt83Hndu86DaEQkgHgkBrXXT5QaA4FuiqI3itl4IPzHWk7G5NQUBQgISUEoBYIxpVlAr
le9+fCbntFY6qM2Z4BOWazFzS13UPrwjlUqzuFhtXF9NZZ0Cn7hLc59mrly+/uPQ+OO3T+6PP8W7
OpH1fJ6cpLU1hUsSphcqRLlNFHK6GXD84nuvlCoDS1FrgZn28+T5zom933jzeoKpyZeY9oPceOJp
z1e4erbtLw/WTTBZWVpaVNmcYuvWDk6eOsPAwCCLseHOpCOfNg0vgACg1rXxSL1enzDGZAC9QSOD
9345nU4PrgfsWKvzRp9/jwcWfgF7VEKXfHY5kwAAAABJRU5ErkJggg==";
$_IMAGES["audio"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIvSURBVDjLjZPLaxNRFIeriP+AO7Gg7nRX
qo1ogoKCK0Fbig8QuxKhPop04SYLNYqlKpEmQlDBRRcFFWlBqqJYLVpbq6ktaRo0aWmamUxmJpN5
ZvKoP++9mmlqWuzAt7jc+X2Hcy6nDkAdhXxbCI2Epv+wlbDeyVUJGm3bzpVKpcVyuYyVIPcIBAL3
qiXVgiYaNgwDpmk6qKoKRVEgCAKT8DyPYDDoSCrhdYHrO9qzkdOQvp+E+O04hC+tED63gBs+QiDn
hQgTWJYFWiQUCv2RUEH/g4YNXwdcT/VEJ6xkF8zEDRixq1CnriD94SikH08gikJNS2wmVLDwybON
H3GbNt8DY+YMrDk/tGkvhOFmKPE+pxVJkpDJZMBx3JJAHN+/MTPq8amxdtj8fWjhwzB+diH5ag9y
8V6QubDhUYmmaWwesiwvCYRRtyv9ca9oc37kk3egTbbBiPowP+iGOHGT0A1h7BrS43ehiXHous5E
joCEx3IzF6FMnYMcPgs95iOCW1DDXqTfnEBqsBnRR9shTvYibyhsiBRHwL13dabe7r797uHOx3Kk
m1T2IDfhhTRyAfMDh5Aauox8Ns5aKRQKDNrSsiHSZ6SHoq1i9nkDuNfHkHi2D9loHwtSisUig4ZX
FaSG2pB8cZBUPY+ila0JV1Mj8F/a3DHbfwDq3Mtlb12R/EuNoKN10ylLmv612h6swKIj+CvZRQZk
0ou1hMm/OtveKkE9laxhnSvQ1a//DV9axd5NSHlCAAAAAElFTkSuQmCC";
$_IMAGES["code"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHtSURBVDjLjZM9T9tQFIYpQ5eOMBKlW6eW
IQipa8RfQKQghEAKqZgKFQgmFn5AWyVDCipVQZC2EqBWlEqdO2RCpAssQBRsx1+1ndix8wFvfW6w
cUhQsfTI0j33PD7n+N4uAF2E+/S5RFwG/8Njl24/LyCIOI6j1+v1y0ajgU64cSSTybdBSVAwSMmm
acKyLB/DMKBpGkRRZBJBEJBKpXyJl/yABLTBtm1Uq1X2JsrlMnRdhyRJTFCpVEAfSafTTUlQoFs1
luxBAkoolUqQZbmtJTYTT/AoHInOfpcwtVtkwcSBgrkDGYph+60oisIq4Xm+VfB0+U/P0Lvj3NwP
GfHPTcHMvoyFXwpe7UmQtAqTUCU0D1VVbwTPVk5jY19Fe3ZfQny7CE51WJDXqpjeEUHr45ki9rIq
a4dmQiJfMLItGEs/FcQ2ucbRmdnSYy5vYWyLx/w3EaMfLmBaDpMQvuDJ65PY8Dpnz3wpYmLtApzc
rIAqmfrEgdZH1grY/a36w6Xz0DKD8ES25/niYS6+wWE8mWfByY8cXmYEJFYLkHUHtVqNQcltAvoL
D3v7o/FUHsNvzlnwxfsCEukC/ho3yUHaBN5Buo17Ojtyl+DqrnvQgUtfcC0ZcAdkUeA+ye7eMru9
AUGIJPe4zh509UP/AAfNypi8oj/mAAAAAElFTkSuQmCC";
$_IMAGES["database"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHVSURBVDjLjZPLaiJBFIZNHmJWCeQdMuT1
Mi/gYlARBRUkao+abHUhmhgU0QHtARVxJ0bxhvfGa07Of5Iu21yYFPyLrqrz1f+f6rIRkQ3icca6
ZF39RxesU1VnAVyuVqvJdrvd73Y7+ky8Tk6n87cVYgVcoXixWNByuVSaTqc0Ho+p1+sJpNvtksvl
UhCb3W7/cf/w+BSLxfapVIqSySRlMhnSdZ2GwyHN53OaTCbU7/cFYBgG4RCPx/MKub27+1ur1Xqj
0YjW6zWxCyloNBqUSCSkYDab0WAw+BBJeqLFtQpvGoFqAlAEaZomuc0ocAQnnU7nALiJ3uh8whgn
ttttarVaVCgUpCAUCgnQhMAJ+gG3CsDZa7xh1mw2ZbFSqYgwgsGgbDQhcIWeAHSIoP1pcGeNarUq
gFKpJMLw+/0q72azkYhmPAWIRmM6AGbXc7kc5fN5AXi9XgWACwAguLEAojrfsVGv1yV/sVikcrks
AIfDIYUQHEAoPgLwT3GdzWYNdBfXh3xwApDP5zsqtkoBwuHwaSAQ+OV2u//F43GKRCLEc5ROpwVo
OngvBXj7jU/wwZPPX72DT7RXgDfIT27QEgvfKea9c3m9FsA5IN94zqbw9M9fAEuW+zzj8uLvAAAA
AElFTkSuQmCC";
$_IMAGES["directory"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGrSURBVDjLxZO7ihRBFIa/6u0ZW7GHBUV0
UQQTZzd3QdhMQxOfwMRXEANBMNQX0MzAzFAwEzHwARbNFDdwEd31Mj3X7a6uOr9BtzNjYjKBJ6ni
cP7v3KqcJFaxhBVtZUAK8OHlld2st7Xl3DJPVONP+zEUV4HqL5UDYHr5xvuQAjgl/Qs7TzvOOVAj
xjlC+ePSwe6DfbVegLVuT4r14eTr6zvA8xSAoBLzx6pvj4l+DZIezuVkG9fY2H7YRQIMZIBwycmz
H1/s3F8AapfIPNF3kQk7+kw9PWBy+IZOdg5Ug3mkAATy/t0usovzGeCUWTjCz0B+Sj0ekfdvkZ3a
bBv+U4GaCtJ1iEm6ANQJ6fEzrG/engcKw/wXQvEKxSEKQxRGKE7Izt+DSiwBJMUSm71rguMYhQKr
BygOIRStf4TiFFRBvbRGKiQLWP29yRSHKBTtfdBmHs0BUpgvtgF4yRFR+NUKi0XZcYjCeCG2smkz
LAHkbRBmP0/Uk26O5YnUActBp1GsAI+S5nRJJJal5K1aAMrq0d6Tm9uI6zjyf75dAe6tx/SsWeD/
/o2/Ab6IH3/h25pOAAAAAElFTkSuQmCC";
$_IMAGES["graphics"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH8SURBVDjLjZPLaxNRFIfHLrpx10WbghXx
H7DQx6p14cadiCs31Y2LLizYhdBFWyhYaFUaUxLUQFCxL61E+0gofWGLRUqGqoWp2JpGG8g4ybTJ
JJm86897Ls4QJIm98DED9/6+mXNmjiAIwhlGE6P1P5xjVAEQiqHVlMlkYvl8/rhQKKAUbB92u91W
SkKrlcLJZBK6rptomoZoNApFUbhElmU4HA4u8YzU1PsmWryroxYrF9CBdDqNbDbLr0QikUAsFkM4
HOaCVCoFesjzpwMuaeXuthYcw4rtvG4KKGxAAgrE43FEIhGzlJQWxE/RirQ6i8/T7XjXV2szBawM
8yDdU91GKaqqInQgwf9xCNmoB7LYgZn+Oud0T121KfiXYokqf8X+5jAyR3NQvtzEq96z4os7lhqz
ieW6TxJN3UVg8yEPqzu38P7xRVy+cPoay52qKDhUf0HaWsC3xRvstd3Qvt9mTWtEOPAJf/+L8oKA
fwfLnil43z7Bkusqdr2X4Btvg1+c5fsVBZJ/H9aXbix/2EAouAVx4zVmHl2BtOrkPako2DsIwule
xKhnG/cmfbg+uIbukXkooR/I5XKcioLu+8/QNTyGzqE36OidQNeDJayLe7yZBuUEv8t9iRIcU6Z4
FprZ36fTxknC7GyCBrBY0ECSE4yzAY1+gyH4Ay9cw2Ifwv9mAAAAAElFTkSuQmCC";
$_IMAGES["image"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGWSURBVBgZpcE/a1NhGMbh3/OeN56cKq2D
p6AoCOKmk4uCn8DNycEOIojilr2TaBfRzVnESQR3Bz+FFDoWA2IjtkRqmpyc97k9qYl/IQV7XSaJ
w4g0VlZfP0m13dwepPbuiH85fyhyWCx4/ubxjU6kkdxWHt69VC6XpZlFBAhwJgwJJHAmRKorbj94
ewvoRBrbuykvT5R2/+lLTp05Tp45STmEJYJBMAjByILxYeM9jzr3GCczGpHGYAQhRM6fO8uFy1fJ
QoaUwCKYEcwwC4QQaGUBd36KTDmQ523axTGQmEcIEBORKQfG1ZDxcA/MkBxXwj1ggCQyS9TVAMmZ
iUxJ8Ln/kS+9PmOvcSW+jrao0mmMH5bzHfa+9UGBmciUBJ+2Fmh1h+yTQCXSkJkdCrpd8btIwwEJ
QnaEkOXMk7XaiF8CUxL/JdKQOwb0Ntc5SG9zHXQNd/ZFGsaEeLa2ChjzXQcqZiKNxSL0vR4unVww
MENMCATib0ZdV+QtE41I42geXt1Ze3dlMNZFdw6Ut6CIvKBhkjiM79Pyq1YUmtkKAAAAAElFTkSu
QmCC";
$_IMAGES["presentation"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHeSURBVDjLjZO/i1NBEMc/u+/lBYxiLkgU
7vRstLEUDyxtxV68ykIMWlocaGHrD1DxSAqxNf4t115jo6DYhCRCEsk733s7u2PxkuiRoBkYdmGZ
z3xndsaoKgDGmC3gLBDxbxsA31U1AKCqzCBXsywbO+e8iOgqz7JM2+32W+AiYFX1GGDHOeen06mm
abrwyWSio9FI+/2+ioj2ej3tdDoLiJm+bimAhgBeUe9RmbkrT5wgT97RaDQoioIQAt1ud7/Var1h
+uq+/s9+PLilw+FwqSRgJ1YpexHSKenHF4DFf/uC3b7CydsPsafraO5IkoTxeEwIARGh2WwCYNUJ
AOmHZ5y4eY/a7h4hPcIdHvDz/fMSnjviOCZJEiqVCtVqdfEl8RygHkz9DLZWQzOHisd9OizfckcU
RRhjMMbMm14CQlEC/NfPjPd2CSJQCEEEDWYBsNZijFkaCqu5Ky+blwl5geaOUDg0c8TnNssSClkE
R1GEtXYZcOruI6ILl1AJqATirW02Hr8sFThBVZfklyXMFdQbbDzdXzm78z4Bx7KXTcwdgzs3yizu
zxAhHvVh4avqBzAzaQa4JiIHgGE9C3EcX7ezhVIgeO9/AWGdYO/9EeDNX+t8frbOdk0FHhj8BvUs
fP0TH5dOAAAAAElFTkSuQmCC";
$_IMAGES["spreadsheet"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIpSURBVDjLjZNPSFRRFMZ/9707o0SOOshM
0x/JFtUmisKBooVEEUThsgi3KS0CN0G2lagWEYkSUdsRWgSFG9sVFAW1EIwQqRZiiDOZY804b967
954249hUpB98y/PjO5zzKREBQCm1E0gDPv9XHpgTEQeAiFCDHAmCoBhFkTXGyL8cBIGMjo7eA3YD
nog0ALJRFNlSqSTlcrnulZUVWV5elsXFRTHGyMLCgoyNjdUhanCyV9ayOSeIdTgnOCtY43DWYY3j
9ulxkskkYRjinCOXy40MDAzcZXCyVzZS38MeKRQKf60EZPXSXInL9y+wLZMkCMs0RR28mJ2grSWJ
Eo+lH9/IpNPE43GKxSLOOYwxpFIpAPTWjiaOtZ+gLdFKlJlD8u00xWP8lO/M5+e5efEB18b70Vqj
lMJai++vH8qLqoa+nn4+fJmiNNPCvMzQnIjzZuo1V88Ns3/HAcKKwfd9tNZorYnFYuuAMLDMfJ3m
+fQznr7L0Vk9zGpLmezB4zx++YggqhAFEZ7n4ft+HVQHVMoB5++cJNWaRrQwMjHM9qCLTFcnJJq5
9WSIMLAopQDwfR/P8+oAbaqWK2eGSGxpxVrDnvQ+3s++4tPnj4SewYscUdUgIiilcM41/uXZG9kN
z9h9aa+EYdjg+hnDwHDq+iGsaXwcZ6XhsdZW+FOqFk0B3caYt4Bic3Ja66NerVACOGttBXCbGbbW
rgJW/VbnXbU6e5tMYIH8L54Xq0cq018+AAAAAElFTkSuQmCC";
$_IMAGES["textdocument"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIdSURBVDjLjZO7a5RREMV/9/F9yaLBzQY3
CC7EpBGxU2O0EBG0sxHBUitTWYitYCsiiJL0NvlfgoWSRpGA4IMsm43ZXchmv8e9MxZZN1GD5MCB
W8yce4aZY1QVAGPMaWAacPwfm8A3VRUAVJWhyIUsy7plWcYQgh7GLMt0aWnpNTADWFX9Q2C+LMu4
s7Oj/X5/xF6vp51OR1utloYQtNls6vLy8kjE3Huz9qPIQjcUg/GZenVOokIEiSBBCKUSQ+TFwwa1
Wo2iKBARVlZW3iwuLr7izssPnwZ50DLIoWz9zPT+s/fabrf/GQmY97GIIXGWp28/08si5+oV1jcG
TCSO6nHH2pddYqmkaUq320VECCFQr9cBsBIVBbJcSdXQmK7Q6Qsnq54sj2gBplS896RpSpIkjI2N
jVZitdh7jAOSK6trXcpC2GjlfP1esHD+GDYozjm893jvSZJkXyAWe+ssc6W5G9naLqkaw/pGxBrl
1tVpJCrWWpxzI6GRgOQKCv2BYHPl5uUatROeSsVy7eIkU9UUiYoxBgDnHNbagw4U6yAWwpmphNvX
T6HAhAZuLNRx1iDDWzHG/L6ZEbyJVLa2c54/PgsKgyzw5MHcqKC9nROK/aaDvwN4KYS7j959DHk2
PtuYnBUBFUEVVBQRgzX7I/wNM7RmgEshhFXAcDSI9/6KHQZKAYkxDgA5SnOMcReI5kCcG8M42yM6
iMDmL261eaOOnqrOAAAAAElFTkSuQmCC";
$_IMAGES["unknown"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAC4SURBVCjPdZFbDsIgEEWnrsMm7oGGfZro
hxvU+Iq1TyjU60Bf1pac4Yc5YS4ZAtGWBMk/drQBOVwJlZrWYkLhsB8UV9K0BUrPGy9cWbng2CtE
EUmLGppPjRwpbixUKHBiZRS0p+ZGhvs4irNEvWD8heHpbsyDXznPhYFOyTjJc13olIqzZCHBouE0
FRMUjA+s1gTjaRgVFpqRwC8mfoXPPEVPS7LbRaJL2y7bOifRCTEli3U7BMWgLzKlW/CuebZPAAAA
AElFTkSuQmCC";
$_IMAGES["vectorgraphics"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIWSURBVDjLhZNPbxJRFMWhRrYu3NrExIUb
dzWte6M7d34Eo2Hjxm8gwZUxIYEARUKAWgwbV0BpxAW11bpQFrCoCVEMDplhQMow782/enx3WsiU
0jrJ2bz7zu+9e95cHwAfSXzXhFaEVv+j60JLM58HsGIYxsi27SPHcbBIoo5oNBrxQryAVTJPJhPo
uu6q0+mgVquh0WhAlmUX0uv1EIvFZpCp2U8A2sA5h2maYIyhUChA0zTU63UoiuICaJ0OSSaTx5B5
AJnpqqVSCbmNTWxVt9FsNtHv98+05GYyD7AsC5VKBZvFd/j2k6Etc6gjHfLgELKiujeRJGkxQGSA
YDCIx8+eI/ORIb3Lkf0sWvmio9aaoC2NoQ7+QFUHCwFr5XIZ8bfvhZFhq2XgU9tEb2Tj99DCgcTx
9YeOg64GZTCGPQdYEnpaLBbxZl9HfIejo1rg5nGvti3CMyxouonhIYM8ZG7NBWSz2YepVKobiUR+
UXjrwry+wzBm9qnAqD03YHohbsASUP+ly2u+XC7XzmQyt9LpdJc2xuscr0ULU9NUFC6JDiFRCy4g
n88/EWqFw+EEmfL7HK8+8FOAqdmrWYjC7E8kElcCgcAdWmx2LbzY5mCmc+YWXp33H/w1LQehKhPP
ZuK8mTjR0QxwArktQtKpsLHHEarwC81ir+ZOrwewTBCiXr157/7d0PfqjQcvH10w1jT6y/8A/nHJ
HcAgm2AAAAAASUVORK5CYII=";
$_IMAGES["video"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIfSURBVDjLpZNPaBNBGMXfbrubzBqbg4kL
0lJLgiVKE/AP6Kl6UUFQNAeDIAjVS08aELx59GQPAREV/4BeiqcqROpRD4pUNCJSS21OgloISWME
Z/aPb6ARdNeTCz92mO+9N9/w7RphGOJ/nsH+olqtvg+CYJR8q9VquThxuVz+oJTKeZ63Uq/XC38E
0Jj3ff8+OVupVGLbolkzQw5HOqAxQU4wXWWnZrykmYD0QsgAOJe9hpEUcPr8i0GaJ8n2vs/sL2h8
R66TpVfWTdETHWE6GRGKjGiiKNLii5BSLpN7pBHpgMYhMkm8tPUWz3sL2D1wFaY/jvnWcTTaE5Dy
jMfTT5J0XIAiTRYn3ASwZ1MKbTmN7z+KaHUOYqmb1fcPiNa4kQBuyvWAHYfcHGzDgYcx9NKrwJYH
CAyF21JiPWBnXMAQOea6bmn+4ueYGZi8gtymNVobF7BG5prNpjd+eW6X4BSUD0gOdCpzA8MpA/v2
v15kl4+pK0emwHSbjJGBlz+vYM1fQeDrYOBTdzOGvDf6EFNr+LYjHbBgsaCLxr+moNQjU2vYhRXp
gIUOmSWWnsJRfjlOZhrexgtYDZ/gWbetNRbNs6QT10GJglNk64HMaGgbAkoMo5fiFNy7CKDQUGqE
5r38YktxAfSqW7Zt33l66WtkAkACjuNsaLVaDxlw5HdJ/86aYrG4WCgUZD6fX+jv/U0ymfxoWVZo
muZyf+8XqfGP49CCrBUAAAAASUVORK5CYII=";
$_IMAGES["webpage"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJwSURBVDjLjZPdT1JhHMetvyO3/gfLKy+6
8bLV2qIAq7UyG6IrdRPL5hs2U5FR0MJIAqZlh7BVViI1kkyyiPkCyUtztQYTYbwJE8W+Pc8pjofK
1dk+OxfP+X3O83srAVBCIc8eQhmh/B/sJezm4niCsvX19cTm5uZWPp/H3yDnUKvVKr6ELyinwWtr
a8hkMhzJZBLxeBwrKyusJBwOQ6PRcJJC8K4DJ/dXM04DOswNqNOLybsRo9N6LCy7kUgkEIlEWEE2
mwX9iVar/Smhglqd8IREKwya3qhg809gPLgI/XsrOp/IcXVMhqnFSayurv6RElsT6ZCoov5u1fzU
VwvcKRdefVuEKRCA3OFHv2MOxtlBdFuaMf/ZhWg0yt4kFAoVCZS3Hd1gkpOwRt9h0LOES3YvamzP
cdF7A6rlPrSbpbhP0kmlUmw9YrHYtoDku2T6pEZ/2ICXEQ8kTz+g2TkNceAKKv2nIHachn6qBx1M
I5t/Op1mRXzBd31AiRafBp1vZyEcceGCzQ6p24yjEzocGT6LUacS0iExcrkcK6Fsp6AXLRnmFOjy
PMIZixPHmAAOGxZQec2OQyo7zpm6cNN6GZ2kK1RAofPAr8GA4oUMrdNNkIw/wPFhDwSjX3Dwlg0C
Qy96HreiTlcFZsaAjY0NNvh3QUXtHeHcoKMNA7NjqLd8xHmzDzXDRvRO1KHtngTyhzL4SHeooAAn
KMxBtUYQbGWa0Dc+AsWzSVy3qkjeItLCFsz4XoNMaRFFAm4SyTXbmQa2YHQSGacR/pAXO+zGFif4
JdlHCpShBzstEz+YfJtmt5cnKKWS/1jnAnT1S38AGTynUFUTzJcAAAAASUVORK5CYII=";


$_IMAGES["7z"] = $_IMAGES["archive"];
$_IMAGES["as"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIqSURBVDjLjZPNi1JRGMan/ooWDbSKNq2s
gZqh0UgqKVoOU7OooEWLgZi+JIaYGolaRAS60JXuxJWoIC6E0KAgAzGbCqpFmua393qv9+PoPJ33
THPHcYy68HDPvee8v/e8zznvFIApEn8Octm4Zv6hQ1z7rbgRgE3X9S5jbDgYDDBJfB5er/flKGQU
MEPBiqJAVVVLkiSh0+mgVqsJSLVahc/nsyDbwfsIQAs0TYNhGNDevIX29BnUxx50u13U63UB6Pf7
oCR+v38LMg6gYCOdhnb1GgaeVajnL0CWZTQajT0lCU/GAea379AWFsHu3kJ/4TLUO/etUprNpthJ
pVL5C4Ax6I/WwVbvoe9+AMazMvrHzSMI7YT8aLVakwHs8xdoS1eguC7CeJUBa3fEwkKhgEwmI+pP
8/Ly+fxkgP78BZj7NgYP3ZDn7FDXPGJhKpVCuVwW/tA7HA7vBawdPrJEmZl7hQc7IJ2YtwCxWEyU
IgzmCgaDuwF157kDlVOnC+bKMmS7E8a79zA3PsEs/0Q8Hkc2m4VpmkLkB5URjUa3AMpZ1+uew/lV
mnMw/cZ1qOtPrGOirKVSCclk0gKQQqGQOFYB6NnPKPKsfdNYvgnJdQnsV23XWRMkkUig3W6LMSkQ
COyUIJ+ch3R8Fj+O2j6YHzc2J/VAsVgUEBpHIhHkcjkaDy0P/hh5jBuk0sQ4gO4AXSIa09b595Cv
7YnuHQFME+Q/2nlb1PrTvwGo2K3gWVH3FgAAAABJRU5ErkJggg==";
$_IMAGES["avi"] = $_IMAGES["video"];
$_IMAGES["bz2"] = $_IMAGES["archive"];
$_IMAGES["c"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHdSURBVDjLjZNLS+NgFIad+R0KwuzcSQdd
unTWXraKA4KCuFKcWYqgVbE4TKJWNyqC2oHKoDBeEBF04UpFUVQqUoemSVOTJr2lrb5+5xsTUy+j
gYdc3yfnnOQrAVBCsK2U4WFUvUE546OTcwk82WxWz+fzt4VCAS/B7kMQhB9uiVtQReFkMolUKuWQ
SCSgaRpkWeYSSZIgiqIjscMfSEAPZDIZWJbF94RpmtB1HYqicEE6nQa9xO/3/5OQoM57/qm2a3PG
tyzDtxzF/FYMe6c6F1DAMAzEYrFnLfGZ1A9devqC8o2wpmL8jwJhRcbw7ygGAxJYS7xvuxVVVXkl
kUjkUdAshgP+DRVfureXbPPcuoKe2b/QDKtIQpXQPOLx+KOgf0nGCCu9smHiu7u8IGuDBHRsS6gd
mgmJHEHfLwn9wSgqagc6Xvt8RC6X48MlCeEI2ibDIS8TVDYGBHfAO3ONowvTOacqSEBQNY6gpvOk
p3cxgq8/Q8ZxyISWsDAwfY32sSscnhk8SFAFBIWLBPQZq1sOvjX5LozOqTBaxSu0jF5iYVV+FnZT
JLB/pN0DDTv7WlHvtuQpLwrYxbv/DfIJt47gQfKZDShFN94TZs+afPW6BGUkecdytqGlX3YPTr7m
omspN0YAAAAASUVORK5CYII=";
$_IMAGES["cab"] = $_IMAGES["archive"];
$_IMAGES["cpp"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAH/SURBVDjLjZPNaxNRFMWrf4cFwV13JVKX
Luta61apIChIV0rblUqhjYpRcUaNboxIqxFTQgVti4hQQTe1C7FFSUmnmvmM85XJzCSpx3efzmTS
RtqBw7yZ9+5v7rl3bg+AHhK7DjClmAZ20UGm/XFcApAKgsBqNptbrVYL3cT2IQjCnSQkCRig4Fqt
Bs/zYtm2DdM0oaoqh8iyDFEUY0gUvI8AdMD3fYRhyO8k13VhWRY0TeOAer0O+kg2m/0LIcDx9LdD
gxff5jJzKjJzCmbe6fi0anEABTiOA13Xd1jiNTlxfT01UVB/CfMG7r/WILxScaOo4FpeBrPEfUdW
DMPgmVQqlTbgtCjls4sGjl16PxuRny5oGH3yA7oZoPjR4BDbqeHlksLrUa1W24DJWRU3Wer9Qw/G
k+kVmA2lGuDKtMQzsVwfl6c3eE3IUgyYeCFjsqCgb3DqQhJwq/gTY7lyV61Jdhtw7qFUSjNA/8m8
kASkc5tYXnN4BvTs1kO23uAdIksx4OjI19Grzys4c7fkfCm5MO0QU483cf5eGcurNq8BWfD8kK11
HtwBoDYeGV4ZO5X57ow8knBWLGP49jqevVF5IKnRaOxQByD6kT6smFj6bHb0OoJsV1cAe/n7f3PQ
RVsx4B/kMCuQRxt7CWZnXT69CUAvQfYwzpFo9Hv/AD332dKni9XnAAAAAElFTkSuQmCC";
$_IMAGES["cs"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJOSURBVDjLjZPbaxNBFMarf4cFwb9AIgXB
R18Enyw+i1gs4g01kphSlPjQeAtNzNqGNLVpNCGhEvBS21Rr0ZIK6ovFiKbNbXNpdpNsstncUz9n
NiauErEDHwMz8/1mzjlz+gD0UZGxh0hFNPAf7SXa3fUpAKparVZoNpvbrVYLvUT2YbFYTEqIEjBA
zZIkoVwud1UsFiEIAjKZjAxJp9NgGKYL6Zh3UQA9UK1WUa/X5ZmqVCqhUCiA4zgZUKlUQC+xWq1t
CAUM3v6+74hu2cH4eUz6OcwFcvgYEmUANYiiiFF3Aq5XHIJRCeqHLOJbFcg5OW6Mqm495fL2NznY
l7OwveYxsZSF6QUHEpIc9+eQgOvuFL6EMjC6wrg4GZZfIwOGbazX8TaPY/qAr5Ms72oOBt8WknwV
em8KWmcCY0/S0E1HcXYyhjNMBAYH2waYF8izl3I4eGLqmjLjz9by+PRNxCMS0k0C0c+yMDjj0Mwm
MOGJ4+Vqtg0Yn+dwf5HH/sG75/4uWzAiwbfCQ+dMYSGQxdhMHMPmMFY+8MgX623AiDu9+YAADg35
LErzHU8SGkcSI4+T0DoSuGRnoZ5mcdIUwdC9zd85OHpjQzP+nMOVmZj4NSZBKNVh9LbN6xslnGai
8CxmMP+Ol81criwntgugZTysDmovTEXEUVcKV8lt520s5kjJvP4MTpkjyApVXCZmvTWKRqMh6w9A
5yO9Xy9ijUgZCi1lL/UEkMUf/+qDHtruAn5BDpAvXKYbOzGTsyW5exWAfgrZQTt3RFu//yfHVsX/
fi5tjwAAAABJRU5ErkJggg==";
$_IMAGES["css"] = $_IMAGES["code"];
$_IMAGES["doc"] = $_IMAGES["textdocument"];
$_IMAGES["docx"] = $_IMAGES["textdocument"];
$_IMAGES["exe"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEkSURBVCjPbdE9S0IBGIbhxxobWxP8D8r5
I60RLg0NNTS21VBRQwg1aA4VOAWBpBVCFhKUtkVJtPQx9GFFWh49x3P0bvAjjsWzXrzvcAtpREEZ
fQtoACEkpKBVdpouv7NYi3SJkAynWcXExKTCJ6+4PLPeIZJPhksdmzp1vilTwqVGlWhEgR6wsbGp
U+OLt94rGfJ1gIOLi4OFSYV3Sjx5QXdtkiHFx//gjiwlTshyT5LV3T8gwy3HFLnhkCuWmB3qA0Uu
2WGOZVIUmN/ru5CiwAsLNLCI8cg+i3hAggMeiNOgwQbXRJnwghoX5DkiTow0OcLJ8HAbtLpkkzwJ
CuTY4pQppgeFFLJNtxMrzSRFtlnhvDXO6Fk7ll8hb+wZxpChoPzoB6aiXIYcSLDWAAAAAElFTkSu
QmCC";
$_IMAGES["gz"] = $_IMAGES["archive"];
$_IMAGES["gif"] = $_IMAGES["image"];
$_IMAGES["h"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHtSURBVDjLjZNLS9xQFMe138C9A/0OynyB
UjeFQjduROi2MMtCEalS0ToLEdQMdEShoKDWRymKigWxII7PhaB9aBFUJjHJpHlnnvbfe27NJcVI
DfwIyT3nd885cOoA1BHsaWQ0MZL/4SHjgciLCJpKpZJVrVava7Ua4mDnkCRpKCqJCpKU7HkefN8X
2LYN0zShqiqXKIqCTCYjJGFyPQkooFgsolwu8zfhui4sy4KmaVwQBAHokmw2+1cSClpSUmr12MP7
LQunii8klOA4DnRdv9USn0koePRiJDW+aTGBjcOLgAewlnjfYSuFQoFXIsvybQF9jG2avIKFPQtz
OyZmcyZMtywkVAnNwzCMeMG7jV+YyFmQ1g30L2kYWitAWtZFJdQOzYREsYLhzwZGGF+OHez/9PD2
k4aeeYUHVyoVPheSELGCwRUdA+zG/VMPeycu3iyo6J5WxDxIQFA1QtCauUwPrOpIPh/vSC+qSC/q
PHn3u4uu2Su8nsrzZKqAoOR/BO2j+Q+DTPC0/2CdSu79qOLVlIyXk3l0zsjomJYxv6ELQYgQPOk7
a2jpOnmcaG57tvuD3fzNxc5XB9sEm0XuyMb5VcCriBI7A/bz9117EMO1ENxImtmAfDq4TzKLdfn2
RgQJktxjnUNo9RN/AFmTwlP7TY1uAAAAAElFTkSuQmCC";
$_IMAGES["htm"] = $_IMAGES["webpage"];
$_IMAGES["html"] = $_IMAGES["webpage"];
$_IMAGES["iso"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIsSURBVDjLjZNfa9NQGIdnP4cDv8Nkn8PL
6UfwSgQZOoSBYkUvZLN1lMFArQyHrsIuWkE3ug2t1K3O0LXrZotdlzZp0qZp/qc9P8852qyyigs8
F8nJ7znveZN3DMAYg14XKROUyf9wiRIKckOCCcdxNN/3+71eD6Og64hEInPDkmHBJAsbhgHTNAM6
nQ7a7TYkSeKSer2OaDQaSAbhC7efJGY28gZWPrUQTyt4l2lCKLfR7XahaRpkWeYCy7LANonFYr8l
qzt26PUXIxzf7pCfioeS5EI2fVQkG+GVH0hlRVqFjmazeeZIvCc0PBXf1ohu96GZBEnBQMMmcAjg
eH3cWRKQyTf4URRF4ZWIongqoOFURXZpUEOt1YNm+BzDI6AeFKo6IqsF3g9d13k/VFU9FSytK9V8
zUJiR0WbBh+/2cVich+trodvNQeFEwvTsa/8C7Dzs54wUSBYeN+ofq+ageDZmoBX64dQdRcbByaE
qoGbTzPwPA+u63IJIxDMrR2nDkUTR6oPxSJ8ZxYuNlxsHtnYLal48DIH+om5gMGqCQSP3lam7i+X
SMfp40AFsjWCrbKHdMlGpeng2uxHpHM1XgGDhf8S3Fsuhe4+3w9PL+6RvbKGguhAODaRLSq4OvsB
L5JFvutAMCAQDH6kK9fnZyKJAm4tZHFj/jMexnPYzJ3w0kdxRsBu6EPyrzkYQT8Q/JFcpqWabOE8
Yfpul0/vkGCcSc4xzgPY6I//AmC87eKq4rrzAAAAAElFTkSuQmCC";
$_IMAGES["java"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIRSURBVDjLjZPJa1NRFIera/8ECy7dV7tx
kb2UOoDgzo0R3YuLrFwWIVglWQRtN0GCLkIixJDJQJKGQOYBA4akmec5eSFT/XnPsXlNsWIffOTd
d3O+e+6PezcAbBDiuS7YEmz/hxuCq3LdmmBrOp32F4vFyXK5xEWIeWg0mnfrknXBNhWPx2NIkiQz
GAzQ6/XQaDRYUqvVoNVqZQkXGwyGm2q1+k00GkUkEkE4HEYwGGQCgQDS6TSKxSILJpMJaBGdTvdH
YjKZHvp8vuNsNot6vc7QavRLq1UqFcTjcbhcLrmLFZyJ2+0u9Pt9hC1f8OHpDt4/uoO3928zmscK
HD5/gKPPB8jn8yxpNpuoVqtnAqPRiOFwiPGgB/fhPr7uvcJH5S4Ont3Dp5dP8G3/NX4cfedCi8XC
eXQ6nTOBzWaT5vM5J0yTFFy325WhtmkbhN1ux2g04gVlgcfj+UmDUqkEh8OBcrnM7xRaLpdDIpHg
cSqVYihEYr0DL61O6fv9fhQKBd4vhUrpk6DdbsNsNrN8Nptxt7JApVK9EMW9TCbDEgqI2qUOSELv
JPF6vbw9Kj4nEM81pVJ5V6/XH8diMQ6IaLVaLAmFQnA6nfyNslohC05P4RWFQrFLHVitVoYSF2cE
yWSSgxOn9Bx/CWggPv761z24gBNZcCq5JQKSaOIyxeK/I769a4JNklziOq+gq7/5Gx172kZga+XW
AAAAAElFTkSuQmCC";
$_IMAGES["jpg"] = $_IMAGES["image"];
$_IMAGES["jpeg"] = $_IMAGES["image"];
$_IMAGES["js"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHdSURBVDjLjZNPaxNBGIdrLwURLznWgkcv
IrQhRw9FGgy01IY0TVsQ0q6GFkT0kwjJId9AP4AHP4Q9FO2hJ7El2+yf7OzMbja7Sf0578QdNybF
LjwszLu/Z2femZkDMEfI54FkRVL4Dw8l8zqXEawMBgM2HA6vR6MRZiHraDabH7KSrKBA4SAIEIah
xvd9eJ6HbrerJKZpotVqaUkavkMC+iCKIsRxrN6EEAKMMViWpQT9fh/0k3a7PZZkBUPmqXAKCSjA
OYdt21NLUj1JBYW7C6vi6BC8vKWKQXUXQcNA5Nh6KY7jqJl0Op1JwY/Hi7mLp/lT/uoA/OX2WLC3
C9FoQBwfILKulIRmQv1wXfevwHmyuMPXS5Fv1MHrFSTmhSomnUvw/Spo3C+vg3/+pJZDPSGRFvil
NV+8PUZvoziKvn+d3LZvJ/BelMDevIZXK2EQCiUhtMDM53bY5rOIGXtwjU3EVz/HM5Az8eplqPFK
EfzLR91cOg8TPTgr3MudFx+d9owK7KMNVfQOtyQ1OO9qiHsWkiRRUHhKQLuwfH9+1XpfhVVfU0V3
//k4zFwdzjIlSA/Sv8jTOZObBL9uugczuNaCP5K8bFBIhduE5bdC3d6MYIkkt7jOKXT1l34DkIu9
e0agZjoAAAAASUVORK5CYII=";
$_IMAGES["mov"] = $_IMAGES["video"];
$_IMAGES["mp3"] = $_IMAGES["audio"];
$_IMAGES["mp4"] = $_IMAGES["audio"];
$_IMAGES["mpeg"] = $_IMAGES["video"];
$_IMAGES["mpg"] = $_IMAGES["video"];
$_IMAGES["odg"] = $_IMAGES["vectorgraphics"];
$_IMAGES["odp"] = $_IMAGES["presentation"];
$_IMAGES["ods"] = $_IMAGES["spreadsheet"];
$_IMAGES["odt"] = $_IMAGES["textdocument"];
$_IMAGES["pdf"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHhSURBVDjLjZPLSxtRFIfVZRdWi0oFBf+B
rhRx5dKVYKG4tLhRqlgXPmIVJQiC60JCCZYqFHQh7rrQlUK7aVUUfCBRG5RkJpNkkswrM5NEf73n
6gxpHujAB/fOvefjnHM5VQCqCPa1MNoZnU/Qxqhx4woE7ZZlpXO53F0+n0c52Dl8Pt/nQkmhoJOC
dUWBsvQJ2u4ODMOAwvapVAqSJHGJKIrw+/2uxAmuJgFdMDUVincSxvEBTNOEpmlIp9OIxWJckMlk
oOs6AoHAg6RYYNs2kp4RqOvfuIACVFVFPB4vKYn3pFjAykDSOwVta52vqW6nlEQiwTMRBKGygIh9
GEDCMwZH6EgoE+qHLMuVBdbfKwjv3yE6Ogjz/PQ/CZVDPSFRRYE4/RHy1y8wry8RGWGSqyC/nM1m
eX9IQpQV2JKIUH8vrEgYmeAFwuPDCHa9QehtD26HBhCZnYC8ucGzKSsIL8wgsjiH1PYPxL+vQvm5
B/3sBMLyIm7GhhCe90BaWykV/Gp+VR9oqPVe9vfBTsruM1HtBKVPmFIUNusBrV3B4ev6bsbyXlPd
kbr/u+StHUkxruBPY+0KY8f38oWX/byvNAdluHNLeOxDB+uyQQfPCWZ3NT69BYJWkjxjnB1o9Fv/
ASQ5s+ABz8i2AAAAAElFTkSuQmCC";
$_IMAGES["php"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGsSURBVDjLjZNLSwJRFICtFv2AgggS2vQL
DFvVpn0Pi4iItm1KItvWJqW1pYsRemyyNILARbZpm0WtrJ0kbmbUlHmr4+t0z60Z7oSSAx935txz
vrlPBwA4EPKMEVwE9z+ME/qtOkbgqtVqUqPRaDWbTegE6YdQKBRkJazAjcWapoGu6xayLIMoilAo
FKhEEAQIh8OWxCzuQwEmVKtVMAyDtoiqqiBJEhSLRSqoVCqAP+E47keCAvfU5sDQ8MRs/OYNtr1x
2PXdwuJShLLljcFlNAW5HA9khLYp0TUhSYMLHm7PLEDS7zyw3ybRqyfg+TyBtwl2sDP1nKWFiUSa
zFex3tk45sXjL1Aul20CGTs+syVY37igBbwg03eMsfH9gwSsrZ+Doig2QZsdNiZmMkVrKmwc18az
HKELyQrOMEHTDJp8HXu1hostG8dY8PiRngdWMEq467ZwbDxwlIR8XrQLcBvn5k9Gpmd8fn/gHlZW
T20C/D4k8eTDB3yVFKjX6xSbgD1If8G970Q3QbvbPehAyxL8SibJEdaxo5dikqvS28sInCjp4Tqb
4NV3fgPirZ4pD4KS4wAAAABJRU5ErkJggg==";
$_IMAGES["png"] = $_IMAGES["image"];
$_IMAGES["pps"] = $_IMAGES["presentation"];
$_IMAGES["ppsx"] = $_IMAGES["presentation"];
$_IMAGES["ppt"] = $_IMAGES["presentation"];
$_IMAGES["pptx"] = $_IMAGES["presentation"];
$_IMAGES["psd"] = $_IMAGES["graphics"];
$_IMAGES["rar"] = $_IMAGES["archive"];
$_IMAGES["rb"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIESURBVDjLjZNPTxNBGIexid9CEr8DBr8C
HEiMVoomJiQkxBIM3dgIiaIESJTGGpVtyXIzHhoM4SIe9KAnEi4clQtJEczWFrbdP93d7s7u/JwZ
7XYJBdnkyRxmfs/MvO9OD4AeDvuuMPoY/f/hKiMR5WKCvlarpRNCwiAI0A02D1mW38QlcUE/Dzeb
Tdi2HWEYBhqNBqrVqpBUKhUUCoVI0g5f4gK+wHVdeJ4nRo5lWdB1HbVaTQgcxwHfRFGUvxIuCKYf
zmqZyZ2wKIO8fQ3/1Uv4Sy/QWliAO/sU9qMZmFMS3HfvT1xJ1ITOZJ9RpQi6+RH0y2fQb19BP23C
VhRo+TysXA71+XkcMIk6fAfHK6tQVfWEoESXngNra0C5DHZJYGMDZiaD35IEi41qOo3vc3MoJ1Oo
j92HpmkdQZiVEsHUAzl88hjY3gYIAdbXYQ0MoDo4CH1kBHssvH8jCf3eGKzDXzBNsyNoF/HH7WSJ
ZLPA7i6wtQVnaAhmKoXjxUX8vDkMY3Qcnm6IInJOCS4nEte9QhF+RhInIRMTcFhYvZWCcXcUPmsl
7w6H/w+nBFEb5SLc8TTo8jLq7M4m25mHfd8X8PC5AtHrXB5NdmwRrnfCcc4VCEnpA8jREasp6cpZ
AnrWO+hCGAn+Sa6xAtl84iJhttYSrzcm6OWSCzznNvzp9/4BgwKvG3Zq1eoAAAAASUVORK5CYII=";
$_IMAGES["sln"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJQSURBVDjLjZNvSBNxGMeX9O+FOAkaLbeh
ozdGRGiMQqTIlEqJMIig3oxl0YxcgYt6FUZRryLYwpFWCr2wXgjBIMJMYhFjgZSiEXOg5c5N593u
dne7u+2+3V3tT22SBx/uxe/5fu7uuefRAdCpKJdJoVHB9h9qFSryuSJBYzqdpiRJymYyGZRDOYfH
43lULCkW2NRwKpUCy7J5kskkSJJELBbTJARBwOv15iW58AZVoBbwPA9BELS7CsMwoCgK8XhcE3Ac
B/UhPp/vtyQnGBi03pYXjyAbPQuRD2sSbmUFVN9NLJ5ux9DryZJP0nqiChzjl48Oh9oYRPTAXBVk
sgnS0hRWu7uxXG/EfL0ZZ9yjGHgb1t4kGo0WBO6AvcUVsFP9oTZZjlQCP7ZA/r4JpHM3lup2Im6p
RsRai2PX/GjoDWEk8BWJRKIg6P147mfP+CW63d16RUyOQP5SA6rLAsKyA0TNNizvM4D9/A4Tk2Ec
7nuPE0+vgqbpgqBnzLl6vv8N3+x4eEsS0mAvHAJhMoAw6kHUVUF4rkeWHAKXZtA15kDL6C6tkXmB
ffiZs/P+NE7dC4pBhwsJY6USVjBtBO/bCswrbfq2GS+Ce9DwyooHoRvaPPzVxI67IVfHnQA+2JqQ
MFQgur0anP8J5IVmYEopmdbh5YQO1wMu0BxdKlB/44GLg48/HT8J8uBesH6/ViDxC5DnWiHPWjAz
0wleYCGKokaJIDdI/6JMZ1nWEshr7UEZsnnBH8l+ZfpY9WA9YaWW0ba3SGBWJetY5xzq6pt/AY6/
mKmzshF5AAAAAElFTkSuQmCC";
$_IMAGES["sql"] = $_IMAGES["database"];
$_IMAGES["tar"] = $_IMAGES["archive"];
$_IMAGES["tgz"] = $_IMAGES["archive"];
$_IMAGES["txt"] = "iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADoSURBVBgZBcExblNBGAbA2ceegTRBuIKO
giihSZNTcC5LUHAihNJR0kGKCDcYJY6D3/77MdOinTvzAgCw8ysThIvn/VojIyMjIyPP+bS1sUQI
V2s95pBDDvmbP/mdkft83tpYguZq5Jh/OeaYh+yzy8hTHvNlaxNNczm+la9OTlar1UdA/+C2A4tr
RCnD3jS8BB1obq2Gk6GU6QbQAS4BUaYSQAf4bhhKKTFdAzrAOwAxEUAH+KEM01SY3gM6wBsEAQB0
gJ+maZoC3gI6iPYaAIBJsiRmHU0AALOeFC3aK2cWAACUXe7+AwO0lc9eTHYTAAAAAElFTkSuQmCC";
$_IMAGES["wav"] = $_IMAGES["audio"];
$_IMAGES["wma"] = $_IMAGES["audio"];
$_IMAGES["wmv"] = $_IMAGES["video"];
$_IMAGES["xcf"] = $_IMAGES["graphics"];
$_IMAGES["xls"] = $_IMAGES["spreadsheet"];
$_IMAGES["xlsx"] = $_IMAGES["spreadsheet"];
$_IMAGES["xml"] = $_IMAGES["code"];
$_IMAGES["zip"] = $_IMAGES["archive"];

/***************************************************************************/
/*   EDASIST KOODI EI OLE TARVIS MUUTA                                     */
/*                                                                         */
/*   HERE COMES THE CODE.                                                  */
/*   DON'T CHANGE UNLESS YOU KNOW WHAT YOU ARE DOING ;)                    */
/***************************************************************************/

//
// The class that displays images (icons and thumbnails)
//
class ImageServer
{
	//
	// Checks if an image is requested and displays one if needed
	//
	public static function showImage()
	{
		global $_IMAGES;
		if(isset($_GET['img']))
		{
			if(strlen($_GET['img']) > 0)
			{
				$mtime = gmdate('r', filemtime($_SERVER['SCRIPT_FILENAME']));
				$etag = md5($mtime.$_SERVER['SCRIPT_FILENAME']);
				
				if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $mtime)
					|| (isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag)) 
				{
					header('HTTP/1.1 304 Not Modified');
					return true;
				}
				else {
					header('ETag: "'.$etag.'"');
					header('Last-Modified: '.$mtime);
					header('Content-type: image/gif');
					if(isset($_IMAGES[$_GET['img']]))
						print base64_decode($_IMAGES[$_GET['img']]);
					else
						print base64_decode($_IMAGES["unknown"]);
				}
			}
			return true;
		}
		else if(isset($_GET['thumb']))
		{
			if(strlen($_GET['thumb']) > 0 && EncodeExplorer::getConfig('thumbnails') == true)
			{
				ImageServer::showThumbnail($_GET['thumb']);
			}
			return true;
		}
		return false;
	}
	
	public static function isEnabledPdf()
	{
		if(class_exists("Imagick"))
			return true;
		return false;
	}
	
	public static function openPdf($file)
	{
		if(!ImageServer::isEnabledPdf())
			return null;
			
		$im = new Imagick($file.'[0]');
		$im->setImageFormat( "png" );
		$str = $im->getImageBlob();
		$im2 = imagecreatefromstring($str);
		return $im2;
	}
	
	//
	// Creates and returns a thumbnail image object from an image file
	//
	public static function createThumbnail($file)
	{
		if(is_int(EncodeExplorer::getConfig('thumbnails_width')))
			$max_width = EncodeExplorer::getConfig('thumbnails_width');
		else
			$max_width = 200;
		
		if(is_int(EncodeExplorer::getConfig('thumbnails_height')))
			$max_height = EncodeExplorer::getConfig('thumbnails_height');
		else
			$max_height = 200;

		if(File::isPdfFile($file))
			$image = ImageServer::openPdf($file);
		else
			$image = ImageServer::openImage($file);
		if($image == null)
			return;
			
		imagealphablending($image, true);
		imagesavealpha($image, true);
			
		$width = imagesx($image);
		$height = imagesy($image);
			
		$new_width = $max_width;
		$new_height = $max_height;
		if(($width/$height) > ($new_width/$new_height))
			$new_height = $new_width * ($height / $width);
		else 
			$new_width = $new_height * ($width / $height);   
		
		if($new_width >= $width && $new_height >= $height)
		{
			$new_width = $width;
			$new_height = $height;
		}
		
		$new_image = ImageCreateTrueColor($new_width, $new_height);
		imagealphablending($new_image, true);
		imagesavealpha($new_image, true);
		$trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
		imagefill($new_image, 0, 0, $trans_colour);
		
		imagecopyResampled ($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		return $new_image;
	}
	
	//
	// Function for displaying the thumbnail.
	// Includes attempts at cacheing it so that generation is minimised.
	//
	public static function showThumbnail($file)
	{
		if(filemtime($file) < filemtime($_SERVER['SCRIPT_FILENAME']))
			$mtime = gmdate('r', filemtime($_SERVER['SCRIPT_FILENAME']));
		else
			$mtime = gmdate('r', filemtime($file));
			
		$etag = md5($mtime.$file);
		
		if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $mtime)
			|| (isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag)) 
		{
			header('HTTP/1.1 304 Not Modified');
			return;
		}
		else
		{
			header('ETag: "'.$etag.'"');
			header('Last-Modified: '.$mtime);
			header('Content-Type: image/png');
			$image = ImageServer::createThumbnail($file);
			imagepng($image);
		}
	}
	
	//
	// A helping function for opening different types of image files
	//
	public static function openImage ($file) 
	{
	    $size = getimagesize($file);
	    switch($size["mime"])
	    {
			case "image/jpeg":
				$im = imagecreatefromjpeg($file);
			break;
			case "image/gif":
				$im = imagecreatefromgif($file);
			break;
			case "image/png":
				$im = imagecreatefrompng($file);
			break;
			default:
				$im=null;
			break;
	    }
	    return $im;
	}
}

//
// The class for logging user activity
//
class Logger
{
	public static function log($message)
	{
		global $encodeExplorer;
		if(strlen(EncodeExplorer::getConfig('log_file')) > 0)
		{
			if(Location::isFileWritable(EncodeExplorer::getConfig('log_file')))
			{
				$message = "[" . date("Y-m-d h:i:s", mktime()) . "] ".$message." (".$_SERVER["HTTP_USER_AGENT"].")\n";
				error_log($message, 3, EncodeExplorer::getConfig('log_file'));
			}
			else
				$encodeExplorer->setErrorString("log_file_permission_error");
		}
	}
	
	public static function logAccess($path, $isDir)
	{
		$message = $_SERVER['REMOTE_ADDR']." ".GateKeeper::getUserName()." accessed ";
		$message .= $isDir?"dir":"file";
		$message .= " ".$path;
		Logger::log($message);
	}
	
	public static function logQuery()
	{
		if(isset($_POST['log']) && strlen($_POST['log']) > 0)
		{
			Logger::logAccess($_POST['log'], false);
			return true;
		}
		else
			return false;
	}
	
	public static function logCreation($path, $isDir)
	{
		$message = $_SERVER['REMOTE_ADDR']." ".GateKeeper::getUserName()." created ";
		$message .= $isDir?"dir":"file";
		$message .= " ".$path;
		Logger::log($message);
	}
	
	public static function emailNotification($path, $isFile)
	{
		if(strlen(EncodeExplorer::getConfig('upload_email')) > 0)
		{
			$message = "This is a message to let you know that ".GateKeeper::getUserName()." ";
			$message .= ($isFile?"uploaded a new file":"created a new directory")." in Encode Explorer.\n\n";
			$message .= "Path : ".$path."\n";
			$message .= "IP : ".$_SERVER['REMOTE_ADDR']."\n";
			mail(EncodeExplorer::getConfig('upload_email'), "Upload notification", $message);
		}
	}
}

//
// The class controls logging in and authentication
//
class GateKeeper
{
	public static function init()
	{
		global $encodeExplorer;
		if(strlen(EncodeExplorer::getConfig("session_name")) > 0)
				session_name(EncodeExplorer::getConfig("session_name"));
				
		if(count(EncodeExplorer::getConfig("users")) > 0)
			session_start();			
		else
			return;
			
		if(isset($_GET['logout']))
		{
			$_SESSION['ee_user_name'] = null;
			$_SESSION['ee_user_pass'] = null;
		}
			
		if(isset($_POST['user_pass']) && strlen($_POST['user_pass']) > 0)
		{
			if(GateKeeper::isUser((isset($_POST['user_name'])?$_POST['user_name']:""), $_POST['user_pass']))
			{
				$_SESSION['ee_user_name'] = isset($_POST['user_name'])?$_POST['user_name']:"";
				$_SESSION['ee_user_pass'] = $_POST['user_pass'];
				
				$addr = $_SERVER['PHP_SELF'];
				if(isset($_GET['m']))
					$addr .= "?m";
				else if(isset($_GET['s']))
					$addr .= "?s";
				header( "Location: ".$addr);
			}
			else
				$encodeExplorer->setErrorString("wrong_pass");
		}
	}
	
	public static function isUser($userName, $userPass)
	{
		foreach(EncodeExplorer::getConfig("users") as $user)
		{
			if($user[1] == $userPass)
			{
				if(strlen($userName) == 0 || $userName == $user[0])
				{
					return true;
				}
			}
		}
		return false;
	}
	
	public static function isLoginRequired()
	{
		if(EncodeExplorer::getConfig("require_login") == false){
			return false;
		}
		return true;
	}
	
	public static function isUserLoggedIn()
	{
		if(isset($_SESSION['ee_user_name']) && isset($_SESSION['ee_user_pass']))
		{
			if(GateKeeper::isUser($_SESSION['ee_user_name'], $_SESSION['ee_user_pass']))
				return true;
		}
		return false;
	}
	
	public static function isAccessAllowed()
	{
		if(!GateKeeper::isLoginRequired() || GateKeeper::isUserLoggedIn())
			return true;
		return false;
	}
	
	public static function isUploadAllowed(){
		if(EncodeExplorer::getConfig("upload_enable") == true && GateKeeper::isUserLoggedIn() == true && GateKeeper::getUserStatus() == "admin")
			return true;
		return false;
	}
	
	public static function isNewdirAllowed(){
		if(EncodeExplorer::getConfig("newdir_enable") == true && GateKeeper::isUserLoggedIn() == true && GateKeeper::getUserStatus() == "admin")
			return true;
		return false;
	}
	
	public static function isDeleteAllowed(){
		if(EncodeExplorer::getConfig("delete_enable") == true && GateKeeper::isUserLoggedIn() == true && GateKeeper::getUserStatus() == "admin")
			return true;
		return false;
	}
	
	public static function getUserStatus(){
		if(GateKeeper::isUserLoggedIn() == true && EncodeExplorer::getConfig("users") != null && is_array(EncodeExplorer::getConfig("users"))){
			foreach(EncodeExplorer::getConfig("users") as $user){
				if($user[0] != null && $user[0] == $_SESSION['ee_user_name'])
					return $user[2];
			}
		}
		return null;
	}
	
	public static function getUserName()
	{
		if(GateKeeper::isUserLoggedIn() == true && isset($_SESSION['ee_user_name']) && strlen($_SESSION['ee_user_name']) > 0)
			return $_SESSION['ee_user_name'];
		if(isset($_SERVER["REMOTE_USER"]) && strlen($_SERVER["REMOTE_USER"]) > 0)
			return $_SERVER["REMOTE_USER"];
		if(isset($_SERVER['PHP_AUTH_USER']) && strlen($_SERVER['PHP_AUTH_USER']) > 0)
			return $_SERVER['PHP_AUTH_USER'];
		return "an anonymous user";
	}
	
	public static function showLoginBox(){
		if(!GateKeeper::isUserLoggedIn() && count(EncodeExplorer::getConfig("users")) > 0)
			return true;
		return false;
	}
}

// 
// The class for any kind of file managing (new folder, upload, etc).
//
class FileManager
{
	/* Obsolete code
	function checkPassword($inputPassword)
	{
		global $encodeExplorer;
		if(strlen(EncodeExplorer::getConfig("upload_password")) > 0 && $inputPassword == EncodeExplorer::getConfig("upload_password"))
		{
			return true;
		}
		else
		{
			$encodeExplorer->setErrorString("wrong_password");
			return false;
		}
	}
	*/
	function newFolder($location, $dirname)
	{
		global $encodeExplorer;
		if(strlen($dirname) > 0)
		{
			$forbidden = array(".", "/", "\\");
			for($i = 0; $i < count($forbidden); $i++)
			{
				$dirname = str_replace($forbidden[$i], "", $dirname);
			}
			
			if(!$location->uploadAllowed())
			{
				// The system configuration does not allow uploading here
				$encodeExplorer->setErrorString("upload_not_allowed");
			}
			else if(!$location->isWritable())
			{
				// The target directory is not writable
				$encodeExplorer->setErrorString("upload_dir_not_writable");
			}
			else if(!mkdir($location->getDir(true, false, false, 0).$dirname, 0777))
			{
				// Error creating a new directory
				$encodeExplorer->setErrorString("new_dir_failed");
			}
			else if(!chmod($location->getDir(true, false, false, 0).$dirname, 0777))
			{
				// Error applying chmod 777
				$encodeExplorer->setErrorString("chmod_dir_failed");
			}
			else
			{
				// Directory successfully created, sending e-mail notification
				Logger::logCreation($location->getDir(true, false, false, 0).$dirname, true);
				Logger::emailNotification($location->getDir(true, false, false, 0).$dirname, false);
			}
		}
	}

	function uploadFile($location, $userfile)
	{
		global $encodeExplorer;
		$name = basename($userfile['name']);
		if(get_magic_quotes_gpc())
			$name = stripslashes($name);

		$upload_dir = $location->getFullPath();
		$upload_file = $upload_dir . $name;
		
		if(function_exists("finfo_open") && function_exists("finfo_file"))
			$mime_type = File::getFileMime($userfile['tmp_name']);
		else
			$mime_type = $userfile['type'];
	
		$extension = File::getFileExtension($userfile['name']);

		if(!$location->uploadAllowed())
		{
			$encodeExplorer->setErrorString("upload_not_allowed");
		}
		else if(!$location->isWritable())
		{
			$encodeExplorer->setErrorString("upload_dir_not_writable");
		}
		else if(!is_uploaded_file($userfile['tmp_name']))
		{
			$encodeExplorer->setErrorString("failed_upload");
		}
		else if(is_array(EncodeExplorer::getConfig("upload_allow_type")) && count(EncodeExplorer::getConfig("upload_allow_type")) > 0 && !in_array($mime_type, EncodeExplorer::getConfig("upload_allow_type")))
		{
			$encodeExplorer->setErrorString("upload_type_not_allowed");
		}
		else if(is_array(EncodeExplorer::getConfig("upload_reject_extension")) && count(EncodeExplorer::getConfig("upload_reject_extension")) > 0 && in_array($extension, EncodeExplorer::getConfig("upload_reject_extension")))
		{
			$encodeExplorer->setErrorString("upload_type_not_allowed");
		}
		else if(!@move_uploaded_file($userfile['tmp_name'], $upload_file))
		{
			$encodeExplorer->setErrorString("failed_move");
		}
		else
		{
			chmod($upload_file, 0755);
			Logger::logCreation($location->getDir(true, false, false, 0).$name, false);
			Logger::emailNotification($location->getDir(true, false, false, 0).$name, true);
		}
	}
	
	public static function delete_dir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") 
						FileManager::delete_dir($dir."/".$object); 
					else 
						unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	
	public static function delete_file($file){
		if(is_file($file)){
			unlink($file);
		}
	}

	//
	// The main function, checks if the user wants to perform any supported operations
	// 
	function run($location)
	{
		if(isset($_POST['userdir']) && strlen($_POST['userdir']) > 0){
			if($location->uploadAllowed() && GateKeeper::isUserLoggedIn() && GateKeeper::isAccessAllowed() && GateKeeper::isNewdirAllowed()){
				$this->newFolder($location, $_POST['userdir']);
			}
		}
			
		if(isset($_FILES['userfile']['name']) && strlen($_FILES['userfile']['name']) > 0){
			if($location->uploadAllowed() && GateKeeper::isUserLoggedIn() && GateKeeper::isAccessAllowed() && GateKeeper::isUploadAllowed()){
				$this->uploadFile($location, $_FILES['userfile']);
			}
		}
		
		if(isset($_GET['del'])){
			if(GateKeeper::isUserLoggedIn() && GateKeeper::isAccessAllowed() && GateKeeper::isDeleteAllowed()){
				$split_path = Location::splitPath($_GET['del']);
				$path = "";
				for($i = 0; $i < count($split_path); $i++){
					$path .= $split_path[$i];
					if($i + 1 < count($split_path))
						$path .= "/";
				}
				if($path == "" || $path == "/" || $path == "\\" || $path == ".")
					return;
				
				if(is_dir($path))
					FileManager::delete_dir($path);
				else if(is_file($path))
					FileManager::delete_file($path);
			}
		}
	}
}

//
// Dir class holds the information about one directory in the list
//
class Dir
{
	var $name;
	var $location;

	//
	// Constructor
	// 
	function Dir($name, $location)
	{
		$this->name = $name;
		$this->location = $location;
	}

	function getName()
	{
		return $this->name;
	}

	function getNameHtml()
	{
		return htmlspecialchars($this->name);
	}

	function getNameEncoded()
	{
		return rawurlencode($this->name);
	}

	//
	// Debugging output
	// 
	function debug()
	{
		print("Dir name (htmlspecialchars): ".$this->getName()."\n");
		print("Dir location: ".$this->location->getDir(true, false, false, 0)."\n");
	}
}

//
// File class holds the information about one file in the list
//
class File
{
	var $name;
	var $location;
	var $size;
	//var $extension;
	var $type;
	var $modTime;

	//
	// Constructor
	// 
	function File($name, $location)
	{
		$this->name = $name;
		$this->location = $location;
		
		$this->type = File::getFileType($this->location->getDir(true, false, false, 0).$this->getName());
		$this->size = File::getFileSize($this->location->getDir(true, false, false, 0).$this->getName());
		$this->modTime = filemtime($this->location->getDir(true, false, false, 0).$this->getName());
	}

	function getName()
	{
		return $this->name;
	}

	function getNameEncoded()
	{
		return rawurlencode($this->name);
	}

	function getNameHtml()
	{
		return htmlspecialchars($this->name);
	}

	function getSize()
	{
		return $this->size;
	}

	function getType()
	{
		return $this->type;
	}
	
	function getModTime()
	{
		return $this->modTime;
	}

	//
	// Determine the size of a file
	// 
	public static function getFileSize($file)
	{
		$sizeInBytes = filesize($file);

		// If filesize() fails (with larger files), try to get the size from unix command line.
		if (EncodeExplorer::getConfig("large_files") == true || !$sizeInBytes || $sizeInBytes < 0) {
			$sizeInBytes=exec("ls -l '$file' | awk '{print $5}'");
		}
		return $sizeInBytes;
	}
	
	public static function getFileType($filepath)
	{
		/*
		 * This extracts the information from the file contents.
		 * Unfortunately it doesn't properly detect the difference between text-based file types.
		 * 
		$mime_type = File::getMimeType($filepath);
		$mime_type_chunks = explode("/", $mime_type, 2);
		$type = $mime_type_chunks[1];
		*/
		return File::getFileExtension($filepath);
	}
	
	public static function getFileMime($filepath)
	{
		$fhandle = finfo_open(FILEINFO_MIME);
		$mime_type = finfo_file($fhandle, $filepath);
		$mime_type_chunks = preg_split('/\s+/', $mime_type);
		$mime_type = $mime_type_chunks[0];
		$mime_type_chunks = explode(";", $mime_type);
		$mime_type = $mime_type_chunks[0];
		return $mime_type;
	}
	
	public static function getFileExtension($filepath)
	{
		return strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
	}

	//
	// Debugging output
	// 
	function debug()
	{
		print("File name: ".$this->getName()."\n");
		print("File location: ".$this->location->getDir(true, false, false, 0)."\n");
		print("File size: ".$this->size."\n");
		print("File modTime: ".$this->modTime."\n");
	}
	
	function isImage()
	{
		$type = $this->getType();
		if($type == "png" || $type == "jpg" || $type == "gif" || $type == "jpeg")
			return true;
		return false;
	}
	
	function isPdf()
	{
		if(strtolower($this->getType()) == "pdf")
			return true;
		return false;		
	}
	
	public static function isPdfFile($file)
	{
		if(File::getFileType($file) == "pdf")
			return true;
		return false;
	}
	
	function isValidForThumb()
	{
		if($this->isImage() || ($this->isPdf() && ImageServer::isEnabledPdf()))
			return true;
		return false;
	}
}

class Location
{
	var $path;

	//
	// Split a file path into array elements
	// 
	public static function splitPath($dir)
	{
		$dir = stripslashes($dir);
		$path1 = preg_split("/[\\\\\/]+/", $dir);
		$path2 = array();
		for($i = 0; $i < count($path1); $i++)
		{
			if($path1[$i] == ".." || $path1[$i] == "." || $path1[$i] == "")
				continue;
			$path2[] = $path1[$i];
		}
		return $path2;
	}

	//
	// Get the current directory.
	// Options: Include the prefix ("./"); URL-encode the string; HTML-encode the string; return directory n-levels up
	// 
	function getDir($prefix, $encoded, $html, $up)
	{
		$dir = "";
		if($prefix == true)
			$dir .= "./";
		for($i = 0; $i < ((count($this->path) >= $up && $up > 0)?count($this->path)-$up:count($this->path)); $i++)
		{
			$temp = $this->path[$i];
			if($encoded)
				$temp = rawurlencode($temp);
			if($html)
				$temp = htmlspecialchars($temp);
			$dir .= $temp."/";
		}
		return $dir;
	}

	function getPathLink($i, $html)
	{
		if($html)
			return htmlspecialchars($this->path[$i]);
		else
			return $this->path[$i];
	}

	function getFullPath()
	{
		return (strlen(EncodeExplorer::getConfig('basedir')) > 0?EncodeExplorer::getConfig('basedir'):dirname($_SERVER['SCRIPT_FILENAME']))."/".$this->getDir(true, false, false, 0);
	}

	//
	// Debugging output
	// 
	function debug()
	{
		print_r($this->path);
		print("Dir with prefix: ".$this->getDir(true, false, false, 0)."\n");
		print("Dir without prefix: ".$this->getDir(false, false, false, 0)."\n");
		print("Upper dir with prefix: ".$this->getDir(true, false, false, 1)."\n");
		print("Upper dir without prefix: ".$this->getDir(false, false, false, 1)."\n");
	}


	//
	// Set the current directory
	// 
	function init()
	{
		if(!isset($_GET['dir']) || strlen($_GET['dir']) == 0)
		{
			$this->path = $this->splitPath(EncodeExplorer::getConfig('starting_dir'));
		}
		else
		{
			$this->path = $this->splitPath($_GET['dir']);
		}
	}
	
	//
	// Checks if the current directory is below the input path
	//
	function isSubDir($checkPath)
	{
		for($i = 0; $i < count($this->path); $i++)
		{
			if(strcmp($this->getDir(true, false, false, $i), $checkPath) == 0)
				return true;
		}
		return false;
	}
	
	//
	// Check if uploading is allowed into the current directory, based on the configuration
	//
	function uploadAllowed()
	{
		if(EncodeExplorer::getConfig('upload_enable') != true)
			return false;
		if(EncodeExplorer::getConfig('upload_dirs') == null || count(EncodeExplorer::getConfig('upload_dirs')) == 0)
			return true;
			
		$upload_dirs = EncodeExplorer::getConfig('upload_dirs');
		for($i = 0; $i < count($upload_dirs); $i++)
		{
			if($this->isSubDir($upload_dirs[$i]))
				return true;
		}
		return false;
	}
	
	function isWritable()
	{
		return is_writable($this->getDir(true, false, false, 0));
	}
	
	public static function isDirWritable($dir)
	{
		return is_writable($dir);
	}
	
	public static function isFileWritable($file)
	{
		if(file_exists($file))
		{
			if(is_writable($file))
				return true;
			else
				return false;
		}
		else if(Location::isDirWritable(dirname($file)))
			return true;
		else
			return false;
	}
}

class EncodeExplorer
{
	var $location;
	var $dirs;
	var $files;
	var $sort_by;
	var $sort_as;
	var $mobile;
	var $logging;
	var $spaceUsed;
	var $lang;
	
	//
	// Determine sorting, calculate space.
	// 
	function init()
	{
		$this->sort_by = "";
		$this->sort_as = "";
		if(isset($_GET["sort_by"]) && isset($_GET["sort_as"]))
		{
			if($_GET["sort_by"] == "name" || $_GET["sort_by"] == "size" || $_GET["sort_by"] == "mod")
				if($_GET["sort_as"] == "asc" || $_GET["sort_as"] == "desc")
				{
					$this->sort_by = $_GET["sort_by"];
					$this->sort_as = $_GET["sort_as"];
				}
		}
		if(strlen($this->sort_by) <= 0 || strlen($this->sort_as) <= 0)
		{
			$this->sort_by = "name";
			$this->sort_as = "desc";
		}
		
		
		global $_TRANSLATIONS;
		if(isset($_GET['lang']) && isset($_TRANSLATIONS[$_GET['lang']]))
			$this->lang = $_GET['lang'];
		else
			$this->lang = EncodeExplorer::getConfig("lang");
		
		$this->mobile = false;
		if(EncodeExplorer::getConfig("mobile_enabled") == true)
		{
			if((EncodeExplorer::getConfig("mobile_default") == true || isset($_GET['m'])) && !isset($_GET['s']))
				$this->mobile = true;
		}
		
		$this->logging = false;
		if(EncodeExplorer::getConfig("log_file") != null && strlen(EncodeExplorer::getConfig("log_file")) > 0)
			$this->logging = true;
	}

	//
	// Read the file list from the directory
	// 
	function readDir()
	{
		global $encodeExplorer;
		//
		// Reading the data of files and directories
		//
		if($open_dir = @opendir($this->location->getFullPath()))
		{
			$this->dirs = array();
			$this->files = array();
			while ($object = readdir($open_dir))
			{
				if($object != "." && $object != "..") 
				{
					if(is_dir($this->location->getDir(true, false, false, 0)."/".$object))
					{
						if(!in_array($object, EncodeExplorer::getConfig('hidden_dirs')))
							$this->dirs[] = new Dir($object, $this->location);
					}
					else if(!in_array($object, EncodeExplorer::getConfig('hidden_files')))
						$this->files[] = new File($object, $this->location);
				}
			}
			closedir($open_dir);
		}
		else
		{
			$encodeExplorer->setErrorString("unable_to_read_dir");;
		}
	}

	//
	// A recursive function for calculating the total used space
	// 
	function sum_dir($start_dir, $ignore_files, $levels = 1) 
	{
		if ($dir = opendir($start_dir)) 
		{
			$total = 0;
			while ((($file = readdir($dir)) !== false)) 
			{
				if (!in_array($file, $ignore_files)) 
				{
					if ((is_dir($start_dir . '/' . $file)) && ($levels - 1 >= 0)) 
					{
						$total += $this->sum_dir($start_dir . '/' . $file, $ignore_files, $levels-1);
					}
					elseif (is_file($start_dir . '/' . $file)) 
					{					
						$total += File::getFileSize($start_dir . '/' . $file) / 1024;
					}
				}
			}
			
			closedir($dir);
			return $total;
		}
	}

	function calculateSpace()
	{
		if(EncodeExplorer::getConfig('calculate_space_level') <= 0)
			return;
		$ignore_files = array('..', '.');
		$start_dir = getcwd();
		$spaceUsed = $this->sum_dir($start_dir, $ignore_files, EncodeExplorer::getConfig('calculate_space_level'));
		$this->spaceUsed = round($spaceUsed/1024, 3);
	}

	function sort()
	{
		if(is_array($this->files)){
			usort($this->files, "EncodeExplorer::cmp_".$this->sort_by);
			if($this->sort_as == "desc")
				$this->files = array_reverse($this->files);
		}
		
		if(is_array($this->dirs)){
			usort($this->dirs, "EncodeExplorer::cmp_name");
			if($this->sort_by == "name" && $this->sort_as == "desc")
				$this->dirs = array_reverse($this->dirs);
		}
	}

	function makeArrow($sort_by)
	{	
		if($this->sort_by == $sort_by && $this->sort_as == "asc")
		{
			$sort_as = "desc";
			$img = "arrow_up";
		}
		else
		{
			$sort_as = "asc";
			$img = "arrow_down";
		}

		if($sort_by == "name")
			$text = $this->getString("file_name");
		else if($sort_by == "size")
			$text = $this->getString("size");
		else if($sort_by == "mod")
			$text = $this->getString("last_changed");

		return "<a href=\"".$this->makeLink(false, false, $sort_by, $sort_as, null, $this->location->getDir(false, true, false, 0))."\">
			$text <img style=\"border:0;\" alt=\"".$sort_as."\" src=\"?img=".$img."\" /></a>";
	}
	
	function makeLink($switchVersion, $logout, $sort_by, $sort_as, $delete, $dir)
	{
		$link = "?";
		if($switchVersion == true && EncodeExplorer::getConfig("mobile_enabled") == true)
		{
			if($this->mobile == false)
				$link .= "m&amp;";
			else
				$link .= "s&amp;";
		}
		else if($this->mobile == true && EncodeExplorer::getConfig("mobile_enabled") == true && EncodeExplorer::getConfig("mobile_default") == false)
			$link .= "m&amp;";
		else if($this->mobile == false && EncodeExplorer::getConfig("mobile_enabled") == true && EncodeExplorer::getConfig("mobile_default") == true)
			$link .= "s&amp;";
			
		if($logout == true)
		{
			$link .= "logout";
			return $link;
		}
			
		if(isset($this->lang) && $this->lang != EncodeExplorer::getConfig("lang"))
			$link .= "lang=".$this->lang."&amp;";
			
		if($sort_by != null && strlen($sort_by) > 0)
			$link .= "sort_by=".$sort_by."&amp;";
			
		if($sort_as != null && strlen($sort_as) > 0)
			$link .= "sort_as=".$sort_as."&amp;";
		
		$link .= "dir=".$dir;
		if($delete != null)
			$link .= "&amp;del=".$delete;
		return $link;
	}

	function makeIcon($l)
	{
		$l = strtolower($l);
		return "?img=".$l;
	}

	function formatModTime($time)
	{
		$timeformat = "d.m.y H:i:s";
		if(EncodeExplorer::getConfig("time_format") != null && strlen(EncodeExplorer::getConfig("time_format")) > 0)
			$timeformat = EncodeExplorer::getConfig("time_format");
		return date($timeformat, $time);
	}

	function formatSize($size) 
	{
		$sizes = Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$y = $sizes[0];
		for ($i = 1; (($i < count($sizes)) && ($size >= 1024)); $i++) 
		{
			$size = $size / 1024;
			$y  = $sizes[$i];
		}
		return round($size, 2)." ".$y;
	}

	//
	// Debugging output
	// 
	function debug()
	{
		print("Explorer location: ".$this->location->getDir(true, false, false, 0)."\n");
		for($i = 0; $i < count($this->dirs); $i++)
			$this->dirs[$i]->output();
		for($i = 0; $i < count($this->files); $i++)
			$this->files[$i]->output();
	}
	
	//
	// Comparison functions for sorting.
	//
	
	public static function cmp_name($b, $a)
	{
		return strcasecmp($a->name, $b->name);
	}
	
	public static function cmp_size($a, $b)
	{
		return ($a->size - $b->size);
	}
	
	public static function cmp_mod($b, $a)
	{
		return ($a->modTime - $b->modTime);
	}
	
	//
	// The function for getting a translated string.
	// Falls back to english if the correct language is missing something.
	//
	public static function getLangString($stringName, $lang)
	{
		global $_TRANSLATIONS;
		if(isset($_TRANSLATIONS[$lang]) && is_array($_TRANSLATIONS[$lang]) 
			&& isset($_TRANSLATIONS[$lang][$stringName]))
			return $_TRANSLATIONS[$lang][$stringName];
		else if(isset($_TRANSLATIONS["en"]))// && is_array($_TRANSLATIONS["en"]) 
			//&& isset($_TRANSLATIONS["en"][$stringName]))
			return $_TRANSLATIONS["en"][$stringName];
		else
			return "Translation error";
	}
	
	function getString($stringName)
	{
		return EncodeExplorer::getLangString($stringName, $this->lang);
	}
	
	//
	// The function for getting configuration values
	//
	public static function getConfig($name)
	{
		global $_CONFIG;
		if(isset($_CONFIG) && isset($_CONFIG[$name]))
			return $_CONFIG[$name];
		return null;
	}
	
	public static function setError($message)
	{
		global $_ERROR;
		if(isset($_ERROR) && strlen($_ERROR) > 0)
			;// keep the first error and discard the rest
		else
			$_ERROR = $message;
	}
	
	function setErrorString($stringName)
	{
		EncodeExplorer::setError($this->getString($stringName));
	}

	//
	// Main function, activating tasks
	// 
	function run($location)
	{
		$this->location = $location;
		$this->calculateSpace();
		$this->readDir();
		$this->sort();
		$this->outputHtml();
	}
	
	public function printLoginBox()
	{
		?>
		<div id="login">
		<form enctype="multipart/form-data" action="<?php print $this->makeLink(false, false, null, null, null, ""); ?>" method="post">
		<?php 
		if(GateKeeper::isLoginRequired())
		{
			$require_username = false;
			foreach(EncodeExplorer::getConfig("users") as $user){
				if($user[0] != null && strlen($user[0]) > 0){
					$require_username = true;
					break;
				}
			}
			if($require_username)
			{
			?>
			<div><label for="user_name"><?php print $this->getString("username"); ?>:</label>
			<input type="text" name="user_name" value="" id="user_name" /></div>
			<?php 
			}
			?>
			<div><label for="user_pass"><?php print $this->getString("password"); ?>:</label>
			<input type="password" name="user_pass" id="user_pass" /></div>
			<div><input type="submit" value="<?php print $this->getString("log_in"); ?>" class="button" /></div>
		</form>
		</div>
	<?php 
		}
	}

	//
	// Printing the actual page
	// 
	function outputHtml()
	{
		global $_ERROR;
		global $_START_TIME;
?>
 
<?php css(); ?>
 <?php
if(($this->getConfig('log_file') != null && strlen($this->getConfig('log_file')) > 0)
	|| ($this->getConfig('thumbnails') != null && $this->getConfig('thumbnails') == true && $this->mobile == false)
	|| (GateKeeper::isDeleteAllowed()))
{ 
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
<?php
	if(GateKeeper::isDeleteAllowed()){
?>
	$('td.del a').click(function(){
		var answer = confirm('Are you sure you want to delete : \'' + $(this).attr("data-name") + "\' ?");
		return answer;
	});
<?php 
	}	
	if($this->logging == true)
	{ 
?>
		function logFileClick(path)
		{
			 $.ajax({
		        	async: false,
					type: "POST",
					data: {log: path},
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					cache: false
				});
		}
		
		$("a.file").click(function(){
			logFileClick("<?php print $this->location->getDir(true, true, false, 0);?>" + $(this).html());
			return true;
		});
<?php 
	}
	if(EncodeExplorer::getConfig("thumbnails") == true && $this->mobile == false)
	{
?>
		function positionThumbnail(e) {
			xOffset = 30;
			yOffset = 10;
			$("#thumb").css("left",(e.clientX + xOffset) + "px");

			diff = 0;
			if(e.clientY + $("#thumb").height() > $(window).height())
				diff = e.clientY + $("#thumb").height() - $(window).height();
			
			$("#thumb").css("top",(e.pageY - yOffset - diff) + "px");
		}
		
		$("a.thumb").hover(function(e){
			$("#thumb").remove();
			$("body").append("<div id=\"thumb\"><img src=\"?thumb="+ $(this).attr("href") +"\" alt=\"Preview\" \/><\/div>");
			positionThumbnail(e);
			$("#thumb").fadeIn("medium");
		},
		function(){
			$("#thumb").remove();
		});

		$("a.thumb").mousemove(function(e){
			positionThumbnail(e);
			});

		$("a.thumb").click(function(e){$("#thumb").remove(); return true;});
<?php 
	}
?>
	});
//]]>                
</script>
<?php 
}
?>
<title><?php if(EncodeExplorer::getConfig('main_title') != null) print EncodeExplorer::getConfig('main_title'); ?></title>
</head>
<body class="<?php print ($this->mobile == true?"mobile":"standard");?>">
<?php 
//
// Print the error (if there is something to print)
//
if(isset($_ERROR) && strlen($_ERROR) > 0)
{
	print "<div id=\"error\">".$_ERROR."</div>";
}
?>
<div id="frame">
<?php
if(EncodeExplorer::getConfig('show_top') == true)
{
?>
<div id="top">
	<a href="<?php print $this->makeLink(false, false, null, null, null, ""); ?>"><span><?php if(EncodeExplorer::getConfig('main_title') != null) print EncodeExplorer::getConfig('main_title'); ?></span></a>
<?php 
if(EncodeExplorer::getConfig("secondary_titles") != null && is_array(EncodeExplorer::getConfig("secondary_titles")) && count(EncodeExplorer::getConfig("secondary_titles")) > 0 && $this->mobile == false)
{
	$secondary_titles = EncodeExplorer::getConfig("secondary_titles");
	print "<div class=\"subtitle\">".$secondary_titles[array_rand($secondary_titles)]."</div>\n";
}
?>
</div>
<?php
}

// Checking if the user is allowed to access the page, otherwise showing the login box
if(!GateKeeper::isAccessAllowed())
{
	$this->printLoginBox();
}
else 
{
if($this->mobile == false && EncodeExplorer::getConfig("show_path") == true)
{
?>
<div class="breadcrumbs">
<a href="?dir="><?php print $this->getString("root"); ?></a>
<?php
	for($i = 0; $i < count($this->location->path); $i++)
	{
		print "&gt; <a href=\"".$this->makeLink(false, false, null, null, null, $this->location->getDir(false, true, false, count($this->location->path) - $i - 1))."\">";
		print $this->location->getPathLink($i, true);
		print "</a>\n";
	}
?>
</div>
<?php 
}
?>

<!-- START: List table -->
<table class="table">
<?php 
if($this->mobile == false)
{
?>
<tr class="row one header">
	<td class="icon"> </td>
	<td class="name"><?php print $this->makeArrow("name");?></td>
	<td class="size"><?php print $this->makeArrow("size"); ?></td>
	<td class="changed"><?php print $this->makeArrow("mod"); ?></td>
	<?php if($this->mobile == false && GateKeeper::isDeleteAllowed()){?>
	<td class="del"><?php print EncodeExplorer::getString("del"); ?></td>
	<?php } ?>
</tr>
<?php 
}
?>
<tr class="row two">
	<td class="icon"><img alt="dir" src="?img=directory" /></td>
	<td colspan="<?php print (($this->mobile == true?2:(GateKeeper::isDeleteAllowed()?4:3))); ?>" class="long">
		<a class="item" href="<?php print $this->makeLink(false, false, null, null, null, $this->location->getDir(false, true, false, 1)); ?>">..</a>
	</td>
</tr>
<?php
//
// Ready to display folders and files.
//
$row = 1;

//
// Folders first
//
if($this->dirs)
{
	foreach ($this->dirs as $dir)
	{
		$row_style = ($row ? "one" : "two");
		print "<tr class=\"row ".$row_style."\">\n";
		print "<td class=\"icon\"><img alt=\"dir\" src=\"?img=directory\" /></td>\n";
		print "<td class=\"name\" colspan=\"".($this->mobile == true?2:3)."\">\n";
		print "<a href=\"".$this->makeLink(false, false, null, null, null, $this->location->getDir(false, true, false, 0).$dir->getNameEncoded())."\" class=\"item dir\">";
		print $dir->getNameHtml();
		print "</a>\n";
		print "</td>\n";
		if($this->mobile == false && GateKeeper::isDeleteAllowed()){
			print "<td class=\"del\"><a data-name=\"".htmlentities($dir->getName())."\" href=\"".$this->makeLink(false, false, null, null, $this->location->getDir(false, true, false, 0).$dir->getNameEncoded(), $this->location->getDir(false, true, false, 0))."\"><img src=\"?img=del\" alt=\"Delete\" /></a></td>";
		}
		print "</tr>\n";
		$row =! $row;
	}
}

//
// Now the files
//
if($this->files)
{
	$count = 0;
	foreach ($this->files as $file)
	{
		$row_style = ($row ? "one" : "two");
		print "<tr class=\"row ".$row_style.(++$count == count($this->files)?" last":"")."\">\n";
		print "<td class=\"icon\"><img alt=\"".$file->getType()."\" src=\"".$this->makeIcon($file->getType())."\" /></td>\n";
		print "<td class=\"name\">\n";
		print "\t\t<a href=\"".$this->location->getDir(false, true, false, 0).$file->getNameEncoded()."\"";
		if(EncodeExplorer::getConfig('open_in_new_window') == true)
			print "target=\"_blank\"";
		print " class=\"item file";
		if($file->isValidForThumb())
			print " thumb";
		print "\">";
		print $file->getNameHtml();
		if($this->mobile == true)
		{
			print "<span class =\"size\">".$this->formatSize($file->getSize())."</span>";
		}
		print "</a>\n";
		print "</td>\n";
		if($this->mobile != true)
		{
			print "<td class=\"size\">".$this->formatSize($file->getSize())."</td>\n";
			print "<td class=\"changed\">".$this->formatModTime($file->getModTime())."</td>\n";
		}
		if($this->mobile == false && GateKeeper::isDeleteAllowed()){
			print "<td class=\"del\">
				<a data-name=\"".htmlentities($file->getName())."\" href=\"".$this->makeLink(false, false, null, null, $this->location->getDir(false, true, false, 0).$file->getNameEncoded(), $this->location->getDir(false, true, false, 0))."\">
					<img src=\"?img=del\" alt=\"Delete\" />
				</a>
			</td>";
		}
		print "</tr>\n";
		$row =! $row;
	}
}


//
// The files and folders have been displayed
//
?>

</table>
<!-- END: List table -->
<?php 
}
?>
</div>

<?php
if(GateKeeper::isAccessAllowed() && GateKeeper::showLoginBox()){
?>
<!-- START: Login area -->
<form enctype="multipart/form-data" method="post">
	<div id="login_bar">
	<?php print $this->getString("username"); ?>:
	<input type="text" name="user_name" value="" id="user_name" />
	<?php print $this->getString("password"); ?>:
	<input type="password" name="user_pass" id="user_pass" />
	<input type="submit" class="submit" value="<?php print $this->getString("log_in"); ?>" />
	<div class="bar"></div>
	</div>
</form>
<!-- END: Login area -->
<?php 
}

if(GateKeeper::isAccessAllowed() && $this->location->uploadAllowed() && (GateKeeper::isUploadAllowed() || GateKeeper::isNewdirAllowed()))
{
?>
<!-- START: Upload area -->
<form enctype="multipart/form-data" method="post">
	<div id="upload">
		<?php 
		if(GateKeeper::isNewdirAllowed()){
		?>
		<div id="newdir_container">
			<input name="userdir" type="text" class="upload_dirname" />
			<input type="submit" value="<?php print $this->getString("make_directory"); ?>" />
		</div>
		<?php 
		}
		if(GateKeeper::isUploadAllowed()){
		?>
		<div id="upload_container">
			<input name="userfile" type="file" class="upload_file" />
			<input type="submit" value="<?php print $this->getString("upload"); ?>" class="upload_sumbit" />
		</div>
		<?php 
		}
		?>
		<div class="bar"></div>
	</div>
</form>
<!-- END: Upload area -->
<?php
}

?>
<!-- START: Info area -->
<div id="info">
<?php
if(GateKeeper::isUserLoggedIn())
	print "<a href=\"".$this->makeLink(false, true, null, null, null, "")."\">".$this->getString("log_out")."</a> | ";

if(EncodeExplorer::getConfig("mobile_enabled") == true)
{
	print "<a href=\"".$this->makeLink(true, false, null, null, null, $this->location->getDir(false, true, false, 0))."\">\n";
	print ($this->mobile == true)?$this->getString("standard_version"):$this->getString("mobile_version")."\n";
	print "</a> | \n";
}
if(GateKeeper::isAccessAllowed() && $this->getConfig("calculate_space_level") > 0 && $this->mobile == false)
{
	print $this->getString("total_used_space").": ".$this->spaceUsed." MB | ";
}
if($this->mobile == false && $this->getConfig("show_load_time") == true)
{
	printf($this->getString("page_load_time")." | ", (microtime(TRUE) - $_START_TIME)*1000);
}
?> 
<a href="http://encode-explorer.siineiolekala.net">Encode Explorer</a>
</div>
 
	
<?php
	}
}

//
// This is where the system is activated. 
// We check if the user wants an image and show it. If not, we show the explorer.
//
$encodeExplorer = new EncodeExplorer();
$encodeExplorer->init();

  
	$encodeExplorer->run();
 
 