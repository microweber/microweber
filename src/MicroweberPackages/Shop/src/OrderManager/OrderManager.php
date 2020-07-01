<?php

namespace MicroweberPackages\OrderManager;

use DB;

class OrderManager
{
    /** @var \Microweber\Application */
    public $app;

    public $table = 'cart_orders';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = app();
        }
    }

    public function get($params = false)
    {
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        if (defined('MW_API_CALL') and $this->app->user_manager->is_admin() == false) {
            if (!isset($params['payment_verify_token'])) {
                $params['session_id'] = app()->user_manager->session_id();
            }
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('first_name', 'last_name', 'email', 'city', 'state', 'zip', 'address', 'address2', 'phone', 'promo_code');
        }
        $table = $table = $this->table;
        $params['table'] = $table;

        return $this->app->database_manager->get($params);
    }

    public function get_by_id($id = false)
    {
        $table = $this->table;
        $params['table'] = $table;
        $params['one'] = true;

        $params['id'] = intval($id);

        $item = $this->app->database_manager->get($params);

        if (is_array($item) and isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
            $item = $this->app->format->render_item_custom_fields_data($item);
        }

        return $item;
    }
    public function get_count_of_new_orders()
    {
        return $this->get('count=1&order_status=new&is_completed=y');

    }

    public function get_items($order_id = false)
    {
        return $this->app->cart_manager->get_by_order_id($order_id);
    }

    public function delete_order($data)
    {
        // this function also handles ajax requests from admin

        $adm = $this->app->user_manager->is_admin();

        if (defined('MW_API_CALL') and $adm == false) {
            return $this->app->error('Not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = $table = $this->table;
        if (!is_array($data)) {
            $data = array('id' => intval($data));
        }
        if (isset($data['is_cart']) and trim($data['is_cart']) != 'false' and isset($data['id'])) {
            $this->app->cart_manager->delete_cart('session_id=' . $data['id']);

            return $data['id'];
        } elseif (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database_manager->delete_by_id($table, $c_id);
            $this->app->event_manager->trigger('mw.cart.delete_order', $c_id);
            $this->app->cart_manager->delete_cart('order_id=' . $data['id']);

            return $c_id;
        }
    }

    public function place_order($place_order)
    {
        $sid = app()->user_manager->session_id();
        if ($sid == false) {
            return $sid;
        }

        $place_order = array_filter($place_order);
        $ord = $this->app->database_manager->save($this->table, $place_order);
        $place_order['id'] = $ord;

        //get client

//        $client_id = $this->app->clients_manager->find_or_create_client_id($place_order);
//
//        if (isset($place_order['email']) and $place_order['email']) {
//            $client = array();
//            $client['email'] = $place_order['email'];
//            if (isset($place_order['email']) and $place_order['email']) {
//
//            }
//        }

        DB::transaction(function () use ($sid, $ord, $place_order) {

            DB::table($this->app->cart_manager->table_name())->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord]);

            if (isset($place_order['order_completed']) and $place_order['order_completed'] == 1) {
                DB::table($this->app->cart_manager->table_name())->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord, 'order_completed' => 1]);

                if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                    DB::table($this->table)->whereOrderCompleted(0)->whereSessionId($sid)->whereId($ord)->update(['order_completed' => 1]);
                }
	
                $this->app->cache_manager->delete('cart');
                $this->app->cache_manager->delete('cart_orders');
                
                if (!empty($place_order['promo_code'])) {
                	\CouponClass::log($place_order['promo_code'], $place_order['email']);
                }
                
                if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                    $this->app->shop_manager->update_quantities($ord);
                }
                $this->app->shop_manager->after_checkout($ord);
            }
        });

        app()->user_manager->session_set('order_id', $ord);

        return $ord;
    }

    /**
     * update_order.
     *
     * updates order by parameters
     *
     * @category       shop module api
     */
    public function save($params = false)
    {
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_params($params);
        }

        if (isset($params['is_paid'])) {
            if ($params['is_paid'] == 'y') {
                $params['is_paid'] = 1;
            } elseif ($params['is_paid'] == 'n') {
                $params['is_paid'] = 0;
            }
        }

        $table = $this->table;
        $params['table'] = $table;
        $this->app->cache_manager->delete('cart_orders');
        $this->app->cache_manager->delete('cart');

        return $this->app->database_manager->save($table, $params);
    }

    public function export_orders()
    {
        if (isset($_POST['id'])) {
            $data = get_orders('no_limit=true&id='. intval($_POST['id']));
        } else {
            $data = get_orders('no_limit=true&order_completed=1');
        }

        if (!$data) {
            return array('error' => 'You do not have any orders');
        }
        $allowed = array(
            'id', 'created_at', 'created_at', 'amount', 'transaction_id', 'shipping', 'currency', 'is_paid',
            'user_ip', 'last_name', 'email', 'country', 'city', 'state', 'zip', 'address', 'phone', 'payment_gw',
            'order_status', 'taxes_amount', 'cart', 'transaction_id',

        );
        $export = array();
        foreach ($data as $item) {
            $cart_items = app()->shop_manager->order_items($item['id']);
            $cart_items_str = app()->format->array_to_ul($cart_items, 'div', 'span');
            $cart_items_str = (strip_tags($cart_items_str, '<span>'));
            $cart_items_str = str_replace('</span>', "\r\n", $cart_items_str);
            $cart_items_str = (strip_tags($cart_items_str));

            if (!empty($cart_items)) {
                $item['cart'] = $cart_items_str;
            }
            foreach ($item as $key => $value) {
                if (!in_array($key, $allowed)) {
                    unset($item[$key]);
                }
            }
            $export[] = $item;
        }

        if (empty($export)) {
            return;
        }


        $filename = 'orders' . '_' . date('Y-m-d_H-i', time()) . uniqid() . '.csv';
        $filename_path = userfiles_path() . 'export' . DS . 'orders' . DS;
        $filename_path_index = userfiles_path() . 'export' . DS . 'orders' . DS . 'index.php';
        if (!is_dir($filename_path)) {
            mkdir_recursive($filename_path);
        }
        if (!is_file($filename_path_index)) {
            @touch($filename_path_index);
        }
        $filename_path_full = $filename_path . $filename;

        // $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv = \League\Csv\Writer::createFromPath($filename_path_full, 'w'); //to work make sure you have the write permission
        $csv->setEncodingFrom('UTF-8'); // this probably is not needed?

        $csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8); //adding the BOM sequence on output

        //we insert the CSV header
        $csv->insertOne(array_keys(reset($export)));

        $csv->insertAll($export);

        $download = $this->app->url_manager->link_to_file($filename_path_full);

        return array('success' => 'Your file has been exported!', 'download' => $download);
    }

    public function export_orders1()
    {
        $data = get_orders('no_limit=true&order_completed=1');
        if (!$data) {
            return array('error' => 'You do not have any orders');
        }

        $csv_output = '';
        $head = reset($data);
        foreach ($head as $k => $v) {
            $csv_output .= $this->app->format->no_dashes($k) . ',';
            // $csv_output .= "\t";
        }
        $csv_output .= "\n";
        foreach ($data as $item) {
            foreach ($item as $k => $v) {
                $csv_output .= $this->app->format->no_dashes($v) . ',';
                //  $csv_output .= "\t";
            }
            $cart_items = app()->shop_manager->order_items($item['id']);
            if (!empty($cart_items)) {
            }


            $csv_output .= "\n";
        }



        $filename = 'orders' . '_' . date('Y-m-d_H-i', time()) . uniqid() . '.csv';
        $filename_path = userfiles_path() . 'export' . DS . 'orders' . DS;
        $filename_path_index = userfiles_path() . 'export' . DS . 'orders' . DS . 'index.php';
        if (!is_dir($filename_path)) {
            mkdir_recursive($filename_path);
        }
        if (!is_file($filename_path_index)) {
            @touch($filename_path_index);
        }
        $filename_path_full = $filename_path . $filename;
        file_put_contents($filename_path_full, $csv_output);
        $download = $this->app->url_manager->link_to_file($filename_path_full);

        return array('success' => 'Your file has been exported!', 'download' => $download);

        dd('export_orders');
    }
}
