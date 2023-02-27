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

namespace MicroweberPackages\Cart;

use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Database\Crud;

class CartManager extends Crud
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    public $table = 'cart';
    public $coupon_data = false;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }


    }

    /**
     * This will sum all cart items amount
     * @param bool $return_amount
     * @return array|false|float|int|mixed
     */
    public function sum($return_amount = true)
    {
        if ($return_amount) {
            return $this->app->cart_repository->getCartAmount();
        } else {
            return $this->app->cart_repository->getCartItemsCount();
        }

    }

    public function totals($return = 'all')
    {
        $all_totals = array('subtotal', 'shipping', 'tax', 'discount', 'total');


        $tax = $shipping_cost = $discount_sum = 0;

        $shipping_cost = $this->app->checkout_manager->getShippingCost();
        $shipping_modules = $this->app->checkout_manager->getShippingModules();

        // Coupon code discount
        $discount_value = $this->get_discount_value();
        $discount_type = $this->get_discount_type();

        $sum = $subtotal = $this->sum();

        if ($discount_type == 'percentage' or $discount_type == 'percentage') {
            // Discount with percentage
            $discount_sum = ($sum * ($discount_value / 100));
            $sum = $sum - $discount_sum;
        } else if ($discount_type == 'fixed_amount') {
            // Discount with amount
            $discount_sum = $discount_value;
            $sum = $sum - $discount_value;
        }


        $total = $sum + $shipping_cost;

        if (get_option('enable_taxes', 'shop') == 1) {
            if ($total > 0) {
                $tax = $this->app->tax_manager->calculate($sum);
                $total = $total + $tax;
            }
        }


        $totals = array();
        foreach ($all_totals as $total_key) {
            switch ($total_key) {
                case 'subtotal':
                    $totals[$total_key] = array(
                        'label' => _e("Subtotal", true),
                        'value' => $subtotal,
                        'amount' => currency_format($subtotal)
                    );
                    break;
                case 'tax':
                    if ($tax) {
                        $totals[$total_key] = array(
                            'label' => _e("Tax", true),
                            'value' => $tax,
                            'amount' => currency_format($tax)
                        );
                    }
                    break;


                case 'discount':
                    if ($discount_sum and $discount_sum > 0) {
                        $totals[$total_key] = array(
                            'label' => _e("Discount", true),
                            'value' => $discount_sum,
                            'amount' => currency_format($discount_sum)
                        );
                    }
                    break;

                case 'shipping':

                    if ($shipping_modules) {
                        if ($shipping_cost and $shipping_cost > 0) {
                            $totals[$total_key] = array(
                                'label' => _e("Shipping", true),
                                'value' => $shipping_cost,
                                'amount' => currency_format($shipping_cost)
                            );
                        }
                    }


                    break;

                case 'total':

                    $totals[$total_key] = array(
                        'label' => _e("Total", true),
                        'value' => $total,
                        'amount' => currency_format($total)
                    );


                    break;
            }


        }

        if (isset($return) and $return != 'all') {
            if (isset($totals[$return])) {
                return $totals[$return];
            }
        } else {
            return $totals;
        }

    }

    public function total()
    {
        $total = $this->totals('total');

        if (isset($total['value'])) {
            return $total['value'];
        }
    }


    public function get_tax()
    {
        $sum = $this->sum();
        $tax = $this->app->tax_manager->calculate($sum);

        return $tax;
    }

    public function get_discount()
    {
        return $this->get_discount_value();
    }

    public function get_discount_type()
    {
        $data = $this->couponCodeGetDataFromSession();
        if (empty($data)) {
            return false;
        }
        if (isset($data['discount_type'])) {
            return $data['discount_type'];
        }
        return false;
    }

    public function set_coupon_data($data)
    {
        $this->coupon_data = $data;
    }

    public function get_discount_value()
    {
        $data = $this->couponCodeGetDataFromSession();


        if (empty($data)) {
            return false;
        }

        if (!isset($data['discount_value'])) {
            return false;
        }

        if (!isset($data['total_amount'])) {
            return false;
        }

        $apply_code = false;
        if ($this->sum() >= $data['total_amount']) {
            $apply_code = true;
        }

        if ($apply_code) {
            return floatval($data['discount_value']);
        }

        return false;
    }

    public function get_discount_text()
    {
        if ($this->get_discount_type() == "percentage" or $this->get_discount_type() == "percentage") {
            return $this->get_discount_value() . "%";
        } else {
            return currency_format($this->get_discount_value());
        }
    }

    public function get($params = false)
    {
        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        $table = $this->table;
        $params['table'] = $table;
        $skip_sid = false;
        if (!defined('MW_API_CALL')) {
            if (isset($params['order_id'])) {
                $skip_sid = 1;
            }
        }
        if ($skip_sid == false) {
            if (!defined('MW_ORDERS_SKIP_SID')) {
                if ($this->app->user_manager->is_admin() == false) {
                    $params['session_id'] = mw()->user_manager->session_id();
                } else {
                    if (isset($params['session_id']) and $this->app->user_manager->is_admin() == true) {
                    } else {
                        $params['session_id'] = mw()->user_manager->session_id();
                    }
                }
                if (isset($params['no_session_id']) and $this->app->user_manager->is_admin() == true) {
                    unset($params['session_id']);
                }
            }
        }
        if (!isset($params['rel']) and isset($params['for'])) {
            $params['rel_type'] = $params['for'];
        } elseif (isset($params['rel']) and !isset($params['rel_type'])) {
            $params['rel_type'] = $params['rel'];
        }
        if (!isset($params['rel_id']) and isset($params['for_id'])) {
            $params['rel_id'] = $params['for_id'];
        }

        $params['limit'] = 10000;
        if (!isset($params['order_completed'])) {
            if (!isset($params['order_id'])) {
                $params['order_completed'] = 0;
            }
        } elseif (isset($params['order_completed']) and $params['order_completed'] === 'any') {
            unset($params['order_completed']);
        }
        // $params['no_cache'] = 1;
        $get = $this->app->database_manager->get($params);
        if (isset($params['count']) and $params['count'] != false) {
            return $get;
        }
        $return = array();
        if (is_array($get)) {
            foreach ($get as $k => $item) {
                if (is_array($item)) {
                    if (isset($item['rel_id']) and isset($item['rel_type']) and $item['rel_type'] == 'content') {
                        $item['content_data'] = $this->app->content_manager->data($item['rel_id']);
                        $item['url'] = $this->app->content_manager->link($item['rel_id']);
                        $item['picture'] = $this->app->media_manager->get_picture($item['rel_id']);
                    }
                    if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
                        $item = $this->app->format->render_item_custom_fields_data($item);
                    }
                    if (isset($item['title'])) {
                        $item['title'] = html_entity_decode($item['title']);
                        $item['title'] = strip_tags($item['title']);
                        $item['title'] = $this->app->format->clean_html($item['title']);
                        $item['title'] = htmlspecialchars_decode($item['title']);
                    }
                    if (!isset($item['url'])) {
                        $item['url'] = '';
                    }
                    if (!isset($item['picture'])) {
                        $item['picture'] = '';
                    }
                }

                $return[$k] = $item;
            }
        } else {
            $return = $get;
        }

        return $return;
    }

    public function get_by_order_id($order_id = false)
    {
        $order_id = intval($order_id);
        if ($order_id == false) {
            return;
        }
        $params = array();
        $table = $this->table;
        $params['table'] = $table;
        $params['order_id'] = $order_id;
        $get = $this->app->database_manager->get($params);

        if (!empty($get)) {
            foreach ($get as $k => $item) {

                if (is_array($item) and isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
                    $item = $this->app->format->render_item_custom_fields_data($item);
                }

                if (!isset($item['item_image']) and is_array($item) and isset($item['rel_id']) and isset($item['rel_type']) and $item['rel_type'] == 'content') {
                    $item['item_image'] = get_picture($item['rel_id']);
                }

                if (!isset($item['item_image'])) {
                    $item['item_image'] = false;
                }

                $get[$k] = $item;
            }
        }

        return $get;
    }

    public function remove_item($data)
    {
        if (!is_array($data)) {
            $id = intval($data);
            $data = array('id' => $id);
        }

        if (!isset($data['id']) or $data['id'] == 0) {
            return false;
        }

        $cart = array();
        $cart['id'] = intval($data['id']);

        // if ($this->app->user_manager->is_admin() == false) {
        $cart['session_id'] = mw()->user_manager->session_id();
        // }

        $cart['order_completed'] = 0;
        $cart['one'] = 1;
        $cart['limit'] = 1;

        $checkCart = $this->get($cart);

        if ($checkCart != false and is_array($checkCart)) {

            $findCart = Cart::where('id', $cart['id'])->first();
            if ($findCart) {
                $findCart->delete();
            }

            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');


            $cart_sum = $this->sum(true);
            $cart_qty = $this->sum(false);

            return array('success' => _e('Item was removed from cart', true), 'product' => $checkCart, 'cart_sum' => $cart_sum, 'cart_items_quantity' => $cart_qty);
        } else {
            return array('error' => _e('Item not removed from cart', true));
        }
    }

    public function update_item_qty($data)
    {
        if (!isset($data['id'])) {
            return array('error' => _e('Invalid data', true));
        }
        if (!isset($data['qty'])) {
            return array('error' => _e('Invalid data', true));
        }

        $data['qty'] = intval($data['qty']);
        if ($data['qty'] < 1) {
            return array('error' => _e('Invalid product quantity', true));
        }

        $data_fields = false;

        $cart = array();
        $cart['id'] = intval($data['id']);


        $cart['session_id'] = mw()->user_manager->session_id();

        $cart['order_completed'] = 0;
        $cart['one'] = 1;
        $cart['limit'] = 1;
        $check_cart = $this->get($cart);
        if (isset($check_cart['rel_type']) and isset($check_cart['rel_id']) and $check_cart['rel_type'] == 'content') {
            $data_fields = $this->app->content_manager->data($check_cart['rel_id'], 1);
            if (isset($check_cart['qty']) and isset($data_fields['qty']) and $data_fields['qty'] != 'nolimit') {
                $old_qty = intval($data_fields['qty']);
                if (intval($data['qty']) > $old_qty) {
                    return array('error' => true, 'message' => _e('Quantity not changed, because there are not enough items in stock.', true), 'cart_item_quantity_available' => $check_cart['qty']);
                }
            }
        }

        if ($check_cart != false and is_array($check_cart)) {
            $cart['qty'] = intval($data['qty']);
            if ($cart['qty'] < 0) {
                $cart['qty'] = 0;
            }


            if (isset($data_fields['max_qty_per_order']) and intval($data_fields['max_qty_per_order']) != 0) {

                if ($cart['qty'] > intval($data_fields['max_qty_per_order'])) {
                    $cart['qty'] = intval($data_fields['max_qty_per_order']);
                }
            }


            $cart_return = $check_cart;


            $table = $this->table;
            $cart_data_to_save = array();
            $cart_data_to_save['qty'] = $cart['qty'];
            $cart_data_to_save['id'] = $cart['id'];
            $cart_saved_id = $this->app->database_manager->save($table, $cart_data_to_save);

            $cart_sum = $this->sum(true);
            $cart_qty = $this->sum(false);

            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');

            return array('success' => _e('Item quantity changed', true), 'product' => $cart_return, 'cart_sum' => $cart_sum, 'cart_items_quantity' => $cart_qty);


        }
    }


    public function empty_cart()
    {
        $sid = mw()->user_manager->session_id();
        $cart_table = $this->table;

        Cart::where('order_completed', 0)->where('session_id', $sid)->delete();
        $this->no_cache = true;
        $this->app->cache_manager->delete('cart');
        $this->app->cache_manager->delete('cart_orders');

        $cart_sum = $this->sum(true);
        $cart_qty = $this->sum(false);
        return array('success' => 'Cart is emptied', 'cart_sum' => $cart_sum, 'cart_items_quantity' => $cart_qty);

    }

    public function delete_cart($params)
    {
        if (is_string($params)) {
            $params = parse_params($params);
        }
        if (isset($params['session_id'])) {
            $id = $params['session_id'];
            Cart::where('session_id', $id)->delete();
        }
        if (isset($params['order_id'])) {
            $id = $params['order_id'];
            Cart::where('order_id', $id)->delete();
        }
        $this->app->cache_manager->delete('cart');
        $this->app->cache_manager->delete('cart_orders');
    }

    public function update_cart($data)
    {
        if (!isset($data['for']) and isset($data['rel_type'])) {
            $data['for'] = $data['rel_type'];
        }
        if (!isset($data['for_id']) and isset($data['rel_id'])) {
            $data['for_id'] = $data['rel_id'];
        }
        if (!isset($data['for']) and !isset($data['rel_type'])) {
            $data['for'] = 'content';
        }

        if (isset($data['content_id'])) {
            $data['for'] = 'content';
            $for_id = $data['for_id'] = $data['content_id'];
        }
        $override = $this->app->event_manager->trigger('mw.shop.update_cart', $data);
        if (is_array($override)) {
            foreach ($override as $resp) {
                if (is_array($resp) and !empty($resp)) {
                    $data = array_merge($data, $resp);
                }
            }
        }

        $update_qty = 0;
        $update_qty_new = 0;

        if (isset($data['qty'])) {
            $update_qty_new = $update_qty = intval($data['qty']);
            unset($data['qty']);
        }
        if (!isset($data['for']) or !isset($data['for_id'])) {
            if (!isset($data['id'])) {

            } else {
                $cart = array();
                $cart['id'] = intval($data['id']);
                $cart['limit'] = 1;
                $data_existing = $this->get($cart);
                if (is_array($data_existing) and is_array($data_existing[0])) {
                    $data = array_merge($data, $data_existing[0]);
                }
            }
        }


        if (!isset($data['for']) and !isset($data['for_id'])) {
            return array('error' => 'Invalid for and for_id params');
        }

        $data['for'] = $this->app->database_manager->assoc_table_name($data['for']);
        $for = $data['for'];
        $for_id = intval($data['for_id']);
        if ($for_id == 0) {
            return array('error' => 'Invalid data for_id');
        }
        $cont_data = false;

        if ($update_qty > 0) {
            $data['qty'] = $update_qty;
        }

        if ($data['for'] == 'content') {

            $cont = $this->app->content_manager->get_by_id($for_id);

            if (isset($cont['is_active'])) {
                if ($cont['is_active'] != 1) {
                    $cont = false;
                }
            }

            if (isset($cont['is_deleted'])) {
                if ($cont['is_deleted'] > 0) {
                    $cont = false;
                }
            }

            $cont_data = $this->app->content_manager->data($for_id);
            if ($cont == false) {
                return array('error' => 'Invalid product?');
            } else {
                if (is_array($cont) and isset($cont['title'])) {
                    $data['title'] = $cont['title'];
                }
            }
        }

        if (isset($data['title']) and is_string($data['title'])) {
            $data['title'] = (strip_tags($data['title']));
        }

        $found_price = false;
        $add = array();

        if (isset($data['custom_fields_data']) and is_array($data['custom_fields_data'])) {
            $add = $data['custom_fields_data'];
        }

        $prices = array();

        $skip_keys = array();

        $content_custom_fields = $this->app->fields_manager->get([
            'rel_type' => $for,
            'rel_id' => $for_id,
            'return_full' => true,
        ]);

        $product_prices = array();
        if ($for == 'content') {
            $prices_data = app()->shop_manager->get_product_prices($for_id, true);
            if ($prices_data) {
                foreach ($prices_data as $price_data) {
                    if (isset($price_data['name'])) {
                        $product_prices[$price_data['name']] = $price_data['value'];
                    }
                }
            }
        }

        if ($content_custom_fields == false) {
            $content_custom_fields = $data;

            if (isset($data['price'])) {

                if ($product_prices) {
                    foreach ($product_prices as $price) {
                        if ($price['value'] == $data['price']) {
                            $found_price = $data['price'];
                        }
                    }
                }
            }
        } elseif (is_array($content_custom_fields)) {
            foreach ($content_custom_fields as $cf) {
                if (isset($cf['type']) and $cf['type'] == 'price') {
                    if (isset($product_prices[$cf['name']])) {
                        $prices[$cf['name']] = $product_prices[$cf['name']];
                    } else {
                        $prices[$cf['name']] = $cf['value'];
                    }
                }
            }
        }

        foreach ($data as $k => $item) {
            if ($k != 'for' and $k != 'for_id' and $k != 'title') {
                $found = false;
                foreach ($content_custom_fields as $cf) {
                    if (isset($cf['type']) and isset($cf['name']) and $cf['type'] != 'price') {
                        if(isset($data[$cf['name_key']])){
                            $cf['name'] = $data[$cf['name_key']];
                        }
                   } elseif (isset($cf['type']) and $cf['type'] == 'price' and isset($cf['name']) and isset($cf['value'])) {
                        if ($cf['value'] != '') {
                            if (isset($product_prices[$cf['name']])) {
                                $prices[$cf['name']] = $product_prices[$cf['name']];
                            } else {
                                $prices[$cf['name']] = $cf['value'];
                            }
                        }
                    }
                }

                if ($content_custom_fields) {
                    foreach ($content_custom_fields as $cf) {
                        if (isset($cf['type']) and isset($cf['name']) and $cf['type'] != 'price') {
                            if ($k == $cf['name']) {
                                $found = true;
                            } else if ($k == $cf['name_key']) {
                                $found = true;
                            }
                        }
                    }
                }

                if ($found == false) {
                    $skip_keys[] = $k;
                }

                if (is_array($prices)) {
                    foreach ($prices as $price_key => $price) {
                        if (isset($data['price'])) {
                            if ($price == $data['price']) {
                                $found = true;
                                $found_price = $price;
                            }
                        } elseif (isset($item) and $price == $item) {
                            $found = true;
                            if ($found_price == false) {
                                $found_price = $item;
                            }
                        }
                    }
                    if ($found_price == false) {
                        $found_price = array_pop($prices);
                    } else {
                        if (count($prices) > 1) {
                            foreach ($prices as $pk => $pv) {
                                if ($pv == $found_price) {
                                    $add[$pk] = $this->app->shop_manager->currency_format($pv);
                                }
                            }
                        }
                    }
                }
                if (isset($item)) {
                    if ($found == true) {
                        if ($k != 'price' and !in_array($k, $skip_keys)) {
                            $add[$k] = $this->app->format->clean_html($item);
                        }
                    }
                }
            }
        }

        if ($found_price == false and is_array($prices)) {
            $found_price = array_pop($prices);
        }
        if ($found_price == false) {
            $found_price = 0;
        }


        if (is_array($prices)) {
            ksort($add);
            asort($add);
            $add = mw()->format->clean_xss($add);
            $table = $this->table;


            $cart = array();
            $cart['rel_type'] = trim($data['for']);
            $cart['rel_id'] = intval($data['for_id']);
            $cart['session_id'] = mw()->user_manager->session_id();
            $cart['no_cache'] = 1;
            $cart['disable_triggers'] = 1;
            $cart['order_completed'] = 0;
            $cart['custom_fields_data'] = $this->app->format->array_to_base64($add);

            $cart['custom_fields_json'] = json_encode($add);
            $cart['allow_html'] = 1;
            $cart['price'] = doubleval($found_price);
            $cart['limit'] = 1;
            $cart['title'] = mw()->format->clean_html($data['title']);

            $cart_return['custom_fields_data'] = $add;
            $cart_return['price'] = $cart['price'];

            $findCart = Cart::where('custom_fields_data', $cart['custom_fields_data'])
                ->where('session_id', $cart['session_id'])
                ->where('order_completed', $cart['order_completed'])
                ->where('rel_id', $cart['rel_id'])
                ->where('rel_type', $cart['rel_type'])
                ->first();
            $check_cart = false;
            if ($findCart !== null) {
                $check_cart = $findCart->toArray();
            }


            if ($found_price and $check_cart != false and is_array($check_cart) and isset($check_cart['id'])) {
                if ($check_cart and isset($check_cart['price']) and (doubleval($check_cart['price']) == doubleval($found_price))) {
                    $cart['id'] = $check_cart['id'];
                    if ($update_qty > 0) {
                        $cart['qty'] = $check_cart['qty'] + $update_qty;
                    } elseif ($update_qty_new > 0) {
                        $cart['qty'] = $update_qty_new;
                    } else {
                        $cart['qty'] = $check_cart['qty'] + 1;
                    }
                }
            } else {
                if ($update_qty > 0) {
                    $cart['qty'] = $update_qty;
                } else {
                    $cart['qty'] = 1;
                }
            }

            if (isset($cont_data['qty']) and trim($cont_data['qty']) != 'nolimit') {
                if (intval($cont_data['qty']) < intval($cart['qty'])) {
                    $cart['qty'] = $cont_data['qty'];
                }
            }


            if (isset($cont_data['max_qty_per_order']) and intval($cont_data['max_qty_per_order']) != 0) {
                if ($cart['qty'] > intval($cont_data['max_qty_per_order'])) {
                    $cart['qty'] = intval($cont_data['max_qty_per_order']);
                }
            }


            if (isset($data['other_info']) and is_string($data['other_info'])) {
                $cart['other_info'] = strip_tags($data['other_info']);
            }

            if (isset($data['description']) and is_string($data['description'])) {
                $cart_return['description'] = $cart['description'] = $this->app->format->clean_html($data['description']);
            }
            if (isset($data['image']) and is_string($data['image'])) {
                $cart_return['item_image'] = $cart['item_image'] = $this->app->format->clean_html($data['image']);
            }
            if (isset($data['item_image']) and is_string($data['item_image'])) {
                $cart_return['item_image'] = $cart['item_image'] = $this->app->format->clean_html($data['item_image']);
            }
            if (isset($data['link']) and is_string($data['link'])) {
                $cart_return['link'] = $cart['link'] = $this->app->format->clean_html($data['link']);
            }

            if (isset($data['currency']) and is_string($data['currency'])) {
                $cart_return['currency'] = $cart['currency'] = $this->app->format->clean_html($data['link']);
            }

            // Update cart in database
            if ($findCart == null) {
                $findCart = new Cart();
                $findCart->rel_id = $cart['rel_id'];
                $findCart->rel_type = $cart['rel_type'];
                $findCart->custom_fields_data = $cart['custom_fields_data'];
                $findCart->custom_fields_json = $cart['custom_fields_json'];
            }

            $findCart->qty = $cart['qty'];
            $findCart->title = $cart['title'];
            $findCart->price = $cart['price'];
            $findCart->session_id = $cart['session_id'];
            $findCart->order_completed = $cart['order_completed'];
            $findCart->session_id = $cart['session_id'];
            $findCart->save();

            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');

            if (isset($cart['rel_type']) and isset($cart['rel_id']) and $cart['rel_type'] == 'content') {
                $cart_return['image'] = $this->app->media_manager->get_picture($cart['rel_id']);
                $cart_return['product_link'] = $this->app->content_manager->link($cart['rel_id']);
            }
            $cart_sum = $this->sum(true);
            $cart_qty = $this->sum(false);

            $this->app->cache_manager->delete('cart');
            $this->app->cache_manager->delete('cart_orders');

            return array('success' => 'Item added to cart', 'product' => $cart_return, 'cart_sum' => $cart_sum, 'cart_items_quantity' => $cart_qty);
        } else {
            return array('error' => 'Invalid cart items');
        }
    }

    public function recover_cart($sid = false, $ord_id = false)
    {
        if ($sid == false) {
            return;
        }
        $cur_sid = mw()->user_manager->session_id();
        if ($cur_sid == false) {
            return;
        } else {


            if ($cur_sid != false) {
                $c_id = $sid;
                $table = $this->table;
                $params = array();
                //   $params['order_completed'] = 0;
                $params['session_id'] = $c_id;
                $params['table'] = $table;
                if ($ord_id != false) {
                    unset($params['order_completed']);
                    $params['order_id'] = intval($ord_id);
                }

                $will_add = true;
                $res = $this->app->database_manager->get($params);

                if (!empty($res)) {
                    foreach ($res as $item) {
                        if (isset($item['id'])) {
                            $data = $item;
                            unset($data['id']);
                            if (isset($item['order_id'])) {
                                unset($data['order_id']);
                            }
                            if (isset($item['created_by'])) {
                                unset($data['created_by']);
                            }
                            if (isset($item['updated_at'])) {
                                unset($data['updated_at']);
                            }
                            if (isset($item['rel_type']) and isset($item['rel_id'])) {
                                $is_ex_params = array();
                                $is_ex_params['order_completed'] = 0;
                                $is_ex_params['session_id'] = $cur_sid;
                                $is_ex_params['table'] = $table;
                                $is_ex_params['rel_type'] = $item['rel_type'];
                                $is_ex_params['rel_id'] = $item['rel_id'];
                                $is_ex_params['count'] = 1;

                                $is_ex = $this->app->database_manager->get($is_ex_params);

                                if ($is_ex != false) {
                                    $will_add = false;
                                }
                            }
                            $data['order_completed'] = 0;
                            $data['session_id'] = $cur_sid;

                            if (isset($item['order_completed']) and intval($item['order_completed']) == 1) {
                                if ($sid == $cur_sid) {
                                    if (isset($item['is_paid']) and intval($item['is_paid']) == 0) {
                                        $data['id'] = $item['id'];
                                    }
                                }
                            }
                            if ($will_add == true) {
                                $s = $this->app->database_manager->save($table, $data);
                            }
                        }
                    }
                }
                if ($will_add == true) {
                    $this->app->cache_manager->delete('cart');

                    $this->app->cache_manager->delete('cart_orders');
                }
            }
        }
    }

    public function table_name()
    {
        return $this->table;
    }


    public function is_product_in_stock($content_id)
    {

        $item = content_data($content_id);
        $isInStock = true;
        if ($item) {
            if (isset($item['qty']) and $item['qty'] != 'nolimit') {
                $quantity = intval($item['qty']);
                if ($quantity < 1) {
                    $isInStock = false;
                }
            }

        }

        return $isInStock;
    }

    public function couponCodeGetDataFromSession()
    {
        $coupon_code = $this->app->user_manager->session_get('coupon_code');
        if ($coupon_code and !$this->couponCodeCheckIfValid($coupon_code)) {
            //check if coupon is valid
            if (function_exists('coupons_delete_session')) {
                coupons_delete_session();
            }

            $this->coupon_data = false;
        } else {
            if ($coupon_code and function_exists('coupon_get_by_code')) {
                $this->coupon_data = coupon_get_by_code($coupon_code);
            } else {
                $this->coupon_data = false;
            }
        }
        return $this->coupon_data;
    }

    public function couponCodeCheckIfValid($coupon_code)
    {
        if (function_exists('coupon_apply')) {
            //check if coupon is valid
            $coupon_valid = coupon_apply([
                'coupon_code' => $coupon_code,
                'coupon_check_if_valid' => true
            ]);
            if (!$coupon_valid) {
                return false;
            }
            return true;
        }
        return false;

    }


}
