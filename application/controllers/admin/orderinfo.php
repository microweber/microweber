<?php
include "orders.php";
class OrderInfo extends Orders {
        function __construct() {
                parent::Orders();

        }
        
        function index(){

			CI::view ( 'admin/popup' );
        }
        function id($id){
        	global $cms_db_tables;
        	$data = array();
        	//parent::ajax_json_get_items_for_order_id();
        	if($id){
        		$q = "SELECT * FROM {$cms_db_tables['table_cart_orders']} WHERE id=$id";
        		$q = $this->core_model->dbQuery ( $q );
        		$array_buf = $q[0];
        		$array_buf2 = array();
        		foreach($array_buf as $key => $val){
        			if($key != 'cvv2'){
        				if($key == 'cardholdernumber') $val = substr_replace($val,"######",0,strlen($val)-4);
        				$array_buf2[$key] = $val;
        			}
        		}
        		$data['order_info'] = $array_buf2;
        	}
        	$primarycontent =$this->load->view ( 'admin/popup', $data );
        }
}
?>