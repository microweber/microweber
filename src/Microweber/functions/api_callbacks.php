<?php


// content

api_expose('get_content_admin');
api_expose_admin('get_content');
api_expose_admin('get_posts');
api_expose_admin('content_title');
api_expose_admin('get_pages');
api_expose('content_link');
api_expose_admin('get_content_by_id');
api_expose_admin('get_products');
api_expose('delete_content');
api_bind_admin('content/delete', 'delete_content');
api_expose_admin('content_parents');
api_expose_admin('get_content_children');
api_expose_admin('page_link');
api_expose_admin('post_link');
api_expose_admin('pages_tree');
api_expose('save_edit');
api_expose_admin('save_content');
api_expose('save_content_admin');
api_expose_admin('get_content_field_draft');

api_bind_admin('content/set_published', function ($data) {
    return mw()->content_manager->set_published($data);
});


api_bind_admin('content/set_unpublished', function ($data) {
    return mw()->content_manager->set_unpublished($data);
});
api_bind_admin('content/reorder', function ($data) {
    return mw()->content_manager->reorder($data);
});

api_bind_admin('content/reset_edit', function ($data) {
    return mw()->content_manager->reset_edit($data);
});
api_bind_admin('content/bulk_assign', function ($data) {
    return mw()->content_manager->bulk_assign($data);
});
api_bind_admin('content/copy', function ($data) {
    return mw()->content_manager->copy($data);
});


api_bind_admin('current_template_save_custom_css', function ($data) {
    return mw()->layouts_manager->template_save_css($data);
});


// SHOP
api_expose_admin('delete_client');
api_expose_admin('update_order');
api_expose('empty_cart');
api_expose('update_cart');
api_expose_admin('delete_order');
api_expose('checkout_ipn');
api_expose('checkout');
api_expose('remove_cart_item');

api_expose('update_cart_item_qty');

api_bind_admin('shop/update_order', function ($data) {
    return mw()->shop_manager->update_order($data);
});


api_expose_admin('checkout_confirm_email_test');
