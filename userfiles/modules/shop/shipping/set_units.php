<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("All changes are saved"); ?>.");
    });
});
</script>
<script  type="text/javascript">
       $(document).ready(function(){
          if(self !== parent && typeof _binded === 'undefined'){
              _binded = true;
              $(mwd.body).ajaxStop(function(){
				  	if(parent != undefined && parent.mw != undefined){
                    parent.mw.reload_module("shop/shipping/gateways/country");
					}
              });
          }
       });
</script>

<div class="shipping-units-setup">
	<h2>
		<?php _e("Shipping units"); ?>
	</h2>
	<label class="mw-ui-label">
		<?php _e("Units for weight"); ?>
	</label>
	<label class="mw-ui-check">
		<input name="shipping_weight_units" class="mw_option_field"  data-option-group="orders"  value="kg"  type="radio"  <?php if(get_option('shipping_weight_units', 'orders') == 'kg'): ?> checked="checked" <?php endif; ?> >
		<span></span><span>
		<?php _e("kilograms (kg)"); ?>
		</span></label>
	<label class="mw-ui-check">
		<input name="shipping_weight_units" class="mw_option_field"     data-option-group="orders"  value="lb"  type="radio"  <?php if(get_option('shipping_weight_units', 'orders') == 'lb'): ?> checked="checked" <?php endif; ?> >
		<span></span><span>
		<?php _e("pound (lb)"); ?>
		</span></label>
	<label class="mw-ui-label">
		<?php _e("Units for size"); ?>
	</label>
	<label class="mw-ui-check">
		<input name="shipping_size_units" class="mw_option_field"    data-option-group="orders"  value="cm"  type="radio"  <?php if(get_option('shipping_size_units', 'orders') == 'cm'): ?> checked="checked" <?php endif; ?> >
		<span></span><span>
		<?php _e("centimeters (cm)"); ?>
		</span></label>
	<label class="mw-ui-check">
		<input name="shipping_size_units" class="mw_option_field"     data-option-group="orders"  value="in"  type="radio"  <?php if(get_option('shipping_size_units', 'orders') == 'in'): ?> checked="checked" <?php endif; ?> >
		<span></span><span>
		<?php _e("inches (in)"); ?>
		</span></label>
</div>
