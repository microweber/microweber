<script type="text/javascript">






function changeShippingCountry(){
$c =  $('#shipping_county').val();
 $.post("<?php print site_url('ajax_helpers/set_session_vars'); ?>", { the_var:'country_name' , the_val: $c, time: "2pm" },
  function(data){
	 showShippingCost();
  });
}



function showShippingCost(){
 $.post("<?php print site_url('ajax_helpers/cart_shippingCalculateToCountryName'); ?>/random:"+Math.random(), { time: "2pm" },
  function(data){
	  
	  data = parseInt(data);
 
	  if(data >= 0){
	  $('#payment_form_shipping').val(data);
	  $('#shipping_cost_information').html(data);
	  
	  
	  
	  
	  } 
  });
}



// prepare the form when the DOM is ready 
$(document).ready(function() { 
 showShippingCost();
 
$.post("<?php print site_url('ajax_helpers/cart_itemsGetQty'); ?>", function(data){
	 
	  data = parseInt(data);
 
	  if(data == 0){
		   $('#checkout-table').hide();
		  $('#empty_cart_msg').show();
	  }
	 
	 
	 
	 
  });
  
  
 
 
 
 
 
 

 //$("#step2").validate();



    var options = { 
        //target:        '#output2',   // target element(s) to be updated with server response 
		url:       '<?php print site_url('ajax_helpers/cart_orderPlace'); ?>',
		type:      'post',
		async: false,
        beforeSubmit:  checkut_form_showRequest,  // pre-submit callback
        success:       checkut_form_showResponse  // post-submit callback 
 
        // other available options:
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000  
    }; 
 
    // bind to the form's submit event 
    $('#submit_payments_form_button').click(function() {
	  $("#step2").isValid();
		//$("#step2").validate();
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit


        var TF = true;
        if($("#step2 .error").exists()){
            TF = false;

        }

       else{
        $("#step2").ajaxSubmit(options);
		 $('#checkout-table').hide();
		  $('#header_cart').hide();


		  $('#paypal_redirect_msg').show();
       }


		 
 
        // !!! Important !!!
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
}); 
 
// pre-submit callback 
function checkut_form_showRequest(formData, jqForm, options) { 

//$("#step2").validate();
  	 var TF = true;
        if($("#step2 .error").exists()){
            TF = false;

        } else {
		//$("#submit_payments_form_button").remove();
		//$("#submit_payments_form_button_redirecting").fadeIn();
		}


        return TF;
} 
 
// post-submit callback 
function checkut_form_showResponse(responseText, statusText)  {

	   $('#step2').submit();

} 
</script>
<style type="text/css">
#step2 {
	width:250px;
}
#step2 .box input {
	width:240px;
	padding: 2px;
	padding-left: 10px;
	border: 0px;
}
#step2 select {
	width: 270px;
	border:none;
	margin-left:14px;
}
label {
	display: block;
	padding: 6px 0 3px 0;
}
.mtf {
	display: block;
	width: 300px;
}
</style>
<?php $sid=$this->session->userdata ( 'session_id' );
$cart_item = array();
$cart_item['sid'] = $sid;
$cart_item['order_completed'] ='n';
$cart_items = CI::model ( 'cart' )->itemsGet($cart_item);
$order_id = "OMN".strtoupper(random_string('alnum', 7)).rand(1,999).rand(1,99);
//var_dump($sid,$cart_items);    
 ?>
<?php if(!empty($cart_items)): ?>
<table width="890" border="0" cellspacing="10" cellpadding="10" align="center" id="checkout-table">
  <tr valign="top">
    <td><div class="richtext">
        <h2>Welcome to our Online Payments Center</h2>
      </div>
      We use secure Payment with PayPal<br />
      <br />
      <table border="0">
        <tr valign="middle">
          <td>We accept:</td>
          <td><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccVisa.gif"    /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccMC.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccAmex.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccDiscover.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/PayPal_mark_37x23.gif"   /></td>
        </tr>
      </table>
      <br />
      PayPal is a convenient, easy-to-use and secure way for individuals and businesses to send and receive money online for goods and services. PayPal allows you to send money securely from your credit card, debit card or bank account without ever revealing those details.<br />
      <br />
      Instructions:
      Click "Proceed to Checkout" and you will be redirected to PayPal's secure payment site. Fill in the appropriate information and you will receive a confirmation email shortly.<br />
      <br />
      After this we will contact you with more information about your order.
      <?php $shipping_price =CI::model ( 'cart' )->cartSumByFields('qty');
