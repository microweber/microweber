<?php
action_hook('mw_db_init_default', '\Content::db_init');
action_hook('on_load', '\Content::db_init');


class Content {



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
   static function link($id = 0)
    {
        if (is_string($id)) {
            // $link = page_link_to_layout ( $id );
        }
        if ($id == false or $id == 0) {
            if (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }
        if ($id == 0) {
            return site_url();
        }

        $link = get_content_by_id($id);
        if (strval($link['url']) == '') {
            $link = get_page_by_url($id);
        }
        $link = site_url($link['url']);
        return $link;
    }


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
    static function get($params = false)
    {

        if (defined('MW_API_CALL')) {
            if (isset($_REQUEST['api_key']) and is_admin() == 0) {
                api_login($_REQUEST['api_key']);
                if (is_admin() == 0) {
                    return false;
                }
            }

        }

        if (defined('PAGE_ID') == false) {
            define_constants();
        }


        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!is_array($params)) {
            $params = array();
            $params['is_active'] = 'y';
        }


        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($params);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        $cache_content = false;
        // $cache_content = cache_get_content($function_cache_id, $cache_group = 'content/global');
        if (($cache_content) == '--false--') {
            //return false;
        }
        // $cache_content = false;
        if (($cache_content) != false) {

            //	return $cache_content;
        } else {

            // $params['orderby'];
            if (isset($params['orderby'])) {
                $orderby = $params['orderby'];
            } else {
                //$params['orderby'] = 'position desc, id desc';

            }

            $cache_group = 'content/global';
            if (isset($params['cache_group'])) {
                $cache_group = $params['cache_group'];
            }


            if (isset($params['limit'])) {
                // $limit = $params['limit'];
            } else {
//            $limit = array();
//            $limit[0] = '0';
//
//            $limit[1] = '30';
            }

            $table = MW_TABLE_PREFIX . 'content';
            if (!isset($params['is_deleted'])) {
                $params['is_deleted'] = 'n';
            }
            $params['table'] = $table;
            $params['cache_group'] = $cache_group;
            $get = get($params);


            if (isset($params['count']) or isset($params['single']) or isset($params['one'])  or isset($params['data-count']) or isset($params['page_count']) or isset($params['data-page-count'])) {
                if (isset($get['url'])) {
                    $get['url'] = site_url($get['url']);
                }
                if (isset($get['title'])) {
                    //$item['url'] = page_link($item['id']);
                    $get['title'] = string_clean($get['title']);
                }
                return $get;
            }
            if (isarr($get)) {
                $data2 = array();
                foreach ($get as $item) {
                    if (isset($item['url'])) {
                        //$item['url'] = page_link($item['id']);
                        $item['url'] = site_url($item['url']);
                    }
                    if (isset($item['title'])) {
                        //$item['url'] = page_link($item['id']);
                        $item['title'] = string_clean($item['title']);
                    }

                    $data2[] = $item;
                }
                $get = $data2;
                //  cache_save($get, $function_cache_id, $cache_group = 'content/global');

                return $get;
            } else {
                // cache_save('--false--', $function_cache_id, $cache_group = 'content/global');

                return FALSE;
            }
        }
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
    static function get_page($id = 0)
    {
        if ($id == false or $id == 0) {
            return false;
        }

        // $CI = get_instance ();
        if (intval($id) != 0) {
            $page = get_content_by_id($id);

            if (empty($page)) {
                $page = get_content_by_url($id);
            }
        } else {
            if (empty($page)) {
                $page = array();
                $page['layout_name'] = trim($id);

                $page = get_content($page);
                $page = $page[0];
            }
        }

        return $page;

        // $link = get_instance()->content_model->getContentURLByIdAndCache (
        // $link['id'] );
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
    static function get_by_id($id)
    {
        global $mw_global_content_memory;

        if (isset($mw_global_content_memory[$id])) {
            return $mw_global_content_memory[$id];
        }

        // ->'content';
        $table = MW_TABLE_PREFIX . 'content';

        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        //$q = "SELECT * FROM $table WHERE id='$id'  LIMIT 0,1 ";

        $params = array();
        $params['id'] = $id;
        $params['limit'] = 1;
        $params['table'] = $table;
        //$params['debug'] = 1;
        $params['cache_group'] = 'content/' . $id;


        $q = get($params);

        //  $q = db_get($table, $params, $cache_group = 'content/' . $id);
        //  $q = db_query($q, __FUNCTION__ . crc32($q), 'content/' . $id);
        if (isset($q[0])) {
            $content = $q[0];
            if (isset($content['title'])) {
                $content['title'] = string_clean($content['title']);
            }
        } else {

            $mw_global_content_memory[$id] = false;
            return false;
        }
        $mw_global_content_memory[$id] = $content;
        return $content;
    }

    static  function custom_fields($content_id, $full = true, $field_type = false)
    {

        return \CustomFields::get('content', $content_id, $full, false, false, $field_type);


    }



    static function edit_field($data, $debug = false)
    {


        $table = MW_DB_TABLE_CONTENT_FIELDS;

        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (!is_array($data)) {
            $data = array();
        }
        // d($data);


        if (isset($data['is_draft'])) {
            $table = $table_drafts;
        }

        if (!isset($data['rel'])) {
            if (isset($data['rel'])) {
                if ($data['rel'] == 'content' or $data['rel'] == 'page' or $data['rel'] == 'post') {
                    $data['rel'] = 'content';
                }
                $data['rel'] = $data['rel'];
            }
        }
        if (!isset($data['rel_id'])) {
            if (isset($data['data-id'])) {
                $data['rel_id'] = $data['data-id'];
            } else {

            }
        }

        if (!isset($data['rel_id']) and !isset($data['is_draft'])) {
            $data['rel_id'] = 0;
        }

        if ((!isset($data['rel']) or !isset($data['rel_id'])) and !isset($data['is_draft'])) {
            error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }

        if ((isset($data['rel']) and isset($data['rel_id']))) {

            $data['cache_group'] = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
        } else {
            $data['cache_group'] = guess_cache_group('content_fields/global');

        }
        if (!isset($data['all'])) {
            // $data['one'] = 1;
            $data['limit'] = 1000;
        }

        $data['table'] = $table;

        $get = get($data);



        if (!isset($data['full']) and isset($get['value'])) {
            return $get['value'];
        } else {
            return $get;
        }


        return false;


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
    static function paging($params)
    {
        $params = parse_params($params);

        $pages_count = 1;
        $base_url = false;
        $paging_param = 'curent_page';
        $keyword_param = 'keyword_param';
        $class = 'pagination';
        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }


        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }


        if (isset($params['class'])) {
            $class = $params['class'];
        }

        if (isset($params['paging_param'])) {
            $paging_param = $params['paging_param'];
        }
        $curent_page_from_url = url_param($paging_param);

        if (isset($params['curent_page'])) {
            $curent_page_from_url = $params['curent_page'];
        }

        $data = self::paging_links($base_url, $pages_count, $paging_param, $keyword_param);
        if (isarr($data)) {
            $to_print = "<div class='{$class}'><ul>";
            foreach ($data as $key => $value) {
                $act_class = '';

                if ($curent_page_from_url != false) {
                    if (intval($curent_page_from_url) == intval($key)) {
                        $act_class = ' class="active" ';
                    }
                }
                $to_print .= "<li {$act_class} data-page-number=\"$key\">";
                $to_print .= "<a {$act_class} href=\"$value\" data-page-number=\"$key\">$key</a> ";
                $to_print .= "</li>";
            }
            $to_print .= "</ul></div>";
            return $to_print;
        }


    }

    static function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword')
    {

        // getCurentURL()
        if ($base_url == false) {
            /*if (PAGE_ID != false and CATEGORY_ID == false) {
                $base_url = page_link(PAGE_ID);

                // p($base_url);
            } elseif (PAGE_ID != false and CATEGORY_ID != false) {
                $base_url = category_link(CATEGORY_ID);
            } else {

                // $base_url =  full_url(true);
            }*/

            if (isAjax() == false) {
                $base_url = curent_url(1);

            } else {
                if ($_SERVER['HTTP_REFERER'] != false) {
                    $base_url = $_SERVER['HTTP_REFERER'];
                }
            }


        }

        $page_links = array();


        $the_url = $base_url;

        $append_to_links = '';
        if (strpos($the_url, '?')) {
            $the_url = substr($the_url, 0, strpos($the_url, '?'));


        }


        if (isset($_GET) and !empty($_GET)) {
        }


        $the_url = explode('/', $the_url);


        for ($x = 1; $x <= $pages_count; $x++) {

            //$new_url = array();

            $new = array();

            foreach ($the_url as $itm) {

                $itm = explode(':', $itm);

                if ($itm[0] == $paging_param) {

                    $itm[1] = $x;
                }

                $new[] = implode(':', $itm);
            }

            $new_url = implode('/', $new);

            // var_dump ( $new_url);

            $page_links[$x] = $new_url . $append_to_links;
        }

        for ($x = 1; $x <= count($page_links); $x++) {

            if (stristr($page_links[$x], $paging_param . ':') == false) {

                $l = reduce_double_slashes($page_links[$x] . '/' . $paging_param . ':' . $x);
                $l = str_ireplace('module/', '', $l);
                $page_links[$x] = $l . $append_to_links;
            }
        }

        return $page_links;
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
    static function define_constants($content = false)
    {

        if ($content == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $ref_page = $_SERVER['HTTP_REFERER'];
                if ($ref_page != '') {
                    $ref_page = get_content_by_url($ref_page);
                    if (!empty($ref_page)) {
                        $content = $ref_page;

                    }
                }
            }
        }
//
        if (is_array($content)) {
            if (isset($content['id']) and $content['id'] != 0) {
                $content = get_content_by_id($content['id']);
                $page = $content;

            } else if (isset($content['id']) and $content['id'] == 0) {
                $page = $content;
            } else if (isset($content['active_site_template'])) {
                $page = $content;
            }
        }

        if (isset($page)) {
            if ($page['content_type'] == "post") {
                $content = $page;

                $page = get_content_by_id($page['parent']);
                if (defined('POST_ID') == false) {
                    define('POST_ID', $content['id']);
                }
            } else {
                $content = $page;
                if (defined('POST_ID') == false) {
                    define('POST_ID', false);
                }
            }

            if (defined('ACTIVE_PAGE_ID') == false) {

                define('ACTIVE_PAGE_ID', $page['id']);
            }

            if (defined('CATEGORY_ID') == false) {
                define('CATEGORY_ID', false);
            }

            if (defined('CONTENT_ID') == false) {
                define('CONTENT_ID', $content['id']);
            }

            if (defined('PAGE_ID') == false) {
                define('PAGE_ID', $page['id']);
            }
            if (isset($page['parent'])) {
                if (defined('MAIN_PAGE_ID') == false) {
                    define('MAIN_PAGE_ID', $page['parent']);
                }

                if (defined('PARENT_PAGE_ID') == false) {
                    define('PARENT_PAGE_ID', $page['parent']);
                }
            }
        }

        if (defined('ACTIVE_PAGE_ID') == false) {

            define('ACTIVE_PAGE_ID', false);
        }

        if (defined('CATEGORY_ID') == false) {
            define('CATEGORY_ID', false);
        }

        if (defined('CONTENT_ID') == false) {
            define('CONTENT_ID', false);
        }

        if (defined('POST_ID') == false) {
            define('POST_ID', false);
        }
        if (defined('PAGE_ID') == false) {
            define('PAGE_ID', false);
        }

        if (defined('MAIN_PAGE_ID') == false) {
            define('MAIN_PAGE_ID', false);
        }


        if (isset($page) and isset($page['active_site_template']) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {

            $the_active_site_template = $page['active_site_template'];
        } else {
            $the_active_site_template = get_option('curent_template');
            // d($the_active_site_template );
        }

        $the_active_site_template_dir = normalize_path(TEMPLATEFILES . $the_active_site_template . DS);

        if (defined('DEFAULT_TEMPLATE_DIR') == false) {

            define('DEFAULT_TEMPLATE_DIR', TEMPLATEFILES . 'default' . DS);
        }

        if (defined('DEFAULT_TEMPLATE_URL') == false) {

            define('DEFAULT_TEMPLATE_URL', site_url('userfiles/' . TEMPLATEFILES_DIRNAME . '/' . 'default/'));
        }


        if (trim($the_active_site_template) != 'default') {

            if ((!strstr($the_active_site_template, DEFAULT_TEMPLATE_DIR))) {
                $use_default_layouts = $the_active_site_template_dir . 'use_default_layouts.php';
                if (is_file($use_default_layouts)) {
                    //$render_file = ($use_default_layouts);
                    //if()
                    //
                    //

                    if (isset($page['layout_file'])) {
                        $template_view = DEFAULT_TEMPLATE_DIR . $page['layout_file'];
                    } else {
                        $template_view = DEFAULT_TEMPLATE_DIR;
                    }
                    if (isset($page)) {
                        if (!isset($page['layout_file']) or (isset($page['layout_file']) and $page['layout_file'] == 'inherit' or $page['layout_file'] == '')) {
                            $par_page = get_content_inherited_parent($page['id']);
                            if ($par_page != false) {
                                $par_page = get_content_by_id($par_page);
                            }
                            if (isset($par_page['layout_file'])) {
                                $the_active_site_template = $par_page['active_site_template'];
                                $page['layout_file'] = $par_page['layout_file'];
                                $page['active_site_template'] = $par_page['active_site_template'];
                                $template_view = TEMPLATEFILES . $page['active_site_template'] . DS . $page['layout_file'];


                            }

                        }
                    }

                    if (is_file($template_view) == true) {

                        if (defined('THIS_TEMPLATE_DIR') == false) {

                            define('THIS_TEMPLATE_DIR', TEMPLATEFILES . $the_active_site_template . DS);

                        }
                        if (defined('THIS_TEMPLATE_URL') == false) {
                            $the_template_url = site_url('userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template);

                            $the_template_url = $the_template_url . '/';
                            if (defined('THIS_TEMPLATE_URL') == false) {
                                define("THIS_TEMPLATE_URL", $the_template_url);
                            }
                            if (defined('TEMPLATE_URL') == false) {
                                define("TEMPLATE_URL", $the_template_url);
                            }
                        }
                        $the_active_site_template = 'default';
                        $the_active_site_template_dir = DEFAULT_TEMPLATE_DIR;

                        //	d($the_active_site_template_dir);
                    }


                }
            }

        } else {
            //d($the_active_site_template);
        }


        if (defined('ACTIVE_TEMPLATE_DIR') == false) {

            define('ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_DIR') == false) {

            define('THIS_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_URL') == false) {
            $the_template_url = site_url('userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template);

            $the_template_url = $the_template_url . '/';
            if (defined('THIS_TEMPLATE_URL') == false) {
                define("THIS_TEMPLATE_URL", $the_template_url);
            }
        }
        if (defined('TEMPLATE_NAME') == false) {

            define('TEMPLATE_NAME', $the_active_site_template);
        }


        if (defined('TEMPLATE_DIR') == false) {

            define('TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('ACTIVE_SITE_TEMPLATE') == false) {

            define('ACTIVE_SITE_TEMPLATE', $the_active_site_template);
        }

        if (defined('TEMPLATES_DIR') == false) {

            define('TEMPLATES_DIR', TEMPLATEFILES);
        }

        $the_template_url = site_url('userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template);

        $the_template_url = $the_template_url . '/';
        if (defined('TEMPLATE_URL') == false) {
            define("TEMPLATE_URL", $the_template_url);
        }


        if (defined('LAYOUTS_DIR') == false) {

            $layouts_dir = TEMPLATE_DIR . 'layouts/';

            define("LAYOUTS_DIR", $layouts_dir);
        } else {

            $layouts_dir = LAYOUTS_DIR;
        }

        if (defined('LAYOUTS_URL') == false) {

            $layouts_url = reduce_double_slashes(dirToURL($layouts_dir) . '/');

            define("LAYOUTS_URL", $layouts_url);
        } else {

            // $layouts_url = LAYOUTS_URL;
        }


        return true;
    }



    static function get_by_url($url = '', $no_recursive = false)
    {
        if (strval($url) == '') {

            $url = url_string();
        }

        $u1 = $url;
        $u2 = site_url();

        $u1 = rtrim($u1, '\\');
        $u1 = rtrim($u1, '/');

        $u2 = rtrim($u2, '\\');
        $u2 = rtrim($u2, '/');
        $u1 = str_replace($u2, '', $u1);
        $u1 = ltrim($u1, '/');
        $url = $u1;
//d($url);
        // ->'content';
        $table = MW_TABLE_PREFIX . 'content';

        // $url = strtolower($url);
        //  $url = string_clean($url);
        $url = db_escape_string($url);
        $url = addslashes($url);

        $url12 = parse_url($url);
        if (isset($url12['scheme']) and isset($url12['host']) and isset($url12['path'])) {

            $u1 = site_url();
            $u2 = str_replace($u1, '', $url);
            $current_url = explode('?', $u2);
            $u2 = $current_url[0];
            $url = ($u2);
        } else {
            $current_url = explode('?', $url);
            $u2 = $current_url[0];
            $url = ($u2);
        }
        $url = rtrim($url, '?');
        $url = rtrim($url, '#');

        global $mw_skip_pages_starting_with_url;

        if (1 !== stripos($url, 'http://') && 1 !== stripos($url, 'https://')) {
            // $url = 'http://' . $url;
            // return false;

        }
        if (defined('MW_BACKEND')) {
            return false;

        }
        if (isarr($mw_skip_pages_starting_with_url)) {
            $segs = explode('/', $url);

            foreach ($mw_skip_pages_starting_with_url as $skip_page_url) {
                if (in_array($skip_page_url, $segs)) {
                    return false;
                }
                /* if (0 !== stripos($url, $skip_page_url) or 0 !== stripos($url, $skip_page_url.'/')){

                }*/
            }

        }


        global $mw_precahced_links;


        $link_hash = 'link' . crc32($url);

        if (isset($mw_precahced_links[$link_hash])) {
            return $mw_precahced_links[$link_hash];
        }


        $sql = "SELECT id FROM $table WHERE url='{$url}'   ORDER BY updated_on DESC LIMIT 0,1 ";

        $q = db_query($sql, __FUNCTION__ . crc32($sql), 'content/global');

        $result = $q;

        $content = $result[0];

        if (!empty($content)) {


            //$get_by_id = $content;
            $mw_precahced_links[$link_hash] = $content;
            return $content;
        }

        if ($no_recursive == false) {

            if (empty($content) == true) {

                // /var_dump ( $url );

                $segs = explode('/', $url);

                $segs_qty = count($segs);

                for ($counter = 0; $counter <= $segs_qty; $counter += 1) {

                    $test = array_slice($segs, 0, $segs_qty - $counter);

                    $test = array_reverse($test);

                    if (isset($test[0])) {
                        $url = get_page_by_url($test[0], true);
                    }
                    if (!empty($url)) {
                        $mw_precahced_links[$link_hash] = $url;
                        return $url;
                    }


                }
            }
        } else {

            if (isset($content['id']) and intval($content['id']) != 0) {
                $content['id'] = ((int)$content['id']);
            }
            //$get_by_id = get_content_by_id($content['id']);
            $mw_precahced_links[$link_hash] = $content;
            return $content;
        }
        $mw_precahced_links[$link_hash] = false;
        return false;
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
   static function get_layout($page = array())
    {


        $function_cache_id = '';
        if (is_array($page)) {
            ksort($page);
        }


        $function_cache_id = $function_cache_id . serialize($page);


        $cache_id = __FUNCTION__ . crc32($function_cache_id);
        if (isset($page['id']) and intval($page['id']) != 0) {
            $cache_group = 'content/' . $page['id'];


        } else {
            $cache_group = 'content/global';
        }
        $cache_content = cache_get_content($cache_id, $cache_group);

        if (($cache_content) != false) {

            return $cache_content;
        }

        $render_file = false;
        $look_for_post = false;
        $template_view_set_inner = false;

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            if (isset($page['id'])) {
                define_constants($page);
            }
        }


        if (isset($page['active_site_template']) and isset($page['active_site_template']) and isset($page['layout_file']) and $page['layout_file'] != 'inherit'  and $page['layout_file'] != '') {
            $test_file = str_replace('___', DS, $page['layout_file']);
            $render_file_temp = TEMPLATES_DIR . $page['active_site_template'] . DS . $test_file;

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            }
        }
        if ($render_file == false and isset($page['id']) and intval($page['id']) == 0) {
            $url_file = url_string(1, 1);
            $test_file = str_replace('___', DS, $url_file);
            $render_file_temp = ACTIVE_TEMPLATE_DIR . DS . $test_file . '.php';
            $render_file_temp2 = ACTIVE_TEMPLATE_DIR . DS . $url_file . '.php';

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            } elseif (is_file($render_file_temp2)) {
                $render_file = $render_file_temp2;
            }
        }


        if ($render_file == false and isset($page['id']) and isset($page['active_site_template']) and isset($page['layout_file']) and $page['layout_file'] == 'inherit') {

            $inherit_from = get_content_parents($page['id']);

            $found = 0;
            if (!empty($inherit_from)) {
                foreach ($inherit_from as $value) {
                    if ($found == 0 and $value != $page['id']) {
                        $par_c = get_content_by_id($value);
                        if (isset($par_c['id']) and isset($par_c['active_site_template']) and isset($par_c['layout_file']) and $par_c['layout_file'] != 'inherit') {

                            $page['layout_file'] = $par_c['layout_file'];
                            $page['active_site_template'] = $par_c['active_site_template'];
                            $render_file_temp = TEMPLATES_DIR . $page['active_site_template'] . DS . $page['layout_file'];

                            if (is_file($render_file_temp)) {
                                $render_file = $render_file_temp;
                            } else {

                                $render_file_temp = DEFAULT_TEMPLATE_DIR . $page['layout_file'];
                                if (is_file($render_file_temp)) {
                                    $render_file = $render_file_temp;

                                    //d(THIS_TEMPLATE_DIR);

                                }
                            }

                            //$page = $par_c;
                            //
                            $found = 1;
                        }
                    }
                }
            }
            //d($inherit_from);
        }


        if ($render_file == false and isset($page['content_type']) and $page['content_type'] == 'post') {
            $look_for_post = $page;
            if (isset($page['parent'])) {

                $par_page = get_content_by_id($page['parent']);

                if (isarr($par_page)) {
                    $page = $par_page;
                } else {
                    $template_view_set_inner = ACTIVE_TEMPLATE_DIR . DS . 'inner.php';
                    $template_view_set_inner2 = ACTIVE_TEMPLATE_DIR . DS . 'layouts/inner.php';


                }
            } else {
                $template_view_set_inner = ACTIVE_TEMPLATE_DIR . DS . 'inner.php';
                $template_view_set_inner2 = ACTIVE_TEMPLATE_DIR . DS . 'layouts/inner.php';


            }
        }

        if ($render_file == false and isset($page['simply_a_file'])) {


            $simply_a_file2 = ACTIVE_TEMPLATE_DIR . $page['simply_a_file'];
            $simply_a_file3 = ACTIVE_TEMPLATE_DIR . 'layouts' . DS . $page['simply_a_file'];

            if ($render_file == false and  is_file($simply_a_file3) == true) {
                $render_file = $simply_a_file3;

            }


            if ($render_file == false and  is_file($simply_a_file2) == true) {
                $render_file = $simply_a_file2;

            }


            if ($render_file == false and is_file($page['simply_a_file']) == true) {
                $render_file = $page['simply_a_file'];
            }
            //


        }
        if (!isset($page['active_site_template'])) {
            $page['active_site_template'] = ACTIVE_SITE_TEMPLATE;
        }
        if ($render_file == false and isset($page['active_site_template']) and trim($page['active_site_template']) != 'default') {

            $use_default_layouts = TEMPLATES_DIR . $page['active_site_template'] . DS . 'use_default_layouts.php';

            if (is_file($use_default_layouts)) {
                $page['active_site_template'] = 'default';
            }
        }
        if ($render_file == false and isset($page['active_site_template']) and isset($page['content_type']) and $render_file == false and !isset($page['layout_file'])) {
            $layouts_list = layouts_list('site_template=' . $page['active_site_template']);

            if (isarr($layouts_list)) {
                foreach ($layouts_list as $layout_item) {
                    if ($render_file == false and isset($layout_item['content_type']) and isset($layout_item['layout_file']) and $page['content_type'] == $layout_item['content_type']) {

                        $page['layout_file'] = $layout_item['layout_file'];
                        $render_file = TEMPLATES_DIR . $page['active_site_template'] . DS . $page['layout_file'];
                    }

                }
            }


        }


        if ($render_file == false and isset($page['active_site_template']) and $render_file == false and isset($page['layout_file'])) {
            if ($look_for_post != false) {
                $f1 = $page['layout_file'];


                $stringA = $f1;
                $stringB = "_inner";
                $length = strlen($stringA);
                $temp1 = substr($stringA, 0, $length - 4);
                $temp2 = substr($stringA, $length - 4, $length);
                $f1 = $temp1 . $stringB . $temp2;


                if (strtolower($page['active_site_template']) == 'default') {
                    $template_view = ACTIVE_TEMPLATE_DIR . DS . $f1;
                } else {

                    $template_view = TEMPLATES_DIR . $page['active_site_template'] . DS . $f1;
                }


//.


                if (is_file($template_view) == true) {

                    $render_file = $template_view;
                } else {


                    $dn = dirname($template_view);
                    $dn1 = $dn . DS;
                    $f1 = $dn1 . 'inner.php';

                    if (is_file($f1) == true) {
                        $render_file = $f1;
                    } else {
                        $dn = dirname($dn);
                        $dn1 = $dn . DS;
                        $f1 = $dn1 . 'inner.php';

                        if (is_file($f1) == true) {
                            $render_file = $f1;
                        } else {
                            $dn = dirname($dn);
                            $dn1 = $dn . DS;
                            $f1 = $dn1 . 'inner.php';

                            if (is_file($f1) == true) {
                                $render_file = $f1;
                            }
                        }
                    }


                }
            }


            if ($render_file == false) {
                if (strtolower($page['active_site_template']) == 'default') {

                    $template_view = ACTIVE_TEMPLATE_DIR . DS . $page['layout_file'];
                } else {
                    $template_view = TEMPLATES_DIR . $page['active_site_template'] . DS . $page['layout_file'];
                }

                if (is_file($template_view) == true) {
                    $render_file = $template_view;
                } else {


                    if (trim($page['active_site_template']) != 'default') {
                        $use_default_layouts = TEMPLATES_DIR . $page['active_site_template'] . DS . 'use_default_layouts.php';


                        if (is_file($use_default_layouts)) {
                            $page['active_site_template'] = 'default';

                        }


                    }


                }
            }

        }


        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) == 'default') {
            $template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) != 'default') {
            $template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }
        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) != 'default') {
            $template_view = ACTIVE_TEMPLATE_DIR . 'index.html';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if (isset($page['active_site_template']) and $render_file == false and strtolower($page['active_site_template']) != 'default') {
            $template_view = ACTIVE_TEMPLATE_DIR . 'index.htm';
            if (is_file($template_view) == true) {
                $render_file = $template_view;
            }
        }

        if ($render_file == false and $template_view_set_inner != false) {

            if (isset($template_view_set_inner2)) {
                $template_view_set_inner2 = normalize_path($template_view_set_inner2, false);
                if (is_file($template_view_set_inner2) == true) {
                    $render_file = $template_view_set_inner2;
                }
            }

            $template_view_set_inner = normalize_path($template_view_set_inner, false);
            if ($render_file == false and is_file($template_view_set_inner) == true) {
                $render_file = $template_view_set_inner;
            }


        }

        if (isset($page['custom_view']) and isset($render_file)) {
            $check_custom = dirname($render_file) . DS;
            $cv = trim($page['custom_view']);
            $cv = str_replace('..', '', $cv);
            $cv = str_ireplace('.php', '', $cv);
            $check_custom_f = $check_custom . $cv . '.php';
            if (is_file($check_custom_f)) {
                $render_file = $check_custom_f;
            }

        }
        if ($render_file == false and ($page['layout_file']) != false) {
            $template_view = ACTIVE_TEMPLATE_DIR . DS . $page['layout_file'];
            $template_view = normalize_path($template_view, false);

            if (is_file($template_view) == true) {
                $render_file = $template_view;
            } else {

            }
        }
        cache_save($render_file, $cache_id, $cache_group);

        return $render_file;
    }



