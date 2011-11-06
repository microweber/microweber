<?php

// Spanish Language Module for joomlaXplorer (translated by J. Pedro Flor P. y Actualizado por Udo Llorens)
global $_VERSION;

$GLOBALS["charset"] = "iso-8859-1";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "ERROR(ES)",
	"back"			=> "Ir Atr&aacute;s",
	
	// root
	"home"			=> "El directorio home no existe, revise su configuraci&oacute;n.",
	"abovehome"		=> "El directorio actual no puede estar arriba del directorio home.",
	"targetabovehome"	=> "El directorio objetivo no puede estar arriba del directorio home.",
	
	// exist
	"direxist"		=> "Este directorio no existe.",
	//"filedoesexist"	=>  "Este archivo ya existe.",
	"fileexist"		=> "Este archivo no existe.",
	"itemdoesexist"		=> "Este art&iacute;culo ya existe.",
	"itemexist"		=> "Este art&iacute;culo no existe.",
	"targetexist"		=> "El directorio objetivo no existe.",
	"targetdoesexist"	=> "El art&iacute;culo objetivo ya existe.",
	
	// open
	"opendir"		=> "Incapaz de abrir directorio.",
	"readdir"		=> "Incapaz de leer directorio.",
	
	// access
	"accessdir"		=> "Ud. no est&aacute; permitido accesar este directorio.",
	"accessfile"		=> "Ud. no est&aacute; permitido accesar a este archivo.",
	"accessitem"		=> "Ud. no est&aacute; permitido accesar a este art&iacute;culo.",
	"accessfunc"		=> "Ud. no est&aacute; permitido usar esta funcion.",
	"accesstarget"		=> "Ud. no est&aacute; permitido accesar al directorio objetivo.",
	
	// actions
	"permread"		=> "Fracaso reuniendo permisos.",
	"permchange"		=> "Fracaso en Cambio de permisos.",
	"openfile"		=> "Fracaso abriendo archivo.",
	"savefile"		=> "Fracaso guardando archivo.",
	"createfile"		=> "Fracaso creando archivo.",
	"createdir"		=> "Fracaso creando Directorio.",
	"uploadfile"		=> "Fracaso subiendo archivo.",
	"copyitem"		=> "Fracaso Copiando.",
	"moveitem"		=> "Fracaso Moviendo.",
	"delitem"		=> "Fracaso Borrando.",
	"chpass"		=> "Fracaso Cambiando password.",
	"deluser"		=> "Fracaso Removiendo usuario.",
	"adduser"		=> "Fracaso Agragando usuario.",
	"saveuser"		=> "Fracaso Guardadno usuario.",
	"searchnothing"		=> "Ud. debe suministrar algo para la busqueda.",
	
	// misc
	"miscnofunc"		=> "Funci&oacute;n no disponible.",
	"miscfilesize"		=> "Archivo excede maximo tama&ntilde;o.",
	"miscfilepart"		=> "Archivo fue parcialmente subido.",
	"miscnoname"		=> "Ud. debe suministrar un nombre.",
	"miscselitems"		=> "Ud. no tiene seleccionado(s) ningun art&iacute;culo.",
	"miscdelitems"		=> "Est&aacute; seguro de querer borrar este(os) {0} art&iacute;culo(s)?",
	"miscdeluser"		=> "Est&aacute; seguro de querer borrar usuario '{0}'?",
	"miscnopassdiff"	=> "Nuevo password no difiere del actual.",
	"miscnopassmatch"	=> "No coinciden los Passwords.",
	"miscfieldmissed"	=> "Ud. fall&oacute; en un importante campo.",
	"miscnouserpass"	=> "Usuario o password incorrecto.",
	"miscselfremove"	=> "Ud. no puede borrarse a si mismo.",
	"miscuserexist"		=> "Usuario ya existe.",
	"miscnofinduser"	=> "No se puede encontrar usuario.",
	"extract_noarchive" => "The File is no extractable Archive.",
	"extract_unknowntype" => "Archivo comprimido desconocido",
	
	'chmod_none_not_allowed' => 'El cambio de permisos a <ninguno> no se permite',
	'archive_dir_notexists' => 'El directorio que has elegido para guardar no existe.',
	'archive_dir_unwritable' => 'Por favor eliga un directorio con permisos de escritura para guardr el archivo comprimido.',
	'archive_creation_failed' => 'No se pudo guardar el archivo comprimido'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "PERMISOS CAMBIADOS",
	"editlink"		=> "EDITAR",
	"downlink"		=> "DESCARGAR",
	"uplink"		=> "ARRIBA",
	"homelink"		=> "INICIO",
	"reloadlink"		=> "RECARGAR",
	"copylink"		=> "COPIAR",
	"movelink"		=> "MOVER",
	"dellink"		=> "BORRAR",
	"comprlink"		=> "ARCHIVAR",
	"adminlink"		=> "ADMINISTRAR",
	"logoutlink"		=> "SALIR",
	"uploadlink"		=> "SUBIR",
	"searchlink"		=> "B&Uacute;SQUEDA",
	"extractlink"	=> "Extraer Archivo Comprimido",
	'chmodlink'		=> 'Cambio (chmod) de permisos (Directorio/Archivos(s)))', // new mic
	'mossysinfolink'	=> 'Información de Sistema', // new mic
	'logolink'		=> 'Ir a la página de JoomlaeXtplorer', // new mic
	
	// list
	"nameheader"		=> "Nombre",
	"sizeheader"		=> "Tama&ntilde;o",
	"typeheader"		=> "Tipo",
	"modifheader"		=> "Modificado",
	"permheader"		=> "Permisos",
	"actionheader"		=> "Acciones",
	"pathheader"		=> "Ruta",
	
	// buttons
	"btncancel"		=> "Cancelar",
	"btnsave"		=> "Grabar",
	"btnchange"		=> "Cambiar",
	"btnreset"		=> "Restablecer",
	"btnclose"		=> "Cerrar",
	"btncreate"		=> "Crear",
	"btnsearch"		=> "Buscar",
	"btnupload"		=> "Subir",
	"btncopy"		=> "Copiar",
	"btnmove"		=> "Mover",
	"btnlogin"		=> "Login",
	"btnlogout"		=> "Salir",
	"btnadd"		=> "A&ntilde;adir",
	"btnedit"		=> "Editar",
	"btnremove"		=> "Remover",
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENAME',
	'confirm_delete_file' => 'Seguro que quieres borrar el archivo? \\n%s',
	'success_delete_file' => 'Archivos borrados correctamente.',
	'success_rename_file' => 'El archivo/directorio %s Se renombro correctamente a %s.',
	
	
	// actions
	"actdir"		=> "Directorio",
	"actperms"		=> "Cambiar permisos",
	"actedit"		=> "Editar archivo",
	"actsearchresults"	=> "Resultado de busqueda.",
	"actcopyitems"		=> "Copiar art&iacute;culos(s)",
	"actcopyfrom"		=> "Copia de /%s a /%s ",
	"actmoveitems"		=> "Mover art&iacute;culo(s)",
	"actmovefrom"		=> "Mover de /%s a /%s ",
	"actlogin"		=> "Login",
	"actloginheader"	=> "Login para usar QuiXplorer",
	"actadmin"		=> "Administraci&oacute;n",
	"actchpwd"		=> "Cambiar password",
	"actusers"		=> "Usuarios",
	"actarchive"		=> "Archivar item(s)",
	"actupload"		=> "Subir Archivo(s)",
	
	// misc
	"miscitems"		=> "Art&iacute;culo(s)",
	"miscfree"		=> "Libre",
	"miscusername"		=> "Nombre de usuario",
	"miscpassword"		=> "Password",
	"miscoldpass"		=> "Password Antiguo",
	"miscnewpass"		=> "Password Nuevo",
	"miscconfpass"		=> "Confirmar password",
	"miscconfnewpass"	=> "Confirmar nuevo password",
	"miscchpass"		=> "Cambiar password",
	"mischomedir"		=> "Directorio Home",
	"mischomeurl"		=> "URL Home",
	"miscshowhidden"	=> "Mostrar art&iacute;culos ocultos",
	"mischidepattern"	=> "Ocultar patr&oacute;n",
	"miscperms"		=> "Permisos",
	"miscuseritems"		=> "(nombre, directorio home, mostrar art&iacute;culos ocultos, permisos, activar)",
	"miscadduser"		=> "a&ntilde;adir usuario",
	"miscedituser"		=> "editar usario '%s'",
	"miscactive"		=> "Activar",
	"misclang"		=> "Lenguaje",
	"miscnoresult"		=> "Resultado(s) no disponible(s).",
	"miscsubdirs"		=> "B&uacute;squeda de subdirectorios",
	"miscpermnames"		=> array("Solo ver","Modificar","Cambiar password","Modificar & Cambiar password", "Administrador"),
	"miscyesno"		=> array("Si","No","S","N"),
	"miscchmod"		=> array("Propietario", "Grupo", "P&uacute;blico"),
	// from here all new by mic
	'miscowner'			=> 'Owner',
	'miscownerdesc'		=> '<strong>Descripci&oacute;n:</strong><br />Usuario (UID) /<br />Grupo (GID)<br />Permisos Actuales:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'Informaci&oacute;n de sistema: eXplorer',
	'sisysteminfo'		=> 'Informaci&oacute;n de Sistema',
	'sibuilton'			=> 'Sistema Operativo',
	'sidbversion'		=> 'Versi&oacute;n de la Base de Datos (MySQL)',
	'siphpversion'		=> 'Versi&oacute;n del PHP',
	'siphpupdate'		=> 'INFORMACI&Oacute;N: <span style="color: red;">La version de PHP que usas <strong>no es</strong> moderna!</span><br />Para garantizar toda la funcionalidad de eXtplorer,<br />debes usar al menos <strong>la Versi&oacute;n de PHP 4.3/strong>!',
	'siwebserver'		=> 'Servidor Web',
	'siwebsphpif'		=> 'Servidor Web - Interfaz de PHP',
	'simamboversion'	=> 'Versi&oacute;n de eXtplorer',
	'siuseragent'		=> 'Versi&oacute;n del navegador',
	'sirelevantsettings' => 'Opciones importantes de PHP',
	'sisafemode'		=> 'Modo Seguro',
	'sibasedir'			=> 'Open basedir',
	'sidisplayerrors'	=> 'Errores de PHP',
	'sishortopentags'	=> 'Short Open Tags',
	'sifileuploads'		=> 'Datei Uploads',
	'simagicquotes'		=> 'Magic Quotes',
	'siregglobals'		=> 'Register Globals',
	'sioutputbuf'		=> 'Bufer de Salida',
	'sisesssavepath'	=> 'Session Savepath',
	'sisessautostart'	=> 'Session auto start',
	'sixmlenabled'		=> 'XML enabled',
	'sizlibenabled'		=> 'ZLIB enabled',
	'sidisabledfuncs'	=> 'funciones No Activas',
	'sieditor'			=> ' Editor WYSIWYG',
	'siconfigfile'		=> 'Archivo de Configuraci&oacute;n',
	'siphpinfo'			=> 'Informaci&oacute;n de PHP',
	'siphpinformation'	=> 'Informaci&oacute;n de PHP',
	'sipermissions'		=> 'Permisos',
	'sidirperms'		=> 'Permisos de directorios',
	'sidirpermsmess'	=> 'Para asegurar el correcto funcionamiento de eXtplorer, los siguientes directorios han de tener permisos de escritura [chmod 0777]',
	'sionoff'			=> array( 'Encendido', 'Apagado' ),
	
	'extract_warning' => "Seguro que quieres descomprimir este archivo aqui ?\\nEsto sobreescribir&aacute; archivos si no se usa con cuidado!",
	'extract_success' => "La descompresi&oacute;n sali&oacute; bien",
	'extract_failure' => "La descompresi&oacute;n sali&oacute; mal",
	
	'overwrite_files' => 'Sobreescribir el/los siguiente(s) archivo(s)?',
	"viewlink"		=> "VER",
	"actview"		=> "Se muestra el origen del archivo",
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_chmod.php file
	'recurse_subdirs'	=> 'Recurse into subdirectories?',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to footer.php file
	'check_version'	=> 'Check for latest version',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_rename.php file
	'rename_file'	=>	'Renombrar un directorio o archivo...',
	'newname'		=>	'Nuevo Nombre',
	
	// added by Paulino Michelazzo (paulino@michelazzo.com.br) to fun_edit.php file
	'returndir'	=>	'&iquest;volver al directorio tras guardar?',
	'line'		=> 	'Linea',
	'column'	=>	'Columna',
	'wordwrap'	=>	': (IE only)',
	'copyfile'	=>	'&iquest;Copiar el archivo sobre este nombre de archivo?',
	
	// Bookmarks
	'quick_jump' => 'Ir directamente a',
	'already_bookmarked' => '&Eacute;ste directorio ya se a&ntilde;adi&oacute; a los favoritos',
	'bookmark_was_added' => '&Eacute;ste directorio se a&ntilde;adi&oacute; a los favoritos',
	'not_a_bookmark' => 'Este directorio no es un favorito.',
	'bookmark_was_removed' => 'Se borr&oacute; &eacute;ste directorio de la lista de favoritos.',
	'bookmarkfile_not_writable' => "Fallo al %s el favorito.\n el archivo de favoritos '%s' \nno es escribible.",
	
	'lbl_add_bookmark' => 'A&ntilde;adir este directorio como favorito',
	'lbl_remove_bookmark' => 'Borrar este directorio de la lista de favoritos',
	
	'enter_alias_name' => 'Por favor introduzca un sobrenombre para este favorito',
	
	'normal_compression' => 'compresi&oacute;n normal',
	'good_compression' => 'compresi&oacute;n buena',
	'best_compression' => 'la mejor compresi&oacute;n',
	'no_compression' => 'sin compresi&oacute;n',
	
	'creating_archive' => 'Creando archivo comprimido...',
	'processed_x_files' => 'Procesados %s de %s Archivos',
	
	'ftp_header' => 'Autenticación de FTP local',
	'ftp_login_lbl' => 'Por favor introduce las credenciales de acceso al FTP',
	'ftp_login_name' => 'Nombre de usuario del FTP',
	'ftp_login_pass' => 'Contrase&ntilde;a del FTP',
	'ftp_hostname_port' => 'Servidor y puerto del FTP <br />(El puerto es opcional)',
	'ftp_login_check' => 'Comprobando la conexi&oacute;n con el FTP...',
	'ftp_connection_failed' => "No se pudo contactar con el servidor FTP. \nPor favor, comprueba que tienes el FTP  funcionando en tu servidor.",
	'ftp_login_failed' => "Fallo de acceso al FTP. Por favor comprueba usuario y contrase&ntilde;a y vuelve a intentarlo.",
		
	'switch_file_mode' => 'Modo Actual: <strong>%s</strong>. Podr&iacute;a cambiar al modo %s',
	'symlink_target' => 'Destino del Link Simb&oacute;lico',
	
	"permchange"		=> "Funcion&oacute; el CHMOD:",
	"savefile"		=> "Se guard&oacute; el archivo.",
	"moveitem"		=> "Funcion&oacute; el traslado.",
	"copyitem"		=> "Funcion&oacute; el copiado.",
	'archive_name' 	=> 'Nombre del archivo comprimido',
	'archive_saveToDir' 	=> 'Guardar el comprimido a este directorio',
	
	'editor_simple'	=> 'Modo de edici&oacute;n simple',
	'editor_syntaxhighlight'	=> 'Modo de sistaxis resaltada',

	'newlink'	=> 'Nuevo Archivo o Directorio',
	'show_directories' => 'Mostrar Directorios',
	'actlogin_success' => '&iexcl;Acceso Concedido!',
	'actlogin_failure' => 'Acceso denegado, int&eacute;ntelo de nuevo.',
	'directory_tree' => '&Aacute;rbol de Directorios',
	'browsing_directory' => 'Directorio Actual',
	'filter_grid' => 'Filtro',
	'paging_page' => 'P&aacute;gina',
	'paging_of_X' => 'de {0}',
	'paging_firstpage' => 'Primera P&aacute;gina',
	'paging_lastpage' => '&Uacute;ltima P&aacute;gina',
	'paging_nextpage' => 'P&aacute;gina Siguiente',
	'paging_prevpage' => 'P&aacute;gina Anterior',
	
	'paging_info' => 'Mostrando Elementos {0} - {1} of {2}',
	'paging_noitems' => 'No hay nada que mostrar',
	'aboutlink' => 'Acerca de...',
	'password_warning_title' => '&iexcl;Importante - Cambie su contrase&ntilde;a!',
	'password_warning_text' => 'El usuario con el que se ha registrado (admin con contrase&ntilde;a admin) corresponde a las credenciales por defecto de eXtplorer. Esto es un riesgo alto de seguridad y debe arreglarlo inmediatamente',
	'change_password_success' => 'Se cambi&oacute; su contrase&ntilde;a',
	'success' => '&Eacute;xito',
	'failure' => 'Fallo',
	'dialog_title' => 'Website Dialog',
	'upload_processing' => 'Procesando la subida del archivo. Espere, por favor...',
	'upload_completed' => 'Archivo subido correctamente',
	'acttransfer' => 'Transferir desde otro servidor',
	'transfer_processing' => 'Procesando la transferencia entre servidores. Espere, por favor...',
	'transfer_completed' => 'Tranferencia completada!',
	'max_file_size' => 'Tamaño Máximo del archivo',
	'max_post_size' => 'Límite de subida',
	'done' => 'Hecho.',
	'permissions_processing' => 'Aplicando los permisos. Espere, por favor...',
	'archive_created' => 'Se cre&oacute; el archivo comprimido!',
	'save_processing' => 'Guardando archivo...',
	'current_user' => 'Este Script funciona con los permisos del usuario:',
	'your_version' => 'Tu versi&oacute;n',
	'search_processing' => 'Buscando. Espere, por favor...',
	'url_to_file' => 'URL del archivo',
	'file' => 'Archivo'
);
?>
