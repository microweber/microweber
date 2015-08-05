<?php

/*

type: layout

name: Default

description: Default cart template

*/

?>

<script>

complete_order = window.complete_order || function(){

  mw.cart.checkout('#checkout_form_<?php print $params['id'] ?>', function(){
	    
      mw.$('.mw-checkout-form').hide();
	  mw.$('.mw-checkout-responce').html(this);


  });

}

</script>
        <?php if($requires_registration and is_logged() == false): ?>
<module type="users/register" />
<?php else: ?>
 
<?php if ($payment_success == false): ?>
    
    <form class="mw-checkout-form" id="checkout_form_<?php print $params['id'] ?>" method="post"
          action="<?php print api_link('checkout') ?>">

        <?php $cart_show_enanbled = get_option('data-show-cart', $params['id']); ?>
        <?php if ($cart_show_enanbled != 'n'): ?>
            <module type="shop/cart" template="big" id="cart_checkout_<?php print $params['id'] ?>"
                    data-checkout-link-enabled="n"/>
        <?php endif;?>
        
 
        <div class="mw-ui-row shipping-and-payment">
            <div class="mw-ui-col" style="width: 33%;">
                <div class="mw-ui-col-container">
                    <div class="well">
                        <?php $user = get_user(); ?>
                        <h2 style="margin-top:0 " class="edit nodrop" field="checkout_personal_inforomation_title"
                            rel="global" rel_id="<?php print $params['id'] ?>"><?php _e("Personal Information"); ?></h2>
                        <hr/>
                        <label>
                            <?php _e("First Name"); ?>
                        </label>
                        <input name="first_name" class="field-full form-control" type="text"
                               value="<?php if (isset($user['first_name'])) {
                                   print $user['first_name'];
                               } ?>"/>
                        <label>
                            <?php _e("Last Name"); ?>
                        </label>
                        <input name="last_name" class="field-full form-control" type="text"
                               value="<?php if (isset($user['last_name'])) {
                                   print $user['last_name'];
                               } ?>"/>
                        <label>
                            <?php _e("Email"); ?>
                        </label>
                        <input name="email" class="field-full form-control" type="text"
                               value="<?php if (isset($user['email'])) {
                                   print $user['email'];
                               } ?>"/>
                        <label>
                            <?php _e("Phone"); ?>
                        </label>
                        <input name="phone" class="field-full form-control" type="text"
                               value="<?php if (isset($user['phone'])) {
                                   print $user['phone'];
                               } ?>"/>
                    </div>
                </div>
            </div>
            <?php if ($cart_show_shipping != 'n'): ?>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <module type="shop/shipping"/>
                    </div>
                </div>
            <?php endif;?>
            <?php if ($cart_show_payments != 'n'): ?>

                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <module type="shop/payments"/>
                    </div>
                </div>
            <?php endif;?>

        </div>
        <div class="alert hide"></div>
        <div class="mw-cart-action-holder">
        
        <?php
$tems = get_option('shop_require_terms', 'website') == 1;	
 

 ?>
       <?php if($tems): ?>
    <script>



$( document ).ready(function() {
   
  
   
       $('#i_agree_with_terms_row').click(function() {
		var el = $('#i_agree_with_terms');
		if(el.is(':checked')) {
			 $('#complete_order_button').removeAttr('disabled');
		} else {
		 	 $('#complete_order_button').attr('disabled','disabled');

		}
	});
   
   
});


    
    </script>
    
    
    <div class="mw-ui-row" id="i_agree_with_terms_row">
    <label class="mw-ui-check">
      <input type="checkbox" name="terms" id="i_agree_with_terms" value="1" autocomplete="off" />
      <span class="edit" field="i_agree_with_terms_text" rel="shop_checkout">
      I agree with
      <a href="<?php print site_url('tems') ?>" target="_blank">
       terms and conditions
      </a>
      
      </span>
      </label>
      </div>
      <br>
    <?php endif; ?>
        


            <?php $shop_page = get_content('is_shop=0');      ?>

             <?php if (is_array($shop_page)): ?>
                <a href="<?php print page_link($shop_page[0]['id']); ?>"
                   class="btn btn-default"
                   type="button"><?php _e("Continue Shopping"); ?></a>
            <?php endif; ?>

                <button class="btn btn-warning mw-checkout-btn"
                        onclick="complete_order();"
                        type="button" id="complete_order_button" <?php if($tems): ?> disabled="disabled"   <?php endif; ?> >
    <?php _e("Complete order"); ?>
    </button>

        </div>
    </form>
    <div class="mw-checkout-responce"></div>
<?php else: ?>
    <h2><?php _e("Your payment was successfull."); ?></h2>
<?php endif; ?>
    <?php endif;?>  