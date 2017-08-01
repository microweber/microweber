<?php


namespace content\controllers;

use Microweber\View;
use DB;

class Front
{
    public $app = null;

    public $empty_data = array(
        'id' => 0,
        'content_type' => 'page',
        'title' => false,
        'content' => false,
        'url' => '',
        'thumbnail' => '',
        'is_active' => 1,
        'is_home' => 0,
        'is_shop' => 0,
        'require_login' => 0,
        'subtype' => 'static',
        'description' => '',
        'active_site_template' => '',
        'subtype_value' => '',
        'parent' => 0,
        'layout_name' => '',
        'layout_file' => 'inherit',
        'original_link' => '');

    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }


    }

    function index($params, $config)
    {


        $current_page = $current_page = 1;
        $post_params = $params;
        $tag_param = 'tags';
        if (isset($post_params['id'])) {
            $paging_param = 'current_page' . crc32($post_params['id']);
            unset($post_params['id']);
        }
        if (isset($params['data-tags-param'])) {
            $tag_param = $params['data-tags-param'];
        }

        $cat_from_url = get_category_id_from_url();
        $posts_parent_related = false;
        if (isset($params['current_page'])) {
            // $params['current_page'] = $params['current_page'];
        } elseif (isset($params['curent-page'])) {
            $params['current_page'] = $params['curent-page'];
        } elseif (isset($params['current-page'])) {
            $params['current_page'] = $params['current-page'];
        } elseif (isset($params['curent-page'])) {
            $params['current_page'] = $params['curent-page'];
        }

        if (isset($params['paging_param'])) {
            if (isset($params[$params['paging_param']])) {
                $current_page = $current_page = $params['current_page'] = $params[$params['paging_param']];
                $paging_param = $params['paging_param'];
            }

        }


        if (isset($params['current_page'])) {
            $current_page = $params['current_page'] = $params['current_page'];
        } else {
            $current_page_from_url = url_param($paging_param);
            if ($current_page_from_url != false) {
                $current_page = $current_page_from_url;
            }
        }


        if (isset($post_params['data-page-number'])) {
            $post_params['current_page'] = $post_params['data-page-number'];
            unset($post_params['data-page-number']);
        }


        if (isset($post_params['data-category-id'])) {
            $post_params['category'] = $post_params['data-category-id'];
            unset($post_params['data-category-id']);
        }


        if (isset($params['data-paging-param'])) {
            $paging_param = $params['data-paging-param'];
        }
        $tags_val = false;

        if (isset($params[$tag_param])) {
            $tags_val = $params[$tag_param];
        }
        if (isset($params['data-tags'])) {
            $tags_val = $params['data-tags'];
        }

        if(!$tags_val){
            $current_tags_from_url = url_param($tag_param);
            if ($current_tags_from_url != false) {
                $tags_val = $current_tags_from_url;
            }

        }
        if(!$tags_val){
            $tags_val = get_option('data-tags', $params['id']);
        }



        if($tags_val and is_string($tags_val)){
            $tags_val = explode(',',$tags_val);
            $tags_val = array_trim($tags_val);
            $tags_val = array_filter($tags_val);
            $tags_val = array_unique($tags_val);
            $tags_val = implode(',',$tags_val);
        }
        if($tags_val){
            $post_params['tags'] = $tags_val;
        }

        $show_fields = false;
        if (isset($post_params['data-show'])) {
            $show_fields = $post_params['data-show'];
        }
        if (isset($post_params['show'])) {
            $show_fields = $post_params['show'];
        }






        $set_content_type_from_opt = get_option('data-content-type', $params['id']);

        $show_fields1 = get_option('data-show', $params['id']);
        if ($show_fields1 != false and is_string($show_fields1) and trim($show_fields1) != '') {
            $show_fields = $show_fields1;
        }
        if ($show_fields != false and is_string($show_fields)) {
            $show_fields = explode(',', $show_fields);
        }

        if (isset($post_params['limit'])) {
            $post_params['limit'] = $post_params['limit'];
        }
        if (isset($post_params['data-limit'])) {
            $post_params['limit'] = $post_params['data-limit'];
        }


        if (!isset($post_params['data-limit'])) {
            $posts_limit = get_option('data-limit', $params['id']);
            if ($posts_limit != false) {
                $post_params['limit'] = $posts_limit;
            }
        }

        $posts_parent_category = $posts_parent_category_cfg = get_option('data-category-id', $params['id']);
        if ($posts_parent_category == '') {
            $posts_parent_category = false;
        }


        $set_category_for_posts = false;

        $posts_limit = get_option('data-limit', $params['id']);
        if ($posts_limit != false) {
            $post_params['data-limit'] = $post_params['limit'] = $posts_limit;
        }
        $cfg_page_id = $cfg_page_id_force = get_option('data-page-id', $params['id']);
        if ($cfg_page_id == false and isset($post_params['data-page-id'])) {
            $cfg_page_id = intval($post_params['data-page-id']);
        } else if ($cfg_page_id == false and isset($post_params['content_id'])) {
            $cfg_page_id = intval($post_params['content_id']);
        } else if ($cfg_page_id == false and isset($post_params['content-id'])) {
            $cfg_page_id = intval($post_params['content-id']);
        } elseif ($cfg_page_id == false and isset($post_params['current_page'])) {
            $cfg_page_id = 'current_page';
        }


        if ($posts_parent_category == false and isset($post_params['category_id'])) {
            $posts_parent_category = $post_params['category_id'];
        }

        if ($posts_parent_category == false and isset($post_params['related'])) {
            if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {
                $posts_parent_category = $posts_parent_related = CATEGORY_ID;
            }
        }
        if ($posts_parent_category_cfg == 'related') {
            $posts_parent_related = true;
            $posts_parent_category = $posts_parent_related = CATEGORY_ID;


        }

        if ($posts_parent_category == false and ($cfg_page_id == 'current_page')) {
            if (defined('PAGE_ID') and PAGE_ID > 0) {
                $cfg_page_id = PAGE_ID;
            }
        }


        if ($posts_parent_category_cfg == false) {

            if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {
                $posts_parent_category = CATEGORY_ID;
            }

        }

        if ($cfg_page_id == false and isset($post_params['related']) and $post_params['related'] != false and (!isset($post_params['parent']) or $post_params['parent'] == false)) {
            if (defined('PAGE_ID') and PAGE_ID > 0) {
                $cfg_page_id = PAGE_ID;
                $post_params['parent'] = $cfg_page_id;
            }
        }

        if (isset($post_params['most_ordered'])) {
            $str0 = 'table=cart&limit=30&rel_type=content&fields=rel_id&order_by=id desc';
            $orders = db_get($str0);
            if (!empty($orders)) {
                $ids = array();
                foreach ($orders as $order) {
                    $ids[] = $order['rel_id'];
                }
                $post_params['ids'] = $ids;
            }
        }
        if (isset($post_params['recently_viewed'])) {
            if (defined("MAIN_PAGE_ID") and defined("CONTENT_ID")) {
                $str0 = 'table=stats_pageviews&limit=30&main_page_id=' . MAIN_PAGE_ID . '&page_id=[neq]' . CONTENT_ID . '&fields=page_id&order_by=id desc&no_cache=true';
                $orders = db_get($str0);
                if (!empty($orders)) {
                    $ids = array();
                    foreach ($orders as $order) {
                        $ids[] = $order['page_id'];
                    }
                    $post_params['ids'] = $ids;
                }
            }
        }


        if ($posts_parent_related == false) {
            if (intval($cfg_page_id_force) or !isset($params['global'])) {
                if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
                    $sub_categories = array();
                    $page_categories = false;
                    if (intval($cfg_page_id) != 0 and $cat_from_url == false) {

                        $str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel_id=' . $cfg_page_id;
                        $page_categories = db_get($str0);

                        $str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel_id=' . $cfg_page_id;
                        $page_categories = db_get('table=categories&limit=1&data_type=category&' . 'parent_id=0&rel_id=' . $cfg_page_id);

                        if (is_array($page_categories)) {
                            foreach ($page_categories as $item_cat) {
                                $sub_categories[] = $item_cat['id'];
                                $more = get_category_children($item_cat['id']);
                                if ($more != false and is_array($more)) {
                                    foreach ($more as $item_more_subcat) {
                                        $sub_categories[] = $item_more_subcat;
                                    }
                                }
                            }
                        }
                    }

                    if ($posts_parent_category != false and intval($posts_parent_category) > 0 and $cat_from_url == false) {
                        if ($page_categories != false and is_array($page_categories) and !empty($page_categories)) {
                            // $sub_categories = array();
                            foreach ($page_categories as $item_cat) {
                                if (intval($item_cat['id']) == intval($posts_parent_category)) {
                                    $sub_categories[] = $item_cat['id'];
                                }
                            }
                        } elseif ($posts_parent_category_cfg != false) {
                            $post_params['category'] = $posts_parent_category_cfg;
                        }

                        if (is_array($sub_categories) and !empty($sub_categories) and isset($post_params['related']) and $post_params['related'] != false) {
                            $post_params['category'] = $sub_categories;
                        } elseif ($cfg_page_id != false) {
                            $post_params['parent'] = $cfg_page_id;
                        }

                    } else {
                        $post_params['parent'] = $cfg_page_id;

                        if (($cfg_page_id == PAGE_ID or $cfg_page_id == MAIN_PAGE_ID) and (!isset($post_params['category']) or $post_params['category'] == false) and $cat_from_url != false) {
                            $post_params['category'] = $cat_from_url;
                        }


                    }

                } elseif ($cat_from_url != false) {
                    $post_params['category'] = $cat_from_url;

                } elseif ($posts_parent_category != false and intval($posts_parent_category) > 0 and ($cfg_page_id) != false) {
                    $post_params['category'] = $posts_parent_category;


                }
            }

            if ($posts_parent_category_cfg != false and intval($posts_parent_category_cfg) > 0 and $cfg_page_id_force != false and intval($cfg_page_id_force) > 0) {
                $post_params['category'] = $posts_parent_category_cfg;
            }
        } else {
            $post_params['category'] = $posts_parent_related;
        }


        $tn_size = array('150');
        $tn = $tn_size;

        if (isset($post_params['data-thumbnail-size'])) {
            $temp = explode('x', strtolower($post_params['data-thumbnail-size']));
            if (!empty($temp)) {
                $tn_size = $temp;
            }
        } else {
            $cfg_page_item = get_option('data-thumbnail-size', $params['id']);
            if ($cfg_page_item != false) {
                $temp = explode('x', strtolower($cfg_page_item));

                if (!empty($temp)) {
                    $tn_size = $temp;
                }
            }
        }


        if (!isset($tn[0]) or ($tn[0]) == 150) {
            $tn[0] = 350;
        }
        if (!isset($tn[1])) {
            $tn[1] = $tn[0];
        }

        $character_limit = 120;
        $cfg_character_limit = get_option('data-character-limit', $params['id']);
        if ($cfg_character_limit != false and trim($cfg_character_limit) != '') {
            $character_limit = intval($cfg_character_limit);
        } else if (isset($params['description-length'])) {
            $character_limit = intval($params['description-length']);
        }


        $title_character_limit = 200;
        $cfg_character_limit1 = get_option('data-title-limit', $params['id']);
        if ($cfg_character_limit1 != false and trim($cfg_character_limit1) != '') {
            $title_character_limit = intval($cfg_character_limit1);
        } else if (isset($params['title-length'])) {
            $title_character_limit = intval($params['title-length']);
        }


        if ($show_fields == false) {
//$show_fields = array('thumbnail', 'title', 'description', 'read_more');
        }

        if (is_array($show_fields)) {
            $show_fields = array_trim($show_fields);
        }

        if (isset($current_page) and intval($current_page) > 0) {
            $post_params['current_page'] = intval($current_page);
        }

        if (!isset($post_params['global'])) {
            if (!isset($post_params['content_type'])) {
                $post_params['content_type'] = 'post';
            }

        }


        if (isset($params['is_shop'])) {
            $post_params['content_type'] = 'product';
            unset($post_params['is_shop']);
        }

        if (!isset($post_params['content_type']) and !isset($post_params['global'])) {
            $post_params['content_type'] = 'post';
        }

        if (!isset($params['order_by']) and isset($params['order-by'])) {
            $params['orderby'] = $post_params['orderby'] = $params['order-by'];
        }


        if (isset($params['subtype_value'])) {
            $post_params['subtype_value'] = $params['subtype_value'];
        }


        $schema_org_item_type = false;
        $schema_org_item_type_tag = false;


        if (isset($post_params['content_type']) and $post_params['content_type'] == 'page') {
            $schema_org_item_type = 'WebPage';

        } else if (isset($post_params['content_type']) and $post_params['content_type'] == 'post') {
            if (isset($post_params['subtype']) and $post_params['subtype'] != $post_params['content_type']) {
                $schema_org_item_type = $post_params['subtype'];

            } else {
                $schema_org_item_type = 'Article';
            }
        }


        if ($schema_org_item_type != false) {
            $schema_org_item_type = ucfirst($schema_org_item_type);
            $schema_org_item_type_tag = ' itemtype="http://schema.org/' . $schema_org_item_type . '" ';
            $schema_org_item_type_tag = 'http://schema.org/' . $schema_org_item_type;
        }

        $ord_by = get_option('data-order-by', $params['id']);
        if ($ord_by != false and trim($ord_by) != '') {
            $post_params['orderby'] = $ord_by;
        }

        $date_format = get_option('date_format', 'website');
        if ($date_format == false) {
            $date_format = "Y-m-d H:i:s";
        }

        if (isset($params['title'])) {

            unset($post_params['title']);
        }

        $post_params['is_active'] = 1;
        $post_params['is_deleted'] = 0;

        if (((!isset($post_params['parent']) and !isset($post_params['category'])
                or isset($post_params['category']) and empty($post_params['category']))
            and $cat_from_url != false and trim($cat_from_url) != '')
        ) {
            $post_params['category'] = ($cat_from_url);
        }

        if (isset($params['content_type']) and $params['content_type'] == 'all') {
            unset($post_params['content_type']);
            unset($post_params['subtype']);
        }


        if (isset($params['search-parent'])) {
            $sub_content = get_content_children($params['search-parent']);
            if (!empty($sub_content)) {
                $post_params['ids'] = $sub_content;
                unset($post_params['parent']);
            }
        }
        if (isset($params['data-id'])) {
            unset($post_params['data-id']);
        }

        if ($posts_parent_related == false) {
            if (isset($post_params['category']) and is_string($post_params['category'])) {
                $sub_categories = array();
                $sub_categories[] = $post_params['category'];
                $more = get_category_children($post_params['category']);
                if ($more != false and is_array($more)) {
                    foreach ($more as $item_more_subcat) {
                        $sub_categories[] = $item_more_subcat;
                    }
                }
                //$post_params['category']
                $post_params['category'] = $sub_categories;
                //$post_params['category'] = $post_params['category'];
            } else if (isset($post_params['category']) and is_array($post_params['category']) and empty($post_params['category']) and isset($post_params['related']) and $post_params['related'] != false) {
                if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {

                    $post_params['category'] = CATEGORY_ID;


                }

            }
        }
        if (defined('POST_ID') and isset($posts_parent_category) and $posts_parent_category != false or isset($post_params['related'])) {
            $post_params['exclude_ids'] = POST_ID;
        }


        if (!isset($params['order_by'])) {
//            if(isset($post_params['content_type']) and $post_params['content_type'] == 'page'){
//                $post_params['order_by'] = 'position asc';
//            } else {
//
//            }
            $post_params['order_by'] = 'position desc';
        }

        if (isset($params['search_in_fields']) and $params['search_in_fields'] != false) {
        $post_params['search_in_fields'] = $params['search_in_fields'];
    }


        if (isset($params['strict_categories']) and $params['strict_categories'] != false) {
            $post_params['strict_categories'] = $params['strict_categories'];
        }

        $is_search = url_param('search');
        if ($is_search and isset($_GET['search_params'])) {
            $search_params = $_GET['search_params'];
            if ($search_params) {

                //   DB::enableQueryLog();


                $post_params['no_cache'] = $search_params;

                $post_params['search_params'] = $search_params;

            }

        }


        $content = get_content($post_params);
        if ($is_search) {
            //dd(DB::getQueryLog(), $content);
        }

        if ($posts_parent_related != false and empty($content) and isset($post_params['category'])) {
            unset($post_params['category']);
            $content = get_content($post_params);
        }


        $data = array();

        if (!empty($content)) {

            foreach ($content as $item) {

                $iu = get_picture($item['id'], $for = 'post', $full = false);

                if ($iu != false) {
                    $item['image'] = $iu;
                } else {
                    $item['image'] = false;
                }


                if ($item['image'] != false) {
                    $item['tn_image'] = thumbnail($item['image'], $tn[0], $tn[1]);

                } else {
                    $item['tn_image'] = false;
                }


                $item['content'] = htmlspecialchars_decode($item['content']);


                if (isset($item['created_at']) and trim($item['created_at']) != '') {
                    $item['created_at'] = date($date_format, strtotime($item['created_at']));
                }

                if (isset($item['updated_at']) and trim($item['updated_at']) != '') {
                    $item['updated_at'] = date($date_format, strtotime($item['updated_at']));
                }

                $item['link'] = content_link($item['id']);
                $item['full_description'] = '';
                if (!isset($item['description']) or $item['description'] == '') {
                    if (isset($item['content']) and $item['content'] != '') {


                        $item['description'] = character_limiter(strip_tags($item['content']), $character_limit);
                        $item['full_description'] = strip_tags($item['content']);
                    } elseif (isset($item['content_body']) and $item['content_body'] != '') {
                        $item['full_description'] = strip_tags($item['content']);
                        $item['description'] = character_limiter(strip_tags($item['content_body']), $character_limit);
                    }


                } else {
                    $item['full_description'] = trim($item['description']);
                    $item['description'] = character_limiter(strip_tags($item['description']), $character_limit);

                }


                if (isset($item['title']) and $item['title'] != '') {
                    $item['full_title'] = $item['title'];
                    $item['title'] = character_limiter($item['title'], $title_character_limit);
                }

                if (isset($post_params['content_type']) and $post_params['content_type'] == 'product') {
                    $item['prices'] = get_custom_fields("field_type=price&for=content&for_id=" . $item['id']);

                } else {
                    $item['prices'] = false;
                }
                if (isset($item['prices']) and is_array($item['prices']) and !empty($item['prices'])) {
                    $vals2 = array_values($item['prices']);
                    $val1 = array_shift($vals2);
                    $item['price'] = $val1;
                } else {
                    $item['price'] = false;

                }


                if (isset($show_fields) and is_array($show_fields) and !empty($show_fields)) {
                    if (!in_array('title', $show_fields)) {
                        $item['title'] = false;
                    }
                    if (!in_array('description', $show_fields)) {
                        $item['description'] = false;
                    }

                    if (!in_array('created_at', $show_fields)) {
                        $item['created_at'] = false;
                    }
                    if (!in_array('read_more', $show_fields)) {
                        $item['read_more'] = false;
                    }

                    if (!in_array('thumbnail', $show_fields)) {
                        $item['thumbnail'] = false;
                    }
                }


                $data[] = $item;
            }
        } else {
            if (isset($params['is_shop'])) {
                print lnotif('Your products module is empty');
            } elseif (isset($params['global'])) {
                print lnotif('Your content module is empty');
            } else {
                print lnotif('Your posts module is empty');
            }
        }


        $post_params_paging = $post_params;
        $post_params_paging['page_count'] = true;
        $cfg_data_hide_paging = get_option('data-hide-paging', $params['id']);

        if ($cfg_data_hide_paging === false) {
            if (isset($post_params['hide_paging']) and trim($post_params['hide_paging']) != 'false') {
                $post_params['hide-paging'] = $post_params['hide_paging'];
                unset($post_params['hide_paging']);
            }
            if (isset($post_params['hide-paging']) and trim($post_params['hide-paging']) != 'false') {
                $cfg_data_hide_paging = 'y';
                unset($post_params['hide-paging']);
            }
        }

        if ($cfg_data_hide_paging != 'y') {

            $pages_of_posts = get_content($post_params_paging);
            $pages_count = intval($pages_of_posts);
        } else {
            $pages_count = 0;
        }


        $paging_links = false;
        if (intval($pages_count) > 1) {
            //$paging_links = mw()->content_manager->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword');
        }

        $read_more_text = get_option('data-read-more-text', $params['id']);
        $add_cart_text = get_option('data-add-to-cart-text', $params['id']);
        if ($add_cart_text == false or $add_cart_text == "Add to cart") {
            $add_cart_text = _e("Add to cart", true);
        }

        if (!isset($params['return'])) {

            $module_template = get_option('data-template', $params['id']);
            if ($module_template == false and isset($params['template'])) {
                $module_template = $params['template'];
            }


            if ($module_template != false) {
                if (strtolower($module_template) == 'none') {
                    if (isset($params['template'])) {
                        $module_template = $params['template'];
                    }
                }
                $template_file = module_templates($config['module'], $module_template);

            } else {
                $template_file = module_templates($config['module'], 'default');

            }
            if ($template_file == false) {
                $template_file = module_templates($config['module'], 'default');
            }

            if (isset($template_file) and is_file($template_file) != false) {
                include($template_file);

                ?>
                <?php if (isset($params['ajax_paging']) or isset($params['ajax-paging'])): ?>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            mw.$('#<?php print $params['id'] ?>').find('a[data-page-number]').unbind('click');
                            mw.$('#<?php print $params['id'] ?>').find('a[data-page-number]').click(function (e) {
                                var pn = $(this).attr('data-page-number');
                                mw.$('#<?php print $params['id'] ?>').attr('paging_param', 'current_page');
                                mw.$('#<?php print $params['id'] ?>').attr('current_page', pn)
                                mw.reload_module('#<?php print $params['id'] ?>');
                                return false;
                            });
                        });
                    </script>
                <?php endif; ?>
                <?php if (isset($params['is_shop'])): ?>
                    <script type="text/javascript">
                        mw.require("shop.js");
                    </script>
                <?php endif; ?>
                <?php
            } else {
                print lnotif('No default template for ' . $config['module'] . ' is found');
            }
        }
    }
}
