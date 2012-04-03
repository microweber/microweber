<?

$cart_items = get_cart_items();
$order_id = "ORD". date("ymdHis") . rand ();
//var_dump($params);

if($params['module_id'] == false){
	
//$params['module_id'] = 'default';	
}

 ?>
<script type="text/javascript">


function payment_proceed_paypal(){
		
	
		 $('#paypal_form').submit();
	 $(".cart_show_on_complete").fadeIn();
	
	
}



function sales_tax_apply(){
	state =  $('#state').val();
	if(state == 'MD'){
	 
		$(".webstore_sales_tax").fadeIn();
		
		$tot = $('#pay_pal_total').val(); 
		
		var resultzzzzzzz = ( 6 / 100) * $tot;
		
		var amt = parseFloat(resultzzzzzzz);
		amt = amt.toFixed(2);
              resultzzzzzzz1 =  ('$ ' + amt);
			   resultzzzzzzz =  (amt);
				
				
		
		 $('.sales_tax').html(resultzzzzzzz1);
		    $('input.sales_tax').val(resultzzzzzzz);
		
		
		
	} else {
		$(".webstore_sales_tax").hide();
		 $('.sales_tax').html('');
		    $('input.sales_tax').val('');
	}
}


function ups_shiping_cost(){
		
		
		
		zip =  $('#zip').val();
		is_val_zip = isValidUSZip(zip)
		
		if(is_val_zip == true){
		$.ajax( {
		type : "POST",
		url : "<? print site_url('api/cart/ups_shiping_cost'); ?>",
		data: "zip="+zip,
		async : false,
		success : function(data) {
		  $('.ups_shiping_cost').html(data);
		    $('input.shiping').val(data);
			
			 $('.ups_shipping_cost').html(data);
		    $('input.shipping').val(data);
   
		  
		}
	});
		
		
		}  else {
			$('.ups_shiping_cost').html('please enter valid zip code');
		}
		
		
	
	 
	
	
}





function payment_proceed(){
	
	 
	 
	 
	 
	 required = ["first_name", "last_name", "zip", "city", "address", "state", "i_agree", "zip"];
	// If using an ID other than #email or #error then replace it here
	email = $("#email");
	errornotice = $("#error");
	// The text to show up within a field when it is incorrect
	emptyerror = "Please fill out this field.";
	emailerror = "Please enter a valid e-mail.";

	 
		//Validate required fields
		for (i=0;i<required.length;i++) {
			var input = $('#'+required[i]);
			if ((input.val() == "") || (input.val() == emptyerror)) {
				input.addClass("needsfilled");
				input.val(emptyerror);
				errornotice.fadeIn(750);
			} else {
				input.removeClass("needsfilled");
			}
		}
		// Validate the e-mail.
		if (!/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email.val())) {
			email.addClass("needsfilled");
			email.val(emailerror);
		}

		//if any inputs on the page have the class 'needsfilled' the form will not submit
		if ($(":input").hasClass("needsfilled")) {
			 
		} else {
			errornotice.hide();
			
			
				mw.cart.complete_order('.checkout_form', '.cart_hide_on_complete', payment_proceed_paypal)
$(".checkout_form").hide();
			
			
			//return true;
		}
	 
	
	// Clears any fields in the form when the user clicks on them
	
	 
	 
	 
	 

	
	
	
	
	
 
}
 
 
 function isValidUSZip(sZip) {
   return /^\d{5}(-\d{4})?$/.test(sZip);
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
<style type="text/css">
 
#error {
	color:red;
	font-size:10px;
	display:none;
}
.needsfilled {
	 background-color:#FFE6E6;
	color:#000;
}
</style>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" style="display:none" >
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="ebuxton@digital-connections.tv">
  <input type="hidden" name="lc" value="GB">
  <input type="hidden" name="item_name" value="<? print $order_id ?>">
  <input type="hidden" name="item_number" value="1">
  <input type="text" name="amount" id="pay_pal_total" class="cart_items_total" value="<? print (get_cart_total()); ?>">
  <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="button_subtype" value="products">
  <input type="hidden" name="no_note" value="0">
  <input type="hidden" name="cn" value="Add special instructions to the seller">
  <input type="hidden" name="shipping"  class="shipping">
  
  
  
   <input type="hidden" name="tax"  class="sales_tax">

  
  
  
  
 
  
  
  <input type="hidden" name="rm" value="1">
  <input type="hidden" name="return" value="<? print page_link(PAGE_ID) ?>view:paid">
  <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<? if(empty($cart_items)): ?>
<span class="cartico"> Your cart is empty</span>
<? else: ?>
 
<div class="cart_show_on_complete" style="display:none">
  <h1> Your order is placed. We are redirecting to paypal. </h1>
