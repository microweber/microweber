<?php

namespace shop\shipping\gateways\express;

api_bind('shop/shipping/gateways/express/shipping_express/test', 'shop/shipping/gateways/express/shipping_express/test2');

// print('shop/shipping/gateways/express/shipping_express/test'. 'shop/shipping/gateways/express/shipping_express/test2');
api_expose_admin('shop/shipping/gateways/express/shipping_express/save');
api_expose('shop/shipping/gateways/express/shipping_express/set');
api_expose('shop/shipping/gateways/express/shipping_express/get');
api_expose_admin('shop/shipping/gateways/express/shipping_express/delete');
api_expose_admin('shop/shipping/gateways/express/shipping_express/reorder');

class shipping_express
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
        $shipping_country_name = $shipping_country_session = $this->app->user_manager->session_get('shipping_country');

        if ($shipping_country_name == false and is_logged()) {
            $shipping_address_from_profile = app()->user_manager->get_shipping_address();
            if ($shipping_address_from_profile and isset($shipping_address_from_profile['country'])) {
                $shipping_country_name = $shipping_address_from_profile['country'];
            }
        }


        $defined_cost = 0;
        $is_worldwide = false;
        if ($shipping_country_name == false) {
            $shipping_country = $this->get('one=1&is_active=1&shipping_country=Worldwide');
            if (is_array($shipping_country)) {
                $is_worldwide = true;
            }
        } else {
            $shipping_country = $this->get('one=1&is_active=1&shipping_country=' . $shipping_country_name);
            if (!$shipping_country) {
                $shipping_country = $this->get('one=1&is_active=1&shipping_country=Worldwide');
                if (is_array($shipping_country)) {
                    $is_worldwide = true;
                }
            }

        }


        if ($shipping_country == false) {


            $shipping_country = $this->get('order_by=position asc&one=1&is_active=1');


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
            // if (intval($shipping_cost_above) > 0 and intval($shipping_country['shipping_cost_max']) > 0) {
            if (isset($shipping_country['shipping_cost_max']) and trim($shipping_country['shipping_cost_max']) != '') {
                if ($items_cart_amount >= $shipping_cost_above) {
                    $defined_cost = floatval($shipping_country['shipping_cost_max']);
                    //   }
                }
            }
        }
        if (isset($shipping_country['shipping_cost']) and intval($shipping_country['shipping_cost']) > 0) {
            if (isset($shipping_country['shipping_type']) and $shipping_country['shipping_type'] == 'fixed') {

            } else {
                $defined_cost = $defined_cost + floatval($shipping_country['shipping_cost']);

            }
        }


        if (!$shipping_country_session) {
            //  if ($is_worldwide == false) {
            //     $this->app->user_manager->session_set('shipping_country', $shipping_country['shipping_country']);
            // }
        }
//var_dump($shipping_country);
        $this->app->user_manager->session_set('shipping_cost', $defined_cost);
        app()->shipping_manager->setDefaultDriver('shop/shipping/gateways/express');

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

        app()->shipping_manager->setDefaultDriver('shop/shipping/gateways/express');


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

                session_set('shipping_country', $active['shipping_country']);
                $active['cost'] = $this->get_cost();
            }
        }
        if (is_array($params) and !empty($params)) {
            foreach ($params as $k => $v) {
                if ($k != 'country' and $k != 'shipping_country') {
                    if (is_string($k) and is_string($v)) {
                        $k = strip_tags($k);
                        $v = strip_tags($v);
                        //  session_set('shipping_' . $k, $v);
                    }
                }

            }
        }

        $shipping_fields_keys = ['country', 'address', 'city', 'state', 'zip', 'other_info'];
        $shipping_fields_vals_session = [];
        $shipping_fields_to_save = null;
        $look_for_address_in_array = $params;
        if (isset($params['Address']) and is_array($params['Address'])) {
            $merge = $params['Address'];
            unset($params['Address']);
            $look_for_address_in_array = array_merge($params, $merge);
        }

        if (is_array($look_for_address_in_array) and !empty($look_for_address_in_array)) {
            foreach ($look_for_address_in_array as $k => $v) {
                foreach ($shipping_fields_keys as $k1) {
                    if ($k == $k1 and !isset($shipping_fields_vals_session[$k1])) {
                        $shipping_fields_vals_session[$k1] = $v;
                    }
                }
            }
        }
        if ($shipping_fields_vals_session) {
            session_set('checkout', $shipping_fields_vals_session);
        }
        session_set('shipping_country', $active['shipping_country']);
        $selected_country_from_session = session_get('shipping_country');

        //   session_set('shipping_country_data', $active);

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


    public function get_available_countries()
    {
        $data_disabled = $this->get("is_active=0");
        $data = $this->get("is_active=1");
        $countries_all = mw()->forms_manager->countries_list_from_json();

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (trim(strtolower($item['shipping_country'])) == 'worldwide') {
                    unset($data[$key]);
                    if (is_array($countries_all)) {
                        foreach ($countries_all as $countries_new) {
                            $data[] = array('shipping_country' => $countries_new);
                        }
                    }
                }
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($data_disabled)) {
                    foreach ($data_disabled as $item_disabled) {
                        if (trim(strtolower($item_disabled['shipping_country'])) == 'worldwide') {
                                foreach ($data as $key => $item) {
                                    if ($item['shipping_country'] == $item_disabled['shipping_country']){
                                        unset($data[$key]);
                                    }
                                }


                        } else if ($item['shipping_country'] == $item_disabled['shipping_country']) {
                            unset($data[$key]);
                        }
                    }
                }

            }
        }

        $ready = [];
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                $ready[] = $item;
            }
        }

        return $ready;

    }


}
