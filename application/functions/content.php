<?php

/**
 * Get array of content items from the database
 *
 * It accepts string or array as parameters. You can pass any db field name as parameter to filter content by it.
 * All parameter are passed to the get() function
 *
 * You can get and filter content and also order the results by criteria
 *
 *
 *
 *
 * @function get_content
 * @package Content
 *
 *
 * @desc  Get array of content items from the content DB table
 *
 * @uses get() You can use all the options of get(), such as limit, order_by, count, etc...
 *
 * @param mixed|array|bool|string $params You can pass parameters as string or as array
 * @params
 *
 * *Some parameters you can use*
 *  You can use all defined database fields as parameters
 *
 * .[params-table]
 *|-----------------------------------------------------------------------------
 *| Field Name     | Description      | Values
 *|------------------------------------------------------------------------------
 *| id            | the id of the content |
 *| is_active        | flag published or unpublished content | "y" or "n"
 *| parent            | get content with parent  | any id or 0
 *| created_by        | get by author id  | any user id
 *| created_on        | the date of creation  |
 *| updated_on        | the date of last edit  |
 *| content_type    | the type of the content | "page" or "post", anything custom
 *| subtype            | subtype of the content | "static","dynamic","post","product", anything custom
 *| url                | the link to the content |
 *| title            | Title of the content |
 *| content            | The html content saved in the database |
 *| description     | Description used for the content list |
 *| position        | The order position |
 *| active_site_template        | Current template for the content |
 *| layout_file        | Current layout from the template directory |
 *| is_deleted        | flag for deleted content |  "n" or "y"
 *| is_home        | flag for homepage |  "n" or "y"
 *| is_shop        | flag for shop page |  "n" or "y"
 *
 *
 * @return array|bool|mixed Array of content or false if nothing is found
 * @example
 * #### Get with parameters as array
 * <code>
 *
 * $params = array();
 * $params['is_active'] = 'y'; //get only active content
 * $params['parent'] = 2; //get by parent id
 * $params['created_by'] = 1; //get by author id
 * $params['content_type'] = 'post'; //get by content type
 * $params['subtype'] = 'product'; //get by subtype
 * $params['title'] = 'my title'; //get by title
 *
 * $data = get_content($params);
 * var_dump($data);
 *
 * </code>
 *
 * @example
 * #### Get by params as string
 * <code>
 *  $data = get_content('is_active=y');
 *  var_dump($data);
 * </code>
 *
 * @example
 * #### Ordering and sorting
 * <code>
 *  //Order by position
 *  $data = get_content('content_type=post&is_active=y&order_by=position desc');
 *  var_dump($data);
 *
 *  //Order by date
 *  $data = get_content('content_type=post&is_active=y&order_by=updated_on desc');
 *  var_dump($data);
 *
 *  //Order by title
 *  $data = get_content('content_type=post&is_active=y&order_by=title asc');
 *  var_dump($data);
 *
 *  //Get content from last week
 *  $data = get_content('created_on=[mt]-1 week&is_active=y&order_by=title asc');
 *  var_dump($data);
 * </code>
 *
 */
function get_content($params = false)
{

    return \Content::get($params);
}

api_expose('get_content_admin');

function get_content_admin($params)
{
    if (is_admin() == false) {
        return false;
    }

    return get_content($params);
}




api_expose('content_link');
/**
 * Gets a link for given content id
 *
 * If you don't pass id parameter it will try to use the current page id
 *
 * @param int $id The $id The id of the content
 * @return string The url of the content
 * @package Content
 * @see post_link()
 * @see page_link()
 *
 *
 * @example
 * <code>
 * print content_link($id=1);
 * </code>
 *
 */
function content_link($id = 0)
{
    return \Content::link($id);
}


function page_link($id = false)
{
    return \Content::link($id);
}

function post_link($id = 0)
{
    return \Content::link($id);
}
/**
 * Return array of posts specified by $params
 *
 * This function makes query in the database and returns data from the content table
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
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
 *       print content_link($item['id']);
 *      }
 * }
 * </code>
 *
 */