</div>
<div id="cart_checkout_placeholder"></div>
<script type="text/javascript">

 

    $(document).ready(function(){
ups_shiping_cost();
sales_tax_apply()
         $(":input").focus(function(){		
	   if ($(this).hasClass("needsfilled") ) {
			$(this).val("");
			$(this).removeClass("needsfilled");
	   }
	});
    });



















 








    </script>
<?  if(user_id()  != false):  ?>
<? $form_values = get_user(user_id()); ?>
<? endif ;?>





































<? 
				   $shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
				//  var_dump($shop_page);
				  ?>
<form class="checkout_form">
<div id="error"></div>
<div class="webstore_form_left">
  <div class="webstore_form_tit">Personal Information</div>
  <div class="webstore_form_lable">First Name <span class="required_red">(required)</span> </div>
  <input type="text" name="first_name" id="first_name" class="webstore_form_text" />
  <div class="webstore_form_lable">Last Name <span class="required_red">(required)</span> </div>
  <input type="text"  name="last_name" id="last_name" class="webstore_form_text" />
  <div class="webstore_form_lable">Email <span class="required_red">(required)</span> </div>
  <input type="text"  name="email"  id="email" class="webstore_form_text" />
  <div class="webstore_form_lable">Phone Number</div>
  <input type="text"  name="phone" class="webstore_form_text" />
 <!-- <div class="webstore_chkbox">
   <input type="checkbox"  name="i_agree" id="i_agree" value="checkbox" /> 
  </div>
  <div class="webstore_agree"><label for="i_agree">I agree with the terms and conditions <small><A href="<? print site_url("terms"); ?>">click here read them</A></small></label></div>-->
  <!--<div class="webstore_continue_shopping_but">
    <a href="<? print page_link( $shop_page['id']); ?>"><img src="<? print TEMPLATE_URL ?>images/continue_shopping_but.jpg" /></a>
  </div>-->
</div>
<div class="webstore_form_rt">
  <div class="webstore_form_tit">Shipping Address</div>
  <div class="webstore_form_lable">City <span class="required_red">(required)</span> </div>
  <input type="text"  name="city" id="city" class="webstore_form_text" />
  <div class="webstore_form_lable">Address <span class="required_red">(required)</span> </div>
  <input type="text"  name="address" id="address" class="webstore_form_text" />
  <div class="webstore_form_lable">State/Province <span class="required_red">(required)</span></div>
<!--  <input type="text"  name="state"  id="state" class="webstore_form_text" />
-->  
  
  
    
  
  
  
  
  <select name="state"  id="state" class="webstore_form_text" onchange="sales_tax_apply()">
  <option value="">-- Select state</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">District of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>
</select>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <div class="webstore_form_lable">Zip/Postal Code <span class="required_red">(required)</span></div>
  <input type="text"  id="zip"  name="zip" class="webstore_form_text" onkeyup="ups_shiping_cost()" />
  <div class="webstore_continue_shipping_but">
      <a href="javascript:payment_proceed()"><img src="<? print TEMPLATE_URL ?>images/webstore_paypal_but.jpg" /></a>
<!--    <input type="image"  onclick="payment_proceed()" src="<? print TEMPLATE_URL ?>images/webstore_paypal_but.jpg" />
-->  </div>
</div>

 <input type="hidden"    name="shipping" class="shipping" />


</form>


<!-- &nbsp;&nbsp;You have <? print get_items_qty() ; ?> item in your cart-->












<!--<form method="post" action="#" class="checkout_form cart_hide_on_complete" style="display:none">
  <table width="80%" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td><h3>Names</h3></td>
      <td><span class="field"> <span>
        <input value="Names" class="required"  name="names" default="Names" type="text">
        </span> </span></td>
    </tr>
    <tr>
      <td><h3>Email</h3></td>
      <td><span class="field"> <span>
        <input value="email" class="required"  name="email" default="email" type="text">
        </span> </span></td>
    </tr>
    <tr>
      <td><h3>City</h3></td>
      <td><span class="field"> <span>
        <input value="city" class="required"  name="city" default="city" type="text">
        </span> </span></td>
    </tr>
    <tr>
      <td><h3>Address</h3></td>
      <td><span class="field"> <span>
        <input value="address" class="required"  name="address" default="address" type="text">
        </span> </span></td>
    </tr>
    <tr>
      <td><h3>Zip</h3></td>
      <td><span class="field"> <span>
        <input value="zip" class="required"  name="zip" default="zip" type="text">
        </span> </span></td>
    </tr>
    <tr>
      <td></td>
      <td><a class="bs" ><span>Continue to payment</span></a></td>
    </tr>
  </table>
</form>

-->





<? endif ?>
