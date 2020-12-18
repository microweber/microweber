<?php

function get_menus($params = false)
{
    return mw()->menu_manager->get_menus($params);
}
//
//function get_menu($params = false)
//{
//    return mw()->menu_manager->get_menu($params);
//}
//
//function menu_delete($id = false)
//{
//    return mw()->menu_manager->menu_delete($id);
//}


//function delete_menu_item($id)
//{
//    return mw()->menu_manager->menu_item_delete($id);
//}

//function get_menu_item($id)
//{
//    return mw()->menu_manager->menu_item_get($id);
//}

//
//function edit_menu_item($data_to_save)
//{
//    return mw()->menu_manager->menu_item_save($data_to_save);
//}


//function reorder_menu_items($data)
//{
//    return mw()->menu_manager->menu_items_reorder($data);
//}

function menu_tree($menu_id = false, $maxdepth = false, $show_images = false)
{
    return mw()->menu_manager->menu_tree($menu_id, $maxdepth, $show_images);
}

function is_in_menu($menu_id = false, $content_id = false)
{
    return mw()->menu_manager->is_in_menu($menu_id, $content_id);
}

function add_content_to_menu($content_id, $menu_id = false)
{
    return mw()->content_manager->helpers->add_content_to_menu($content_id, $menu_id);
}


function add_new_menu($data_to_save)
{
    return mw()->menu_manager->menu_create($data_to_save);
}

