<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">
  $(document).ready(function(){
 
	
	 mw.options.form('.mw-set-payment-options', function(){
      mw.notification.success("<?php _e("Settings are saved"); ?>.");
    });
	
	
	

$('.mw-admin-wrap').click(function(){
	// mw.options.form('.mw-set-payment-options');
  });


    mw.tools.tabGroup({
       nav:'.payment-tab',
       tabs:'.otab',
       master: mwd.querySelector('.mw-admin-side-nav')
    });





var email_ed =  mw.tools.iframe_editor("#order_email_content" , {modules:'shop/orders/editor_dynamic_values'})
$(email_ed).css('width',"100%");
$(email_ed).css('height',"450px");


  });
  
  
  
  
  
  
  
  mw.checkout_confirm_email_test = function(){

 
	var email_to = {}
	email_to.to = $('#test_email_to').val();;
	//email_to.subject = $('#test_email_subject').val();;
	
	 $.post("<?php print site_url('api_html/checkout_confirm_email_test'); ?>", email_to,  function(msg){
//Alert("<pre>"+msg+"</pre>")

		mw.tools.modal.init({
			html:"<pre>"+msg+"</pre>",	
			title:"Email send results..."
		});
			// $('#email_send_test_btn_output').html(msg);
	  });
}
</script>
<style>
.otab {
	display: none;
}
.mw-set-payment-options input[type='text'], .mw-set-payment-options textarea {
	width:300px;
}
.mw-set-payment-options .mw-ui-select {
	width: 320px;
}
.mw-set-payment-options .mw-ui-label {
	padding-bottom: 5px;
	padding-top: 10px;
}
.mw-set-payment-options .mw-ui-inline-selector li label.mw-ui-label {
	width: 75px;
	padding: 5px 0;
}
</style>
<?
$here = dirname(__FILE__).DS.'gateways'.DS;
$payment_modules = modules_list("cache_group=modules/global&dir_name={$here}");
// d($payment_modules);
?>

<div class="vSpace"></div>
<?
/**
 *
 */

?>
<div class="mw-admin-wrap">
  <div class="mw-o-box has-options-bar">
    <div class="mw-o-box-header" style="margin-bottom: 0;"> <span class="ico ioptions"></span> <span>Options</span> </div>
    <div class="mw-o-box-content" style="padding: 0;">
      <div class="options-bar" style="margin-right: 0;">
        <div class="mw-admin-side-nav">
          <ul>
            <li><a class="payment-tab active" href="javascript:;" style="padding: 6px;">Payments</a></li>
            <li><a class="payment-tab" href="javascript:;" style="padding: 6px;">Emails for order</a></li>
          </ul>
        </div>
      </div>
      <div style="float: right;width: 745px;" class="mw-set-payment-options">
        <div class="otab" style="display: block">
          <h2>Payment providers </h2>
          <? if(isarr($payment_modules )): ?>
          <div class="mw_simple_tabs mw_tabs_layout_stylish">
            <? foreach($payment_modules  as $payment_module): ?>
            <div class="mw-o-box mw-o-box-accordion mw-accordion-active">
            
            <? 
			
			
			$module_info = (module_info($payment_module['module']));
			
			 
			 ?>
            
            
              <div class="mw-o-box-header"  onmousedown="mw.tools.accordion(this.parentNode);">
              
              <? print module_ico_title($payment_module['module'],false); ?>
              
              
             <!--  <span class="ico ireport"></span><span><? print $payment_module['name'] ?></span> -->
             
        
            
            
            
            
            
            
            
          
             
             
             
             
             </div>
              <div class="mw-o-box-content mw-o-box-accordion-content">
                   <label class="mw-ui-label"> <? print $payment_module['name'] ?> <div onmousedown="mw.switcher._switch(this);" class="mw-switcher mw-switcher-green unselectable <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?>mw-switcher-on<? endif; ?>">
<span class="mw-switch-handle"></span>
                <label>Enabled<input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <? endif; ?> ></label>
                <label>Disabled<input name="payment_gw_<? print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <? if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <? endif; ?> ></label>
            </div></label>
            
            
            
                <div class="mw-set-payment-gw-options" >
                  <module type="<? print $payment_module['module'] ?>" view="admin" />
                </div>
              </div>
            </div>
            <? endforeach ; ?>
            <? endif; ?>
          </div>
          <hr>
          <h2>Currency settings</h2>
          <? ?>
          <? $cur = get_option('currency', 'payments');  ?>
          <? $curencies = curencies_list(); ?>
          <? if(isarr($curencies )): ?>
          <div class="mw-ui-select">
            <select name="currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
              <? foreach($curencies  as $item): ?>
              <option  value="<? print $item[1] ?>" <? if($cur == $item[1]): ?> selected="selected" <? endif; ?>><? print $item[1] ?> <? print $item[3] ?> (<? print $item[2] ?>)</option>
              <? endforeach ; ?>
            </select>
          </div>
          <? endif; ?>
          <module type="shop/payments/currency_render" id="mw_curr_rend" />
        </div>
        <div class="otab">
          <h2>Send email to the client on new order</h2>
          <label class="mw-ui-check">
            <input name="order_email_enabled" class="mw_option_field"    data-option-group="orders"  value="y"  type="radio"  <? if(get_option('order_email_enabled', 'orders') == 'y'): ?> checked="checked" <? endif; ?> >
            <span></span><span>Yes</span></label>
          <label class="mw-ui-check">
            <input name="order_email_enabled" class="mw_option_field"     data-option-group="orders"  value="n"  type="radio"  <? if(get_option('order_email_enabled', 'orders') != 'y'): ?> checked="checked" <? endif; ?> >
            <span></span><span>No</span></label>
          <br />
          <small>You must have a working email setup in order to send emails. <a class="mw-ui-link" target="_blank"  href="<?  print admin_url('view:settings#option_group=email'); ?>" style="padding: 6px;">Setup email here.</a></small>
          <label class="mw-ui-label">Email subject</label>
          <input name="order_email_subject" class="mw-ui-field mw_option_field"   id="order_email_subject"  placeholder="Thank you for your order!" data-option-group="orders"  value="<? print get_option('order_email_subject', 'orders') ?>"  type="text" />
          <label class="mw-ui-label">Send copy email to</label>
          <input name="order_email_cc" class="mw-ui-field mw_option_field"   id="order_email_cc" placeholder="me@email.com"  data-option-group="orders"  value="<? print get_option('order_email_cc', 'orders') ?>"  type="text" />
          <a class="mw-ui-btn mw-ui-btn-small" href="javascript:$('#test_ord_eml_toggle').toggle(); void(0);">[test]</a></small>
          <table width=" 100%" border="0" id="test_ord_eml_toggle" style="display:none">
            <tr>
              <td><label class="mw-ui-label">Send test email to</label>
                <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<? print get_option('test_email_to','email'); ?>"  />
                <div class="vSpace"></div>
                <span onclick="mw.checkout_confirm_email_test();" class="mw-ui-btn mw-ui-btn-green" id="email_send_test_btn">Send test email</span></td>
              <td><pre id="email_send_test_btn_output"></pre></td>
            </tr>
          </table>
          <label class="mw-ui-label">Email content</label>
          <textarea class="mw-ui-field mw_option_field"   data-option-group="orders" id="order_email_content" name="order_email_content"><? print get_option('order_email_content', 'orders') ?></textarea>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="vSpace"></div>