    /**
     * Returns the homepage as array
     *
     * @category Content
     * @package Content
     */
    static function homepage()
    {

        // ->'content';
        $table = MW_TABLE_PREFIX . 'content';


        $sql = "SELECT * FROM $table WHERE is_home='y'  ORDER BY updated_on DESC LIMIT 0,1 ";

        $q = db_query($sql, __FUNCTION__ . crc32($sql), 'content/global');
        // var_dump($q);
        $result = $q;

        $content = $result[0];

        return $content;
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
    static function pages_tree($parent = 0, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)
    {

        $params2 = array();
        $params = false;
        $output = '';
        if (is_integer($parent)) {

        } else {
            $params = $parent;
            if (is_string($params)) {
                $params = parse_str($params, $params2);
                $params = $params2;
                extract($params);
            }
            if (is_array($params)) {
                $parent = 0;
                extract($params);
            }
        }
        if (!defined('CONTENT_ID')) {
            define_constants();
        }
        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = __FUNCTION__ . crc32($function_cache_id) . CONTENT_ID . PAGE_ID;
        if ($parent == 0) {
            $cache_group = 'content/global';
        } else {
            //$cache_group = 'content/' . $parent;
            $cache_group = 'categories/global';
        }
        if (isset($include_categories) and $include_categories == true) {
            $cache_group = 'categories/global';
        }


        //

        $cache_content = cache_get_content($function_cache_id, $cache_group);
        // $cache_content = false;
//	if (!isset($_GET['debug'])) {
        if (($cache_content) != false) {

            if (isset($params['return_data'])) {
                return $cache_content;
            } else {
                print $cache_content;
            }

            return;
            //  return $cache_content;
        }
        //}
        $nest_level = 0;

        if (isset($params['nest_level'])) {
            $nest_level = $params['nest_level'];
        }
        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        } else if (isset($params['maxdepth'])) {
            $max_level = $params['max_level'] = $params['maxdepth'];
        }

        if ($max_level != false) {

            if (intval($nest_level) >= intval($max_level)) {
                print '';
                return;
            }
        }


        $is_shop = '';
        if (isset($params['is_shop'])) {
            $is_shop = db_escape_string($params['is_shop']);
            $is_shop = " and is_shop='{$is_shop} '";
            $include_first = false;

        }
        $ul_class = 'pages_tree';
        if (isset($params['ul_class'])) {

            $ul_class_name = $ul_class = $params['ul_class'];
        }

        $li_class = 'pages_tree_item';
        if (isset($params['li_class'])) {

            $li_class = $params['li_class'];
        }


        if (isset($params['include_categories'])) {

            $include_categories = $params['include_categories'];
        }


        ob_start();


        $table = MW_TABLE_PREFIX . 'content';
        $par_q = '';
        if ($parent == false) {

            $parent = (0);
        } else {
            $par_q = " parent=$parent    and  ";

        }

        if ($include_first == true) {
            $sql = "SELECT * from $table where  id={$parent}    and   is_deleted='n' and content_type='page' " . $is_shop . "  order by position desc  limit 0,1";
            //
        } else {

            //$sql = "SELECT * from $table where  parent=$parent    and content_type='page'  order by updated_on desc limit 0,1";
            $sql = "SELECT * from $table where  " . $par_q . "  content_type='page' and   is_deleted='n' $is_shop  order by position desc limit 0,100";
        }

        //$sql = "SELECT * from $table where  parent=$parent    and content_type='page'  order by updated_on desc limit 0,1000";

        $cid = __FUNCTION__ . crc32($sql);
        $cidg = 'content/' . $parent;

        //$q = db_query($sql, $cid, $cidg);
        if (!isarr($params)) {
            $params = array();
        }

        if (isset($append_to_link) == false) {
            $append_to_link = '';
        }
        if (isset($id_prefix) == false) {
            $id_prefix = '';
        }

        if (isset($link) == false) {
            $link = '<span data-page-id="{id}" class="pages_tree_link {nest_level} {active_class} {active_parent_class}" href="{link}' . $append_to_link . '">{title}</span>';
        }

        if (isset($list_tag) == false) {
            $list_tag = 'ul';
        }

        if (isset($active_code_tag) == false) {
            $active_code_tag = '';
        }

        if (isset($list_item_tag) == false) {
            $list_item_tag = 'li';
        }

        if (isset($remove_ids) and is_string($remove_ids)) {
            $remove_ids = explode(',', $remove_ids);
        }
        if (isset($active_ids)) {
            $active_ids = $active_ids;
        }


        if (isset($active_ids) and is_string($active_ids)) {
            $active_ids = explode(',', $active_ids);
        }
        $the_active_class = 'active';
        if (isset($params['active_class'])) {
            $the_active_class = $params['active_class'];
        }


        //	$params['debug'] = $parent;
        //
        $params['content_type'] = 'page';

        $include_first_set = false;
        if ($include_first == true) {
            $include_first_set = 1;
            $include_first = false;
            $include_first_set = $parent;
            if (isset($params['include_first'])) {
                unset($params['include_first']);
            }
            if (isset($params['parent'])) {
                //unset($params['parent']);
            }


        } else {
            // if($parent != 0){
            $params['parent'] = $parent;
            // }
        }

        if (isset($params['is_shop']) and $params['is_shop'] == 'y') {
            if (isset($params['parent']) and $params['parent'] == 0) {
                unset($params['parent']);
            }

            if (isset($params['parent']) and $params['parent'] == 'any') {
                unset($params['parent']);

            }

        } else {

            if (isset($params['parent']) and $params['parent'] == 'any') {
                $params['parent'] = 0;

            }


        }


        $params['limit'] = 50;
        $params['orderby'] = 'position desc';

        $params['curent_page'] = 1;

        $params['is_deleted'] = 'n';

        $skip_pages_from_tree = false;
        $params2 = $params;

        if (isset($params['is_shop']) and $params['is_shop'] == 'y') {

            //$max_level = $params2['max_level'] =2;
            // $skip_pages_from_tree = 1;
            //	unset($params2['parent']);
//d($params2);


        }
        if (isset($params2['id'])) {
            unset($params2['id']);
        }
        if (isset($params2['link'])) {
            unset($params2['link']);
        }

        if ($include_first_set != false) {
            $q = get_content("id=" . $include_first_set);

        } else {
            $q = get_content($params2);

        }

        $result = $q;

        if (is_array($result) and !empty($result)) {
            $nest_level++;
            if (trim($list_tag) != '') {
                if ($ul_class_name == false) {
                    print "<{$list_tag} class='pages_tree depth-{$nest_level}'>";
                } else {
                    print "<{$list_tag} class='{$ul_class_name} depth-{$nest_level}'>";
                }
            }
            $res_count = 0;
            foreach ($result as $item) {
                $skip_me_cause_iam_removed = false;
                if (is_array($remove_ids) == true) {

                    if (in_array($item['id'], $remove_ids)) {

                        $skip_me_cause_iam_removed = true;
                    }
                }

                if ($skip_me_cause_iam_removed == false) {

                    $output = $output . $item['title'];

                    $content_type_li_class = false;

                    switch ($item ['subtype']) {

                        case 'dynamic' :
                            $content_type_li_class = 'have_category';

                            break;

                        case 'module' :
                            $content_type_li_class = 'is_module';

                            break;

                        default :
                            $content_type_li_class = 'is_page';

                            break;
                    }

                    if ($item['is_home'] != 'y') {

                    } else {

                        $content_type_li_class .= ' is_home';
                    }
                    $st_str = '';
                    $st_str2 = '';
                    $st_str3 = '';
                    if (isset($item['subtype']) and trim($item['subtype']) != '') {
                        $st_str = " data-subtype='{$item['subtype']}' ";
                    }

                    if (isset($item['subtype_value']) and trim($item['subtype_value']) != '') {
                        $st_str2 = " data-subtype-value='{$item['subtype_value']}' ";
                    }

                    if (isset($item['is_shop']) and trim($item['is_shop']) == 'y') {
                        $st_str3 = " data-is-shop=true ";
                        $content_type_li_class .= ' is_shop';
                    }
                    $iid = $item['id'];


                    $to_pr_2 = "<{$list_item_tag} class='{$li_class} $content_type_li_class {active_class} {active_parent_class} depth-{$nest_level} item_{$iid} {exteded_classes}' data-page-id='{$item['id']}' value='{$item['id']}'  data-item-id='{$item['id']}'  {active_code_tag} data-parent-page-id='{$item['parent']}' {$st_str} {$st_str2} {$st_str3}  title='" . addslashes($item['title']) . "' >";

                    if ($link != false) {


                        $active_parent_class = '';
                        //if(isset($item['parent']) and intval($item['parent']) != 0){
                        if (intval($item['parent']) != 0 and intval($item['parent']) == intval(MAIN_PAGE_ID)) {
                            $active_parent_class = 'active-parent';
                        } elseif (intval($item['id']) == intval(MAIN_PAGE_ID)) {
                            $active_parent_class = 'active-parent';
                        } else {
                            $active_parent_class = '';
                        }

                        //}
                        if ($item['id'] == CONTENT_ID) {
                            $active_class = 'active';
                        } elseif ($item['id'] == PAGE_ID) {
                            $active_class = 'active';
                        } elseif ($item['id'] == POST_ID) {
                            $active_class = 'active';
                        } elseif (CATEGORY_ID != false and intval($item['subtype_value']) != 0 and $item['subtype_value'] == CATEGORY_ID) {
                            $active_class = 'active';
                        } else {
                            $active_class = '';
                        }


                        $ext_classes = '';
                        if ($res_count == 0) {
                            $ext_classes .= ' first-child ';
                            $ext_classes .= ' child-' . $res_count . '';
                        } else if (!isset($result[$res_count + 1])) {
                            $ext_classes .= ' last-child';
                            $ext_classes .= ' child-' . $res_count . '';
                        } else {
                            $ext_classes .= ' child-' . $res_count . '';
                        }

                        if (isset($item['parent']) and intval($item['parent']) > 0) {
                            $ext_classes .= ' have-parent';
                        }


                        if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                            $ext_classes .= ' have-category';
                        }

                        if (isset($item['is_active']) and $item['is_active'] == 'n') {

                            $ext_classes = $ext_classes . ' content-unpublished ';
                        }

                        $ext_classes = trim($ext_classes);
                        $the_active_class = $active_class;


                        $to_print = str_replace('{id}', $item['id'], $link);
                        $to_print = str_replace('{active_class}', $active_class, $to_print);
                        $to_print = str_replace('{active_parent_class}', $active_parent_class, $to_print);
                        $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);
                        $to_pr_2 = str_replace('{exteded_classes}', $ext_classes, $to_pr_2);
                        $to_pr_2 = str_replace('{active_class}', $active_class, $to_pr_2);
                        $to_pr_2 = str_replace('{active_parent_class}', $active_parent_class, $to_pr_2);


                        $to_print = str_replace('{title}', $item['title'], $to_print);

                        $to_print = str_replace('{nest_level}', 'depth-' . $nest_level, $to_print);
                        if (strstr($to_print, '{link}')) {
                            $to_print = str_replace('{link}', page_link($item['id']), $to_print);
                        }
                        $empty1 = intval($nest_level);
                        $empty = '';
                        for ($i1 = 0; $i1 < $empty1; $i1++) {
                            $empty = $empty . '&nbsp;&nbsp;';
                        }
                        $to_print = str_replace('{empty}', $empty, $to_print);


                        if (strstr($to_print, '{tn}')) {
                            $to_print = str_replace('{tn}', thumbnail($item['id'], 'original'), $to_print);
                        }
                        foreach ($item as $item_k => $item_v) {
                            $to_print = str_replace('{' . $item_k . '}', $item_v, $to_print);
                        }
                        $res_count++;
                        if (is_array($active_ids) == true) {

                            $is_there_active_ids = false;

                            foreach ($active_ids as $active_id) {

                                if (intval($item['id']) == intval($active_id)) {

                                    $is_there_active_ids = true;

                                    $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                    $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                    $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                    $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                }
                            }

                            if ($is_there_active_ids == false) {

                                $to_print = str_ireplace('{active_code}', '', $to_print);
                                $to_print = str_ireplace('{active_class}', '', $to_print);
                                $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                                $to_pr_2 = str_ireplace('{active_code_tag}', '', $to_pr_2);
                            }
                        } else {

                            $to_print = str_ireplace('{active_code}', '', $to_print);
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{active_code_tag}', '', $to_pr_2);
                        }
                        $to_print = str_replace('{exteded_classes}', '', $to_print);

                        if (is_array($remove_ids) == true) {

                            if (in_array($item['id'], $remove_ids)) {

                                if ($removed_ids_code == false) {

                                    $to_print = false;
                                } else {
                                    $remove_ids[] = $item['id'];
                                    $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                                    //$to_pr_2 = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_pr_2);
                                }
                            } else {

                                $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
                                //$to_pr_2 = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_pr_2);
                            }
                        }
                        $to_pr_2 = str_replace('{active_class}', '', $to_pr_2);
                        $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);

                        print $to_pr_2;
                        $to_pr_2 = false;
                        print $to_print;
                    } else {
                        $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                        $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);
                        $to_pr_2 = str_replace('{active_parent_class}', '', $to_pr_2);


                        print $to_pr_2;
                        $to_pr_2 = false;
                        print $item['title'];
                    }

