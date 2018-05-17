<?php


namespace shop\orders\controllers;

use Microweber\View;


class Admin
{
    public $app = null;
    public $views_dir = 'views';


    function __construct($app = null)
    {

        only_admin_access();


        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->views_dir = dirname(__DIR__) . DS . 'views' . DS;


    }

    private function _get_orders_from_params($params)
    {
        $ord = 'order_by=id desc';
        $orders = false;

        if (isset($params['order'])) {
            $data['order_by'] = $params['order'];
            $ord = 'order_by=' . $params['order'];
        }

        $orders_type = 'completed';
        $kw = '';

        if (isset($params['keyword'])) {
            $kw = '&search_in_fields=email,first_name,last_name,country,created_at,transaction_id,city,state,zip,address,phone,user_ip,payment_gw&keyword=' . $params['keyword'];
        }

        if (isset($params['order-type']) and $params['order-type'] == 'carts') {

            // for abandoned carts
            $orders_type = 'carts';
            $ord = 'order_by=updated_at desc';
            $orders = get_cart('limit=1000&group_by=session_id&no_session_id=true&order_completed=0&' . $ord);

        } else {
            $skip_new = false;
            $limit = 'no_limit=true';
            $limit = 'limit=10';
            $cur_page = false;
            if (isset($params['current_page'])) {
                $cur_page = '&current_page=' . $params['current_page'];
                $skip_new = true;
            }

            if (isset($params['limit'])) {
                $limit = 'limit=' . $params['limit'];

            }
            if (isset($params['get_new_orders'])) {

                $orders = get_orders($limit . '&order_completed=1&order_status=new&' . $ord . $kw);

            } else {
                $orders = get_orders($limit . $cur_page . '&order_completed=1&' . $ord . $kw);
                if (isset($params['page_count_only'])) {
                    $orders = get_orders($limit . '&count_paging=1&order_completed=1&' . $ord . $kw);

                }

            }


        }

        return $orders;

    }

    function index($params)
    {


        $orders_type = 'completed';
        if (isset($params['order-type']) and $params['order-type'] == 'carts') {
            $orders_type = 'carts';
        }


        $params2 = $params;
        $params2['page_count_only'] = true;

        $has_new = false;
        $new_orders = false;
        $current_page = false;

        $orders = $this->_get_orders_from_params($params);
        $orders_page_count = $this->_get_orders_from_params($params2);


        $params2 = $params;
        $current_page = false;


        if (isset($params['current_page'])) {
            $current_page = $params['current_page'];
        } elseif (url_param('current_page')) {
            $current_page = url_param('current_page');
        }


        if (!$current_page) {
            $params2['get_new_orders'] = true;
            $new_orders = $this->_get_orders_from_params($params);
            if ($new_orders) {
                $has_new = true;
            }
        }


        if (isset($params['intersect-new-orders']) and $new_orders and $orders) {
            foreach ($new_orders as $new_ord) {
                foreach ($orders as $old_k => $old_ord) {
                    if (isset($old_ord['id'])) {
                        if (isset($new_ord['id'])) {
                            if ($old_ord['id'] == $new_ord['id']) {
                                unset($orders[$old_k]);
                            }
                        }
                    }
                }
            }
        }


        $view_file = $this->views_dir . 'admin.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('has_new', $has_new);
        $view->assign('orders', $orders);
        $view->assign('orders_page_count', $orders_page_count);
        $view->assign('new_orders', $new_orders);
        $view->assign('orders_type', $orders_type);
        $view->assign('current_page', $current_page);
        //   $view->assign('abandoned_carts', $abandoned_carts);
        //  $view->assign('completed_carts', $completed_carts);

        return $view->display();


    }

    function abandoned_carts($params)
    {
        $abandoned_carts = get_cart('count=1&no_session_id=true&order_completed=0&group_by=session_id');
        $completed_carts = get_orders('count=1&order_completed=1');
        $orders = $this->_get_orders_from_params($params);


        $view_file = $this->views_dir . 'abandoned_carts.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('orders', $orders);

        $view->assign('abandoned_carts', $abandoned_carts);
        $view->assign('completed_carts', $completed_carts);

        return $view->display();


    }

    function edit_order($params)
    {


        $ord = mw()->shop_manager->get_order_by_id($params['order-id']);

        if (isset($ord['order_status']) and $ord['order_status'] == 'new') {


            $s = array();
            $s['id'] = $ord['id'];
            $s['order_status'] = 'pending';
            mw()->order_manager->save($s);


        }

        $cart_items = array();
        if (is_array($ord)) {
            $cart_items = false;
            if (empty($cart_items)) {
                $cart_items = mw()->shop_manager->order_items($ord['id']);
            }
        } else {
            mw_error("Invalid order id");
        }

        $show_ord_id = $ord['id'];
        if (isset($ord['order_id']) and $ord['order_id'] != false) {
            $show_ord_id = $ord['order_id'];
        }


        $view_file = $this->views_dir . 'edit_order.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('show_ord_id', $show_ord_id);
        $view->assign('cart_items', $cart_items);
        $view->assign('ord', $ord);


        return $view->display();
    }
}