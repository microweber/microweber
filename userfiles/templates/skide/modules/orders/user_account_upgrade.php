  <?  if($user_id == false){
	 $user_id = user_id_from_url(); 
 
}


?>



<? $paypal_payment_form_id = 'paypalform'.rand().rand().rand();


$order_id =  'member_'.date("YmdHis").rand().rand().'_usr:'.$user_id;

 ?>
<script type="text/javascript">

try{
    document.getElementById('wall').id='main_side';
}
catch(err){}

function set_paypal($amount, $months){

	$(".field_pay_amount").val($amount);
	$(".field_pay_months").val($months);
	 
	checkout_paypal();

}

function checkout_paypal(){
	//$("#submit_paypal_btn").fadeOut();
	var options1 = {
        //target:        '#output2',   // target element(s) to be updated with server response 
		url:       '<?php print site_url('api/shop/place_order'); ?>',
		type:      'post',
		async: false,
        beforeSubmit:  checkout_paypal_form_showRequest,  // pre-submit callback
        success:       checkout_paypal_form_showResponse  // post-submit callback
    }; 
													 
 
		   
		   
        $("#<? print $paypal_payment_form_id; ?>").ajaxSubmit(options1);
		//
		// 
		// $('#checkout-table').hide();
		 // $('#header_cart').hide();
		//  $('#paypal_redirect_msg').show();
        
      // return false; 

}
function checkout_paypal_form_showRequest(formData, jqForm, options) {




}
 function checkout_paypal_form_showResponse(responseText, statusText)  {
	/*  $("#submit_payments_form_button_redirecting").fadeIn();
	  $("#checkout_page").hide();
	   $("#step2").fadeOut();
	      $(".paybtn").fadeOut();*/
	   
	  // alert(responseText); 
	   
	   //
	   
$("#<? print $paypal_payment_form_id; ?>").attr("action", "https://www.paypal.com/cgi-bin/webscr" );
$("#<? print $paypal_payment_form_id; ?>").submit();
 
} 

</script>

<div class="bluebox">
  <div class="blueboxcontent">
    <div class="payment_engine">

      <? if(url_param('ok') == true): ?>
      <h2 class="green">Your payment has been made. Thank you.</h2>
      <div class="c" style="padding-bottom: 5px">&nbsp;</div>
      <h2 class="green" style="padding-bottom: 10px;">Please allow up to 5 minutes for your account to be upgraded.</h2>
      <? elseif(url_param('cancel') == true): ?>
      <h2 class="red">You have canceled your payment.</h2>
      <? else: ?>
      <h2><? /*
Extend the user account of <? print user_name( $user_id); ?>
*/ ?></h2>

<div id="extend_wrap">











     <h1 class="extend_title">30 days free trial on all accounts</h1>
     
      
     
     <? /*
     <h2 class="extend_slogan">Skid-e-kids is the best safe and fun social networking choice for your kids. A perfect alternative for parents who are not comfortable with allowing their children on MySpace and Facebook. There is no other network out there that is offering Toy swaps, educational question and answers, Age appropriate blockbuster Movies, Fantastic games and many more. Value is priceless.</h2>
     */ ?>
     <h2 class="extend_slogan">For a penny a day, you and your child would be able to enjoy the safest, fun and educative online community for kids, ages 7 to 13.</h2>

     <p class="xextend_slogan">Kids/ Parents Dashboard, Education, Skid-e-tube, Movies, Toys Swap, Games & many more. </p>
     <p class="xextend_slogan"><strong>Compared Value is priceless.</strong> You will automatically get 30 days free, after you signup.</p>

     <div style="height: 20px;">&nbsp;</div>

     <div id="plans">
        <div class="plan plan1">
             <h3>6 month</h3>
             <strong>$12.00 / 6 months</strong>
             <div style="height: 20px;">&nbsp;</div>

             <span>Save money with this plan</span>


             <a href="javascript: set_paypal('12','6');"  class="plan_btn">Choose Plan</a>
        </div>
        <div class="plan plan2">
            <h3>1 Year</h3>
            <strong>Only $24.00 / per Year</strong>
            <div style="height: 20px;">&nbsp;</div>
            <span>Save money with this plan</span>
            <a href="javascript: set_paypal('24','12');" class="plan_btn_big">Choose Plan</a>
        </div>
        <div class="plan plan3">
             <h3>1 month</h3>
             <strong>$3.00 / per month</strong>
             <div style="height: 20px;">&nbsp;</div>

             <a href="javascript: set_paypal('3','1');" class="plan_btn">Choose Plan</a>
        </div>
     </div>

</div>


<br />
<br />
<? if($user_id != user_id()): ?>
<span>You are about to pay for the user <? print user_name( $user_id); ?></span>
<? else: ?>

<? endif; ?>




      <form action="#" method="post" id="<? print $paypal_payment_form_id; ?>">
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
       <input type="hidden" name="business" value="skidekidsmg@gmail.com">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="item_name" value="Skidekids membership for <? print addslashes(user_name($user_id)); ?> (id:<? print $user_id; ?>) ">
        <input type="hidden" name="item_number" value="<? print $order_id ?>">
        <input type="hidden" name="order_id" value="<? print $order_id ?>">
        <input type="hidden" name="for" value="users">
        <input type="hidden" name="for_id" value="<? print $user_id; ?>">
        <input type="hidden" name="reattempt" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="rm" value="1">
        
        
          <?  if($redirect_to == false) : ?>
	<input type="hidden" name="return" value="<? print url(); ?>/ok:true">
    
    <? else: ?>
     <input type="hidden" name="return" value="<? print $redirect_to; ?>">
<? endif; ?>

<?  if($redirect_to_on_cancel == false) : ?>
	 <input type="hidden" name="cancel_return" value="<? print url(); ?>/cancel:true">
    
    <? else: ?>
     <input type="hidden" name="cancel_return" value="<? print $redirect_to_on_cancel; ?>">
<? endif; ?>


       
       
        
        
        
        <input type="hidden" name="a1" value="0.00">
        <input type="hidden" name="p1" value="1">
        <input type="hidden" name="t1" value="M">
        <input type="hidden" name="src" value="1">
        <input type="hidden" name="a3" class="field_pay_amount" value="3.00">
        <input type="hidden" name="amount" class="field_pay_amount" value="3.00">
        <input type="hidden" name="p3" class="field_pay_months"  value="1">
        <input type="hidden" name="t3" value="M">
        <input type="hidden" name="currency_code" value="USD">
<? /*
        <input name="subscribe" value="subscribe" type="button" onClick="checkout_paypal();" />
*/ ?>
      </form>
      <? endif; ?>
    </div>

    <img src="<? print TEMPLATE_URL ?>static/img/payments.jpg" class="left" style="margin-right:10px;" alt="" />
    <p style="padding-top: 4px;"><em>All secured payments are made through PayPal. PayPal also accept visa and MasterCard.</em></p>

  </div>
</div>
