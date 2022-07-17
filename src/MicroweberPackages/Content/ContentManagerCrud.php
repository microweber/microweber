<?php
namespace MicroweberPackages\Content;

use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Database\Crud;
use Illuminate\Support\Facades\DB;
use function Opis\Closure\serialize as serializeClosure;

class ContentManagerCrud extends Crud
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public $table = 'content';

    public $tables = array();
    public static $precached_links = array();
    public static $skip_pages_starting_with_url = ['admin', 'api', 'module'];

    /** @var ContentRepository */
   // public $content_repository;

    /**
     *  Boolean that indicates the usage of cache while making queries.
     *
     * @var
     */

    public $no_cache = false;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
        $this->set_table_names();

      //  $this->content_repository = $this->app->repository_manager->driver(\MicroweberPackages\Content\Content::class);



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

        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        if (!is_array($params)) {
            $params = array();
            $params['is_active'] = 1;
        }

        $do_not_replace_site_url = false;
        if (isset($params['do_not_replace_site_url'])) {
            $do_not_replace_site_url = $params['do_not_replace_site_url'];
        }

        $cache_group = 'content';
        if (isset($params['cache_group'])) {
            $cache_group = $params['cache_group'];
        }
        $table = $this->tables['content'];
        if (!isset($params['is_deleted'])) {
            $params['is_deleted'] = 0;
        }
        $params['table'] = $table;
        $params['cache_group'] = $cache_group;
        if (isset($params['id'])) {
            $params['id'] = intval($params['id']);
        }

        if (isset($params['search_by_keyword'])) {
            $params['keyword'] = $params['search_by_keyword'];
        }

        if (isset($params['category']) and is_numeric($params['category'])) {
            $params['category'] = intval($params['category']);
            //check if this is dynamic category
            $cat_id = $params['category'];
            $category = $this->app->category_manager->get_by_id($cat_id);
            if (is_array($category)
                and isset($category['category_subtype'])
                and ($category['category_subtype'] == 'content_filter')
                and isset($category['category_subtype_settings'])
                and isset($category['category_subtype_settings']['filter_content_by_keywords'])
                and trim($category['category_subtype_settings']['filter_content_by_keywords']) != ''
            ) {
                $params['keyword'] = $category['category_subtype_settings']['filter_content_by_keywords'];
            }
        }


        if (isset($params['keyword']) and !isset($params['search_in_fields'])) {
            $params['search_in_fields'] = array('title', 'content_body', 'content', 'description', 'content_meta_keywords', 'content_meta_title', 'url');
        }
        if (isset($params['keyword'])) {
            if (!is_admin()) {
                $params['is_deleted'] = 0;
                $params['is_active'] = 1;
            }
        }

        $extra_data = false;
        if (isset($params['get_extra_data'])) {
            $extra_data = true;
        }

         if (isset($params['filter-only-in-stock'])) {
            $params['__query_get_only_in_stock'] = function ($query){
                return $query->whereIn('content.id', function ($subQuery)  {
                    $subQuery->select('content_data.content_id');
                    $subQuery->from('content_data');
                    $subQuery->where('content_data.field_name', '=', 'qty');
                    $subQuery->where('content_data.field_value', '!=','0');
                });
              };
             unset($params['filter-only-in-stock']);
         }
//        if (isset($params['related'])) {
//              \Config::set('microweber.disable_model_cache',1);
//             $params['__query_try_to_get_related_content_from_table'] = function ($query){
//                 return $query->whereIn('content.id', function ($subQuery)  {
//                     $subQuery->select('content_related.related_content_id');
//                     $subQuery->from('content_related');
//                     $subQuery->where('content_related.content_id', '=', 'content.id');
//                 });
//             };
//             $params['no_cache'] = 1;
//             $params['category'] = null;
//             $params['exclude_ids'] = null;
//
//              unset($params['try_to_get_related_content']);
//              unset($params['try_to_get_related_content']);
//              unset($params['related']);
//
//
//        }
//

