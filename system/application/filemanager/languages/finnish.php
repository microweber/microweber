<?php
// Finnish language file for eXtplorer 2.1.0 Beta2 UTF-8
// Dated 21.06.2009
// Author: Markku Suominen / admin@joomlaportal.fi
// Author/Editor: Sami Haaranen / mortti@joomlaportal.fi
// Finnish Joomla translation team, http://www.joomlaportal.fi
global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d.m.Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "virheet",
	"message"			=> "viesti(ä)",
	"back"			=> "Palaa",

	// root
	"home"			=> "Kotihakemistoa ei ole, tarkista asetuksesi.",
	"abovehome"		=> "Nykyinen hakemisto ei saa olla kotihakemiston yläpuolella.",
	"targetabovehome"	=> "Kohdehakemisto ei saa olla kotihakemiston yläpuolella.",

	// exist
	"direxist"		=> "Hakemistoa ei ole.",
	//"filedoesexist"	=> "This file already exists.",
	"fileexist"		=> "Tiedostoa ei ole.",
	"itemdoesexist"		=> "Nimike on jo olemassa.",
	"itemexist"		=> "Nimike ei ole olemassa.",
	"targetexist"		=> "Kohdehakemistoa ei ole.",
	"targetdoesexist"	=> "Kohdenimike on jo olemassa.",

	// open
	"opendir"		=> "Hakemistoa ei voi avata.",
	"readdir"		=> "Hakemistoa ei voi lukea.",

	// access
	"accessdir"		=> "Sinulla ei ole valtuuksia tähän hakemistoon.",
	"accessfile"		=> "Sinulla ei ole valtuuksia tähän tiedostoon.",
	"accessitem"		=> "Sinulla ei ole valtuuksia tähän nimikkeeseen.",
	"accessfunc"		=> "Sinulla ei ole valtuuksia tähän toimintoon.",
	"accesstarget"		=> "Sinulla ei ole valtuuksia kohdehakemistoon.",

	// actions
	"permread"		=> "Käyttöoikeuksien luku epäonnistui.",
	"permchange"		=> "Käyttöoikeuksien muutos epäonnistui.",
	"openfile"		=> "Tiedoston avaaminen epäonnistui.",
	"savefile"		=> "Tiedoston tallennus epäonnistui.",
	"createfile"		=> "Tiedoston luonti epäonnistui.",
	"createdir"		=> "Hakemiston luonti epäonnistui.",
	"uploadfile"		=> "Tiedoston vienti palvelimelle epäonnistui.",
	"copyitem"		=> "Kopiointi epäonnistui.",
	"moveitem"		=> "Siirto epäonnistui.",
	"delitem"		=> "Poisto epäonnistui.",
	"chpass"		=> "Salasanan vaihto epäonnistui.",
	"deluser"		=> "Käyttäjän poisto epäonnistui.",
	"adduser"		=> "Käyttäjän lisäys epäonnistui.",
	"saveuser"		=> "Käyttäjän tallennus epäonnistui.",
	"searchnothing"		=> "Sinun pitää antaa jotain etsittävää.",

	// misc
	"miscnofunc"		=> "Toiminto ei ole k&auml;ytettäviss&auml;.",
	"miscfilesize"		=> "Tiedosto koko ylitt&auml;&auml; suurimman sallitun arvon.",
	"miscfilepart"		=> "Tiedoston vienti palvelimelle onnistui vain osittain.",
	"miscnoname"		=> "Anna nimi.",
	"miscselitems"		=> "Et ole valinnut yht&auml;&auml;n nimikett&auml;.",
	"miscdelitems"		=> "Haluatko varmasti poistaa n&auml;m&auml; {0} nimike(tt&auml;)?",
	"miscdeluser"		=> "Haluatko varmasti poistaa k&auml;ytt&auml;j&auml;n '{0}'?",
	"miscnopassdiff"	=> "Uusi salasana ei eroa nykyisest&auml;.",
	"miscnopassmatch"	=> "Salasanat eivät t&auml;sm&auml;&auml;.",
	"miscfieldmissed"	=> "Ohitit t&auml;rke&auml;n kent&auml;n.",
	"miscnouserpass"	=> "K&auml;ytt&auml;j&auml;nimi tai salasana on v&auml;&auml;r&auml;.",
	"miscselfremove"	=> "Et voi poistaa omaa tunnustasi.",
	"miscuserexist"		=> "K&auml;ytt&auml;j&auml; on jo olemassa.",
	"miscnofinduser"	=> "K&auml;ytt&auml;j&auml;&auml; ei l&oumlydy.",
	"extract_noarchive" => "Tiedostomuoto ei ole sellainen joka voidaan purkaa.",
	"extract_unknowntype" => "Tuntematon arkistointimuoto",
	
	'chmod_none_not_allowed' => 'Käyttöoikeuksien muutos <none> ei ole sallittu',
	'archive_dir_notexists' => 'Tallennuksiin määriteltyä hakemistoa ei ole olemassa.',
	'archive_dir_unwritable' => 'Määrittele kirjoitettava hakemisto tallentaaksesi arkisto sinne.',
	'archive_creation_failed' => 'Arkistotiedoston tallennus epäonnistui'
	
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Muuta oikeuksia",
	"editlink"		=> "Muokkaa",
	"downlink"		=> "Lataa",
	"uplink"		=> "Ylös",
	"homelink"		=> "Juurihakemisto",
	"reloadlink"	=> "Päivitä",
	"copylink"		=> "Kopioi",
	"movelink"		=> "Siirrä",
	"dellink"		=> "Poista",
	"comprlink"		=> "Arkistoi",
	"adminlink"		=> "Hallinta",
	"logoutlink"	=> "Poistu",
	"uploadlink"	=> "Vie palvelimelle",
	"searchlink"	=> "Etsi",
	'difflink'      => 'Vertaa',
	"extractlink"	=> "Pura arkistotiedosto",
	'chmodlink'		=> 'Muuta (chmod) oikeudet (kansio/tiedosto(t))', // new mic
	'mossysinfolink'	=> 'eXtplorer järjestelmätiedot (eXtplorer, palvelin, PHP, mySQL)', // new mic
	'logolink'		=> 'Siirry eXtplorer sivustolle (uusi ikkuna)', // new mic

	// list
	"nameheader"		=> "Nimi",
	"sizeheader"		=> "Koko",
	"typeheader"		=> "Tyyppi",
	"modifheader"		=> "Muutettu",
	"permheader"		=> "Oikeudet",
	"actionheader"		=> "Toiminnot",
	"pathheader"		=> "Polku",

	// buttons
	"btncancel"		=> "Peru",
	"btnsave"		=> "Tallenna",
	"btnchange"		=> "Muuta",
	"btnreset"		=> "Peru",
	"btnclose"		=> "Sulje",
	"btncreate"		=> "Luo",
	"btnsearch"		=> "Etsi",
	"btnupload"		=> "Vie palvelimelle",
	"btncopy"		=> "Kopioi",
	"btnmove"		=> "Siirrä",
	"btnlogin"		=> "Kirjaudu",
	"btnlogout"		=> "Poistu",
	"btnadd"		=> "Lisää",
	"btnedit"		=> "Muokkaa",
	"btnremove"		=> "Poista",
	"btndiff"       => "Vertaa",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'Nimeä',
	'confirm_delete_file' => 'Haluatko varmasti poistaa tiedoston? <br />%s',
	'success_delete_file' => 'Nimike poistettu.',
	'success_rename_file' => 'Hakemisto/tiedosto  %s nimettiin nimellä %s.',
	
	// actions
	"actdir"		=> "Hakemisto",
	"actperms"		=> "Muuta oikeuksia",
	"actedit"		=> "Muokkaa tiedostoa",
	"actsearchresults"	=> "Haun tulokset",
	"actcopyitems"		=> "Kopioi nimikkeet",
	"actcopyfrom"		=> "Kopioi kohteesta /%s kohteeseen /%s ",
	"actmoveitems"		=> "Siirrä nimikkeet",
	"actmovefrom"		=> "Siirrä kohteesta /%s kohteeseen /%s ",
	"actlogin"		=> "Kirjaudu",
	"actloginheader"	=> "Kirjaudu käyttääksesi joomlaXploreria",
	"actadmin"		=> "Hallinta",
	"actchpwd"		=> "Muuta salasana",
	"actusers"		=> "Käyttäjät",
	"actarchive"		=> "Arkistoi nimikkeet",
	"actupload"		=> "Vie tiedostot palvelimelle",

	// misc
	"miscitems"		=> "Nimike(ttä)",
	"miscfree"		=> "Vapaana",
	"miscusername"		=> "Käyttäjätunnus",
	"miscpassword"		=> "Salasana",
	"miscoldpass"		=> "Vanha salasana",
	"miscnewpass"		=> "Uusi salasana",
	"miscconfpass"		=> "Vahvista salasana",
	"miscconfnewpass"	=> "Vahvista uusi salasana",
	"miscchpass"		=> "Muuta salasana",
	"mischomedir"		=> "Kotihakemisto",
	"mischomeurl"		=> "Koti URL",
	"miscshowhidden"	=> "Näytä piilotetut nimikkeet",
	"mischidepattern"	=> "Piilota kuvio",
	"miscperms"		=> "Oikeudet",
	"miscuseritems"		=> "(nimi, kotihakemisto, näytä piilotetut nimikkeet, oikeudet, aktiivi)",
	"miscadduser"		=> "lisää käyttäjä",
	"miscedituser"		=> "muokkaa käyttäjää '%s'",
	"miscactive"		=> "Aktiivi",
	"misclang"		=> "Kieli",
	"miscnoresult"		=> "Ei tuloksia.",
	"miscsubdirs"		=> "Etsi alahakemistoista",
	"miscpermnames"		=> array("Vain katselu","Muokkaa","Muuta salasana","Muokkaa ja muuta salasana",
					"Hallinta"),
	"miscyesno"		=> array("Kyllä","Ei","K","E"),
	"miscchmod"		=> array("Omistaja", "Ryhmä", "Julkinen"),
	'misccontent'    => "Tiedostosisältö",

	// from here all new by mic
	'miscowner'			=> 'Omistaja',
	'miscownerdesc'		=> '<strong>Kuvaus:</strong><br />Käyttäjä (UID) /<br />Ryhmä (GID)<br />Nykyiset oikeudet:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> "eXtplorer järjestelmän tiedot",
	'sisysteminfo'		=> 'Järjestelmän tiedot',
	'sibuilton'			=> 'Käyttöjärjestelmä',
	'sidbversion'		=> 'Tietokannan versio (MySQL)',
	'siphpversion'		=> 'PHP versio',
	'siphpupdate'		=> 'TIETOJA: <span style="color: red;">Käyttämäsi PHP-versio <strong>ei ole </strong> riittävän uusi</span><br />Käyttääksesi tuotteen toimintoja ja lisäosia,<br />vanhin hyväksytty versio on <strong>PHP 4.3</strong>!',
	'siwebserver'		=> 'Web-palvelin',
	'siwebsphpif'		=> 'Web-palvelin - PHP rajapinta',
	'simamboversion'	=> 'eXtplorer versio',
	'siuseragent'		=> 'Selaimen versio',
	'sirelevantsettings' => 'Tärkeät PHP-asetukset',
	'sisafemode'		=> 'Safe Mode',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'PHP-virheet',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Tiedostojen lataaminen palvelimelle',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Output Buffer',
	'sisesssavepath'	=> 'Istunnon tallennuspolku',
	'sisessautostart'	=> 'Istunnon automaattinen käynnistys',
	'sixmlenabled'		=> 'XML käytössä',
	'sizlibenabled'		=> 'ZLIB käytössä',
	'sidisabledfuncs'	=> 'Estetyt funktiot',
	'sieditor'			=> 'WYSIWYG-editori',
	'siconfigfile'		=> 'Asetustiedosto',
	'siphpinfo'			=> 'PHP-tiedot',
	'siphpinformation'	=> 'PHP-tiedot',
	'sipermissions'		=> 'Käyttöoikeudet',
	'sidirperms'		=> 'Hakemiston käyttöoikeudet',
	'sidirpermsmess'	=> 'Jotta kaikki tuotteen toiminnot ja funktiot toimivat oikein, seuraavien kansioihin tulee voida kirjoittaa [chmod 0777]',
	'sionoff'			=> array( 'Päällä', 'Pois' ),
	
	'extract_warning' => "Haluatko purkaa tiedoston tähän hakemistoon?<br />Käytä toimintoa varoen, sillä olemassa olevat tiedostot ylikirjoitetaan arkistotiedoston tiedostoilla.",
	'extract_success' => "Tiedoston purkaminen onnistui",
	'extract_failure' => "Purkaminen epäonnistui",
	
	'overwrite_files' => 'Korvaa nykyiset tiedostot?',
	"viewlink"		=> "Näytä",
	"actview"		=> "Näytetään tiedoston sisältö",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Kohdista toiminto myös alihakemistoihin?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Tarkista viimeisin versio',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Nimeä tiedosto tai hakemisto...',
	'newname'		=>	'Uusi nimi',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Palaa hakemistoon tallentamisen jälkeen?',
	'line'		=> 	'Rivi',
	'column'	=>	'Sarake',
	'wordwrap'	=>	'Rivitä: (vain IE)',
	'copyfile'	=>	'Kopioi tiedosto tälle nimelle',
	
	// Bookmarks
	'quick_jump' => 'Siirry kohteeseen',
	'already_bookmarked' => 'Hakemisto on jo kirjanmerkitty',
	'bookmark_was_added' => 'Hakemisto lisättiin kirjanmerkki listaan.',
	'not_a_bookmark' => 'Hakemisto ei ole kirjanmerkki.',
	'bookmark_was_removed' => 'Hakemisto poistettiin kirjanmerkki listasta.',
	'bookmarkfile_not_writable' => "Kirjanmerkkiin liittyvä toiminto %s epäonnistui.\n Kirjanmerkkitiedosto '%s' \n on kirjoitussuojattu.",
	
	'lbl_add_bookmark' => 'Lisää hakemisto kirjanmerkkeihin',
	'lbl_remove_bookmark' => 'Poista hakemisto kirjanmerkki listasta',
	
	'enter_alias_name' => 'Kirjoita kirjanmerkin alias',
	
	'normal_compression' => 'normaali pakkaus',
	'good_compression' => 'parempi pakkaus',
	'best_compression' => 'paras pakkaus',
	'no_compression' => 'ei pakkausta',
	
	'creating_archive' => 'Luodaan arkistotiedosto ...',
	'processed_x_files' => 'Käyty läpi %s / %s tiedostoa',
	
	'ftp_header' => 'Paikallinen FTP-autentikointi',
	'ftp_login_lbl' => 'Anna FTP-palvelimen vaatimat kirjatumistiedot',
	'ftp_login_name' => 'FTP käyttäjätunnus',
	'ftp_login_pass' => 'FTP salasana',
	'ftp_hostname_port' => 'FTP-palvelimen nimi ja portti <br />(portti on valinnainen)',
	'ftp_login_check' => 'Tarkistetaan FTP-yhteyttä...',
	'ftp_connection_failed' => "FTP-palvelimeen ei saada yhteyttä. \nTarkista että FTP-palvelin on toiminnassa.",
	'ftp_login_failed' => "FTP-kirjautuminen ei onnistunut. Tarkista käyttäjätunnus ja salasana ja yritä uudelleen.",
		
	'switch_file_mode' => 'Nykyinen tila: <strong>%s</strong>. Voit vaihtaa tilaan %s.',
	'symlink_target' => 'Symbolisen linkin kohde',
	
	"permchange"		=> "CHMOD-toiminto onnistui:",
	"savefile"		=> "Tiedosto tallennettiin.",
	"moveitem"		=> "Siirto onnistui.",
	"copyitem"		=> "Kopiointi onnistui.",
	'archive_name' 	=> 'Arkistotiedoston nimi',
	'archive_saveToDir' 	=> 'Tallenna arkistotiedosto tähän hakemistoon',
	
	'editor_simple'	=> 'Yksinkertainen editori -tila',
	'editor_syntaxhighlight'	=> 'Syntaksin korostus -tila',

	'newlink'	=> 'Uusi tiedosto/hakemisto',
	'show_directories' => 'Näytä hakemistot',
	'actlogin_success' => 'Kirjautuminen onnistui',
	'actlogin_failure' => 'Kirjautuminen epäonnistui, yritä uudelleen.',
	'directory_tree' => 'Hakemistopuu',
	'browsing_directory' => 'Selataan hakemistoa',
	'filter_grid' => 'Suodatin',
	'paging_page' => 'Sivu',
	'paging_of_X' => '/ {0}',
	'paging_firstpage' => 'Ensimmäinen sivu',
	'paging_lastpage' => 'Viimeinen sivu',
	'paging_nextpage' => 'Seuraava sivu',
	'paging_prevpage' => 'Edellinen sivu',
	
	'paging_info' => 'Näytetään nimikkeet {0} - {1} / {2}',
	'paging_noitems' => 'Näytettäviä nimikkeitä ei ole',
	'aboutlink' => 'Tietoja...',
	'password_warning_title' => 'Tärkeää - muuta salasanasi',
	'password_warning_text' => 'Olet kirjautunut eXtplorer-sovellukseen oletuskäyttäjätilin (käyttäjätunnus admin, salasana admin) avulla. eXtplorerin väärinkäyttö on mahdollista, joten vaihda admin-käyttäjän salasana välittömästi.',
	'change_password_success' => 'Salasanasi on muutettu',
	'success' => 'Onnistui',
	'failure' => 'Epäonnistui',
	'dialog_title' => 'Web-sivuston valintaikkuna',
	'upload_processing' => 'Lataus käynnissä...',
	'upload_completed' => 'Lataus onnistui.',
	'acttransfer' => 'Siirrä toiselta palvelimelta',
	'transfer_processing' => 'Siirto toiselta palvelimelta käynnissä...',
	'transfer_completed' => 'Siirto suoritettu.',
	'max_file_size' => 'Suurin tiedosto koko',
	'max_post_size' => 'Suurin lataus raja',
	'done' => 'Valmis.',
	'permissions_processing' => 'Käyttöoikeuksien lisäys käynnissä...',
	'archive_created' => 'Arkistotiedosto on luotu',
	'save_processing' => 'Tallennetaan tiedostoa...',
	'current_user' => 'Tämä skripti ajetaan seuraavien käyttäjien käyttöoikeuksilla:',
	'your_version' => 'Versiosi',
	'search_processing' => 'Etsitään...',
	'url_to_file' => 'Tiedoston URL',
	'file' => 'Tiedosto'
	
);
?>
