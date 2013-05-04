<?php
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
			   $('.mw-checkout-responce').html(data);
			  
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
mw.$(".mw-checkout-form .well").css("minHeight",__max);

});


</script>
<?php if($payment_success == false): ?>

<form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post" action="<?php print api_url('checkout') ?>" >
  <script type="text/javascript">
    mw.require("shop.js");
  </script>
  <?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
  <?php
  
   if($cart_show_enanbled != 'n'): ?>
  <module type="shop/cart" template="big" id="cart_checkout_<?php print $params['id']?>" data-checkout-link-enabled="n" />
  <?php endif ;?>
  <div style="margin-left: 0">
    <div class="row-fluid mw-cart-data-holder">
      <div class="span4">
        <div class="well">
          <?php $user = get_user(); ?>
          <h2 style="margin-top:0 ">Personal Information</h2>
          <hr />
          <label>
            <?php _e("First Name"); ?>
          </label>
          <input name="first_name" class="field-full"  type="text" value="<?php if(isset($user['first_name'])) { print $user['first_name']; } ?>" />
          <label>
            <?php _e("Last Name"); ?>
          </label>
          <input name="last_name" class="field-full"  type="text" value="<?php if(isset($user['last_name'])) { print $user['last_name']; } ?>" />
          <label>
            <?php _e("Email"); ?>
          </label>
          <input name="email" class="field-full" type="text" value="<?php if(isset($user['email'])) { print $user['email']; } ?>" />
          <label>
            <?php _e("Phone"); ?>
          </label>
          <input name="phone" class="field-full"  type="text" value="<?php if(isset($user['phone'])) { print $user['phone']; } ?>" />
        </div>
      </div>
      <module type="shop/shipping" class="span4" />
      <module type="shop/payments" class="span4" />
    </div>
    <div class="alert hide"></div>
    <div class="mw-cart-action-holder">
      <hr />
      <?php $shop_page = get_content('is_shop=y');      ?>
      <button class="btn btn-warning pull-right mw-checkout-btn" onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');" type="button">Complete order</button>
      <?php if(isarr($shop_page)): ?>
      <a href="<?php print page_link($shop_page[0]['id']); ?>" class="btn pull-left" type="button">Continue Shopping</a>
      <?php endif; ?>
      <div class="clear"></div>
    </div>
  </div>
</form>
<div class="mw-checkout-responce"></div>
<?php else: ?>
<h2>Your payment was successfull.</h2>
<?php endif; ?>
