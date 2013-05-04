<?php

 

action_hook('mw_admin_shop_link', 'mw_admin_accounting_link');

function mw_admin_accounting_link() {
	$active_action = url_param('view');
	$cls = '';
	if ($active_action == 'shop') {
		//   $cls = ' class="active" ';
	}
	
	$mod = module_info('accounting');
	//d($mod);
	$tn = '';
	if(isset($mod['icon'])){
	//$tn = 	thumbnail($mod['icon'], 16,16);
	//$tn = 	thumbnail($mod['icon'], 24,24);
	$tn = 	thumbnail($mod['icon'], 23,20);
	}
	$url = admin_url().'view:modules/load_module:'.module_name_encode($mod['module']);
	$to_pr = "<li><a href='{$url}' target='_blank'><span class='ico' style='background-image:url($tn)'></span><span>Accounting</span></a></li>";
	print $to_pr;
}
 