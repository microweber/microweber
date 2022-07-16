<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Order;

use DB;
use Illuminate\Support\Facades\Notification;
use MicroweberPackages\Order\Events\OrderIsCreating;
use MicroweberPackages\Order\Events\OrderWasCreated;
use MicroweberPackages\Order\Events\OrderWasPaid;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Order\Notifications\NewOrderNotification;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Notifications\ProductOutOfStockNotification;
use MicroweberPackages\User\Models\User;

class OrderManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public $table = 'cart_orders';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
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
                $params['session_id'] = mw()->user_manager->session_id();
            }
        }
        if (isset($params['keyword'])) {
            $params['search_in_fields'] = array('first_name', 'last_name', 'email', 'city', 'state', 'zip', 'address', 'address2', 'phone', 'promo_code');
        }
        $table = $table = $this->table;
        $params['table'] = $table;
        $params['no_cache'] = true;

        $data =  $this->app->database_manager->get($params);


        return $data;
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

        if (isset($item['payment_data']) and ($item['payment_data'])) {
            $item['payment_data'] = json_decode($item['payment_data']);
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
        $sid = mw()->user_manager->session_id();
        if ($sid == false) {
            return $sid;
        }

        event($event = new OrderIsCreating($place_order));
        $should_mark_as_paid = false;
        $place_order = array_filter($place_order);
        if (isset($place_order['is_paid']) and intval($place_order['is_paid']) == 1) {
            unset($place_order['is_paid']);
            $should_mark_as_paid = true;
        }
        $ord = $this->app->database_manager->save($this->table, $place_order);
        $place_order['id'] = $ord;

        $orderModel = Order::find($ord);




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
        DB::transaction(function () use ($sid, $ord, $place_order,$should_mark_as_paid) {

            DB::table($this->app->cart_manager->table_name())->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord]);

            $this->app->event_manager->trigger('mw.cart.checkout.recarted_order', $ord);

            if (isset($place_order['order_completed']) and $place_order['order_completed'] == 1) {
                DB::table($this->app->cart_manager->table_name())->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord, 'order_completed' => 1]);

                if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                    DB::table($this->table)->whereOrderCompleted(0)->whereSessionId($sid)->whereId($ord)->update(['order_completed' => 1]);
                }

                $this->app->cache_manager->delete('cart');
                $this->app->cache_manager->delete('cart_orders');

                if (!empty($place_order['promo_code']) and is_module('shop/coupons') ) {
                	\CouponClass::log($place_order['promo_code'], $place_order['email']);
                }

                if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                  //  $this->app->shop_manager->update_quantities($ord);

                  //  event($event = new OrderWasPaid(Order::find($ord), $place_order));
                }

                if($should_mark_as_paid){
                    $this->app->checkout_manager->mark_order_as_paid($ord);
                }

                $this->app->checkout_manager->after_checkout($ord);
            }
        });
        event($event = new OrderWasCreated($orderModel, $place_order));
        mw()->user_manager->session_set('order_id', $ord);

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
            if ($params['is_paid'] === 'y') {
                $params['is_paid'] = 1;
            } elseif ($params['is_paid'] === 'n') {
                $params['is_paid'] = 0;
            }
        }


        if (isset($params['payment_data']) and !empty($params['payment_data'])) {
            if(is_array($params['payment_data'])){
               $params['payment_data'] = json_encode($params['payment_data']);
            }
        }
        $table = $this->table;
        $params['table'] = $table;
        $this->app->cache_manager->delete('cart_orders');
        $this->app->cache_manager->delete('cart');
        $this->app->cache_manager->delete('shop');

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
            $cart_items = mw()->shop_manager->order_items($item['id']);
            $cart_items_str = mw()->format->array_to_ul($cart_items, 'div', 'span');
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
        $filename_path = storage_path() . DS . 'export' . DS . 'orders' . DS;
        $filename_path_index = storage_path() . DS . 'export' . DS . 'orders' . DS . 'index.php';
        if (!is_dir($filename_path)) {
            mkdir_recursive($filename_path);
        }
        if (!is_file($filename_path_index)) {
            @touch($filename_path_index);
        }
        $filename_path_full = $filename_path . $filename;

        // $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv = \League\Csv\Writer::createFromPath($filename_path_full, 'w'); //to work make sure you have the write permission

        $csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8); //adding the BOM sequence on output

        //we insert the CSV header
        $csv->insertOne(array_keys(reset($export)));

        $csv->insertAll($export);

        return response()->download($filename_path_full);
    }

    /**
     * Remove quantity from product.
     *
     * On completed order this function deducts the product quantities.
     *
     * @param bool|string $order_id
     *                              The id of the order that is completed
     *
     * @return bool
     *              True if quantity is updated
     */
    public function update_quantities($order_id = false)
    {

       // dd('update_quantities',123123123154555555,$order_id);
        $order_id = intval($order_id);
        if ($order_id == false) {
            return;
        }
        $res =  array();
        $ord_data = $this->get_by_id($order_id);

        $cart_data = $this->get_items($order_id);
        if (empty($cart_data)) {
            return $res;
        }
        $res = array();
        foreach ($cart_data as $item) {
            if (!isset($item['rel_type']) or !isset($item['rel_id']) or $item['rel_type'] !== 'content') {
                continue;
            }
            $data_fields = $this->app->content_manager->data($item['rel_id'], 1);
            if (!isset($item['qty']) or !isset($data_fields['qty']) or $data_fields['qty'] == 'nolimit') {
                continue;
            }
            $old_qty = intval($data_fields['qty']);
            $new_qty = $old_qty - intval($item['qty']);
            $new_qty = intval($new_qty);
            $notify = false;
            $new_q = array();
            $new_q['field_name'] = 'qty';
            $new_q['content_id'] = $item['rel_id'];
            if ($new_qty > 0) {
                $new_q['field_value'] = $new_qty;
            } else {
                $notify = true;
                $new_q['field_value'] = '0';
            }

            $res[] = $new_q;
            $upd_qty = $this->app->content_manager->save_content_data_field($new_q);
            if ($notify) {
                $notifiables = User::whereIsAdmin(1)->get();
                if($notifiables){
                    $product = Product::find($item['rel_id']);
                    if ($product) {
                        Notification::send($notifiables, new ProductOutOfStockNotification($product));
                    }
                }
            }
        }

        return $res;
    }



}
