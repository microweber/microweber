<?php





event_bind('mw.admin', 'mw_add_admin_menu_buttons');

function mw_add_admin_menu_buttons($params = false)
{

    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }
    mw()->ui->add_create_content_menu(array('product' => "Product"));

}





event_bind('mw_admin_settings_menu', 'mw_print_admin_shop_settings_link');

function mw_print_admin_shop_settings_link()
{
    $active = url_param('view');
    $cls = '';
    $mname = module_name_encode('shop/payments/admin');
    if ($active == $mname) {
        $cls = ' class="active" ';
    }
    $notif_html = '';

    print "<li><a class=\"item-" . $mname . "\" href=\"#option_group=" . $mname . "\">" . _e("Shop", true) . "</a></li>";
}

event_bind('mw_edit_product_admin', 'mw_print_admin_shop_product_settings');
function mw_print_admin_shop_product_settings($data = false)
{
    $content_id = 0;
    if ($data != false and isset($data['id'])) {
        $content_id = $data['id'];
    }
    print '<module data-type="shop/products/product_options" id="mw_admin_product_settings" data-content-id="' . $content_id . '" />';
}


event_bind('admin_header_menu_start', 'mw_print_admin_menu_shop_btn');

function mw_print_admin_menu_shop_btn()
{
    $active = mw('url')->param('view');
    $cls = '';
    if ($active == 'shop') {
        $cls = ' class="active" ';
    }
    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }

    $is_shop = get_content('is_active=y&is_shop=y&count=1');

    if ($is_shop > 0) {
        print '<li' . $cls . '><a href="' . admin_url() . 'view:shop" title="' . _e("Online Shop", true) . '"><i class="mw-icon-shop"></i><span>' . _e('Online Shop', true) . '</span></a></li>';
    }

}


event_bind('admin_shop_side_menu_start', 'mw_print_admin_menu_shop_sidebar_btn');
event_bind('admin_content_side_menu', 'mw_print_admin_menu_shop_sidebar_btn');

function mw_print_admin_menu_shop_sidebar_btn($params = false)
{

    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }
    print '<a href="#action=new:product" class="mw_action_nav mw_action_product" onclick="mw.url.windowHashParam(\'action\',\'new:product\');"><label>' . _e("Product", true) . '</label>
          <span class="mw-ui-btn"><span class="mw-icon-plus"></span><span class="mw-icon-iproduct"></span></span>
        </a>';


}

event_bind('admin_content_right_sidebar_menu_list_end', 'mw_print_admin_menu_shop_right_sidebar_btn');

function mw_print_admin_menu_shop_right_sidebar_btn($params = false)
{

    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }

    $par = '';
    if (isset($params['page-id'])) {
        $par = '&parent_page=' . intval($params['page-id']);

    }


    print '<li> <a href="#action=new:product' . $par . '"> <span class="mw-icon-plus">&nbsp;</span> <span class="mw-icon-iproduct"></span> <span>' . _e("New Product", true) . '</span> </a> </li>';


}

event_bind('live_edit_quick_add_menu_end', 'mw_print_admin_menu_quck_add_live_edit_btn');

function mw_print_admin_menu_quck_add_live_edit_btn($params = false)
{

    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }


    print '<li><a href="javascript:;" onclick="mw.quick.product();"><span class="mw-icon-plus"></span><span class="mw-icon-iproduct"></span>' . _e("Product", true) . '</a></li>';


}
 