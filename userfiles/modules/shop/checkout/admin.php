<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


__checkout_options_save_msg = function(){
	 if(mw.notification != undefined){
			 mw.notification.success('Checkout updated!');
	 }
	 
	 
	 if (window.parent.mw != undefined && window.parent.mw.reload_module != undefined) {
	 
	window.parent.mw.reload_module("#<?php print $params['id'] ?>");
	 }
	
}

$(document).ready(function(){
    mw.options.form('.mw-set-checkout-options-swticher', __checkout_options_save_msg);
});




</script> <div class="mw_simple_tabs mw_tabs_layout_simple">
	<ul style="margin: 0;" class="mw_simple_tabs_nav">
		<li><a class="active" href="javascript:;"><?php _e("Settings"); ?></a></li>
	</ul>
	<div class="tab mw-set-checkout-options-swticher">
<label class="mw-ui-label"><strong>
	<?php _e("Show shopping cart in checkout?"); ?>
	</strong>
	<?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
	<select name="data-show-cart"  class="mw_option_field"  >
		<option value="y"  <?php if(('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("Yes"); ?>
		</option>
		<option value="n"  <?php if(('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("No"); ?>
		</option>
	</select>
</label>
<label class="mw-ui-label"> <strong>
	<?php _e("Show shipping?"); ?>
	</strong>
	<?php $cart_show_enanbled =  get_option('data-show-shipping', $params['id']); ?>
	<select name="data-show-shipping"  class="mw_option_field"  >
		<option value="y"  <?php if(('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("Yes"); ?>
		</option>
		<option value="n"  <?php if(('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("No"); ?>
		</option>
	</select>
</label>
<label class="mw-ui-label"> <strong>
	<?php _e("Show payments?"); ?>
	</strong>
	<?php $cart_show_enanbled =  get_option('data-show-payments', $params['id']); ?>
	<select name="data-show-payments"  class="mw_option_field"  >
		<option value="y"  <?php if(('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("Yes"); ?>
		</option>
		<option value="n"  <?php if(('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>>
		<?php _e("No"); ?>
		</option>
	</select>
</label>
</div></div>