<?

if (!defined("MODULE_DB_TABLE_SHOP")) {
    define('MODULE_DB_TABLE_SHOP', TABLE_PREFIX . 'cart');
}

api_expose('update_cart');

function update_cart($data) {

    if (!session_id() and !headers_sent()) {
        session_start();
    }

    if (!isset($data['for'])) {
        $data['for'] = 'table_content';
    }

    if (!isset($data['for']) or !isset($data['for_id'])) {
        error('Invalid data');
    }

    $data['for'] = db_get_assoc_table_name($data['for']);



    $for = $data['for'];
    $for_id = intval($data['for_id']);
    $update_qty = 0;

    if ($for_id == 0) {

        error('Invalid data');
    }

    if ($data['for'] == 'table_content') {
        $cont = get_content_by_id($for_id);

        if ($cont == false) {
            error('Invalid product?');
        } else {
            if (isarr($cont) and isset($cont['title'])) {
                $data['title'] = $cont['title'];
            }
        }
    }


    if (isset($data['qty'])) {
        $update_qty = intval($data['qty']);
        unset($data['qty']);
    }


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
        $cart['to_table'] = ($data['for']);
        $cart['to_table_id'] = intval($data['for_id']);
        $cart['title'] = ($data['title']);
        $cart['price'] = floatval($found_price);
        $cart['custom_fields_data'] = encode_var($add);
        $cart['order_completed'] = 'n';
        $cart['session_id'] = session_id();
        $cart['one'] = 1;
        $cart['limit'] = 1;
        //  $cart['no_cache'] = 1;
        $checkz = get_cart($cart);
        // d($checkz);
        if ($checkz != false and isarr($checkz)) {
            //    d($check);
            $cart['id'] = $checkz['id'];
            if ($update_qty > 0) {
                $cart['qty'] = $checkz['qty'] + $update_qty;
            } else {
                $cart['qty'] = $checkz['qty'] + 1;
            }

            //
        } else {

            if ($update_qty > 0) {
                $cart['qty'] = $update_qty;
            } else {
                $cart['qty'] = 1;
            }
        }
        //
        define('FORCE_SAVE', $table);

        $cart_s = save_data($table, $cart);
        return($cart_s);
    } else {
        error('Invalid cart items');
    }

    //  d($data);
    exit;
}

api_expose('update_cart_item_qty');

function update_cart_item_qty($data) {

    if (!isset($data['id'])) {
        error('Invalid data');
    }

    if (!isset($data['qty'])) {
        error('Invalid data');
    }
    if (!session_id() and !headers_sent()) {
        session_start();
    }
    $cart = array();
    $cart['id'] = intval($data['id']);

    if (is_admin() == false) {
        $cart['session_id'] = session_id();
    }
    $cart['order_completed'] = 'n';

    $cart['one'] = 1;
    $cart['limit'] = 1;
    $checkz = get_cart($cart);

    if ($checkz != false and isarr($checkz)) {
        // d($checkz);
        $cart['qty'] = intval($data['qty']);
        $table = MODULE_DB_TABLE_SHOP;
        define('FORCE_SAVE', $table);

        $cart_s = save_data($table, $cart);
        return($cart_s);
        //   db_delete_by_id($table, $id = $cart['id'], $field_name = 'id');
    } else {

    }
}

api_expose('remove_cart_item');

function remove_cart_item($data) {

    if (!isset($data['id'])) {
        error('Invalid data');
    }
    if (!session_id() and !headers_sent()) {
        session_start();
    }
    $cart = array();
    $cart['id'] = intval($data['id']);

    if (is_admin() == false) {
        $cart['session_id'] = session_id();
    }
    $cart['order_completed'] = 'n';

    $cart['one'] = 1;
    $cart['limit'] = 1;
    $checkz = get_cart($cart);

    if ($checkz != false and isarr($checkz)) {
        // d($checkz);
        $table = MODULE_DB_TABLE_SHOP;
        db_delete_by_id($table, $id = $cart['id'], $field_name = 'id');
    } else {

    }
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