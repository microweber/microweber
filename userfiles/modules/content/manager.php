<?php

namespace Microweber\Content;
use Microweber\View;


if (!isset($params)) {
    $params = array();
}
$manager = new Manager($params);


if (isset($params['view'])) {
    if (method_exists($manager, $params['view'])) {

        return $manager->$params['view']($params);
    }
}
return $manager->index($params);

class Manager
{
    public $params = array();
    public $views_dir = 'views';
    public $provider = null;
    public $event = null;

    function __construct($params)
    {
        $this->params = $params;
        $this->views_dir = __DIR__ . DS . 'views' . DS;
        $this->provider = mw()->content();
        $this->event = mw()->event();

    }

    function index($params)
    {
        if (isset($params['manage_categories'])) {
            return include __DIR__ . DS . '../categories/manage.php';
        }
        $posts_mod = array();
        // $posts_mod['type'] = 'content/admin_posts_list';
        if (isset($params['data-page-id'])) {
            $posts_mod['page-id'] = $params['data-page-id'];
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
        if (isset($params['is_shop']) and $params['is_shop'] == 'y') {
            $posts_mod['subtype'] = 'product';
        }

        if (!isset($params['category-id']) and isset($params['page-id']) and $params['page-id'] != 'global') {
            $check_if_excist = $this->provider->get_by_id($params['page-id']);
            if (is_array($check_if_excist)) {
                if (isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y') {
                    $posts_mod['subtype'] = 'product';
                }
            }
        }
        $page_info = false;
        if (isset($params['page-id'])) {
            if ($params['page-id'] == 'global') {
                if (isset($params['is_shop']) and $params['is_shop'] == 'y') {
                    $page_info = get_content('limit=1&one=1&content_type=page&is_shop=y');
                } else {

                }
            } else {
                $page_info = get_content_by_id($params['page-id']);
            }
        }

        if (isset($params['category-id']) and $params['category-id'] != 'global') {
            $check_if_excist = get_page_for_category($params['category-id']);

            if (is_array($check_if_excist)) {
                $page_info = $check_if_excist;
                if (isset($check_if_excist['is_shop']) and trim($check_if_excist['is_shop']) == 'y') {
                    $posts_mod['subtype'] = 'product';
                }
            }
        }
        $posts_mod['paging_param'] = 'pg';
        $posts_mod['orderby'] = 'position desc';
        if (isset($posts_mod['page-id'])) {
            $posts_mod['parent'] = $posts_mod['page-id'];
        }
        if (isset($params['data-category-id'])) {
            $posts_mod['category-id'] = $params['data-category-id'];
        }
        if (isset($params['data-category-id'])) {
            $posts_mod['category-id'] = $params['data-category-id'];
        }
        if (isset($params[$posts_mod['paging_param']])) {
            $posts_mod['page'] = $params[$posts_mod['paging_param']];
        }
        if (isset($params['category-id'])) {
            $posts_mod['category'] = $params['category-id'];
        }
        $keyword = false;
        if (isset($posts_mod['search_by_keyword'])) {
            $keyword = strip_tags($posts_mod['search_by_keyword']);
        }

        $data = $this->provider->get($posts_mod);
        if (empty($data) and isset($posts_mod['page'])) {

            unset($posts_mod['page']);
            $data = $this->provider->get($posts_mod);

        }

        $post_params_paging = $posts_mod;
        $post_params_paging['page_count'] = true;
        $pages = $this->provider->get($post_params_paging);


        $post_toolbar_view = $this->views_dir . 'toolbar.php';

        $toolbar = new View($post_toolbar_view);
        $toolbar->assign('page_info', $page_info);
        $toolbar->assign('keyword', $keyword);
        $toolbar->assign('params', $params);

        $post_list_view = $this->views_dir . 'manager.php';
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

    function admin()
    {
        print rand();
    }

}






