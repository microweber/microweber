<?php

if(!is_admin()){
    return;
}

?>
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
    mw.notification.success("<?php _e("Shop settings are saved"); ?>.");

	 mw.reload_module_parent("shop/payments");



  });




$('.mw-admin-wrap').click(function(){
	// mw.options.form('.mw-set-payment-options');
  });


    mw.tools.tabGroup({
       nav:'.payment-tab',
       tabs:'.otab',
       master: mwd.querySelector('.mw-admin-side-nav')
    });





var email_ed =  mw.editor("#order_email_content" , {modules:'shop/orders/editor_dynamic_values'});


mw.$("#available_providers").sortable({
  items:".mw-ui-box",
  handle:".mw-icon-drag",
  axis:"y",
  placeholder: "available_providers_placeholder",
  start:function(a,b){

    $(this).find(".mw-ui-box").each(function(){
      $(this).height("auto");
      $(this).removeClass("mw-accordion-active");
      $(this).removeClass("active");
      $(this).find(".mw-ui-box-content").hide();
    });
    $(this).sortable("refreshPositions");

  },
  	update: function(){
          var serial = $(this).sortable('serialize');
          $.ajax({
            url: mw.settings.api_url+'module/reorder_modules',
            type:"post",
            data:serial
          });
        },
  stop:function(){
  //  Alert("<?php _e("Saving"); ?> ... ");
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
.mw-set-payment-options .mw-ui-label {
	padding-bottom: 5px;
	padding-top: 10px;
	clear: both
}
.payment-state-status {
	padding: 12px 12px 5px;
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

.mw-ui-box-header .mw-icon-drag {
	visibility: hidden;
}
.mw-ui-box-header:hover .mw-icon-drag {
	visibility: visible;
}
.available_providers_placeholder {
	border: 2px dashed #ccc;
	background:transparent;
	height: 50px;
	margin: 10px 0;
	position: relative;
}
.gateway-icon-title > .mw-ui-row{
  width: auto;
}
.gateway-icon-title > .mw-ui-row *{
  vertical-align: middle;
}

.gateway-icon-title > .mw-ui-row img{
  max-width: 100px;
  max-height: 30px;
}
.gateway-icon-title > .mw-ui-row .mw-ui-col{
    padding-right: 15px;
}

.gateway-icon-title > .mw-ui-row .mw-icon-drag{
  font-size: 19px;
  color:#808080 ;
  cursor: move;
  cursor: grab;
  cursor: -moz-grab;
  cursor: -webkit-moz-grab;
}
.otab{
  padding-right: 10px;
}

#available_providers .mw-ui-box-header{
  cursor: pointer;
}

#available_providers > .mw-ui-box{
  margin-bottom: 15px;
}

@media (max-width: 767px) {
.otab{
  padding-left: 10px;
}
}

</style>
<?php
$here = dirname(__FILE__).DS.'gateways'.DS;


$payment_modules = get_modules('type=payment_gateway');
if($payment_modules == false){
$payment_modules = scan_for_modules("cache_group=modules/global&dir_name={$here}");

}


?>


<div class="mw-admin-wrap">
  <div class="mhas-options-bar">

    <div class="mw-ui-box-content" style="padding: 0;">
      <div class="mw-ui-row">
        <div class="mw-ui-col" style="width: 200px;">
          <div class="mw-ui-col-container">
            <div class="options-bar" style="margin-right: 0;">
              <div class="mw-ui-sidenav" style="margin-top: 15px;">
                <ul>
                  <li><a class="payment-tab active" href="javascript:;">
                    <?php _e("Payments"); ?>
                    </a></li>
                  <li><a class="payment-tab" href="javascript:;">
                    <?php _e("Emails for order"); ?>
                    </a></li>
                  <li><a class="payment-tab" href="javascript:;">
                    <?php _e("Shipping Units"); ?>
                    </a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="mw-ui-col">
          <div class="mw-ui-col-container">
            <div class="mw-set-payment-options">
              <div class="otab" style="display: block">
                <h2>
                  <?php _e("Payment providers"); ?>
                </h2>
                <?php if(is_array($payment_modules )): ?>
                <div class="mw_simple_tabs mw_tabs_layout_stylish" id="available_providers">
                  <?php foreach($payment_modules  as $payment_module): ?>
                  <?php
    			                 $module_info = (module_info($payment_module['module']));
                                 if(!isset($module_info['id']) or $module_info['id'] == false){
                                	$module_info['id'] = 0;
                                 }
			                ?>
                  <div class="mw-ui-box mw-ui-box-accordion mw-accordion-active" id="module-db-id-<?php print $module_info['id'] ?>">
                    <div class="mw-ui-box-header"  onmousedown="mw.tools.accordion(this.parentNode);">
                      <div class="gateway-icon-title">
                        <div class="mw-ui-row">
                            <div class="mw-ui-col"><span class="mw-icon-drag"></span></div>
                            <div class="mw-ui-col">
                               <img src="<?php print $payment_module['icon']; ?>" alt="" />
                            </div>
                            <div class="mw-ui-col">
                               <span class="gateway-title"><?php print $payment_module['name'] ?>
                        <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?>
                        <small class="mw-small">(disabled)</small>
                        <?php endif; ?>
                        </span>
                            </div>
                        </div>
                        </div>
                      <!--  <span class="ico ireport"></span><span><?php print $payment_module['name'] ?></span> -->

                    </div>
                    <div class="mw-ui-box-content mw-accordion-content">
                      <label class="mw-ui-label">
                      <h3><?php print $payment_module['name'] ?>:</h3>
                      <div class="mw-ui-box payment-state-status <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?>active<?php endif; ?>">
                        <div class="mw-ui-check-selector">
                          <label class="mw-ui-check">
                            <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="y"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
                            <span></span> <span class="first">
                            <?php _e("Enabled"); ?>
                            </span> </label>
                          <label class="mw-ui-check">
                            <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="n"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
                            <span></span> <span class="second">
                            <?php _e("Disabled"); ?>
                            </span> </label>
                        </div>
                      </div>
                      </label>
                      <div class="mw-set-payment-gw-options" >
                        <module type="<?php print $payment_module['module'] ?>" view="admin" />
                      </div>
                    </div>
                  </div>
                  <?php endforeach ; ?>
                  <?php endif; ?>
                </div>

                <h4>
                 Purchasing requires registration
                </h4>
                <label class="mw-ui-check">
                  <input name="shop_require_registration" class="mw_option_field"     data-option-group="website"  value="n"  type="radio"  <?php if(get_option('shop_require_registration', 'website') != 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="shop_require_registration" class="mw_option_field"    data-option-group="website"  value="y"  type="radio"  <?php if(get_option('shop_require_registration', 'website') == 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>
                                  

                <hr>
                <h2>Currency settings</h2>
                <?php ?>
                <?php $cur = get_option('currency', 'payments');  ?>
                <?php $curencies = mw('shop')->currency_get();  ?>
                <?php if(is_array($curencies )): ?>
                <select name="currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
                  <?php foreach($curencies  as $item): ?>
                  <option  value="<?php print $item[1] ?>" <?php if($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
                  <?php endforeach ; ?>
                </select>
                <?php endif; ?>
                <module type="shop/payments/currency_render" id="mw_curr_rend" />
                
                <h2>Checkout URL</h2>
                <?php ?>
                <?php $checkout_url = get_option('checkout_url', 'shop');  ?>
                <input name="checkout_url"  class="mw_option_field mw-ui-field"   type="text" option-group="shop"   value="<?php print get_option('checkout_url','shop'); ?>" placeholder="Use default"  />
                
                <h4>
                  <?php _e("Disable online shop"); ?>
                </h4>
                <label class="mw-ui-check">
                  <input name="shop_disabled" class="mw_option_field"     data-option-group="website"  value="n"  type="radio"  <?php if(get_option('shop_disabled', 'website') != 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="shop_disabled" class="mw_option_field"    data-option-group="website"  value="y"  type="radio"  <?php if(get_option('shop_disabled', 'website') == 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>
                <br />
                <small>
                <?php _e("You can aways enable it"); ?>
                <a class="mw-ui-link"   href="<?php  print admin_url('view:settings#option_group=shop__payments__admin'); ?>" >
                <?php _e("here"); ?>
                </a></small> </div>
              <div class="otab">
                <h2>
                  <?php _e("Send email to the client on new order"); ?>
                </h2>
                <label class="mw-ui-check">
                  <input name="order_email_enabled" class="mw_option_field"    data-option-group="orders"  value="y"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') == 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="order_email_enabled" class="mw_option_field"     data-option-group="orders"  value="n"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') != 'y'): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <br />
                <small>
                <?php _e("You must have a working email setup in order to send emails"); ?>
                . <a class="mw-ui-link" target="_blank"  href="<?php  print admin_url('view:settings#option_group=email'); ?>" style="padding: 6px;">
                <?php _e("Setup email here"); ?>
                .</a></small>
                <div class="mw-ui-row">
                  <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                      <label class="mw-ui-label">
                        <?php _e("Email subject"); ?>
                      </label>
                      <input name="order_email_subject" class="mw-ui-field mw_option_field"   id="order_email_subject"  placeholder="<?php _e("Thank you for your order"); ?>!" data-option-group="orders"  value="<?php print get_option('order_email_subject', 'orders') ?>"  type="text" />
                    </div>
                  </div>
                  <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                      <label class="mw-ui-label">
                        <?php _e("Send copy email to"); ?>
                      </label>
                      <input name="order_email_cc" class="mw-ui-field mw_option_field"  style="float: left;margin-right:10px;"  id="order_email_cc" placeholder="me@email.com"  data-option-group="orders"  value="<?php print get_option('order_email_cc', 'orders') ?>"  type="text" />
                    </div>
                  </div>
                </div>
                
                <a class="mw-ui-btn mw-ui-btn-link" href="javascript:void(0);" onclick="$('#test_ord_eml_toggle').show();$(this).hide();">
                <?php _e("Test"); ?>
                </a>
                <table width=" 100%" border="0" id="test_ord_eml_toggle" style="display:none">
                  <tr>
                    <td><label class="mw-ui-label">
                        <?php _e("Send test email to"); ?>
                      </label>
                      <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<?php print get_option('test_email_to','email'); ?>"  />

                      <span onclick="mw.checkout_confirm_email_test();" class="mw-ui-btn mw-ui-btn-green" id="email_send_test_btn">
                      <?php _e("Send test email"); ?>
                      </span></td>
                    <td><pre id="email_send_test_btn_output"></pre></td>
                  </tr>
                </table>
                <label class="mw-ui-label">
                  <?php _e("Email content"); ?>
                </label>
                <textarea class="mw-ui-field mw_option_field"   data-option-group="orders" id="order_email_content" name="order_email_content" style="width:100%;height:450px;"><?php print get_option('order_email_content', 'orders') ?></textarea>
              </div>
              <div class="otab">
                <module type="shop/shipping/set_units" id="mw_set_shipping_units" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

