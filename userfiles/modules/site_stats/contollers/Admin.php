<?php


namespace site_stats\controllers;



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



    function index($params)
    {
        

		 



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
