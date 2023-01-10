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

function is_home()
{
    if (defined('IS_HOME')) {
        return IS_HOME;
    }
}

function is_category()
{
    if (category_id()) {
        return true;
    }
}
function is_product()
{
    if (product_id()) {
        return true;
    }
}


function page_id()
{
    return app()->content_manager->page_id();
}



function post_id()
{
    return app()->content_manager->post_id();
}

function product_id()
{
    return app()->content_manager->product_id();
}

function content_id()
{
    return app()->content_manager->content_id();
}

function category_id()
{
    return app()->content_manager->category_id();
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
 * @since  0.1
 * @link   http://microweber.com/docs/functions/get_content
 *
 * @param array|string|bool $params Filter content by parameters
 * @params int $params['limit'] - Limit the results, default is 30
 *
 * @return array The database results
 */
function get_content($params = false)
{
    return app()->content_manager->get($params);
}

/**
 * Same as get_content(), just it checks if the user is admin.
 *
 * @see get_content
 *
 * @param array|bool $params Optional parameters to filter the content
 *
 * @return array The database results
 */
function get_content_admin($params)
{
    if (is_admin() == false) {
        return false;
    }

    if(isset($params['keyword'])){
        $params['keywords_exact_match'] = true;
  //      $params['no_cache'] = true;
    }


    return get_content($params);
}

/**
 * Return array of posts specified by $params.
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 *
 * @return string The url of the content
 *
 * @link    http://microweber.com/docs/functions/get_posts
 *
 * @uses    get_content()
 *
 * @example
 * <code>
 * //get array of posts
 * $content = get_posts('parent=5');
 *
 * if (!empty($content)) {
 *      foreach($content as $item){
 *       print $item['id'];
 *       print $item['title'];
 *       print app()->content_manager->link($item['id']);
 *      }
 * }
 * </code>
 */
function get_posts($params = false)
{
    return app()->content_manager->get_posts($params);
}

/**
 * Return array of pages specified by $params.
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 *
 * @return string The url of the content
 *
 * @link    http://microweber.com/docs/functions/get_pages
 */
function get_pages($params = false)
{
    return app()->content_manager->get_pages($params);
}

function get_products($params = false)
{
    return app()->content_manager->get_products($params);
}

/**
 * Gets a single content item by id.
 *
 * Function parameters:
 *
 *     'id' - the id of the content
 *
 * @since 0.1
 * @link  http://microweber.com/docs/functions/get_content_by_id
 *
 * @param int|bool $params Optional
 *
 * @return array The database results
 */
function get_content_by_id($params = false)
{
    return app()->content_manager->get_by_id($params);
}

/**
 * Returns the link of a given content id.
 *
 * Function parameters:
 *
 *     'id' - the id of the content
 *
 * @since 0.1
 * @link  http://microweber.com/docs/functions/get_content_by_id
 *
 * @param bool|int $id the id of the content
 *
 * @return string The content URL
 */
function content_link($id = false)
{
    return app()->content_manager->link($id);
}

function content_edit_link($id = false)
{
    return app()->content_manager->edit_link($id);
}

function content_title($id = false)
{
    return app()->content_manager->title($id);
}

function content_description($id = false)
{
    return app()->content_manager->description($id);
}

/**
 * @param bool $id
 * @return mixed
 */
function content_date($id = false)
{
    if ($id == false) {
        $id = CONTENT_ID;
    }


    $cont = app()->content_manager->get_by_id($id);
    if (isset($cont['created_at'])) {
        return $cont['created_at'];
    }
}

/**
 * Send content to trash or delete it forever.
 *
 * @since 0.1
 * @link  http://microweber.com/docs/functions/delete_content
 *
 * @param $data The content to delete
 *
 * @return array|string
 */
function delete_content($data)
{
    return app()->content_manager->helpers->delete($data);
}

/**
 * Returns html code with paging links.
 *
 * @param $params ['num'] = 5; //the numer of pages
 *
 * @internal  param $display =
 *            'default' //sets the default paging display with <ul> and </li>
 *            tags. If $display = false, the function will return the paging
 *            array which is the same as $posts_pages_links in every template
 *
 * @return string - html string with ul/li
 */
function paging($params)
{
    return app()->content_manager->paging($params);
}

/**
 * Get the content parent pages recursively.
 *
 * @since 0.1
 * @link  http://microweber.com/docs/functions/content_parents
 *
 * @param int $id
 * @param bool $without_main_parent If true, it will exclude the $id from the results
 *
 * @return array The parent content items
 */
function content_parents($id = 0, $without_main_parent = false)
{
    return app()->content_manager->get_parents($id, $without_main_parent);
}

function get_content_children($id = 0, $without_main_parent = false)
{
    return app()->content_manager->get_children($id, $without_main_parent);
}

function page_link($id = false)
{
    if ($id == false and defined('PAGE_ID')) {
        $id = PAGE_ID;
    }

    return app()->content_manager->link($id);
}

function page_title($id = false)
{
    if ($id == false and defined('PAGE_ID')) {
        $id = PAGE_ID;
    }

    return app()->content_manager->title($id);
}

function post_link($id = false)
{
    return app()->content_manager->link($id);
}

function pages_tree($params = false)
{
    return app()->content_manager->pages_tree($params);
}

function save_edit($post_data)
{
    return app()->content_manager->save_edit($post_data);
}

/**
 * Function to save content into the content_table.
 *
 * @param array|string $data data to save
 * @param bool $delete_the_cache
 *
 * @return string | the id saved
 *
 * @version 1.0
 *
 * @since   Version 0.1
 */
function save_content($data, $delete_the_cache = true)
{
    return app()->content_manager->save_content($data, $delete_the_cache);
}

function save_content_admin($data, $delete_the_cache = true)
{
    return app()->content_manager->save_content_admin($data, $delete_the_cache);
}

function save_content_field($data, $delete_the_cache = true)
{
    return app()->content_manager->save_content_field($data, $delete_the_cache);
}

function content_custom_fields($content_id, $full = true, $field_type = false)
{
    return app()->content_manager->custom_fields($content_id, $full, $field_type);
}

function get_content_field_draft($data)
{
    return app()->content_manager->helpers->get_edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{
    return app()->content_manager->edit_field($data, $debug);
}

function content_data($content_id, $field_name = false)
{
    return app()->content_manager->data($content_id, $field_name);
}

function content_attributes($content_id)
{
    return app()->content_manager->attributes($content_id);
}

function next_content($content_id = false)
{
    return app()->content_manager->next_content($content_id);
}

function next_post($content_id = false)
{
    return app()->content_manager->next_content($content_id, $mode = 'next', $content_type = 'post');
}

function prev_post($content_id = false)
{
    return app()->content_manager->next_content($content_id, $mode = 'prev', $content_type = 'post');
}

function prev_content($content_id = false)
{
    return app()->content_manager->prev_content($content_id);
}

function breadcrumb($params = false)
{
    return app()->content_manager->breadcrumb($params);
}

function helper_body_classes()
{
    $template = template_name();

    $classes = array();

    if (page_id()) {
        $classes[] = 'page-id-' . page_id();
    }
    if (post_id()) {
        $classes[] = 'post-id-' . post_id();
    }
    if (content_id()) {
        $classes[] = 'content-id-' . content_id();
    }
    if (category_id()) {
        $classes[] = 'category-id-' . category_id();
    }
    $seg = url_segment(0);
    if ($seg) {
        if (category_id()) {
            $slugs = app()->permalink_manager->link(category_id(), 'category', $returnSlug = 1);
            if (isset($slugs['slug']) and $slugs['slug']) {
                $classes[] = 'category-' . $slugs['slug'];
            }
        }

        $slugs = app()->permalink_manager->link(content_id(), 'content',$returnSlug=1);
        if(isset($slugs['slug']) and $slugs['slug']){
            $classes[] = 'page-' . $slugs['slug'];
        }

    }

    if ($template) {
        $classes[] = $template;
    }

    return implode(' ', $classes);
}
