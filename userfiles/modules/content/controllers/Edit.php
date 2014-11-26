<?php


namespace Microweber\content\controllers;

use Microweber\View;

class Edit
{
    public $app = null;
    public $views_dir = 'views';
    public $provider = null;
    public $category_provider = null;
    public $event = null;
    public $modules = array();
    public $empty_data = array(
        'id' => 0,
        'content_type' => 'page',
        'title' => false,
        'content' => false,
        'url' => '',
        'thumbnail' => '',
        'is_active' => 'y',
        'is_home' => 'n',
        'is_shop' => 'n',
        'require_login' => 'n',
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
                $this->app = \Microweber\Application::getInstance();
            }
        }
        $this->views_dir = dirname(__DIR__) . DS . 'views' . DS;
        $this->provider = $this->app->content;
        $this->category_provider = $this->app->category;
        $this->event = $this->app->event;
        $is_admin = $this->app->user_manager->admin_access();
    }

    function index($params)
    {

        if (is_admin() == false) {
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
            $data = $this->app->content_manager->get_by_id(intval($params["page-id"]));
        }
        if (isset($params['content-id'])) {
            $data = $this->app->content_manager->get_by_id(intval($params["content-id"]));
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
        }
        /* FILLING UP EMPTY CONTENT WITH DATA */
        if ($data == false or empty($data)) {
            $is_new_content = true;
            $data = $this->empty_data;
            if (isset($params['content_type'])) {
                $data['content_type'] = $params['content_type'];
            }
//            if($data['id'] == 0){
//                        if (isset($params['content_type']) and $params['content_type'] == 'page') {
//                            d($params);
//                            $data['layout_file'] = 'clean';
//                        }
//            }


            if (isset($params['subtype'])) {
                $data['subtype'] = $params['subtype'];
                if ($data['subtype'] == 'post' or $data['subtype'] == 'product') {
                    $data['content_type'] = 'post';
                }
            }
            if (isset($data['content_type']) and $data['content_type'] == 'post' and ($data['subtype']) == 'static') {
                $data['subtype'] = 'post';
            } else if (isset($data['content_type']) and $data['content_type'] == 'product' and ($data['subtype']) == 'static') {
                $data['content_type'] = 'post';
                $data['subtype'] = 'product';
            }


        }
        if (isset($data['subtype'])) {
            if ($data['subtype'] == 'post' or $data['subtype'] == 'product') {
                $data['content_type'] = 'post';
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
            if (isset($params['subtype']) and $params['subtype'] == 'product') {
                $parent_content = $this->app->content_manager->get_by_id($params['parent-page-id']);
                // if(!isset($parent_content['is_shop']) or $parent_content['is_shop'] != 'y'){

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
		
		 if ($recommended_parent != false and intval($data['id']) == 0){
			 if (isset($data['subtype']) and $data['subtype'] == 'post') {
				 if (isset($data['is_shop']) and $data['is_shop'] == 'n') {
				     $parent_content = $this->app->content_manager->get_by_id($recommended_parent);
					  if (isset($parent_content['is_shop']) and $parent_content['is_shop'] == 'y') {
						   $parent_content_params = array();
						$parent_content_params['subtype'] = 'dynamic';
						$parent_content_params['content_type'] = 'page';
						$parent_content_params['limit'] = 1;
						$parent_content_params['one'] = 1;
 						$parent_content_params['fields'] = 'id';
						$parent_content_params['order_by'] = 'posted_on desc, updated_on desc';
						 $parent_content_params['is_shop'] = 'n';
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
            $parent_content_params['order_by'] = 'posted_on desc, updated_on desc';

            if (isset($params['subtype']) and $params['subtype'] == 'post') {
                $parent_content_params['is_shop'] = 'n';
                $parent_content_params['is_home'] = 'n';
                $parent_content = $this->app->content_manager->get($parent_content_params);

                if (!isset($parent_content['id'])) {
                    unset($parent_content_params['parent']);
                    $parent_content = $this->app->content_manager->get($parent_content_params);

                }
                if (isset($parent_content['id'])) {
                    $data['parent'] = $parent_content['id'];
                } else {
                    $this->app->content_manager->create_default_content('blog');
                    $parent_content_params['no_cache'] = true;
                    $parent_content = $this->app->content_manager->get($parent_content_params);

                }
            } elseif (isset($params['subtype']) and $params['subtype'] == 'product') {
                $parent_content_params['is_shop'] = 'y';
                $parent_content = $this->app->content_manager->get($parent_content_params);

                if (isset($parent_content['id'])) {
                    $data['parent'] = $parent_content['id'];
                } else {
                    $this->app->content_manager->create_default_content('shop');
                    $parent_content_params['no_cache'] = true;
                    $parent_content = $this->app->content_manager->get($parent_content_params);
                }
            }
			
            if (isset($parent_content) and isset($parent_content['id'])) {
				
                $data['parent'] = $parent_content['id'];
            }


        } elseif ($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['subtype'] == 'product') {

            //if we are adding product in a page that is not a shop
            $parent_shop_check = $this->app->content_manager->get_by_id($data['parent']);
            if (!isset($parent_shop_check['is_shop']) or $parent_shop_check['is_shop'] != 'y') {
                $parent_content_shop = $this->app->content_manager->get('content_type=page&order_by=updated_on desc&one=true&is_shop=y');
                if (isset($parent_content_shop['id'])) {
                    $data['parent'] = $parent_content_shop['id'];
                }
            }

        } elseif ($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['subtype'] == 'post') {
             $parent_shop_check = $this->app->content_manager->get_by_id($data['parent']);
            if (!isset($parent_shop_check['content_type']) or $parent_shop_check['content_type'] != 'page') {
                $parent_content_shop = $this->app->content_manager->get('order_by=updated_on desc&one=true&content_type=page&subtype=dynamic&is_shop=n');
                if (isset($parent_content_shop['id'])) {
                    $data['parent'] = $parent_content_shop['id'];
                }
            }
        }



        /* END OF SETTING PARENT AND CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */

        $module_id = $params['id'];

        $post_list_view = $this->views_dir . 'edit.php';
        $this->event->emit('module.content.edit', $data);
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