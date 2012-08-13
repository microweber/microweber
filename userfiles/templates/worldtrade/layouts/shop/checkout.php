<script type="text/javascript">


function payment_proceed_paypal(){
		
	$v =  $(".payment_method:checked").val();
	if($v == "paypal"){
		 $('#paypal_form').submit();
	}
	$(".cart_show_on_complete").fadeIn();
	
	
}

function payment_proceed(){
	
	$v =  $(".payment_method:checked").val();
//alert($v );


if($('.checkout_form').isValid()){
	
	
	
	mw.cart.complete_order('.checkout_form', '.cart_hide_on_complete', payment_proceed_paypal)
	
	
	
	}
}
 

  function payment_method_change($method){
	  if($method == 'default'){
		
		//$('#paypal_form').fadeOut();
		
		
	  }
	  
	  
	  
	  if($method == 'paypal'){
		
		
		//$('#paypal_form').fadeIn();
		
	  }
	  
	  
	  
  }

    </script>

<div class="cart_hide_on_complete">
  <h1 class="font_size_18 pink_color cart_hide_on_complete">Завършване на поръчка</h1>
  <br />
  <br />
  <br />
  <p> Имате <a href="<? print page_link($shop_page['id']); ?>/view:cart"><strong><span class="items cart_items_qty"><? print get_items_qty() ; ?></span> артикула</strong></a> във вашата кошница на обща стойност <b><span class="cart_items_total"><? print get_cart_total()  ?></span> <?php print option_get('shop_currency_sign') ; ?></b> <br />
    <? $shipping_free = option_get ( 'shipping_free' ); ?>
    <? $to_return =  get_cart_total();  ?>
    <? if ($shipping_free != false) {
				if (intval ( $to_return ) >= intval ( $shipping_free )) {
					//$ship_price = 
				 print "<br/> <p>За поръчка над {$shipping_free} лв. доставката е безплатна.</p>";
				} else {
 				
				}
			
			} 
			
			?>
    <br />
  </p>
</div>
<br />
<h1 class="font_size_18 pink_color cart_show_on_complete" style="display:none">Вашата поръчка е завършена.</h1>
<?

$cart_items = get_cart_items();
$order_id = "ORD". date("ymdHis") . rand ();
//var_dump($params);

if($params['module_id'] == false){
	
//$params['module_id'] = 'default';	
}

 ?>
<? if(empty($cart_items)): ?>
<span class="cartico">
<?php  print (option_get('empty_cart_text', $params['module_id'])) ? option_get('empty_cart_text', $params['module_id']) : "Your cart is empty";  ?>
</span>
<? else: ?>
<h2 class="title">
  <?php  print (option_get('title', $params['module_id'])) ? option_get('title', $params['module_id']) : "Please complete your order";  ?>
</h2>
<div class="cart_show_on_complete" style="display:none">
  <h1>
    <?php  print (option_get('order_complete_text', $params['module_id'])) ? option_get('order_complete_text', $params['module_id']) : "Your order is placed. We will contact you with more info.";  ?>
  </h1>
</div>
<div id="cart_checkout_placeholder"></div>
<script type="text/javascript">

 

    $(document).ready(function(){

         
    });

    </script>
