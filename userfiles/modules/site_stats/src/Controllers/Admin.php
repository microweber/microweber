<?php


namespace Microweber\SiteStats\Controllers;

use Microweber\View;


class Admin
{
    public $app = null;
    public $views_dir = 'views';


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


    }


    function recent_comments($params = null)
    {


        $comments_data = array(
            'order_by' => 'created_at desc',
            'limit' => '30',
        );
        $comments = get_comments($comments_data);


        if ($comments) {

        }


    }


    function index($params)
    {

        $view_file = $this->views_dir . 'index.php';
        $view = new View($view_file);
        $view->assign('params', $params);

        



//        $view->assign('page_info', $page_info);
//        $view->assign('toolbar', $toolbar);
//        $view->assign('data', $data);
//        $view->assign('pages', $pages);
//        $view->assign('keyword', $keyword);
//        $view->assign('post_params', $posts_mod);
//        $view->assign('paging_param', $posts_mod['paging_param']);
        return $view->display();
    }
}
