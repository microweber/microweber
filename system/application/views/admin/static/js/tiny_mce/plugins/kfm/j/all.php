<?php
require '../initialise.php';
require 'libs.php';

header('Content-type: text/javascript');
header('Expires: '.gmdate("D, d M Y H:i:s", time() + 3600*24*365).' GMT');

$name=md5_of_dir(KFM_BASE_PATH.'j/');
if(file_exists(WORKPATH.$name))readfile(WORKPATH.$name);
else{ // build cacheable js file
	$js='';

	// { mootools
	$js.=file_get_contents('mootools.v1.11/mootools.v1.11.js');
	// }
	// { jquery scripts
	$js.=file_get_contents('jquery/jquery.iutil.pack.js');
	$js.=file_get_contents('jquery/jquery.idrag.js');
	$js.=file_get_contents('jquery/jquery.grid.columnSizing.js');
	$js.=file_get_contents('jquery/jquery.tablesorter.js');
	$js.=file_get_contents('jquery/setup.js');
	// }
	// { main files
	$js.=file_get_contents('variables.js');
	$js.=file_get_contents('common.js');
	$js.=file_get_contents('notice.js');
	$js.=file_get_contents('kfm.js');
	$js.=file_get_contents('alerts.js');
	$js.=file_get_contents('contextmenu.js');
	$js.=file_get_contents('directories.js');
	$js.=file_get_contents('file.selections.js');
	$js.=file_get_contents('file.text-editing.js');
	$js.=file_get_contents('hooks.js');
	$js.=file_get_contents('modal.dialog.js');
	$js.=file_get_contents('panels.js');
	$js.=file_get_contents('kaejax_replaces.js');
	$js.=file_get_contents('kdnd.js');
	$js.=file_get_contents('file.class.js');
	$js.=file_get_contents('files.js');
	$js.=file_get_contents('resize_handler.js');
	$js.=file_get_contents('search.js');
	$js.=file_get_contents('lazyload_functions.js');
	// }
	if(isset($_REQUEST['minify'])){
		require '../includes/jsmin-1.1.1.php';
		$js=JSMin::minify($js);
		file_put_contents(WORKPATH.$name,$js);
		delete_old_md5s(WORKPATH);
		exit;
	}
	else if(strpos($_SERVER['REQUEST_URI'],'/can-minify')!==false){
		$js.="setTimeout(function(){var a=document.createElement('img');a.src='j/all.php?minify=1';a.style.display='none';document.body.appendChild(a);},5000);";
	}
	echo $js;
}