                    if (is_array($params)) {
                        $params['parent'] = $item['id'];
                        if ($max_level != false) {
                            $params['max_level'] = $max_level;
                        }
                        if (isset($params['is_shop'])) {
                            unset($params['is_shop']);
                        }

                        //   $nest_level++;
                        $params['nest_level'] = $nest_level;
                        $params['ul_class_name'] = false;
                        $params['ul_class'] = false;

                        if (isset($params['ul_class_deep'])) {
                            $params['ul_class'] = $params['ul_class_deep'];
                        }

                        if (isset($maxdepth)) {
                            $params['maxdepth'] = $maxdepth;
                        }


                        if (isset($params['li_class_deep'])) {
                            $params['li_class'] = $params['li_class_deep'];
                        }

                        if (isset($params['return_data'])) {
                            unset($params['return_data']);
                        }

                        if ($skip_pages_from_tree == false) {

                            $children = self::pages_tree($params);
                        }
                    } else {
                        if ($skip_pages_from_tree == false) {

                            $children = self::pages_tree(intval($item['id']), $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name = false);
                        }
                    }

                    if (isset($include_categories) and $include_categories == true) {

                        $content_cats = array();
                        if (isset($item['subtype_value']) and intval($item['subtype_value']) == true) {

                        }


                        $cat_params = array();
                        if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                            //$cat_params['subtype_value'] = $item['subtype_value'];
                        }
                        //$cat_params['try_rel_id'] = $item['id'];

