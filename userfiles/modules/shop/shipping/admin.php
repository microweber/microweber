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
<?php 

$here = dirname(__FILE__).DS.'gateways'.DS;
 $shipping_modules = scan_for_modules("cache_group=modules/global/shipping&dir_name={$here}");
  
//$shipping_modules = mw('module')->get('debug=1&ui=any&module=shop\shipping\gateways\*');
 
?>

<div class="mw-set-shipping-options mw-admin-wrap">
	<div class="mw-o-box" style="background: #F8F8F8;">
		<?php if(is_array($shipping_modules )): ?>
		<?php foreach($shipping_modules  as $shipping_module): ?>
		<?php if(mw('module')->is_installed( $shipping_module['module'] )): ?>
		<div class="mw-o-box-header mw-set-shipping-options-swticher"> <span class="ico itruck"></span><span><strong><?php print $shipping_module['name'] ?></strong>
		
		
		<?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> &nbsp;<small class="mw-ui-label-help" style="display:inline;"><em>(inactive)</em></small><?php endif; ?>
		</span>
			<div onmousedown="mw.switcher._switch(this);" class="mw-switcher mw-switcher-green unselectable right <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?>mw-switcher-off<?php else: ?>mw-switcher-on<?php endif; ?>"> <span class="mw-switch-handle"></span>
				<label>
					<?php _e("Yes"); ?>
					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="y" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') == 'y'): ?> checked="checked" <?php endif; ?> />
				</label>
				<label>
					<?php _e("No"); ?>
					<input name="shipping_gw_<?php print $shipping_module['module'] ?>" class="mw_option_field" data-option-group="shipping" value="n" type="radio" <?php if(get_option('shipping_gw_'.$shipping_module['module'], 'shipping') != 'y'): ?> checked="checked" <?php endif; ?> />
				</label>
			</div>
			<label class="mw-ui-label right" style="margin-right: 12px;">
				<?php _e("Activated?"); ?>
				: </label>
		</div>
		<div style="padding: 15px;">
			<div class="mw-set-shipping-gw-options">
				<module type="<?php print $shipping_module['module'] ?>" view="admin" />
			</div>
		</div>
		<?php endif; ?>
		<?php endforeach ; ?>
		<?php endif; ?>
	</div>
</div>
