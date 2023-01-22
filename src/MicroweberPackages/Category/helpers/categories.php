<?php
api_expose_admin('get_category_by_id');
api_expose_admin('get_categories');


api_expose_admin('content_categories');
api_expose_admin('get_category_children');
api_expose_admin('category_link');
api_expose_admin('get_page_for_category');
api_expose_admin('category_tree');

api_expose_admin('get_category_items');

//api_expose_admin('category/reorder', function ($data) {
//
//    return mw()->category_manager->reorder($data);
//});


/**
 * @desc        Get a single row from the categories_table by given ID and returns it as one dimensional array
 *
 * @param int
 *
 * @return array
 *
 * @author      Peter Ivanov
 *
 * @version     1.0
 *
 * @since       Version 1.0
 */
function get_category_by_id($id = 0)
{
    return app()->category_manager->get_by_id($id);
}

function get_categories($data)
{
    return app()->category_manager->get($data);
}

function save_category($data)
{
    return app()->category_manager->save($data);
}

function delete_category($data)
{
    return app()->category_manager->delete($data);
}

function reorder_categories($data)
{
    return app()->category_manager->reorder($data);
}

function content_categories($content_id = false, $data_type = 'categories')
{
    return get_categories_for_content($content_id, $data_type);
}

function content_tags($content_id = false, $return_full = false)
{
    return app()->content_manager->tags($content_id, $return_full);
}


function media_tags($media_id = false, $return_full = false)
{
    return app()->media_manager->tags($media_id, $return_full);
}

function picture_tags($media_id = false)
{
    return media_tags($media_id);
}

function get_categories_for_content($content_id = false, $data_type = 'categories')
{
    if (intval($content_id) == 0) {
        if (!defined('CONTENT_ID')) {
            return false;
        } else {
            $content_id = CONTENT_ID;
        }
    }

    return app()->category_manager->get_for_content($content_id, $data_type);
}

function category_link($id)
{
    if (intval($id) == 0) {
        return false;
    }

    return app()->category_manager->link($id);
}

function category_title($id = false)
{
    if (!$id) {
        $id = CATEGORY_ID;
    }
    if (intval($id) == 0) {
        return false;
    }
    $cat = get_category_by_id($id);
    if (isset($cat['title'])) {
        return $cat['title'];
    }
}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false)
{
    return app()->category_manager->get_children($parent_id, $type, $visible_on_frontend);
}

function get_page_for_category($category_id)
{
    return app()->category_manager->get_page($category_id);
}

function get_category_id_from_url($url = false)
{
    return app()->category_manager->get_category_id_from_url($url);
}

/**
 * category_tree.
 *
 * @desc        prints category_tree of UL and LI
 *
 * @category    categories
 *
 * @author      Microweber
 *
 * @param  $params = array();
 * @param  $params ['parent'] = false; //parent id
 * @param  $params ['link'] = false; // the link on for the <a href
 * @param  $params ['active_ids'] = array(); //ids of active categories
 * @param  $params ['active_code'] = false; //inserts this code for the active ids's
 * @param  $params ['remove_ids'] = array(); //remove those caregory ids
 * @param  $params ['ul_class_name'] = false; //class name for the ul
 * @param  $params ['include_first'] = false; //if true it will include the main parent category
 * @param  $params ['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params ['add_ids'] = array(); //if you send array of ids it will add them to the category
 * @param  $params ['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_at','asc');
 * @param  $params ['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params ['list_tag'] = 'select';
 * @param  $params ['list_item_tag'] = "option";
 */
function category_tree($params = false)
{
    return app()->category_manager->tree($params);
}

function get_category_items($category_id)
{
    return app()->category_manager->get_items('parent_id=' . intval($category_id));
}

function get_category_items_count($category_id, $rel_type = false)
{
    return app()->category_manager->get_items_count($category_id, $rel_type);
}