$shipping_price = $shipping_price * 9;  
 ?>
      <br />
      <br />
      <div class="richtext">
        <h3>Your order information:</h3>
      </div>
      <ul>
        <li><b>Order unique id:</b><?php print $order_id; ?></li>
        <li title="<?php print $this->session->userdata ( 'shop_currency_code' ) ?>"><b>Total amount:</b><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><?php print CI::model ( 'cart' )->itemsGetTotal($this->session->userdata ( 'cart_promo_code' ), $this->session->userdata ( 'shop_currency' ) ); ?>
          <?php if($this->session->userdata ( 'cart_promo_code' ) != ''): ?>
          (Promo code:<?php print $this->session->userdata ( 'cart_promo_code' ) ?>)
          <?php endif; ?>
        </li>
        <li><b>Shipping and handling fee:</b><?php print $this->session->userdata ( 'shop_currency_sign' ) ?><span id="shipping_cost_information"></span></li>
      </ul>
      <br />
      <br />
      <div class="richtext">
        <h3>Want to change your order:</h3>
      </div>
      <ul>
        <li><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(44); ?>">Go to edit your order</a></li>
        <li><a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">Shop for more items</a></li>
      </ul>
      <br />
      <br />
      See the <a href="<?php print CI::model ( 'content' )->getContentURLById(26); ?>">terms and conditions</a>for more information about the payments.
      <div class="clear"></div>
      <br />
      <br /></td>
    <td><form method="post" action="https://www.paypal.com/cgi-bin/webscr"   id="step2" >
        <input type="hidden" name="business" value="info@omnitom.com">
        <input type="hidden" name="cmd" value="_xclick">
        <input name="autoreply" type="hidden"  value="autoreply_order" />
        <input type="hidden" name="currency_code" value="<?php print $this->session->userdata ( 'shop_currency_code' ) ?>">
        <!--        <input type="hidden" name="image_url" value="http://omnitom.com/userfiles/templates/omnitom/img/logo.jpg">-->
        <input type="hidden" name="no_shipping" value="2">
        <input type="hidden" name="shipping" id="payment_form_shipping" value="<?php print $shipping_price ?>">
        <!--  <input type="hidden" name="shopping_url" value="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">-->
        <input type="hidden" name="amount" value="<?php print CI::model ( 'cart' )->itemsGetTotal($this->session->userdata ( 'cart_promo_code' ), $this->session->userdata ( 'shop_currency' )); ?>">
        <input type="hidden" name="promo_code" value="<?php print ($this->session->userdata ( 'cart_promo_code' )); ?>">
        <input type="hidden" name="item_name" value="Omnitom.com Online Shop - Order ID: <?php print $order_id; ?>">
        <input type="hidden" name="weight_cart" value="<?php print CI::model ( 'cart' )->cartSumByFields('weight'); ?>">
        <input type="hidden" name="weight_unit" value="kgs">
        <!--  <input type="hidden" name="return" value="<?php print $link = CI::model ( 'content' )->getContentURLById(57); ?>/order:<?php print $this->session->userdata ( 'session_id' ); ?>">-->
        <input type="hidden" name="order_id" value="<?php print $order_id; ?>">
        <input type="hidden" name="sid" value="<?php print $this->session->userdata ( 'session_id' ); ?>">
        <div class="mtf">
          <label>First Name<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="first_name" value="" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Last Name<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="last_name" value="" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Email<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="email" value="" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Phone Number</label>
          <div class="box">
            <input type="text" name="night_phone_a"   />
          </div>
        </div>
        <div class="mtf">
          <label>Country<small  class="pink_text">(required)</small></label>
          <div class="box">
            <?php $where_we_ship = CI::model ( 'cart' )->shippingGetActiveContinents(); ?>
            <?php $countries = $this->core_model->geoGetAllCountries($where_we_ship); ?>
            <select name="country" class="required" id="shipping_county"  onchange="changeShippingCountry()">
              <?php $i=0; foreach($countries as $c):   ?>
              <option value="<?php print $c['name'] ?>" <?php if($this->session->userdata ( 'country_name' ) == $c['name']) : ?> selected="selected" <?php endif;?> ><?php print $c['name'] ?></option>
              <?php $i++; endforeach; ?>
            </select>
            <!--  <input type="text" name="country"   value="<?php print USER_COUNTRY_NAME ?>"  class="required">-->
          </div>
        </div>
        <div class="mtf">
          <label>City<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="city"   class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Address<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="address1"   class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Address 2</label>
          <div class="box">
            <input type="text" name="address2">
          </div>
        </div>
        <div class="mtf">
          <label>State/Province</label>
          <div class="box">
            <input name="state" type="text" />
          </div>
        </div>
        <div class="mtf">
          <label>Zip/Postal Code</label>
          <div class="box">
            <input name="zip" type="text" />
          </div>
        </div>
        <div style="height: 15px"></div>
        <div class="clear"></div>
        <small style="width:300px; display:block;" class="pink_text">*Note: by clicking the continue to payment button your order will be placed and your shopping bag will be emptied</small><br />
        <div class="clear"></div>
        <a class="big_btn right" href="javascript:void(0);" id="submit_payments_form_button"><span>Continue to  payment*</span></a><span id="submit_payments_form_button_redirecting" style="display:none"><br />
        Redirecting to Paypal...</span>
        
        <!--  onclick="$('#step2').submit()"-->
      </form></td>
  </tr>
</table>
<?php else: ?>


Your bag is empty. Please go to the <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">shop</a> and add some items. <br />
<br />
<br />
<?php endif; ?>
<div id="empty_cart_msg" style="display:none"> Your bag is empty. Please go to the <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">shop</a> and add some items. <br />
</div>



<div id="paypal_redirect_msg" style="display:none"> You are being redirected to PayPal. Please complete your payment there.<br />
</div>