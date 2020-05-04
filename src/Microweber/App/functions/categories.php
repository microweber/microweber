<?php
api_expose_admin('get_category_by_id');
api_expose_admin('get_categories');
api_expose_admin('save_category');
api_bind_admin('category/save', 'save_category');
api_bind_admin('category/delete', 'delete_category');
api_expose_admin('delete_category');
api_expose_admin('reorder_categories');
api_expose_admin('content_categories');
api_expose_admin('get_category_children');
api_expose_admin('category_link');
api_expose_admin('get_page_for_category');
api_expose_admin('category_tree');
api_expose_admin('category/save');
api_expose_admin('category/delete');
api_expose_admin('get_category_items');

api_bind_admin('category/reorder', function ($data) {
    return mw()->category_manager->reorder($data);
});
