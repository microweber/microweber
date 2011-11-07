<?php
/**
 * KFM - Kae's File Manager - index page
 *
 * @category None
 * @package  None
 * @author   Kae Verens <kae@verens.com>
 * @author   Benjamin ter Kuile <bterkuile@gmail.com>
 * @license  docs/license.txt for licensing
 * @link	 http://kfm.verens.com/
 */
// { functions
function js_array($a,$quotes=true){
	if(gettype($a)!='array')return '[]';
	if(count($a)){
		if($quotes) return '["'.join('","',$a).'"]';
		else return '['.join(',',$a).']';
	}
	return '[]';
}
function kfm_getJsFunction($name){
	if(preg_replace('/[0-9a-zA-Z_.]/','',$name)!='')return 'alert("no hacking!");';
	$js=file_get_contents('j/functions/'.$name.'.js');
	if(!$js)return 'alert("could not find function \''.addslashes($name).'\' on the server-side!");';
	return $js;
}
// }
// { setup
error_reporting(E_ALL);
require_once 'initialise.php';
require_once KFM_BASE_PATH.'includes/kaejax.php';
$tmp=str_replace($_SERVER['DOCUMENT_ROOT'],'',getcwd());
$kfm_session->set('kfm_url',dirname((!empty($_SERVER['HTTPS'])) ?
	"https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].DIRECTORY_SEPARATOR.$tmp :
	"http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].DIRECTORY_SEPARATOR.$tmp
).DIRECTORY_SEPARATOR);
// { root folder name
if($kfm->setting('root_folder_name')=='foldername')$kfm->setting('root_folder_name',$user_root_dir->name);
elseif(strpos($kfm->setting('root_folder_name'), 'username') !== false){
	if($kfm->user_id == 1) $kfm->setting('root_folder_name', 'root'); // Special default user case
  else $kfm->setting('root_folder_name',str_replace('username',$kfm->setting('username'),$kfm->setting('root_folder_name')));
}
// }
// { startup folder
$kfm_startupfolder_id = $user_root_dir->id;
$startup_sequence	 = '[]';
if(isset($_GET['startup_folder']))$kfm->setting('startup_folder',$_GET['startup_folder']);
if ($kfm->setting('startup_folder')) {
	$dirs				   = explode(DIRECTORY_SEPARATOR, trim($kfm->setting('startup_folder'), ' '.DIRECTORY_SEPARATOR));
	$subdir				 = $user_root_dir;
	$startup_sequence_array = array();
	foreach ($dirs as $dirname) {
		$subdir = $subdir->getSubdir($dirname, $kfm->setting('force_startup_folder'));
		if(!$subdir)break;
		$startup_sequence_array[] = $subdir->id;
		$kfm_startupfolder_id	 = $subdir->id;
	}
	$kfm_session->set('cwd_id', $kfm_startupfolder_id);
	$startup_sequence = '['.implode(',', $startup_sequence_array).']';
}
$kfm->setting('startupfolder_id',$kfm_startupfolder_id);
// }

// First the user defined file associations
if($kfm->user_id!=1 || $kfm->isAdmin() || !$kfm->setting('allow_user_file_associations')){
	$associations=db_fetch_all('SELECT extension, plugin FROM '.KFM_DB_PREFIX.'plugin_extensions WHERE user_id='.$kfm->user_id);
	$kfm->addAssociations($associations);
}

// Now overwrite users file associations with the default file associations
$associations=db_fetch_all('SELECT extension, plugin FROM '.KFM_DB_PREFIX.'plugin_extensions WHERE user_id=1');
$kfm->addAssociations($associations);

