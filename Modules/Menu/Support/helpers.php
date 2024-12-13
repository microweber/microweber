<?php

if (!function_exists('get_menus')) {
    function get_menus($params = false)
    {
        return mw()->menu_manager->get_menus($params);
    }
}

if (!function_exists('menu_tree')) {
    function menu_tree($menu_id = false, $maxdepth = false, $show_images = false)
    {
        return mw()->menu_manager->menu_tree($menu_id, $maxdepth, $show_images);
    }
}

if (!function_exists('is_in_menu')) {
    function is_in_menu($menu_id = false, $content_id = false)
    {
        return mw()->menu_manager->is_in_menu($menu_id, $content_id);
    }
}

if (!function_exists('add_content_to_menu')) {
    function add_content_to_menu($content_id, $menu_id = false)
    {
        return app()->content_manager->helpers->add_content_to_menu($content_id, $menu_id);
    }
}

if (!function_exists('add_new_menu')) {
    function add_new_menu($data_to_save)
    {
        return mw()->menu_manager->menu_create($data_to_save);
    }
}

if (!function_exists('get_menu')) {
    function get_menu($params = false)
    {
        return mw()->menu_manager->get_menu($params);
    }
}

if (!function_exists('menu_delete')) {
    function menu_delete($id = false)
    {
        return mw()->menu_manager->menu_delete($id);
    }
}

if (!function_exists('delete_menu_item')) {
    function delete_menu_item($id)
    {
        return mw()->menu_manager->menu_item_delete($id);
    }
}

if (!function_exists('get_menu_item')) {
    function get_menu_item($id)
    {
        return mw()->menu_manager->menu_item_get($id);
    }
}

if (!function_exists('edit_menu_item')) {
    function edit_menu_item($data_to_save)
    {
        return mw()->menu_manager->menu_item_save($data_to_save);
    }
}

if (!function_exists('reorder_menu_items')) {
    function reorder_menu_items($data)
    {
        return mw()->menu_manager->menu_items_reorder($data);
    }
}
