<?php




function get_menus($params = false)
{

    return mw()->menu_manager->get_menus($params);

}

function get_menu($params = false)
{

    return mw()->menu_manager->get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return mw()->menu_manager->menu_create($data_to_save);

}
api_hook('content/menu_create', function ($data) {
    return mw()->menu_manager->menu_create($data);
});

api_hook('content/menu_item_save', function ($data) {
    return mw()->menu_manager->menu_item_save($data);
});

api_hook('content/menu_items_reorder', function ($data) {
    return mw()->menu_manager->menu_items_reorder($data);
});

api_hook('content/menu_item_delete', function ($data) {
    return mw()->menu_manager->menu_delete($data);
});




api_expose('menu_delete');
function menu_delete($id = false)
{
    return mw()->menu_manager->menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    return mw()->menu_manager->menu_item_delete($id);

}

function get_menu_item($id)
{

    return mw()->menu_manager->menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return mw()->menu_manager->menu_item_save($data_to_save);


}

api_expose('reorder_menu_items');
function reorder_menu_items($data)
{
    return mw()->menu_manager->menu_items_reorder($data);
}

function menu_tree($menu_id = false, $maxdepth = false)
{
    return mw()->menu_manager->menu_tree($menu_id, $maxdepth);
}

function is_in_menu($menu_id = false, $content_id = false)
{
    return mw()->menu_manager->is_in_menu($menu_id, $content_id);

}

api_hook('save_content_admin', 'add_content_to_menu');
function add_content_to_menu($content_id, $menu_id = false)
{

    return mw()->content_manager->add_content_to_menu($content_id, $menu_id);

}