                        if (isset($categores_link)) {
                            $cat_params['link'] = $categores_link;

                        } else {
                            $cat_params['link'] = $link;
                        }

                        if (isset($categories_active_ids)) {
                            $cat_params['active_ids'] = $categories_active_ids;

                        }

                        if (isset($active_code)) {
                            $cat_params['active_code'] = $active_code;

                        }


                        //$cat_params['for'] = 'content';
                        $cat_params['list_tag'] = $list_tag;
                        $cat_params['list_item_tag'] = $list_item_tag;
                        $cat_params['rel'] = 'content';
                        $cat_params['rel_id'] = $item['id'];

                        $cat_params['include_first'] = 1;
                        $cat_params['nest_level'] = $nest_level;
                        if ($max_level != false) {
                            $cat_params['max_level'] = $max_level;
                        }


                        if ($nest_level > 1) {
                            if (isset($params['ul_class_deep'])) {
                                $cat_params['ul_class'] = $params['ul_class_deep'];
                            }


                            if (isset($params['li_class_deep'])) {
                                $cat_params['li_class'] = $params['li_class_deep'];
                            }

                        } else {


                            if (isset($params['ul_class'])) {
                                $cat_params['ul_class'] = $params['ul_class'];
                            }


                            if (isset($params['li_class'])) {
                                $cat_params['li_class'] = $params['li_class'];
                            }


                        }