/*
        if (isset($params['category']) || isset($params['categories'])) {
            $findByCategoryIds = [];

            $params['__query_get_with_categories'] = function ($query) {
                return $query->whereIn('content.id', function ($subQuery)  {
                     $subQuery->select('categories_items.id');
                     $subQuery->from('categories_items');
                     $subQuery->where('categories_items.rel_id', '=', 'content.id');
                     $subQuery->where('categories_items.rel_type', '=', 'content');
                 });
            };
        }*/

    /*    if (isset($params['category-id'])) {
            dump($params);
        }*/





        if (!isset($params['fields']) and !isset($params['count']) and !isset($params['count_paging']) and !isset($params['page_count'])) {
            $get = false;
            $params['fields'] = 'id';
            $getIds = app()->content_repository->getByParams($params);



            if ($getIds) {
                if(isset($params['single']) or isset($params['one'])){
                    $getIds = array_values($getIds);
                    $getOne = app()->content_repository->getById(array_pop($getIds));


                    if($getOne){
                        if(!isset($getOne[0]) and !empty($getOne)){
                            $get = $getOne;
                        } elseif(isset($getOne[0])) {
                        $get = $getOne[0] ;
                        }
                        unset($getOne);
                    }
                 } else {
                    if(is_numeric($getIds)){
                    $get = app()->content_repository->getById($getIds);
                    } else {
                    $get = app()->content_repository->getById(array_values(array_flatten($getIds)));
                    }

                }

            }
        } else {
            $get = app()->content_repository->getByParams($params);

        }
