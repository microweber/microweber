<?php

only_admin_access();

$ord = mw()->shop_manager->get_order_by_id($params['order-id']);

$cart_items = array();
if (is_array($ord)) {
    $cart_items = false;
    if (empty($cart_items)) {
        $cart_items = mw()->shop_manager->order_items($ord['id']);
    }
}
else {
    mw_error("Invalid order id");
}

 
?>
<?php 
			  
$show_ord_id = $ord['id'];	
if(isset($ord['order_id']) and $ord['order_id'] != false){
	$show_ord_id = $ord['order_id'];
} 

?>
<?php include(__DIR__.DS.'_partials'.DS.'order_inner_scripts.php'); ?>

<div class="mw-admin-section-shop">
  <div class="mw-ui-row mw-admin-main-section-inner-panel">
    <div class="mw-ui-col">
      <h2 class="mw-admin-main-section-inner-panel-title">
        <?php _e("Order"); ?>
        #<?php print $show_ord_id ?> </h2>
    </div>
    <div class="mw-ui-col"> </div>
    <div class="mw-ui-col"> <a href="?show=list" class="mw-btn-blue pull-right"><span class="mw-icon-back"></span> <?php _e('TO ORDERS LIST'); ?> </a> </div>
  </div>
  <div class="mw-admin-small-spacer"></div>
  <?php include(__DIR__.DS.'_partials'.DS.'order_inner_table.php'); ?>
  <div class="mw-admin-normal-spacer"></div>
  <?php include(__DIR__.DS.'_partials'.DS.'order_inner_payment_info_bar.php'); ?>
  <div class="mw-admin-normal-spacer"></div>
  <div class="mw-ui-row">
    <div class="mw-ui-col" style="width:50%">
      <h2 class="mw-admin-main-section-inner-panel-title"><?php _e('Customer'); ?></h2>
      <div class="mw-ui-box" style="margin-top: 10px;">
        <div class="mw-ui-box-content">
          <?php include(__DIR__.DS.'_partials'.DS.'order_inner_box_client_info.php'); ?>
          <a href="<?php print $config['url_main']; ?>/../action:customers?clientorder=<?php print $ord['id'] ?>" class="mw-admin-shop-info-blue-link pull-right">
          <?php _e("Customer information and orders"); ?>
          </a> </div>
      </div>
    </div>
    <div class="mw-ui-col" style="width:50%; padding-left:10px;">
      <h2 class="mw-admin-main-section-inner-panel-title"><?php _e('Order Address'); ?></h2>
      <div class="mw-ui-box" style="margin-top: 10px;">
        <div class="mw-ui-box-content">
          <?php include(__DIR__.DS.'_partials'.DS.'order_inner_box_shipping_and_address.php'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="mw-admin-normal-spacer"></div>
  <?php include(__DIR__.DS.'_partials'.DS.'order_inner_stasus_bar.php'); ?>
  <div class="mw-ui-row mw-admin-main-section-inner-panel">
    <div class="mw-ui-col"> </div>
    <div class="mw-ui-col"> </div>
    <div class="mw-ui-col"> <a href="?show=list" class="mw-btn-blue pull-right"><span class="mw-icon-back"></span> <?php _e('TO ORDERS LIST'); ?> </a> </div>
  </div>
</div>
