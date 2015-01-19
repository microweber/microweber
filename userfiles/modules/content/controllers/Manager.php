<?php


namespace content\controllers;

use Microweber\View;

class Manager
{
    public $app = null;
    public $views_dir = 'views';
    public $provider = null;
    public $category_provider = null;
    public $event_manager = null;

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
        $this->event_manager = $this->app->event_manager;
        $is_admin = $this->app->user_manager->admin_access();
    }

    function index($params)
    {
        

		 
		if (isset($params['manage_categories'])) {
            print load_module('categories/manage', $params);

            return;


        }
		 if (isset($params['is_shop']) and $params['is_shop'] == 'y') {
			 $params['is_shop'] = 1;
		 } else if (isset($params['is_shop']) and $params['is_shop'] == 'n') {
			 $params['is_shop'] = 0;
		 }
		
		
 

        $no_page_edit = false;
        $posts_mod = array();
        // $posts_mod['type'] = 'content/admin_posts_list';
        if (isset($params['data-page-id'])) {
            $posts_mod['page-id'] = $params['data-page-id'];
        }

        if (isset($params['no_page_edit'])) {
            $no_page_edit = $params['no_page_edit'];
        }
        if (isset($params['keyword'])) {
            $posts_mod['search_by_keyword'] = $params['keyword'];
        }
        if (isset($params['content_type']) and $params['content_type'] != false) {
            $posts_mod['content_type'] = $params['content_type'];
        }
        if (isset($params['subtype']) and $params['subtype'] != false) {
            $posts_mod['subtype'] = $params['subtype'];
        }
        if (isset($params['is_shop']) and $params['is_shop'] == 1) {
            $posts_mod['content_type'] = 'product';
        } else if (isset($params['is_shop']) and $params['is_shop'] == 0) {
            $posts_mod['subtype'] = 'post';
        }
		
		 if (isset($params['content_type']) and $params['content_type'] == 'product') {
            $posts_mod['content_type'] = 'product';
           // $posts_mod['content_type'] = 'post';
        }

		if (isset($params['content_type']) and $params['content_type'] == 'post') {
			 if (!isset($params['subtype']) or $params['subtype'] == false) {
					$posts_mod['subtype'] = 'post';
				}
		}
		

		
        if (isset($params['content_type_filter']) and $params['content_type_filter'] != '') {
            $posts_mod['content_type'] = $params['content_type_filter'];
        }
        if (isset($params['subtype_filter']) and $params['subtype_filter'] != '') {
            $posts_mod['subtype'] = $params['subtype_filter'];
        }


        if (!isset($params['category-id']) and isset($params['page-id']) and $params['page-id'] != 'global') {
            $check_if_exist = $this->provider->get_by_id($params['page-id']);
            if (is_array($check_if_exist)) {
                if (isset($check_if_exist['is_shop']) and trim($check_if_exist['is_shop']) == 1) {
                    $posts_mod['subtype'] = 'product';
                }
            }
        }
        $page_info = false;
        if (isset($params['page-id'])) {
            if ($params['page-id'] == 'global') {
                if (isset($params['is_shop']) and $params['is_shop'] == 1) {
                    $page_info = $this->provider->get('limit=1&one=1&content_type=page&is_shop=0');
                }
            } else {
                $page_info = $this->provider->get_by_id($params['page-id']);
				if (isset($page_info['is_shop']) and trim($page_info['is_shop']) == 1) {
                  //  $posts_mod['subtype'] = 'product';
                }
            }
        }
		


        if (isset($params['category-id']) and $params['category-id'] != 'global') {
            $check_if_exist = $this->category_provider->get_page($params['category-id']);

            if (is_array($check_if_exist)) {
                $page_info = $check_if_exist;
                if (isset($check_if_exist['is_shop']) and trim($check_if_exist['is_shop']) == 1) {
                   $posts_mod['content_type'] = 'product';
                } else {
                    // $posts_mod['subtype'] = $check_if_exist['subtype'];
                }
            }
        }
		
		


        $posts_mod['paging_param'] = 'pg';
        $posts_mod['orderby'] = 'position desc';
        if (isset($posts_mod['page-id'])) {
            $posts_mod['parent'] = $posts_mod['page-id'];
        }
		
		 if (isset($params['pg'])) {
            $posts_mod['pg'] = $params['pg'];
        }
		 
        if (isset($params['data-category-id'])) {
            $posts_mod['category'] = $params['data-category-id'];
        } else if (isset($params['parent-category-id'])) {
            $posts_mod['category'] = $params['parent-category-id'];
        } elseif (isset($params['category-id'])) {
            $posts_mod['category'] = $params['category-id'];
        }

        if (isset($params[$posts_mod['paging_param']])) {
            $posts_mod['page'] = $params[$posts_mod['paging_param']];
        }

        $keyword = false;
        if (isset($posts_mod['search_by_keyword'])) {
            $keyword = strip_tags($posts_mod['search_by_keyword']);
        }

        $data = $this->provider->get($posts_mod);
        if (empty($data) and isset($posts_mod['page'])) {
            if(isset($posts_mod['paging_param'])) {
                $posts_mod[$posts_mod['paging_param']]= 1;
            }
            unset($posts_mod['page']);
            $data = $this->provider->get($posts_mod);
        }

        $post_params_paging = $posts_mod;
        $post_params_paging['page_count'] = true;
        $pages = $this->provider->get($post_params_paging);
        $this->event_manager->trigger('module.content.manager', $posts_mod);

        $post_toolbar_view = $this->views_dir . 'toolbar.php';

        $toolbar = new View($post_toolbar_view);
        $toolbar->assign('page_info', $page_info);
        $toolbar->assign('keyword', $keyword);
        $toolbar->assign('params', $params);

 
        $post_list_view = $this->views_dir . 'manager.php';
        if ($no_page_edit == false) {
            if ($data == false) {
                if (isset($posts_mod['category-id']) and isset($page_info['content_type']) and $page_info['content_type'] == 'page' and ($page_info['subtype'] != 'static')) {

                    if (isset($posts_mod['category-id']) and $posts_mod['category-id'] != 0) {
                         
                    } else {


						  $manager = new Edit();
                          return $manager->index($params);
					}


                  
                } elseif (isset($page_info['content_type']) and $page_info['content_type'] == 'page' and isset($page_info['subtype'])
                    and isset($page_info['id'])
                ) {


                    if($page_info['subtype'] != 'dynamic'){
                    $manager = new Edit();
                    return $manager->index($params);
                    }


                }
            }
        }


        $view = new View($post_list_view);
        $view->assign('params', $params);
        $view->assign('page_info', $page_info);
        $view->assign('toolbar', $toolbar);
        $view->assign('data', $data);
        $view->assign('pages', $pages);
        $view->assign('keyword', $keyword);
        $view->assign('post_params', $posts_mod);
        $view->assign('paging_param', $posts_mod['paging_param']);
        return $view->display();
    }
}
