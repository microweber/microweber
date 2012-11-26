<?php include (ADMIN_VIEWS_PATH . 'header.php'); ?>
<? if(is_admin() == false): ?>
<module type="users/login" />
<? else: ?>
<?php include (ADMIN_VIEWS_PATH . 'header_nav.php'); ?>
<?php 

$v = url_param('view');

$vf = ADMIN_VIEWS_PATH . $v. '.php';
$vf = str_replace('..', '', $vf);
if(is_file($vf)){
//d($vf);	

include ($vf);

}
 
 else { ?>
<?php

$v_mod = module_name_decode($v);
 
 if(is_module($v_mod)){
	 $mod = load_module($v_mod, $attrs=array('view' => 'admin','backend' => 'true'));
	 print $mod ;
 } else {
	 
	 include (ADMIN_VIEWS_PATH . 'index.php');
 }

  ?>
<?
 }

 endif; ?>
<?php	include (ADMIN_VIEWS_PATH . 'footer.php'); ?>