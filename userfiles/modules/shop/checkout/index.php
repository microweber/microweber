<?
$payment_success = false;
if(isset($_SESSION['mw_payment_success'])){
session_del('mw_payment_success');	
$payment_success = true;
}
 
?>
<? if($payment_success == false): ?>

<form id="checkout_form_<? print $params['id'] ?>" method="post" action="<? print api_url('checkout') ?>" >
  <script type="text/javascript">
mw.require("<?php print( module_url('shop')); ?>shop.js");
</script> 
  Checkout module
  <?php $cart_show_enanbled =  option_get('data-show-cart', $params['id']); ?>
  <? if($cart_show_enanbled == 'y'): ?>
  <module type="shop/cart" id="cart_checkout_<? print $params['id']?>" data-checkout-link-enabled="n" />
  <? endif ;?>
  first_name
  <input name="first_name"  type="text" value="" />
  <br />
  last_name
  <input name="last_name"  type="text" value="" />
  <br />
  email
  <input name="email"  type="text" value="" />
  <br />
 
   <module type="shop/shipping" />
  
  
  <module type="shop/payments" />
  <button onclick="mw.cart.checkout('#checkout_form_<? print $params['id'] ?>');" type="button">Checkout</button>
  <button type="submit">submit</button>
</form>
<? else: ?>
<h2>Your payment was successfull.</h2>
<? endif; ?>