                        if (isset($debug)) {

                        }
                        //d($cat_params);
                        //d($cat_params);
                        category_tree($cat_params);

                    }
                }
                print "</{$list_item_tag}>";
            }


            if (trim($list_tag) != '') {
                print "</{$list_tag}>";
            }
        } else {

        }

        $content = ob_get_contents();
//	if (!isset($_GET['debug'])) {
        cache_save($content, $function_cache_id, $cache_group);
        //}
        ob_end_clean();

        if (isset($params['return_data'])) {
            return $content;
        } else {
            print $content;
        }


        return false;
    }

    /**
     * Creates the content tables in the database.
     *
     * It is executed on install and on update
     *
     * @function mw_db_init_content_table
     * @category Content
     * @package Content
     * @subpackage  Advanced
     * @uses set_db_table()
     */
    static function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_content = cache_get_content($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MW_DB_TABLE_CONTENT;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('expires_on', 'datetime default NULL');

        $fields_to_add[] = array('created_by', 'int(11) default NULL');

        $fields_to_add[] = array('edited_by', 'int(11) default NULL');


        $fields_to_add[] = array('content_type', 'TEXT default NULL');
        $fields_to_add[] = array('url', 'longtext default NULL');
        $fields_to_add[] = array('content_filename', 'TEXT default NULL');
        $fields_to_add[] = array('title', 'longtext default NULL');
        $fields_to_add[] = array('parent', 'int(11) default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('content_meta_title', 'TEXT default NULL');

        $fields_to_add[] = array('content_meta_keywords', 'TEXT default NULL');
        $fields_to_add[] = array('position', 'int(11) default 1');

        $fields_to_add[] = array('content', 'TEXT default NULL');

        $fields_to_add[] = array('is_active', "char(1) default 'y'");
        $fields_to_add[] = array('is_home', "char(1) default 'n'");
        $fields_to_add[] = array('is_pinged', "char(1) default 'n'");
        $fields_to_add[] = array('is_shop', "char(1) default 'n'");
        $fields_to_add[] = array('is_deleted', "char(1) default 'n'");
        $fields_to_add[] = array('draft_of', 'int(11) default NULL');

        $fields_to_add[] = array('require_login', "char(1) default 'n'");


        $fields_to_add[] = array('subtype', 'TEXT default NULL');
        $fields_to_add[] = array('subtype_value', 'TEXT default NULL');
        $fields_to_add[] = array('original_link', 'TEXT default NULL');
        $fields_to_add[] = array('layout_file', 'TEXT default NULL');
        $fields_to_add[] = array('layout_name', 'TEXT default NULL');
        $fields_to_add[] = array('layout_style', 'TEXT default NULL');
        $fields_to_add[] = array('active_site_template', 'TEXT default NULL');
        $fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
        set_db_table($table_name, $fields_to_add);


        db_add_table_index('url', $table_name, array('url(255)'));
        db_add_table_index('title', $table_name, array('title(255)'));


        $table_name = MW_DB_TABLE_CONTENT_FIELDS;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('rel', 'TEXT default NULL');

        $fields_to_add[] = array('rel_id', 'TEXT default NULL');
        $fields_to_add[] = array('field', 'longtext default NULL');
        $fields_to_add[] = array('value', 'TEXT default NULL');
        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(255)'));
        //db_add_table_index('field', $table_name, array('field(55)'));

        $table_name = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;
        $fields_to_add[] = array('session_id', 'varchar(50) DEFAULT NULL');
        $fields_to_add[] = array('is_temp', "char(1) default 'y'");
        $fields_to_add[] = array('url', 'TEXT default NULL');


        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(255)'));
        //db_add_table_index('field', $table_name, array('field(56)'));


        $table_name = MW_DB_TABLE_MEDIA;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('session_id', 'varchar(50) DEFAULT NULL');
        $fields_to_add[] = array('rel', 'TEXT default NULL');

        $fields_to_add[] = array('rel_id', 'TEXT default NULL');
        $fields_to_add[] = array('media_type', 'TEXT default NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('title', 'longtext default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('embed_code', 'TEXT default NULL');
        $fields_to_add[] = array('filename', 'TEXT default NULL');


        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(255)'));
        db_add_table_index('media_type', $table_name, array('media_type(55)'));

        //db_add_table_index('url', $table_name, array('url'));
        //db_add_table_index('title', $table_name, array('title'));


        $table_name = MW_DB_TABLE_CUSTOM_FIELDS;

        $fields_to_add = array();
        $fields_to_add[] = array('rel', 'TEXT default NULL');

        $fields_to_add[] = array('rel_id', 'TEXT default NULL');
        $fields_to_add[] = array('session_id', 'varchar(50) DEFAULT NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');


        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');

        $fields_to_add[] = array('custom_field_name', 'TEXT default NULL');
        $fields_to_add[] = array('custom_field_name_plain', 'longtext default NULL');


        $fields_to_add[] = array('custom_field_value', 'TEXT default NULL');


        $fields_to_add[] = array('custom_field_type', 'TEXT default NULL');
        $fields_to_add[] = array('custom_field_values', 'longtext default NULL');
        $fields_to_add[] = array('custom_field_values_plain', 'longtext default NULL');

        $fields_to_add[] = array('field_for', 'TEXT default NULL');
        $fields_to_add[] = array('custom_field_field_for', 'TEXT default NULL');
        $fields_to_add[] = array('custom_field_help_text', 'TEXT default NULL');
        $fields_to_add[] = array('options', 'TEXT default NULL');


        $fields_to_add[] = array('custom_field_is_active', "char(1) default 'y'");
        $fields_to_add[] = array('custom_field_required', "char(1) default 'n'");
        $fields_to_add[] = array('copy_of_field', 'int(11) default NULL');


        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(55)'));
        db_add_table_index('custom_field_type', $table_name, array('custom_field_type(55)'));


        $table_name = MW_DB_TABLE_MENUS;

        $fields_to_add = array();
        $fields_to_add[] = array('title', 'TEXT default NULL');
        $fields_to_add[] = array('item_type', 'varchar(33) default NULL');
        $fields_to_add[] = array('parent_id', 'int(11) default NULL');
        $fields_to_add[] = array('content_id', 'int(11) default NULL');
        $fields_to_add[] = array('categories_id', 'int(11) default NULL');
        $fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('is_active', "char(1) default 'y'");
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('url', 'TEXT default NULL');
        set_db_table($table_name, $fields_to_add);


        cache_save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }


}

$mw_skip_pages_starting_with_url = array('admin', 'api', 'module'); //its set in the funk bellow
$mw_precahced_links = array();
$mw_global_content_memory = array();