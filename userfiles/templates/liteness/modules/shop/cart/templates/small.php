<?php

/*

type: layout

name: Small

description: Small cart template

*/
  ?>


<script>
    mw.moduleCSS("<?php print $config['url_to_module'] ?>templates/templates.css");

</script>

<div class="cart-small <?php if(is_array($data)==false){print "mw-cart-small-no-items";} ?>  mw-cart-<?php print $params['id']?> <?php print  $template_css_prefix  ?>">
  <?php if(is_array($data)) :?>

<a class="mw-ui-row-nodrop cart-small-checkout-link" href="<?php print checkout_url(); ?>">
    <span class="mw-ui-col icon-shopping-cart-holder"><span class="mw-cart-small-icon-shopping-cart"></span></span>


    <?php
        $total_qty = 0;
        $total_price = 0;
        foreach ($data as $item) {
            $total_qty += $item['qty'];
            $total_price +=  $item['price']* $item['qty'];
        }
      ?>


    <span class="mw-ui-col"><span class="mw-cart-small-order-info"><strong><?php print $total_qty; ?></strong><span class="mw-cart-small-order-info-total">Total: &nbsp;<?php print currency_format($total_price); ?></span></span></span>
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

    <?php endif ; ?>

    </a>
    <?php else : ?>
    <div class="mw-ui-row-nodrop">
    <span class="mw-ui-col icon-shopping-cart-holder"><span class="mw-cart-small-icon-shopping-cart"></span></span>
    <div class="mw-ui-col">
    <span class="mw-cart-small-order-info"><span class="mw-cart-small-order-info-total no-items"><?php   _e('Your cart is empty') ?></span></span></div>
     </div>
    <?php endif ; ?>

</div>