// To javascript object
$ass_str='{';
if(!isset($kfm->associations['all']) && isset($kfm_default_file_selection_handler))$kfm->associations['all']=$kfm_default_file_selection_handler;
foreach($kfm->associations as $ext=>$plugin)$ass_str.='"'.$ext.'":"'.$plugin.'",';
$ass_str=rtrim($ass_str,', ').'}';
// }
// { startup selected files
if (isset($_GET['fid']) && $_GET['fid']) {/*{*/
	$f = kfmFile::getInstance($_GET['fid']);/*}*/
	if($f){
		$_GET['cwd']			   = $f->parent;
		$kfm->setting('startup_selected_files', array($_GET['fid']));
	}
}
// }
//TODO:The next section should be reviewed (benjamin: I thing $_GET['startup_folder']) should be used in stead of this. (no directory id supported)
if (isset($_GET['cwd']) && (int)$_GET['cwd']) {
	$path   = kfm_getDirectoryParentsArr($_GET['cwd']);
	$path[] = $_GET['cwd'];
	if(count($path)>1){
		$startup_sequence_array = $path;
		$kfm_startupfolder_id   = $_GET['cwd'];
		$kfm_session->set('cwd_id', $kfm->setting('startupfolder_id'));
		$startup_sequence = '['.implode(',', $startup_sequence_array).']';
	}
}
// { check for default directories
if(count($kfm->setting('default_directories'))){
	foreach($kfm->setting('default_directories') as $dir){
		$dir=trim($dir);
		@mkdir($user_root_dir->path().$dir,0755, true);
	}
}
// }
// } setup
header('Content-type: text/html; charset=UTF-8');
// { export kaejax stuff
kfm_kaejax_export('kfm_changeCaption', 'kfm_copyFiles', 'kfm_createDirectory',
	'kfm_createEmptyFile', 'kfm_deleteDirectory', 'kfm_downloadFileFromUrl', 
	'kfm_extractZippedFile', 'kfm_getFileDetails', 'kfm_getFileUrl', 'kfm_getFileUrls',
	'kfm_getJsFunction', 'kfm_getTagName', 'kfm_getTextFile', 'kfm_getThumbnail', 'kfm_loadDirectories',
	'kfm_loadFiles', 'kfm_moveDirectory', 'kfm_moveFiles', 'kfm_renameDirectory',
	'kfm_renameFile', 'kfm_renameFiles', 'kfm_resizeImage', 'kfm_resizeImages', 'kfm_rm',
	'kfm_rotateImage', 'kfm_cropToOriginal', 'kfm_cropToNew', 'kfm_saveTextFile',
	'kfm_search', 'kfm_setDirectoryMaxSizeImage', 'kfm_tagAdd', 'kfm_tagRemove',
	'kfm_translate', 'kfm_zip');
if(!empty($_POST['kaejax']))kfm_kaejax_handle_client_request();
// }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
    <link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" />
		<style type="text/css">@import "themes/<?php echo $kfm->setting('theme'); ?>/css.php"; </style>
		<style type="text/css">@import "plugins/css.php"; </style>
		<title>KFM - Kae's File Manager</title>
	</head>
	<body>
		<div id="removeme">
			<p>Please Wait - loading...</p>
			<noscript>KFM relies on JavaScript. Please either turn on JavaScript in your browser, or <a href="http://www.getfirefox.com/">get Firefox</a> if your browser does not support JavaScript.</noscript>
		</div>
