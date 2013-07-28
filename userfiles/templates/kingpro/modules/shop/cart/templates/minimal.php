<?php

/*

type: layout

name: Minimal

description: Minimal cart template

*/
  ?>
<style type="text/css">


</style>
<div class="mw-cart-minimal  mw-cart-<?php print $params['id']?> <?php print  $template_css_prefix  ?>">

  <span class="icon-shopping-cart"></span>


    <?php if(isarr($data)) :?>
    <?php
        $total_qty = 0;
        $total_price = 0;
        foreach ($data as $item) {
            $total_qty += $item['qty'];
            $total_price +=  $item['price']* $item['qty'];
        }
      ?>
    <span class="mw-cart-minimal-order-info">Cart (<strong><?php print $total_qty; ?></strong>) <?php print currency_format($total_price); ?></span> |
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
	   $checkout_page_link = mw_site_url('checkout');

   }

   ?>
    <a class="btn btn-mini" href="<?php print $checkout_page_link; ?>"><?php _e("Checkout"); ?></a>
    <?php endif ; ?>
    <?php else : ?>
    <span class="no-items">
    <?php   _e('Your cart is empty') ?>
    </span>
    <?php endif ; ?>

</div>
