<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


setActiveProvider = function(el){
  if(el.checked == true){
    el.value == 'y'? $(el.parentNode.parentNode).addClass("active") : $(el.parentNode.parentNode).removeClass("active");
  }

}


  $(document).ready(function(){



  if(typeof thismodal !== 'undefined'){
   thismodal.main.width(1000);
   $(thismodal.main[0].getElementsByTagName('iframe')).width(985);
}

	
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






mw.$("#available_providers").sortable({
  items:".mw-o-box",
  handle:".iMove",
  axis:"y",
  placeholder: "available_providers_placeholder",
  start:function(a,b){

    $(this).find(".mw-o-box").each(function(){
      $(this).height("auto");
      $(this).removeClass("mw-accordion-active");
      $(this).removeClass("active");
      $(this).find(".mw-o-box-content").hide();
    });
    $(this).sortable("refreshPositions");

  },
  stop:function(){
    Alert("Saving ... ");
  }
})


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

.payment-state-status{
  padding: 12px;
  display: inline-block;
  margin-top: 12px;
  transition: all 200ms;
}

.payment-state-status {
  background: #F27E54;
  color: white;

}

.payment-state-status.active {
  background: #D7FFC3;
  color: #6C6C6C;
}

.payment-state-status .mw-ui-check:first-child{
    margin-right: 12px;
}

.gateway-icon-title {
  font-weight: normal;
  font-size: 16px;
}

.gateway-icon-title img{
  margin: 10px 22px 0 0;
  max-height: 40px;
  max-width: 130px;
  float: left;
}
.gateway-icon-title .gateway-title{
  display: block;
  float: right;
  width: 530px;
  padding-top: 10px;

}

.mw-o-box-header .iMove{
  visibility: hidden;
}
.mw-o-box-header:hover .iMove{
  visibility: visible;
}

.available_providers_placeholder{
  border: 2px dashed #ccc;
  background:transparent;
  height: 70px;
  margin: 10px 0;
  position: relative;
}


</style>
<?php
$here = dirname(__FILE__).DS.'gateways'.DS;
$payment_modules = modules_list("cache_group=modules/global&dir_name={$here}");
// d($payment_modules);
?>

<div class="vSpace"></div>
<?php
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
          <?php if(isarr($payment_modules )): ?>
          <div class="mw_simple_tabs mw_tabs_layout_stylish" id="available_providers">
            <?php foreach($payment_modules  as $payment_module): ?>
            <div class="mw-o-box mw-o-box-accordion mw-accordion-active">
              <?php 
			
			
			        $module_info = (module_info($payment_module['module']));

			 
			 ?>
              <div class="mw-o-box-header"  onmousedown="mw.tools.accordion(this.parentNode);">
                   <div class="gateway-icon-title">
                      <span class="ico iMove"></span>
                      <img src="<?php print $payment_module['icon']; ?>" alt="" />
                      <span class="gateway-title"><?php print $payment_module['name'] ?></span>
                   </div>
                <!--  <span class="ico ireport"></span><span><?php print $payment_module['name'] ?></span> -->
                
              </div>
              <div class="mw-o-box-content mw-accordion-content">
                <label class="mw-ui-label">
                <h3><?php print $payment_module['name'] ?>:</h3>


                <div class="mw-o-box payment-state-status <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?>active<?php endif; ?>">
                    <label class="mw-ui-check">
                        <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                        <span></span>
                        <span class="first">Enabled</span>
                    </label>
                    <label class="mw-ui-check">
                      <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                      <span></span>
                      <span class="second">Disabled</span>
                    </label>
                </div>
                <div class="mw_clear"></div>
                <div class="vSpace"></div>
               <!-- <div onmousedown="mw.switcher._switch(this);" class="mw-switcher mw-switcher-green unselectable <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?>mw-switcher-on<?php endif; ?>"> <span class="mw-switch-handle"></span>

                </div>-->
                </label>
                <div class="mw-set-payment-gw-options" >
                  <module type="<?php print $payment_module['module'] ?>" view="admin" />
                </div>
              </div>
            </div>
            <?php endforeach ; ?>
            <?php endif; ?>
          </div>
          <hr>
          <h2>Currency settings</h2>
          <?php ?>
          <?php $cur = get_option('currency', 'payments');  ?>
          <?php $curencies = curencies_list(); ?>
          <?php if(isarr($curencies )): ?>
          <div class="mw-ui-select">
            <select name="currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
              <?php foreach($curencies  as $item): ?>
              <option  value="<?php print $item[1] ?>" <?php if($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
              <?php endforeach ; ?>
            </select>
          </div>
          <?php endif; ?>
          <module type="shop/payments/currency_render" id="mw_curr_rend" />
        </div>
        <div class="otab">
          <h2>Send email to the client on new order</h2>
          <label class="mw-ui-check">
            <input name="order_email_enabled" class="mw_option_field"    data-option-group="orders"  value="y"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') == 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span><span>Yes</span></label>
          <label class="mw-ui-check">
            <input name="order_email_enabled" class="mw_option_field"     data-option-group="orders"  value="n"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') != 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span><span>No</span></label>
          <br />
          <small>You must have a working email setup in order to send emails. <a class="mw-ui-link" target="_blank"  href="<?php  print admin_url('view:settings#option_group=email'); ?>" style="padding: 6px;">Setup email here.</a></small>
          <label class="mw-ui-label">Email subject</label>
          <input name="order_email_subject" class="mw-ui-field mw_option_field"   id="order_email_subject"  placeholder="Thank you for your order!" data-option-group="orders"  value="<?php print get_option('order_email_subject', 'orders') ?>"  type="text" />
          <label class="mw-ui-label">Send copy email to</label>
          <input name="order_email_cc" class="mw-ui-field mw_option_field"   id="order_email_cc" placeholder="me@email.com"  data-option-group="orders"  value="<?php print get_option('order_email_cc', 'orders') ?>"  type="text" />
          <a class="mw-ui-btn mw-ui-btn-small" href="javascript:$('#test_ord_eml_toggle').toggle(); void(0);">[test]</a></small>
          <table width=" 100%" border="0" id="test_ord_eml_toggle" style="display:none">
            <tr>
              <td><label class="mw-ui-label">Send test email to</label>
                <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<?php print get_option('test_email_to','email'); ?>"  />
                <div class="vSpace"></div>
                <span onclick="mw.checkout_confirm_email_test();" class="mw-ui-btn mw-ui-btn-green" id="email_send_test_btn">Send test email</span></td>
              <td><pre id="email_send_test_btn_output"></pre></td>
            </tr>
          </table>
          <label class="mw-ui-label">Email content</label>
          <textarea class="mw-ui-field mw_option_field"   data-option-group="orders" id="order_email_content" name="order_email_content"><?php print get_option('order_email_content', 'orders') ?></textarea>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="vSpace"></div>
