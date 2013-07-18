<?php



action_hook('mw_edit_page_admin_menus', 'mw_print_admin_menu_selector');

function mw_print_admin_menu_selector($params = false)
{
    //d($params);
    $add = '';
    if (isset($params['id'])) {
        $add = '&content_id=' . $params['id'];
    }
    print module('view=edit_page_menus&type=menu' . $add);
}

function get_menu_items($params = false)
{
    return \Content::get_menu_items($params);
}



function get_menu($params = false)
{

    return \Content::get_menu($params);

}

api_expose('add_new_menu');
function add_new_menu($data_to_save)
{
    return \ContentUtils::menu_create($data_to_save);

}

api_expose('menu_delete');
function menu_delete($id = false)
{
    return \ContentUtils::menu_delete($id);

}

api_expose('delete_menu_item');
function delete_menu_item($id)
{

    return \ContentUtils::menu_item_delete($id);

}

function get_menu_item($id)
{

    return \ContentUtils::menu_item_get($id);

}

api_expose('edit_menu_item');
function edit_menu_item($data_to_save)
{
    return \ContentUtils::menu_item_save($data_to_save);



}

api_expose('reorder_menu_items');

function reorder_menu_items($data)
{

    return \ContentUtils::menu_items_reorder($data);

}

function menu_tree($menu_id, $maxdepth = false)
{
    return \Content::menu_tree($menu_id, $maxdepth );


}

function is_in_menu($menu_id = false, $content_id = false)
{
    return \ContentUtils::is_in_menu($menu_id,$content_id);

}

api_hook('save_content', 'add_content_to_menu');

function add_content_to_menu($content_id, $menu_id=false)
{
    return \ContentUtils::add_content_to_menu($content_id,$menu_id);


}
