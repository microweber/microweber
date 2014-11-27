<?php



function is_page()
{
    if (page_id()) {
        return true;
    }
}

function is_post()
{
    if (post_id()) {
        return true;
    }
}

function is_category()
{
    if (category_id()) {
        return true;
    }
}

function page_id()
{
    if (defined('PAGE_ID')) {
        return PAGE_ID;
    }
}

function post_id()
{
    if (defined('POST_ID')) {
        return POST_ID;
    }
}

function content_id()
{
    if (post_id()) {
        return post_id();
    } elseif (page_id()) {
        return page_id();
    }
}


function category_id()
{
    if (defined('CATEGORY_ID')) {
        return CATEGORY_ID;
    }
}





/**
 * Gets content or posts by matching criteria.
 *
 * Function parameters:
 *
 *     $params['limit'] - Limit the results, default is 30
 *     $params['current_page'] - Default is 0.You can make paging of the results
 *     $params['category'] - Id of category to get posts from
 *
 *     $params['content_type'] - The type of the content you want to get. "page" and "post" are supported by default
 *     $params['subtype'] - The sub-type of the content. defaults are "post", "product", "static" , "dynamic"
 *
 *     $params['include_ids'] - Comma separated values, such as 1,3,5
 *     $params['exclude_ids'] - Comma separated values , such as 2,4,6
 *
 *     $params['position'] - The post position in the database
 *
 *     $params['is_active'] - 'y' or 'n' Indicates if the post is active/published
 *     $params['is_deleted'] - 'y' or 'n' Indicates if the post is deleted
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/get_content
 *
 * @param array|string|bool $params Filter content by parameters
 * @params int $params['limit'] - Limit the results, default is 30
 * @return array The database results
 */


api_expose_admin('get_content');
function get_content($params = false)
{

    return mw()->content_manager->get($params);
}


api_expose('get_content_admin');
/**
 * Same as get_content(), just it checks if the user is admin
 * @see get_content
 *
 * @param array|bool $params Optional parameters to filter the content
 * @return array The database results
 */
function get_content_admin($params)
{
    if (is_admin() == false) {
        return false;
    }

    return get_content($params);
}

/**
 * Return array of posts specified by $params
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @link http://microweber.com/docs/functions/get_posts
 *
 * @uses get_content()
 * @example
 * <code>
 * //get array of posts
 * $content = get_posts('parent=5');
 *
 * if (!empty($content)) {
 *      foreach($content as $item){
 *       print $item['id'];
 *       print $item['title'];
 *       print mw()->content_manager->link($item['id']);
 *      }
 * }
 * </code>
 *
 */
api_expose_admin('get_posts');
function get_posts($params = false)
{
    return mw()->content_manager->get_posts($params);
}

/**
 * Return array of pages specified by $params
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @link http://microweber.com/docs/functions/get_pages
 *
 */
api_expose_admin('get_pages');
function get_pages($params = false)
{
    return mw()->content_manager->get_pages($params);
}
api_expose_admin('get_products');
function get_products($params = false)
{
    return mw()->content_manager->get_products($params);
}


/**
 * Gets a single content item by id
 *
 * Function parameters:
 *
 *     'id' - the id of the content
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/get_content_by_id
 *
 * @param int|bool $params Optional
 * @return array The database results
 */
api_expose_admin('get_content_by_id');
function get_content_by_id($params = false)
{
    return mw()->content_manager->get_by_id($params);
}




/**
 * Returns the link of a given content id
 *
 * Function parameters:
 *
 *     'id' - the id of the content
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/get_content_by_id
 *
 * @param bool|int $id the id of the content
 * @return string The content URL
 */
api_expose('content_link');
function content_link($id = false)
{

    return mw()->content_manager->link($id);
}
api_expose_admin('content_title');
function content_title($id = false)
{

    return mw()->content_manager->title($id);
}



/**
 * Send content to trash or delete it forever
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/delete_content
 *
 * @param $data The content to delete
 * @return array|string
 */
api_expose('delete_content');
function delete_content($data)
{
    return mw()->content_manager->delete($data);
}


/**
 * Returns html code with paging links
 *
 * @param $params['num'] = 5; //the numer of pages
 * @internal param $display =
 *            'default' //sets the default paging display with <ul> and </li>
 *            tags. If $display = false, the function will return the paging
 *            array which is the same as $posts_pages_links in every template
 *
 * @return string - html string with ul/li
 */
function paging($params)
{
    return mw()->content_manager->paging($params);

}

/**
 * Get the content parent pages recursively
 *
 * @since 0.1
 * @link http://microweber.com/docs/functions/content_parents
 *
 * @param int $id
 * @param bool $without_main_parent If true, it will exclude the $id from the results
 * @return array The parent content items
 */
api_expose_admin('content_parents');
function content_parents($id = 0, $without_main_parent = false)
{
    return mw()->content_manager->get_parents($id, $without_main_parent);
}


api_expose_admin('get_content_children');
function get_content_children($id = 0, $without_main_parent = false)
{
    return mw()->content_manager->get_children($id, $without_main_parent);
}

api_expose_admin('page_link');
function page_link($id = false)
{
    return mw()->content_manager->link($id);
}
api_expose_admin('post_link');
function post_link($id = false)
{
    return mw()->content_manager->link($id);
}
api_expose_admin('pages_tree');
function pages_tree($params = false)
{

    return mw()->content_manager->pages_tree($params);
}


api_expose('save_edit');
function save_edit($post_data)
{
    return mw()->content_manager->save_edit($post_data);
}


/**
 * Function to save content into the content_table
 *
 * @param array|string $data data to save
 *
 * @param bool $delete_the_cache
 * @return string | the id saved
 * @version 1.0
 * @since Version 0.1
 */
api_expose_admin('save_content');
function save_content($data, $delete_the_cache = true)
{
    return mw()->content_manager->save_content($data, $delete_the_cache);
}

api_expose('save_content_admin');

function save_content_admin($data, $delete_the_cache = true)
{
    return mw()->content_manager->save_content_admin($data, $delete_the_cache);
}


function save_content_field($data, $delete_the_cache = true)
{

    return mw()->content_manager->save_content_field($data, $delete_the_cache);

}

api_expose('get_content_field_draft');
function get_content_field_draft($data)
{
    return mw()->content_manager->edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{
    return mw()->content_manager->edit_field($data, $debug);
}


function content_data($content_id, $field_name = false)
{

    return mw()->content_manager->data($content_id, $field_name);
}


function next_content($content_id = false)
{
    return mw()->content_manager->next_content($content_id);
}


function prev_content($content_id = false)
{
    return mw()->content_manager->prev_content($content_id);
}

function breadcrumb($params = false){


    return mw()->content_manager->breadcrumb($params);


}
function custom_field_value($content_id, $field_name, $table = 'content')
{
    return mw()->fields_manager->get_value($content_id, $field_name, $table);
}


function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false)
{
    if (isset($table) and intval($table) > 0) {
        $id = intval(intval($table));
        $table = 'content';
    }
    return mw()->fields_manager->get($table, $id, $return_full, $field_for, $debug, $field_type, $for_session);
}


function get_custom_field_by_id($id)
{
    return mw()->fields_manager->get_by_id($id);
}

function save_custom_field($data)
{
    return mw()->fields_manager->save($data);
}

function delete_custom_field($data)
{
    return mw()->fields_manager->delete($data);
}

function make_custom_field($field_id = 0, $field_type = 'text', $settings = false)
{
    return mw()->fields_manager->make($field_id, $field_type, $settings);
}
