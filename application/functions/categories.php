<?php


/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @param $params = array();
 * @param  $params['parent'] = false; //parent id
 * @param  $params['link'] = false; // the link on for the <a href
 * @param  $params['active_ids'] = array(); //ids of active categories
 * @param  $params['active_code'] = false; //inserts this code for the active ids's
 * @param  $params['remove_ids'] = array(); //remove those caregory ids
 * @param  $params['ul_class_name'] = false; //class name for the ul
 * @param  $params['include_first'] = false; //if true it will include the main parent category
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['add_ids'] = array(); //if you send array of ids it will add them to the category
 * @param  $params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['list_tag'] = 'select';
 * @param  $params['list_item_tag'] = "option";
 *
 *
 */
function category_tree($params = false) {

    return \Category::tree($params);
}

/**
 * `
 *
 * Prints the selected categories as an <UL> tree, you might pass several
 * options for more flexibility
 *
 * @param
 *        	array
 *
 * @param
 *        	boolean
 *
 * @author Peter Ivanov
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
function content_helpers_getCaregoriesUlTree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false, $active_code_tag = false) {
    return \Category::html_tree($parent, $link , $active_ids , $active_code, $remove_ids, $removed_ids_code, $ul_class_name , $include_first , $content_type , $li_class_name , $add_ids , $orderby , $only_with_content , $visible_on_frontend , $depth_level_counter , $max_level, $list_tag , $list_item_tag , $active_code_tag );

}



api_expose('save_category');

function save_category($data, $preserve_cache = false) {
    return \CategoryUtils::save(   $data, $preserve_cache);


}

api_expose('delete_category');

function delete_category($data) {

    return \CategoryUtils::delete($data);

}



function get_categories($params, $data_type = 'categories') {
    return \Category::get($params,$data_type);


}

function get_category_items($params, $data_type = 'categories') {

    return \Category::get_items($params,$data_type);
}

api_expose('reorder_categories');

function reorder_categories($data) {

    return \CategoryUtils::reorder($data);

}

function get_categories_for_content($content_id, $data_type = 'categories') {
	if (intval($content_id) == 0) {

		return false;
	}

    return \Category::get_for_content($content_id, $data_type);
}

function category_link($id) {

	if (intval($id) == 0) {

		return false;
	}

    return \Category::link($id);

}

/**

 * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array

 * @param int

 * @return array

 * @author      Peter Ivanov

 * @version 1.0

 * @since Version 1.0

 */
function get_category_by_id($id = 0) {
    return \Category::get_by_id($id);

}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false) {

    return \Category::get_children($parent_id, $type , $visible_on_frontend);
}

function get_page_for_category($category_id) {
    return \Category::get_page($category_id);


}

function get_category_parents($id = 0, $without_main_parrent = false, $data_type = 'category') {

    return \Category::get_parents($id = 0, $without_main_parrent , $data_type);
}
