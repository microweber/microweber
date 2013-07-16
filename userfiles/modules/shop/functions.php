<?php


action_hook('mw_admin_settings_menu', 'mw_print_admin_shop_settings_link');

function mw_print_admin_shop_settings_link() {
	$active = url_param('view');
	$cls = '';
	$mname = module_name_encode('shop/payments/admin');
	if ($active == $mname ) {
		$cls = ' class="active" ';
	}
	$notif_html = '';
	
	print "<li><a class=\"item-".$mname."\" href=\"#option_group=".$mname."\">Payments</a></li>";

	//$notif_count = \mw\Notifications::get('module=comments&is_read=n&count=1');
	/*if ($notif_count > 0) {
		$notif_html = '<sup class="mw-notif-bubble">' . $notif_count . '</sup>';
	}*/
	//print '<li' . $cls . '><a href="' . admin_url() . 'view:comments"><span class="ico icomment">' . $notif_html . '</span><span>Comments</span></a></li>';
}



