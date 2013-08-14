<?php
namespace Microweber;

//event_bind('mw_db_init', mw('Microweber\Content')->db_init());

/**
 * This file holds useful functions to work with content
 * Here you will find functions to get and save content in the database and much more.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */


class Content
{
    public $app;

    function __construct($app = null)
    {

        if (!defined("MW_DB_TABLE_CONTENT")) {
            define('MW_DB_TABLE_CONTENT', MW_TABLE_PREFIX . 'content');
        }

        if (!defined("MW_DB_TABLE_CONTENT_FIELDS")) {
            define('MW_DB_TABLE_CONTENT_FIELDS', MW_TABLE_PREFIX . 'content_fields');
        }

        if (!defined("MW_DB_TABLE_CONTENT_FIELDS_DRAFTS")) {
            define('MW_DB_TABLE_CONTENT_FIELDS_DRAFTS', MW_TABLE_PREFIX . 'content_fields_drafts');
        }

        if (!defined("MW_DB_TABLE_MEDIA")) {
            define('MW_DB_TABLE_MEDIA', MW_TABLE_PREFIX . 'media');
        }

        if (!defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            define('MW_DB_TABLE_CUSTOM_FIELDS', MW_TABLE_PREFIX . 'custom_fields');
        }
        if (!defined("MW_DB_TABLE_MENUS")) {
            define('MW_DB_TABLE_MENUS', MW_TABLE_PREFIX . 'menus');
        }
        if (!defined("MODULE_DB_MENUS")) {
            define('MODULE_DB_MENUS', MW_TABLE_PREFIX . 'menus');
        }

        if (!defined("MW_DB_TABLE_TAXONOMY")) {
            define('MW_DB_TABLE_TAXONOMY', MW_TABLE_PREFIX . 'categories');
        }

        if (!defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            define('MW_DB_TABLE_TAXONOMY_ITEMS', MW_TABLE_PREFIX . 'categories_items');
        }


        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw('application');
            }

        }


        $this->db_init();

    }

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
     * print $this->link($id=1);
     * </code>
     *
     */
    public function link($id = 0)
    {
        if (is_string($id)) {
            // $link = page_link_to_layout ( $id );
        }

        if (is_array($id)) {
            extract($id);
        }


        if ($id == false or $id == 0) {
            if (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }


        if ($id == 0) {
            return $this->app->url->site();
        }

        $link = $this->get_by_id($id);


        if (strval($link['url']) == '') {
            $link = $this->get_by_url($id);
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
     *| Field Name          | Description               | Values
     *|------------------------------------------------------------------------------
     *| id                  | the id of the content     |
     *| is_active           | published or unpublished  | "y" or "n"
     *| parent              | get content with parent   | any id or 0
     *| created_by          | get by author id          | any user id
     *| created_on          | the date of creation      |
     *| updated_on          | the date of last edit     |
     *| content_type        | the type of the content   | "page" or "post", anything custom
     *| subtype             | subtype of the content    | "static","dynamic","post","product", anything custom
     *| url                 | the link to the content   |
     *| title               | Title of the content      |
     *| content             | The html content saved in the database |
     *| description         | Description used for the content list |
     *| position            | The order position        |
     *| active_site_template   | Current template for the content |
     *| layout_file         | Current layout from the template directory |
     *| is_deleted          | flag for deleted content  |  "n" or "y"
     *| is_home             | flag for homepage         |  "n" or "y"
     *| is_shop             | flag for shop page        |  "n" or "y"
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
     * $data = $this->app->content->get($params);
     * var_dump($data);
     *
     * </code>
     *
     * @example
     * #### Get by params as string
     * <code>
     *  $data = $this->app->content->get('is_active=y');
     *  var_dump($data);
     * </code>
     *
     * @example
     * #### Ordering and sorting
     * <code>
     *  //Order by position
     *  $data = $this->app->content->get('content_type=post&is_active=y&order_by=position desc');
     *  var_dump($data);
     *
     *  //Order by date
     *  $data = $this->app->content->get('content_type=post&is_active=y&order_by=updated_on desc');
     *  var_dump($data);
     *
     *  //Order by title
     *  $data = $this->app->content->get('content_type=post&is_active=y&order_by=title asc');
     *  var_dump($data);
     *
     *  //Get content from last week
     *  $data = $this->app->content->get('created_on=[mt]-1 week&is_active=y&order_by=title asc');
     *  var_dump($data);
     * </code>
     *
     */
    public function get($params = false)
    {

        if (defined('PAGE_ID') == false) {
            $this->define_constants();
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


        $cache_group = 'content/global';
        if (isset($params['cache_group'])) {
            $cache_group = $params['cache_group'];
        }
        $table = MW_DB_TABLE_CONTENT;
        if (!isset($params['is_deleted'])) {
            $params['is_deleted'] = 'n';
        }
        $params['table'] = $table;
        $params['cache_group'] = $cache_group;

        $get = $this->app->db->get($params);


        if (isset($params['count']) or isset($params['single']) or isset($params['one'])  or isset($params['data-count']) or isset($params['page_count']) or isset($params['data-page-count'])) {
            if (isset($get['url'])) {
                $get['url'] = $this->app->url->site($get['url']);
            }
            if (isset($get['title'])) {
                $get['title'] = $this->app->format->clean_html($get['title']);
            }
            return $get;
        }
        if (is_array($get)) {
            $data2 = array();
            foreach ($get as $item) {
                if (isset($item['url'])) {
                    $item['url'] = $this->app->url->site($item['url']);
                }
                if (isset($item['title'])) {
                    $item['title'] = $this->app->format->clean_html($item['title']);
                }

                $data2[] = $item;
            }
            $get = $data2;

            return $get;
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
     * $page = $this->get_page(1);
     * var_dump($page);
     * </pre>
     * @example
     * <pre>
     * Get by url
     *
     * $page = $this->get_page('home');
     * var_dump($page);
     *</pre>
     */
    public function get_page($id = 0)
    {
        if ($id == false or $id == 0) {
            return false;
        }

        // $CI = get_instance ();
        if (intval($id) != 0) {
            $page = $this->get_by_id($id);

            if (empty($page)) {
                $page = $this->get_by_url($id);
            }
        } else {
            if (empty($page)) {
                $page = array();
                $page['layout_name'] = trim($id);

                $page = $this->app->content->get($page);
                $page = $page[0];
            }
        }

        return $page;

        // $link = get_instance()->content_model->getContentURLByIdAndCache (
        // $link['id'] );
    }


    /**
     * Return the path to the layout file that will render the page
     *
     * It accepts array $page that must have  $page['id'] set
     *
     * @example
     * <code>
     *  //get the layout file for content
     *  $content = $this->get_by_id($id=1);
     *  $render_file = get_layout_for_page($content);
     *  var_dump($render_file ); //print full path to the layout file ex. /home/user/public_html/userfiles/templates/default/index.php
     * </code>
     * @package Content
     * @subpackage Advanced
     */
    public function get_layout($page = array())
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
        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            if (isset($page['id'])) {
                $this->define_constants($page);
            }
        }
        $cache_content = $this->app->cache->get($cache_id, $cache_group);

        if (($cache_content) != false) {

             return $cache_content;
        }

        $render_file = false;
        $look_for_post = false;
        $template_view_set_inner = false;




        if (isset($page['active_site_template']) and isset($page['active_site_template']) and isset($page['layout_file']) and $page['layout_file'] != 'inherit'  and $page['layout_file'] != '') {
            $test_file = str_replace('___', DS, $page['layout_file']);
            $render_file_temp = TEMPLATES_DIR . $page['active_site_template'] . DS . $test_file;

            if (is_file($render_file_temp)) {
                $render_file = $render_file_temp;
            }
        }
        if ($render_file == false and isset($page['id']) and intval($page['id']) == 0) {
            $url_file = $this->app->url->string(1, 1);
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

            $inherit_from = $this->get_parents($page['id']);

            $found = 0;
            if (!empty($inherit_from)) {
                foreach ($inherit_from as $value) {
                    if ($found == 0 and $value != $page['id']) {
                        $par_c = $this->get_by_id($value);
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

                $par_page = $this->get_by_id($page['parent']);

                if (is_array($par_page)) {
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


        if ($render_file == false and isset($page['active_site_template']) and trim($page['layout_file']) == '') {

            $use_index = TEMPLATES_DIR . $page['active_site_template'] . DS . 'index.php';

            if (is_file($use_index)) {


                $render_file = $use_index;
            }
        }

        if ($render_file == false and isset($page['active_site_template']) and isset($page['content_type']) and $render_file == false and !isset($page['layout_file'])) {
            $layouts_list = $this->app->layouts->scan('site_template=' . $page['active_site_template']);

            if (is_array($layouts_list)) {
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
        if ($render_file == false and isset($page['layout_file']) and ($page['layout_file']) != false) {
            $template_view = ACTIVE_TEMPLATE_DIR . DS . $page['layout_file'];
            $template_view = normalize_path($template_view, false);

            if (is_file($template_view) == true) {
                $render_file = $template_view;
            } else {

            }
        }
        $this->app->cache->save($render_file, $cache_id, $cache_group);

        return $render_file;
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
     * $content = $this->get_by_id(1);
     * var_dump($content);
     * </pre>
     *
     */
    public function get_by_id($id)
    {
        global $mw_global_content_memory;
        if (!is_array($mw_global_content_memory)) {
            $mw_global_content_memory = array();
        }
        if (!is_array($id)) {
            if (isset($mw_global_content_memory[$id])) {
                return $mw_global_content_memory[$id];
            }
        }

        // ->'content';
        $table = MW_DB_TABLE_CONTENT;

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


        $q = $this->app->db->get($params);

        //  $q = $this->app->db->get_long($table, $params, $cache_group = 'content/' . $id);
        //  $q = $this->app->db->query($q, __FUNCTION__ . crc32($q), 'content/' . $id);
        if (isset($q[0])) {
            $content = $q[0];
            if (isset($content['title'])) {
                $content['title'] = $this->app->format->clean_html($content['title']);
            }
        } else {
            if (!is_array($id)) {
            $mw_global_content_memory[$id] = false;
            }
            return false;
        }
        if (!is_array($id)) {
        $mw_global_content_memory[$id] = $content;
        }
        return $content;
    }


    public function get_by_url($url = '', $no_recursive = false)
    {
        if (strval($url) == '') {

            $url = $this->app->url->string();
        }

        $u1 = $url;
        $u2 = $this->app->url->site();

        $u1 = rtrim($u1, '\\');
        $u1 = rtrim($u1, '/');

        $u2 = rtrim($u2, '\\');
        $u2 = rtrim($u2, '/');
        $u1 = str_replace($u2, '', $u1);
        $u1 = ltrim($u1, '/');
        $url = $u1;
        $table = MW_DB_TABLE_CONTENT;

        $url = $this->app->db->escape_string($url);
        $url = addslashes($url);

        $url12 = parse_url($url);
        if (isset($url12['scheme']) and isset($url12['host']) and isset($url12['path'])) {

            $u1 = $this->app->url->site();
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
        if (is_array($mw_skip_pages_starting_with_url)) {
            $segs = explode('/', $url);

            foreach ($mw_skip_pages_starting_with_url as $skip_page_url) {
                if (in_array($skip_page_url, $segs)) {
                    return false;
                }

            }

        }


        global $mw_precached_links;


        $link_hash = 'link' . crc32($url);

        if (isset($mw_precached_links[$link_hash])) {
            return $mw_precached_links[$link_hash];
        }


        $sql = "SELECT id FROM $table WHERE url='{$url}'   ORDER BY updated_on DESC LIMIT 0,1 ";

        $q = $this->app->db->query($sql, __FUNCTION__ . crc32($sql), 'content/global');

        $result = $q;

        $content = $result[0];

        if (!empty($content)) {

            $mw_precached_links[$link_hash] = $content;
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
                        $url = $this->get_by_url($test[0], true);
                    }
                    if (!empty($url)) {
                        $mw_precached_links[$link_hash] = $url;
                        return $url;
                    }


                }
            }
        } else {

            if (isset($content['id']) and intval($content['id']) != 0) {
                $content['id'] = ((int)$content['id']);
            }
            //$get_by_id = $this->get_by_id($content['id']);
            $mw_precached_links[$link_hash] = $content;
            return $content;
        }
        $mw_precached_links[$link_hash] = false;
        return false;
    }


    public function get_parents($id = 0, $without_main_parrent = false)
    {

        if (intval($id) == 0) {

            return FALSE;
        }

        $table = MW_DB_TABLE_CONTENT;

        $ids = array();

        $data = array();

        if (isset($without_main_parrent) and $without_main_parrent == true) {

            $with_main_parrent_q = " and parent<>0 ";
        } else {

            $with_main_parrent_q = false;
        }
        $id = intval($id);
        $q = " SELECT id, parent FROM $table WHERE id ={$id} " . $with_main_parrent_q;

        $taxonomies = $this->app->db->query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'content/' . $id);

        //var_dump($q);
        //  var_dump($taxonomies);
        //  exit;

        if (!empty($taxonomies)) {

            foreach ($taxonomies as $item) {

                if (intval($item['id']) != 0) {

                    $ids[] = $item['parent'];
                }
                if ($item['parent'] != $item['id'] and intval($item['parent'] != 0)) {
                    $next = $this->get_parents($item['parent'], $without_main_parrent);

                    if (!empty($next)) {

                        foreach ($next as $n) {

                            if ($n != '' and $n != 0) {

                                $ids[] = $n;
                            }
                        }
                    }
                }
            }
        }

        if (!empty($ids)) {

            $ids = array_unique($ids);

            return $ids;
        } else {

            return false;
        }
    }

    public function custom_fields($content_id, $full = true, $field_type = false)
    {

        return $this->app->fields->get('content', $content_id, $full, false, false, $field_type);


    }


    public function edit_field($data, $debug = false)
    {


        $table = MW_DB_TABLE_CONTENT_FIELDS;

        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (!is_array($data)) {
            $data = array();
        }


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
            mw_error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }

        if ((isset($data['rel']) and isset($data['rel_id']))) {

            $data['cache_group'] = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
        } else {
            $data['cache_group'] = guess_cache_group('content_fields/global');

        }
        if (!isset($data['all'])) {
            $data['one'] = 1;
            $data['limit'] = 1000;
        }

        $data['table'] = $table;

        $get = $this->app->db->get($data);


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
    public function paging($params)
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
        $curent_page_from_url = $this->app->url->param($paging_param);

        if (isset($params['curent_page'])) {
            $curent_page_from_url = $params['curent_page'];
        }

        $data = $this->paging_links($base_url, $pages_count, $paging_param, $keyword_param);
        if (is_array($data)) {
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

    public function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword')
    {


        if ($base_url == false) {

            if ($this->app->url->is_ajax() == false) {
                $base_url = $this->app->url->current(1);

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


            $new = array();

            foreach ($the_url as $itm) {

                $itm = explode(':', $itm);

                if ($itm[0] == $paging_param) {

                    $itm[1] = $x;
                }

                $new[] = implode(':', $itm);
            }

            $new_url = implode('/', $new);


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
     *  $ref_page = $this->get_by_id(1);
     *  $this->define_constants($ref_page);
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
    public function define_constants($content = false)
    {

        if ($content == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $ref_page = $_SERVER['HTTP_REFERER'];
                if ($ref_page != '') {
                    $ref_page = $this->get_by_url($ref_page);
                    if (!empty($ref_page)) {
                        $content = $ref_page;

                    }
                }
            }
        }
//
        if (is_array($content)) {
            if (isset($content['id']) and $content['id'] != 0) {
                $content = $this->get_by_id($content['id']);
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




                $current_categorys = get_categories_for_content($page['id']);
                if(!empty($current_categorys)){
                    $current_category = array_shift($current_categorys);
                    if (defined('CATEGORY_ID') == false and isset($current_category['id'])) {
                        define('CATEGORY_ID', $current_category['id']);
                    }
                }

                $page = $this->get_by_id($page['parent']);
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
            $the_active_site_template = $this->app->option->get('curent_template','template');
            //
        }

        if($the_active_site_template == false){
            $the_active_site_template = 'default';
        }


        if (defined('THIS_TEMPLATE_DIR') == false and $the_active_site_template != false) {

            define('THIS_TEMPLATE_DIR', MW_TEMPLATES_DIR . $the_active_site_template . DS);

        }
        $the_active_site_template_dir = normalize_path(MW_TEMPLATES_DIR . $the_active_site_template . DS);

        if (defined('DEFAULT_TEMPLATE_DIR') == false) {

            define('DEFAULT_TEMPLATE_DIR', MW_TEMPLATES_DIR . 'default' . DS);
        }

        if (defined('DEFAULT_TEMPLATE_URL') == false) {

            define('DEFAULT_TEMPLATE_URL', MW_USERFILES_URL . '/' . MW_TEMPLATES_FOLDER_NAME . '/default/');
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
                            $par_page = $this->get_inherited_parent($page['id']);
                            if ($par_page != false) {
                                $par_page = $this->get_by_id($par_page);
                            }
                            if (isset($par_page['layout_file'])) {
                                $the_active_site_template = $par_page['active_site_template'];
                                $page['layout_file'] = $par_page['layout_file'];
                                $page['active_site_template'] = $par_page['active_site_template'];
                                $template_view = MW_TEMPLATES_DIR . $page['active_site_template'] . DS . $page['layout_file'];


                            }

                        }
                    }

                    if (is_file($template_view) == true) {

                        if (defined('THIS_TEMPLATE_DIR') == false) {

                            define('THIS_TEMPLATE_DIR', MW_TEMPLATES_DIR . $the_active_site_template . DS);

                        }
                        if (defined('THIS_TEMPLATE_URL') == false) {
                            $the_template_url = MW_USERFILES_URL . '/' . MW_TEMPLATES_FOLDER_NAME . '/' . $the_active_site_template;

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

        }

        if (defined('ACTIVE_TEMPLATE_DIR') == false) {

            define('ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_DIR') == false) {

            define('THIS_TEMPLATE_DIR', $the_active_site_template_dir);
        }

        if (defined('THIS_TEMPLATE_URL') == false) {
            $the_template_url = MW_USERFILES_URL . '/' . MW_TEMPLATES_FOLDER_NAME . '/' . $the_active_site_template;

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

            define('TEMPLATES_DIR', MW_TEMPLATES_DIR);
        }

        $the_template_url = MW_USERFILES_URL . '/' . MW_TEMPLATES_FOLDER_NAME . '/' . $the_active_site_template;

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

            $layouts_url = reduce_double_slashes($this->app->url->link_to_file($layouts_dir) . '/');

            define("LAYOUTS_URL", $layouts_url);
        }


        return true;
    }


    /**
     * Returns the homepage as array
     *
     * @category Content
     * @package Content
     */
    public function homepage()
    {

        // ->'content';
        $table = MW_DB_TABLE_CONTENT;


        $sql = "SELECT * FROM $table WHERE is_home='y'  ORDER BY updated_on DESC LIMIT 0,1 ";

        $q = $this->app->db->query($sql, __FUNCTION__ . crc32($sql), 'content/global');
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
    public function pages_tree($parent = 0, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false)
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
            $this->define_constants();
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

        $cache_content = $this->app->cache->get($function_cache_id, $cache_group);
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
            $is_shop = $this->app->db->escape_string($params['is_shop']);
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


        $table = MW_DB_TABLE_CONTENT;
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

        //$q = $this->app->db->query($sql, $cid, $cidg);
        if (!is_array($params)) {
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
            $q = $this->app->content->get("id=" . $include_first_set);

        } else {
            $q = $this->app->content->get($params2);

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

                            $children = $this->pages_tree($params);
                        }
                    } else {
                        if ($skip_pages_from_tree == false) {

                            $children = $this->pages_tree(intval($item['id']), $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name = false);
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
                        $this->app->category->tree($cat_params);

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
        $this->app->cache->save($content, $function_cache_id, $cache_group);
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
     *  Get the first parent that has layout
     *
     * @category Content
     * @package Content
     * @subpackage Advanced
     * @uses $this->get_parents()
     * @uses $this->get_by_id()
     */
    public function get_inherited_parent($content_id)
    {


        $inherit_from = $this->get_parents($content_id);

        $found = 0;
        if (!empty($inherit_from)) {
            foreach ($inherit_from as $value) {
                if ($found == 0) {
                    $par_c = $this->get_by_id($value);
                    if (isset($par_c['id']) and isset($par_c['active_site_template']) and isset($par_c['layout_file']) and $par_c['layout_file'] != 'inherit') {
                        return $par_c['id'];
                        $found = 1;
                    }
                }
            }
        }

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
     * @uses \mw('Microweber\DbUtils')->build_table()
     */
    public function db_init()
    {

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'content_' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

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
        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);


        \mw('Microweber\DbUtils')->add_table_index('url', $table_name, array('url(255)'));
        \mw('Microweber\DbUtils')->add_table_index('title', $table_name, array('title(255)'));


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
        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        //\mw('Microweber\DbUtils')->add_table_index('field', $table_name, array('field(55)'));

        $table_name = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;
        $fields_to_add[] = array('session_id', 'varchar(50) DEFAULT NULL');
        $fields_to_add[] = array('is_temp', "char(1) default 'y'");
        $fields_to_add[] = array('url', 'TEXT default NULL');


        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        //\mw('Microweber\DbUtils')->add_table_index('field', $table_name, array('field(56)'));


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


        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        \mw('Microweber\DbUtils')->add_table_index('media_type', $table_name, array('media_type(55)'));

        //\mw('Microweber\DbUtils')->add_table_index('url', $table_name, array('url'));
        //\mw('Microweber\DbUtils')->add_table_index('title', $table_name, array('title'));


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


        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id(55)'));
        \mw('Microweber\DbUtils')->add_table_index('custom_field_type', $table_name, array('custom_field_type(55)'));


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
        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);


        $table_name = MW_DB_TABLE_TAXONOMY;

        $fields_to_add = array();

        $fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('data_type', 'TEXT default NULL');
        $fields_to_add[] = array('title', 'longtext default NULL');
        $fields_to_add[] = array('parent_id', 'int(11) default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('content', 'TEXT default NULL');
        $fields_to_add[] = array('content_type', 'TEXT default NULL');
        $fields_to_add[] = array('rel', 'TEXT default NULL');

        $fields_to_add[] = array('rel_id', 'int(11) default NULL');

        $fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('is_deleted', "char(1) default 'n'");
        $fields_to_add[] = array('users_can_create_subcategories', "char(1) default 'n'");
        $fields_to_add[] = array('users_can_create_content', "char(1) default 'n'");
        $fields_to_add[] = array('users_can_create_content_allowed_usergroups', 'TEXT default NULL');

        $fields_to_add[] = array('categories_content_type', 'TEXT default NULL');
        $fields_to_add[] = array('categories_silo_keywords', 'TEXT default NULL');


        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id'));
        \mw('Microweber\DbUtils')->add_table_index('parent_id', $table_name, array('parent_id'));

        $table_name = MW_DB_TABLE_TAXONOMY_ITEMS;

        $fields_to_add = array();
        $fields_to_add[] = array('parent_id', 'int(11) default NULL');
        $fields_to_add[] = array('rel', 'TEXT default NULL');

        $fields_to_add[] = array('rel_id', 'int(11) default NULL');
        $fields_to_add[] = array('content_type', 'TEXT default NULL');
        $fields_to_add[] = array('data_type', 'TEXT default NULL');

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        //\mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id'));
        \mw('Microweber\DbUtils')->add_table_index('parent_id', $table_name, array('parent_id'));

        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }


    public function get_menu_items($params = false)
    {
        $table = MODULE_DB_MENUS;
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['table'] = $table;
        $params['item_type'] = 'menu_item';
        return $this->app->db->get($params);
    }


    public function get_menu($params = false)
    {

        $table = MODULE_DB_MENUS;

        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        //$table = MODULE_DB_SHOP_ORDERS;
        $params['table'] = $table;
        $params['item_type'] = 'menu';
        //$params['debug'] = 'menu';
        $menus = $this->app->db->get($params);
        if (!empty($menus)) {
            return $menus;
        } else {
            if (isset($params['make_on_not_found']) and ($params['make_on_not_found']) == true and isset($params['title'])) {
                add_new_menu('id=0&title=' . $params['title']);
            }

        }

    }


    public function menu_tree($menu_id, $maxdepth = false)
    {

        static $passed_ids;
        if (!is_array($passed_ids)) {
            $passed_ids = array();
        }
        $menu_params = '';
        if (is_string($menu_id)) {
            $menu_params = parse_params($menu_id);
            if (is_array($menu_params)) {
                extract($menu_params);
            }
        }

        if (is_array($menu_id)) {
            $menu_params = $menu_id;
            extract($menu_id);
        }
        $params_o = $menu_params;
        $cache_group = 'menus/global';
        $function_cache_id = false;


        $params = array();
        $params['item_parent'] = $menu_id;
        // $params ['item_parent<>'] = $menu_id;
        $menu_id = intval($menu_id);
        $params_order = array();
        $params_order['position'] = 'ASC';

        $menus = MODULE_DB_MENUS;

        $sql = "SELECT * FROM {$menus}
	WHERE parent_id=$menu_id

	ORDER BY position ASC ";
        //d($sql); and item_type='menu_item'
        $menu_params = array();
        $menu_params['parent_id'] = $menu_id;
        $menu_params['table'] = $menus;
        $menu_params['orderby'] = "position ASC";

        //$q = $this->app->db->get($menu_params);
        // d($q);
        $q = $this->app->db->query($sql, __FUNCTION__ . crc32($sql), 'menus/global/' . $menu_id);

        // $data = $q;
        if (empty($q)) {

            return false;
        }
        $active_class = '';
        if (!isset($ul_class)) {
            $ul_class = 'menu';
        }

        if (!isset($li_class)) {
            $li_class = 'menu_element';
        }

        if (!isset($depth) or $depth == false) {
            $depth = 0;
        }
        if (isset($ul_class_deep)) {
            if ($depth > 0) {
                $ul_class = $ul_class_deep;
            }
        }

        if (isset($li_class_deep)) {
            if ($depth > 0) {
                $li_class = $li_class_deep;
            }
        }

        if (isset($ul_tag) == false) {
            $ul_tag = 'ul';
        }

        if (isset($li_tag) == false) {
            $li_tag = 'li';
        }

        if (isset($params['maxdepth']) != false) {
            $maxdepth = $params['maxdepth'];
        }

        if (isset($params_o['maxdepth']) != false) {
            $maxdepth = $params_o['maxdepth'];
        }


        if (!isset($link) or $link == false) {
            $link = '<a data-item-id="{id}" class="menu_element_link {active_class} {exteded_classes} {nest_level}" href="{url}">{title}</a>';
        }
        //d($link);
        // $to_print = '<ul class="menu" id="menu_item_' .$menu_id . '">';
        $to_print = '<' . $ul_tag . ' role="menu" class="{ul_class}' . ' menu_' . $menu_id . ' {exteded_classes}" >';

        $cur_depth = 0;
        $res_count = 0;
        foreach ($q as $item) {
            $full_item = $item;

            $title = '';
            $url = '';
            $is_active = true;
            if (intval($item['content_id']) > 0) {
                $cont = $this->get_by_id($item['content_id']);
                if (is_array($cont) and isset($cont['is_deleted']) and $cont['is_deleted'] == 'y') {
                    $is_active = false;
                    $cont = false;
                }


                if (is_array($cont)) {
                    $title = $cont['title'];
                    $url = $this->link($cont['id']);

                    if ($cont['is_active'] != 'y') {
                        $is_active = false;
                    }

                }
            } else if (intval($item['categories_id']) > 0) {
                $cont = $this->app->category->get_by_id($item['categories_id']);
                if (is_array($cont)) {
                    $title = $cont['title'];
                    $url = category_link($cont['id']);
                } else {
                    $this->app->db->delete_by_id($menus, $item['id']);
                    $title = false;
                    $item['title'] = false;
                }
            } else {
                $title = $item['title'];
                $url = $item['url'];
            }

            if (trim($item['url'] != '')) {
                $url = $item['url'];
            }

            if ($item['title'] == '') {
                $item['title'] = $title;
            } else {
                $title = $item['title'];
            }

            $active_class = '';
            if (trim($item['url'] != '') and intval($item['content_id']) == 0 and intval($item['categories_id']) == 0) {
                $surl = $this->app->url->site();
                $cur_url = $this->app->url->current(1);
                $item['url'] = $this->app->format->replace_once('{SITE_URL}', $surl, $item['url']);
                if ($item['url'] == $cur_url) {
                    $active_class = 'active';
                } else {
                    $active_class = '';
                }
            } else if (CONTENT_ID != 0 and $item['content_id'] == CONTENT_ID) {
                $active_class = 'active';
            } elseif (PAGE_ID != 0 and $item['content_id'] == PAGE_ID) {
                $active_class = 'active';
            } elseif (POST_ID != 0 and $item['content_id'] == POST_ID) {
                $active_class = 'active';
            } elseif (CATEGORY_ID != false and intval($item['categories_id']) != 0 and $item['categories_id'] == CATEGORY_ID) {
                $active_class = 'active';
            } else {
                $active_class = '';
            }
            if ($is_active == false) {
                $title = '';
            }
            if ($title != '') {
                $item['url'] = $url;
                //$full_item['the_url'] = page_link($full_item['content_id']);
                $to_print .= '<' . $li_tag . '  class="{li_class}' . ' ' . $active_class . ' {nest_level}" data-item-id="' . $item['id'] . '" >';

                $ext_classes = '';
                if (isset($item['parent']) and intval($item['parent']) > 0) {
                    $ext_classes .= ' have-parent';
                }

                if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                    $ext_classes .= ' have-category';
                }

                $ext_classes = trim($ext_classes);

                $menu_link = $link;
                foreach ($item as $key => $value) {
                    $menu_link = str_replace('{' . $key . '}', $value, $menu_link);
                }
                $menu_link = str_replace('{active_class}', $active_class, $menu_link);
                $to_print .= $menu_link;

                $ext_classes = '';
                if ($res_count == 0) {
                    $ext_classes .= ' first-child';
                    $ext_classes .= ' child-' . $res_count . '';
                } else if (!isset($q[$res_count + 1])) {
                    $ext_classes .= ' last-child';
                    $ext_classes .= ' child-' . $res_count . '';
                } else {
                    $ext_classes .= ' child-' . $res_count . '';
                }

                if (in_array($item['id'], $passed_ids) == false) {

                    if ($maxdepth == false) {

                        if (isset($params) and is_array($params)) {

                            $menu_params['menu_id'] = $item['id'];
                            $menu_params['link'] = $link;
                            if (isset($menu_params['item_parent'])) {
                                unset($menu_params['item_parent']);
                            }
                            if (isset($ul_class)) {
                                $menu_params['ul_class'] = $ul_class;
                            }
                            if (isset($li_class)) {
                                $menu_params['li_class'] = $li_class;
                            }

                            if (isset($maxdepth)) {
                                $menu_params['maxdepth'] = $maxdepth;
                            }

                            if (isset($li_tag)) {
                                $menu_params['li_tag'] = $li_tag;
                            }
                            if (isset($ul_tag)) {
                                $menu_params['ul_tag'] = $ul_tag;
                            }
                            if (isset($ul_class_deep)) {
                                $menu_params['ul_class_deep'] = $ul_class_deep;
                            }
                            if (isset($li_class_empty)) {
                                $menu_params['li_class_empty'] = $li_class_empty;
                            }

                            if (isset($li_class_deep)) {
                                $menu_params['li_class_deep'] = $li_class_deep;
                            }

                            if (isset($depth)) {
                                $menu_params['depth'] = $depth + 1;
                            }


                            $test1 = $this->menu_tree($menu_params);
                        } else {
                            $test1 = $this->menu_tree($item['id']);

                        }


                    } else {

                        if (($maxdepth != false) and intval($maxdepth) > 1 and ($cur_depth <= $maxdepth)) {

                            if (isset($params) and is_array($params)) {
                                $test1 = $this->menu_tree($menu_params);

                            } else {
                                $test1 = $this->menu_tree($item['id']);

                            }

                        }
                    }
                }
                if (isset($li_class_empty) and isset($test1) and trim($test1) == '') {
                    if ($depth > 0) {
                        $li_class = $li_class_empty;
                    }
                }

                $to_print = str_replace('{ul_class}', $ul_class, $to_print);
                $to_print = str_replace('{li_class}', $li_class, $to_print);
                $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);
                $to_print = str_replace('{nest_level}', 'depth-' . $depth, $to_print);

                if (isset($test1) and strval($test1) != '') {
                    $to_print .= strval($test1);
                }

                $res_count++;
                $to_print .= '</' . $li_tag . '>';

            }

            $passed_ids[] = $item['id'];

            $cur_depth++;
        }

        $to_print .= '</' . $ul_tag . '>';
        return $to_print;
    }

    public function template_header($script_src)
    {
        static $mw_template_headers;
        if ($mw_template_headers == null) {
            $mw_template_headers = array();
        }

        if (is_string($script_src)) {
            if (!in_array($script_src, $mw_template_headers)) {
                $mw_template_headers[] = $script_src;
                return $mw_template_headers;
            }
        } else if (is_bool($script_src)) {
            //   return $mw_template_headers;
            $src = '';
            if (is_array($mw_template_headers)) {
                foreach ($mw_template_headers as $header) {
                    $ext = get_file_extension($header);
                    switch (strtolower($ext)) {


                        case 'css':
                            $src .= '<link rel="stylesheet" href="' . $header . '" type="text/css" media="all">' . "\n";
                            break;

                        case 'js':
                            $src .= '<script type="text/javascript" src="' . $header . '"></script>' . "\n";
                            break;


                        default:
                            $src .= $header . "\n";
                            break;
                    }
                }
            }
            return $src;
        }
    }


    function debug_info()
    {
        //if (c('debug_mode')) {

        return include(MW_ADMIN_VIEWS_DIR . 'debug.php');
        // }
    }


    /**
     * Get the current language of the site
     *
     * @example
     * <code>
     *  $current_lang = current_lang();
     *  print $current_lang;
     * </code>
     *
     * @package Language
     * @constant  MW_LANG defines the MW_LANG constant
     */
    public function lang_current()
    {

        if (defined('MW_LANG') and MW_LANG != false) {
            return MW_LANG;
        }


        $lang = false;


        if (!isset($lang) or $lang == false) {
            if (isset($_COOKIE['lang'])) {
                $lang = $_COOKIE['lang'];
            }
        }
        if (!isset($lang) or $lang == false) {
            $def_language = $this->app->option->get('language', 'website');
            if ($def_language != false) {
                $lang = $def_language;
            }
        }
        if (!isset($lang) or $lang == false) {
            $lang = 'en';
        }

        if (!defined('MW_LANG') and isset($lang)) {
            define('MW_LANG', $lang);
        }


        return $lang;

    }


    /**
     * Set the current language
     *
     * @example
     * <code>
     *   //sets language to Spanish
     *  set_language('es');
     * </code>
     * @package Language
     */
    function lang_set($lang = 'en')
    {
        setcookie("lang", $lang);
        return $lang;
    }


    /**
     * Gets all the language file contents
     * @internal its used via ajax in the admin panel under Settings->Language
     * @package Language
     */
    function get_language_file_content()
    {
        global $mw_language_content;

        if (!empty($mw_language_content)) {
            return $mw_language_content;
        }


        $lang = current_lang();

        $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
        $lang_file = normalize_path($lang_file, false);

        $lang_file2 = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . $lang . '.php';
        $lang_file3 = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.php';


        if (is_file($lang_file2)) {
            include ($lang_file2);

            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content[$k]) == false) {
                        $mw_language_content[$k] = $v;
                    }
                }
            }
        }


        if (is_file($lang_file)) {
            include ($lang_file);

            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content[$k]) == false) {

                        $mw_language_content[$k] = $v;
                    }
                }
            }
        }
        if (is_file($lang_file3)) {
            include ($lang_file3);

            if (isset($language) and is_array($language)) {
                foreach ($language as $k => $v) {
                    if (isset($mw_language_content[$k]) == false) {

                        $mw_language_content[$k] = $v;
                    }
                }
            }
        }

        return $mw_language_content;


    }
}

$mw_language_content = array();
$mw_skip_pages_starting_with_url = array('admin', 'api', 'module'); //its set in the funk bellow
$mw_precached_links = array();
$mw_global_content_memory = array();
