<?php
$payment_success = false;
if(isset($_SESSION['mw_payment_success'])){
mw('user')->session_del('mw_payment_success');
$payment_success = true;
}
 
?>

<script type="text/javascript">
    mw.require("tools.js");
	    mw.require("shop.js");

  </script>


<script type="text/javascript">

function checkout_callback(data,selector){
		   var z = typeof(data);
		   if(z != 'object'){
			  var dataObj;
        	  try{
        	    dataObj =  $.parseJSON(data);
        	    data = dataObj;
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
  <?php $cart_show_payments =  get_option('data-show-payments', $params['id']); ?>

  <?php $cart_show_shipping =  get_option('data-show-shipping', $params['id']); ?>

<?php if($payment_success == false): ?>
  <div class="vSpace"></div>
<form class="mw-checkout-form"  id="checkout_form_<?php print $params['id'] ?>" method="post" action="<?php print api_link('checkout') ?>" >

  <?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
  <?php if($cart_show_enanbled != 'n'): ?>
  <module type="shop/cart" template="big" id="cart_checkout_<?php print $params['id']?>" data-checkout-link-enabled="n" />
  <?php endif ;?>
    <div class="mw-ui-row">
      <div class="mw-ui-col" style="width: 33%;">
        <div class="mw-ui-col-container">
            <div class="well">
              <?php $user = get_user(); ?>
              <h2 style="margin-top:0 " class="edit nodrop" field="checkout_personal_inforomation_title" rel="global" rel_id="<?php print $params['id']?>"><?php _e("Personal Information"); ?></h2>
              <hr />
              <label>
                <?php _e("First Name"); ?>
              </label>
              <input name="first_name" class="field-full form-control"  type="text" value="<?php if(isset($user['first_name'])) { print $user['first_name']; } ?>" />
              <label>
                <?php _e("Last Name"); ?>
              </label>
              <input name="last_name" class="field-full form-control"  type="text" value="<?php if(isset($user['last_name'])) { print $user['last_name']; } ?>" />
              <label>
                <?php _e("Email"); ?>
              </label>
              <input name="email" class="field-full form-control" type="text" value="<?php if(isset($user['email'])) { print $user['email']; } ?>" />
              <label>
                <?php _e("Phone"); ?>
              </label>
              <input name="phone" class="field-full form-control"  type="text" value="<?php if(isset($user['phone'])) { print $user['phone']; } ?>" />
            </div>
        </div>
      </div>
	  <?php if($cart_show_shipping != 'n'): ?>
      <div class="mw-ui-col"><div class="mw-ui-col-container"><module type="shop/shipping" /></div></div>
	   <?php endif ;?>
	   	  <?php if($cart_show_payments != 'n'): ?>

      <div class="mw-ui-col"><div class="mw-ui-col-container"><module type="shop/payments" /></div></div>
	  	   <?php endif ;?>

    </div>
    <div class="alert hide"></div>
    <div class="mw-cart-action-holder">
      <hr />
      <?php $shop_page = get_content('is_shop=y');      ?>
      <button class="btn btn-warning pull-right mw-checkout-btn" onclick="mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>');" type="button"><?php _e("Complete order"); ?></button>
      <?php if(is_array($shop_page)): ?>
      <a href="<?php print page_link($shop_page[0]['id']); ?>" class="btn btn-default pull-left" type="button"><?php _e("Continue Shopping"); ?></a>
      <?php endif; ?>
      <div class="clear"></div>
    </div>
</form>
<div class="mw-checkout-responce"></div>
<?php else: ?>
<h2><?php _e("Your payment was successfull."); ?></h2>
<?php endif; ?>