function get_posts($params = false)
{
    return \Content::get($params);
}





/**
 * Defines all constants that are needed to parse the page layout
 *
 * It accepts array or $content that must have  $content['id'] set
 *
 * @example
 * <code>
 *  Define constants for some page
 *  $ref_page = get_content_by_id(1);
 *  define_constants($ref_page);
 *  print PAGE_ID;
 *  print POST_ID;
 *  print CATEGORY_ID;
 *  print MAIN_PAGE_ID;
 *  print DEFAULT_TEMPLATE_DIR;
 *  print DEFAULT_TEMPLATE_URL;
 * </code>
 *
 * @package Content
 * @subpackage Advanced
 * @const  PAGE_ID Defines the current page id
 * @const  POST_ID Defines the current post id
 * @const  CATEGORY_ID Defines the current category id if any
 * @const  ACTIVE_PAGE_ID Same as PAGE_ID
 * @const  CONTENT_ID current post or page id
 * @const  MAIN_PAGE_ID the parent page id
 * @const DEFAULT_TEMPLATE_DIR the directory of the site's default template
 * @const DEFAULT_TEMPLATE_URL the url of the site's default template
 */
function define_constants($content = false)
{

    return \Content::define_constants($content);
}


/**
 * Return the path to the layout file that will render the page
 *
 * It accepts array $page that must have  $page['id'] set
 *
 * @example
 * <code>
 *  //get the layout file for content
 *  $content = get_content_by_id($id=1);
 *  $render_file = get_layout_for_page($content);
 *  var_dump($render_file ); //print full path to the layout file ex. /home/user/public_html/userfiles/templates/default/index.php
 * </code>
 * @package Content
 * @subpackage Advanced
 */
function get_layout_for_page($page = array())
{
    return \Content::get_layout($page);

}


/**
 * Returns the homepage as array
 *
 * @category Content
 * @package Content
 */
function get_homepage()
{

    return \Content::homepage();
}

/**
 * Get the content as array by url
 *
 * @param string $url the url of the content
 * @param bool $no_recursive if true, it will not try to search for parent content
 * @return bool|string
 * @package Content
 */
function get_content_by_url($url = '', $no_recursive = false)
{
    return get_page_by_url($url, $no_recursive);
}


function get_page_by_url($url = '', $no_recursive = false)
{
    return \Content::get_by_url($url,$no_recursive);
}

/**
 * Get single content item by id from the content_table
 *
 * @param int $id The id of the content item
 * @return array
 * @category Content
 * @function  get_content_by_id
 *
 * @example
 * <pre>
 * $content = get_content_by_id(1);
 * var_dump($content);
 * </pre>
 *
 */
function get_content_by_id($id)
{
    return \Content::get_by_id($id);
}


/**
 * Get single content item by id from the content_table
 *
 * @param int|string $id The id of the page or the url of a page
 * @return array The page row from the database
 * @category Content
 * @function  get_page
 *
 * @example
 * <pre>
 * Get by id
 * $page = get_page(1);
 * var_dump($page);
 * </pre>
 * @example
 * <pre>
 * Get by url
 *
 * $page = get_page('home');
 * var_dump($page);
 *</pre>
 */
function get_page($id = 0)
{
    return \Content::get_page($id);

}

api_expose('reorder_content');
function reorder_content($params)
{


    return \ContentUtils::reorder($params);



}


function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword')
{

    return \Content::paging_links($base_url = false, $pages_count, $paging_param, $keyword_param);

}

