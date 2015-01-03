<?php

namespace Microweber\Providers;

use Microweber\Utils\Adapters\Cache\LaravelCache;

use Content;
use ContentFields;
use Menu;

/**
 * Content class is used to get and save content in the database.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */
class ContentManager
{

    public $tables = array();
    public $table_prefix = false;
    static $skip_pages_starting_with_url = ['admin', 'api', 'module'];
    static $precached_links = array();

    public $app = null;

    /**
     *  Boolean that indicates the usage of cache while making queries
     *
     * @var $no_cache
     */
    public $no_cache = false;


    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->set_table_names();


    }


    public function edit_field($data, $debug = false)
    {


        $table = $this->tables['content_fields'];

        $table_drafts = $this->tables['content_fields_drafts'];

        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (!is_array($data)) {
            $data = array();
        }


        if (isset($data['is_draft'])) {
            $table = $table_drafts;
        }

        if (!isset($data['rel_type'])) {
            if (isset($data['rel_type'])) {
                if ($data['rel_type'] == 'content' or $data['rel_type'] == 'page' or $data['rel_type'] == 'post') {
                    $data['rel_type'] = 'content';
                }
                $data['rel_type'] = $data['rel_type'];
            }
        }
        if (!isset($data['rel_id'])) {
            if (isset($data['data-id'])) {
                $data['rel_id'] = $data['data-id'];
            } else {

            }
        }

        if (!isset($data['rel_id']) and !isset($data['is_draft'])) {

        }

        if ((!isset($data['rel_type']) or !isset($data['rel_id'])) and !isset($data['is_draft'])) {
        }

        if ((isset($data['rel_type']) and isset($data['rel_id']))) {

            $data['cache_group'] = guess_cache_group('content_fields/global/' . $data['rel_type'] . '/' . $data['rel_id']);
        } else {
            $data['cache_group'] = guess_cache_group('content_fields/global');

        }
        if (!isset($data['all'])) {
            $data['one'] = 1;
            $data['limit'] = 1;
        }

        $data['table'] = $table;

        $get = $this->app->database->get($data);

        if (!isset($data['full']) and isset($get['value'])) {
            return $get['value'];
        } else {
            return $get;
        }


        return false;


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
     */
    public function get_page($id = 0)
    {
        if ($id == false or $id == 0) {
            return false;
        }
        if (intval($id) != 0) {
            $page = $this->get_by_id($id);
            if (empty($page)) {
                $page = $this->get_by_url($id);
            }
        } else {
            if (empty($page)) {
                $page = array();
                $page['layout_name'] = trim($id);
                $page = $this->get($page);
                $page = $page[0];
            }
        }
        return $page;
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

        if ($id == false) {
            return false;
        }

        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $q = $this->app->database->get_by_id($this->tables['content'], $id);
//        $q = Content::where('id', '=', $id)->first();
//        if (!empty($q)) {
//            $q = $q->toArray();
//        }

        if (isset($q['title'])) {

            $content = $q;
            if (isset($content['title'])) {
                $content['title'] = html_entity_decode($content['title']);
                $content['title'] = strip_tags($content['title']);
                $content['title'] = $this->app->format->clean_html($content['title']);
                $content['title'] = htmlspecialchars_decode($content['title']);

            }

        } else {
            return false;
        }

        return $content;
    }

    public function get_by_url($url = '', $no_recursive = false)
    {

        static $passed = array();
        if (isset($passed[$url])) {
            //    return;
        }
        $passed[$url] = 1;
        if (strval($url) == '') {
            $url = $this->app->url_manager->string();
        }


        $u1 = $url;
        $u2 = $this->app->url_manager->site();

        $u1 = rtrim($u1, '\\');
        $u1 = rtrim($u1, '/');

        $u2 = rtrim($u2, '\\');
        $u2 = rtrim($u2, '/');

        $u1 = str_replace($u2, '', $u1);
        $u1 = ltrim($u1, '/');
        $url = $u1;

        $table = $this->tables['content'];
        $url = addslashes($url);
        $url12 = parse_url($url);
        if (isset($url12['scheme']) and isset($url12['host']) and isset($url12['path'])) {
            $u1 = $this->app->url_manager->site();
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


        if (is_array(self::$skip_pages_starting_with_url)) {
            $segs = explode('/', $url);
            $arr = self::$skip_pages_starting_with_url;
            foreach ($arr as $skip_page_url) {
                if (in_array($skip_page_url, $segs)) {
                    return false;
                }
            }
        }


        $link_hash = 'link' . crc32($url);

        if (isset(self::$precached_links[$link_hash])) {
            return self::$precached_links[$link_hash];
        }

// @todo cleanup old code
        $sql = "SELECT id FROM $table WHERE url='{$url}'   ORDER BY updated_at DESC LIMIT 0,1 ";

        $get = array();
        $get['url'] = $url;
        $get['single'] = true;
        $content = $this->get($get);


        if (!empty($content)) {


            self::$precached_links[$link_hash] = $content;
            return $content;
        }

        if ($no_recursive == false) {
            if (empty($content) == true) {
                $segs = explode('/', $url);
                $segs_qty = count($segs);

                for ($counter = 0; $counter <= $segs_qty; $counter += 1) {
                    $test = array_slice($segs, 0, $segs_qty - $counter);
                    $test = array_reverse($test);
                    if (isset($test[0])) {
                        $url = $this->get_by_url($test[0], true);
                    }
                    if (!empty($url)) {
                        self::$precached_links[$link_hash] = $url;
                        return $url;
                    }
                }
            }
        } else {
            if (isset($content['id']) and intval($content['id']) != 0) {
                $content['id'] = ((int)$content['id']);
            }
            self::$precached_links[$link_hash] = $content;
            return $content;
        }
        self::$precached_links[$link_hash] = false;
        return false;
    }

    /**
     * Get array of content items from the database
     *
     * It accepts string or array as parameters. You can pass any db field name as parameter to filter content by it.
     * All parameter are passed to the get() function
     *
     * You can get and filter content and also order the results by criteria
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
     *| created_at          | the date of creation      |
     *| updated_at          | the date of last edit     |
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
     * $params['is_active'] = 1; //get only active content
     * $params['parent'] = 2; //get by parent id
     * $params['created_by'] = 1; //get by author id
     * $params['content_type'] = 'post'; //get by content type
     * $params['subtype'] = 'product'; //get by subtype
     * $params['title'] = 'my title'; //get by title
     *
     * $data = $this->get($params);
     * var_dump($data);
     *
     * </code>
     *
     * @example
     * #### Get by params as string
     * <code>
     *  $data = $this->get('is_active=1');
     *  var_dump($data);
     * </code>
     *
     * @example
     * #### Ordering and sorting
     * <code>
     *  //Order by position
     *  $data = $this->get('content_type=post&is_active=1&order_by=position desc');
     *  var_dump($data);
     *
     *  //Order by date
     *  $data = $this->get('content_type=post&is_active=1&order_by=updated_at desc');
     *  var_dump($data);
     *
     *  //Order by title
     *  $data = $this->get('content_type=post&is_active=1&order_by=title asc');
     *  var_dump($data);
     *
     *  //Get content from last week
     *  $data = $this->get('created_at=[mt]-1 week&is_active=1&order_by=title asc');
     *  var_dump($data);
     * </code>
     *
     */
    public function get($params = false)
    {

        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!is_array($params)) {
            $params = array();
            $params['is_active'] = 1;
        }


        $cache_group = 'content/global';
        if (isset($params['cache_group'])) {
            $cache_group = $params['cache_group'];
        }
        $table = $this->tables['content'];
        if (!isset($params['is_deleted'])) {
            $params['is_deleted'] = 0;
        }
        $params['table'] = $table;
        $params['cache_group'] = $cache_group;


        if (isset($params['search_by_keyword'])) {
            $params['keyword'] = $params['search_by_keyword'];
        }

        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('title', 'content_body', 'content', 'description', 'content_meta_keywords', 'content_meta_title', 'url');
        }


        $get = mw()->database->get($params);

        // $get = mw()->content->get_items($params);

        //   $get = $this->app->database->get($params);

        if (isset($params['count']) or isset($params['single']) or isset($params['one']) or isset($params['data-count']) or isset($params['page_count']) or isset($params['data-page-count'])) {
            if (isset($get['url'])) {
                $get['url'] = $this->app->url_manager->site($get['url']);
            }
            if (isset($get['title'])) {
                $get['title'] = html_entity_decode($get['title']);
                $get['title'] = strip_tags($get['title']);
                $get['title'] = $this->app->format->clean_html($get['title']);
                $get['title'] = htmlspecialchars_decode($get['title']);
            }
            return $get;
        }

        if (is_array($get)) {
            $data2 = array();
            foreach ($get as $item) {
                if (isset($item['url'])) {
                    $item['url'] = $this->app->url_manager->site($item['url']);
                }
                if (isset($item['title'])) {
                    $item['title'] = html_entity_decode($item['title']);
                    $item['title'] = strip_tags($item['title']);
                    $item['title'] = $this->app->format->clean_html($item['title']);
                    $item['title'] = htmlspecialchars_decode($item['title']);

                }
                $data2[] = $item;
            }
            $get = $data2;
            return $get;
        }

    }

    /**
     * Sets the database table names to use by the class
     *
     * @param array|bool $tables
     */
    public function set_table_names($tables = false)
    {

        $prefix = '';
        if (!isset($tables['content'])) {
            $tables['content'] = 'content';
        }
        if (!isset($tables['content_fields'])) {
            $tables['content_fields'] = 'content_fields';
        }
        if (!isset($tables['content_data'])) {
            $tables['content_data'] = 'content_data';
        }
        if (!isset($tables['content_fields_drafts'])) {
            $tables['content_fields_drafts'] = 'content_fields_drafts';
        }
        if (!isset($tables['media'])) {
            $tables['media'] = 'media';
        }
        if (!isset($tables['custom_fields'])) {
            $tables['custom_fields'] = 'custom_fields';
        }
        if (!isset($tables['custom_fields_values'])) {
            $tables['custom_fields_values'] = 'custom_fields_values';
        }
        if (!isset($tables['content_data'])) {
            $tables['content_data'] = 'content_data';
        }

        if (!isset($tables['categories'])) {
            $tables['categories'] = 'categories';
        }
        if (!isset($tables['categories_items'])) {
            $tables['categories_items'] = 'categories_items';
        }
        if (!isset($tables['menus'])) {
            $tables['menus'] = 'menus';
        }

        $this->tables['content'] = $tables['content'];
        $this->tables['content_fields'] = $tables['content_fields'];
        $this->tables['content_data'] = $tables['content_data'];
        $this->tables['content_fields_drafts'] = $tables['content_fields_drafts'];
        $this->tables['media'] = $tables['media'];
        $this->tables['custom_fields'] = $tables['custom_fields'];
        $this->tables['categories'] = $tables['categories'];
        $this->tables['categories_items'] = $tables['categories_items'];
        $this->tables['menus'] = $tables['menus'];


        /**
         * Define table names constants for global default usage
         */
        if (!defined("MW_DB_TABLE_CONTENT")) {
            define('MW_DB_TABLE_CONTENT', $tables['content']);
        }
        if (!defined("MW_DB_TABLE_CONTENT_FIELDS")) {
            define('MW_DB_TABLE_CONTENT_FIELDS', $tables['content_fields']);
        }
        if (!defined("MW_DB_TABLE_CONTENT_DATA")) {
            define('MW_DB_TABLE_CONTENT_DATA', $tables['content_data']);
        }
        if (!defined("MW_DB_TABLE_CONTENT_FIELDS_DRAFTS")) {
            define('MW_DB_TABLE_CONTENT_FIELDS_DRAFTS', $tables['content_fields_drafts']);
        }
        if (!defined("MW_DB_TABLE_MEDIA")) {
            define('MW_DB_TABLE_MEDIA', $tables['media']);
        }
        if (!defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
            define('MW_DB_TABLE_CUSTOM_FIELDS', $tables['custom_fields']);
        }
        if (!defined("MW_DB_TABLE_MENUS")) {
            define('MW_DB_TABLE_MENUS', $tables['menus']);
        }
        if (!defined("MW_DB_TABLE_TAXONOMY")) {
            define('MW_DB_TABLE_TAXONOMY', $tables['categories']);
        }
        if (!defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
            define('MW_DB_TABLE_TAXONOMY_ITEMS', $tables['categories_items']);
        }
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
        return $this->app->template->get_layout($page);

    }

    public function get_children($id = 0, $without_main_parrent = false)
    {

        if (intval($id) == 0) {

            return FALSE;
        }

        $table = $this->tables['content'];

        $ids = array();

        $data = array();
        $id = intval($id);


        $get = array();
        $get['parent'] = $id;


        if (isset($without_main_parrent) and $without_main_parrent == true) {
            $get['parent'] = '[neq]0';
        }

        // $q = " SELECT id, parent FROM $table WHERE parent={$id} " . $with_main_parrent_q;
        // $taxonomies = $this->app->database->query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'content/' . $id);

        $taxonomies = $this->get($get);
        // $taxonomies = $taxonomies->get()->toArray();

        if (!empty($taxonomies)) {
            foreach ($taxonomies as $item) {
                if (intval($item['id']) != 0) {
                    $ids[] = $item['id'];
                }

                if ($item['parent'] != $item['id'] and intval($item['parent'] != 0)) {
                    $next = $this->get_children($item['id'], $without_main_parrent);
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

    public function get_data($params = false)
    {

        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (!is_array($params)) {
            $params = array();
        }

        $table = $this->tables['content_data'];

        $params['table'] = $table;

        $get = $this->app->database->get($params);

        return $get;
    }

    public function data($content_id, $field_name = false)
    {
        $table = $this->tables['content_data'];
        $data = array();
        $data['table'] = $table;
        $data['cache_group'] = 'content_data';
        $data['content_id'] = intval($content_id);
        $res = array();
        $get = $this->app->database->get($data);
        if (!empty($get)) {
            foreach ($get as $item) {
                if (isset($item['field_name']) and isset($item['field_value'])) {
                    $res[$item['field_name']] = $item['field_value'];
                }
            }
        }
        if (!empty($res)) {
            return $res;
        }
        return $get;
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
     * @param $params ['num'] = 5; //the numer of pages
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
        $limit = false;
        if (isset($params['limit'])) {
            $limit = intval($params['limit']);
        }


        if (isset($params['class'])) {
            $class = $params['class'];
        }

        if (isset($params['paging_param'])) {
            $paging_param = $params['paging_param'];
        }

        $current_page_from_url = $this->app->url_manager->param($paging_param);

        if (isset($params['curent_page'])) {
            $current_page_from_url = $params['curent_page'];
        } elseif (isset($params['current_page'])) {
            $current_page_from_url = $params['current_page'];
        }

        $data = $this->paging_links($base_url, $pages_count, $paging_param, $keyword_param);
        if (is_array($data)) {
            $to_print = "<div class='{$class}-holder' ><ul class='{$class}'>";
            $paging_items = array();
            $active_item = 1;
            foreach ($data as $key => $value) {
                $skip = false;
                $act_class = '';
                if ($current_page_from_url != false) {
                    if (intval($current_page_from_url) == intval($key)) {
                        $act_class = ' class="active" ';
                        $active_item = $key;
                    }
                }

                $item_to_print = '';
                $item_to_print .= "<li {$act_class} data-page-number=\"$key\">";
                $item_to_print .= "<a {$act_class} href=\"$value\" data-page-number=\"$key\">$key</a> ";
                $item_to_print .= "</li>";
                $paging_items[$key] = $item_to_print;
            }

            if ($limit != false and count($paging_items) > $limit) {
                $limited_paging = array();

                $limited_paging_begin = array();

                foreach ($paging_items as $key => $paging_item) {
                    if ($key == $active_item) {
                        $steps = $steps2 = floor($limit / 2);
                        for ($i = 1; $i <= $steps; $i++) {
                            if (isset($paging_items[$key - $i])) {
                                $limited_paging_begin[$key - $i] = $paging_items[$key - $i];
                                // $steps2--;
                            } else {
                                $steps2++;
                            }
                        }

                        $limited_paging[$key] = $paging_item;
                        for ($i = 1; $i <= $steps2; $i++) {
                            if (isset($paging_items[$key + $i])) {
                                $limited_paging[$key + $i] = $paging_items[$key + $i];
                            }
                        }


                    }

                }
                $prev_link = '#';
                $next_link = '#';
                if (isset($data[$active_item - 1])) {
                    $prev_link = $data[$active_item - 1];
                    $limited_paging_begin[] = '<li class="mw-previous-page-item"><a data-page-number="' . ($active_item - 1) . '" href="' . $prev_link . '">&laquo;</a></li>';

                }

                $limited_paging_begin = array_reverse($limited_paging_begin);

                $limited_paging = array_merge($limited_paging_begin, $limited_paging);

                if (isset($data[$active_item + 1])) {
                    $next_link = $data[$active_item + 1];
                    $limited_paging[] = '<li class="mw-next-page-item"><a data-page-number="' . ($active_item + 1) . '" href="' . $next_link . '">&raquo;</a></li>';

                }
                if (count($limited_paging) > 2) {
                    $paging_items = $limited_paging;
                }
            }
            $to_print .= implode("\n", $paging_items);
            $to_print .= "</ul></div>";
            return $to_print;
        }
    }

    public function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword')
    {
        if ($base_url == false) {
            if ($this->app->url_manager->is_ajax() == false) {
                $base_url = $this->app->url_manager->current(1);
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

        $in_empty_url = false;
        if ($the_url == site_url()) {
            $in_empty_url = 1;
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
                if ($in_empty_url == false) {
                    $l = reduce_double_slashes($page_links[$x] . '/' . $paging_param . ':' . $x);
                } else {
                    $l = reduce_double_slashes($page_links[$x] . '?' . $paging_param . ':' . $x);

                }
                $l = str_ireplace('module/', '', $l);
                $page_links[$x] = $l . $append_to_links;
            }
        }

        return $page_links;
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
        $function_cache_id = __FUNCTION__ . crc32($function_cache_id) . PAGE_ID . $parent;
        if ($parent == 0) {
            $cache_group = 'content/global';
        } else {
            $cache_group = 'categories/global';
        }
        if (isset($include_categories) and $include_categories == true) {
            $cache_group = 'categories/global';
        }


        $nest_level = 0;

        if (isset($params['nest_level'])) {
            $nest_level = $params['nest_level'];
        }

        $nest_level_orig = $nest_level;
        //$params['no_cache'] = 1;
        if ($nest_level_orig == 0) {
            $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
            if (isset($params['no_cache'])) {
                $cache_content = false;
            }
            //      $cache_content = false;
            if (($cache_content) != false) {
                if (isset($params['return_data'])) {
                    return $cache_content;
                } else {
                    print $cache_content;
                }
                return;
            }
        }

        $nest_level = 0;

        if (isset($params['nest_level'])) {
            $nest_level = $params['nest_level'];
        }
        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        } else if (isset($params['maxdepth'])) {
            $max_level = $params['max_level'] = $params['maxdepth'];
        } else if (isset($params['depth'])) {
            $max_level = $params['max_level'] = $params['depth'];
        }

        if ($max_level != false) {
            if (intval($nest_level) >= intval($max_level)) {
                print '';
                return;
            }
        }


        $is_shop = '';
        if (isset($params['is_shop'])) {
            if ($params['is_shop'] == 'y') {
                $params['is_shop'] = 1;
            } else if ($params['is_shop'] == 'n') {
                $params['is_shop'] = 0;
            }

            $is_shop = $this->app->database_manager->escape_string($params['is_shop']);
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
        if (isset($params['ul_tag'])) {

            $list_tag = $params['ul_tag'];
        }
        if (isset($params['li_tag'])) {

            $list_item_tag = $params['li_tag'];
        }
        if (isset($params['include_categories'])) {

            $include_categories = $params['include_categories'];
        }


        ob_start();

        $table = $this->tables['content'];
        $par_q = '';
        if ($parent == false) {

            $parent = (0);
        } else {
            $par_q = " parent=$parent    and  ";

        }


        if ($include_first == true) {
            $sql = "SELECT * from $table where  id={$parent}    and   is_deleted=0 and content_type='page' " . $is_shop . "  order by position desc  limit 0,1";
        } else {
            $sql = "SELECT * from $table where  " . $par_q . "  content_type='page' and   is_deleted=0 $is_shop  order by position desc limit 0,100";
        }
        $cid = __FUNCTION__ . crc32($sql);
        $cidg = 'content/' . $parent;
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


        $params['content_type'] = 'page';

        $include_first_set = false;
        if ($include_first == true) {
            $include_first_set = 1;
            $include_first = false;
            $include_first_set = $parent;
            if (isset($params['include_first'])) {
                unset($params['include_first']);
            }
        } else {
            $params['parent'] = $parent;
        }

        if (isset($params['is_shop']) and $params['is_shop'] == 1) {
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


        $params['limit'] = 500;
        $params['orderby'] = 'position desc';
        $params['curent_page'] = 1;
        $params['is_deleted'] = 0;
        $params['cache_group'] = false;
        $params['no_cache'] = true;

        $skip_pages_with_no_categories = false;
        $skip_pages_from_tree = false;

        if (isset($params['skip_sub_pages']) and $params['skip_sub_pages'] != '') {
            $skip_pages_from_tree = $params['skip_sub_pages'];
        }
        if (isset($params['skip-static-pages']) and $params['skip-static-pages'] != false) {
            $skip_pages_with_no_categories = 1;
        }

        $params2 = $params;


        if (isset($params2['id'])) {
            unset($params2['id']);
        }
        if (isset($params2['link'])) {
            unset($params2['link']);
        }

        if ($include_first_set != false) {
            $q = $this->get("id=" . $include_first_set);
        } else {

            $q = $this->get($params2);

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
                if (is_array($item) != false and isset($item['title']) and $item['title'] != null) {
                    $skip_me_cause_iam_removed = false;
                    if (is_array($remove_ids) == true) {

                        if (in_array($item['id'], $remove_ids)) {

                            $skip_me_cause_iam_removed = true;
                        }
                    }

                    if ($skip_pages_with_no_categories == true) {
                        if (isset($item ['subtype']) and $item ['subtype'] != 'dynamic') {
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

                        if (isset($item ['layout_file']) and stristr($item ['layout_file'], 'blog')) {
                            $content_type_li_class .= ' is_blog';
                        }


                        if ($item['is_home'] == 1) {
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

                        if (isset($item['is_shop']) and trim($item['is_shop']) == 1) {
                            $st_str3 = " data-is-shop=true ";
                            $content_type_li_class .= ' is_shop';
                        }
                        $iid = $item['id'];


                        $to_pr_2 = "<{$list_item_tag} class='{$li_class} $content_type_li_class {active_class} {active_parent_class} depth-{$nest_level} item_{$iid} {exteded_classes} menu-item-id-{$item['id']}' data-page-id='{$item['id']}' value='{$item['id']}'  data-item-id='{$item['id']}'  {active_code_tag} data-parent-page-id='{$item['parent']}' {$st_str} {$st_str2} {$st_str3}  title='" . addslashes($item['title']) . "' >";

                        if ($link != false) {
                            $active_parent_class = '';
                            if (intval($item['parent']) != 0 and intval($item['parent']) == intval(MAIN_PAGE_ID)) {
                                $active_parent_class = 'active-parent';
                            } elseif (intval($item['id']) == intval(MAIN_PAGE_ID)) {
                                $active_parent_class = 'active-parent';
                            } else {
                                $active_parent_class = '';
                            }


                            if ($item['id'] == CONTENT_ID) {
                                $active_class = 'active';
                            } elseif (isset($active_ids) and !is_array($active_ids) and $item['id'] == $active_ids) {
                                $active_class = 'active';
                            }
                            if (isset($active_ids) and is_array($active_ids) and in_array($item['id'], $active_ids)) {
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
                            if (isset($active_ids) and is_array($active_ids) == true) {
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
                            } else if (isset($active_ids) and !is_array($active_ids)) {
                                if (intval($item['id']) == intval($active_ids)) {
                                    $is_there_active_ids = true;
                                    $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                    $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                    $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                    $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                }
                            }


                            $to_print = str_ireplace('{active_code}', '', $to_print);
                            $to_print = str_ireplace('{active_class}', '', $to_print);
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{active_code_tag}', '', $to_pr_2);

                            $to_print = str_replace('{exteded_classes}', '', $to_print);

                            if (is_array($remove_ids) == true) {
                                if (in_array($item['id'], $remove_ids)) {
                                    if ($removed_ids_code == false) {
                                        $to_print = false;
                                    } else {
                                        $remove_ids[] = $item['id'];
                                        $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                                    }
                                } else {
                                    $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
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
                            if (isset($include_categories)) {
                                $params['include_categories'] = $include_categories;
                            }


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

                            if (isset($categories_removed_ids)) {
                                $cat_params['remove_ids'] = $categories_removed_ids;

                            }

                            if (isset($active_code)) {
                                $cat_params['active_code'] = $active_code;
                            }


                            //$cat_params['for'] = 'content';
                            $cat_params['list_tag'] = $list_tag;
                            $cat_params['list_item_tag'] = $list_item_tag;
                            $cat_params['rel_type'] = 'content';
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
                            $this->app->category_manager->tree($cat_params);
                        }
                    }
                    print "</{$list_item_tag}>";
                }
            }
            if (trim($list_tag) != '') {
                print "</{$list_tag}>";
            }
        }
        $content = ob_get_contents();
        if ($nest_level_orig == 0) {
            $this->app->cache_manager->save($content, $function_cache_id, $cache_group);
        }
        ob_end_clean();
        if (isset($params['return_data'])) {
            return $content;
        } else {
            print $content;
        }
        return false;
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
     * @see content_link()
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
        if (is_array($id)) {
            extract($id);
        }

        if ($id == false or $id == 0) {
            if (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }

        if ($id == 0) {
            return $this->app->url_manager->site();
        }

        $link = $this->get_by_id($id);

        if (!isset($link['url']) or strval($link['url']) == '') {
            $link = $this->get_by_url($id);
        }

        $site_url = $this->app->url_manager->site();
        if (!stristr($link['url'], $site_url)) {
            $link = site_url($link['url']);
        } else {
            $link = ($link['url']);
        }

        return $link;
    }

    function debug_info()
    {
        //if (c('debug_mode')) {

        return include(mw_includes_path() . 'debug.php');
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
            $def_language = $this->app->option_manager->get('language', 'website');
            if ($def_language != false) {
                $lang = $def_language;
            }
        }
        if (!isset($lang) or $lang == false) {
            $lang = 'en';
        }
        $lang = str_replace('..', '', $lang);
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
        $lang = str_replace('..', '', $lang);
        setcookie("lang", $lang);
        return $lang;
    }

    public function add_content_to_menu($content_id, $menu_id = false)
    {
        $new_item = false;

        $id = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $id == false) {
            return;
        }

        $content_id = intval($content_id);

        if ($content_id == 0 or !isset($this->tables['menus'])) {

            return;
        }


        if ($menu_id != false) {
            $_REQUEST['add_content_to_menu'] = $menu_id;
        }


        $menus = $this->tables['menus'];
        if (isset($_REQUEST['add_content_to_menu']) and is_array($_REQUEST['add_content_to_menu'])) {
            $add_to_menus = $_REQUEST['add_content_to_menu'];
            $add_to_menus_int = array();
            foreach ($add_to_menus as $value) {
                if ($value == 'remove_from_all') {
                    Menu::where('content_id', $content_id)->where('item_type', 'menu_item')->delete();


                    $this->app->cache_manager->delete('menus');

                }

                $value = intval($value);
                if ($value > 0) {
                    $add_to_menus_int[] = $value;
                }
            }

        }


        $add_under_parent_page = false;
        $content_data = false;

        if (isset($_REQUEST['add_content_to_menu_auto_parent']) and ($_REQUEST['add_content_to_menu_auto_parent']) != false) {
            $add_under_parent_page = true;
            //
            //
            $content_data = $this->get_by_id($content_id);
            if ($content_data['is_active'] != 1) {

                return false;
            }

        }
        if (!isset($add_to_menus_int) or empty($add_to_menus_int)) {
            if ($menu_id != false) {
                $add_to_menus_int[] = intval($menu_id);
            }
        }

        if (isset($add_to_menus_int) and is_array($add_to_menus_int)) {


            Menu::where('content_id', $content_id)
                ->where('item_type', 'menu_item')
                ->whereNotIn('parent_id', $add_to_menus_int)
                ->delete();

            foreach ($add_to_menus_int as $value) {
                $check = $this->app->menu_manager->get_menu_items("limit=1&count=1&parent_id={$value}&content_id=$content_id");

                if ($check == 0) {
                    $save = array();
                    $save['item_type'] = 'menu_item';
                    $save['is_active'] = 1;
                    $save['parent_id'] = $value;
                    $save['position'] = 999999;
                    //  $save['debug'] = 999999;
                    if ($add_under_parent_page != false and is_array($content_data) and isset($content_data['parent'])) {
                        $parent_cont = $content_data['parent'];
                        $check_par = $this->app->menu_manager->get_menu_items("limit=1&one=1&content_id=$parent_cont");
                        if (is_array($check_par) and isset($check_par['id'])) {
                            $save['parent_id'] = $check_par['id'];
                        }
                    }


                    $save['url'] = '';

                    $save['content_id'] = $content_id;

                    $new_item = $this->app->database->save($menus, $save);
                    $this->app->cache_manager->delete('menus/global');

                    $this->app->cache_manager->delete('menus/' . $save['parent_id']);
                    //$this->app->cache_manager->delete('menus/' . $save['parent_id']);

                    $this->app->cache_manager->delete('menus/' . $value);

                    $this->app->cache_manager->delete('content/' . $content_id);
                }
            }

            $this->app->cache_manager->delete('menus/global');
            $this->app->cache_manager->delete('menus');

        }
        return $new_item;

    }


    function breadcrumb($params = false)
    {
        $result = array();
        $cur_page = false;
        $cur_content = false;
        $cur_category = false;
        if (defined('PAGE_ID') and PAGE_ID != false) {
            $cur_page = PAGE_ID;
        }
        if (defined('POST_ID') and CONTENT_ID != false) {
            $cur_content = CONTENT_ID;
            if ($cur_content == $cur_page) {
                $cur_content = false;
            }
        }
        if (defined('CATEGORY_ID') and CATEGORY_ID != false) {
            $cur_category = CATEGORY_ID;
        }


        if ($cur_page != false) {


            $content_parents = $this->get_parents($cur_page);
            if (!empty($content_parents)) {
                foreach (($content_parents) as $item) {
                    $item = intval($item);
                    if ($item > 0) {
                        $content = $this->get_by_id($item);
                        if (isset($content['id'])) {
                            $result_item = array();
                            $result_item['title'] = $content['title'];
                            $result_item['url'] = $this->link($content['id']);
                            $result_item['description'] = $content['description'];
                            if ($cur_content == $content['id']) {
                                $result_item['is_active'] = true;
                            } else {
                                $result_item['is_active'] = false;
                            }
                            $result_item['parent_content_id'] = $content['parent'];
                            $result_item['content_type'] = $content['content_type'];
                            $result_item['subtype'] = $content['subtype'];
                            $result[] = $result_item;
                        }
                    }
                }
            }
            $content = $this->get_by_id($cur_page);
            if (isset($content['id'])) {
                $result_item = array();
                $result_item['title'] = $content['title'];
                $result_item['url'] = $this->link($content['id']);
                $result_item['description'] = $content['description'];
                if ($cur_content == $content['id']) {
                    $result_item['is_active'] = true;
                } else {
                    $result_item['is_active'] = false;
                }
                $result_item['parent_content_id'] = $content['parent'];
                $result_item['content_type'] = $content['content_type'];
                $result_item['subtype'] = $content['subtype'];
                $result[] = $result_item;
            }
        }


        if ($cur_category != false) {
            $cur_category_data = $this->app->category_manager->get_by_id($cur_category);
            if ($cur_category_data != false and isset($cur_category_data['id'])) {
                $cat_parents = $this->app->category_manager->get_parents($cur_category);
                if (!empty($cat_parents)) {
                    foreach (($cat_parents) as $item) {
                        $item = intval($item);
                        if ($item > 0) {
                            $content = $this->app->category_manager->get_by_id($item);
                            if (isset($content['id'])) {
                                $result_item = array();
                                $result_item['title'] = $content['title'];
                                $result_item['description'] = $content['description'];
                                $result_item['url'] = $this->app->category_manager->link($content['id']);

                                $result_item['content_type'] = 'category';
                                if ($cur_content == false and $cur_category == $content['id']) {
                                    $result_item['is_active'] = true;

                                } else {
                                    $result_item['is_active'] = false;

                                }
                                $result[] = $result_item;
                            }
                        }
                    }
                }
            }
            $content = $cur_category_data;
            if (isset($content['id'])) {
                $result_item = array();
                $result_item['title'] = $content['title'];
                $result_item['description'] = $content['description'];
                $result_item['url'] = $this->app->category_manager->link($content['id']);
                $result_item['content_type'] = 'category';
                if ($cur_content == false and $cur_category == $content['id']) {
                    $result_item['is_active'] = true;
                } else {
                    $result_item['is_active'] = false;

                }
                $result[] = $result_item;
            }

        }

        if ($cur_content != false) {
            $content = $this->get_by_id($cur_content);
            if (isset($content['id'])) {
                $result_item = array();
                $result_item['title'] = $content['title'];
                $result_item['url'] = $this->link($content['id']);
                $result_item['description'] = $content['description'];
                if ($cur_content == $content['id']) {
                    $result_item['is_active'] = true;
                } else {
                    $result_item['is_active'] = false;
                }
                $result_item['parent_content_id'] = $content['parent'];
                $result_item['content_type'] = $content['content_type'];
                $result_item['subtype'] = $content['subtype'];
                $result[] = $result_item;
            }
        }
        return $result;
    }

    /**
     * Saves your custom language translation
     * @internal its used via ajax in the admin panel under Settings->Language
     * @package Language
     */
    function lang_file_save($data)
    {

        if (isset($_POST) and !empty($_POST)) {
            $data = $_POST;
        }
        $is_admin = $this->app->user_manager->is_admin();
        if ($is_admin == true) {
            if (isset($data['unicode_temp_remove'])) {
                unset($data['unicode_temp_remove']);
            }


            $lang = current_lang();

            $cust_dir = $lang_file = MW_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;
            if (!is_dir($cust_dir)) {
                mkdir_recursive($cust_dir);
            }

            $language_content = $data;

            $lang_file = $cust_dir . $lang . '.php';

            if (is_array($language_content)) {
                $language_content = array_unique($language_content);

                $lang_file_str = '<?php ' . "\n";
                $lang_file_str .= ' $language=array();' . "\n";
                foreach ($language_content as $key => $value) {

                    $value = addslashes($value);
                    $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

                }
                $language_content_saved = 1;
                if (is_admin() == true) {
                    file_put_contents($lang_file, $lang_file_str);
                }
            }
            return array('success' => 'Language file [' . $lang . '] is updated');


        }


    }

    public function save_edit($post_data)
    {
        $is_module = false;
        $is_admin = $this->app->user_manager->is_admin();
        if ($post_data) {
            if (isset($post_data['json_obj'])) {
                $obj = json_decode($post_data['json_obj'], true);
                $post_data = $obj;
            }
            if (isset($post_data['mw_preview_only'])) {
                $is_no_save = true;
                unset($post_data['mw_preview_only']);
            }
            $is_no_save = false;
            $is_draft = false;
            if (isset($post_data['is_draft'])) {
                unset($post_data['is_draft']);
                $is_draft = 1;
            }
            $the_field_data_all = $post_data;
        } else {

            return array('error' => 'no POST?');

        }

        $ustr2 = $this->app->url_manager->string(1, 1);

        if (isset($ustr2) and trim($ustr2) == 'favicon.ico') {
            return false;
        }
        $ref_page = $ref_page_url = false;
        if (isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $ref_page_url = $_SERVER['HTTP_REFERER'];

        }

        if (isset($post_data['id']) and intval($post_data['id']) > 0) {
            $page_id = intval($post_data['id']);
        } elseif ($ref_page != '') {
            //removing hash from url
            if (strpos($ref_page_url, '#')) {
                $ref_page = $ref_page_url = substr($ref_page_url, 0, strpos($ref_page_url, '#'));
            }
            // $ref_page = $the_ref_page = $this->get_by_url($ref_page_url);
            $ref_page2 = $ref_page = $this->get_by_url($ref_page_url);
            if ($ref_page2 == false) {

                $ustr = $this->app->url_manager->string(1);

                if ($this->app->modules->is_installed($ustr)) {
                    $ref_page = false;
                }

            } else {
                $ref_page = $ref_page2;
            }
            if (isset($ustr) and trim($ustr) == 'favicon.ico') {
                return false;
            } elseif ($ustr2 == '' or $ustr2 == '/') {
                $ref_page = $this->homepage();
                if ($ref_page_url) {
                    $page_url_ref = $this->app->url_manager->param('content_id', $ref_page_url);
                    if ($page_url_ref !== false) {
                        if ($page_url_ref == 0) {
                            return false;
                        }
                    }
                }


            }


            if ($ref_page == false) {


                $guess_page_data = new \Microweber\Controllers\DefaultController();
                // $guess_page_data =  new  $this->app->controller($this->app);
                $guess_page_data->page_url = $ref_page_url;
                $guess_page_data->return_data = true;
                $guess_page_data->create_new_page = true;
                $pd = $guess_page_data->index();
                $ustr = $this->app->url_manager->string(1);
                $is_module = false;

                if ($this->app->modules->is_installed($ustr)) {
                    $is_module = true;
                    $save_page['layout_file'] = 'clean.php';
                    $save_page['subtype'] = 'module';

                    $hp_id = $this->app->content_manager->homepage();

                    if (isset($hp_id['id'])) {
                        $page_id = $hp_id['id'];

                    } else {
                        //save global fields anyway
                        $page_id = 1;

                    }
                    $is_module = 1;
                    $save_page = false;

                } else {

                }


                if ($is_admin == true and is_array($pd) and $is_module == false) {
                    $save_page = $pd;


                    if (!isset($_GET['mw_quick_edit'])) {
                        if (isset($ref_page_url) and $ref_page_url != false) {
                            $save_page['url'] = $ref_page_url;
                        } else {
                            $save_page['url'] = $this->app->url_manager->string(1);

                        }
                        $title = str_replace('%20', ' ', ($this->app->url_manager->string(1)));

                        if ($title == 'editor_tools/wysiwyg' or $title == 'api/module' or $title == 'admin/view:content') {
                            return false;
                        }

                        $save_page['title'] = $title;
                        if ($save_page['url'] == '' or $save_page['url'] == '/' or $save_page['url'] == $this->app->url_manager->site()) {
                            $save_page['url'] = 'home';
                            $home_exists = $this->homepage();
                            if ($home_exists == false) {
                                $save_page['is_home'] = 1;
                            }
                        }
                    }
                    if ($save_page['title'] == '') {
                        $save_page['title'] = 'Home';
                    }
                    if (!isset($save_page['is_active'])) {
                        $save_page['is_active'] = 1;
                    }
                    if (isset($save_page['content_type']) and $save_page['content_type'] == 'page') {
                        if (!isset($save_page['subtype'])) {
                            $save_page['subtype'] = 'static';
                            $save_page['layout_file'] = 'inherit';
                        }
                    }
                    if ($save_page != false) {

                        $page_id = $this->save_content_admin($save_page);
                    }
                }

            } else {
                $page_id = $ref_page['id'];
                $ref_page['custom_fields'] = $this->custom_fields($page_id, false);
            }
        }

        $author_id = user_id();
        if ($is_admin == false and $page_id != 0 and $author_id != 0) {
            $page_data_to_check_author = $this->get_by_id($page_id);
            if (!isset($page_data_to_check_author['created_by']) or ($page_data_to_check_author['created_by'] != $author_id)) {
                return array('error' => 'You dont have permission to edit this content');
            }


        } else if ($is_admin == false) {
            return array('error' => 'Not logged in as admin to use ' . __FUNCTION__);

        }


        $save_as_draft = false;
        if (isset($post_data['save_draft'])) {
            $save_as_draft = true;
            unset($post_data['save_draft']);
        }

        /*

          $double_save_checksum = md5(serialize($post_data));
          $last_save_checksum = $this->app->user_manager->session_get('mw_live_ed_checksum');

          if($double_save_checksum != $last_save_checksum){
              $this->app->user_manager->session_set('mw_live_ed_checksum',$double_save_checksum);
          } else {
              return array('success'=>'No text is changed from the last save');
          }

        */


        $json_print = array();
        foreach ($the_field_data_all as $the_field_data) {
            $save_global = false;
            $save_layout = false;
            if (isset($page_id) and $page_id != 0 and !empty($the_field_data)) {
                $save_global = false;

                $content_id = $page_id;


                $url = $this->app->url_manager->string(true);
                $some_mods = array();
                if (isset($the_field_data) and is_array($the_field_data) and isset($the_field_data['attributes'])) {
                    if (($the_field_data['html']) != '') {
                        $field = false;
                        if (isset($the_field_data['attributes']['field'])) {
                            $field = trim($the_field_data['attributes']['field']);
                            //$the_field_data['attributes']['rel_type'] = $field;


                        }

                        if (isset($the_field_data['attributes']['data-field'])) {
                            $field = $the_field_data['attributes']['field'] = trim($the_field_data['attributes']['data-field']);
                        }

                        if ($field == false) {
                            if (isset($the_field_data['attributes']['id'])) {
                                //	$the_field_data['attributes']['field'] = $field = $the_field_data['attributes']['id'];
                            }
                        }

                        if (($field != false)) {
                            $page_element_id = $field;
                        }
                        if (!isset($the_field_data['attributes']['rel'])) {
                            $the_field_data['attributes']['rel_type'] = 'content';
                        } else {
                            $the_field_data['attributes']['rel_type'] = $the_field_data['attributes']['rel'];
                        }

                        if (isset($the_field_data['attributes']['rel-id'])) {
                            $content_id = $the_field_data['attributes']['rel-id'];
                        } elseif (isset($the_field_data['attributes']['rel_id'])) {
                            $content_id = $the_field_data['attributes']['rel_id'];
                        } elseif (isset($the_field_data['attributes']['data-rel-id'])) {
                            $content_id = $the_field_data['attributes']['data-rel-id'];
                        } elseif (isset($the_field_data['attributes']['data-rel_id'])) {
                            $content_id = $the_field_data['attributes']['data-rel_id'];
                        }


                        $save_global = false;
                        if (isset($the_field_data['attributes']['rel_type']) and (trim($the_field_data['attributes']['rel_type']) == 'global' or trim($the_field_data['attributes']['rel_type'])) == 'module') {
                            $save_global = true;
                        } else {
                            $save_global = false;
                        }
                        if (isset($the_field_data['attributes']['rel_type']) and trim($the_field_data['attributes']['rel_type']) == 'layout') {
                            $save_global = false;
                            $save_layout = true;
                        } else {
                            $save_layout = false;
                        }
                        if (isset($the_field_data['attributes']['rel'])) {
                            $the_field_data['attributes']['rel_type'] = $the_field_data['attributes']['rel'];
                        }

                        if (!isset($the_field_data['attributes']['data-id'])) {
                            $the_field_data['attributes']['data-id'] = $content_id;
                        }

                        $save_global = 1;

                        if (isset($the_field_data['attributes']['rel_type']) and isset($the_field_data['attributes']['data-id'])) {


                            $rel_ch = trim($the_field_data['attributes']['rel_type']);
                            switch ($rel_ch) {
                                case 'content':

                                    $save_global = false;
                                    $save_layout = false;
                                    $content_id_for_con_field = $content_id = $the_field_data['attributes']['data-id'];
                                    break;
                                case 'page':
                                case 'post':
                                    $save_global = false;
                                    $save_layout = false;
                                    $content_id_for_con_field = $content_id = $page_id;
                                    break;


                                default:

                                    break;
                            }


                        }

                        $inh = false;


                        if (isset($the_field_data['attributes']['rel_type']) and ($the_field_data['attributes']['rel_type']) == 'inherit') {


                            $save_global = false;
                            $save_layout = false;
                            $content_id = $page_id;

                            $inh = $this->get_inherited_parent($page_id);
                            if ($inh != false) {
                                $content_id_for_con_field = $content_id = $inh;

                            }

                        } else if (isset($the_field_data['attributes']['rel_type']) and ($the_field_data['attributes']['rel_type']) == 'page') {


                            $save_global = false;
                            $save_layout = false;
                            $content_id = $page_id;
                            $check_if_page = $this->get_by_id($content_id);

                            if (is_array($check_if_page)
                                and isset($check_if_page['content_type'])
                                and isset($check_if_page['parent'])
                                and $check_if_page['content_type'] != ''
                                and intval($check_if_page['parent']) != 0
                                and $check_if_page['content_type'] != 'page'
                            ) {
                                // $inh = $this->get_inherited_parent($page_id);
                                $inh = $check_if_page['parent'];
                                if ($inh != false) {
                                    $content_id_for_con_field = $content_id = $inh;

                                }

                            }


                        }

                        $save_layout = false;

                        if (isset($post_data['id'])) {
                            $content_id_for_con_field = $post_data['id'];
                        } elseif ($inh == false and !isset($content_id_for_con_field)) {
                            if (is_array($ref_page) and isset($ref_page['parent']) and isset($ref_page['content_type']) and $ref_page['content_type'] == 'post') {
                                $content_id_for_con_field = intval($ref_page['parent']);
                            } else {
                                $content_id_for_con_field = intval($ref_page['id']);

                            }
                        }
                        $html_to_save = $the_field_data['html'];
                        $html_to_save = $content = mw()->parser->make_tags($html_to_save);

                        if ($save_global == false and $save_layout == false) {
                            if ($content_id) {
                                $for_histroy = $ref_page;
                                $old = false;
                                $field123 = str_ireplace('custom_field_', '', $field);

                                if (stristr($field, 'custom_field_')) {

                                    $old = $for_histroy['custom_fields'][$field123];
                                } else {

                                    if (isset($for_histroy['custom_fields'][$field123])) {
                                        $old = $for_histroy['custom_fields'][$field123];
                                    } elseif (isset($for_histroy[$field])) {
                                        $old = $for_histroy[$field];
                                    }
                                }
                                $history_to_save = array();
                                $history_to_save['table'] = 'content';
                                $history_to_save['id'] = $content_id;
                                $history_to_save['value'] = $old;
                                $history_to_save['field'] = $field;

                                $cont_field = array();
                                $cont_field['rel_type'] = 'content';
                                $cont_field['rel_id'] = $content_id_for_con_field;
                                $cont_field['value'] = $html_to_save;
                                $cont_field['field'] = $field;


                                if ($is_draft != false) {
                                    $cont_field['is_draft'] = 1;
                                    $cont_field['rel_type'] = $rel_ch;
                                    $cont_field['url'] = $url;

                                    $cont_field1 = $this->save_content_field($cont_field);

                                } else {
                                    if ($field != 'content') {

                                        $cont_field1 = $this->save_content_field($cont_field);
                                    }

                                }


                                $to_save = array();
                                $to_save['id'] = $content_id;


                                $is_native_fld = $this->app->database->get_fields('content');
                                if (in_array($field, $is_native_fld)) {
                                    $to_save[$field] = ($html_to_save);
                                } else {

                                    //$to_save['custom_fields'][$field] = ($html_to_save);
                                }


                                if ($is_no_save != true and $is_draft == false) {
                                    $json_print[] = $to_save;
                                    $saved = $this->save_content_admin($to_save);
                                }


                            } else if (isset($category_id)) {
                                print(__FILE__ . __LINE__ . ' category is not implemented ... not ready yet');
                            }
                        } else {

                            $cont_field = array();

                            $cont_field['rel_type'] = $the_field_data['attributes']['rel_type'];
                            $cont_field['rel_id'] = 0;
                            if (isset($the_field_data['attributes']['rel-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['rel-id'];
                            } elseif (isset($the_field_data['attributes']['rel_id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['rel_id'];
                            } elseif (isset($the_field_data['attributes']['data-rel-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-rel-id'];
                            } elseif ($cont_field['rel_type'] != 'global' and isset($the_field_data['attributes']['content-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['content-id'];
                            } elseif ($cont_field['rel_type'] != 'global' and isset($the_field_data['attributes']['data-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-id'];
                            } elseif (isset($the_field_data['attributes']['data-rel_id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-rel_id'];
                            }


                            $cont_field['value'] = $this->app->parser->make_tags($html_to_save);

                            if ((!isset($the_field_data['attributes']['field']) or $the_field_data['attributes']['field'] == '') and isset($the_field_data['attributes']['data-field'])) {
                                $the_field_data['attributes']['field'] = $the_field_data['attributes']['data-field'];
                            }
                            $cont_field['field'] = $the_field_data['attributes']['field'];


                            if ($is_draft != false) {
                                $cont_field['is_draft'] = 1;
                                $cont_field['url'] = $this->app->url_manager->string(true);
                                $cont_field_new = $this->save_content_field($cont_field);
                            } else {
                                $cont_field_new = $this->save_content_field($cont_field);

                            }


                            if ($save_global == true and $save_layout == false) {


                                $json_print[] = $cont_field;
                                $history_to_save = array();
                                $history_to_save['table'] = 'global';
                                // $history_to_save ['id'] = 'global';
                                $history_to_save['value'] = $cont_field['value'];
                                $history_to_save['field'] = $field;
                                $history_to_save['page_element_id'] = $page_element_id;


                            }


//                            if ($save_global == false and $save_layout == true) {
//
//                                $d = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editable' . DIRECTORY_SEPARATOR;
//                                $f = $d . $ref_page['id'] . '.php';
//                                if (!is_dir($d)) {
//                                    mkdir_recursive($d);
//                                }
//
//                                file_put_contents($f, $html_to_save);
//                            }
                        }
                    }
                } else {

                }
            }
        }
        if (isset($opts_saved)) {
            $this->app->cache_manager->delete('options');
        }
        return $json_print;
    }

    /**
     * Returns the homepage as array
     *
     * @category Content
     * @package Content
     */
    public function homepage()
    {

        $get = array();
        $get['is_home'] = 1;
        $get['single'] = 1;

        $data = $this->get($get);


        return $data;


    }

    public function save_content_admin($data, $delete_the_cache = true)
    {

        if (is_string($data)) {
            $data = parse_params($data);
        }

        $adm = $this->app->user_manager->is_admin();

        $checks = mw_var('FORCE_SAVE_CONTENT');
        $orig_data = $data;
        $stop = false;
        $data = $this->app->format->strip_unsafe($data);
        if ($adm == false) {

            $stop = true;
            $author_id = user_id();
            if (isset($data['id']) and $data['id'] != 0 and $author_id != 0) {
                $page_data_to_check_author = $this->get_by_id($data['id']);
                if (!isset($page_data_to_check_author['created_by']) or ($page_data_to_check_author['created_by'] != $author_id)) {
                    $stop = true;
                    return array('error' => "You don't have permission to edit this content");
                } else if (isset($page_data_to_check_author['created_by']) and ($page_data_to_check_author['created_by'] == $author_id)) {
                    $stop = false;
                }
            } elseif ($author_id == false) {
                return array('error' => "You must be logged to save content");

            }

            if (isset($data['is_home'])) {
                unset($data['is_home']);
            }

            if ($stop == true) {
                if (defined('MW_API_FUNCTION_CALL') and MW_API_FUNCTION_CALL == __FUNCTION__) {

                    if (!isset($data['captcha'])) {
                        if (isset($data['error_msg'])) {
                            return array('error' => $data['error_msg']);
                        } else {
                            return array('error' => 'Please enter a captcha answer!');

                        }
                    } else {
                        $cap = $this->app->user_manager->session_get('captcha');
                        if ($cap == false) {
                            return array('error' => 'You must load a captcha first!');
                        }
                        if ($data['captcha'] != $cap) {
                            return array('error' => 'Invalid captcha answer!');
                        }
                    }
                }
            }


            if (isset($data['categories'])) {
                $data['category'] = $data['categories'];
            }
            //if (defined('MW_API_FUNCTION_CALL') and MW_API_FUNCTION_CALL == __FUNCTION__) {
            if (isset($data['category'])) {
                $cats_check = array();
                if (is_array($data['category'])) {
                    foreach ($data['category'] as $cat) {
                        $cats_check[] = intval($cat);
                    }
                } else {
                    $cats_check[] = intval($data['category']);
                }
                $check_if_user_can_publish = $this->app->category_manager->get('ids=' . implode(',', $cats_check));
                if (!empty($check_if_user_can_publish)) {
                    $user_cats = array();
                    foreach ($check_if_user_can_publish as $item) {
                        if (isset($item["users_can_create_content"]) and $item["users_can_create_content"] == 1) {
                            $user_cats[] = $item["id"];
                            $cont_cat = $this->get('limit=1&content_type=page&subtype_value=' . $item["id"]);
                        }
                    }

                    if (!empty($user_cats)) {
                        $stop = false;
                        $data['categories'] = $user_cats;

                    }
                }
            }
        }
        // }


        if ($stop == true) {
            return array('error' => 'You don\'t have permissions to save content here!');
        }

        return $this->save_content($data, $delete_the_cache);

    }

    public function custom_fields($content_id, $full = true, $field_type = false)
    {

        return $this->app->fields_manager->get('content', $content_id, $full, false, false, $field_type);


    }


// ------------------------------------------------------------------------

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
                    }
                }
            }
        }
    }

    public function get_parents($id = 0, $without_main_parrent = false)
    {

        if (intval($id) == 0) {
            return FALSE;
        }

        $table = $this->tables['content'];

        $ids = array();

        $data = array();


        $get = array();
        $get['id'] = $id;

        if (isset($without_main_parrent) and $without_main_parrent == true) {
            $get['parent'] = '[neq]0';
        }
        $content_parents = $this->get($get);

        if (!empty($content_parents)) {

            foreach ($content_parents as $item) {

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

    public function  save_content_field($data, $delete_the_cache = true)
    {

        $adm = $this->app->user_manager->is_admin();
        $table = $this->tables['content_fields'];
        $table_drafts = $this->tables['content_fields_drafts'];


        if ($adm == false) {
            return false;
        }

        if (!is_array($data)) {
            $data = array();
        }

        if (isset($data['is_draft'])) {
            $table = $table_drafts;


        }

        $data = $this->app->format->strip_unsafe($data);

        if (isset($data['is_draft']) and isset($data['url'])) {

            $draft_url = $this->app->database_manager->escape_string($data['url']);
            $last_saved_date = date("Y-m-d H:i:s", strtotime("-5 minutes"));
            $last_saved_date = date("Y-m-d H:i:s", strtotime("-1 week"));

            $history_files_params = array();
            $history_files_params['order_by'] = 'id desc';
            $history_files_params['fields'] = 'id';
            $history_files_params['field'] = $data['field'];
            $history_files_params['rel_type'] = $data['rel_type'];
            $history_files_params['rel_id'] = $data['rel_id'];

            $history_files_params['is_draft'] = 1;
            $history_files_params['limit'] = 20;
            $history_files_params['url'] = $draft_url;
            $history_files_params['current_page'] = 2;
            $history_files_params['created_at'] = '[lt]' . $last_saved_date;


            // $history_files_params['created_at'] = '[mt]' . $last_saved_date;
            $history_files = $this->edit_field($history_files_params);
            //
            // $history_files = $this->edit_field('order_by=id desc&fields=id&is_draft=1&all=1&limit=50&curent_page=1&url=' . $draft_url . '&created_at=[mt]' . $last_saved_date . '');
            if (is_array($history_files)) {
                $history_files_ids = $this->app->format->array_values($history_files);
            }

            if (isset($history_files_ids) and is_array($history_files_ids) and !empty($history_files_ids)) {
                ContentFields::whereIn('id', $history_files_ids)->delete();


            }


        }


        if (!isset($data['rel_type']) or !isset($data['rel_id'])) {
            mw_error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }

        //if($data['rel_type'] == 'global'){
        if (isset($data['field']) and !isset($data['is_draft'])) {
            $fld = $this->app->database_manager->escape_string($data['field']);
            $fld_rel = $this->app->database_manager->escape_string($data['rel_type']);
            $del_params = array();
            $del_params['rel_type'] = $fld_rel;
            $del_params['field'] = $fld;
            $del_params['table'] = $table;
            // $del = ContentFields::where('rel_type', $fld_rel)               ->where('field', $fld);


            if (isset($data['rel_id'])) {
                $i = ($data['rel_id']);
                $del_params['rel_id'] = $i;
                // $del->where('rel_id', $i);
            } else {
                $del_params['rel_id'] = 0;
                // $del->where('rel_id', 0);
            }
            $del = $this->app->database->get($del_params);
            if (!empty($del)) {
                foreach ($del as $item) {
                    $this->app->database->delete_by_id($table, $item['id']);
                }
            }

            // $del = $del->delete();

            $cache_group = guess_cache_group('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete($cache_group);


        }
        if (isset($fld)) {

            $this->app->cache_manager->delete('content_fields/' . $fld);
            $this->app->cache_manager->delete('content_fields/global/' . $fld);


        }
        $this->app->cache_manager->delete('content_fields/global');
        if (isset($data['rel_type']) and isset($data['rel_id'])) {
            $cache_group = guess_cache_group('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete($cache_group);


            $this->app->cache_manager->delete('content/' . $data['rel_id']);

        }
        if (isset($data['rel_type'])) {
            $this->app->cache_manager->delete('content_fields/' . $data['rel_type']);
        }
        if (isset($data['rel_type']) and isset($data['rel_id'])) {
            $this->app->cache_manager->delete('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete('content_fields/global/' . $data['rel_type'] . '/' . $data['rel_id']);
        }
        if (isset($data['field'])) {
            $this->app->cache_manager->delete('content_fields/' . $data['field']);
        }

        $this->app->cache_manager->delete('content_fields/global');

        $data['allow_html'] = true;
        $data['table'] = $table;


        $save = $this->app->database->save($data);

        $this->app->cache_manager->delete('content_fields');

        return $save;


    }


    public function copy($data)
    {
        $new_cont_id = false;

        if (defined('MW_API_CALL')) {
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm == false) {
                return array('error' => 'You must be admin to copy content!');
            }
        }
        if (isset($data['id'])) {
            $this->app->event_manager->trigger('content.before.copy', $data);
            $cont = get_content_by_id($data['id']);
            if ($cont != false and isset($cont['id'])) {
                $new_cont = $cont;
                if (isset($new_cont['title'])) {
                    $new_cont['title'] = $new_cont['title'] . ' copy';
                }

                $new_cont['id'] = 0;
                $content_cats = array();

                $cats = content_categories($cont['id']);
                if (!empty($cats)) {
                    foreach ($cats as $cat) {
                        if (isset($cat['id'])) {
                            $content_cats[] = $cat['id'];
                        }
                    }
                }
                if (!empty($content_cats)) {
                    $new_cont['categories'] = $content_cats;
                }
                $new_cont_id = $this->save($new_cont);


                $cust_fields = get_custom_fields('content', $data['id'], true);
                if (!empty($cust_fields)) {
                    foreach ($cust_fields as $cust_field) {
                        $new = $cust_field;
                        $new['id'] = 0;
                        $new['rel_id'] = $new_cont_id;
                        $new_item = save_custom_field($new);
                    }
                }
                $images = get_pictures($data['id']);
                if (!empty($images)) {
                    foreach ($images as $image) {
                        $new = $image;
                        $new['id'] = 0;
                        $new['rel_id'] = $new_cont_id;
                        $new['rel_type'] = 'content';
                        $new_item = save_media($new);

                    }
                }


            }
        }
        return $new_cont_id;

    }

    public function reset_edit($data)
    {
        if (defined('MW_API_CALL')) {
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm == false) {
                return array('error' => 'You must be admin to reset content!');
            }
        }
        if (isset($data['id'])) {
            $cont = get_content_by_id($data['id']);
            if (isset($cont['id']) and $cont['id'] != 0) {
                $id = intval($cont['id']);
                $cont['content'] = false;
                $cont['content_body'] = false;
                $save = $this->save($cont);

                $table_fields = $this->app->database_manager->real_table_name($this->tables['content_fields']);
                $del = "DELETE FROM {$table_fields} WHERE rel_type='content' AND rel_id='{$id}' ";
                $this->app->database->query($del);
                $this->app->cache_manager->delete('content');
                $this->app->cache_manager->delete('content_fields');
                return $save;
            }

        }

    }

    public function save($data, $delete_the_cache = true)
    {
        return $this->save_content($data, $delete_the_cache);
    }

    public function delete($data)
    {
        $to_trash = false;
        $to_untrash = false;

        if (defined('MW_API_CALL')) {
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm == false) {
                return array('error' => 'You must be admin to delete content!');
            }
        }

        if (!is_array($data)) {
            $del_data = array();
            $del_data['id'] = intval($data);
            $data = $del_data;
            $to_trash = false;
        }

        if (isset($data['forever']) or isset($data['delete_forever'])) {
            $to_trash = false;
        }

        if (isset($data['undelete'])) {
            $to_trash = true;
            $to_untrash = true;
        }

        $del_ids = array();
        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $del_ids[] = $c_id;
            if ($to_trash == false) {
                $this->app->database_manager->delete_by_id('content', $c_id);
            }
        }
        $this->app->event_manager->trigger('content.before.delete', $data);

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $del_ids[] = $c_id;
                if ($to_trash == false) {
                    $this->app->database_manager->delete_by_id('content', $c_id);
                }
            }

        }

        if (!empty($del_ids)) {
            $table = $this->app->database_manager->real_table_name($this->tables['content']);
            foreach ($del_ids as $value) {
                $c_id = intval($value);
                if ($to_untrash == true) {
                    $q = "UPDATE $table SET is_deleted=0 WHERE id=$c_id AND  is_deleted=1 ";
                    $this->app->database->query($q);
                    $q = "UPDATE $table SET is_deleted=0 WHERE parent=$c_id   AND  is_deleted=1 ";
                    $this->app->database->query($q);
                    if (isset($this->tables['categories'])) {
                        $table1 = $this->tables['categories'];
                        $table1 = $this->app->database_manager->real_table_name($table1);
                        $q = "UPDATE $table1 SET is_deleted=0 WHERE rel_id=$c_id  AND  rel_type='content' AND  is_deleted=1 ";
                        $this->app->database->query($q);
                    }

                } else if ($to_trash == false) {
                    $q = "UPDATE $table SET parent=0 WHERE parent=$c_id ";
                    $q = $this->app->database->query($q);

                    $this->app->database_manager->delete_by_id('menus', $c_id, 'content_id');

                    if (isset($this->tables['media'])) {
                        $delete_in_table = $this->tables['media'];
                        $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                        $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                        $this->app->database->query($q);
                    }

                    if (isset($this->tables['categories'])) {
                        $delete_in_table = $this->tables['categories'];
                        $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                        $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                        $this->app->database->query($q);
                    }


                    if (isset($this->tables['categories_items'])) {
                        $delete_in_table = $this->tables['categories_items'];
                        $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                        $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                        $this->app->database->query($q);
                    }
                    if (isset($this->tables['custom_fields'])) {
                        $delete_in_table = $this->tables['custom_fields'];
                        $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                        $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                        $this->app->database->query($q);
                    }

                    if (isset($this->tables['content_data'])) {
                        $delete_in_table = $this->tables['content_data'];
                        $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                        $q = "DELETE FROM $delete_in_table WHERE content_id=$c_id    ";
                        $this->app->database->query($q);
                    }


                } else {
                    $q = "UPDATE $table SET is_deleted=1 WHERE id=$c_id ";
                    $this->app->database->query($q);
                    $q = "UPDATE $table SET is_deleted=1 WHERE parent=$c_id ";
                    $this->app->database->query($q);
                    if (isset($this->tables['categories'])) {
                        $table1 = $this->tables['categories'];
                        $table1 = $this->app->database_manager->real_table_name($table1);
                        $q = "UPDATE $table1 SET is_deleted=1 WHERE rel_id=$c_id  AND  rel_type='content' AND  is_deleted=0 ";
                        $this->app->database->query($q);
                    }
                }
                $this->app->cache_manager->delete('content/' . $c_id);
            }
            $this->app->cache_manager->delete('menus');
            $this->app->cache_manager->delete('content');
            $this->app->cache_manager->delete('categories/global');
            $this->app->cache_manager->delete('content/global');

        }
        return ($del_ids);
    }

    public function edit_field_draft($data)
    {
        only_admin_access();

        $page = false;
        if (isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == $this->app->url_manager->site()) {
                //$page = $this->get_by_url($url);
                $page = $this->homepage();
                // var_dump($page);
            } else {

                $page = $this->get_by_url($url);
            }
        } else {
            $url = $this->app->url_manager->string();
        }

        $this->define_constants($page);


        $table_drafts = $this->tables['content_fields_drafts'];


        $data = parse_params($data);

        if (isset($data['id']) and $data['id'] == 'latest_content_edit') {

            if (isset($page['id'])) {
                $page_data = $this->get_by_id($page['id']);

                $results = array();
                if (isset($page_data['title'])) {
                    $arr = array('rel_type' => 'content',
                        'field' => 'title',
                        'value' => $page_data['title']);
                    $results[] = $arr;
                    if (isset($page_data['content_type'])) {
                        $arr = array('rel_type' => $page_data['content_type'],
                            'field' => 'title',
                            'value' => $page_data['title']);
                        $results[] = $arr;
                    }
                    if (isset($page_data['subtype'])) {
                        $arr = array('rel_type' => $page_data['subtype'],
                            'field' => 'title',
                            'value' => $page_data['title']);
                        $results[] = $arr;
                    }
                }
                if (isset($page_data['content']) and $page_data['content'] != '') {
                    $arr = array('rel_type' => 'content',
                        'field' => 'content',
                        'value' => $page_data['content']);
                    $results[] = $arr;
                    if (isset($page_data['content_type'])) {
                        $arr = array('rel_type' => $page_data['content_type'],
                            'field' => 'content',
                            'value' => $page_data['content']);
                        $results[] = $arr;
                    }
                    if (isset($page_data['subtype'])) {
                        $arr = array('rel_type' => $page_data['subtype'],
                            'field' => 'content',
                            'value' => $page_data['content']);
                        $results[] = $arr;
                    }
                }
                //$results[]

            }


        } else {
            $data['is_draft'] = 1;
            $data['full'] = 1;
            $data['all'] = 1;
            $results = $this->edit_field($data);
        }


        $ret = array();


        if ($results == false) {
            return;
        }

        $i = 0;
        foreach ($results as $item) {


            if (isset($item['value'])) {
                $field_content = htmlspecialchars_decode($item['value']);
                $field_content = $this->_decode_entities($field_content);
                $item['value'] = mw()->parser->process($field_content, $options = false);

            }

            $ret[$i] = $item;
            $i++;

        }


        return $ret;


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
     *
     *
     *
     * @param array|bool $content
     * @option integer  "id"   [description]
     * @option  string "content_type" [description]
     * @return void
     */
    public function define_constants($content = false)
    {
        if ($content == false) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $ref_page = $_SERVER['HTTP_REFERER'];
                if ($ref_page != '') {
                    $ref_page = $this->get_by_url($ref_page);
                    if ($ref_page != false and !empty($ref_page)) {
                        $content = $ref_page;
                    }
                }
            }
        }

        $page = false;
        if (is_array($content)) {
            if (!isset($content['active_site_template']) and isset($content['id']) and $content['id'] != 0) {
                $content = $this->get_by_id($content['id']);
                $page = $content;

            } else if (isset($content['id']) and $content['id'] == 0) {
                $page = $content;
            } else if (isset($content['active_site_template'])) {
                $page = $content;
            }

            if ($page == false) {
                $page = $content;
            }

        }

        if (is_array($page)) {
            if (isset($page['content_type']) and $page['content_type'] == "post") {


                if (isset($page['id']) and $page['id'] != 0) {
                    $content = $page;


                    $current_categorys = $this->app->category_manager->get_for_content($page['id']);
                    if (!empty($current_categorys)) {
                        //d($current_categorys);
                        $current_category = end($current_categorys);

                        if (defined('CATEGORY_ID') == false and isset($current_category['id'])) {
                            define('CATEGORY_ID', $current_category['id']);
                        }


                    }

                    $page = $this->get_by_id($page['parent']);

                    if (defined('POST_ID') == false) {
                        define('POST_ID', $content['id']);
                    }

                }


            } else {
                $content = $page;
                if (defined('POST_ID') == false) {
                    define('POST_ID', false);
                }
            }

            if (defined('ACTIVE_PAGE_ID') == false) {
                if (!isset($page['id'])) {
                    $page['id'] = 0;
                }
                define('ACTIVE_PAGE_ID', $page['id']);
            }


            if (!defined('CATEGORY_ID')) {
                //define('CATEGORY_ID', $current_category['id']);
            }

            if (defined('CATEGORY_ID') == false) {
                $cat_url = $this->app->url_manager->param('category', $skip_ajax = true);
                if ($cat_url != false) {
                    define('CATEGORY_ID', intval($cat_url));
                }
            }
            if (!defined('CATEGORY_ID')) {
                define('CATEGORY_ID', false);
            }

            if (defined('CONTENT_ID') == false and isset($content['id'])) {
                define('CONTENT_ID', $content['id']);
            }

            if (defined('PAGE_ID') == false and isset($content['id'])) {
                define('PAGE_ID', $page['id']);
            }


            if (isset($page['parent'])) {


                $parent_page_check_if_inherited = $this->get_by_id($page['parent']);

                if (isset($parent_page_check_if_inherited["layout_file"]) and $parent_page_check_if_inherited["layout_file"] == 'inherit') {

                    $inherit_from_id = $this->get_inherited_parent($parent_page_check_if_inherited["id"]);

                    if (defined('MAIN_PAGE_ID') == false) {
                        define('MAIN_PAGE_ID', $inherit_from_id);
                    }

                }

                //$root_parent = $this->get_inherited_parent($page['parent']);

                //  $this->get_inherited_parent($page['id']);
                // if ($par_page != false) {
                //  $par_page = $this->get_by_id($page['parent']);
                //  }
                if (defined('ROOT_PAGE_ID') == false) {

                    $root_page = $this->get_parents($page['id']);
                    if (!empty($root_page) and isset($root_page[0])) {
                        $root_page[0] = end($root_page);
                    } else {
                        $root_page[0] = $page['parent'];
                    }

                    define('ROOT_PAGE_ID', $root_page[0]);
                }

                if (defined('MAIN_PAGE_ID') == false) {
                    if ($page['parent'] == 0) {
                        define('MAIN_PAGE_ID', $page['id']);
                    } else {
                        define('MAIN_PAGE_ID', $page['parent']);
                    }

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

        if (isset($content) and isset($content['active_site_template']) and ($content['active_site_template']) != '' and strtolower($page['active_site_template']) != 'inherit' and strtolower($page['active_site_template']) != 'default') {

            $the_active_site_template = $content['active_site_template'];
        } else if (isset($page) and isset($page['active_site_template']) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {

            $the_active_site_template = $page['active_site_template'];
        } else if (isset($content) and isset($content['active_site_template']) and ($content['active_site_template']) != '' and strtolower($content['active_site_template']) != 'default') {

            $the_active_site_template = $content['active_site_template'];
        } else {
            $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
            //
        }


        if (isset($content['parent']) and $content['parent'] != 0 and isset($content['layout_file']) and $content['layout_file'] == 'inherit') {
            $inh = $this->get_inherited_parent($content['id']);
            if ($inh != false) {
                $inh_parent = $this->get_by_id($inh);
                if (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) != 'default') {

                    $the_active_site_template = $inh_parent['active_site_template'];
                } else if (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) == 'default') {

                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                } else if (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) == '') {

                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                }

            }

        }


        if (isset($the_active_site_template) and $the_active_site_template != 'default' and $the_active_site_template == 'mw_default') {
            $the_active_site_template = 'default';
        }

        if ($the_active_site_template == false) {
            $the_active_site_template = 'default';
        }

        if (defined('THIS_TEMPLATE_DIR') == false and $the_active_site_template != false) {

            define('THIS_TEMPLATE_DIR', templates_path() . $the_active_site_template . DS);

        }

        if (defined('THIS_TEMPLATE_FOLDER_NAME') == false and $the_active_site_template != false) {

            define('THIS_TEMPLATE_FOLDER_NAME', $the_active_site_template);

        }

        $the_active_site_template_dir = normalize_path(templates_path() . $the_active_site_template . DS);

        if (defined('DEFAULT_TEMPLATE_DIR') == false) {

            define('DEFAULT_TEMPLATE_DIR', templates_path() . 'default' . DS);
        }

        if (defined('DEFAULT_TEMPLATE_URL') == false) {

            define('DEFAULT_TEMPLATE_URL', templates_url() . '/default/');
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
                                $template_view = templates_path() . $page['active_site_template'] . DS . $page['layout_file'];


                            }

                        }
                    }

                    if (is_file($template_view) == true) {

                        if (defined('THIS_TEMPLATE_DIR') == false) {
                            define('THIS_TEMPLATE_DIR', templates_path() . $the_active_site_template . DS);
                        }

                        if (defined('THIS_TEMPLATE_URL') == false) {
                            $the_template_url = templates_url() . '/' . $the_active_site_template;
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
            $the_template_url = templates_url() . '/' . $the_active_site_template;

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

            define('TEMPLATES_DIR', templates_path());
        }

        $the_template_url = templates_url() . $the_active_site_template;

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

            $layouts_url = reduce_double_slashes($this->app->url_manager->link_to_file($layouts_dir) . '/');

            define("LAYOUTS_URL", $layouts_url);
        }


        return true;
    }

    public function _decode_entities($text)
    {

        $text = html_entity_decode($text, ENT_QUOTES, "ISO-8859-1"); #NOTE: UTF-8 does not work!
        $text = preg_replace('/&#(\d+);/me', "chr(\\1)", $text); #decimal notation
        $text = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $text); #hex notation
        return $text;
    }

    public function prev_content($content_id = false)
    {
        return $this->next_content($content_id, $mode = 'prev');

    }

    public function next_content($content_id = false, $mode = 'next')
    {
        if ($content_id == false) {
            if (defined('POST_ID') and POST_ID != 0) {
                $content_id = POST_ID;
            } else if (defined('PAGE_ID') and PAGE_ID != 0) {
                $content_id = PAGE_ID;
            } else if (defined('MAIN_PAGE_ID') and MAIN_PAGE_ID != 0) {
                $content_id = MAIN_PAGE_ID;
            }
        }
        $category_id = false;
        if (defined('CATEGORY_ID') and CATEGORY_ID != 0) {
            $category_id = CATEGORY_ID;
        }
        if ($content_id == false) {
            return false;
        } else {
            $content_id = intval($content_id);
        }
        $cont_data = $this->get_by_id($content_id);
        if ($cont_data == false) {
            return false;
        }
        $categories = array();
        $params = array();

        if (isset($cont_data['parent']) and $cont_data['parent'] > 0) {
            $params['parent'] = $cont_data['parent'];
        }

        $compare_q = '[lt]';
        if (trim($mode) == 'prev') {
            $compare_q = '[mt]';
        }
        if (isset($cont_data['content_type'])) {
            $params['content_type'] = $cont_data['content_type'];
        }

        if (isset($cont_data['content_type']) and $cont_data['content_type'] != 'page') {
            $compare_q = '[mt]';
            $params['order_by'] = 'created_at asc';
            $params['order_by'] = 'position asc, created_at asc';
            $params['order_by'] = 'position asc';
            if (trim($mode) == 'prev') {
                $compare_q = '[lt]';
                $params['order_by'] = 'position desc, created_at desc';
                $params['order_by'] = 'position desc';
            }
            $cats = $this->app->category_manager->get_for_content($content_id);
            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    $categories[] = $cat['id'];
                }
            } else {
                if ($category_id != false) {
                    //$categories[] = $category_id;
                }
            }
            $params['position'] = $compare_q . $cont_data['position'];

            //  $params['created_at'] = $compare_q . $cont_data['created_at'];
        } else {
            if (isset($cont_data['position']) and $cont_data['position'] > 0) {
                $params['position'] = $compare_q . $cont_data['position'];

            }
            $params['order_by'] = 'created_at asc';
            if (trim($mode) == 'prev') {
                $params['order_by'] = 'created_at desc';
            }
        }

        if (!empty($categories)) {
            $params['category'] = $categories;
        }

        $params['limit'] = 1;
        $params['exclude_ids'] = array($content_id);
        $params['is_active'] = 1;
        $params['is_deleted'] = 0;
        $params['single'] = true;
        $q = $this->get($params);
        if (is_array($q)) {
            return $q;
        } else {
            if (isset($params['created_at'])) {
                unset($params['created_at']);
            }
            $q = $this->get($params);
            if (!is_array($q)) {
                if (isset($params['category'])) {
                    unset($params['category']);
                    $q = $this->get($params);
                }
            }
            if (is_array($q)) {
                return $q;
            }
            return false;
        }
    }

    public function reorder($params)
    {
        $id = $this->app->user_manager->is_admin();
        if ($id == false) {
            return ('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $ids = $params['ids'];
        if (empty($ids)) {
            $ids = $_POST[0];
        }
        if (empty($ids)) {
            return false;
        }
        $ids = array_unique($ids);

        $ids_implode = implode(',', $ids);
        $ids_implode = $this->app->database_manager->escape_string($ids_implode);


        $table = $this->app->database_manager->real_table_name($this->tables['content']);
        $maxpos = 0;
        $get_max_pos = "SELECT max(position) AS maxpos FROM $table  WHERE id IN ($ids_implode) ";
        $get_max_pos = $this->app->database->query($get_max_pos);
        if (is_array($get_max_pos) and isset($get_max_pos[0]['maxpos'])) {

            $maxpos = intval($get_max_pos[0]['maxpos']) + 1;

        }

        // $q = " SELECT id, created_at, position from $table where id IN ($ids_implode)  order by position desc  ";
        // $q = $this->app->database->query($q);
        // $max_date = $q[0]['created_at'];
        // $max_date_str = strtotime($max_date);
        $i = 1;
        foreach ($ids as $id) {
            $id = intval($id);
            $this->app->cache_manager->delete('content/' . $id);
            //$max_date_str = $max_date_str - $i;
            //	$nw_date = date('Y-m-d H:i:s', $max_date_str);
            //$q = " UPDATE $table set created_at='$nw_date' where id = '$id'    ";
            $pox = $maxpos - $i;
            $q = " UPDATE $table SET position=$pox WHERE id=$id   ";
            //    var_dump($q);
            $q = $this->app->database->q($q);
            $i++;
        }
        //
        // var_dump($q);
        $this->app->cache_manager->delete('content/global');
        $this->app->cache_manager->delete('categories/global');
        return true;
    }

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
     * @uses $this->save_content()
     * @see  content_set_unpublished()
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
    public function set_unpublished($params)
    {

        if (intval($params) > 0 and !isset($params['id'])) {
            if (!is_array($params)) {
                $id = $params;
                $params = array();
                $params['id'] = $id;
            }
        }
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return array('error' => 'You must be admin to unpublish content!');
        }

        if (!isset($params['id'])) {
            return array('error' => 'You must provide id parameter!');
        } else {
            if (intval($params['id'] != 0)) {
                $save = array();
                $save['id'] = intval($params['id']);
                $save['is_active'] = 0;

                $save_data = $this->save_content($save);
                return ($save_data);
            }
        }

    }

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
     * @uses $this->save_content()
     * @example
     * <code>
     * //set published the content with id 5
     * api/content/set_published(5);
     *
     * //alternative way
     * api/content/set_published(array('id' => 5));
     * </code>
     *
     */
    public function set_published($params)
    {

        if (intval($params) > 0 and !isset($params['id'])) {
            if (!is_array($params)) {
                $id = $params;
                $params = array();
                $params['id'] = $id;
            }
        }
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            return array('error' => 'You must be admin to publish content!');
        }


        if (!isset($params['id'])) {
            return array('error' => 'You must provide id parameter!');
        } else {
            if (intval($params['id'] != 0)) {

                $save = array();
                $save['id'] = intval($params['id']);
                $save['is_active'] = 1;

                $save_data = $this->save_content($save);
                return ($save_data);
            }

        }
    }

    public function save_content($data, $delete_the_cache = true)
    {

        if (is_string($data)) {
            $data = parse_params($data);
        }


        $mw_global_content_memory = array();
        $adm = $this->app->user_manager->is_admin();
        $table = $this->tables['content'];

        $table_data = $this->tables['content_data'];

        $checks = mw_var('FORCE_SAVE_CONTENT');
        $orig_data = $data;
        $stop = false;


        if ($stop == true) {
            return array('error' => 'You are not logged in as admin to save content!');
        }

        $cats_modified = false;


        if (!empty($data)) {
            if (!isset($data['id'])) {
                $data['id'] = 0;
            }

            if ($data['id'] == 0 and !isset($data['is_active'])) {
                $data['is_active'] = 1;
            }

            $this->app->event_manager->trigger('content.before.save', $data);
            if (intval($data['id']) == 0) {
                if (isset($data['subtype']) and $data['subtype'] == 'post' and !isset($data['content_type'])) {
                    $data['subtype'] = 'post';
                    $data['content_type'] = 'post';
                }
                if (!isset($data['subtype'])) {
                    $data['subtype'] = 'post';
                }
                if (!isset($data['content_type'])) {
                    $data['content_type'] = 'post';
                }
            }
        }

        if (isset($data['content_url']) and !isset($data['url'])) {
            $data['url'] = $data['content_url'];
        }

        if (!isset($data['parent']) and isset($data['content_parent'])) {
            $data['parent'] = $data['content_parent'];
        }

        if (isset($data['is_active'])) {
            if ($data['is_active'] == 'y') {
                $data['is_active'] = 1;
            } elseif ($data['is_active'] == 'n') {
                $data['is_active'] = 0;
            }
        }


        $data_to_save = $data;
        if (!isset($data['title']) and isset($data['content_title'])) {
            $data['title'] = $data['content_title'];
        }
        if (isset($data['title'])) {
            $data['title'] = html_entity_decode($data['title']);
            $data['title'] = strip_tags($data['title']);
            $data['title'] = $this->app->format->clean_html($data['title']);

            $data['title'] = preg_replace("/(^\s+)|(\s+$)/us", "", $data['title']);
            $data_to_save['title'] = $data['title'];
        }


        if (!isset($data['url']) and intval($data['id']) != 0) {


            $q = $this->get_by_id($data_to_save['id']);


            $thetitle = $q['title'];
            $q = $q['url'];
            $theurl = $q;
        } else {
            if (isset($data['url'])) {
                $theurl = $data['url'];
            } else {
                $theurl = $data['title'];
            }
            $thetitle = $data['title'];
        }


        if (isset($data['id']) and intval($data['id']) == 0) {

            if (!isset($data['is_deleted']) or ($data['is_deleted']) == '') {
                $data_to_save['is_deleted'] = 0;
            } else {
                $data_to_save['is_deleted'] = $data['is_deleted'];
            }

            if (!isset($data['title']) or ($data['title']) == '') {
                $data['title'] = "New page";
                if (isset($data['content_type']) and ($data['content_type']) != 'page') {
                    $data['title'] = "New " . $data['content_type'];
                    if (isset($data['subtype']) and ($data['subtype']) != 'page' and ($data['subtype']) != 'post' and ($data['subtype']) != 'static' and ($data['subtype']) != 'dynamic') {
                        $data['title'] = "New " . $data['subtype'];
                    }
                }
                $data_to_save['title'] = $data['title'];
            }
        }

        if (isset($data['url']) == false or $data['url'] == '') {
            if (isset($data['title']) != false and intval($data ['id']) == 0) {
                $data['url'] = $this->app->url_manager->slug($data['title']);
            }
        }
        $url_changed = false;

        if (isset($data['url']) != false and is_string($data['url'])) {

            $search_weird_chars = array('%E2%80%99',
                '%E2%80%99',
                '%E2%80%98',
                '%E2%80%9C',
                '%E2%80%9D'
            );
            $str = $data['url'];
            $good[] = 9; #tab
            $good[] = 10; #nl
            $good[] = 13; #cr
            for ($a = 32; $a < 127; $a++) {
                $good[] = $a;
            }
            $newstr = '';
            $len = strlen($str);
            for ($b = 0; $b < $len + 1; $b++) {
                if (isset($str[$b]) and in_array(ord($str[$b]), $good)) {
                    $newstr .= $str[$b];
                }

            }

            $newstr = str_replace('--', '-', $newstr);
            $newstr = str_replace('--', '-', $newstr);
            if ($newstr == '-' or $newstr == '--') {
                $newstr = 'post-' . date('YmdHis');
            }
            $data['url'] = $newstr;

            $url_changed = true;
            $data_to_save['url'] = $data['url'];

        }


        if (isset($data['category']) or isset($data['categories'])) {
            $cats_modified = true;
        }
        $table_cats = $this->tables['categories'];

        if (isset($data_to_save['title']) and ($data_to_save['title'] != '') and (!isset($data['url']) or trim($data['url']) == '')) {
            $data['url'] = $this->app->url_manager->slug($data_to_save['title']);
        }


        if (isset($data['url']) and $data['url'] != false) {

            if (trim($data['url']) == '') {

                $data['url'] = $this->app->url_manager->slug($data['title']);
            }

            $data['url'] = $this->app->database_manager->escape_string($data['url']);


            $date123 = date("YmdHis");


            $get = array();
            $get['url'] = $data['url'];
            $get['single'] = true;
            $q = $this->get($get);
            // $q = Content::where('url', $data['url'])->first();;


            if (!empty($q)) {


                if ($data['id'] != $q['id']) {

                    $data['url'] = $data['url'] . '-' . $date123;
                    $data_to_save['url'] = $data['url'];

                }
            }

            if (isset($data_to_save['url']) and strval($data_to_save['url']) == '' and (isset($data_to_save['quick_save']) == false)) {

                $data_to_save['url'] = $data_to_save['url'] . '-' . $date123;
            }

            if (isset($data_to_save['title']) and strval($data_to_save['title']) == '' and (isset($data_to_save['quick_save']) == false)) {

                $data_to_save['title'] = 'post-' . $date123;
            }
            if (isset($data_to_save['url']) and strval($data_to_save['url']) == '' and (isset($data_to_save['quick_save']) == false)) {
                $data_to_save['url'] = strtolower(reduce_double_slashes($data['url']));
            }

        }


        if (isset($data_to_save['url']) and is_string($data_to_save['url'])) {
            $data_to_save['url'] = str_replace(site_url(), '', $data_to_save['url']);
        }


        $data_to_save_options = array();
        if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'y') {
            $data_to_save['is_home'] = 1;
        } elseif (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'n') {
            $data_to_save['is_home'] = 0;
        }


        if (isset($data_to_save['is_shop']) and $data_to_save['is_shop'] == 'y') {
            $data_to_save['is_shop'] = 1;
        } elseif (isset($data_to_save['is_shop']) and $data_to_save['is_shop'] == 'n') {
            $data_to_save['is_shop'] = 0;
        }

        if (isset($data_to_save['require_login']) and $data_to_save['require_login'] == 'y') {
            $data_to_save['require_login'] = 1;
        } elseif (isset($data_to_save['require_login']) and $data_to_save['require_login'] == 'n') {
            $data_to_save['require_login'] = 0;
        }

        if (isset($data_to_save['is_active']) and $data_to_save['is_active'] == 'y') {
            $data_to_save['is_active'] = 1;
        } elseif (isset($data_to_save['is_active']) and $data_to_save['is_active'] == 'n') {
            $data_to_save['is_active'] = 0;
        }


        if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 1) {
            if ($adm == true) {
                $q = Content::where('is_home', 1)
                    ->update(array(
                        'is_home' => 0,
                    ));
            } else {
                $data_to_save['is_home'] = 0;
            }
        }

        if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'post') {
            if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'static') {
                $data_to_save['subtype'] = 'post';
            } else if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
                $data_to_save['subtype'] = 'post';
            }
        }

        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $check_ex = false;
            if (isset($data_to_save['subtype_value']) and trim($data_to_save['subtype_value']) != '' and intval(($data_to_save['subtype_value'])) > 0) {

                $check_ex = $this->app->category_manager->get_by_id(intval($data_to_save['subtype_value']));
            }
            if ($check_ex == false) {
                if (isset($data_to_save['id']) and intval(trim($data_to_save['id'])) > 0) {
                    $test2 = $this->app->category_manager->get('data_type=category&rel_type=content&rel_id=' . intval(($data_to_save['id'])));
                    if (isset($test2[0])) {
                        $check_ex = $test2[0];
                        $data_to_save['subtype_value'] = $test2[0]['id'];
                    }
                }
                unset($data_to_save['subtype_value']);
            }


            if (isset($check_ex) and $check_ex == false) {

                if (!isset($data_to_save['subtype_value_new'])) {
                    if (isset($data_to_save['title'])) {
                        //$cats_modified = true;
                        //$data_to_save['subtype_value_new'] = $data_to_save['title'];
                    }
                }
            }
        }


        $par_page = false;
        if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'post') {
            if (isset($data_to_save['parent']) and intval($data_to_save['parent']) > 0) {
                $par_page = $this->get_by_id($data_to_save['parent']);
            }


            if (is_array($par_page)) {
                $change_to_dynamic = true;
                if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 1) {
                    $change_to_dynamic = false;
                }
                if ($change_to_dynamic == true and $par_page['subtype'] == 'static') {
                    $par_page_new = array();
                    $par_page_new['id'] = $par_page['id'];
                    $par_page_new['subtype'] = 'dynamic';

                    $par_page_new = $this->app->database->save($table, $par_page_new);
                    $cats_modified = true;
                }
                if (!isset($data_to_save['categories'])) {
                    $data_to_save['categories'] = '';
                }
                if (is_string($data_to_save['categories']) and isset($par_page['subtype_value']) and intval($par_page['subtype_value']) != 0) {
                    $data_to_save['categories'] = $data_to_save['categories'] . ', ' . intval($par_page['subtype_value']);
                }
            }
            $c1 = false;
            if (isset($data_to_save['category']) and !isset($data_to_save['categories'])) {
                $data_to_save['categories'] = $data_to_save['category'];
            }


            if (isset($data_to_save['categories']) and $par_page == false) {
                if (is_string($data_to_save['categories'])) {
                    $c1 = explode(',', $data_to_save['categories']);
                    if (is_array($c1)) {
                        foreach ($c1 as $item) {
                            $item = intval($item);
                            if ($item > 0) {
                                $cont_cat = $this->get('limit=1&content_type=page&subtype_value=' . $item);
                                if (isset($cont_cat[0]) and is_array($cont_cat[0])) {
                                    $cont_cat = $cont_cat[0];
                                    if (isset($cont_cat["subtype_value"]) and intval($cont_cat["subtype_value"]) > 0) {


                                        $data_to_save['parent'] = $cont_cat["id"];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (isset($data_to_save['content'])) {
            if (trim($data_to_save['content']) == '' or $data_to_save['content'] == false) {
                $data_to_save['content'] = null;
            } else {

                if (isset($data['download_remote_images']) and $data['download_remote_images'] != false and $adm == true) {


                    $site_url = $this->app->url_manager->site();
                    $images = mw()->parser->query($data_to_save['content'], 'img');
                    $to_download = array();
                    $to_replace = array();
                    $possible_sources = array();

                    if (isset($data['insert_content_image']) and $data['insert_content_image'] != false and isset($data['content'])) {
                        $data['content'] = "<img src='{$data['insert_content_image']}' /> " . $data['content'];
                    }


                    if (!empty($images)) {
                        foreach ($images as $image) {
                            $srcs = array();
                            preg_match('/src="([^"]*)"/i', $image, $srcs);
                            if (!empty($srcs) and isset($srcs[1]) and $srcs[1] != false) {
                                $possible_sources[] = $srcs[1];
                            }
                        }
                    }

                    if (!empty($possible_sources)) {
                        foreach ($possible_sources as $image_src) {
                            if (!stristr($image_src, $site_url)) {

                                $to_replace[] = $image_src;
                                $image_src = strtok($image_src, '?');
                                $ext = get_file_extension($image_src);
                                switch (strtolower($ext)) {
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                        $to_download[] = $image_src;
                                        break;
                                    default:
                                        break;
                                }

                            }
                        }
                    }

                    if (!empty($to_download)) {
                        $to_download = array_unique($to_download);

                        if (!empty($to_download)) {
                            foreach ($to_download as $src) {
                                $dl_dir = media_base_path() . 'downloaded' . DS;
                                if (!is_dir($dl_dir)) {
                                    mkdir_recursive($dl_dir);
                                }
                                $dl_file = $dl_dir . md5($src) . basename($src);
                                if (!is_file($dl_file)) {
                                    $is_dl = $this->app->url_manager->download($src, false, $dl_file);
                                }
                                if (is_file($dl_file)) {
                                    $url_local = dir2url($dl_file);
                                    $data_to_save['content'] = str_ireplace($src, $url_local, $data_to_save['content']);
                                }
                            }
                        }
                    }
                }


                $data_to_save['content'] = mw()->parser->make_tags($data_to_save['content']);
            }
        }

        $data_to_save['updated_at'] = date("Y-m-d H:i:s");
        if (isset($data_to_save['id']) and intval($data_to_save['id']) == 0) {
            if (!isset($data_to_save['position']) or intval($data_to_save['position']) == 0) {
                $pos_params = array();
                $pos_params['table'] = 'content';
                if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'page') {
                    $pos_params['content_type'] = $data_to_save['content_type'];
                    $pos_params['min'] = 'position';
                } else {
                    $pos_params['max'] = 'position';
                }
                $get_max_pos = mw()->database->get($pos_params);
                if (is_int($get_max_pos))
                    if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'page') {
                        $data_to_save['position'] = intval($get_max_pos) - 1;
                    } else {
                        $data_to_save['position'] = intval($get_max_pos) + 1;
                    }

            }
            $data_to_save['posted_at'] = $data_to_save['updated_at'];
        }


        $cats_modified = true;


        if (!isset($data_to_save['id']) or intval($data_to_save['id']) == 0) {
            if (!isset($data_to_save['parent'])) {
                $data_to_save['parent'] = 0;
            }
            if ($data_to_save['parent'] == 0) {
                if (isset($data_to_save['categories'])) {
                    $first = false;
                    if (is_array($data_to_save['categories'])) {
                        $temp = $data_to_save['categories'];
                        $first = array_shift($temp);
                    } else {
                        $first = intval($data_to_save['categories']);
                    }
                    if ($first != false) {
                        $first_par_for_cat = $this->app->category_manager->get_page($first);
                        if (!empty($first_par_for_cat) and isset($first_par_for_cat['id'])) {
                            $data_to_save['parent'] = $first_par_for_cat['id'];
                            if (!isset($data_to_save['content_type'])) {
                                $data_to_save['content_type'] = 'post';
                            }

                            if (!isset($data_to_save['subtype'])) {
                                $data_to_save['subtype'] = 'post';
                            }

                        }
                    }
                }
            }

        }


        if (isset($data_to_save['url']) and $data_to_save['url'] == $this->app->url_manager->site()) {
            unset($data_to_save['url']);
        }


        $data_to_save['allow_html'] = true;
        $this->no_cache = true;

        //clean some fields
        if (isset($data_to_save['custom_field_type']) and isset($data_to_save['value'])) {
            unset($data_to_save['custom_field_type']);
            unset($data_to_save['value']);
        }
        if (isset($data_to_save['custom_field_help_text'])) {
            unset($data_to_save['custom_field_help_text']);
        }
        if (isset($data_to_save['custom_field_is_active'])) {
            unset($data_to_save['custom_field_is_active']);
        }
        if (isset($data_to_save['name'])) {
            unset($data_to_save['name']);
        }
        if (isset($data_to_save['values'])) {
            unset($data_to_save['values']);
        }
        if (isset($data_to_save['value'])) {
            unset($data_to_save['value']);
        }
        if (isset($data_to_save['title'])) {
            $url_changed = true;
        }
        $data_to_save['table'] = $table;
        $save = $this->app->database->save($table, $data_to_save);

        $id = $save;
        if (isset($data_to_save['parent']) and $data_to_save['parent'] != 0) {
            $upd_posted = array();
            $upd_posted['posted_at'] = $data_to_save['updated_at'];
            $upd_posted['id'] = $data_to_save['parent'];
            $save_posted = $this->app->database->save($table, $upd_posted);
        }
        $after_save = $data_to_save;
        $after_save['id'] = $id;
        $this->app->event_manager->trigger('content.after.save', $after_save);
        $this->app->cache_manager->delete('content/' . $save);

        $this->app->cache_manager->delete('content_fields/global');
        if ($url_changed != false) {
            $this->app->cache_manager->delete('menus');
            $this->app->cache_manager->delete('categories');
        }

        $data_fields = array();
        if (!empty($orig_data)) {
            $data_str = 'data_';
            $data_str_l = strlen($data_str);
            foreach ($orig_data as $k => $v) {

                if (strlen($k) > $data_str_l) {
                    $rest = substr($k, 0, $data_str_l);
                    $left = substr($k, $data_str_l, strlen($k));
                    if ($rest == $data_str) {
                        $data_field = array();
                        $data_field["content_id"] = $save;
                        $data_field["field_name"] = $left;
                        $data_field["field_value"] = $v;

                        $data_field = $this->save_content_data_field($data_field);

                    }
                }

            }
        }
        if (!isset($data_to_save['images']) and isset($data_to_save['pictures'])) {
            $data_to_save['images'] = $data_to_save['pictures'];
        }
        if (isset($data_to_save['images']) and is_string($data_to_save['images'])) {
            $data_to_save['images'] = explode(',', $data_to_save['images']);
        }
        if (isset($data_to_save['images']) and is_array($data_to_save['images']) and !empty($data_to_save['images'])) {
            $images_to_save = $data_to_save['images'];
            foreach ($images_to_save as $image_to_save) {
                if (is_string($image_to_save)) {
                    $image_to_save = trim($image_to_save);

                    if ($image_to_save != '') {
                        $save_media = array();
                        $save_media['content_id'] = $id;
                        $save_media['filename'] = $image_to_save;
                        $check = $this->app->media_manager->get($save_media);
                        $save_media['media_type'] = 'picture';
                        if ($check == false) {
                            $this->app->media_manager->save($save_media);
                        }
                    }
                } elseif (is_array($image_to_save) and !empty($image_to_save)) {
                    $save_media = $image_to_save;
                    $save_media['content_id'] = $id;
                    $this->app->media_manager->save($save_media);
                }


            }
        }


        if (isset($data_to_save['categories'])) {


            if (is_string($data_to_save['categories'])) {
                $data_to_save['categories'] = explode(',', $data_to_save['categories']);
            }
            $categories = $data_to_save['categories'];
            if (is_array($categories)) {

                $save_cat_item = array();
                $save_cat_item['rel_type'] = 'content';
                $save_cat_item['rel_id'] = $id;
                $check = $this->app->category_manager->get_items($save_cat_item);
                //  dd($check);
                if (is_array($check) and !empty($check)) {
                    foreach ($check as $item) {
                        if (!in_array($item['parent_id'], $categories)) {
                            $this->app->category_manager->delete_item($item['id']);
                        }
                    }
                }


                $cats_modified = true;
                foreach ($categories as $category) {
                    if (intval($category) != 0) {
                        $save_cat_item = array();
                        $save_cat_item['rel_type'] = 'content';
                        $save_cat_item['rel_id'] = $id;
                        $save_cat_item['parent_id'] = $category;
                        $check = $this->app->category_manager->get_items($save_cat_item);
                        if ($check == false) {
                            $this->app->category_manager->save_item($save_cat_item);
                        }
                    }
                }

            }

        }

        if (isset($data_to_save['add_content_to_menu']) and is_array($data_to_save['add_content_to_menu'])) {

            foreach ($data_to_save['add_content_to_menu'] as $menu_id) {
                $this->add_content_to_menu($save, $menu_id);
            }
        }
        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $new_category = $this->app->category_manager->get_for_content($save);

            if ($new_category == false) {
                //$new_category_id = intval($new_category);
                $new_category = array();
                $new_category["data_type"] = "category";
                $new_category["rel_type"] = 'content';
                $new_category["rel_id"] = $save;
                $new_category["table"] = $table_cats;
                $new_category["id"] = 0;
                $new_category["title"] = $data_to_save['title'];
                $new_category["parent_id"] = "0";
                $cats_modified = true;
                // $new_category = $this->app->category_manager->save($new_category);
            }
        }
        $custom_field_table = $this->tables['custom_fields'];
        $custom_field_table = mw()->database_manager->real_table_name($custom_field_table);

        $sid = mw()->user_manager->session_id();
        $media_table = $this->tables['media'];
        $media_table = mw()->database_manager->real_table_name($media_table);

        if ($sid != false and $sid != '' and $id != false) {

            $clean = " UPDATE $custom_field_table SET
            rel_type =\"content\" ,
            rel_id =\"{$id}\"
            WHERE

              (rel_id=0 OR rel_id IS NULL OR rel_id =\"0\")
            AND rel_type =\"content\"
	        ";

            $this->app->database->q($clean);


            $clean = " UPDATE $media_table SET
            rel_id =\"{$id}\"
            WHERE
            session_id =\"{$sid}\"
            AND rel_type =\"content\" AND (rel_id=0 OR rel_id IS NULL)
            ";
            $this->app->database->q($clean);
        }


        $this->app->cache_manager->delete('custom_fields');
        $this->app->cache_manager->delete('media/global');

        if (isset($data_to_save['parent']) and intval($data_to_save['parent']) != 0) {
            $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['parent']));
        }
        if (isset($data_to_save['id']) and intval($data_to_save['id']) != 0) {
            $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['id']));
        }

        $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('content_fields/global');


        $this->app->cache_manager->delete('content');
        $this->app->cache_manager->delete('categories/global');
        $this->app->cache_manager->delete('categories_items/global');
        if ($cats_modified != false) {


            if (isset($c1) and is_array($c1)) {
                foreach ($c1 as $item) {
                    $item = intval($item);
                    if ($item > 0) {
                        $this->app->cache_manager->delete('categories/' . $item);
                    }
                }
            }
        }
        event_trigger('mw_save_content', $save);
        return $save;
    }

    public function save_content_data_field($data, $delete_the_cache = true)
    {

        $adm = $this->app->user_manager->is_admin();
        $table = $this->tables['content_data'];

        $check_force = mw_var('FORCE_SAVE_CONTENT_DATA_FIELD');


        if ($check_force == false and $adm == false) {
            return array('error' => "You must be logged in as admin to use: " . __FUNCTION__);

        }

        if (!is_array($data)) {
            $data = parse_params($data);
        }

        if (!isset($data['id'])) {

            if (!isset($data['field_name'])) {
                return array('error' => "You must set 'field' parameter");
            }
            if (!isset($data['field_value'])) {
                return array('error' => "You must set 'value' parameter");
            }

            if (!isset($data['content_id'])) {
                return array('error' => "You must set 'content_id' parameter");
            }
        }


        if (isset($data['field_name']) and isset($data['content_id'])) {
            $is_existing_data = array();
            $is_existing_data['field_name'] = $data['field_name'];
            $is_existing_data['content_id'] = intval($data['content_id']);

            $is_existing_data['one'] = true;

            $is_existing = $this->get_content_data_fields($is_existing_data);
            if (is_array($is_existing) and isset($is_existing['id'])) {
                $data['id'] = $is_existing['id'];
            }

        }
        if (isset($data['content_id'])) {
            $data['rel_id'] = intval($data['content_id']);
        }
        if (isset($data['field_value']) and is_array($data['field_value'])) {
            $data['field_value'] = json_encode($data['field_value']);
        }
        $data['rel_type'] = 'content';

        $data['allow_html'] = true;
        // $data['debug'] = true;

        $save = $this->app->database->save($table, $data);

        $this->app->cache_manager->delete('content_data');

        return $save;


    }

    public function get_content_data_fields($data, $debug = false)
    {


        $table = $this->tables['content_data'];


        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (!is_array($data)) {
            $data = array();
        }


        $data['table'] = $table;
        $data['cache_group'] = 'content_data';


        $get = $this->app->database->get($data);

        return $get;

    }

    function create_default_content($what)
    {

        if (defined("MW_NO_DEFAULT_CONTENT")) {
            return true;
        }


        switch ($what) {
            case 'shop' :
                $is_shop = $this->get('content_type=page&is_shop=0');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop == false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['is_active'] = 1;

                    $add_page['title'] = "Online shop";
                    $add_page['url'] = "shop";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = '1';
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts_manager->scan();
                    if (is_array($find_layout)) {
                        foreach ($find_layout as $item) {
                            if (isset($item['layout_file']) and isset($item['is_shop'])) {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }
                    }
                    $new_shop = $this->app->database->save('content', $add_page);
                    $this->app->cache_manager->delete('content');
                    $this->app->cache_manager->delete('categories');
                    $this->app->cache_manager->delete('custom_fields');

                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }

                $posts = $this->get('content_type=post&parent=' . $new_shop);
                if ($posts == false and $new_shop != false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = $new_shop;
                    $add_page['title'] = "My product";
                    $add_page['url'] = "my-product";
                    $add_page['content_type'] = "post";
                    $add_page['subtype'] = "product";
                    $add_page['is_active'] = 1;
                    //$new_shop = $this->save_content($add_page);
                    //$this->app->cache_manager->delete('content');
                    //$this->app->cache_manager->clear();
                }


                break;


            case 'blog' :
                $is_shop = $this->get('is_deleted=0&content_type=page&subtype=dynamic&is_shop=1&limit=1');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop == false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['is_active'] = 1;
                    $add_page['title'] = "Blog";
                    $add_page['url'] = "blog";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = 0;
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts_manager->scan();
                    if (is_array($find_layout)) {
                        foreach ($find_layout as $item) {
                            if (!isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type'])) == 'dynamic') {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }

                        foreach ($find_layout as $item) {
                            if (isset($item['name']) and stristr($item['name'], 'blog') and !isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type'])) == 'dynamic') {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }


                    }

                    $new_shop = $this->app->database->save('content', $add_page);
                    $this->app->cache_manager->delete('content');
                    $this->app->cache_manager->delete('categories');
                    $this->app->cache_manager->delete('content_fields');


                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }


                break;

            case 'default' :
            case 'install' :
                $any = $this->get('count=1&content_type=page&limit=1');
                if (intval($any) == 0) {


                    $table = $this->tables['content'];
                    mw_var('FORCE_SAVE_CONTENT', $table);
                    mw_var('FORCE_SAVE', $table);

                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['title'] = "Home";
                    $add_page['url'] = "home";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'static';
                    $add_page['is_shop'] = 0;
                    //$add_page['debug'] = 1;
                    $add_page['is_active'] = 1;
                    $add_page['is_home'] = 1;
                    $add_page['active_site_template'] = 'default';
                    $new_shop = $this->save_content($add_page);
                }

                break;

            default :
                break;
        }
    }

    public function get_pages($params = false)
    {
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!is_array($params)) {
            $params = array();
        }
        if (!isset($params['content_type'])) {
            $params['content_type'] = 'page';
        }

        return $this->get($params);
    }

    public function get_posts($params = false)
    {
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!is_array($params)) {
            $params = array();
        }
        if (!isset($params['content_type'])) {
            $params['content_type'] = 'post';
        }
        if (!isset($params['subtype'])) {
            $params['subtype'] = 'post';
        }
        return $this->get($params);
    }

    public function get_products($params = false)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (!is_array($params)) {
            $params = array();
        }
        if (!isset($params['content_type'])) {
            $params['content_type'] = 'product';
        }
        return $this->get($params);
    }


    public function title($id)
    {
        if ($id == false or $id == 0) {
            if (defined('CONTENT_ID') == true) {
                $id = CONTENT_ID;
            } else if (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }
        $content = $this->get_by_id($id);
        if (isset($content['title'])) {
            return $content['title'];
        }
    }


    public function bulk_assign($data)
    {
        if (!is_admin()) {
            return;
        }
        if (is_string($data)) {
            $data = parse_params($data);
        }

        if (isset($data['content_ids'])) {
            $content_ids = $data['content_ids'];

            if (is_array($content_ids)) {
                foreach ($content_ids as $content_id) {
                    $to_save = array();
                    $to_save['id'] = $content_id;
                    $to_save['skip_timestamps'] = true;
                    if (isset($data['parent_id'])) {
                        $to_save['parent'] = $data['parent_id'];
                    }
                    if (isset($data['categories'])) {
                        $to_save['categories'] = $data['categories'];
                    }
                    $this->save_content($to_save);
                }
            }
        }
        return array('success' => 'Content is moved');


    }

    public function site_templates()
    {
        //shim for old versions
        return $this->app->template->site_templates();
    }

    public function ping()
    {

//        if (!is_object($this->pinger)) {
//            if (!isset($this->app->adapters->container['content_ping'])) {
//                $app = $this->app;
//                $this->app->adapters->container['content_ping'] = function ($c) use ($app) {
//                    return new Adapters\Ping\SearchEngines($app);
//                };
//            }
//            $this->pinger = $this->app->adapters->container['content_ping'];
//        }
//
//        return $this->pinger->ping();


    }


}

