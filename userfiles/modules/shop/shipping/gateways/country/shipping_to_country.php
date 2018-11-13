<?php

namespace shop\shipping\gateways\country;

api_bind('shop/shipping/gateways/country/shipping_to_country/test', 'shop/shipping/gateways/country/shipping_to_country/test2');

// print('shop/shipping/gateways/country/shipping_to_country/test'. 'shop/shipping/gateways/country/shipping_to_country/test2');
api_expose_admin('shop/shipping/gateways/country/shipping_to_country/save');
api_expose('shop/shipping/gateways/country/shipping_to_country/set');
api_expose('shop/shipping/gateways/country/shipping_to_country/get');
api_expose_admin('shop/shipping/gateways/country/shipping_to_country/delete');
api_expose_admin('shop/shipping/gateways/country/shipping_to_country/reorder');

class shipping_to_country
{

    // singleton instance
    public $table;
    public $app;

    // private constructor function
    // to prevent external instantiation
    function __construct($app = false)
    {
        $this->table = 'cart_shipping';
        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }

    }

    function get_cost()
    {

        // $defined_cost = $this->app->user_manager->session_get('shipping_cost');
        $shipping_country = $this->app->user_manager->session_get('shipping_country');
        $defined_cost = 0;
        $shipping_country = $this->get('one=1&is_active=1&shipping_country=' . $shipping_country);
        $is_worldwide = false;
        if ($shipping_country == false) {
            $shipping_country = $this->get('one=1&is_active=1&shipping_country=Worldwide');
            if (is_array($shipping_country)) {
                $is_worldwide = true;

            }
        }
        if ($shipping_country == false) {


            $shipping_country = $this->get('order_by=position asc&one=1&is_active=1');


            //
        }

        if ($shipping_country == false) {
            $this->app->user_manager->session_set('shipping_country', 'none');

            $this->app->user_manager->session_set('shipping_cost', 0);

            return false;
        }


        if (isset($shipping_country['id'])) {
            if (isset($shipping_country['shipping_type']) and $shipping_country['shipping_type'] == 'fixed') {
                $defined_cost = floatval($shipping_country['shipping_cost']);

            }


        } else {
            return false;
        }

        if (isset($shipping_country['shipping_type']) and $shipping_country['shipping_type'] == 'dimensions') {
            $total_shipping_weight = 0;
            $total_shipping_volume = 0;

            $items_cart_count = $this->app->shop_manager->cart_sum(false);
            if ($items_cart_count > 0) {
                $items_items = $this->app->shop_manager->get_cart();
                if (!empty($items_items)) {
                    foreach ($items_items as $item) {
                        if (!isset($item['content_data'])) {
                            $item['content_data'] = array();
                        }

                        $content_data = $item['content_data'];


                        if (!isset($content_data['is_free_shipping']) or $content_data['is_free_shipping'] != 'y') {

                            if (isset($content_data['additional_shipping_cost']) and intval($content_data['additional_shipping_cost']) > 0) {

                                $volume = floatval($content_data['additional_shipping_cost']) * intval($item['qty']);

                                $defined_cost = $defined_cost + $volume;

                            } else {
                                if (isset($content_data['shipping_weight']) and $content_data['shipping_weight'] != '') {
                                    $weight = floatval($content_data['shipping_weight']);
                                    $weight = $weight * intval($item['qty']);
                                    $total_shipping_weight = $total_shipping_weight + $weight;

                                }


                                if (isset($content_data['shipping_width']) and $content_data['shipping_width'] != ''
                                    and isset($content_data['shipping_height']) and $content_data['shipping_height'] != ''
                                    and isset($content_data['shipping_depth']) and $content_data['shipping_depth'] != ''
                                ) {


                                    $volume = floatval($content_data['shipping_width']) * floatval($content_data['shipping_height']) * floatval($content_data['shipping_depth']);
                                    $volume = $volume * intval($item['qty']);
                                    $total_shipping_volume = $total_shipping_volume + $volume;

                                }

                            }
                        }


                    }
                }
            }


            if (isset($shipping_country['shipping_price_per_weight']) and trim($shipping_country['shipping_price_per_weight']) != '') {
                if ($total_shipping_weight > 1) {
                    $calc = floatval($shipping_country['shipping_price_per_weight']);
                    $calc2 = $calc * ceil($total_shipping_weight - 1);
                    $defined_cost = $defined_cost + $calc2;
                }

            }
            if (isset($shipping_country['shipping_price_per_size']) and trim($shipping_country['shipping_price_per_size']) != '') {
                $calc = floatval($shipping_country['shipping_price_per_size']);
                $calc2 = $calc * $total_shipping_weight;
                $defined_cost = $defined_cost + $calc2;
            }


        } else if (isset($shipping_country['shipping_type']) and $shipping_country['shipping_type'] == 'per_item') {
            if (isset($shipping_country['shipping_price_per_item']) and intval($shipping_country['shipping_price_per_item']) != 0) {


                $calc = floatval($shipping_country['shipping_price_per_item']);
                $calc2 = 0;

                $items_cart_count = $this->app->shop_manager->cart_sum(false);
                if ($items_cart_count > 0) {
                    $items_items = $this->app->shop_manager->get_cart();
                    if (!empty($items_items)) {
                        foreach ($items_items as $item) {
                            if (isset($item['content_data'])) {
                                $content_data = $item['content_data'];

                                if (!isset($content_data['is_free_shipping']) or $content_data['is_free_shipping'] != 'y') {
                                    if (isset($content_data['additional_shipping_cost']) and intval($content_data['additional_shipping_cost']) > 0) {

                                        $volume = floatval($content_data['additional_shipping_cost']) * intval($item['qty']);

                                        $defined_cost = $defined_cost + $volume;

                                    } else {
                                        $volume = $calc * intval($item['qty']);

                                        $calc2 = $calc2 + $volume;
                                    }
                                }

                            }


                        }
                    }


                }
                $defined_cost = $defined_cost + $calc2;


            }


        }


        $items_cart_amount = $this->app->shop_manager->cart_sum();

        if (isset($shipping_country['shipping_cost_above']) and intval($shipping_country['shipping_cost_above']) > 0) {
            $shipping_cost_above = floatval($shipping_country['shipping_cost_above']);
            if (intval($shipping_cost_above) > 0 and intval($shipping_country['shipping_cost_max']) > 0) {
                if ($items_cart_amount > $shipping_cost_above) {
                    $defined_cost = floatval($shipping_country['shipping_cost_max']);
                }
            }
        }
        if (isset($shipping_country['shipping_cost']) and intval($shipping_country['shipping_cost']) > 0) {
            if (isset($shipping_country['shipping_type']) and $shipping_country['shipping_type'] == 'fixed') {

            } else {
                $defined_cost = $defined_cost + floatval($shipping_country['shipping_cost']);

            }
        }
        if ($is_worldwide == false) {
            $this->app->user_manager->session_set('shipping_country', $shipping_country['shipping_country']);
        }

        $this->app->user_manager->session_set('shipping_cost', $defined_cost);

        return $defined_cost;
    }

    function test()
    {
        return 'ping ';
    }

    function test2()
    {
        return 'pong ';
    }

    // getInstance method
    function save($data)
    {
        if (is_admin() == false) {
            error('Must be admin');

        }
        if (isset($data['id']) and $data['id'] == 0) {
            if (!isset($data['shipping_country'])) {
                return 0;
            }
        }

        if (isset($data['shipping_country'])) {
            if ($data['shipping_country'] == 'none') {
                error('Please choose country');
            }
            if (isset($data['id']) and intval($data['id']) > 0) {

            } else {
                $check = $this->get('shipping_country=' . $data['shipping_country']);
                if ($check != false and is_array($check[0]) and isset($check[0]['id'])) {
                    $data['id'] = $check[0]['id'];
                }
            }
        }

        $data = mw()->database_manager->save($this->table, $data);
        return ($data);
    }

    function get($params = false)
    {

        $params = parse_params($params);

        $params['table'] = $this->table;

        if (!isset($params['order_by'])) {
            $params['order_by'] = 'position ASC';
        }
        $params['limit'] = 1000;

        return mw()->database_manager->get($params);

    }

    function delete($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            mw()->database_manager->delete_by_id($this->table, $c_id);

        }

        return true;
    }

    function set($params = false)
    {

        $active = array();
        if (isset($params['shipping_country'])) {
            $params['country'] = $params['shipping_country'];
        }

        if (isset($params['country'])) {
            $is_worldwide = false;
            $active = $this->get('one=1&is_active=1&shipping_country=' . $params['country']);
            if (!is_array($active)) {
                $active = $this->get('one=1&is_active=1&shipping_country=Worldwide');
                if (is_array($active)) {
                    $is_worldwide = true;
                }
            }
            if (is_array($active) and isset($active['shipping_country'])) {
                if ($is_worldwide == true) {
                    $active['shipping_country'] = $params['country'];
                }
                $this->app->user_manager->session_set('shipping_country', $active['shipping_country']);
                $active['cost'] = $this->get_cost();
            }
        }
        if (is_array($params) and !empty($params)) {
            foreach ($params as $k => $v) {
                if ($k != 'country' and $k != 'shipping_country') {
                    if (is_string($k)) {
                        $k = strip_tags($k);
                        $v = strip_tags($v);
                        $this->app->user_manager->session_set('shipping_' . $k, $v);
                    }
                }

            }
        }

        return $active;

    }

    function reorder($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->table;


        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $i++;
                }

                $this->app->database_manager->update_position_field($table, $indx);

                return true;
                // d($indx);
            }
        }
    }


}
