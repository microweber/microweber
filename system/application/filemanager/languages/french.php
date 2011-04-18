<?php

// French Language Module for joomlaXplorer (translated by Olivier Pariseau and Alexandre PRIETO)

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "Erreur(s)",
	"back"			=> "Page précédente",
	
	// root
	"home"				=> "Le répertoire home n'existe pas, vérifiez vos préférences.",
	"abovehome"			=> "Le répertoire courant ne semble pas être dans home.",
	"targetabovehome"	=> "Le répertoire cible ne semble pas être dans home.",
	
	// exist
	"direxist"			=> "Ce répertoire est inexistant.",
	//"filedoesexist"	=> "Ce fichier existe déjà.",
	"fileexist"			=> "Ce fichier est inexistant.",
	"itemdoesexist"		=> "Cet élément existe déjà.",
	"itemexist"			=> "Cet élément est inexistant.",
	"targetexist"		=> "Le répertoire cible est inexistant.",
	"targetdoesexist"	=> "Cet élément cible existe déjà.",
	
	// open
	"opendir"		=> "Ouverture du répertoire impossible.",
	"readdir"		=> "Lecture du répertoire impossible.",
	
	// access
	"accessdir"			=> "Vous ne possédez pas les droits pour accéder à ce répertoire.",
	"accessfile"		=> "Vous ne possédez pas les droits pour accéder à ce fichier.",
	"accessitem"		=> "Vous ne possédez pas les droits pour accéder à cet élément.",
	"accessfunc"		=> "Vous ne possédez pas les droits pour utiliser cette fonction.",
	"accesstarget"		=> "Vous ne possédez pas les droits pour accéder au repertoire cible.",
	
	// actions
	"permread"		=> "Echec de la lecture des permissions.",
	"permchange"	=> "Echec du changement des permissions.",
	"openfile"		=> "Echec ouverture du fichier.",
	"savefile"		=> "Echec de la sauvegarde du fichier.",
	"createfile"	=> "Echec de la création du fichier.",
	"createdir"		=> "Echec de la création du répertoire.",
	"uploadfile"	=> "Echec envoi du fichier.",
	"copyitem"		=> "Echec de la copie.",
	"moveitem"		=> "Echec du déplacement.",
	"delitem"		=> "Echec de la suppression.",
	"chpass"		=> "Echec du changement de mot de passe.",
	"deluser"		=> "Echec de la suppression Usager.",
	"adduser"		=> "Echec ajout Usager.",
	"saveuser"		=> "Echec sauvegarde Usager.",
	"searchnothing"	=> "Vous devez entrez un élément é chercher.",
	
	// misc
	"miscnofunc"		=> "Fonctionalité non disponible.",
	"miscfilesize"		=> "La taille du fichier excède la taille maximale autorisée.",
	"miscfilepart"		=> "Envoi du fichier non complété.",
	"miscnoname"		=> "Vous devez entrer un nom.",
	"miscselitems"		=> "Aucun élément sélectionné.",
	"miscdelitems"		=> "Etes-vous sûr de vouloir supprimer : {0} élément(s)?",
	"miscdeluser"		=> "Etes-vous sûr de vouloir supprimer l'usager {0}?",
	"miscnopassdiff"	=> "Le nouveau mot de passe est indentique au précédent.",
	"miscnopassmatch"	=> "Les mots de passe différent.",
	"miscfieldmissed"	=> "Un champs requis est vide.",
	"miscnouserpass"	=> "Nom ou mot de passe invalide.",
	"miscselfremove"	=> "Vous ne pouvez pas supprimer votre compte.",
	"miscuserexist"		=> "Ce nom existe déjà.",
	"miscnofinduser"	=> "Usager non trouvé.",
	"extract_noarchive" => "Ce fichier ne correspond pas une archive extractible.",
	"extract_unknowntype" => "Type Archive inconnue",
	
	'chmod_none_not_allowed'	=> 'La suppression de tous les droits est impossible',
	'archive_dir_notexists'		=> 'Le répertoire spécifié pour la sauvegarde est inexistant.',
	'archive_dir_unwritable'	=> 'Le répertoire spécifié pour la sauvegarde doit être en droit Ecriture.',
	'archive_creation_failed'	=> 'Echec de la création du fichier Archive'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "Changer les permissions",
	"editlink"		=> "Editer",
	"downlink"		=> "Télécharger",
	"uplink"		=> "Dossier parent",
	"homelink"		=> "Racine",
	"reloadlink"	=> "Rafra&icirc;chir",
	"copylink"		=> "Copier",
	"movelink"		=> "Déplacer",
	"dellink"		=> "Supprimer",
	"comprlink"		=> "Archiver",
	"adminlink"		=> "Administration",
	"logoutlink"	=> "Déconnecter",
	"uploadlink"	=> "Envoyer",
	"searchlink"	=> "Rechercher",
	"extractlink"	=> "Extraction Archive",
	'chmodlink'		=> 'Changer les droits (CHMOD) des répertoire/Fichiers', // new mic
	'mossysinfolink'	=> 'Informations Système, Server, PHP, mySQL)', // new mic
	'logolink'		=> 'Visiter le site de eXtplorer (nouvelle fenêtre)', // new mic
	
	// list
	"nameheader"		=> "Nom",
	"sizeheader"		=> "Taille",
	"typeheader"		=> "Type",
	"modifheader"		=> "Modifié",
	"permheader"		=> "Permissions",
	"actionheader"		=> "Actions",
	"pathheader"		=> "Chemin",
	
	// buttons
	"btncancel"		=> "Annuler",
	"btnsave"		=> "Sauver",
	"btnchange"		=> "Changer",
	"btnreset"		=> "Réinitialiser",
	"btnclose"		=> "Fermer",
	"btncreate"		=> "Créer",
	"btnsearch"		=> "Chercher",
	"btnupload"		=> "Envoyer",
	"btncopy"		=> "Copier",
	"btnmove"		=> "Déplacer",
	"btnlogin"		=> "Connecter",
	"btnlogout"		=> "Déconnecter",
	"btnadd"		=> "Ajouter",
	"btnedit"		=> "Editer",
	"btnremove"		=> "Supprimer",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'			=> "Renommer",
	'confirm_delete_file' 	=> 'Etes-vous sûr de vouloir supprimer le fichier %s',
	'success_delete_file'	=> 'Fichier supprimé avec succés.',
	'success_rename_file' 	=> 'Le répertoire/fichier %s a été renommé %s.',
	
	
	// actions
	"actdir"			=> "répertoire",
	"actperms"			=> "Changer les permissions",
	"actedit"			=> "Editer le fichier",
	"actsearchresults"	=> "Résultats de la recherche",
	"actcopyitems"		=> "Copier les éléments",
	"actcopyfrom"		=> "Copier de /%s à /%s ",
	"actmoveitems"		=> "Déplacer les éléments",
	"actmovefrom"		=> "Déplacer de /%s à /%s ",
	"actlogin"			=> "Connecter",
	"actloginheader"	=> "Connecter pour utiliser QuiXplorer",
	"actadmin"			=> "Administration",
	"actchpwd"			=> "Changer le mot de passe",
	"actusers"			=> "Usagers",
	"actarchive"		=> "Archiver les éléments",
	"actupload"			=> "Envoyer les fichiers",
	
	// misc
	"miscitems"				=> "Elèments",
	"miscfree"				=> "Disponible",
	"miscusername"			=> "Usager",
	"miscpassword"			=> "Mot de passe",
	"miscoldpass"			=> "Ancien mot de passe",
	"miscnewpass"			=> "Nouveau mot de passe",
	"miscconfpass"			=> "Confirmer le mot de passe",
	"miscconfnewpass"		=> "Confirmer le nouveau mot de passe",
	"miscchpass"			=> "Changer le mot de passe",
	"mischomedir"			=> "répertoire home",
	"mischomeurl"			=> "Chemin Racine",
	"miscshowhidden"		=> "Voir les éléments cachès",
	"mischidepattern"		=> "Cacher pattern",
	"miscperms"				=> "Permissions",
	"miscuseritems"			=> "(nom, répertoire home, Voir les éléments cachès, permissions, actif)",
	"miscadduser"			=> "Ajouter un usager",
	"miscedituser"			=> "Editer usager '%s'",
	"miscactive"			=> "Actif",
	"misclang"				=> "Langue",
	"miscnoresult"			=> "Aucun rèsultats.",
	"miscsubdirs"			=> "Rechercher dans les sous-répertoires",
	"miscpermnames"			=> array("Lecture seulement","Modifier","Changer le mot de passe","Modifier & Changer le mot de passe","Administrateur"),
	"miscyesno"			 	=> array("Oui","Non","O","N"),
	"miscchmod"				=> array("Propriétaire", "Groupe", "Publique"),
	// from here all new by mic
	'miscowner'			=> 'Propriétaire',
	'miscownerdesc'		=> '<strong>Description:</strong><br />Propriétaire (UID) /<br />Groupe (GID)<br />Droits actuels:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'Informations Système',
	'sisysteminfo'		=> 'Info Système',
	'sibuilton'			=> 'OS',
	'sidbversion'		=> 'Version Base de Données (MySQL)',
	'siphpversion'		=> 'Version PHP',
	'siphpupdate'		=> 'INFORMATION: <span style="color: red;">La version de PHP que vous utilisez n\'est <strong>plus</strong> d\'actualité!</span><br />Afin de garantir un fonctionnement maximum de eXtplorer et addons,<br />Vous devez utiliser au minimum <strong>PHP.Version 4.3</strong>!',
	'siwebserver'		=> 'Webserver',
	'siwebsphpif'		=> 'WebServer - Interface PHP',
	'simamboversion'	=> 'Version eXtplorer',
	'siuseragent'		=> 'Version du Navigateur',
	'sirelevantsettings' => 'Paramètres PHP avancés',
	'sisafemode'		=> 'Mode sécurisé',
	'sibasedir'			=> 'Ouvrir répertoire de base',
	'sidisplayerrors'	=> 'Erreurs PHP',
	'sishortopentags'	=> 'Tags',
	'sifileuploads'		=> 'Date Envoi',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Buffer',
	'sisesssavepath'	=> 'Chemin de Sauvegarde Session',
	'sisessautostart'	=> 'Session Automatique',
	'sixmlenabled'		=> 'XML activé',
	'sizlibenabled'		=> 'ZLIB activé',
	'sidisabledfuncs'	=> 'Fonction non validées',
	'sieditor'			=> 'Editeur WYSIWYG',
	'siconfigfile'		=> 'Fichier de configuration',
	'siphpinfo'			=> 'PHP Info',
	'siphpinformation'	=> 'PHP Information',
	'sipermissions'		=> 'Permissions',
	'sidirperms'		=> 'Permissions répertoire',
	'sidirpermsmess'	=> 'Pour obtenir un fonctionnement total, assurez vous que vous possédez les droits en écriture sur les répertoires et fichiers (chmod). Vous pouvez vous connecter en FTP pour modifier ces droits',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Voulez-vous réellement extraire ce fichier Ici? Ce fichier remplacera le fichier si existant!",
	'extract_success' => "Extraction réussie",
	'extract_failure' => "Extraction échouée",
	
	'overwrite_files'	=> 'Remplacer les fichiers?',
	"viewlink"			=> "Aperçu",
	"actview"			=> "Aperçu des sources du fichier",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Récursif dans les sous-répertoires',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Vérifier si une version plus récente existe',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Renommer le répertoire ou fichier...',
	'newname'		=>	'Nouveau nom',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'Retourner au répertoire aprés sauvegarde',
	'line'		=> 	'Ligne',
	'column'	=>	'Colonne',
	'wordwrap'	=>	'Wordwrap: (IE seulement)',
	'copyfile'	=>	'Copier le fichier avec ce nom de fichier',
	
	// Bookmarks
	'quick_jump' 			=> 'Saut rapide vers',
	'already_bookmarked' 	=> 'Ce répertoire existe déjà dans le signet',
	'bookmark_was_added' 	=> 'répertoire ajouté à la liste des signets.',
	'not_a_bookmark' 		=> 'Ce répertoire ne correspond pas à un signet.',
	'bookmark_was_removed' 	=> 'Ce répertoire à été supprimé de la liste des signets.',
	'bookmarkfile_not_writable' => "Echec lors de %s dans le signet. Le fichier signet '%s' ne possède pas les droits Ecriture.",
	
	'lbl_add_bookmark' 		=> 'Ajouter ce répertoire au signet',
	'lbl_remove_bookmark' 	=> 'Supprimer ce répertoire de la liste des signets',
	
	'enter_alias_name' 		=> 'Veuillez entrez un alias pour ce signet',
	
	'normal_compression' 	=> 'Compression normal',
	'good_compression'		=> 'Compression élevée',
	'best_compression' 		=> 'Compression optimale',
	'no_compression' 		=> 'Aucune compression',
	
	'creating_archive' 		=> 'Création du Fichier Archive...',
	'processed_x_files' 	=> '%s de %s fichiers traités',
	
	'ftp_header' 			=> 'Authentification FTP Locale',
	'ftp_login_lbl' 		=> 'Veuillez entrez un login de connexion pour le serveur FTP',
	'ftp_login_name' 		=> "Nom d'utilisateur FTP",
	'ftp_login_pass' 		=> 'Mot de passe FTP',
	'ftp_hostname_port' 	=> 'Nom du serveur FTP et Port <br />(Le port est optionnel)',
	'ftp_login_check' 		=> 'Test connexion serveur FTP...',
	'ftp_connection_failed' => "Connexion au serveur FTP impossible. Veuillez vérifiez que le service FTP soit activé sur le serveur.",
	'ftp_login_failed' 		=> "Login FTP incorrect. Veuillez vérifiez le nom et mot de passe utilisateur.",
		
	'switch_file_mode' 		=> 'Mode courant: %s. Vous pouvez passer en mode %s.',
	'symlink_target' 		=> 'Cible du lien symbolique',
	
	"permchange"			=> "Changement CHMOD réussi:",
	"savefile"				=> "Le fichier est sauvegardé.",
	"moveitem"				=> "Déplacement réussi.",
	"copyitem"				=> "Copie réussie.",
	'archive_name' 			=> "Nom Archive",
	'archive_saveToDir' 	=> "Sauvegarder dans ce répertoire",
	
	'editor_simple'			=> 'Mode Editeur Simple',
	'editor_syntaxhighlight'	=> 'Coloration Syntaxique',

	'newlink'				=> 'Nouveau Fichier/Dossier',
	'show_directories' 		=> 'Voir les Dossiers',
	'actlogin_success' 		=> 'Connexion réussie!',
	'actlogin_failure' 		=> 'Connexion échoué. Veuillez essayer à nouveau.',
	'directory_tree' 		=> 'Arborescense Dossier',
	'browsing_directory' 	=> 'Parcourir Dossier',
	'filter_grid' 			=> 'Filtre',
	'paging_page'			=> 'Page',
	'paging_of_X'			=> 'de {0}',
	'paging_firstpage' 		=> 'Première page',
	'paging_lastpage' 		=> 'Dernière page',
	'paging_nextpage' 		=> 'Page suivante',
	'paging_prevpage' 		=> 'Page précédente',
	
	'paging_info' 			=> 'Affiche Elément {0} - {1} de {2}',
	'paging_noitems' 		=> 'Aucun élément à afficher',
	'aboutlink' 			=> 'Au sujet de...',
	'password_warning_title' 	=> 'Important - Changer votre mot de passe!',
	'password_warning_text' 	=> 'Le compte usager pour votre accès (admin avec mot de passe admin) correspond au compte privilégié eXtplorer par defaut. Votre installation eXtplorer est sujette à intrusion et vous devez corriger cette faille de sécurité immédiatement!',
	'change_password_success' 	=> 'Votre mot de passe a été changé!',
	'success' 				=> 'Succés',
	'failure' 				=> 'Echec',
	'dialog_title' 			=> 'Dialogue site',
	'upload_processing' 	=> 'Envoi...',
	'upload_completed' 		=> 'Envoi effectué!',
	'acttransfer' 			=> 'Transfert depuis une URL',
	'transfer_processing' 	=> 'Transfert...',
	'transfer_completed' 	=> 'Transfert effectué!',
	'max_file_size' 		=> 'Poids Maximum',
	'max_post_size' 		=> 'Limite Envoi',
	'done' 					=> 'Annuler.',
	'permissions_processing' => 'Application des Permissions...',
	'archive_created'		=> 'Le fichier Archives est créé!',
	'save_processing' 		=> 'Sauvegarde...',
	'current_user' 			=> 'Le script courant fonctionne avec les permissions utilisateur de:',
	'your_version' 			=> 'Votre Version',
	'search_processing' 	=> 'Recherche...',
	'url_to_file' 			=> 'URL du Fichier',
	'file' 					=> 'Fichier'
);
?>
