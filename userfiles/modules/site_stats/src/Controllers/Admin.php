<?php


namespace Microweber\SiteStats\Controllers;

use Microweber\SiteStats\Stats;
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

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'users_online';
        $stats = new Stats();
        $users_online = $stats->get_stats_count($get_visits_params);


        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'visitors_count';
        $stats = new Stats();
        $visits_count = $stats->get_stats_count($get_visits_params);


        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'views_count';
        $stats = new Stats();
        $views_count = $stats->get_stats_count($get_visits_params);


        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'orders_count';
        $stats = new Stats();
        $orders_count = $stats->get_stats_count($get_visits_params);


        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'comments_count';
        $stats = new Stats();
        $comments_count = $stats->get_stats_count($get_visits_params);



        $get_visits_params = array();
        $get_visits_params['period'] = $params['period'];
        $get_visits_params['return'] = 'views_count_grouped_by_period';
        $stats = new Stats();
        $views_count_grouped_by_period = $stats->get_stats_count($get_visits_params);


//        $visits_daily = get_visits();
//        $v_weekly = get_visits('weekly');
//        $v_monthly = get_visits('monthly');
//
//        if (!$v_monthly) {
//            return;
//        }

        $graph_data = array();
        $graph_data['views'] = $views_count_grouped_by_period;

        $view_file = $this->views_dir . 'visits_graph.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('views_count', $views_count);
        $view->assign('visits_count', $visits_count);
        $view->assign('orders_count', $orders_count);
        $view->assign('comments_count', $comments_count);
        $view->assign('users_online', $users_online);
      //  $view->assign('visitors_count_by_period', $visitors_count_by_period);



        $view->assign('graph_data', $graph_data);
        return $view->display();
    }
}
