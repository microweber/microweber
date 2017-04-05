<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


__shipping_options_save_msg = function(){
	 if(mw.notification != undefined){
			 mw.notification.success('Shipping options are saved!');
	 }
	 mw.reload_module_parent('shop/shipping');
	
}

shippingToCountryClass = function(){
    var val = mw.$('[data-option-group="shipping"]:checked').val();
    if(val == 'y'){
      mw.$("#set-shipping-to-country").removeClass('mw-ui-box-warn').addClass('mw-ui-box-notification');
    }
    else{
        mw.$("#set-shipping-to-country").removeClass('mw-ui-box-notification').addClass('mw-ui-box-warn');
    }
	 
	
	
	
}

$(document).ready(function(){
    mw.options.form('.mw-set-shipping-options-swticher', __shipping_options_save_msg);
});
</script>
<div class="section-header">
    <h2><span class="mw-icon-truck"></span><?php _e("Shipping"); ?></h2>
</div>
<?php 
    $here = dirname(__FILE__).DS.'gateways'.DS;
   // $shipping_modules = scan_for_modules("cache_group=modules/global/shipping&dir_name={$here}"); 
	
	 $shipping_modules = get_modules("type=shipping_gateway");
?>
<div class="mw-set-shipping-options mw-admin-wrap">
	<div class="mw-ui-box">
		<?php if(is_array($shipping_modules )): ?>
		<?php foreach($shipping_modules  as $shipping_module): ?>
		<?php if(mw()->modules->is_installed( $shipping_module['module'] )): ?>
		<div class="mw-ui-box-header mw-set-shipping-options-swticher">
          <h4 class="pull-left">
          <span>
          <?php print $shipping_module['name'] ?>
  		    <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> &nbsp;<small class="mw-ui-label-help" style="display:inline;"><em>(<?php _e('inactive'); ?>)</em></small><?php endif; ?>
  		  </span></h4>




          <?php

          $status = get_option('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y' ? 'notification' : 'warn';

          ?>
            <div class="mw-ui-box mw-ui-box-content mw-ui-box-<?php print $status; ?> pull-right" style="clear: none;padding: 5px 10px;margin-left:12px;" id="set-shipping-to-country">

            <ul class="mw-ui-inline-list">
                <li>
                    <label class="mw-ui-check">
    					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" onchange="shippingToCountryClass()" data-option-group="shipping" value="y" type="radio" <?php if($status == 'notification'): ?> checked="checked" <?php endif; ?> />
                        <span></span>
                        <span><?php _e("Yes"); ?></span>
                    </label>
                </li>
                <li>
                     <label class="mw-ui-check">
    					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" onchange="shippingToCountryClass()" data-option-group="shipping" value="n" type="radio" <?php if($status == 'warn'): ?> checked="checked" <?php endif; ?> />
                        <span></span>
                        <span><?php _e("No"); ?></span>
                    </label>
                </li>
            </ul>

            </div>

            <label class="mw-ui-label-inline pull-right" style="margin-top: 7px;"><?php _e("Activated?"); ?></label>

		</div>
        <div class="mw-set-shipping-gw-options">
            <module type="<?php print $shipping_module['module'] ?>" view="admin" />
        </div>
		<?php endif; ?>
		<?php endforeach ; ?>
		<?php endif; ?>
	</div>
</div>
