<?php
// Portuguese Translation by Paulino Michelazzo - paulino@michelazzo.com.br
// http://www.noritmodomambo.org
// Version: 1.0
// Date: Sep, 07 2006

global $_VERSION;

$GLOBALS["charset"] = "UTF-8";
$GLOBALS["text_dir"] = "ltr";
$GLOBALS["date_fmt"] = "d/m/Y H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "ERRO(S)",
	"back"			=> "Voltar",
	
	// root
	"home"			=> "O diretório HOME não existe. Verifique suas opções",
	"abovehome"		=> "O diretório atual não pode estar acima do diretório home.",
	"targetabovehome"	=> "O diretório de destino não pode estar acima do diretório home.",
	
	// exist
	"direxist"		=> "Este diretório não existe",
	"fileexist"		=> "Este arquivo não existe",
	"itemdoesexist"	=> "Este item já existe",
	"itemexist"		=> "Este item não existe",
	"targetexist"	=> "O diretório de destino não existe",
	"targetdoesexist"	=> "O item de destino já existe",
	
	// open
	"opendir"		=> "Impossível abrir o diretório",
	"readdir"		=> "Impossível ler o diretório",
	
	// access
	"accessdir"		=> "Você não tem permissão para acessar este diretório",
	"accessfile"	=> "Você não tem permissão para acessar este arquivo",
	"accessitem"	=> "Você não tem permissão para acessar este item",
	"accessfunc"	=> "Você não tem permissão para usar esta função",
	"accesstarget"	=> "Você não tem permissão para acessar o diretório de destino",
	
	// actions
	"permread"		=> "Falha ao buscar permissões",
	"permchange"	=> "Falha ao alterar permissões",
	"openfile"		=> "Falha ao abrir o arquivo",
	"savefile"		=> "Falha ao salvar o arquivo",
	"createfile"	=> "Falha ao criar o arquivo",
	"createdir"		=> "Falha ao criar o diretório",
	"uploadfile"	=> "Falha ao enviar o arquivo",
	"copyitem"		=> "Falha ao copiar",
	"moveitem"		=> "Falha ao mover",
	"delitem"		=> "Falha ao apagar",
	"chpass"		=> "Falha ao alterar a senha",
	"deluser"		=> "Falha ao remover usuário",
	"adduser"		=> "Falha ao adicionar usuário",
	"saveuser"		=> "Falha ao salvar usuário",
	"searchnothing"	=> "Você deve informar o que buscar",
	
	// misc
	"miscnofunc"		=> "Função não dispon�vel",
	"miscfilesize"		=> "O arquivo excede o tamanho máximo permitido",
	"miscfilepart"		=> "O arquivo foi enviado parcialmente",
	"miscnoname"		=> "Informe um nome",
	"miscselitems"		=> "Selecione pelo menos um item",
	"miscdelitems"		=> "Tem certeza que deseja apagar estes {0} item(s)?",
	"miscdeluser"		=> "Tem certeza que deseja apagar o usuário {0}?",
	"miscnopassdiff"	=> "A nova senha não é diferente da atual",
	"miscnopassmatch"	=> "As senhas não coincidem",
	"miscfieldmissed"	=> "Você esqueceu um campo importante",
	"miscnouserpass"	=> "Nome de usuário ou senha incorretos",
	"miscselfremove"	=> "Você não pode se auto-remover",
	"miscuserexist"		=> "Usuário já existe",
	"miscnofinduser"	=> "Usuário não encontrado",
	"extract_noarchive" => "O arquivo não é um arquivo compactado",
	"extract_unknowntype" => "Tipo de Arquivo Desconhecido",
	
	'chmod_none_not_allowed' => 'Changing Permissions to <none> is not allowed',
	'archive_dir_notexists' => 'The Save-To Directory you have specified does not exist.',
	'archive_dir_unwritable' => 'Please specify a writable directory to save the archive to.',
	'archive_creation_failed' => 'Failed saving the Archive File'
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "ALTERAR PERMISSõES",
	"editlink"		=> "EDITAR",
	"downlink"		=> "DOWNLOAD",
	"uplink"		=> "SOBE",
	"homelink"		=> "HOME",
	"reloadlink"	=> "ATUALIZAR",
	"copylink"		=> "COPIAR",
	"movelink"		=> "MOVER",
	"dellink"		=> "APAGAR",
	"comprlink"		=> "ARQUIVO",
	"adminlink"		=> "ADMIN",
	"logoutlink"	=> "SAIR",
	"uploadlink"	=> "ENVIAR",
	"searchlink"	=> "BUSCAR",
	"extractlink"	=> "Extrair Arquivo",
	'chmodlink'		=> 'Alterar (chmod) Permissões (Pasta/Arquivo(s))',
	'mossysinfolink'	=> 'eXtplorer Informação do Sistema (eXtplorer, Servidor, PHP, MySQL)',
	'logolink'		=> 'Ir para o site do joomlaXplorer (nova janela)',
	
	// user messages, new in joomlaXplorer 1.3.0
	'renamelink'	=> 'RENOMEAR',
	'confirm_delete_file' => 'Você tem certeza que deseja apagar este arquivo? \\n%s',
	'success_delete_file' => 'Item(s) apagado com sucesso',
	'success_rename_file' => 'O arquivo/diretório %s foi renomeado com sucesso para %s',
	
	// list
	"nameheader"		=> "Nome",
	"sizeheader"		=> "Tamanho",
	"typeheader"		=> "Tipo",
	"modifheader"		=> "Modificado",
	"permheader"		=> "Perm's",
	"actionheader"		=> "Ações",
	"pathheader"		=> "Caminho",
	
	// buttons
	"btncancel"		=> "Cancelar",
	"btnsave"		=> "Salvar",
	"btnchange"		=> "Alterar",
	"btnreset"		=> "Limpar",
	"btnclose"		=> "Fechar",
	"btncreate"		=> "Criar",
	"btnsearch"		=> "Buscar",
	"btnupload"		=> "Enviar",
	"btncopy"		=> "Copiar",
	"btnmove"		=> "Mover",
	"btnlogin"		=> "Login",
	"btnlogout"		=> "Sair",
	"btnadd"		=> "Adicionar",
	"btnedit"		=> "Editar",
	"btnremove"		=> "Remover",
	
	// actions
	"actdir"		=> "Diretório",
	"actperms"		=> "Alterar permissões",
	"actedit"		=> "Editar arquivo",
	"actsearchresults"	=> "Buscar resultados",
	"actcopyitems"	=> "Copiar item(s)",
	"actcopyfrom"	=> "Copiar de /%s para /%s ",
	"actmoveitems"	=> "Mover item(s)",
	"actmovefrom"	=> "Mover de /%s para /%s ",
	"actlogin"		=> "Login",
	"actloginheader" => "Login para usar QuiXplorer",
	"actadmin"		=> "Administração",
	"actchpwd"		=> "Alterar senha",
	"actusers"		=> "Usuários",
	"actarchive"	=> "Arquivar item(s)",
	"actupload"		=> "Enviar arquivo(s)",
	
	// misc
	"miscitems"		=> "Item(s)",
	"miscfree"		=> "Espaço Livre",
	"miscusername"	=> "Nome de usuário",
	"miscpassword"	=> "Senha",
	"miscoldpass"	=> "Senha antiga",
	"miscnewpass"	=> "Nova senha",
	"miscconfpass"	=> "Confirmar senha",
	"miscconfnewpass"	=> "Confirmar nova senha",
	"miscchpass"	=> "Alterar senha",
	"mischomedir"	=> "Diretório Home",
	"mischomeurl"	=> "Home URL",
	"miscshowhidden"	=> "Mostrar itens ocultos",
	"mischidepattern"	=> "Ocultar padrão",
	"miscperms"		=> "Permissões",
	"miscuseritems"	=> "(nome, diretório home, mostrar itens ocultos, permissões, ativo)",
	"miscadduser"	=> "adicionar usuário",
	"miscedituser"	=> "editar usuário '%s'",
	"miscactive"	=> "Ativo",
	"misclang"		=> "Idioma",
	"miscnoresult"	=> "Sem resultados.",
	"miscsubdirs"	=> "Buscar subdiretórios",
	"miscpermnames"	=> array("Somente ver","Modificar","Alterar senha","Modificar & Alterar senha","Administração"),
	"miscyesno"		=> array("Sim","Não","S","N"),
	"miscchmod"		=> array("Dono", "Grupo", "Público"),

	// from here all new by mic
	'miscowner'			=> 'Proprietário',
	'miscownerdesc'		=> '<strong>Descrição:</strong><br />Usuário (UID) /<br />Grupo (GID)<br />Permissões atuais:<br /><strong> %s ( %s ) </strong>/<br /><strong> %s ( %s )</strong>',

	// sysinfo (new by mic)
	'simamsysinfo'		=> 'eXtplorer Infos do Sistema',
	'sisysteminfo'		=> 'Sistema',
	'sibuilton'			=> 'Sistema Operacional',
	'sidbversion'		=> 'Versão do Banco de Dados MySQL',
	'siphpversion'		=> 'Versão do PHP',
	'siphpupdate'		=> 'INFORMAçãO: <span style="color: red;">A versão do PHP instalada <strong>não é</strong> atual!</span><br />Para garantir todas as funcionalidades do eXtplorer e seus componentes adicionais,<br />você deve usar pelo menos a versão <strong>4.3</strong>!',
	'siwebserver'		=> 'Servidor Web',
	'siwebsphpif'		=> 'Interface Servidor Web - PHP',
	'simamboversion'	=> 'Versão do '.$_VERSION->PRODUCT,
	'siuseragent'		=> 'Versão do Navegador',
	'sirelevantsettings' => 'Configurações Importantes do PHP',
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
	'siconfigfile'		=> 'Arquivo de Configuração',
	'siphpinfo'			=> 'Info PHP',
	'siphpinformation'	=> 'Informação do PHP',
	'sipermissions'		=> 'Permissões',
	'sidirperms'		=> 'Permissões de Diretórios',
	'sidirpermsmess'	=> 'Tenha certeza que todas as funções e funcionalidades do eXtplorer estão funcionando corretamente verificando se as pastas a seguir possuem permissão de escrita [chmod 0777]',
	'sionoff'			=> array( 'On', 'Off' ),
	
	'extract_warning' => "Você tem certeza que deseja extrair este arquivo? Aqui?\\nEsta operação pode sobrescrever os arquivos existentes se não usada com cuidado!",
	'extract_success' => "Sucesso na Extração",
	'extract_failure' => "Falha na Extração",
	
	'overwrite_files' => 'Sobrescrever arquivos existentes?',
	"viewlink"		=> "VER",
	"actview"		=> "Mostrar fonte do arquivo",
	
	// added by Paulino Michelazzo to fun_chmod.php file
	'recurse_subdirs'	=> 'Recursivo nos Subdiretórios?',
	
	// added by Paulino Michelazzo to footer.php file
	'check_version'	=> 'Verificar nova versão',
	
	// added by Paulino Michelazzo to fun_rename.php file
	'rename_file'	=>	'Renomear um diretório ou arquivo...',
	'newname'		=>	'Novo nome',
	
	// added by Paulino Michelazzo to fun_edit.php file
	'returndir'	=>	'Retornar ao diretório depois de salvar?',
	'line'		=> 	'Linha',
	'column'	=>	'Coluna',
	'wordwrap'	=>	'Quebrar Linhas (Somente no IE)',
	'copyfile'	=>	'Copiar o conteúdo dentro do arquivo',
	
	// Bookmarks
	'quick_jump' => 'Acesso rápido para',
	'already_bookmarked' => 'Este diretório j� foi adicionado',
	'bookmark_was_added' => 'Este diretório foi adicionado a lista de favoritos.',
	'not_a_bookmark' => 'Este diretório não é um favorito.',
	'bookmark_was_removed' => 'Este diretório foi removido da lista de favoritos.',
	'bookmarkfile_not_writable' => "Falha na inclus�o do diretório %s.\n O arquivo de favoritos '%s' \nn�o permite escrita.",
	
	'lbl_add_bookmark' => 'Adicionar este diretório como favorito',
	'lbl_remove_bookmark' => 'Remover este diretório dos favoritos',
	
	'enter_alias_name' => 'Digite um apelido para este favorito',
	
	'normal_compression' => 'compressão normal',
	'good_compression' => 'boa compressão',
	'best_compression' => 'ótima compressão',
	'no_compression' => 'sem compressão',
	
	'creating_archive' => 'Criando arquivo...',
	'processed_x_files' => 'Processados %s de %s Arquivos',
	
	'ftp_login_lbl' => 'Digite os dados de login para o servidor de FTP',
	'ftp_login_name' => 'Usuário do FTP',
	'ftp_login_pass' => 'Senha do FTP',
	'ftp_hostname_port' => 'Nome do Host de FTP e porta <br />(A porta é opcional)',
	'ftp_login_check' => 'Verificando conexão com servidor de FTP...',
	'ftp_connection_failed' => "O servidor de FTP não pode ser contatado. \nVerifique se ele está funcionando em seu servidor.",
	'ftp_login_failed' => "O login do FTP falhou. Verifique o nome de usuário e senha e tente novamente.",
		
	'switch_file_mode' => 'Modo Atual: <strong>%s</strong>. Você pode alterar para o modo %s.',
	'symlink_target' => 'Alvo para o link simbólico',
	
	// added by Paulino Michelazzo to fun_ftpauthentication.php file
	'ftp_header' => 'Autentica��o Local do FTP',
	
	"permchange"		=> "CHMOD Success:",
	"savefile"		=> "The File was saved.",
	"moveitem"		=> "Moving succeeded.",
	"copyitem"		=> "Copying succeeded.",
	'archive_name' 	=> 'Name of the Archive File',
	'archive_saveToDir' 	=> 'Save the Archive in this directory',
	
	'editor_simple'	=> 'Simple Editor Mode',
	'editor_syntaxhighlight'	=> 'Syntax-Highlighted Mode',

	'newlink'	=> 'New File/Directory',
	'show_directories' => 'Show Directories',
	'actlogin_success' => 'Login successful!',
	'actlogin_failure' => 'Login failed, try again.',
	'directory_tree' => 'Directory Tree',
	'browsing_directory' => 'Browsing Directory',
	'filter_grid' => 'Filter',
	'paging_page' => 'Page',
	'paging_of_X' => 'of {0}',
	'paging_firstpage' => 'First Page',
	'paging_lastpage' => 'Last Page',
	'paging_nextpage' => 'Next Page',
	'paging_prevpage' => 'Previous Page',
	
	'paging_info' => 'Displaying Items {0} - {1} of {2}',
	'paging_noitems' => 'No items to display',
	'aboutlink' => 'About...',
	'password_warning_title' => 'Important - Change your Password!',
	'password_warning_text' => 'The user account you are logged in with (admin with password admin) corresponds to the default eXtplorer priviliged account. Your eXtplorer installation is open to intrusion and you should immediately fix this security hole!',
	'change_password_success' => 'Your Password has been changed!',
	'success' => 'Success',
	'failure' => 'Failure',
	'dialog_title' => 'Website Dialog',
	'upload_processing' => 'Processing Upload, please wait...',
	'upload_completed' => 'Upload successful!',
	'acttransfer' => 'Transfer from another Server',
	'transfer_processing' => 'Processing Server-to-Server Transfer, please wait...',
	'transfer_completed' => 'Transfer completed!',
	'max_file_size' => 'Maximum File Size',
	'max_post_size' => 'Maximum Upload Limit',
	'done' => 'Done.',
	'permissions_processing' => 'Applying Permissions, please wait...',
	'archive_created' => 'The Archive File has been created!',
	'save_processing' => 'Saving File...',
	'current_user' => 'This script currently runs with the permissions of the following user:',
	'your_version' => 'Your Version',
	'search_processing' => 'Searching, please wait...',
	'url_to_file' => 'URL of the File',
	'file' => 'File'
);
?>
