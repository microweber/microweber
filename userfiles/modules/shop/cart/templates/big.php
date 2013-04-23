<?php

/*

type: layout

name: Big

description: Full width cart template

*/

?>

<div class="mw-cart mw-cart-<? print $params['id']?> <? print  $template_css_prefix  ?>">
  <div class="mw-cart-title mw-cart-<? print $params['id']?>">
    <h4 style="margin-top: 16px;" class="edit" rel="<? print $params['id']?>" field="cart_title">
      <?   _e('My cart') ?>
    </h4>
  </div>
  <? if(isarr($data)) :?>
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
        <th>Image</th>
        <th class="mw-cart-table-product">Product Name</th>
        <th>QTY</th>
        <th>Price</th>
        <th>Total</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?
       $total = 0;
       foreach ($data as $item) :
       $total += $item['price']* $item['qty'];
       ?>
      <tr class="mw-cart-item mw-cart-item-<? print $item['id'] ?>">
      	 <? $p = get_picture($item['rel_id']); ?>
         <td>
      <? if($p != false): ?>
      <img height="70" class="img-polaroid img-rounded mw-order-item-image mw-order-item-image-<?php print $item['id'] ; ?>" src="<?php print thumbnail($p, 70,70); ?>"  />
      <? endif; ?>
      </td>
        <td class="mw-cart-table-product">
		

      

      

		<? print $item['title'] ?>
          <? 	if(isset($item['custom_fields'])): ?>
          <? print $item['custom_fields'] ?>
          <?  endif ?></td>
        <td><input type="number" class="input-mini" value="<? print $item['qty'] ?>" onchange="mw.cart.qty('<? print $item['id'] ?>', this.value)" /></td>
        <?php /*<td><? print currency_format($item['price']); ?></td>*/ ?>
        <td class="mw-cart-table-price"><? print currency_format($item['price']); ?></td>
        <td class="mw-cart-table-price"><? print currency_format($item['price']* $item['qty']); ?></td>
        <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<? print $item['id'] ?>');"></a></td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>



   <?  $shipping_options =  api('shop/shipping/shipping_api/get_active');  ?>
	<? if(isarr($shipping_options)) :?>
    <div>



    <h3>Continue Shopping or Complete Order</h3>

    <table cellspacing="0" cellpadding="0" class="table table-bordered table-striped mw-cart-table mw-cart-table-medium checkout-total-table">
        <tbody>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-country"><label>Shipping to:</label> <module type="shop/shipping/select" /></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-price"><label>Shipping price:</label> <span class="shiping_cost"><?php print currency_format(session_get('shiping_cost')); ?></span></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="width: 260px;" colspan="2" class="cell-shipping-total"><label>Total Price:</label> <span class="total_cost"><?php print currency_format($total + intval(session_get('shiping_cost'))); ?></span></td>
            </tr>
        </tbody>
    </table>
  </div>
  <? endif ; ?>




  


  <?  
  if(!isset($params['checkout-link-enabled'])){
	  $checkout_link_enanbled =  get_option('data-checkout-link-enabled', $params['id']);
  } else {
	   $checkout_link_enanbled = $params['checkout-link-enabled'];
  }
   ?>
  <? if($checkout_link_enanbled != 'n') :?>
  <? $checkout_page =get_option('data-checkout-page', $params['id']); ?>
  <? if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   
	   $checkout_page_link = content_link($checkout_page).'/view:checkout';
   } else {
	   $checkout_page_link = site_url('checkout');;
	   
   }

   ?>



  <a class="btn btn-warning pull-right" href="<? print $checkout_page_link; ?>">Checkout</a>

  <a href="javascript:;" class="btn pull-right continue-shopping">Continue Shopping</a>

  <? endif ; ?>
  <? else : ?>
  <h4 class="alert">Your cart is empty.</h4>
  <? endif ; ?>
</div>