/**
 * paging
 *
 * paging
 *
 * @access public
 * @category posts
 * @author Microweber
 * @link
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
    return \Content::paging($params);



}



function get_custom_fields_for_content($content_id, $full = true, $field_type = false)
{


    return \Content::custom_fields($content_id, $full , $field_type );


}


function save_edit($post_data)
{
    return \ContentUtils::save_edit($post_data);
}

api_expose('delete_content');

function delete_content($data)
{

    return \ContentUtils::delete($data);

}

/**
 * Function to save content into the content_table
 *
 * @param
 *            array
 *
 * @param
 *            boolean
 *
 * @return string | the id saved
 *
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
api_expose('save_content');

function save_content($data, $delete_the_cache = true)
{


    return \ContentUtils::save_content($data, $delete_the_cache);

}


//api_expose('save_content_field');

function save_content_field($data, $delete_the_cache = true)
{

    return \ContentUtils::save_content_field($data, $delete_the_cache);

}

api_expose('get_content_field_draft');
function get_content_field_draft($data)
{
    return \ContentUtils::edit_field_draft($data);
}

function get_content_field($data, $debug = false)
{


    return \Content::edit_field($data,$debug);


}

/**
 * Print nested tree of pages
 *
 * @example
 * <pre>
 * // Example Usage:
 * $pt_opts = array();
 * $pt_opts['link'] = "{title}";
 * $pt_opts['list_tag'] = "ol";
 * $pt_opts['list_item_tag'] = "li";
 * pages_tree($pt_opts);
 * </pre>
 *
 * @example
 * <pre>
 * // Example Usage to make <select> with <option>:
 * $pt_opts = array();
 * $pt_opts['link'] = "{title}";
 * $pt_opts['list_tag'] = " ";
 * $pt_opts['list_item_tag'] = "option";
 * $pt_opts['active_ids'] = $data['parent'];
 * $pt_opts['active_code_tag'] = '   selected="selected"  ';
 * $pt_opts['ul_class'] = 'nav';
 * $pt_opts['li_class'] = 'nav-item';
 *  pages_tree($pt_opts);
 * </pre>
 * @example
 * <pre>
 * // Other options
 * $pt_opts['parent'] = "8";
 * $pt_opts['include_first'] =  true; //includes the parent in the tree
 * $pt_opts['id_prefix'] = 'my_id';
 * </pre>
 *
 *
 *
 * @package Content
 * @param int $parent
 * @param bool $link
 * @param bool $active_ids
 * @param bool $active_code
 * @param bool $remove_ids
 * @param bool $removed_ids_code
 * @param bool $ul_class_name
 * @param bool $include_first
 * @return sting Prints the pages tree
 */
function pages_tree($parent = 0, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)
{
    return \Content::pages_tree($parent , $link , $active_ids , $active_code , $remove_ids, $removed_ids_code, $ul_class_name, $include_first );
}

function mw_create_default_content($what)
{

    return \ContentUtils::create_default_content($what);

}


api_expose('content_set_published');


/**
 * Set content to be published
 *
 * Set is_active flag 'y'
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @subpackage Advanced
 *
 * @uses save_content()
 * @see content_set_unpublished()
 * @example
 * <code>
 * //set published the content with id 5
 * content_set_published(5);
 *
 * //alternative way
 * content_set_published(array('id' => 5));
 * </code>
 *
 */
function content_set_published($params)
{

    return \ContentUtils::set_published($params);

}

api_expose('content_set_unpublished');
/**
 * Set content to be unpublished
 *
 * Set is_active flag 'n'
 *
 * @param string|array|bool $params
 * @return string The url of the content
 * @package Content
 * @subpackage Advanced
 *
 * @uses save_content()
 * @see content_set_unpublished()
 * @example
 * <code>
 * //set published the content with id 5
 * content_set_unpublished(5);
 *
 * //alternative way
 * content_set_unpublished(array('id' => 5));
 * </code>
 *
 */
function content_set_unpublished($params)
{

    return \ContentUtils::set_unpublished($params);

}

function get_content_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
{
    return \ContentUtils::get_parents($id , $without_main_parrent, $data_type);

}


/**
 *  Get the first parent that has layout
 *
 * @category Content
 * @package Content
 * @subpackage Advanced
 * @uses get_content_parents()
 * @uses get_content_by_id()
 */
function get_content_inherited_parent($content_id)
{
    return \ContentUtils::get_inherited_parent($content_id);



}

