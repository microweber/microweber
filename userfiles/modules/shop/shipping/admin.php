<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


__shipping_options_save_msg = function(){
	 if(mw.notification != undefined){
			 mw.notification.success('Shipping options are saved!');
	 }
	
}

$(document).ready(function(){
    mw.options.form('.mw-set-shipping-options-swticher', __shipping_options_save_msg);
});
</script>
<div class="section-header">
    <h2><?php _e("Shipping"); ?></h2>
</div>
<?php 
    $here = dirname(__FILE__).DS.'gateways'.DS;
    $shipping_modules = scan_for_modules("cache_group=modules/global/shipping&dir_name={$here}");
?>
<div class="mw-set-shipping-options mw-admin-wrap">
	<div class="mw-ui-box">
		<?php if(is_array($shipping_modules )): ?>
		<?php foreach($shipping_modules  as $shipping_module): ?>
		<?php if(mw('module')->is_installed( $shipping_module['module'] )): ?>
		<div class="mw-ui-box-header mw-set-shipping-options-swticher">
          <h4 class="pull-left"><span class="mw-icon-truck"></span>
          <span>
          <?php print $shipping_module['name'] ?>
  		    <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> &nbsp;<small class="mw-ui-label-help" style="display:inline;"><em>(inactive)</em></small><?php endif; ?>
  		  </span></h4>
            <ul class="mw-ui-inline-list pull-right">
                <li><?php _e("Activated?"); ?></li>
                <li>
                    <label class="mw-ui-check">
    					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="y" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <?php endif; ?> />
                        <span></span>
                        <span><?php _e("Yes"); ?></span>
                    </label>
                </li>
                <li>
                     <label class="mw-ui-check">
    					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="n" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <?php endif; ?> />
                        <span></span>
                        <span><?php _e("No"); ?></span>
                    </label>
                </li>
            </ul>
		</div>
        <div class="mw-set-shipping-gw-options">
            <module type="<?php print $shipping_module['module'] ?>" view="admin" />
        </div>
		<?php endif; ?>
		<?php endforeach ; ?>
		<?php endif; ?>
	</div>
</div>
