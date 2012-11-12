<?

 
?>

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
  country
  <select name="country" >
    <? $countries = countries_list() ; ?>
    <? foreach( $countries as $item) :?>
    <option value="<? print  $item?>"><? print  $item?></option>
    <? endforeach; ?>
  </select>
  <br />
  city
  <input name="city"  type="text" value="" />
  <br />
  state
  <input name="state"  type="text" value="" />
  <br />
  zip
  <input name="zip"  type="text" value="" />
  <br />
  address
  <input name="address"  type="text" value="" />
  <br />
  phone
  <input name="phone"  type="text" value="" />
  <br />
  
  
  
  
<module type="shop/payments" />







  <button onclick="mw.cart.checkout('#checkout_form_<? print $params['id'] ?>');" type="button">Checkout</button>
  <button type="submit">submit</button>
</form>