<?php
// { if there's a template, show it here
$templated = 0;
if (file_exists('themes/'.$kfm->setting('theme').'/template.html')) {
	$template=file_get_contents('themes/'.$kfm->setting('theme').'/template.html');
	$template = '<div id="templateWrapper" style="display:none">'.kfm_parse_template($template).'</div>';
	$templated = 1;
}
// }
// { daily tasks
$today			 = date('Y-m-d');
$last_registration = isset($kfm_parameters['last_registration'])?$kfm_parameters['last_registration']:'';
if ($last_registration!=$today) {
// { database maintenance
	echo '<iframe style="display:none" src="maintenance.php"></iframe>';
// }
// { once per day, tell the kfm website a few simple details about usage
	if (!$kfm_dont_send_metrics) {
		echo '<img src="http://kfm.verens.com/extras/register.php?version='.urlencode(KFM_VERSION).
			'&amp;domain_name='.urlencode($_SERVER['SERVER_NAME']).
			'&amp;db_type='.$kfm_db_type.
		'" />';
	}
// }
	$kfmdb->query("delete from ".KFM_DB_PREFIX."parameters where name='last_registration'");
	$kfmdb->query("insert into ".KFM_DB_PREFIX."parameters (name,value) values ('last_registration','".$today."')");
	$kfm_parameters['last_registration'] = $today;
}
// }
?>
<?php // { set up JavaScript environment variables ?>
		<script type="text/javascript">
			var kfm_vars={
				files:{ name_length_displayed:<?php echo $kfm->setting('files_name_length_displayed'); ?>, name_length_in_list:<?php echo $kfm->setting('files_name_length_in_list'); ?>, return_id_to_cms:<?php echo $kfm->setting('return_file_id_to_cms')?'true':'false'; ?>, drags_move_or_copy:<?php echo $kfm->setting('folder_drag_action'); ?>, refresh_count:0 },
				get_params:"<?php echo GET_PARAMS; ?>",
				permissions:{
					dir:{ ed:<?php echo $kfm->setting('allow_directory_edit'); ?>, mk:<?php echo $kfm->setting('allow_directory_create'); ?>, mv:<?php echo $kfm->setting('allow_directory_move'); ?>, rm:<?php echo $kfm->setting('allow_directory_delete'); ?> },
					file:{ rm:<?php echo $kfm->setting('allow_file_delete'); ?>, ed:<?php echo $kfm->setting('allow_file_edit'); ?>, mk:<?php echo $kfm->setting('allow_file_create'); ?>, mv:<?php echo $kfm->setting('allow_file_move'); ?> },
					image:{ manip:<?php echo $kfm->setting('allow_image_manipulation'); ?> }
				},
				root_folder_name:"<?php echo $kfm->setting('root_folder_name'); ?>",
				root_folder_id:<?php echo $kfm->setting('root_folder_id'); ?>,
				startupfolder_id:<?php echo $kfm->setting('startupfolder_id'); ?>,
				startup_sequence:<?php echo $startup_sequence; ?>,
				startup_selectedFiles:[<?php echo join(',',$kfm->setting('startup_selected_files')); ?>],
				use_multiple_file_upload:<?php echo $kfm->setting('use_multiple_file_upload'); ?>,
				use_templates:<?php echo $templated; ?>,
				associations:<?php echo $ass_str; ?>,
				lang:'<?php echo $kfm_language; ?>',
				subcontext_categories:<?php echo js_array($kfm->setting('subcontext_categories'),true);?>,
				subcontext_size:<?php echo $kfm->setting('subcontext_size');?>,
				show_admin_link:<?php echo ($kfm->setting('show_admin_link')?'true':'false'); ?>,
				version:'<?php echo KFM_VERSION; ?>'
			};
			var kfm_widgets=[];
			var phpsession_name = "<?php echo session_name(); ?>";
			var phpsession_id = "<?php echo session_id(); ?>";
			var session_key="<?php echo $kfm_session->key; ?>";
			var starttype="<?php echo isset($_GET['type'])?$_GET['type']:''; ?>";
			var fckroot="<?php echo $kfm->setting('userfiles_address'); ?>";
			var fckrootOutput="<?php echo $kfm->setting('userfiles_output'); ?>";
			var kfm_theme="<?php echo $kfm->setting('theme'); ?>";
			var kfm_hidden_panels="<?php echo join(',',$kfm->setting('hidden_panels')); ?>".split(',');
			var kfm_show_files_in_groups_of=<?php echo $kfm->setting('show_files_in_groups_of'); ?>;
			var kfm_slideshow_delay=<?php echo ((int)$kfm->setting('slideshow_delay'))*1000; ?>;
			var kfm_listview=<?php echo $kfm->setting('listview');?>;
			var kfm_startup_sequence_index = 0;
			var kfm_cwd_id=<?php echo $kfm->setting('startupfolder_id'); ?>;
			for(var i = 0;i<kfm_hidden_panels.length;++i)kfm_hidden_panels[i] = 'kfm_'+kfm_hidden_panels[i]+'_panel';
		</script>
<?php // } ?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="third-party/swfupload/swfupload.js"></script>
		<script type="text/javascript" src="j/all.php/can-minify"></script>
		<script type="text/javascript" src="lang/<?php echo $kfm_language; ?>.js"></script>
<?php // { widgets and plugins
// { include widgets if they exist
$h   = opendir(KFM_BASE_PATH.'widgets');
$tmp = '';
while (false!==($dir = readdir($h))) {
	if ($dir[0]!='.'&&is_dir(KFM_BASE_PATH.'widgets/'.$dir)) $tmp .= file_get_contents('widgets/'.$dir.'/widget.js');
}
if($tmp != '')echo "<script type=\"text/javascript\"><!--\n$tmp\n--></script>";
// }
// { show plugins if they exist
$pluginssrc='';
foreach ($kfm->plugins as $plugin) {
	echo $plugin->getJavascriptFiles();
	$pluginssrc.=$plugin->getJavascript();
}
if($pluginssrc!='')echo "<script type=\"text/javascript\"><!--\n$pluginssrc\n--></script>";
// }
if($templated) echo $template;
// } ?>
<?php // { more JavaScript environment variables. These should be merged into the above set whenever possible ?>
		<script type="text/javascript">
      $j(document).ready(function(){
	      kfm.build();
      });
			<?php echo kfm_kaejax_get_javascript(); ?>
			<?php if(isset($_GET['kfm_caller_type']))echo 'window.kfm_caller_type="'.addslashes($_GET['kfm_caller_type']).'";'; ?>
		</script>
<?php // } ?>
	</body>
</html>
