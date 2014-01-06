<?php


event_bind('mw_admin_settings_menu', 'mw_print_admin_shop_settings_link');

function mw_print_admin_shop_settings_link() {
	$active = url_param('view');
	$cls = '';
	$mname = module_name_encode('shop/payments/admin');
	if ($active == $mname ) {
		$cls = ' class="active" ';
	}
	$notif_html = '';
	
	print "<li><a class=\"item-".$mname."\" href=\"#option_group=".$mname."\">Shop</a></li>";
}

event_bind('mw_edit_product_admin', 'mw_print_admin_shop_product_settings');
function mw_print_admin_shop_product_settings($data = false) {
	$content_id = 0;
	if($data != false and isset($data['id'])){
		$content_id =$data['id'];
	}  
	 print '<module data-type="shop/products/product_options" id="mw_admin_product_settings" data-content-id="'.$content_id.'" />';
}



event_bind('mw_admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
	
	$is_shop = get_content('is_active=y&is_shop=y&count=1');
	
	if($is_shop > 0){
	print '<li' . $cls . '><a href="' . admin_url() . 'view:shop" title="'._e("Online Shop", true).'"><i class="ico inavshop"></i><span>' . _e('Online Shop', true) . '</span></a></li>';
	} 
   
}