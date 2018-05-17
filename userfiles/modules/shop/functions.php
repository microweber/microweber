<?php

event_bind('mw.admin', function ($params = false) {
    return mw_add_admin_menu_buttons($params);
});

event_bind('mw.front', function ($params = false) {
    return mw_add_admin_menu_buttons($params);
});

function mw_add_admin_menu_buttons($params = false)
{
    if (get_option('shop_disabled', 'website') != 'y') {
        $btn = array();
        $btn['content_type'] = 'product';
        $btn['title'] = _e("Product", true);
        $btn['class'] = 'mai-product';
        mw()->modules->ui('content.create.menu', $btn);
    }
    $btn = array();
    $btn['icon'] = '<span class="mai-market2"></span>';
    $btn['module'] = 'shop/settings';
    $btn['title'] =  _e("Shop", true);

//    mw()->modules->ui('admin.settings.menu', $btn);


}

event_bind('mw.admin.dashboard.links', 'mw_print_admin_dashboard_orders_btn');
function mw_print_admin_dashboard_orders_btn()
{
    if (get_option('shop_disabled', 'website') == 'y') {
        return;
    }
    $admin_dashboard_btn = array();
    $admin_dashboard_btn['view'] = 'shop/action:orders';
    $admin_dashboard_btn['icon_class'] = 'mai-shop';
    $notif_html = '';
    $notif_count = mw()->order_manager->get_count_of_new_orders();
    if ($notif_count > 0) {
        $notif_html = '<sup class="mw-notification-count">' . $notif_count . '</sup>';
    }
    $admin_dashboard_btn['text'] = _e("View Orders", true) . $notif_html;
    mw()->ui->module('admin.dashboard.menu', $admin_dashboard_btn);
}

event_bind('mw_edit_product_admin', function ($data) {
    if (isset($data['id'])) {
        if (get_option('shop_disabled', 'website') == 'y') {
            return;
        }
        print '<module type="shop/products/product_options" content-id="' . $data['id'] . '" />';
    }
});


event_bind('module.content.edit.main', function ($data) {

    if (isset($data['id']) and isset($data['content_type']) and $data['content_type'] == 'product') {
        $data['prices'] = mw()->fields_manager->get("field_type=price&for=content&for_id=" . $data['id']);

        if ($data['prices'] == false) {
            $create_price_field = mw()->fields_manager->save("field_value=0&field_type=price&for=content&for_id=" . $data['id']);
            $data['prices'] = mw()->fields_manager->get("field_type=price&for=content&for_id=" . $data['id']);
        }


        $btn = array();
        $btn['title'] = _e("Price", true);
        $btn['html'] = ' <module type="custom_fields" template="shop/products/edit_price" content_id="' . $data['id'] . '" />';
        $btn['class'] = 'titlepricecolumn';
        mw()->modules->ui('content.edit.title.after', $btn);
    }
});


event_bind('mw.user.login', function ($data) {
    if (is_array($data) and isset($data['old_sid'])) {
        $cur_sid = mw()->user_manager->session_id();

        Cart::where('session_id', $data['old_sid'])->update(array('session_id' => $cur_sid));
        mw()->cache_manager->delete('cart');
    }
});


event_bind('recover_shopping_cart', function ($params = false) {
    return mw()->cart_manager->recover_cart($params);
});

 