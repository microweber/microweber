<?php




/**
 * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array
 * @param int
 * @return array
 * @author      Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
api_expose_admin('get_category_by_id');
function get_category_by_id($id = 0)
{
    return mw()->category_manager->get_by_id($id);
}

api_expose_admin('get_categories');
function get_categories($data)
{
    return mw()->category_manager->get($data);
}

api_expose_admin('save_category');
api_bind('category/save','save_category');
function save_category($data)
{

    $s =  mw()->category_manager->save($data);
     return $s;
}
api_bind_admin('category/delete','delete_category'); 

api_expose_admin('delete_category');
function delete_category($data)
{
    return mw()->category_manager->delete($data);
}


api_expose_admin('reorder_categories');
function reorder_categories($data)
{
    return mw()->category_manager->reorder($data);
}

api_expose_admin('content_categories');
function content_categories($content_id = false, $data_type = 'categories')
{
    return get_categories_for_content($content_id, $data_type);
}

function get_categories_for_content($content_id = false, $data_type = 'categories')
{
    if (intval($content_id) == 0) {
        if (!defined("CONTENT_ID")) {
            return false;
        } else {
            $content_id = CONTENT_ID;
        }
    }
    return mw()->category_manager->get_for_content($content_id, $data_type);
}

api_expose_admin('category_link');
function category_link($id)
{
    if (intval($id) == 0) {
        return false;
    }

    return mw()->category_manager->link($id);

}

api_expose_admin('get_category_children');
function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false)
{
    return mw()->category_manager->get_children($parent_id, $type, $visible_on_frontend);
}

api_expose_admin('get_page_for_category');
function get_page_for_category($category_id)
{
    return mw()->category_manager->get_page($category_id);
}


/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @param $params = array();
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
 *
 *
 */
api_expose_admin('category_tree');
function category_tree($params = false)
{
    return mw()->category_manager->tree($params);
}

api_expose_admin('get_category_items');
function get_category_items($category_id)
{
    return mw()->category_manager->get_items('parent_id=' . intval($category_id));
}
function get_category_items_count($category_id,$rel_type=false)
{
    return mw()->category_manager->get_items_count($category_id,$rel_type);
}




api_expose_admin('category/reorder');
api_expose_admin('category/save');
api_expose_admin('category/delete');
