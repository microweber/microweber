<?php

/*

type: layout

name: Sidebar cart

description: Sidebar cart template

*/

?>

<div class="mw-cart mw-cart-<? print $params['id']?> <? print  $template_css_prefix  ?>">
  <div class="mw-cart-title mw-cart-<? print $params['id']?>">
    <h2 style="margin-top: 0;" class="edit" rel="<? print $params['id']?>" field="cart_title">
      <?   _e('My cart') ?>
    </h2>
  </div>
  <? if(isarr($data)) :?>
  <table class="table table-bordered table-striped mw-cart-table mw-cart-table-medium">
    <thead>
      <tr>
        <th class="mw-cart-table-product">Product Name</th>
        <th>QTY</th>
        <th>Total</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($data as $item) : ?>
      <tr class="mw-cart-item mw-cart-item-<? print $item['id'] ?>">
        <td class="mw-cart-table-product"><? print $item['title'] ?>
          <? 	if(isset($item['custom_fields'])): ?>
          <? print $item['custom_fields'] ?>
          <?  endif ?></td>
        <td><input type="number" class="input-mini" value="<? print $item['qty'] ?>" onchange="mw.cart.qty('<? print $item['id'] ?>', this.value);" /></td>
        <?php /*<td><? print currency_format($item['price']); ?></td>*/ ?>

        <td class="mw-cart-table-price"><? print currency_format($item['price']* $item['qty']); ?></td>
        <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<? print $item['id'] ?>');"></a></td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>




  
  
  

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
  <a class="btn btn-warning right" href="<? print $checkout_page_link; ?>">Checkout</a>
  <? endif ; ?>
  <? else : ?>

  <? endif ; ?>
</div>
