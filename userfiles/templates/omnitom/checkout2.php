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
	   $('.shipping_cost_information').html(data);
	  

	  

	  

	  

	  

	  } 

  });

}









$(document).ready(function() {
//update_the_cart_qty_in_header();

$.post("<? print site_url(); ?>ajax_helpers/cart_itemsGetQty", function(data){
 $numcart = data;
$numcart = parseInt($numcart)
//alert($numcart);
if($numcart == 0){
	//alert($numcart);
	$("#checkout_page").hide();
	$("#empty_cart_msg").show();
}


  });




var opt = {

 // beforeSubmit:  showRequest,  // pre-submit callback

  //success:       showResponse,

  url:'<?php print site_url('ajax_helpers/set_session_vars_by_post'); ?>'

}







$("#step2 input[type='text'], #step2 select").change(function(){
    $(this).parents("form").ajaxSubmit(opt);
});

$("#step2").ajaxSubmit(opt);













 showShippingCost();

 

$.post("<?php print site_url('ajax_helpers/cart_itemsGetQty'); ?>", function(data){

	 

	  data = parseInt(data);

 

	  if(data == 0){

		   $('#checkout-table').hide();

		  $('#empty_cart_msg').show();

	  }

	 

	 

	 

	 

  });

  

  

 

 

 

 

 

 



 //$("#step2").validate();




   
    // bind to the form's submit event 

    $('#submit_payments_form_button').click(function() {
													 
		var options = {

        //target:        '#output2',   // target element(s) to be updated with server response 

		url:       '<?php print site_url('ajax_helpers/cart_check_cc_borica'); ?>',

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
													 
	  $("#step2").isValid();
        var TF = true;

        if($("#step2 .error").exists()){
            TF = false;
             Modal.box("<h2 style='padding:20px;text-align:center;'>Please fill out the required fields.</h2>", 400, 200);
            Modal.xoverlay();
            $("#modalbox").css("background", "white");
        }
       else{
		   
		   
		   
		   
		   
		 
		   
		   
		   
		   
		   
		   
		   
		   
        $("#step2").ajaxSubmit(options);
		// $('#checkout-table').hide();
		 // $('#header_cart').hide();
		//  $('#paypal_redirect_msg').show();
       }
        return false; 

    }); 
	
	
	
	
	 
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}); 


function checkout_paypal(){
	//$("#submit_paypal_btn").fadeOut();
	var options1 = { 
        //target:        '#output2',   // target element(s) to be updated with server response 
		url:       '<?php print site_url('ajax_helpers/cart_orderPlace'); ?>',
		type:      'post',
		async: false,
        beforeSubmit:  checkout_paypal_form_showRequest,  // pre-submit callback
        success:       checkout_paypal_form_showResponse  // post-submit callback
    }; 
													 
	  $("#step2").isValid();
        var TF = true;

        if($("#step2 .error").exists()){
            TF = false;
            Modal.box("<h2 style='padding:20px;text-align:center;'>Please fill out the required fields.</h2>", 400, 200);
            Modal.xoverlay();
            $("#modalbox").css("background", "white");
        }
       else{
		   
		   
		   
        $("#step2").ajaxSubmit(options1);
		//
		// 
		// $('#checkout-table').hide();
		 // $('#header_cart').hide();
		//  $('#paypal_redirect_msg').show();
       }
      // return false; 

}
function checkout_paypal_form_showRequest(formData, jqForm, options) {




}
 function checkout_paypal_form_showResponse(responseText, statusText)  {
	  $("#submit_payments_form_button_redirecting").fadeIn();
	  $("#checkout_page").hide();
	   $("#step2").fadeOut();
	      $(".paybtn").fadeOut();
	   
	   
	   
$("#step2").attr("action", "https://www.paypal.com/cgi-bin/webscr" );
$("#step2").submit();
//alert(responseText);

$('#checkout-table').hide();
$('#header_cart').hide();
$('#paypal_redirect_msg').show();
} 

// pre-submit callback 

function checkut_form_showRequest(formData, jqForm, options) { 



//$("#step2").validate();

  	 var TF = true;

        if($("#step2 .error").exists()){

            TF = false;
            Modal.box("<h2 style='padding:20px;text-align:center;'>Please fill out the required fields.</h2>", 400, 200);
            $("#modalbox").css("background", "white");
            Modal.overlay();




        } else {

		//$("#submit_payments_form_button").remove();

		//$("#submit_payments_form_button_redirecting").fadeIn();

		}





        return TF;

} 

 

// post-submit callback 

function checkut_form_showResponse(responseText, statusText)  {
$("#submit_payments_form_button_redirecting_to_borica").fadeIn();
	  $("#checkout_page").hide();
	   $("#step2").fadeOut();
	      $(".paybtn").fadeOut();
var gogog = responseText;
//alert(responseText);


  	var options1 = { 
        //target:        '#output2',   // target element(s) to be updated with server response 
		url:       '<?php print site_url('ajax_helpers/cart_orderPlace'); ?>',
		type:      'post',
		 success: function(){
        window.location = gogog;
       } , 
		async: false
     
    }; 
													 
	  
		   		   
         $("#step2").ajaxSubmit(options1);




	 //  $('#step2').submit();



} 

</script>
<style type="text/css">
#step2 {
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

$order_id = date("YmdHis"); 

//var_dump($sid,$cart_items);    

 ?>
<?php if(!empty($cart_items)): ?>
<div id="checkout_page">
  <div class="richtext">
    <h2>Welcome to our Online Payments Center</h2>
  </div>
We use secure Payment with PayPal and Verified by Visa<br />
  <br /> 
  <table border="0"> 
    <tr valign="middle">
      <td>We accept:</td>
      <td><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccVisa.gif"    /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccMC.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccAmex.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/logo_ccDiscover.gif"   /><img src="<?php print TEMPLATE_URL; ?>img/payment_methods/PayPal_mark_37x23.gif"   /></td>
    </tr>
  </table>
  <br />
  <strong>PayPal</strong> is a convenient, easy-to-use and secure way for individuals and businesses to send and receive money online for goods and services. PayPal allows you to send money securely from your credit card, debit card or bank account without ever revealing those details.<br /><br />
  

  
  <strong>Verified by Visa</strong> 
(VbyV) is based on 3D Secure software developed by Visa International,a globally interoperable technology for secure online payments. VbyV gives Visa cardholders more control when using their cards on the Internet. It reduces the potential for unauthorised use of payment cards on the Internet and thereby it increases consumer confidence in online shopping.

  <br />  
  <br />
  <strong>Instructions:</strong>
  
  Click "Pay with PayPal" and you will be redirected to PayPal's secure payment site. Fill in the appropriate information and you will receive a confirmation email shortly.<br />
Click "Pay with cradit card" and you will be redirected to Borica's secure payment terminal. Fill your card details and after you made the payment you will be notified whether your payment has been processed. Please be informed that when you proceed with the credit card payment your total will be converted in Bulgarian currency according to the official daily rate of exchange of BNB.<br />
<br />
  <br />
 
  <?php //$shipping_price =CI::model ( 'cart' )->cartSumByFields('qty');

 

 ?>
  <br />
  <br />
  <br />
  <br />
  <div class="clear"></div>
  <br />
  <br />
  <!--<input name="update_the_cart_qty_in_header()" type="button" onClick="update_the_cart_qty_in_header()" value="update_the_cart_qty_in_header()" />-->
  <form method="post" action="#"   id="step2">
    <input type="hidden" name="business" value="info@omnitom.com">
    <input type="hidden" name="cmd" value="_xclick">
    <input name="autoreply" type="hidden"  value="autoreply_order" />
    <input type="hidden" name="currency_code" value="<?php print $this->session->userdata ( 'shop_currency_code' ) ?>">
    <!--        <input type="hidden" name="image_url" value="http://omnitom.com/userfiles/templates/omnitom/img/logo.jpg">-->
    <input type="hidden" name="no_shipping" value="2">
    <input type="text" style="display:none;" name="shipping" class="shipping_cost_information" id="payment_form_shipping" value="">
    <!--  <input type="hidden" name="shopping_url" value="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">-->
    <input type="hidden" name="amount" value="<?php print CI::model ( 'cart' )->itemsGetTotal($this->session->userdata ( 'cart_promo_code' ), $this->session->userdata ( 'shop_currency' )); ?>">
    <input type="hidden" name="promo_code" value="<?php print ($this->session->userdata ( 'cart_promo_code' )); ?>">
    <input type="hidden" name="item_name" value="Omnitom.com Online Shop - Order ID: <?php print $order_id; ?>">
    <input type="hidden" name="weight_cart" value="<?php print CI::model ( 'cart' )->cartSumByFields('weight'); ?>">
    <input type="hidden" name="weight_unit" value="kgs">
    <!--  <input type="hidden" name="return" value="<?php print $link = CI::model ( 'content' )->getContentURLById(57); ?>/order:<?php print $this->session->userdata ( 'session_id' ); ?>">-->
    <input type="text" name="order_id" style="display:none;" value="<?php print $order_id; ?>">
    <input type="hidden" name="sid" value="<?php print $this->session->userdata ( 'session_id' ); ?>">
    <div id="form_left_block">
      <div id="form_personal_info">
        <div class="richtext">
          <h3>Personal info</h3>
        </div>
        <div class="mtf">
          <label>First Name<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="first_name" value="<?php print $this->session->userdata ( 'first_name' ) ?>" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Last Name<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="last_name" value="<?php print $this->session->userdata ( 'last_name' ) ?>" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Email<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="email" value="<?php print $this->session->userdata ( 'email' ) ?>" class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Phone Number</label>
          <div class="box">
            <input type="text"  value="<?php print $this->session->userdata ( 'night_phone_a' ) ?>" name="night_phone_a"   />
          </div>
        </div>
      </div>
      <input name="autoreply" type="hidden"  value="autoreply_order" />
      <input type="hidden" name="currency_code" value="<?php print $this->session->userdata ( 'shop_currency_code' ) ?>">
      <!--        <input type="hidden" name="image_url" value="http://omnitom.com/userfiles/templates/omnitom/img/logo.jpg">-->
      <!--  <input type="hidden" name="shopping_url" value="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">-->
      <input type="hidden" name="amount" value="<?php print CI::model ( 'cart' )->itemsGetTotal($this->session->userdata ( 'cart_promo_code' ), $this->session->userdata ( 'shop_currency' )); ?>">
      <input type="hidden" name="promo_code" value="<?php print ($this->session->userdata ( 'cart_promo_code' )); ?>">
      <input type="hidden" name="item_name" value="Omnitom.com Online Shop - Order ID: <?php print $order_id; ?>">
      <input type="hidden" name="weight_cart" value="<?php print CI::model ( 'cart' )->cartSumByFields('weight'); ?>">
      <input type="hidden" name="weight_unit" value="kgs">
      <!--  <input type="hidden" name="return" value="<?php print $link = CI::model ( 'content' )->getContentURLById(57); ?>/order:<?php print $this->session->userdata ( 'session_id' ); ?>">-->
      <input type="hidden" name="order_id" value="<?php print $order_id; ?>">
      <input type="hidden" name="sid" value="<?php print $this->session->userdata ( 'session_id' ); ?>">
      <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
      <!-- /#form_address_info -->
    </div>
    <!-- /#form_left_block -->
    <div style="float: left; width: 405px; padding: 5px;padding-top: 0">
      <div id="form_address_info">
        <div class="richtext">
          <h3>Address info for shipping</h3>
        </div>
        <div class="mtf">
          <label style="padding-top: 0">Country<small  class="pink_text">(required)</small></label>
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
            <input type="text" name="city"  value="<?php print $this->session->userdata ( 'city' ); ?>"  class="required">
          </div>
        </div>
        <div class="mtf">
          <label>Address<small  class="pink_text">(required)</small></label>
          <div class="box">
            <input type="text" name="address1"   value="<?php print $this->session->userdata ( 'address1' ); ?>"   class="required">
          </div>
        </div>
        <div class="mtf">
          <label>State/Province</label>
          <div class="box">
            <input name="state" value="<?php print $this->session->userdata ( 'state' ); ?>" type="text" />
          </div>
        </div>
        <div class="mtf">
          <label>Zip/Postal Code</label>
          <div class="box">
            <input name="zip" value="<?php print $this->session->userdata ( 'zip' ); ?>" type="text" />
          </div>
        </div>
      </div>
      <? /*
    <div id="card-check-status">
      <div style="padding-left: 7px; padding-bottom: 0px; width: 405px; float: left;" class="">
        <div class="richtext">
          <h3 >Card Data</h3>
        </div>
        <div style="height:0px; overflow: hidden;" class="clear">&nbsp;</div>
        <!--You will be prompted for your credit card data at the final step.-->
        <div style="display: none;" id="card-check-status-error"> Error: We were unable to verify this credit card account. Please check your credit card info and fill all fields correctly. </div>
        <div>
          <div class="mtf" style="clear: both;">
            <label>First name <small class="pink_text">(on card) *</small></label>
            <div class="box error">
              <input type="text" class="required billing_info_trigger" value="<?php print $this->session->userdata ( 'billing_first_name' ); ?>" id="billing_first_name" name="billing_first_name">
            </div>
          </div>
          <div class="mtf">
            <label>Last name <small class="pink_text">(on card) *</small></label>
            <div class="box error">
              <input type="text" class="required billing_info_trigger" value="<?php print $this->session->userdata ( 'billing_last_name' ); ?>" id="billing_last_name" name="billing_last_name">
            </div>
          </div>
        </div>
        <div class="mtf" style="clear: both;">
          <label> Credit card number <small class="pink_text"> *</small></label>
          <div class="box error">
            <input type="text" class="required billing_info_trigger" value="<?php print $this->session->userdata ( 'billing_cardholdernumber' ); ?>" id="billing_cardholdernumber" autocomplete="off" name="billing_cardholdernumber">
          </div>
        </div>
        <div class="mtf">
          <label>Card type <small class="pink_text"> *</small></label>
          <div class="box">
            <?  $cardTypes = (CI::model ( 'cart' )->payPalCardTypes()); ?>
            <? $credit_card_type = $this->session->userdata('credit_card_type');
			if($credit_card_type == false) {
				$credit_card_type = 'Visa';
			}
		?>
            <select name="credit_card_type" class="required billing_info_trigger" id="credit_card_type">
              <? $i=0; foreach($cardTypes as $c => $v):   ?>
              <option value="<? print $c ?>" <? if($credit_card_type == $c) : ?> selected="selected" <?  endif;?> ><? print $v ?></option>
              <? $i++; endforeach; ?>
            </select>
          </div>
        </div>
        <div style="height: 0px; overflow: hidden;" class="clear">&nbsp;</div>
        <div class="mtf">
          <label>Expires month <small  class="pink_text"> *</small></label>
          <div class="box">
            <!--<input name="billing_expiresmonth" id="billing_expiresmonth" value="<? print $this->session->userdata ( 'billing_expiresmonth' ) ?>" class="required billing_info_trigger" type="text" />-->
            <select style="width: 95px" name="billing_expiresmonth" id="billing_expiresmonth" class="required billing_info_trigger">
              <?

	 $y = date('Y');
	 $start = 1;
	 $end = 12;


     for ($i = $start; $i <=  $end; $i++) : ?>
              <option <? if($this->session->userdata ( 'billing_expiresmonth' ) == $i) : ?> selected="selected"   <? endif; ?>  value="<? print $i ?>"><? print $i ?></option>
              <? endfor; ?>
            </select>
          </div>
        </div>
        <div class="mtf">
          <label>Expires year <small  class="pink_text"> *</small></label>
          <div class="box">
            <select name="billing_expiresyear" style="width: 95px;" id="billing_expiresyear" class="required billing_info_trigger">
              <?

	 $y = date('Y');
	 $start = $y - 0;
	 $end = $y + 10;


     for ($i = $start; $i <=  $end; $i++) : ?>
              <option <? if($this->session->userdata ( 'billing_expiresyear' ) == $i) : ?> selected="selected"   <? endif; ?>  value="<? print $i ?>"><? print $i ?></option>
              <? endfor; ?>
            </select>
          </div>
        </div>
      </div
      >
    </div>
    */ ?>
      <? /*
    <div style="clear: none; padding: 2px; width: 300px; float: left; margin-left: 5px;" class="">
      <div class="mtf" style="clear: both;">
        <script type="text/javascript">

  $(document).ready(function(){

    $("#wheretofindit").modal("html")

  });

  </script>
        <label>CVV2 <small class="pink_text"> *</small>&nbsp;&nbsp;<a id="wheretofindit" href="#find-it"><strong>?</strong></a></label>
        <div class="box error">
          <input type="text" class="required billing_info_trigger" value="<?php print $this->session->userdata ( 'billing_cvv2' ); ?>" id="billing_cvv2" autocomplete="off" name="billing_cvv2" maxlength="4" style="width: 70px;">
        </div>
      </div>
      <div id="cvv-info">
        <div style="width: 170px; height: 120px; background: none repeat scroll 0% 0% rgb(233, 242, 249); padding: 15px; display: none;" id="find-it"> <span>VISA &amp; MASTERCARD:</span> <img alt="" src="https://waterforlifeusa.com/new/userfiles/templates/waterforlifeusa/img/cc_code_1.gif"> <em>3-digit code </em>
          <div style="height: 2px; overflow: hidden;" class="clear">&nbsp;</div>
          <span class="clear">AMERICAN EXPRESS:</span> <img alt="" src="https://waterforlifeusa.com/new/userfiles/templates/waterforlifeusa/img/cc_code_1.gif"> <em>4-digit code</em> </div>
      </div>
    </div>
    */ ?>
      <div class="c">&nbsp;</div>
      <div id="termsc">
        <input type="checkbox" class="required" />
        <span class="termsctxt">I agree with <a onclick="chat('<? print site_url('terms-and-conditions') ?>');return false;" href="<? print site_url('terms-and-conditions') ?>">terms and conditions</a></span> </div>
      <div id="o-info" style="">
        <table>
          <tbody>
            <tr>
              <td></td>
              <td><div style="width: 415px;" id="srumk">
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
                  <div style="height: 5px; overflow: hidden;" class="clear"></div>
                </div>
                <div class="clear"></div>
                <br>
                <!--[if IE 6]><input type="submit" value="" class="ie6sbm" style="width:180px;height:27px;" /><![endif]-->
                <span style="display: none;" id="submit_payments_form_button_redirecting"> <br>
                Redirecting... </span> <br></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
 
  <? if (($_SERVER ['REMOTE_ADDR'] == '77.70.8.202') or  ($_SERVER ['REMOTE_ADDR'] == '88.203.244.136')  or  ($_SERVER ['REMOTE_ADDR'] == '88.203.246.109') or  ($_SERVER ['REMOTE_ADDR'] == '88.203.244.254') ): ?>
   
     <? endif; ?>
   
  <a style="margin: 0 111px 0 0;" class="big_btn right paybtn" href="javascript:checkout_paypal();" id="submit_paypal_btn"><span class="relative" style="padding-left: 29px;"><i id="a">&nbsp;</i>Pay with paypal</span></a>
    <a style="margin: 0 10px 0 0;" class="big_btn right paybtn" href="javascript:void(0);" id="submit_payments_form_button"><span class="relative" style="padding-left: 30px;"><i id="b">&nbsp;</i> Pay with credit card</span></a>
   
    <div> <small style="width:300px; display:block;float: right;margin: 10px 167px 0 0;" class="pink_text">*Note: by clicking the "Pay" button your order will be placed and your shopping bag will be emptied</small> </div>
    <div style="height: 15px"></div>
    <div class="clear"></div>
    <!--  onclick="$('#step2').submit()"-->
  </form>
</div>
<div class="clear"></div>
<span id="submit_payments_form_button_redirecting" style="display:none"><br />
<h1>Redirecting to Paypal...</h1>
</span>

<span id="submit_payments_form_button_redirecting_to_borica" style="display:none"><br />
<h1>Redirecting to Visa Verified...</h1>
</span>
</div>
<?php else: ?>
<!--Your bag is empty. Please go to the <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">shop</a> and add some items. <br />-->
<br />
<br />
<?php endif; ?>
<div id="empty_cart_msg" style="display:none"> Your bag is empty. Please go to the <a href="<?php print $link = CI::model ( 'content' )->getContentURLById(14); ?>">shop</a> and add some items. <br />
</div>
<div id="paypal_redirect_msg" style="display:none"> You are being redirected to PayPal. Please complete your payment there.<br />
</div>
