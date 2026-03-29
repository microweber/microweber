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

namespace Modules\Order\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use MicroweberPackages\User\Models\User;
use Modules\ContentData\Models\ContentData;
use Modules\Order\Events\OrderIsCreating;
use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Exceptions\OrderException;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\Product\Notifications\ProductOutOfStockNotification;

/**
* OrderManager - Manages order operations for the e-commerce system.
*
* This class handles order creation, retrieval, updating, and deletion,
* as well as order export functionality and quantity management.
*/
class OrderManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    /** @var string The database table name for orders */
    public $table = 'cart_orders';

    /**
    * Create a new OrderManager instance.
    *
    * @param object|null $app The application instance (defaults to mw())
    */
    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    /**
    * Place a new order from the current cart.
    *
    * Converts cart items to an order and triggers order events.
    *
    * @param array $place_order Order data including customer info, shipping, etc.
    * @return int|false The new order ID or false on failure
    * @throws OrderException
    */
    public function place_order($place_order = array())
    {
        try {
            $sid = mw()->user_manager->session_id();
            if ($sid == false) {
                throw OrderException::invalidOrderData('session_id', 'No valid session found');
            }

            if (empty($place_order)) {
                throw OrderException::invalidOrderData('order_data', 'Order data cannot be empty');
            }

            array_walk_recursive(
                $place_order,
                function (&$string) {
                    if (is_string($string)) {
                        $string = trim(strip_tags($string));
                    }
                }
            );
            $place_order = xss_clean($place_order);
            event($event = new OrderIsCreating($place_order));
            $should_mark_as_paid = false;
            $place_order = array_filter($place_order);
            if (isset($place_order['is_paid']) and intval($place_order['is_paid']) == 1) {
                unset($place_order['is_paid']);
                $should_mark_as_paid = true;
            }

            try {
                $ord = $this->app->database_manager->save('cart_orders', $place_order);
            } catch (QueryException $e) {
                Log::error('Failed to create order in database', [
                    'exception' => $e->getMessage(),
                    'place_order' => $place_order,
                ]);
                throw OrderException::databaseOperationFailed('save', 'cart_orders', $e);
            }

            $place_order['id'] = $ord;

            try {
                $orderModel = Order::find($ord);
            } catch (QueryException $e) {
                Log::error('Failed to retrieve created order', [
                    'exception' => $e->getMessage(),
                    'order_id' => $ord,
                ]);
                throw OrderException::orderNotFound($ord);
            }

            // cart is cleared here and converted to order
            try {
                DB::transaction(function () use ($sid, $ord, $place_order, $should_mark_as_paid) {
                    //DB::table('cart')->whereOrderCompleted(0)->whereSessionId($sid)->update(['order_id' => $ord]);
                    DB::table('cart')->where('order_completed', 0)
                        ->where('session_id', $sid)->update(['order_id' => $ord]);

                    // $this->app->event_manager->trigger('mw.cart.checkout.recarted_order', $ord);

                    if (isset($place_order['order_completed']) and $place_order['order_completed'] == 1) {

                        DB::table('cart')->where('order_completed', 0)
                            ->where('session_id', $sid)
                            ->update(['order_id' => $ord, 'order_completed' => 1]);

                        if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                            DB::table('cart_orders')->where('order_completed', 0)
                                ->where('session_id', $sid)->where('id', $ord)
                                ->update(['order_completed' => 1]);
                        }

                        $this->app->cache_manager->delete('cart');
                        $this->app->cache_manager->delete('cart_orders');
                        $this->app->cache_manager->delete('customers');


                        if (isset($place_order['is_paid']) and $place_order['is_paid'] == 1) {
                            event($event = new OrderWasPaid(Order::find($ord), $place_order));
                        }

                        if ($should_mark_as_paid) {
                            $this->app->checkout_manager->mark_order_as_paid($ord);
                        }

                        $this->app->checkout_manager->after_checkout($ord);
                    }
                });
            } catch (QueryException $e) {
                Log::error('Failed to process order transaction', [
                    'exception' => $e->getMessage(),
                    'order_id' => $ord,
                    'session_id' => $sid,
                ]);
                throw OrderException::databaseOperationFailed('transaction', 'cart/cart_orders', $e);
            }

            event($event = new OrderWasCreated(Order::find($ord), $place_order));
            mw()->user_manager->session_set('order_id', $ord);

            return $ord;
        } catch (OrderException $e) {
            // Re-throw OrderException as-is
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error during order placement', [
                'exception' => $e->getMessage(),
                'place_order' => $place_order ?? [],
            ]);
            throw OrderException::orderPlacementFailed('Unexpected error occurred', $place_order ?? [], $e);
        }
    }

    /**
    * Update an existing order.
    *
    * @param array|string|false $params Order data to update or query string
    * @return int|false The updated order ID or false on failure
    * @throws OrderException
    */
    public function save($params = false)
    {
        try {
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
                if (is_array($params['payment_data'])) {
                    $params['payment_data'] = json_encode($params['payment_data']);
                }
            }
            $table = 'cart_orders';
            $params['table'] = $table;
            $this->app->cache_manager->delete('cart_orders');
            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('shop');

            try {
                return $this->app->database_manager->save($table, $params);
            } catch (QueryException $e) {
                Log::error('Failed to save order', [
                    'exception' => $e->getMessage(),
                    'params' => $params,
                ]);
                throw OrderException::databaseOperationFailed('save', 'cart_orders', $e);
            }
        } catch (OrderException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error during order save', [
                'exception' => $e->getMessage(),
                'params' => $params ?? [],
            ]);
            throw OrderException::databaseOperationFailed('save', 'cart_orders', $e);
        }
    }

    /**
    * Export orders to CSV file.
    *
    * Exports either a specific order or all completed orders.
    *
    * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|false CSV file download response
    * @throws OrderException
    */
    public function export_orders()
    {
        try {
            if (isset($_POST['id'])) {
                $data = get_orders('no_limit=true&id=' . intval($_POST['id']));
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
                $cart_items = app()->order_manager->order_items($item['id']);
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
        } catch (\Exception $e) {
            Log::error('Failed to export orders', [
                'exception' => $e->getMessage(),
            ]);
            throw OrderException::orderPlacementFailed('Failed to export orders', [], $e);
        }
    }

    /**
    * Remove quantity from product.
    *
    * On completed order this function deducts the product quantities.
    *
    * @param bool|string $order_id
    * The id of the order that is completed
    *
    * @return bool
    * True if quantity is updated
    * @throws OrderException
    */
    public function update_quantities($order_id = false)
    {
        try {
            $order_id = intval($order_id);
            if ($order_id == false) {
                throw OrderException::invalidOrderData('order_id', 'Order ID is required');
            }
            $res = array();
            
            try {
                $ord_data = $this->get_by_id($order_id);
            } catch (QueryException $e) {
                Log::error('Failed to retrieve order data', [
                    'exception' => $e->getMessage(),
                    'order_id' => $order_id,
                ]);
                throw OrderException::orderNotFound($order_id);
            }

            try {
                $cart_data = $this->get_items($order_id);
            } catch (QueryException $e) {
                Log::error('Failed to retrieve order items', [
                    'exception' => $e->getMessage(),
                    'order_id' => $order_id,
                ]);
                throw OrderException::databaseOperationFailed('get', 'cart', $e);
            }

            if (empty($cart_data)) {
                return $res;
            }

            $contentReltype = morph_name(\Modules\Content\Models\Content::class);
            $res = array();
            foreach ($cart_data as $item) {

                if (!isset($item['rel_type']) or !isset($item['rel_id']) or $item['rel_type'] !== $contentReltype) {
                    continue;
                }

                $data_fields = content_data($item['rel_id']);

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

                try {
                    ContentData::where('rel_type', $contentReltype)
                        ->where('rel_id', $item['rel_id'])
                        ->where('field_name', 'qty')
                        ->update(['field_value' => $new_q['field_value']]);
                } catch (QueryException $e) {
                    Log::error('Failed to update product quantity', [
                        'exception' => $e->getMessage(),
                        'content_id' => $item['rel_id'],
                        'new_qty' => $new_qty,
                    ]);
                    throw OrderException::databaseOperationFailed('update', 'content_data', $e);
                }

                if ($notify) {
                    try {
                        $notifiables = User::whereIsAdmin(1)->get();
                        if ($notifiables) {
                            $product = Product::find($item['rel_id']);
                            if ($product) {
                                Notification::send($notifiables, new ProductOutOfStockNotification($product));
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send out of stock notification', [
                            'exception' => $e->getMessage(),
                            'content_id' => $item['rel_id'],
                        ]);
                    }
                }
            }

            return $res;
        } catch (OrderException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error during quantity update', [
                'exception' => $e->getMessage(),
                'order_id' => $order_id ?? null,
            ]);
            throw OrderException::databaseOperationFailed('update_quantities', 'content_data', $e);
        }
    }

    /**
     * Get order by ID.
     *
     * @param int|string $order_id
     * @return array|false
     */
    public function get_by_id($order_id)
    {
        try {
            $order_id = intval($order_id);
            if ($order_id == 0) {
                return false;
            }

            $params = [
                'table' => $this->table,
                'id' => $order_id,
                'single' => true,
            ];

            return $this->app->database_manager->get($params);
        } catch (QueryException $e) {
            Log::error('Failed to retrieve order by ID', [
                'exception' => $e->getMessage(),
                'order_id' => $order_id,
            ]);
            throw OrderException::databaseOperationFailed('get', 'cart_orders', $e);
        }
    }

    /**
     * Get order items by order ID.
     *
     * @param int|string $order_id
     * @return array
     */
    public function get_items($order_id)
    {
        try {
            if (is_array($order_id)) {
                $order_id = $order_id['id'] ?? 0;
            }
            $order_id = intval($order_id);
            if ($order_id == 0) {
                return [];
            }

            $params = [
                'table' => 'cart',
                'order_id' => $order_id,
                'limit' => 1000,
            ];

            $result = $this->app->database_manager->get($params);
            return $result ?: [];
        } catch (QueryException $e) {
            Log::error('Failed to retrieve order items', [
                'exception' => $e->getMessage(),
                'order_id' => $order_id,
            ]);
            throw OrderException::databaseOperationFailed('get', 'cart', $e);
        }
    }

    /**
     * Alias for get_items.
     *
     * @param int|string $order_id
     * @return array
     */
    public function order_items($order_id)
    {
        $items = $this->get_items($order_id);
        return empty($items) ? false : $items;
    }
}
