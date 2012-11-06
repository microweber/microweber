<?

if (!defined("MODULE_DB_TABLE_SHOP")) {
    define('MODULE_DB_TABLE_SHOP', TABLE_PREFIX . 'cart');
}

api_expose('update_cart');

function update_cart($data) {

    if (!session_id() and !headers_sent()) {
        session_start();
    }



    if (!isset($data['for']) or !isset($data['for_id'])) {
        error('Invalid data');
    }

    $for = $data['for'];
    $for_id = $data['for_id'];

    $cfs = array();
    $cfs = get_custom_fields($for, $for_id, 1);
    if ($cfs == false) {
        error('Invalid data');
    }
    $add = array();
    $prices = array();
    $found_price = false;
    foreach ($data as $k => $item) {
        $found = false;
        foreach ($cfs as $cf) {
            if (isset($cf['custom_field_type']) and $cf['custom_field_type'] != 'price') {


                if (isset($cf['custom_field_name']) and $cf['custom_field_name'] == $k) {
                    $found = true;
                    if (is_array($item)) {

                        if (isarr($cf['custom_field_values'])) {

                            $vi = 0;
                            foreach ($item as $ik => $item_value) {

                                if (in_array($item_value, $cf['custom_field_values'])) {

                                } else {
                                    unset($item[$ik]);
                                }

                                $vi++;
                            }
                        }
                        // d($item);
                    } else {
                        if ($cf['custom_field_value'] != $item) {
                            unset($item);
                        }
                    }
                    //   d($k);
                //
                }
            } elseif (isset($cf['custom_field_type']) and $cf['custom_field_type'] == 'price') {
                $prices[] = $cf['custom_field_value'];
            }
        }

        if (isarr($prices)) {

            foreach ($prices as $price) {
                if ($price == $item) {
                    $found = true;
                    if ($found_price == false) {
                        $found_price = $item;
                    }
                    // d($item);
                } else {
                    // unset($item);
                }
            }

            // d($prices);
        }

        //if (isset($item)) {
        if ($found == true) {
            $add[$k] = ($item);
        }
        // }
    }
    if ($found_price == false) {
        // $found_price = 0;
        error('Invalid data: Please post a "price" field with <input name="price"> ');
    }


    if (isarr($prices)) {
        ksort($add);
        asort($add);
        $table = MODULE_DB_TABLE_SHOP;
        $cart = array();
        $cart['to_table'] = db_get_assoc_table_name($data['for']);
        $cart['to_table_id'] = intval($data['for_id']);
        $cart['price'] = floatval($found_price);
        $cart['custom_fields_data'] = encode_var($add);
        $cart['order_completed'] = 'n';
        $cart['session_id'] = session_id();
        $cart['one'] = 1;
        $cart['limit'] = 1;
        //  $cart['no_cache'] = 1;
        $checkz = get_cart($cart);
        d($checkz);
        if ($checkz != false and isarr($checkz)) {
            //    d($check);
            $cart['id'] = $checkz['id'];
            $cart['qty'] = $checkz['qty'] + 1;
            //
        }
        //d($cart);
        $cart_s = save_data($table, $cart);
    } else {
        error('Invalid cart items');
    }

    //  d($data);
    exit;
}

function get_cart($params) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    $table = MODULE_DB_TABLE_SHOP;
    $params['table'] = $table;
    if (is_admin() == false) {
        $params['session_id'] = session_id();
    }
    //  d($params);
    return get($params);
}