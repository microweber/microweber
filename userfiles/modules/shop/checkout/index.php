<?
$payment_success = false;
if(isset($_SESSION['mw_payment_success'])){
session_del('mw_payment_success');
$payment_success = true;
}
 
?>
<script type="text/javascript">

function checkout_callback(data,selector){
	//




 var z = typeof(data);
						 if(z != 'object'){
							
							
							var o;
									try{ o =  $.parseJSON(data);
									
									
									data = o;
									
									 }
									catch(e){ 
									 
										$('.mw-checkout-responce').append(data);
									 
									}  
										
									 
									
									

						 } else {
 						 } 





	$('.mw-checkout-responce').removeClass('alert-error');
	$('.mw-checkout-responce').removeClass('alert-success');

	if(data.success !== undefined){
		
		$('.mw-checkout-responce').html(data.success);
		$('.mw-checkout-responce').addClass('alert alert-success');
	 	$('.mw-checkout-form').fadeOut();
		
		
	} else if(data.error !== undefined){
		$('.mw-checkout-responce').empty().append(data.error);
		$('.mw-checkout-responce').addClass('alert alert-error');

		//alert(data.error);
	} else {
		$('.mw-checkout-responce').append(data);

	}
}


$(document).ready(function(){
__max = 0;
mw.$(".mw-checkout-form .well").each(function(){
    var h = $(this).height();
    if(h>__max){
      __max = h;
    }
});
mw.$(".mw-checkout-form .well").height(__max)

});


</script>
<? if($payment_success == false): ?>

<form class="mw-checkout-form" id="checkout_form_<? print $params['id'] ?>" method="post" action="<? print api_url('checkout') ?>" >
  <script type="text/javascript">
mw.require("<?php print( module_url('shop')); ?>shop.js");
</script> 
  Checkout module
  <?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
  <? if($cart_show_enanbled == 'y'): ?>
  <module type="shop/cart" id="cart_checkout_<? print $params['id']?>" data-checkout-link-enabled="n" />
  <? endif ;?>
  <div style="margin-left: 0">
    <div class="row-fluid">
      <div class="span4">
        <div class="well">
          <h2 style="margin-top:0 ">Personal Information</h2>
          <hr />
          <label>
            <?php _e("First Name"); ?>
          </label>
          <input name="first_name"  type="text" value="" />
          <label>
            <?php _e("Last Name"); ?>
          </label>
          <input name="last_name"  type="text" value="" />
          <label>
            <?php _e("Email"); ?>
          </label>
          <input name="email"  type="text" value="" />
          <label>
            <?php _e("Phone"); ?>
          </label>
          <input name="phone"  type="text" value="" />
        </div>
      </div>
      <module type="shop/shipping" />
      <module type="shop/payments" />
    </div>
    <hr />
    <? $shop_page = get_content('is_shop=y');      ?>
    <button class="btn btn-warning right" onclick="mw.cart.checkout('#checkout_form_<? print $params['id'] ?>');" type="button">Complete order</button>
    <?php if(isarr($shop_page)): ?>
    <a href="<? print page_link($shop_page[0]['id']); ?>" class="btn right" type="button" style="margin-right: 10px;">Continue Shopping</a>
    <?php endif; ?>
    <div class="clear"></div>
  </div>
  
  
</form>
  <div class="mw-checkout-responce"></div>

<? else: ?>
<h2>Your payment was successfull.</h2>
<? endif; ?>
