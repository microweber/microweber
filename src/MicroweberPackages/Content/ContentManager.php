<?php

namespace MicroweberPackages\Content;

use Conner\Tagging\Model\Tagged;
use Content;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Menu;
use DB;
use MicroweberPackages\Content\Repositories\ContentRepository;

/**
 * Content class is used to get and save content in the database.
 *
 * @category Content
 * @desc     These functions will allow you to get and save content in the database.
 */
class ContentManager
{
    public static $skip_pages_starting_with_url = ['admin', 'api', 'module'];
    public $tables = array();
    public $table_prefix = false;

    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    /** @var ContentManagerCrud */
    public $crud;

    /** @var ContentManagerHelpers */
    public $helpers;


    public $content_id = false;
    public $product_id = false;
    public $page_id = false;
    public $main_page_id = false;
    public $post_id = false;
    public $category_id = false;
    public $template_name = false;


    /**
     *  Boolean that indicates the usage of cache while making queries.
     *
     * @var
     */
    public $no_cache = false;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->set_table_names();
        $this->crud = new ContentManagerCrud($this->app);
        $this->helpers = new ContentManagerHelpers($this->app);

        //$this->content_repository = $this->app->repository_manager->driver(\MicroweberPackages\Content\Content::class);



    }


    function post_id()
    {
        if ($this->post_id) {
            return $this->post_id;
        }

        if (defined('POST_ID')) {
            return POST_ID;
        }
    }


    function product_id()
    {
        if ($this->product_id) {
            return $this->product_id;
        }

        if (defined('PRODUCT_ID')) {
            return PRODUCT_ID;
        }
    }

    function content_id()
    {
        if ($this->content_id) {
            return $this->content_id;
        }
        if ($this->post_id()) {
            return $this->post_id();
        } elseif ($this->product_id()) {
            return $this->product_id();
        } elseif ($this->page_id()) {
            return $this->page_id();
        } elseif (defined('CONTENT_ID')) {
            return CONTENT_ID;
        }
    }

    function category_id()
    {
        if ($this->category_id) {
            return $this->category_id;
        }
        if (defined('CATEGORY_ID')) {
            return CATEGORY_ID;
        }

    }

    function page_id()
    {
        if ($this->page_id) {
            return $this->page_id;
        }

        if (defined('PAGE_ID')) {
            return PAGE_ID;
        }
    }




    /**
     * Sets the database table names to use by the class.
     *
     * @param array|bool $tables
     */
    public function set_table_names($tables = false)
    {
        $prefix = '';

        if($tables == false){
            $tables = array();
        }

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
        if (!isset($tables['attributes'])) {
            $tables['attributes'] = 'attributes';
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
        $this->tables['attributes'] = $tables['attributes'];

        /*
         * Define table names constants for global default usage
         */
        if (!defined('MW_DB_TABLE_CONTENT')) {
            define('MW_DB_TABLE_CONTENT', $tables['content']);
        }
        if (!defined('MW_DB_TABLE_CONTENT_FIELDS')) {
            define('MW_DB_TABLE_CONTENT_FIELDS', $tables['content_fields']);
        }
        if (!defined('MW_DB_TABLE_CONTENT_DATA')) {
            define('MW_DB_TABLE_CONTENT_DATA', $tables['content_data']);
        }
        if (!defined('MW_DB_TABLE_CONTENT_FIELDS_DRAFTS')) {
            define('MW_DB_TABLE_CONTENT_FIELDS_DRAFTS', $tables['content_fields_drafts']);
        }
        if (!defined('MW_DB_TABLE_MEDIA')) {
            define('MW_DB_TABLE_MEDIA', $tables['media']);
        }
        if (!defined('MW_DB_TABLE_CUSTOM_FIELDS')) {
            define('MW_DB_TABLE_CUSTOM_FIELDS', $tables['custom_fields']);
        }
        if (!defined('MW_DB_TABLE_MENUS')) {
            define('MW_DB_TABLE_MENUS', $tables['menus']);
        }
        if (!defined('MW_DB_TABLE_TAXONOMY')) {
            define('MW_DB_TABLE_TAXONOMY', $tables['categories']);
        }
        if (!defined('MW_DB_TABLE_TAXONOMY_ITEMS')) {
            define('MW_DB_TABLE_TAXONOMY_ITEMS', $tables['categories_items']);
        }
    }

    /**
     * Get single content item by id from the content_table.
     *
     * @param int|string $id The id of the page or the url of a page
     *
     * @return array The page row from the database
     *
     * @category  Content
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
     * Get single content item by id from the content_table.
     *
     * @param int $id The id of the content item
     *
     * @return array
     *
     * @category  Content
     * @function  get_content_by_id
     *
     * @example
     * <pre>
     * $content = $this->get_by_id(1);
     * var_dump($content);
     * </pre>
     */
    public function get_by_id($id)
    {
        return app()->content_repository->getById($id);

      //  return $this->crud->get_by_id($id);
    }

    public function get_by_url($url = '', $no_recursive = false)
    {
        return $this->crud->get_by_url($url, $no_recursive);
    }

    public function get_content_id_from_url($url = '')
    {
        $content = $this->get_by_url($url);
        if ($content && isset($content['id'])) {
            return $content['id'];
        }
    }



    /**
     * Get array of content items from the database.
     *
     * It accepts string or array as parameters. You can pass any db field name as parameter to filter content by it.
     * All parameter are passed to the get() function
     *
     * You can get and filter content and also order the results by criteria
     *
     * @function get_content
     *
     *
     * @desc     Get array of content items from the content DB table
     *
     * @uses     get() You can use all the options of get(), such as limit, order_by, count, etc...
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
     * @return array|bool|mixed Array of content or false if nothing is found
     *
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
     * @example
     * #### Get by params as string
     * <code>
     *  $data = $this->get('is_active=1');
     *  var_dump($data);
     * </code>
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
     */
    public function get($params = false)
    {
        return $this->crud->get($params);
    }

    public function get_children($id = 0, $without_main_parrent = false)
    {
        if (intval($id) == 0) {
            return false;
        }

        $table = $this->tables['content'];

        $content_ids = $this->get('fields=id&no_limit=1&parent=' . $id);

        $ids = array();

        $data = array();
        $id = intval($id);

        $get = array();
        $get['parent'] = $id;

        $cats = get_categories_for_content($id);

        if (isset($without_main_parrent) and $without_main_parrent == true) {
            $get['parent'] = '[neq]0';
        }


        if ($content_ids and !empty($content_ids)) {
            foreach ($content_ids as $n) {
                if ($n and isset($n['id'])) {
                    $ids[] = $n['id'];
                }
            }
        }


        // $q = " SELECT id, parent FROM $table WHERE parent={$id} " . $with_main_parrent_q;
        // $taxonomies = $this->app->database_manager->query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'content/' . $id);

        $taxonomies = $this->get($get);
        // $taxonomies = $taxonomies->get()->toArray();
        if ($cats) {
            foreach ($cats as $cat) {
                if (isset($cat['id'])) {
                    $posts_in_cats = get_category_items($cat['id']);
                    if ($posts_in_cats) {
                        foreach ($posts_in_cats as $posts_in_cat) {
                            if (isset($posts_in_cat['rel_type']) and $posts_in_cat['rel_type'] == 'content') {
                                if (intval($posts_in_cat['rel_id']) != 0) {
                                    $ids[] = $posts_in_cat['rel_id'];
                                }
                            }
                        }
                    }
                }
            }
        }

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
        return $this->app->data_fields_manager->get($params);
    }

    public function data($content_id,$field_name = false)
    {
        $data = array();
        $data['content_id'] = intval($content_id);
        $values = $this->app->data_fields_manager->get_values($data);

        if ($field_name) {
            if (isset($values[$field_name])) {
                return $values[$field_name];
            } else {
                return false;
            }
        }

        return $values;
    }

    public function tags($content_id = false, $return_full = false)
    {
        return $this->app->content_repository->tags($content_id, $return_full);
    }

    public function attributes($content_id)
    {
        $data = array();
        $data['rel_type'] = 'content';
        $data['rel_id'] = intval($content_id);

        return $this->app->attributes_manager->get_values($data);
    }

    /**
     * paging.
     *
     * paging
     *
     * @category  posts
     *
     * @author    Microweber
     *
     * @link
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
    public function paging($params)
    {

        $params = parse_params($params);

        $pages_count = 1;
        $base_url = false;
        $paging_param = 'current_page';
        $keyword_param = 'keyword_param';
        $class = 'pagination';
        $li_class = '';
        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }

        if (isset($params['num'])) {
            $pages_count = $params['num'];
        }
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = intval($params['limit']);
        }

        if (isset($params['class'])) {
            $class = $params['class'];
        }
        if (isset($params['li_class'])) {
            $li_class = $params['li_class'];
        }

        if (isset($params['paging_param'])) {
            $paging_param = $params['paging_param'];
        }

        $current_page_from_url = $this->app->url_manager->param($paging_param);

        if (isset($params['current_page'])) {
            $current_page_from_url = $params['current_page'];
        } elseif (isset($params['curent_page'])) {
            $current_page_from_url = $params['curent_page'];
        }
        $no_wrap = false;
        if (isset($params['no_wrap'])) {
            $no_wrap = true;
        }

        // Laravel pagination
        if (isset($params['laravel_pagination'])) {

            if ($this->app->url_manager->is_ajax() == false) {
                $base_url = $this->app->url_manager->current(1);
            } else {
                if ($_SERVER['HTTP_REFERER'] != false) {
                    $base_url = $_SERVER['HTTP_REFERER'];
                }
            }

            $current_page_from_url = $current_page_from_url ?: (Paginator::resolveCurrentPage() ?: 1);

            $items = [];
            for ($i = 0; $i <= $params['laravel_total']; $i++) {
                $items[] = 1;
            }
            $items = Collection::make($items);

            $paginate = new LengthAwarePaginator($items->forPage($current_page_from_url, $params['laravel_pagination_limit']), $items->count(), $params['laravel_pagination_limit'], $current_page_from_url, []);

            $paginationPath = strtok($base_url, '?');
            $paginate->setPath($paginationPath);

            if (isset($params['return_as_array']) && $params['return_as_array']) {

                $paginateArray = $paginate->toArray();

                $pagination_links = [];

                foreach ($paginateArray['links'] as $paginate) {

                    $pagination_links[] = [
                        'attributes' => [
                            'class' => '',
                            'current' => $paginate['active'],
                            'data-page-number' => '',
                            'href' => $paginate['url']
                        ],
                        'title' => $paginate['label']
                    ];
                }

                return $pagination_links;
            } else {
                return $paginate->links();
            }
        }

        // OLD pagiantion
        $ready_paging_first_links = [];
        $ready_paging_last_links = [];
        $ready_paging_number_links = [];
        $data = $this->paging_links($base_url, $pages_count, $paging_param, $keyword_param);
        if (is_array($data)) {

            if ($no_wrap) {
                $to_print = "<ul class='{$class}'>";
            } else {
                $to_print = "<div class='{$class}-holder' ><ul class='{$class}'>";
            }

            if ($current_page_from_url > 1 && isset($params['show_first_last'])) {
                $to_print = '<a data-page-number="' . $data[1] . '" href="' . $data[1] . '">' . _e('First', true) . '</a>';
                $ready_paging_first_links[] = [
                    'attributes' => [
                        'class' => false,
                        'current' => false,
                        'data-page-number' => $data[1],
                        'href' => $data[1]
                    ],
                    'title' => _e('First', true)
                ];
            }

            $paging_items = array();
            $active_item = 1;
            foreach ($data as $key => $value) {
                $skip = false;
                $act_class = false;
                if ($current_page_from_url != false) {
                    if (intval($current_page_from_url) == intval($key)) {
                        $act_class = ' active ';
                        $active_item = $key;
                    }
                }

                $item_to_print = '';
                $item_to_print .= "";
                $item_to_print .= "<li class=\"page-item\"><a class=\"{$act_class} page-link\" href=\"$value\" data-page-number=\"$key\">$key</a></li> ";
                $item_to_print .= '';
                $paging_items[$key] = $item_to_print;

              /*
               * TODO: this will bug when we have many products
               *   if (count($ready_paging_number_links) > $limit) {
                    continue;
                }*/

                $ready_paging_number_links[] = [
                    'attributes' => [
                        'class' => $act_class,
                        'current' => $act_class,
                        'data-page-number' => $key,
                        'href' => $value
                    ],
                    'title' => $key
                ];
            }

            if ($limit != false and count($paging_items) > $limit) {
                $limited_paging = array();

                $limited_paging_begin = array();

                foreach ($paging_items as $key => $paging_item) {
                    if ($key == $active_item) {
                        $steps = $steps2 = floor($limit / 2);
                        for ($i = 1; $i <= $steps; ++$i) {
                            if (isset($paging_items[$key - $i])) {
                                $limited_paging_begin[$key - $i] = $paging_items[$key - $i];
                                // $steps2--;
                            } else {
                                ++$steps2;
                            }
                        }

                        $limited_paging[$key] = $paging_item;
                        for ($i = 1; $i <= $steps2; ++$i) {
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
                    $limited_paging_begin[] = '<li class="page-item"><a data-page-number="' . ($active_item - 1) . '" href="' . $prev_link . '" class="page-link">&laquo;</a></li>';

                    $ready_paging_first_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => ($active_item - 1),
                            'href' => $prev_link
                        ],
                        'title' => 'Previous'
                    ];

                }

                $limited_paging_begin = array_reverse($limited_paging_begin);
                $limited_paging = array_merge($limited_paging_begin, $limited_paging);

                if (isset($data[$active_item + 1])) {
                    $next_link = $data[$active_item + 1];
                    $limited_paging[] = '<li class="page-item"><a data-page-number="' . ($active_item + 1) . '" href="' . $next_link . '" class="page-link">&raquo;</a></li>';

                    $ready_paging_last_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => ($active_item + 1),
                            'href' => $next_link
                        ],
                        'title' => 'Next'
                    ];

                }

                if (isset($params['show_first_last'])) {
                    $limited_paging[] = '<li class="page-item"><a data-page-number="' . end($data) . '" href="' . end($data) . '" class="page-link">' . _e('Last', true) . '</a></li>';

                    $ready_paging_last_links[] = [
                        'attributes' => [
                            'class' => false,
                            'current' => false,
                            'data-page-number' => end($data),
                            'href' => end($data)
                        ],
                        'title' => _e('Last', true)
                    ];

                }

                if (count($limited_paging) > 2) {
                    $paging_items = $limited_paging;
                }
            }

            if (isset($params['return_as_array']) && $params['return_as_array']) {

                $ready_paging_links = array_merge($ready_paging_first_links, $ready_paging_number_links);
                $ready_paging_links = array_merge($ready_paging_links, $ready_paging_last_links);

                return $ready_paging_links;
            }

            $to_print .= implode("\n", $paging_items);

            if ($no_wrap) {
                $to_print .= '</ul>';
            } else {
                $to_print .= '</ul></div>';
            }

            return $to_print;
        }
    }

    public function paging_links($base_url = false, $pages_count = false, $paging_param = 'current_page', $keyword_param = 'keyword')
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
        $get_params = array();
        $get_params_append = '';
        if ($_GET) {
            $get_params = array_merge($get_params, $_GET);
        }

        if (isset($get_params[$paging_param])) {
            unset($get_params[$paging_param]);
        }

        if ($get_params and is_array($get_params)) {
            $get_params = array_filter($get_params);

            $get_params_append = implode('&', array_map(
                function ($v, $k) {
                    if ($k and $v and !is_array($v)) {
                        return sprintf("%s=%s", $k, $v);
                    }
                },
                $get_params,
                array_keys($get_params)
            ));

        }

        $in_empty_url = false;
        if ($the_url == site_url()) {
            $in_empty_url = 1;
        }


        if ($get_params_append) {
            if (stristr($base_url, '?') == false) {
                $append_to_links = '?' . $get_params_append;
            } else {
                $append_to_links = '&' . $get_params_append;

            }
        }

        $the_url = explode('/', $the_url);
        for ($x = 1; $x <= $pages_count; ++$x) {
            $new = array();
            foreach ($the_url as $itm) {

                $itm = explode(':', $itm);
                if ($itm[0] == $paging_param) {
                    $itm[1] = $x;
                }
                $new[] = implode(':', $itm);
            }
            $new_url = implode('/', $new);


            // $page_links[$x] = $new_url . $append_to_links;
            $page_links[$x] = $new_url;
        }


        $count = count($page_links);
        for ($x = 1; $x <= $count; ++$x) {
            if (stristr($page_links[$x], $paging_param . ':') == false) {
                if ($in_empty_url == false) {
                    $l = reduce_double_slashes($page_links[$x] . '/' . $paging_param . ':' . $x);
                    if ($get_params_append) {
                        $l = $l . '?' . $get_params_append;
                    }
                } else {
                    $l = reduce_double_slashes($page_links[$x] . '?' . $paging_param . '=' . $x);
                    if ($get_params_append) {
                        $l = $l . '&' . $get_params_append;
                    }
                }

                $l = reduce_double_slashes($page_links[$x] . '?' . $paging_param . '=' . $x);
                if ($get_params_append) {
                    $l = $l . '&' . $get_params_append;
                }
                $l = str_ireplace('module/', '', $l);
                $page_links[$x] = $l;
                //$page_links[$x] = $l . $append_to_links;
                //$page_links[$x] = $l . $append_to_links;
            }
        }
        return $page_links;
    }

    /**
     * Print nested tree of pages.
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
     * // Other opltions
     * $pt_opts['parent'] = "8";
     * $pt_opts['include_first'] =  true; //includes the parent in the tree
     * $pt_opts['id_prefix'] = 'my_id';
     * </pre>
     *
     * @param int $parent
     * @param bool $link
     * @param bool $active_ids
     * @param bool $active_code
     * @param bool $remove_ids
     * @param bool $removed_ids_code
     * @param bool $ul_class_name
     * @param bool $include_first
     *
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

        $cache_id_params = $params;
        if (isset($cache_id_params['link']) and is_callable($cache_id_params['link'])) {
            unset($cache_id_params['link']);
            $params['no_cache'] = true;
        }


        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);

        }
        $function_cache_id = $function_cache_id . serialize($cache_id_params);

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
//            $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
//            if (isset($params['no_cache'])) {
//                $cache_content = false;
//            }
//            // @todo: activate cache
//            $cache_content = false;
//            if (($cache_content) != false) {
//                if (isset($params['return_data'])) {
//                    return $cache_content;
//                } else {
//                    echo $cache_content;
//                }
//
//                return;
//            }
        }

        $nest_level = 0;

        if (isset($params['nest_level'])) {
            $nest_level = $params['nest_level'];
        }
        if (isset($params['parent'])) {
            $params['parent'] = intval($params['parent']);
        }

        if (isset($params['id'])) {
            unset($params['id']);
        }

        $max_level = false;
        if (isset($params['max_level'])) {
            $max_level = $params['max_level'];
        } elseif (isset($params['maxdepth'])) {
            $max_level = $params['max_level'] = $params['maxdepth'];
        } elseif (isset($params['depth'])) {
            $max_level = $params['max_level'] = $params['depth'];
        }

        if ($max_level != false) {
            if (intval($nest_level) >= intval($max_level)) {
                echo '';

                return;
            }
        }

        $is_shop = '';
        if (isset($params['is_shop'])) {
            if ($params['is_shop'] == 'y') {
                $params['is_shop'] = 1;
            } elseif ($params['is_shop'] == 'n') {
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
        $content_link_class = 'mw-tree-content-link';
        if (isset($params['content_link_class'])) {
            $content_link_class = $params['content_link_class'];
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
        $include_all_content = false;
        if (isset($params['include_all_content'])) {
            $include_all_content = $params['include_all_content'];
        }
        ob_start();

        $table = $this->tables['content'];
        $par_q = '';
        if ($parent == false) {
            $parent = (0);
        } else {
            $parent = intval($parent);
            $par_q = " parent=$parent    and  ";
        }

        if ($include_first == true) {
            $content_type_q = " and content_type='page'  ";
            if ($include_all_content) {
                $content_type_q = ' ';
            }

            $sql = "SELECT * from $table where  id={$parent}    and   is_deleted=0 " . $content_type_q . $is_shop . '  order by position desc  limit 0,1';
        } else {
            $content_type_q = "  content_type='page'  ";
            if ($include_all_content) {
                $content_type_q = ' ';
            }

            $sql = "SELECT * from $table where  " . $par_q . $content_type_q . "   and   is_deleted=0 $is_shop  order by position desc limit 0,100";
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

        if (isset($params['remove_ids'])) {
            $remove_ids = $params['remove_ids'];
        }

        if (isset($remove_ids) and is_string($remove_ids)) {
            $remove_ids = explode(',', $remove_ids);
        }

        if (isset($active_ids)) {
            $active_ids = $active_ids;
        }

        if (isset($active_ids) and is_string($active_ids)) {
            $active_ids = explode(',', $active_ids);
            if (is_array($active_ids) == true) {
                foreach ($active_ids as $idk => $idv) {
                    $active_ids[$idk] = intval($idv);
                }
            }
        }

        $the_active_class = 'active';
        if (isset($params['active_class'])) {
            $the_active_class = $params['active_class'];
        }

        if (!$include_all_content) {
            $params['content_type'] = 'page';
        }

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
            $q = $this->get('id=' . $include_first_set);
        } else {
            $q = $this->get($params2);
        }
        $result = $q;
        if (is_array($result) and !empty($result)) {
            ++$nest_level;
            if (trim($list_tag) != '') {
                if ($ul_class_name == false) {
                    echo "<{$list_tag} class='pages_tree depth-{$nest_level}'>";
                } else {
                    echo "<{$list_tag} class='{$ul_class_name} depth-{$nest_level}'>";
                }
            }
            $res_count = 0;
            foreach ($result as $item) {
                if (is_array($item) != false and isset($item['title']) and $item['title'] != null) {
                    $skip_me_cause_iam_removed = false;
                    if (is_array($remove_ids) == true) {
                        foreach ($remove_ids as $idk => $idv) {
                            $remove_ids[$idk] = intval($idv);
                        }

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
                            $st_str3 = ' data-is-shop=true ';
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
                            } elseif (!isset($result[$res_count + 1])) {
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


                            if (is_callable($link)) {
                                $to_print = call_user_func_array($link, array($item));
                            } else {
                                $to_print = $link;
                            }


                            $to_print = str_replace('{id}', $item['id'], $to_print);
                            $to_print = str_replace('{active_class}', $active_class, $to_print);
                            $to_print = str_replace('{active_parent_class}', $active_parent_class, $to_print);
                            $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);

                            $to_pr_2 = str_replace('{exteded_classes}', $ext_classes, $to_pr_2);
                            $to_pr_2 = str_replace('{active_class}', $active_class, $to_pr_2);
                            $to_pr_2 = str_replace('{active_parent_class}', $active_parent_class, $to_pr_2);

                            $to_print = str_replace('{title}', $item['title'], $to_print);
                            $to_print = str_replace('{nest_level}', 'depth-' . $nest_level, $to_print);
                            $to_print = str_replace('{content_link_class}', $content_link_class, $to_print);
                            $to_print = str_replace('{empty}', '', $to_print);

                            if (strstr($to_print, '{link}')) {
                                $to_print = str_replace('{link}', page_link($item['id']), $to_print);
                            }

                            $empty1 = intval($nest_level);
                            $empty = '';
                            for ($i1 = 0; $i1 < $empty1; ++$i1) {
                                $empty = $empty . '&nbsp;&nbsp;';
                            }
                            $to_print = str_replace('{empty}', $empty, $to_print);

                            if (strstr($to_print, '{tn}')) {
                                $content_img = get_picture($item['id']);
                                if ($content_img) {
                                    $to_print = str_replace('{tn}', $content_img, $to_print);
                                } else {
                                    $to_print = str_replace('{tn}', '', $to_print);
                                }
                            }
                            foreach ($item as $item_k => $item_v) {
                                if (!is_string($item_k) || !is_string($item_v)) {
                                    continue;
                                }
                                $to_print = str_replace('{' . $item_k . '}', $item_v, $to_print);
                            }
                            ++$res_count;
                            if (isset($active_ids) and is_array($active_ids) == true) {
                                $is_there_active_ids = false;
                                foreach ($active_ids as $active_id) {
                                    if (intval($item['id']) == intval($active_id)) {
                                        $is_there_active_ids = true;
                                        $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                        $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                        $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                        $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                        $to_pr_2 = str_replace('{empty}', '', $to_pr_2);

                                    }
                                }
                            } elseif (isset($active_ids) and !is_array($active_ids)) {
                                if (intval($item['id']) == intval($active_ids)) {
                                    $is_there_active_ids = true;
                                    $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                                    $to_print = str_ireplace('{active_class}', $the_active_class, $to_print);
                                    $to_pr_2 = str_ireplace('{active_class}', $the_active_class, $to_pr_2);
                                    $to_pr_2 = str_ireplace('{active_code_tag}', $active_code_tag, $to_pr_2);
                                    $to_pr_2 = str_replace('{empty}', '', $to_pr_2);

                                }
                            }

                            $to_print = str_ireplace('{active_code}', '', $to_print);
                            $to_print = str_ireplace('{active_class}', '', $to_print);
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{active_code_tag}', '', $to_pr_2);
                            $to_pr_2 = str_ireplace('{content_link_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{empty}', '', $to_pr_2);

                            $to_print = str_replace('{exteded_classes}', '', $to_print);
                            $to_print = str_replace('{content_link_class}', '', $to_print);

                            $to_print = str_replace('{empty}', '', $to_print);


                            if ($item['id'] == $item['parent']) {
                                $remove_ids[] = intval($item['id']);
                            }

                            if (is_array($remove_ids) == true) {
                                if (in_array($item['id'], $remove_ids)) {
                                    if ($removed_ids_code == false) {
                                        $to_print = false;
                                    } else {
                                        $remove_ids[] = intval($item['id']);
                                        $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                                    }
                                } else {
                                    $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
                                }
                            }
                            $to_pr_2 = str_replace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);

                            echo $to_pr_2;
                            $to_pr_2 = false;
                            echo $to_print;
                        } else {
                            $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{exteded_classes}', '', $to_pr_2);
                            $to_pr_2 = str_replace('{active_parent_class}', '', $to_pr_2);

                            echo $to_pr_2;
                            $to_pr_2 = false;
                            echo $item['title'];
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

                            $params['remove_ids'] = $remove_ids;
                            if ($skip_pages_from_tree == false) {
                                if ($item['id'] != $item['parent']) {
                                    $children = $this->pages_tree($params);
                                }
                            }
                        } else {
                            if ($skip_pages_from_tree == false) {
                                if ($item['id'] != $item['parent']) {
                                    $children = $this->pages_tree(intval($item['id']), $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name = false);
                                }
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

                            if (isset($categories_link)) {
                                $cat_params['link'] = $categories_link;
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

                            if (isset($params['categories_extra_attributes'])) {
                                $cat_params['extra_attributes'] = $params['categories_extra_attributes'];
                            }


                            //$cat_params['for'] = 'content';
                            $cat_params['list_tag'] = $list_tag;
                            $cat_params['list_item_tag'] = $list_item_tag;
                            $cat_params['rel_type'] = 'content';
                            $cat_params['rel_id'] = $item['id'];

                            $cat_params['include_first'] = 1;
                            $cat_params['nest_level'] = $nest_level + 1;
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


                            if (isset($params['categories_ul_class'])) {
                                $cat_params['ul_class'] = $params['categories_ul_class'];
                            }

                            if (isset($params['categories_link_class'])) {
                                $cat_params['link_class'] = $params['categories_link_class'];
                            }


                            if (isset($params['categories_li_class'])) {
                                $cat_params['li_class'] = $params['categories_li_class'];
                            }
                            if (isset($params['categories_ul_class_deep'])) {
                                $cat_params['ul_class_deep'] = $params['categories_ul_class_deep'];
                            }


                            if (isset($params['categories_li_class_deep'])) {
                                $cat_params['li_class_deep'] = $params['categories_li_class_deep'];
                            }

                            if (isset($params['active_class'])) {
                                $cat_params['active_class'] = $params['active_class'];
                            }

                            $this->app->category_manager->tree($cat_params);
                        }
                    }
                    echo "</{$list_item_tag}>";
                }
            }
            if (trim($list_tag) != '') {
                echo "</{$list_tag}>";
            }
        }
        $content = ob_get_contents();
        if ($nest_level_orig == 0) {
            //     $this->app->cache_manager->save($content, $function_cache_id, $cache_group);
        }

        if (isset($list_item_tag) and $list_item_tag and $list_item_tag == 'option') {
            $content = str_replace('</option></option>', '</option>', $content);
        }

        ob_end_clean();
        if (isset($params['return_data'])) {
            return $content;
        } else {
            echo $content;
        }

        return false;
    }

    /**
     * Defines all constants that are needed to parse the page layout.
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
     * @const      PAGE_ID Defines the current page id
     * @const      POST_ID Defines the current post id
     * @const      CATEGORY_ID Defines the current category id if any
     * @const      ACTIVE_PAGE_ID Same as PAGE_ID
     * @const      CONTENT_ID current post or page id
     * @const      MAIN_PAGE_ID the parent page id
     * @const      DEFAULT_TEMPLATE_DIR the directory of the site's default template
     * @const      DEFAULT_TEMPLATE_URL the url of the site's default template
     *
     * @param array|bool $content
     *
     * @option     integer  "id"   [description]
     * @option     string "content_type" [description]
     */
    public function define_constants($content = false)
    {

        $ref_page = false;
        if (isset($_REQUEST['from_url'])) {
            $ref_page = $_REQUEST['from_url'];
        } else if (!defined('MW_BACKEND') and (is_ajax() or defined('MW_API_CALL')) && isset($_SERVER['HTTP_REFERER'])) {
            $ref_page = $_SERVER['HTTP_REFERER'];
        } else {
            $ref_page = url_current(true);
        }

        if (!$content and isset($ref_page) and $ref_page) {
            if ($ref_page != '') {
                $ref_page = strtok($ref_page, '?');

                if ($ref_page == site_url()) {
                    $ref_page = $this->app->content_manager->homepage($ref_page);
                } else {
                    $ref_page = $this->app->content_manager->get_by_url($ref_page);
                }
                if ($ref_page != false and !empty($ref_page)) {
                    $content = $ref_page;
                }
            }
        }

        $page = false;
        $content_orig = $content;

        if (is_array($content)) {
            if (!isset($content['active_site_template']) and isset($content['id']) and $content['id'] != 0) {
                $content = $this->get_by_id($content['id']);
                $page = $content;
            } elseif (isset($content['id']) and $content['id'] == 0) {
                $page = $content;
            } elseif (isset($content['active_site_template'])) {
                $page = $content;
            }

            if ($page == false) {
                $page = $content;
            }
        }

        if (defined('CATEGORY_ID') == false) {

            $cat_url = $this->app->category_manager->get_category_id_from_url();
            if ($cat_url != false) {
                define('CATEGORY_ID', intval($cat_url));
                $this->category_id=intval($cat_url);
            }
        }
        // dd(debug_backtrace(1));
        //    dd(debug_backtrace(1));
//    //    dd(__METHOD__,$content,__LINE__);
//
        if (is_array($page)) {
            if (isset($page['content_type']) and ($page['content_type'] != 'page')) {
                if (isset($page['id']) and $page['id'] != 0) {
                    $content = $page;

                    $current_categorys = $this->app->category_manager->get_for_content($page['id']);
                    if (!empty($current_categorys)) {
                        $current_category = reset($current_categorys);

                        if (defined('CATEGORY_ID') == false and isset($current_category['id'])) {
                            define('CATEGORY_ID', intval($current_category['id']));
                            $this->category_id=intval($current_category['id']);

                        }
                    }

                    $page = $this->get_by_id($page['parent']);

                    if (defined('POST_ID') == false) {
                        define('POST_ID', intval($content['id']));
                        $this->post_id=intval($content['id']);

                    }

                    if (is_array($page) and $page['content_type'] == 'product') {
                        if (defined('PRODUCT_ID') == false) {
                            define('PRODUCT_ID', intval($content['id']));
                            $this->product_id=intval($content['id']);
                        }
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


            if (defined('CONTENT_ID') == false and isset($content['id'])) {
                define('CONTENT_ID', $content['id']);
                $this->content_id=intval($content['id']);

            }

            if (defined('PAGE_ID') == false and isset($content['id'])) {
                define('PAGE_ID', $page['id']);
                $this->page_id=intval($content['id']);

            }

            if (isset($page['parent'])) {
                $parent_page_check_if_inherited = $this->get_by_id($page['parent']);

                if (isset($parent_page_check_if_inherited['layout_file']) and $parent_page_check_if_inherited['layout_file'] == 'inherit') {
                    $inherit_from_id = $this->get_inherited_parent($parent_page_check_if_inherited['id']);

                    if (defined('MAIN_PAGE_ID') == false) {
                        define('MAIN_PAGE_ID', $inherit_from_id);
                        $this->main_page_id=intval($inherit_from_id);

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
                if (defined('PARENT_PAGE_ID') == false and isset($content['parent'])) {
                    define('PARENT_PAGE_ID', $content['parent']);
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
            $cat_id = $this->app->category_manager->get_category_id_from_url();
            if ($cat_id != false) {
                define('CATEGORY_ID', intval($cat_id));
                $this->category_id=intval($cat_id);

            }
        }
        if (!defined('CATEGORY_ID')) {
            define('CATEGORY_ID', false);
        };
        if (defined('PAGE_ID') == false) {
            $getPageSlug = $this->app->permalink_manager->slug($ref_page, 'page');
            $pageFromSlug = $this->app->content_manager->get_by_url($getPageSlug);
            if ($pageFromSlug) {
                $page = $pageFromSlug;
                $content = $pageFromSlug;
                define('PAGE_ID', intval($page['id']));
                $this->page_id=intval($page['id']);

            }
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

        if (isset($page) and isset($page['active_site_template']) and $page['active_site_template'] != '' and strtolower($page['active_site_template']) != 'inherit' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($page) and isset($page['active_site_template']) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {
            $the_active_site_template = $page['active_site_template'];
        } elseif (isset($content) and isset($content['active_site_template']) and ($content['active_site_template']) != '' and strtolower($content['active_site_template']) != 'default') {
            $the_active_site_template = $content['active_site_template'];
        }elseif (isset($content_orig) and  !isset($content_orig['id']) and isset($content_orig['active_site_template']) and ($content_orig['active_site_template']) != '' and strtolower($content_orig['active_site_template']) != 'default' and strtolower($content_orig['active_site_template']) != 'inherit') {
            $the_active_site_template = $content_orig['active_site_template'];
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
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) != '' and strtolower($inh_parent['active_site_template']) == 'default') {
                    $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
                } elseif (isset($inh_parent['active_site_template']) and ($inh_parent['active_site_template']) == '') {
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
                                define('THIS_TEMPLATE_URL', $the_template_url);
                            }
                            if (defined('TEMPLATE_URL') == false) {
                                define('TEMPLATE_URL', $the_template_url);
                            }
                        }
                        $the_active_site_template = 'default';
                        $the_active_site_template_dir = DEFAULT_TEMPLATE_DIR;
                    }
                }
            }
        }


        $this->template_name = $the_active_site_template;
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
                define('THIS_TEMPLATE_URL', $the_template_url);
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
            define('TEMPLATE_URL', $the_template_url);
        }

        if (defined('LAYOUTS_DIR') == false) {
            $layouts_dir = TEMPLATE_DIR . 'layouts/';

            define('LAYOUTS_DIR', $layouts_dir);
        } else {
            $layouts_dir = LAYOUTS_DIR;
        }

        if (defined('LAYOUTS_URL') == false) {
            $layouts_url = reduce_double_slashes($this->app->url_manager->link_to_file($layouts_dir) . '/');

            define('LAYOUTS_URL', $layouts_url);
        }

        if (defined('CATEGORY_ID') == false) {
            define('CATEGORY_ID', false);
        }


        if (defined('PAGE_ID') == false) {
            define('PAGE_ID', false);
        }

        if (defined('POST_ID') == false) {
            define('POST_ID', false);
        }

        if (defined('MAIN_PAGE_ID') == false) {
            define('MAIN_PAGE_ID', false);
        }

        return true;
    }


    /**
     *  Get the first parent that has layout.
     *
     * @category   Content
     *
     * @uses       $this->get_parents()
     * @uses       $this->get_by_id()
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

    /***
     * @param $id
     * @param $params
     * @return false|string
     */
    public function get_parents_as_links($id, $params = [])
    {
        $implodeSymbol = ' &rarr; ';

        if (isset($params['implode_symbol'])) {
            $implodeSymbol = $params['implode_symbol'];
        }

        $class = '';
        if (isset($params['class'])) {
            $class = $params['class'];
        }

        $parentTitles = [];
        $parents = $this->get_parents($id);
        if (!empty($parents)) {
            foreach ($parents as $parentId) {
                $editLink = content_edit_link($parentId);
                $parentTitles[] = '<a href="'.$editLink.'" class="'.$class.'">' . $this->title($parentId) . '</a>';
            }
        }

        $parentTitles = array_reverse($parentTitles);
        if (!empty($parentTitles)) {
            return implode($implodeSymbol, $parentTitles);
        }

        return false;
    }

    /**
     * @param $id
     * @param $implodeSymbol
     * @return false|string
     */
    public function get_parents_as_text($id, $implodeSymbol = ' &rarr; ')
    {
        $parentTitles = [];
        $parents = $this->get_parents($id);
        if (!empty($parents)) {
          foreach ($parents as $parentId) {
              $parentTitles[] = $this->title($parentId);
          }
        }
        $parentTitles = array_reverse($parentTitles);

        if (!empty($parentTitles)) {
          return implode($implodeSymbol, $parentTitles);
        }

        return false;
    }

    public function get_parents($id = 0, $without_main_parrent = false)
    {
        if (intval($id) == 0) {
            return false;
        }

        $ids = array();
        $get = array();
        $get['id'] = $id;
        $get['limit'] = 1;
        $get['fields'] = 'id,parent';
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

    /**
     * Get the current language of the site.
     *
     * @example
     * <code>
     *  $current_lang = current_lang();
     *  print $current_lang;
     * </code>
     *
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
        $lang = sanitize_path($lang);
        if (!defined('MW_LANG') and isset($lang)) {
            define('MW_LANG', $lang);
        }

        return $lang;
    }

    /**
     * Set the current language.
     *
     * @example
     * <code>
     *   //sets language to Spanish
     *  set_language('es');
     * </code>
     */
    public function lang_set($lang = 'en')
    {
        $lang = sanitize_path($lang);
        setcookie('lang', $lang);

        return $lang;
    }

    public function breadcrumb($params = false)
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


        $start_from = false;
        if (isset($params['start_from'])) {
            $start_from = trim($params['start_from']);
        }

        if ($cur_page != false) {
            if ($start_from != 'category') {

                $content = $this->get_by_id($cur_page);
                if (isset($content['id'])) {
                    $result_item = array();
                    $result_item['title'] = $content['title'];
                    $result_item['url'] = $this->link($content['id']);
                    $result_item['description'] = $content['description'];
                    $result_item['is_active'] = false;

                    if ($cur_content == $content['id']) {
                        $result_item['is_active'] = true;
                    } elseif ($cur_content != false and $cur_page == $content['id']) {
                        $result_item['is_active_as_parent'] = true;
                        $result_item['is_active'] = false;
                    } elseif ($cur_category == false and $cur_content == false and $cur_page == $content['id']) {
                        $result_item['is_active'] = true;
                    } else {
                        $result_item['is_active'] = false;
                    }
                    $result_item['parent_content_id'] = $content['parent'];
                    $result_item['content_type'] = $content['content_type'];
                    $result_item['subtype'] = $content['subtype'];
                    $result[] = $result_item;
                }


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

                if ($result) {
                    $result = array_reverse($result);
                }
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

                                if (isset($params['current-page-as-root']) and $params['current-page-as-root'] != false) {
                                    $result_item['url'] = page_link() . '/category:' . $content['id'];
                                } else {
                                    $result_item['url'] = $this->app->category_manager->link($content['id']);
                                }


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
     * Gets a link for given content id.
     *
     * If you don't pass id parameter it will try to use the current page id
     *
     * @param int $id The $id The id of the content
     *
     * @return string The url of the content
     *
     * @see     post_link()
     * @see     page_link()
     * @see     content_link()
     *
     * @example
     * <code>
     * print $this->link($id=1);
     * </code>
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
        if (!$link) {
            return;
        }

        $site_url = $this->app->url_manager->site();

        if (isset($link['is_home']) and intval($link['is_home']) == 1) {
            return $site_url;
        }

        if (!isset($link['url']) or strval($link['url']) == '') {
            $link = $this->get_by_url($id);
        }
        if (!$link) {
            return;
        }

        $permalinkGenerated = $this->app->permalink_manager->link($link['id'], 'content');

        if ($permalinkGenerated) {
            $link['url'] = $permalinkGenerated;

            if (!stristr($link['url'], $site_url)) {
                $link = site_url($link['url']);
            } else {
                $link = ($link['url']);
            }
            return $link;
        }

    }

    public function edit_link($id = 0)
    {
        $content = $this->get_by_id($id);

        if (isset($content['content_type']) && $content['content_type'] == 'product') {
            return route('admin.product.edit', $id);
        }

        if (isset($content['content_type']) && $content['content_type'] == 'post') {
            return route('admin.post.edit', $id);
        }

        if (isset($content['content_type']) && $content['content_type'] == 'page') {
            return route('admin.page.edit', $id);
        }

        return route('admin.content.edit', $id);

    }

    public function save_edit($post_data)
    {
        return $this->helpers->save_from_live_edit($post_data);
    }

    /**
     * Returns the homepage as array.
     *
     * @category Content
     */
    public function homepage()
    {
        $get = array();
        $get['is_home'] = 1;
        $get['single'] = 1;

        $data = app()->content_repository->getByParams($get);

        return $data;
    }

// ------------------------------------------------------------------------

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

            if (isset($data['created_at'])) {
                unset($data['created_at']);
            }

            if (isset($data['updated_at'])) {
                unset($data['updated_at']);
            }
            if (isset($data['id']) and $data['id'] != 0 and $author_id != 0) {
                $page_data_to_check_author = $this->get_by_id($data['id']);

                if (!isset($page_data_to_check_author['created_by']) or ($page_data_to_check_author['created_by'] != $author_id)) {
                    $stop = true;

                    return array('error' => "You don't have permission to edit this content");
                } elseif (isset($page_data_to_check_author['created_by']) and ($page_data_to_check_author['created_by'] == $author_id)) {
                    $stop = false;
                }
            } elseif ($author_id == false) {
                return array('error' => 'You must be logged to save content');
            }

            if (isset($data['id']) and $data['id'] != 0) {
                if (!is_admin()) {
                    $check = get_content_by_id($data['id']);
                    if ($check['created_by'] != user_id()) {
                        return array('error' => 'Wrong content');
                    }
                }
            }

            if (isset($data['is_home'])) {
                if (!is_admin()) {
                    unset($data['is_home']);
                }
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
                        // $cap = $this->app->user_manager->session_get('captcha');
                        if (!$this->app->captcha_manager->validate($data['captcha'])) {
                            return array('error' => 'You must load a captcha first!');
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
                        if (isset($item['users_can_create_content']) and $item['users_can_create_content'] == 1) {
                            $user_cats[] = $item['id'];
                            $cont_cat = $this->get('limit=1&content_type=page&subtype_value=' . $item['id']);
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

    public function save_content($data, $delete_the_cache = true)
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }

        $this->app->event_manager->trigger('content.manager.before.save', $data);
        $data_to_save = $data;
        $save = $this->crud->save($data);
        $id = $save;
        if (isset($data_to_save['add_content_to_menu']) and is_array($data_to_save['add_content_to_menu'])) {
            foreach ($data_to_save['add_content_to_menu'] as $menu_id) {
                $ids_to_save = $save;
                $this->helpers->add_content_to_menu($ids_to_save, $menu_id);
            }
        }
        $after_save = $data_to_save;
        $after_save['id'] = $id;

        $this->app->event_manager->trigger('content.manager.after.save', $after_save);
        event_trigger('mw_save_content', $save);

        return $save;
    }

    public function custom_fields($content_id, $full = true, $field_type = false)
    {
        $filter = [];
        $filter['rel_type'] = 'content';
        $filter['rel_id'] = $content_id;
        if ($full) {
            $filter['return_full'] = $full;
        }
        if ($field_type) {
            $filter['type'] = $field_type;
        }

        return $this->app->fields_manager->get($filter);
    }

    public function save_content_field($data, $delete_the_cache = true)
    {
        return $this->helpers->save_content_field($data);
    }

    public function edit_field($data)
    {
        return $this->crud->get_edit_field($data);
    }

    public function save($data, $delete_the_cache = true)
    {
        return $this->save_content($data, $delete_the_cache);
    }

    public function prev_content($content_id = false)
    {
        return $this->next_content($content_id, $mode = 'prev');
    }

    public function next_content($content_id = false, $mode = 'next', $content_type = false)
    {
        if ($content_id == false) {
            if (defined('POST_ID') and POST_ID != 0) {
                $content_id = POST_ID;
            } elseif (defined('PAGE_ID') and PAGE_ID != 0) {
                $content_id = PAGE_ID;
            } elseif (defined('MAIN_PAGE_ID') and MAIN_PAGE_ID != 0) {
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
        $contentData = $this->get_by_id($content_id);
        if ($contentData == false) {
            return false;
        }

        if ($contentData['position'] == null) {
            $contentData['position'] = 0;
        }

        $query = \MicroweberPackages\Content\Content::query()->select('content.*');
        $categories = array();
        $params = array();

        $parent_id = false;
        if (isset($contentData['parent']) and $contentData['parent'] > 0) {
            $parent_id = $contentData['parent'];
        }

        if ($content_type) {
            if (defined('PAGE_ID') and PAGE_ID != 0) {
                $parent_id = PAGE_ID;
            }
        } elseif (isset($contentData['content_type'])) {
            $content_type = $contentData['content_type'];
        }

        if (isset($contentData['content_type']) and $contentData['content_type'] != 'page') {

            if (trim($mode) == 'prev') {
                $query->orderBy('position', 'desc');
                $query->where('position', '<', $contentData['position']);
            } else {
                $query->orderBy('position', 'asc');
                $query->where('position', '>', $contentData['position']);
            }

            $cats = $this->app->category_manager->get_for_content($content_id);
            if (!empty($cats)) {
                foreach ($cats as $cat) {
                    $categories[] = $cat['id'];
                }
                $query->whereCategoryIds($categories);
            }

        } else {

            if (isset($contentData['position']) and $contentData['position'] > 0) {
                if (trim($mode) == 'prev') {
                    $query->where('position', '>', $contentData['position']);
                } else {
                    $query->where('position', '<', $contentData['position']);
                }
            }

            if (trim($mode) == 'prev') {
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'asc');
            }
        }

        $params['exclude_ids'] = array($content_id);

        if ($parent_id) {
            $query->whereParent($parent_id);
        }

        $query->whereContentType($content_type);
        $query->whereIsActive(1);
        $query->whereIsDeleted(0);

        $response = [];
        $get = $query->first();
        if ($get != null) {
            $response = $get->toArray();
        }

        if (is_array($response)) {
            return $response;
        } else {
            return false;
        }
    }

    public function reorder($params)
    {
        $id = $this->app->user_manager->is_admin();
        if ($id == false) {
            return array('error' => 'You must be admin to reorder content!');
        }

        return $this->crud->reorder($params);
    }

    /**
     * Set content to be unpublished.
     *
     * Set is_active flag 'n'
     *
     * @param string|array|bool $params
     *
     * @return string The url of the content
     *
     * @uses       $this->save_content()
     *
     * @see        content_set_unpublished()
     *
     * @example
     * <code>
     * //set published the content with id 5
     * content_set_unpublished(5);
     *
     * //alternative way
     * content_set_unpublished(array('id' => 5));
     * </code>
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

                return $save_data;
            }
        }
    }

    /**
     * Set content to be published.
     *
     * Set is_active flag 'y'
     *
     * @param string|array|bool $params
     *
     * @return string The url of the content
     *
     * @uses       $this->save_content()
     *
     * @example
     * <code>
     * //set published the content with id 5
     * api/content/set_published(5);
     *
     * //alternative way
     * api/content/set_published(array('id' => 5));
     * </code>
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

                return $save_data;
            }
        }
    }

    public function save_content_data_field($data, $delete_the_cache = true)
    {
        return $this->app->data_fields_manager->save($data);
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
            } elseif (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }
        $content = $this->get_by_id($id);
        if (isset($content['title'])) {
            return $content['title'];
        }
    }


    public function description($id)
    {
        $descr = false;

        if ($id == false or $id == 0) {
            if (defined('CONTENT_ID') == true) {
                $id = CONTENT_ID;
            } elseif (defined('PAGE_ID') == true) {
                $id = PAGE_ID;
            }
        }
        $meta = $this->get_by_id($id);
        if (!$meta) {
            return;
        }

        if (isset($meta['description']) and $meta['description'] != '') {
            $descr = $meta['description'];
        } else if (isset($meta['content_meta_description']) and $meta['content_meta_description'] != '') {
            $descr = $meta['content_meta_description'];
        } else if (isset($meta['content_body']) and $meta['content_body'] != '') {
            $descr = strip_tags($meta['content_body']);
        } else if (isset($meta['content']) and $meta['content'] != '') {
            $descr = strip_tags($meta['content']);
        }

        if ($descr) {
            $descr = trim($descr);
        }
        if ($descr) {
            if ($descr == 'mw_saved_inner_edit_from_parent_edit_field') {
                $descr_from_edit_field = $this->app->content_manager->edit_field("rel_type=content&rel_id=" . $id);
                if ($descr_from_edit_field) {
                    $descr_from_edit_field = trim(strip_tags($descr_from_edit_field));
                }
                if ($descr_from_edit_field) {
                    $descr = trim($descr_from_edit_field);
                }

            }
        }
        if ($descr) {
            return $descr;
        }
    }

    public function site_templates()
    {
        //shim for old versions
        return $this->app->template->site_templates();
    }


    public function get_related_content_ids_for_content_id($content_id = false)
    {

        return   $this->app->content_repository->getRelatedContentIds($content_id);

    }


}
