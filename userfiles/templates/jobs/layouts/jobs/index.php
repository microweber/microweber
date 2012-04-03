<?php

/*

type: layout

name: layout

description: site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $view = url_param('view'); 

if( $post){
  $view = 'inner';	
}

?>
<? if(is_file(TEMPLATE_DIR. "layouts/jobs/".$view.'.php')) : ?>
<? include TEMPLATE_DIR. "layouts/jobs/".$view.'.php'; ?>
<? else : ?>
<? include TEMPLATE_DIR. "layouts/jobs/list.php"; ?>
<? endif; ?>
<? include TEMPLATE_DIR. "footer.php"; ?>
