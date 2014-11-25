<script  type="text/javascript">
mw.require('forms.js');
mw.require('options.js');

</script>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('#shipping-units-setup', function(){
      mw.notification.success("<?php _e("Shipping units are saved!"); ?>");
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


<div id="shipping-units-setup" class="mw-ui-box-content">
	<h2>
		<?php _e("Shipping units"); ?>
	</h2>

    <div class="mw-ui-box mw-ui-box-content">

	<label class="mw-ui-label">
		<?php _e("Units for weight"); ?>
	</label>


	<div class="mw-ui-check-selector">
        <label class="mw-ui-check">
    		<input name="shipping_weight_units" class="mw_option_field"  data-option-group="orders"  value="kg"  type="radio"  <?php if(get_option('shipping_weight_units', 'orders') == 'kg'): ?> checked="checked" <?php endif; ?> >
    		<span></span><span>
    		<?php _e("kilograms (kg)"); ?>
    		</span>
        </label>
    	  <label class="mw-ui-check">
    		<input name="shipping_weight_units" class="mw_option_field"     data-option-group="orders"  value="lb"  type="radio"  <?php if(get_option('shipping_weight_units', 'orders') == 'lb'): ?> checked="checked" <?php endif; ?> >
    		<span></span><span>
    		<?php _e("pound (lb)"); ?>
    		</span>
        </label>
    </div>

        <hr>


	<label class="mw-ui-label">
		<?php _e("Units for size"); ?>
	</label>

    <div class="mw-ui-check-selector">
    	<label class="mw-ui-check">
    		<input name="shipping_size_units" class="mw_option_field"    data-option-group="orders"  value="cm"  type="radio"  <?php if(get_option('shipping_size_units', 'orders') == 'cm'): ?> checked="checked" <?php endif; ?> >
    		<span></span><span>
    		<?php _e("centimeters (cm)"); ?>
    		</span>
        </label>
    	<label class="mw-ui-check">
    		<input name="shipping_size_units" class="mw_option_field"     data-option-group="orders"  value="in"  type="radio"  <?php if(get_option('shipping_size_units', 'orders') == 'in'): ?> checked="checked" <?php endif; ?> >
    		<span></span><span>
    		<?php _e("inches (in)"); ?>
    		</span>
        </label>
    </div>
    </div>
</div>
