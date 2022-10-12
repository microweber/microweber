<?php


namespace content\controllers;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\View\View;

class Edit
{
    public $app = null;
    public $views_dir = 'views';
    public $provider = null;
    public $category_provider = null;
    public $event = null;
    public$modules = array();
    public $empty_data = array(
        'id' => 0,
        'content_type' => 'page',
        'title' => false,
        'content' => false,
        'content_body' => false,
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
        $this->views_dir = dirname(__DIR__) . DS . 'views' . DS;
        $this->provider = $this->app->content_manager;
        $this->category_provider = $this->app->category_manager;
    }

    function index($params)
    {

        if (!user_can_access('module.content.edit')) {
            return;
        }

        if (isset($params['content_type']) and $params['content_type'] == 'category') {
            print load_module('categories/edit_category', $params);
            return;
        }

        $data = false;
        $just_saved = false;
        $is_new_content = false;
        $is_current = false;
        $is_live_edit = false;
        if (!isset($is_quick)) {
            $is_quick = false;
        }


        //	if (isset($params['is_shop'])) {
//            if (trim($params['is_shop']) == 'y') {
//				$params['is_shop'] = 1;
//			} else if (trim($params['is_shop']) == 'n') {
//				$params['is_shop'] = 0;
//			}
//        }

        if (isset($params['live_edit'])) {
            $is_live_edit = $params['live_edit'];
        } elseif (isset($params['from_live_edit'])) {
            $is_live_edit = $params['from_live_edit'];
        }

        if (isset($params['quick_edit'])) {
            $is_quick = $params['quick_edit'];
        }
        if ($is_live_edit == true) {
            $is_quick = false;
        }

        if (isset($params['just-saved'])) {
            $just_saved = $params['just-saved'];
        }
        if (isset($params['is-current'])) {
            $is_current = $params['is-current'];
        }
        if (isset($params['page-id'])) {
            $data_q = Content::where('id', intval($params["page-id"]))->first();
            if($data_q){
            $data = $data_q->toArray();
            }
        }

        if (isset($params['content_id'])) {
            $params['content-id'] = $params['content_id'];
        }

        if (isset($params['content-id'])) {
            $data_q = Content::where('id', intval($params["content-id"]))->first();
            if($data_q){
                $data = $data_q->toArray();
            }
        }

        $recommended_parent = false;

        if (isset($params['recommended_parent']) and $params['recommended_parent'] != false) {
            $recommended_parent = $params['recommended_parent'];
        } elseif (isset($params['parent']) and $params['parent'] != false) {
            $recommended_parent = $params['parent'];
        }

        $categories_active_ids = false;
        $title_placeholder = false;
        if (isset($params['category']) and $params['category'] != false) {
            $categories_active_ids = $params['category'];
        } elseif (isset($params['selected-category-id']) and $params['selected-category-id'] != false) {
            $categories_active_ids = $params['selected-category-id'];
        }

        /* FILLING UP EMPTY CONTENT WITH DATA */
        if ($data == false or empty($data)) {
            $is_new_content = true;
            $data = $this->empty_data;
            if (isset($params['content_type'])) {
                $data['content_type'] = $params['content_type'];
            }


            if (isset($params['subtype'])) {
                $data['subtype'] = $params['subtype'];
                if ($data['subtype'] == 'post') {
                    $data['content_type'] = 'post';
                }
            }
            if (isset($data['content_type']) and $data['content_type'] == 'post' and ($data['subtype']) == 'static') {
                $data['subtype'] = 'post';
            } else if (isset($data['content_type']) and $data['content_type'] == 'product' and ($data['subtype']) == 'static') {
                $data['content_type'] = 'product';
                $data['subtype'] = 'product';
            }


        }

        if (isset($params['add-to-menu'])) {
            $data['add_to_menu'] = (($params["add-to-menu"]));
        }


        /* END OF FILLING UP EMPTY CONTENT  */


        /* SETTING PARENT AND ACTIVE CATEGORY */
        $forced_parent = false;
        if (intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-category-id']) and $params['parent-category-id'] != 0 and !isset($params['parent-page-id'])) {
            $cat_page = get_page_for_category($params['parent-category-id']);
            if (is_array($cat_page) and isset($cat_page['id'])) {
                $forced_parent = $params['parent-page-id'] = $cat_page['id'];
            }
        }

        if (intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-page-id'])) {
            $data['parent'] = $params['parent-page-id'];
            if (isset($params['content_type']) and $params['content_type'] == 'product') {
                $parent_content = $this->app->content_manager->get_by_id($params['parent-page-id']);
                // if(!isset($parent_content['is_shop']) or $parent_content['is_shop'] != 1){

                // $data['parent'] = 0;
                // }
            }
            if (isset($params['parent-category-id']) and $params['parent-category-id'] != 0) {
                $categories_active_ids = $params['parent-category-id'];
            }
        } else if (intval($data['id']) != 0) {
            $categories = $this->app->category_manager->get_for_content($data['id']);
            if (is_array($categories)) {
                $c = array();
                foreach ($categories as $category) {
                    $c[] = $category['id'];
                }
                $categories_active_ids = implode(',', $c);
            }

        }
        /* END OF SETTING PARENT AND ACTIVE CATEGORY  */

        if ($recommended_parent != false and $data['parent'] == 0) {
            $data['parent'] = $recommended_parent;
        }

        /* SETTING PARENT AND CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */

        if ($recommended_parent != false and intval($data['id']) == 0) {
            if (isset($data['subtype']) and $data['subtype'] == 'post') {
                if (isset($data['is_shop']) and $data['is_shop'] == 0) {
                    $parent_content = $this->app->content_manager->get_by_id($recommended_parent);
                    if (isset($parent_content['is_shop']) and $parent_content['is_shop'] == 1) {
                        $parent_content_params = array();
                        $parent_content_params['subtype'] = 'dynamic';
                        $parent_content_params['content_type'] = 'page';
                        $parent_content_params['limit'] = 1;
                        $parent_content_params['one'] = 1;
                        $parent_content_params['fields'] = 'id';
                        $parent_content_params['order_by'] = 'posted_at desc, updated_at desc';
                        $parent_content_params['is_shop'] = 0;
                        $parent_content = $this->app->content_manager->get($parent_content_params);
                        if (isset($parent_content['id']) and $parent_content['id'] != 0) {
                            $data['parent'] = $recommended_parent = $parent_content['id'];
                            $categories_active_ids = false;
                        }
                    }

                }
            } elseif (isset($data['subtype']) and $data['subtype'] == 'product') {
                if (isset($data['is_shop']) and $data['is_shop'] == 0) {
                    $parent_content = $this->app->content_manager->get_by_id($recommended_parent);
                    if (isset($parent_content['is_shop']) and $parent_content['is_shop'] == 0) {
                        $parent_content_params = array();
                        $parent_content_params['subtype'] = 'dynamic';
                        $parent_content_params['content_type'] = 'page';
                        $parent_content_params['limit'] = 1;
                        $parent_content_params['one'] = 1;
						$parent_content_params['is_shop'] = 1;
                        $parent_content_params['fields'] = 'id';
                        $parent_content_params['order_by'] = 'posted_at desc, updated_at desc';
                        $parent_content = $this->app->content_manager->get($parent_content_params);
                        if (isset($parent_content['id']) and $parent_content['id'] != 0) {
                            $data['parent'] = $recommended_parent = $parent_content['id'];
                            $categories_active_ids = false;
                        }
                    }

                }
            }

        }
        if ($recommended_parent == false and intval($data['id']) == 0 and intval($data['parent']) == 0) {
            $parent_content_params = array();
            $parent_content_params['subtype'] = 'dynamic';
            $parent_content_params['content_type'] = 'page';
            $parent_content_params['limit'] = 1;
            $parent_content_params['one'] = 1;
            $parent_content_params['parent'] = 0;
            $parent_content_params['fields'] = 'id';
            $parent_content_params['order_by'] = 'posted_at desc, updated_at desc';

            if (isset($params['subtype']) and $params['subtype'] == 'post') {
                $parent_content_params['is_shop'] = 0;
                $parent_content_params['is_home'] = 0;
                $parent_content = $this->app->content_manager->get($parent_content_params);

                if (!isset($parent_content['id'])) {
                    unset($parent_content_params['parent']);
                    $parent_content = $this->app->content_manager->get($parent_content_params);

                }
                if (isset($parent_content['id'])) {
                    $data['parent'] = $parent_content['id'];
                } else {
                    $this->app->content_manager->helpers->create_default_content('blog');
                    $parent_content_params['no_cache'] = true;
                    $parent_content = $this->app->content_manager->get($parent_content_params);

                }
            } elseif (isset($params['content_type']) and $params['content_type'] == 'product') {
                $parent_content_params['is_shop'] = 1;
                $parent_content = $this->app->content_manager->get($parent_content_params);

                if (isset($parent_content['id'])) {
                    $data['parent'] = $parent_content['id'];
                } else {
                    $this->app->content_manager->helpers->create_default_content('shop');
                    $parent_content_params['no_cache'] = true;
                    $parent_content = $this->app->content_manager->get($parent_content_params);
                }
            }

            if (isset($parent_content) and isset($parent_content['id'])) {
                $data['parent'] = $parent_content['id'];
            }


//var_dump($data);
        } elseif ($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['content_type'] == 'product') {

            //if we are adding product in a page that is not a shop
            $parent_shop_check = $this->app->content_manager->get_by_id($data['parent']);
            if (!isset($parent_shop_check['is_shop']) or $parent_shop_check['is_shop'] != 1) {
                $parent_content_shop = $this->app->content_manager->get('content_type=page&order_by=updated_at desc&one=true&is_shop=0');
                if (isset($parent_content_shop['id'])) {
                    $data['parent'] = $parent_content_shop['id'];
                }
            }

        } elseif ($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['subtype'] == 'post') {
            $parent_shop_check = $this->app->content_manager->get_by_id($data['parent']);
            if (!isset($parent_shop_check['content_type']) or $parent_shop_check['content_type'] != 'page') {
                $parent_content_shop = $this->app->content_manager->get('order_by=updated_at desc&one=true&content_type=page&subtype=dynamic&is_shop=1');
                if (isset($parent_content_shop['id'])) {
                    $data['parent'] = $parent_content_shop['id'];
                }
            }
        }


        /* END OF SETTING PARENT AND CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */

        $module_id = $params['id'];


        $post_list_view = $this->views_dir . 'edit.php';
        $this->app->event_manager->trigger('module.content.edit.main', $data);


        $segments = mw()->permalink_manager->link($data['id'], 'content',true);
        if ($segments) {
            $data['slug'] = $segments['slug'];
            $data['slug_prefix_url'] = $segments['slug_prefix_url'];
        }

        $view = new View($post_list_view);
        $view->assign('params', $params);
        $view->assign('module_id', $module_id);
        $view->assign('just_saved', $just_saved);
        $view->assign('is_new_content', $is_new_content);
        $view->assign('is_current', $is_current);
        $view->assign('is_live_edit', $is_live_edit);
        $view->assign('recommended_parent', $recommended_parent);
        $view->assign('categories_active_ids', $categories_active_ids);
        $view->assign('title_placeholder', $title_placeholder);
        $view->assign('rand', rand());
        $view->assign('data', $data);
        $view->assign('is_quick', $is_quick);

        return $view->display();
    }
}
