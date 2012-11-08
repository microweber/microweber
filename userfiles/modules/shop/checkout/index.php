<?

 
?>

<div id="checkout_form_<? print $params['id'] ?>" > 
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
  <? $payment_options =  payment_options();  ?>
  <? if(isarr($payment_options)) :?>
  <h3>Credit card info</h3>
  <select name="payment_gw">
    <? foreach ($payment_options as $item) : ?>
    <option value="<? print  $item['option_key']; ?>"><? print  $item['title']; ?></option>
    <? endforeach; ?>
  </select>
  first_name
  <input name="cc_first_name"  type="text" value="" />
  last_name
  <input name="cc_last_name"  type="text" value="" />
  number
  <input name="cc_number"  type="text" value="" />
  month
  <input name="cc_month"  type="text" value="" />
  year
  <input name="cc_year"  type="text" value="" />
  verification_code
  <input name="cc_verification_value"  type="text" value="" />
  <br />
  <? endif;?>
  <button onclick="mw.cart.checkout('#checkout_form_<? print $params['id'] ?>');">Checkout</button>
</div>