//
        if(isset($params['page_count'])) {
//            dump($params);
//            dump($get);
        }
      //$get = app()->content_repository->getByParams($params);




        //$get = parent::get($params);
        /*
         if (isset($get['id'])) {
           if ($get['id'] != $get2['id']) {
               echo $get['id'] .'[--]'. $get2['id'].'<br />';
           }
       }*/

       //  echo '<pre>' . print_r([$params], true) .'</pre>';

        if (isset($params['count']) or isset($params['single']) or isset($params['one']) or isset($params['data-count']) or isset($params['page_count']) or isset($params['data-page-count'])) {
            if (isset($get['url'])) {
                if (!$do_not_replace_site_url) {
                    $get['full_url'] = $this->app->url_manager->site($get['url']);
                }
            }

            return $get;
        }

        if (is_array($get)) {
            $data2 = array();
            foreach ($get as $item) {
                if (isset($item['url'])) {
                    if (!$do_not_replace_site_url) {
                        $item['url'] = $this->app->url_manager->site($item['url']);
                    }
                }
                if ($extra_data) {
                //    $item['picture'] = get_picture($item['id']);
                 //   $item['content_data'] = content_data($item['id']);
                }

                $data2[] = $item;
            }
            $get = $data2;

            return $get;
        }
    }

    public function get_by_url($url = '', $no_recursive = false)
    {
        static $passed = array();

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
        /*
         * do not use precached links this will broke the multilanguage
        if (isset(self::$precached_links[$link_hash])) {
             return self::$precached_links[$link_hash];
        }*/

        if ($url == '') {
            $content = $this->app->content_manager->homepage();
        } else {
            /* $get = array();
             $get['url'] = $url;
             $get['single'] = true;

             $content = $this->get($get);

             if(!$content){
                 $get = $this->app->event_manager->trigger('content.get_by_url.not_found', $get);
                 if (is_array($get) && isset($get[0])) {
                     $content = $this->get($get[0]);
                 }
             }*/

            $contentSlug = $url;
            $pageSlug = $this->app->permalink_manager->slug($url, 'page');
            $postSlug = $this->app->permalink_manager->slug($url, 'post');
            if ($pageSlug) {
                $contentSlug = $pageSlug;
            }
            if ($postSlug) {
                $contentSlug = $postSlug;
            }

            $get = $this->app->event_manager->trigger('app.content.get_by_url', $contentSlug);
            if (is_array($get) && isset($get[0]) && !empty($get[0])) {
                $content = $get[0];
            } else {

                $get = array();
                $get['url'] = $contentSlug;
                $get['single'] = true;

                $content = $this->app->content_repository->getByParams($get);
            }
        }

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
                        if (!$url) {
                            $test[0] = urldecode($test[0]);
                            $url = $this->get_by_url($test[0], true);
                        }
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

    public function save($data, $delete_the_cache = true)
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


        // clear the cache because the url can change on saving
        self::$precached_links = [];



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
        if (isset($data['parent'])) {
            $data['parent'] = intval($data['parent']);
        }


        if (isset($data['is_active'])) {
            if ($data['is_active'] === 'y') {
                $data['is_active'] = 1;
            } elseif ($data['is_active'] === 'n') {
                $data['is_active'] = 0;
            }
        }

        $data_to_save = $data;
        if (!isset($data['title']) and isset($data['content_title'])) {
            $data['title'] = $data['content_title'];
        }
        if (isset($data['title'])) {
            $data['title'] = strip_tags($data['title']);

            //$data['title'] = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');

            $data_to_save['title'] = $data['title'];
        }
        $thetitle = false;
        $theurl = false;
        if (!isset($data['url']) and intval($data['id']) != 0) {
            $q = $this->get_by_id($data_to_save['id']);
            if(isset($q['title'])){
                $thetitle = $q['title'];
            }
            if(isset($q['url'])){
                $q = $q['url'];
                $theurl = $q;
            }

        } else {
            $thetitle = date("Ymdhis");
            if (isset($data['title']) and $data['title']) {
                $thetitle = $data['title'];
            }

            if (isset($data['url'])) {
                $theurl = $data['url'];
            } elseif (isset($data['title'])) {
                $theurl = $data['title'];

            }

        }

        if (isset($data['id']) and intval($data['id']) == 0) {
            if (!isset($data['is_deleted']) or ($data['is_deleted']) == '') {
                $data_to_save['is_deleted'] = 0;
            } else {
                $data_to_save['is_deleted'] = $data['is_deleted'];
            }

            if (!isset($data['title']) or ($data['title']) == '') {
                $data['title'] = 'New page';
                if (isset($data['content_type']) and ($data['content_type']) != 'page') {
                    $data['title'] = 'New ' . $data['content_type'];
                    if (isset($data['subtype']) and ($data['subtype']) != 'page' and ($data['subtype']) != 'post' and ($data['subtype']) != 'static' and ($data['subtype']) != 'dynamic') {
                        $data['title'] = 'New ' . $data['subtype'];
                    }
                }
                $data_to_save['title'] = $data['title'];
            }
        }

        if (isset($data['url']) == false or $data['url'] == '') {
            if (isset($data['title']) != false and intval($data ['id']) == 0) {
                $data['url'] = $this->app->url_manager->slug($data['title']);
                if ($data['url'] == '') {
                    $data['url'] = date('Y-M-d-His');
                }
            }
        }
        $url_changed = false;


        if (isset($data['url']) != false and is_string($data['url'])) {
            $search_weird_chars = array('%E2%80%99',
                '%E2%80%99',
                '%E2%80%98',
                '%E2%80%9C',
                '%E2%80%9D',
            );
            $str = $data['url'];


            $newstr = $str;
            /* $good[] = 9; #tab
             $good[] = 10; #nl
             $good[] = 13; #cr
             for ($a = 32; $a < 127; ++$a) {
                 $good[] = $a;
             }
             $newstr = '';
             $len = strlen($str);
             for ($b = 0; $b < $len + 1; ++$b) {
                 if (isset($str[$b]) and in_array(ord($str[$b]), $good)) {
                     $newstr .= $str[$b];
                 }
             }


             */

            //  $newstr = str_replace('--', '-', $newstr);
            // $newstr = str_replace('--', '-', $newstr);
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

        if ((isset($data['id']) and intval($data['id']) == 0) or !isset($data['id'])) {

            if (isset($data['auto_discover_id']) and $data['auto_discover_id']) {
                $try_id = false;
                if (isset($data['url'])) {
                    $try_id = $this->get_by_id($data['url'], 'url');
                }
                if (!$try_id and isset($data['title'])) {
                    $try_id = $this->get_by_id($data['title'], 'title');
                }
                if ($try_id and is_array($try_id) and isset($try_id['id'])) {
                    $data_to_save['id'] = $data['id'] = $try_id['id'];
                }
            }
        }
        if (isset($data['url']) and $data['url'] != false) {
            if (trim($data['url']) == '') {
                $data['url'] = $this->app->url_manager->slug($data['title']);
            }

             $data['url'] = $this->app->database_manager->escape_string($data['url']);


            $date123 = date('YmdHis');

            $get = array();
            $get['url'] = $data['url'];
            $get['single'] = true;
            $q = $this->get($get);


            if (!empty($q)) {
                if (!isset($data['id']) or !isset($q['id']) or  $data['id'] != $q['id']) {
                    $orig_slug = $data['url'];
                    $slug = $data['url'];
                    $count = 1;
                    while ($this->get_by_url($slug, true)) {
                        $slug = $orig_slug . '-' . $count++;
                    }
                    $data['url'] = $slug;
                    $data_to_save['url'] = $data['url'];
                }
            }

            if (isset($data_to_save['url']) and strval($data_to_save['url']) != '') {
                $check_cat_wth_slug = $this->app->category_manager->get_by_url($data_to_save['url']);
                if($check_cat_wth_slug){
                    $data_to_save['url'] = $data_to_save['url'] . '-' . $date123;
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
            if ($data_to_save['url'] == '') {
                $data_to_save['url'] = date('Y-M-d-His');
            }
            $data_to_save['url'] = str_replace(site_url(), '', $data_to_save['url']);
        }

        if (isset($data['created_at'])) {
            $data_to_save['created_at'] = ($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            $data_to_save['updated_at'] = ($data['updated_at']);
        }

        $data_to_save_options = array();
        if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'y') {
            $data_to_save['is_home'] = 1;
        } elseif (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'n') {
            $data_to_save['is_home'] = 0;
        }

        if (isset($data_to_save['is_shop']) and $data_to_save['is_shop'] === 'y') {
            $data_to_save['is_shop'] = 1;
        } elseif (isset($data_to_save['is_shop']) and $data_to_save['is_shop'] === 'n') {
            $data_to_save['is_shop'] = 0;
        }

        if (isset($data_to_save['require_login']) and $data_to_save['require_login'] === 'y') {
            $data_to_save['require_login'] = 1;
        } elseif (isset($data_to_save['require_login']) and $data_to_save['require_login'] === 'n') {
            $data_to_save['require_login'] = 0;
        }

        if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 1) {
            $data_to_save['is_home'] = strval($data_to_save['is_home']);
            if ($adm == true) {
                $q = Content::where('is_home', 1)
                    ->update(array(
                        'is_home' => 0,
                    ));
            } else {
                $data_to_save['is_home'] = 0;
            }
            //
        }

        if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'post') {
            if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'static') {
                $data_to_save['subtype'] = 'post';
            } elseif (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
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

                    $par_page_new = $this->app->database_manager->save($table, $par_page_new);
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
                                    if (isset($cont_cat['subtype_value']) and intval($cont_cat['subtype_value']) > 0) {
                                        $data_to_save['parent'] = $cont_cat['id'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $allow_remote_images_download = false;
        if ($adm == true and isset($data['download_remote_images']) and $data['download_remote_images'] != false) {
            $allow_remote_images_download = true;
        }

        if (isset($data_to_save['content'])) {
            if (trim($data_to_save['content']) == '' or $data_to_save['content'] == false) {
                $data_to_save['content'] = null;
            } else {
                if ($allow_remote_images_download) {
                    $data_to_save['content'] = $this->app->content_manager->helpers->download_remote_images_from_text($data_to_save['content']);
                }

                $data_to_save['content'] = $this->app->parser->make_tags($data_to_save['content']);
            }
        }

        if (!isset($data_to_save['updated_at'])) {
            $data_to_save['updated_at'] = date('Y-m-d H:i:s');
        }

        if ((isset($data_to_save['id']) and intval($data_to_save['id']) == 0) or !isset($data_to_save['id'])) {


            if (!isset($data_to_save['position']) or intval($data_to_save['position']) == 0) {
                $pos_params = array();
                $pos_params['table'] = 'content';
                if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'page') {
                    $pos_params['content_type'] = $data_to_save['content_type'];
                    $pos_params['min'] = 'position';
                } else {
                    $pos_params['max'] = 'position';
                }
                $get_max_pos = $this->app->database_manager->get($pos_params);
                if (is_null($get_max_pos)) {
                    $data_to_save['position'] = 1;
                } elseif (is_int($get_max_pos) or is_string($get_max_pos)) {
                    if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'page') {
                        $data_to_save['position'] = intval($get_max_pos) - 1;
                    } else {
                        $data_to_save['position'] = intval($get_max_pos) + 1;
                    }
                }
            }
            $data_to_save['posted_at'] = $data_to_save['updated_at'];
        }

        $cats_modified = true;

        if (isset($data['auto_discover_parent']) and $data['auto_discover_parent']) {

            if (!isset($data_to_save['parent']) or !$data_to_save['parent']) {

                $parent_auto_found = false;
                if (isset($data_to_save['url']) and $data_to_save['url']) {
                    $get_url_segs = explode('/', $data_to_save['url']);

                    if ($get_url_segs and !empty($get_url_segs)) {

                        $count = count($get_url_segs);
                        for ($i = 1; $i <= $count; $i++) {
                            if (!$parent_auto_found) {
                                $url_try = implode('/', $get_url_segs);

                                if ($url_try) {
                                    $try_id = $this->get_by_id($url_try, 'url');
                                    if (!$try_id) {
                                        $url_try = rtrim($url_try, '/');
                                        $try_id = $this->get_by_id($url_try, 'url');
                                    }
                                    if ($try_id and isset($try_id['id']) and $try_id['id'] != $data['id']) {
                                        $parent_auto_found = $try_id['id'];
                                    }
                                }
                            }
                            array_pop($get_url_segs);
                        }
                    }
                }

                if ($parent_auto_found) {
                    $data_to_save['parent'] = $parent_auto_found;
                } else {
                    $data_to_save['parent'] = 0;
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
            unset($data_to_save['custom_field_help_text']);
        }
        if (isset($data_to_save['error_text'])) {
            unset($data_to_save['error_text']);
            unset($data_to_save['error_text']);
        }
        if (isset($data_to_save['custom_field_is_active'])) {
            unset($data_to_save['custom_field_is_active']);
        }
        if (isset($data_to_save['custom_field_width_size'])) {
        	unset($data_to_save['custom_field_width_size']);
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

        $data_fields = array();
        if (!empty($orig_data)) {
            $data_str = 'data_';
            $data_str_l = strlen($data_str);
            foreach ($orig_data as $k => $v) {
                if (is_string($k)) {
                    if (strlen($k) > $data_str_l) {
                        $rest = substr($k, 0, $data_str_l);
                        $left = substr($k, $data_str_l, strlen($k));
                        if ($rest == $data_str) {
                            if (!isset($data_to_save['data_fields'])) {
                                $data_to_save['data_fields'] = array();
                            }
                            $data_to_save['data_fields'][$left] = $v;
                        }
                    }
                }
            }
        }

        if (isset($data_to_save['parent']) and $data_to_save['parent'] != 0) {
            if (isset($data_to_save['id']) and $data_to_save['id'] != 0) {
                if ($data_to_save['parent'] == $data_to_save['id']) {
                    $data_to_save['parent'] = 0;
                }
            }
        }

        $data_to_save = $this->map_params_to_schema($data_to_save);

        $this->clearCache();
        $this->app->content_repository->clearCache();
        $this->app->permalink_manager->clearCache();

        $save = $this->app->database_manager->extended_save($table, $data_to_save);

        /* SQLITE FIX */
        if ($adm == true) {
            if (isset($data_to_save['is_home'])) {
                $q = Content::where('id', $save)
                    ->update(array(
                        'is_home' => intval($data_to_save['is_home']),
                    ));
            }
            if (isset($data_to_save['is_shop'])) {
                $q = Content::where('id', $save)
                    ->update(array(
                        'is_shop' => intval($data_to_save['is_shop']),
                    ));
            }

            if (isset($data_to_save['require_login'])) {
                $q = Content::where('id', $save)
                    ->update(array(
                        'require_login' => intval($data_to_save['require_login']),
                    ));
            }
        }
        /* END SQLITE FIX */

        $id = $save;
        if (isset($data_to_save['parent']) and $data_to_save['parent'] != 0) {
            $upd_posted = array();
            $upd_posted['posted_at'] = $data_to_save['updated_at'];
            $upd_posted['id'] = $data_to_save['parent'];
            $save_posted = $this->app->database_manager->save($table, $upd_posted);
        }
        $after_save = $data_to_save;
        $after_save['id'] = $id;
        $this->app->event_manager->trigger('content.after.save', $after_save);
        $this->app->cache_manager->delete('content/' . $save);

        $this->app->cache_manager->delete('content_fields');
        $this->app->cache_manager->delete('repositories');
        $this->app->cache_manager->delete('db');
        if ($url_changed != false) {
            $this->app->cache_manager->delete('menus');
            $this->app->cache_manager->delete('categories');
        }
        $this->app->cache_manager->delete('options');
        $this->app->option_repository->clearCache();
        $this->app->content_repository->clearCache();
        $this->app->database_manager->clearCache();

        $this->clearCache();

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
                        if ($allow_remote_images_download) {
                            $save_media['allow_remote_download'] = true;
                        }
                        if ($check == false) {
                            $this->app->media_manager->save($save_media);
                        }
                    }
                } elseif (is_array($image_to_save) and !empty($image_to_save)) {
                    $save_media = $image_to_save;
                    $save_media['content_id'] = $id;
                    if ($allow_remote_images_download) {
                        $save_media['allow_remote_download'] = true;
                    }
                    $this->app->media_manager->save($save_media);
                }
            }
        }

        $custom_field_table = $this->tables['custom_fields'];
        $custom_field_table = $this->app->database_manager->real_table_name($custom_field_table);

        $sid = $this->app->user_manager->session_id();
        $media_table = $this->tables['media'];
        $media_table = $this->app->database_manager->real_table_name($media_table);

        if ($sid != false and $sid != '' and $id != false) {

            DB::table($this->tables['custom_fields'])
                ->whereSessionId($sid)
                ->where(function ($query) {
                    $query->whereRelId(0)->orWhere('rel_id', null)->orWhere('rel_id', '0');
                })
                ->whereRelType('content')
                ->update(['rel_type' => 'content', 'rel_id' => $id]);

            DB::table($this->tables['media'])
                ->whereSessionId($sid)
                ->where(function ($query) {
                    $query->whereRelId(0)->orWhere('rel_id', null)->orWhere('rel_id', '0');
                })
                ->whereRelType('content')
                ->update(['rel_type' => 'content', 'rel_id' => $id]);

        }

        $this->app->cache_manager->delete('global');
        $this->app->cache_manager->delete('custom_fields');
        $this->app->cache_manager->delete('custom_fields_values');
        $this->app->cache_manager->delete('media');

        if (isset($data_to_save['parent']) and intval($data_to_save['parent']) != 0) {
            $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['parent']));
        }
        if (isset($data_to_save['id']) and intval($data_to_save['id']) != 0) {
            $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['id']));
        }

        $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('content' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache_manager->delete('content_fields');
        $this->app->cache_manager->delete('content');
        $this->app->cache_manager->delete('categories');
        $this->app->cache_manager->delete('categories_items');
        $this->app->cache_manager->delete('db');
        $this->app->cache_manager->delete('repositories');
        $this->app->cache_manager->delete('___global');

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

        // event_trigger('mw_save_content', $save);

        return $id;
    }

    public function reorder($params)
    {
        $ids = $params['ids'];
        if (empty($ids)) {
            $ids = $_POST[0];
        }
        if (empty($ids)) {
            return false;
        }
        $ids = array_unique($ids);
        $ids = array_map('intval', $ids);
        $ids_implode = implode(',', $ids);
        $table = $this->app->database_manager->real_table_name($this->tables['content']);
        $maxpos = 0;
        $get_max_pos = "SELECT max(position) AS maxpos FROM $table  WHERE id IN ($ids_implode) ";
        $get_max_pos = $this->app->database_manager->query($get_max_pos);
        if (is_array($get_max_pos) and isset($get_max_pos[0]['maxpos'])) {
            $maxpos = intval($get_max_pos[0]['maxpos']) + 1;
        }

        $i = 1;
        foreach ($ids as $id) {
            $id = intval($id);
            $this->app->cache_manager->delete('content/' . $id);

            $pox = $maxpos - $i;

            DB::table($this->tables['content'])->whereId($id)->update(['position' => $pox]);
            ++$i;
        }

        $this->app->cache_manager->delete('content');
        $this->app->cache_manager->delete('categories');

        return true;
    }

    /**
     * Sets the database table names to use by the class.
     *
     * @param array|bool $tables
     */
    private function set_table_names($tables = false)
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

    private function map_params_to_schema($params)
    {

        // map custom fields
        $prefix = 'custom_field';
        $data_str = $prefix . '_';
        $data_str_l = strlen($data_str);
        foreach ($params as $k => $v) {
            if (is_string($k)) {
                if (strlen($k) > $data_str_l) {
                    $rest = substr($k, 0, $data_str_l);
                    $left = substr($k, $data_str_l, strlen($k));
                    if ($rest == $data_str) {
                        if (!isset($params['custom_fields'])) {
                            $params['custom_fields'] = array();
                        }
                        $new_cf = array();
                        $new_cf['name'] = $left;
                        $new_cf['type'] = 'text';
                        $new_cf['value'] = $v;
                        if ($new_cf['name'] == 'price') {
                            $new_cf['type'] = 'price';
                        } elseif (is_array($v)) {
                            $new_cf['type'] = 'dropdown';
                        }
                        $params['custom_fields'][] = $new_cf;
                        unset($params[$k]);
                    }
                }
            }
        }

        $prefixes = array(
            'attributes' => array('attribute', 'attributes'),
            'data_fields' => array('data_fields', 'data_field'),
            //     'custom_fields' => array('custom_fields', 'custom_field'),
            'categories' => array('categories', 'category'),
        );

        if (!empty($params)) {
            foreach ($prefixes as $prefix_group => $keys) {
                foreach ($keys as $prefix) {
                    $data_str = $prefix . '_';
                    $data_str_l = strlen($data_str);
                    foreach ($params as $k => $v) {
                        if (is_string($k)) {
                            if (strlen($k) > $data_str_l) {
                                $rest = substr($k, 0, $data_str_l);
                                $left = substr($k, $data_str_l, strlen($k));
                                if ($rest == $data_str) {
                                    if (!isset($params[$prefix_group])) {
                                        $params[$prefix_group] = array();
                                    }
                                    $params[$prefix_group][$left] = $v;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $params;
    }

    public function get_edit_field($data)
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
//        if (!isset($data['rel_type'])) {
//            if (isset($data['rel_type'])) {
//                if ($data['rel_type'] == 'content' or $data['rel_type'] == 'page' or $data['rel_type'] == 'post' or $data['rel_type'] == 'product') {
//                    $data['rel_type'] = 'content';
//                } else {
//                    $data['rel_type'] = $data['rel_type'];
//                }
//            }
//        }
        if (!isset($data['rel_id'])) {
            if (isset($data['data-id'])) {
                $data['rel_id'] = $data['data-id'];
            } else {
            }
        }
        if ((isset($data['rel_type']) and isset($data['rel_id']))) {
            $data['cache_group'] = ('content_fields/global/' . $data['rel_type'] . '/' . $data['rel_id']);
        } else {
            $data['cache_group'] = ('content_fields');
        }
        $data['cache_group'] = 'content_fields';

        $data['table'] = $table;

        if (!isset($data['all'])) {
            $data['one'] = 1;
            $data['limit'] = 1;
        }

        if (!isset($data['is_draft']) and !isset($data['all']) and isset($data['rel_type']) and isset($data['field'])) {
            if (!isset($data['rel_id'])) {
                $get = $this->app->content_repository->getEditField($data['field'], $data['rel_type']);

            } else {
                $get = $this->app->content_repository->getEditField($data['field'], $data['rel_type'], $data['rel_id']);
            }
        } else {
            $get = $this->app->database_manager->get($data);
        }




        //getEditField



        if (!isset($data['full']) and isset($get['value'])) {
            return $get['value'];
        } else {
            return $get;
        }

        return false;
    }


    public function clearCache()
    {
        self::$precached_links = [];
    }
}
