<?php

/*

type: layout

name: Big

description: Full width cart template

*/

?>

<div class="mw-cart mw-cart-<?php print $params['id']?> <?php print  $template_css_prefix  ?>">
  <div class="mw-cart-title mw-cart-<?php print $params['id']?>">
    <h4 style="margin-top: 16px;" class="edit" rel="<?php print $params['id']?>" field="cart_title">
      <?php  _e('My cart'); ?>
    </h4>
  </div>
  <?php if(isarr($data)) :?>
  <table class="table table-bordered table-striped mw-cart-table mw-cart-table-medium">
    <colgroup>
        <col width="60">
        <col width="620">
        <col width="120">
        <col width="140">
        <col width="140">
    </colgroup>
    <thead>
      <tr>
        <th><?php _e("Image"); ?></th>
        <th class="mw-cart-table-product"><?php _e("Product Name"); ?></th>
        <th><?php _e("QTY"); ?></th>
        <th><?php _e("Price"); ?></th>
        <th><?php _e("Total"); ?></th>
        <th><?php _e("Delete"); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
       $total = 0;
       foreach ($data as $item) :
       $total += $item['price']* $item['qty'];
       ?>
      <tr class="mw-cart-item mw-cart-item-<?php print $item['id'] ?>">
      	 <?php $p = get_picture($item['rel_id']); ?>
         <td>
      <?php if($p != false): ?>
      <img height="70" class="img-polaroid img-rounded mw-order-item-image mw-order-item-image-<?php print $item['id'] ; ?>" src="<?php print thumbnail($p, 70,70); ?>"  />
      <?php endif; ?>
      </td>
        <td class="mw-cart-table-product">
	   <?php print $item['title'] ?>
          <?php if(isset($item['custom_fields'])): ?>
          <?php print $item['custom_fields'] ?>
          <?php  endif ?></td>
        <td><input type="number" class="input-mini" value="<?php print $item['qty'] ?>" onchange="mw.cart.qty('<?php print $item['id'] ?>', this.value)" /></td>
        <?php /*<td><?php print currency_format($item['price']); ?></td>*/ ?>
        <td class="mw-cart-table-price"><?php print currency_format($item['price']); ?></td>
        <td class="mw-cart-table-price"><?php print currency_format($item['price']* $item['qty']); ?></td>
        <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<?php print $item['id'] ?>');"></a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

   <?php  $shipping_options =  api('shop/shipping/shipping_api/get_active');  ?>
	<?php if(isarr($shipping_options)) :?>
    <div>
    <h3><?php _e("Continue Shopping or Complete Order"); ?></h3>
    <table cellspacing="0" cellpadding="0" class="table table-bordered table-striped mw-cart-table mw-cart-table-medium checkout-total-table">
        <tbody>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-country"><label><?php _e("Shipping to"); ?>:</label> <module type="shop/shipping/select" /></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-price"><label><?php _e("Shipping price"); ?>:</label> <span class="shiping_cost"><?php print currency_format(session_get('shiping_cost')); ?></span></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-total"><label><?php _e("Total Price"); ?>:</label> <span class="total_cost"><?php print currency_format($total + intval(session_get('shiping_cost'))); ?></span></td>
            </tr>
        </tbody>
    </table>
  </div>
  <?php endif ; ?>
  <?php  
      if(!isset($params['checkout-link-enabled'])){
    	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
      } else {
    	   $checkout_link_enanbled = $params['checkout-link-enabled'];
      }
   ?>
  <?php if($checkout_link_enanbled != 'n') :?>
  <?php $checkout_page =get_option('data-checkout-page', $params['id']); ?>
  <?php if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   $checkout_page_link = content_link($checkout_page).'/view:checkout';
   } else {
	   $checkout_page_link = site_url('checkout');;
   }
   ?>
  <a class="btn btn-warning pull-right" href="<?php print $checkout_page_link; ?>"><?php _e("Checkout"); ?></a>

  <a href="javascript:;" class="btn pull-right continue-shopping"><?php _e("Continue Shopping"); ?></a>

  <?php endif ; ?>
  <?php else : ?>
  <h4 class="alert"><?php _e("Your cart is empty."); ?></h4>
  <?php endif ; ?>
</div>
