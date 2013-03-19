<?php

/*

type: layout

name: Small

description: Small cart template

*/
  ?>
 <div class="mw-cart mw-cart-<? print $params['id']?> <? print  $template_css_prefix  ?>">
  
  <? if(isarr($data)) :?>

  Cart (<?php print sizeof($data); ?>)  $998.99

    <tbody>
      <? foreach ($data as $item) : ?>

        <td class="mw-cart-table-product"><? print $item['title'] ?>
          <? 	if(isset($item['custom_fields'])): ?>
          <? print $item['custom_fields'] ?>
          <?  endif ?></td>
        <td><input type="text" class="input-mini" value="<? print $item['qty'] ?>" onchange="mw.cart.qty('<? print $item['id'] ?>', this.value)" /></td>
        <?php /*<td><? print currency_format($item['price']); ?></td>*/ ?>
        <td class="mw-cart-table-price"><? print currency_format($item['price']* $item['qty']); ?></td>
        <td><a title="<?php _e("Remove"); ?>" class="icon-trash" href="javascript:mw.cart.remove('<? print $item['id'] ?>');"></a></td>

      <? endforeach; ?>

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
	   $checkout_page_link = site_url().'?view=checkout';;

   }

   ?>
  <a class="btn btn-warning right" href="<? print $checkout_page_link; ?>">Checkout</a>
  <? endif ; ?>
  <? else : ?>
  <div class="edit mw-cart-empty mw-cart-<? print $params['id']?>"   rel="module" field="cart-is-empty">
    <?   _e('Your cart is empty') ?>
  </div>
  <? endif ; ?>
</div>