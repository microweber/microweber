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
  <div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_names_text', $params['module_id'])) ? option_get('order_form_names_text', $params['module_id']) : "Names";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="names" value="<? print user_name(user_id(), 'full'); ?>"  />
    </span> </div>
  <div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_email_text', $params['module_id'])) ? option_get('order_form_email_text', $params['module_id']) : "E-mail";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required-email" name="email" value="<? print $form_values['email']; ?>"   />
    </span> </div>
  <div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_address_text', $params['module_id'])) ? option_get('order_form_address_text', $params['module_id']) : "Address";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="address"  value="<? print $form_values['custom_fields']['address']; ?>"   />
    </span> </div>
  <div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_city_text', $params['module_id'])) ? option_get('order_form_city_text', $params['module_id']) : "City";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="city" value="<? print $form_values['custom_fields']['city']; ?>"    />
    </span> </div>
  <!--<div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_post_code_text', $params['module_id'])) ? option_get('order_form_post_code_text', $params['module_id']) : "Post code";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="zip"   value="<? print $form_values['custom_fields']['zip']; ?>"   />
    </span> </div>-->
  <div class="order_form_field_block">
    <label class="clabel">
      <?php  print (option_get('order_form_phone_text', $params['module_id'])) ? option_get('order_form_phone_text', $params['module_id']) : "Phone";  ?>
      : *</label>
    <span class="order_form_field">
    <input type="text" class="required" name="phone" value="<? print $form_values['custom_fields']['phone']; ?>"  />
    </span> </div>
  <div class="order_form_field_block">
    <input type="checkbox" class="confirm_terms required" />
    <span class="terms">
    <?php  $terms_link =  (option_get('order_terms_link', $params['module_id'])) ? option_get('order_terms_link', $params['module_id']) : "#";  ?>
    <a href="<? print $terms_link ;?>" target="_blank" class="cart_terms_link">
    <?php  print (option_get('order_terms_text', $params['module_id'])) ? option_get('order_terms_text', $params['module_id']) : "I agree with the terms and conditions";  ?>
    </a> </span> </div>
</form>
<br />
<span class="cart_hide_on_complete"><strong>*</strong> -
<?php  print (option_get('order_form_mandatory_fields_text', $params['module_id'])) ? option_get('order_form_mandatory_fields_text', $params['module_id']) : "mandatory fields";  ?>
</span><br />
<div class="c" style="padding-bottom: 15px;">&nbsp;</div>
<a href="#" class="orderbtn cart_hide_on_complete" onclick="if($('.checkout_form').isValid()){mw.cart.complete_order('.checkout_form', '.cart_hide_on_complete', '.cart_show_on_complete')}">
<?php  print (option_get('order_form_btn_text', $params['module_id'])) ? option_get('order_form_btn_text', $params['module_id']) : "Order now";  ?>
</a>
<? endif ?>
