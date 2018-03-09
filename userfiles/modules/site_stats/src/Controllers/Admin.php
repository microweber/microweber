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
        if (!function_exists('get_comments')) {
            return;
        }

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

    function visits_graph($params)
    {

        $users_online = get_visits('users_online');
        $visits_daily = get_visits();
        $v_weekly = get_visits('weekly');
        $v_monthly = get_visits('monthly');

        if (!$v_monthly) {
            return;
        }

        $view_file = $this->views_dir . 'visits_graph.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('visits_daily', $visits_daily);
        $view->assign('visits_weekly', $v_weekly);
        $view->assign('visits_monthly', $v_monthly);
        $view->assign('users_online', $users_online);
        return $view->display();
    }
}
