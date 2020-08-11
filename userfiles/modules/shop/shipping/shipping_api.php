<?php

namespace shop\shipping;



class shipping_api {

    // singleton instance
    public $here;
    public $modules_list;

    public $table = 'cart_shipping';
    // private constructor function
    // to prevent external instantiation
    function __construct() {
        $this->here = dirname(__FILE__) . DS . 'gateways' . DS;

        $here = $this->here;


        $shipping_gateways = get_modules('type=shipping_gateway');

        if ($shipping_gateways==false){
            $shipping_gateways = scan_for_modules("cache_group=modules/global&dir_name={$here}");

        }

        if (!empty($shipping_gateways)){
            $gw = array();
            foreach ($shipping_gateways as $item) {
                if (!isset($item['gw_file']) and isset($item['module'])){
                    $item['gw_file'] = $item['module'];
                }
                if (!isset($item['module_base']) and isset($item['module'])){
                    $item['module_base'] = $item['module'];
                }
                $gw[] = $item;

            }

            $this->modules_list = $gw;
        } else {
            $this->modules_list = $shipping_gateways;
        }


    }


    function save($data) {
        if (is_admin()==false){
            error('Must be admin');

        }

        if (isset($data['shipping_country'])){
            if ($data['shipping_country']=='none'){
                error('Please choose country');
            }
            if (isset($data['id']) and intval($data['id']) > 0){

            } else {
                $check = db_get('shipping_country=' . $data['shipping_country']);
                if ($check!=false and is_array($check[0]) and isset($check[0]['id'])){
                    $data['id'] = $check[0]['id'];
                }
            }
        }


        $data = mw()->database_manager->save($this->table, $data);

        return ($data);
    }

    function get_active() {
        $active = array();
        $m = $this->modules_list;
        foreach ($m as $item) {
            if (get_option('shipping_gw_' . $item['module'], 'shipping')=='y'){
                $active [] = $item;
            }
        }

        return $active;
    }


    function get($params = false) {

        return $this->modules_list;

    }

    function delete($data) {

        $adm = is_admin();
        if ($adm==false){
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])){
            $c_id = intval($data['id']);
            mw()->database_manager->delete_by_id($this->table, $c_id);

            //d($c_id);
        }
    }

    function reorder($data) {

        $adm = is_admin();
        if ($adm==false){
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->table;


        foreach ($data as $value) {
            if (is_array($value)){
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[ $i ] = $value2;
                    $i ++;
                }

                mw()->database_manager->update_position_field($table, $indx);

                return true;
            }
        }
    }


}