<?

$template = option_get('data-template', $params['id']);
$template_css_prefix = '';
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
  $template_css_prefix = no_ext($template);
    $template_file = module_templates($params['type'], $template);

//d();
}


?>
<?php
switch ($template_file):
    case true:
        ?>
<? include($template_file); ?>
<?
        // d();

        if ($template_file != false) {
            break;
        }
        ?>
<?php
    case false:
        ?>

<div class="mw-cart mw-cart-<? print $params['id']?> <? print  $template_css_prefix  ?>">
  <div class="mw-cart-title mw-cart-<? print $params['id']?>">
    <?   _e('My cart') ?>
  </div>
  <?
$cart = array();
$cart['session_id'] = session_id();
$cart['order_completed'] = 'n';
 
 $data = get_cart($cart);
 //d($cart);
 ?>
  <? if(isarr($data)) :?>
  <? foreach ($data as $item) : ?>
  <div class="mw-cart-item mw-cart-item-<? print $item['id'] ?>">
    <div class="mw-cart-item-title"> Title: <? print $item['title'] ?></div>
    price: <? print $item['price'] ?> <br />
    
    qty:
    <input type="text" value="<? print $item['qty'] ?>" onchange="mw.cart.qty('<? print $item['id'] ?>', this.value)" />
    <a href="javascript:mw.cart.remove('<? print $item['id'] ?>');">remove</a> </div>
  <? endforeach; ?>
  
  
  
  <? $checkout_link_enanbled =  option_get('data-checkout-link-enabled', $params['id']) ?>
   
   <? if($checkout_link_enanbled != 'n') :?>
   <? $checkout_page =option_get('data-checkout-page', $params['id']); ?>
   <? if($checkout_page != false and strtolower($checkout_page) != 'default' and intval($checkout_page) > 0){
	   
	   $checkout_page_link = content_link($checkout_page).'/checkout';
   } else {
	   $checkout_page_link = site_url().'checkout';;
	   
   }
   
   ?>
 
 
   <a href="<? print $checkout_page_link; ?>">Checkout</a> 
   
  <? endif ; ?>
  <? else : ?>
  <div class="mw-cart-empty mw-cart-<? print $params['id']?>">
    <?   _e('Your cart is empty') ?>
  </div>
  <? endif ; ?>
</div>
<?php break; ?>
<?php endswitch; ?>