<?  if(user_id()  != false):  ?>
<? $form_values = get_user(user_id()); ?>
<? endif ;?>
<form method="post" action="#" class="checkout_form cart_hide_on_complete">
  <table border="0" cellspacing="10" cellpadding="10" width="750">
    <tr>
      <td><label class="clabel">
          <?php  print (option_get('order_form_names_text', $params['module_id'])) ? option_get('order_form_names_text', $params['module_id']) : "Names";  ?>
          : *</label></td>
      <td><span class="order_form_field">
        <input type="text" class="required" name="names" value="<? print user_name(user_id(), 'full'); ?>"  />
        </span></td>
    </tr>
    <tr>
      <td><label class="clabel">
          <?php  print (option_get('order_form_email_text', $params['module_id'])) ? option_get('order_form_email_text', $params['module_id']) : "E-mail";  ?>
          : *</label></td>
      <td><span class="order_form_field">
        <input type="text" class="required-email" name="email" value="<? print $form_values['email']; ?>"   />
        </span></td>
    </tr>
    <tr>
      <td><label class="clabel">
          <?php  print (option_get('order_form_address_text', $params['module_id'])) ? option_get('order_form_address_text', $params['module_id']) : "Address";  ?>
          : *</label></td>
      <td><span class="order_form_field">
        <input type="text" class="required" name="address"  value="<? print $form_values['custom_fields']['address']; ?>"   />
        </span></td>
    </tr>
    <tr>
      <td><label class="clabel">
          <?php  print (option_get('order_form_city_text', $params['module_id'])) ? option_get('order_form_city_text', $params['module_id']) : "City";  ?>
          : *</label></td>
      <td><span class="order_form_field">
        <input type="text" class="required" name="city" value="<? print $form_values['custom_fields']['city']; ?>"    />
        </span></td>
    </tr>
    <tr>
      <td><label class="clabel">
          <?php  print (option_get('order_form_phone_text', $params['module_id'])) ? option_get('order_form_phone_text', $params['module_id']) : "Phone";  ?>
          : *</label></td>
      <td><span class="order_form_field">
        <input type="text" class="required" name="phone" value="<? print $form_values['custom_fields']['phone']; ?>"  />
        </span></td>
    </tr>
    <tr>
      <td><label class="clabel"> Условия за поръчка и доставка 
          : *</label></td>
      <td><input type="checkbox" class="confirm_terms required" />
        <span class="terms">
        <?php  $terms_link =  (option_get('order_terms_link', $params['module_id'])) ? option_get('order_terms_link', $params['module_id']) : "#";  ?>
        <a href="<? print $terms_link ;?>" target="_blank" class="cart_terms_link">
        <?php  print (option_get('order_terms_text', $params['module_id'])) ? option_get('order_terms_text', $params['module_id']) : "I agree with the terms and conditions";  ?>
        </a> </span></td>
    </tr>
    <tr>
      <td><label class="clabel"> Начин на плащане
          : *</label></td>
      <td><span class="order_form_field">
        <label>
          <input name="payment_method" type="radio" value="default" class="payment_method" checked="checked" onclick="payment_method_change(this.value)" />
          Плащане при доставка </label>
        <br />
        <label>
          <input name="payment_method" type="radio" value="paypal" class="payment_method" onclick="payment_method_change(this.value)" />
          Плащане с Paypal </label>
        </span></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>
  </table>
  <!--<div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_post_code_text', $params['module_id'])) ? option_get('order_form_post_code_text', $params['module_id']) : "Post code";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="zip"   value="<? print $form_values['custom_fields']['zip']; ?>"   />
    </span> </div>-->
</form>
<br />
<span class="cart_hide_on_complete"><strong>*</strong> -
<?php  print (option_get('order_form_mandatory_fields_text', $params['module_id'])) ? option_get('order_form_mandatory_fields_text', $params['module_id']) : "mandatory fields";  ?>
</span><br />
<div class="c" style="padding-bottom: 15px;">&nbsp;</div>
<a href="#" class="orderbtn cart_hide_on_complete" onclick="payment_proceed()">
<?php  print (option_get('order_form_btn_text', $params['module_id'])) ? option_get('order_form_btn_text', $params['module_id']) : "Order now";  ?>
</a>
<? endif ?>
<br />
<br />
<br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" style="display:none;">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="info@worldtrade.bg"> 
  <input type="hidden" name="lc" value="BG">
  <input type="hidden" name="item_name" value="<? print $order_id ?>">
  <input type="hidden" name="item_number" value="1">
  <input type="hidden" name="amount" value="<? print number_format(get_cart_total()/1.95 , 2); ?>">
  <input type="hidden" name="currency_code" value="EUR">
  <input type="hidden" name="button_subtype" value="services">
  <input type="hidden" name="no_note" value="0">
  <input type="hidden" name="cn" value="Add special instructions to the seller">
  <input type="hidden" name="no_shipping" value="1">
  <input type="hidden" name="rm" value="1">
  <input type="hidden" name="return" value="http://worldtrade.bg/products/view:paid">
 
  <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<div class="clener"></div>
