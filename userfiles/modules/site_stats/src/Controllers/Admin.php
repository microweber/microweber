<?php


namespace MicroweberPackages\Modules\SiteStats\Controllers;

use MicroweberPackages\Modules\SiteStats\Stats;
use MicroweberPackages\View\View;


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

    function visitors_list($params)
    {

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'visitors_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/visitors.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();


    }

    function content_list($params)
    {

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'content_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/contents.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();


    }

    function locations_list($params)
    {

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'locations_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/locations.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();


    }

    function referrers_list($params)
    {


        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }

        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'referrers_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/referrers.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();
    }


    function languages_list($params)
    {

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'languages_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/languages.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();


    }

    function quick_stats_by_session($params)
    {
        if (!isset($params['data-user-sid'])) {
            return;
        }
        if (!isset($params['period'])) {
            $params['period'] = 'yearly';
        }

        $sid = $params['data-user-sid'];

        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['only_for_session_id'] = $sid;
        $get_data['return'] = 'content_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'content_list';
        $stats = new Stats();
        $get_data = $stats->get_stats_items($get_data);


        $view_file = $this->views_dir . 'parts/contents.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('data', $get_data);
        return $view->display();


    }

    function visits_graph($params)
    {

        if (!isset($params['period'])) {
            $params['period'] = 'daily';
        }


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'users_online';
        $stats = new Stats();
        $users_online = $stats->get_stats_count($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'visitors_count';
        $stats = new Stats();
        $visits_count = $stats->get_stats_count($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'views_count';
        $stats = new Stats();
        $views_count = $stats->get_stats_count($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'orders_count';
        $stats = new Stats();
        $orders_count = $stats->get_stats_count($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'comments_count';
        $stats = new Stats();
        $comments_count = $stats->get_stats_count($get_data);


        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'views_count_grouped_by_period';
        $stats = new Stats();
        $views_count_grouped_by_period = $stats->get_stats_count($get_data);

        $get_data = array();
        $get_data['period'] = $params['period'];
        $get_data['return'] = 'visits_count_grouped_by_period';
        $stats = new Stats();
        $visits_count_grouped_by_period = $stats->get_stats_count($get_data);


//        $visits_daily = get_visits();
//        $v_weekly = get_visits('weekly');
//        $v_monthly = get_visits('monthly');
//
//        if (!$v_monthly) {
//            return;
//        }

        $views_dates = [];
        if ($views_count_grouped_by_period) {
            foreach ($views_count_grouped_by_period as $views_count_grouped_by) {
                if (isset($views_count_grouped_by['date_key'])) {
                    $views_dates[] = $views_count_grouped_by['date_key'];

                }
            }
        }

        if ($views_dates) {
            if ($visits_count_grouped_by_period) {
                foreach ($visits_count_grouped_by_period as $key => $visits_count_grouped) {
                    if (isset($visits_count_grouped['date_key']) and in_array($visits_count_grouped['date_key'], $views_dates)) {

                    } else {
                        unset($visits_count_grouped_by_period[$key]);
                    }
                }
            }
        }





        $graph_data = array();
        $graph_data['views'] = $views_count_grouped_by_period;
        $graph_data['visits'] = $visits_count_grouped_by_period;

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
