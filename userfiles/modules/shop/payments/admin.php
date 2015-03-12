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
    if(el.value == 1) {
       $(mw.tools.firstParentWithClass(el, 'payment-state-status')).addClass("active");
    }
    else{
       $(mw.tools.firstParentWithClass(el, 'payment-state-status')).removeClass("active");
    }
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
       master: mwd.querySelector('.mw-admin-side-nav'),
       onclick:function(){
         if(this.id == 'payment-tab-email' && !window.MailEditor){
             runMailEditor();
         }
       }
    });



runMailEditor = function(){
    if(!window.MailEditor){
        MailEditor = mw.editor({
            element:"#order_email_content",
            addControls:mwd.getElementById('editorctrls').innerHTML,
            ready:function(content){
              content.defaultView.mw.dropdown();
              mw.$("#dynamic_vals li", content).bind('click', function(){
                  MailEditor.api.insert_html($(this).attr('value'));
              });
            }
        });

        $(MailEditor).bind('change', function(){
          d(this.value)
        })
    }
}





mw.$("#available_providers").sortable({
  items:".mw-ui-box",
  handle:".mw-icon-drag",
  axis:1,
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


<div id="editorctrls" style="display: none">

<span class="mw_dlm"></span>
<div style="width: 112px;" data-value="" title="<?php _e("These values will be replaced with the actual content"); ?>" id="dynamic_vals" class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown-type-wysiwyg_blue mw_dropdown_action_dynamic_values">
    <span class="mw-dropdown-value">
        <span class="mw-dropdown-arrow"></span>
        <span class="mw-dropdown-val"><?php _e("E-mail Values"); ?></span>
    </span>
  <div class="mw-dropdown-content">
      <ul>
        <li value="{id}"><a href="javascript:;"><?php _e("Order ID"); ?></a></li>
        <li value="{cart_items}"><a href="javascript:;"><?php _e("Cart items"); ?></a></li>
        <li value="{amount}"><a href="javascript:;"><?php _e("Amount"); ?></a></li>
        <li value="{order_status}"><a href="javascript:;"><?php _e("Order Status"); ?></a></li>
        <li value="{email}"><a href="javascript:;"><?php _e("Email"); ?></a></li>
        <li value="{currency}"><a href="javascript:;"><?php _e("Currency Code"); ?></a></li>
        <li value="{first_name}"><a href="javascript:;"><?php _e("First Name"); ?></a></li>
        <li value="{last_name}"><a href="javascript:;"><?php _e("Last Name"); ?></a></li>
        <li value="{email}"><a href="javascript:;"><?php _e("Email"); ?></a></li>
        <li value="{country}"><a href="javascript:;"><?php _e("Country"); ?></a></li>

        <li value="{city}"><a href="javascript:;"><?php _e("City"); ?></a></li>
        <li value="{state}"><a href="javascript:;"><?php _e("State"); ?></a></li>
        <li value="{zip}"><a href="javascript:;"><?php _e("ZIP/Post Code"); ?></a></li>
        <li value="{address}"><a href="javascript:;"><?php _e("Address"); ?></a></li>
        <li value="{phone}"><a href="javascript:;"><?php _e("Phone"); ?></a></li>
      </ul>
    </div>
</div>

</div>


<style>
.mw-set-payment-options{
  padding-left: 30px;
}

.admin-side-box{
  padding-top: 19px;
}



.mw-set-payment-options #shipping-units-setup{
  padding: 20px 0 0;
}



.otab {
	display: none;
}
#order_email_subject,
#test_email_to,
#order_email_cc{
	width:100%;
}
#mail-test-btn{
  float: right;
  margin-top: 15px;
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
	-webkit-transition: all 200ms;
	-moz-transition: all 200ms;
	-o-transition: all 200ms;
	transition: all 200ms;
    border:none;
}
.payment-state-status {
	background: #F27E54;
	color: white;
}
.payment-state-status.active {
	background: #48ad79;
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
#test_ord_eml_toggle{
  padding-bottom: 20px;
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



?>


<div class="mw-ui-row">
        <div class="mw-ui-col" style="width: 200px;">
          <div class="mw-ui-col-container">
            <div class="fixed-side-column" >
               <div class="admin-side-box"><h2 class="mw-side-main-title"><span class="mw-icon-gear"></span><?php _e("Options"); ?></h2></div>
              <div class="mw-ui-sidenav" style="margin-top: 15px;">
                <ul>
                  <li><a class="payment-tab active" href="javascript:;">
                    <?php _e("Payments"); ?>
                    </a></li>
                  <li><a class="payment-tab" id="payment-tab-email" href="javascript:;">
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
                <div class="section-header">
                    <h2>
                      <?php _e("Payment providers"); ?>
                    </h2>
                </div>
                <?php if(is_array($payment_modules )): ?>
                <div class="mw_simple_tabs mw_tabs_layout_stylish" id="available_providers">
                  <?php foreach($payment_modules  as $payment_module): ?>
                  <?php



    			                    //$module_info = (module_info($payment_module['module']));
    			                    $module_info = ($payment_module);
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
                        <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 1): ?>
                        <small class="mw-small">(disabled)</small>
                        <?php endif; ?>
                        </span>
                            </div>
                        </div>
                        </div>
                      <!--  <span class="ico ireport"></span><span><?php print $payment_module['name'] ?></span> -->

                    </div>
                    <div class="mw-ui-box-content mw-accordion-content">

                      <div class="mw-ui-row">
                          <div class="mw-ui-col"><h3><?php print $payment_module['name'] ?>:</h3></div>
                          <div class="mw-ui-col">
                              <div class="mw-ui-box payment-state-status <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 1): ?>active<?php endif; ?> pull-right">
                        <div class="mw-ui-check-selector">
                          <label class="mw-ui-check">
                            <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"    data-option-group="payments"  value="1"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') == 1): ?> checked="checked" <?php endif; ?> >
                            <span></span> <span class="first">
                            <?php _e("Enabled"); ?>
                            </span> </label>
                          <label class="mw-ui-check">
                            <input onchange="setActiveProvider(this);" name="payment_gw_<?php print $payment_module['module'] ?>" class="mw_option_field"     data-option-group="payments"  value="0"  type="radio"  <?php if(get_option('payment_gw_'.$payment_module['module'], 'payments') != 1): ?> checked="checked" <?php endif; ?> >
                            <span></span> <span class="second">
                            <?php _e("Disabled"); ?>
                            </span> </label>
                        </div>
                      </div>
                          </div>
                      </div>


                      <div class="mw-set-payment-gw-options" >
                        <module type="<?php print $payment_module['module'] ?>" view="admin" />
                      </div>
                    </div>
                  </div>
                  <?php endforeach ; ?>
                  <?php endif; ?>
                </div>
                
                
                 <hr>
                <h4>
                 <?php _e("Users must agree to Terms and Conditions"); ?>
                </h4>
                <label class="mw-ui-check" style="margin-right: 15px;">
                  <input name="shop_require_terms" class="mw_option_field"     data-option-group="website"  value="0"  type="radio"  <?php if(get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="shop_require_terms" class="mw_option_field"    data-option-group="website"  value="1"  type="radio"  <?php if(get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>
                
                
                 <hr>
                <h4>
                 <?php _e("Purchasing requires registration"); ?>
                </h4>
                <label class="mw-ui-check" style="margin-right: 15px;">
                  <input name="shop_require_registration" class="mw_option_field"     data-option-group="website"  value="0"  type="radio"  <?php if(get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="shop_require_registration" class="mw_option_field"    data-option-group="website"  value="1"  type="radio"  <?php if(get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>


                <hr>

                <module type="shop/payments/currency" id="mw_curr_select" />


                <h2><?php _e("Checkout URL"); ?></h2>
                <?php ?>
                <?php $checkout_url = get_option('checkout_url', 'shop');  ?>
                <input name="checkout_url"  class="mw_option_field mw-ui-field"   type="text" option-group="shop"   value="<?php print get_option('checkout_url','shop'); ?>" placeholder="Use default"  />
                <h4>
                  <?php _e("Disable online shop"); ?>
                </h4>
                <label class="mw-ui-check" style="margin-right: 15px;">
                  <input name="shop_disabled" class="mw_option_field"     data-option-group="website"  value="n"  type="radio"  <?php if(get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span>
                </label>
                <label class="mw-ui-check">
                  <input name="shop_disabled" class="mw_option_field"    data-option-group="website"  value="y"  type="radio"  <?php if(get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
                  <span></span>
                  <span>
                  <?php _e("Yes"); ?>
                  </span>
                </label>
                <br />
                </div>
              <div class="otab">
                <div class="section-header">
                  <h2>
                    <?php _e("Send email to the client on new order"); ?>
                  </h2>
                </div>
                <label class="mw-ui-check" style="margin-right: 15px;">
                  <input name="order_email_enabled" class="mw_option_field"    data-option-group="orders"  value="1"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') == 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("Yes"); ?>
                  </span></label>
                <label class="mw-ui-check">
                  <input name="order_email_enabled" class="mw_option_field"     data-option-group="orders"  value="0"  type="radio"  <?php if(get_option('order_email_enabled', 'orders') != 1): ?> checked="checked" <?php endif; ?> >
                  <span></span><span>
                  <?php _e("No"); ?>
                  </span></label>
                <br />
                <small class="mw-ui-label-help">
                <?php _e("You must have a working email setup in order to send emails"); ?>
                . <a class="mw-ui-btn mw-ui-btn-small" target="_blank"  href="<?php  print admin_url('view:settings#option_group=email'); ?>">
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

                <a class="mw-ui-btn pull-right" id="mail-test-btn" href="javascript:void(0);" onclick="$('#test_ord_eml_toggle').show();$(this).hide();">
                <?php _e("Test"); ?>
                </a>
                <div id="test_ord_eml_toggle" style="display:none"><div class="mw-ui-row valign-bottom">
                  <div class="mw-ui-col">
                  <div class="mw-ui-col-container">
                      <label class="mw-ui-label">
                        <?php _e("Send test email to"); ?>
                      </label>
                      <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field"   type="text" option-group="email"   value="<?php print get_option('test_email_to','email'); ?>"  />

                      </div>
                      </div>
                    <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                    <span onclick="mw.checkout_confirm_email_test();" class="mw-ui-btn mw-ui-btn-green pull-left" id="email_send_test_btn">
                      <?php _e("Send test email"); ?>
                      </span>
                    <pre id="email_send_test_btn_output">
                    </pre>
                  </div>
                  </div>
                </div>
                </div>
                <label class="mw-ui-label">
                  <?php _e("Email content"); ?>
                </label>
                <textarea class="mw-ui-field mw_option_field"  data-option-group="orders" id="order_email_content" name="order_email_content"><?php print get_option('order_email_content', 'orders') ?></textarea>
              </div>
              <div class="otab">
                <module type="shop/shipping/set_units" id="mw_set_shipping_units" />
              </div>
            </div>
          </div>
        </div>
      </div>

