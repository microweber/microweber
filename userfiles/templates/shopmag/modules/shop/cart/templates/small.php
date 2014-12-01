<?php

/*

type: layout

name: Small

description: Small cart template

*/
  ?>



    <div class="mw-cart-shopmag-small">
    <?php if(is_array($data)) :?>



    <?php
        $total_qty = 0;
        $total_price = 0;
        foreach ($data as $item) {
            $total_qty += $item['qty'];
            $total_price +=  $item['price']* $item['qty'];
        }
      ?>

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
	   $checkout_page_link = checkout_url();

   }

   ?>
        <a href="<?php print $checkout_page_link; ?>" class="mw-cart-shopmag-small-link-bag">
            <span class="sm-icon-bag2"></span>
            <span class="sm-icon-bag2 shop-bag-cart-hover"></span>
            <span class="mw-cart-small-order-info">
               <?php print $total_qty; ?>
            </span>

        </a>




    <?php endif ; ?>


    <?php else : ?>
    <span  class="mw-cart-shopmag-small-link-bag">
            <span class="sm-icon-bag2"></span>
            <span class="sm-icon-bag2 shop-bag-cart-hover"></span>
             <span class="mw-cart-small-order-info">
               0
            </span>
            
        </span>
    <?php endif ; ?>

  </div>