<?php 

if(!is_admin()){
return;	
}



$cont_id = 0;

if(isset($params['content-id'])){
//	d($params);
}








?>

<a href="javascript:;" class="mw-ui-more" onclick="mw.tools.toggle('#mw-admin-product-shipping-options', this);" id="shipping-fields-toggler"
    data-for='#mw-admin-product-shipping-options'>
<?php _e("Shipping options"); ?>
</a>
<div id="mw-admin-product-shipping-options">
	<label class="mw-ui-label">Free Shipping</label>
	<span class="mw-onoff" onclick="$(this).toggleClass('active');"> <i>OFF</i> <i>ON</i> </span>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">Width (Optional) Inches</label>
		<span class="mwsico-width"></span>
		<input type="text" name="shipping_width" class="mw-ui-field" />
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">Height (Optional) Inches</label>
		<span class="mwsico-height"></span>
		<input type="text" name="shipping_height" class="mw-ui-field" />
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">Depth (Optional) Inches </label>
		<span class="mwsico-depth"></span>
		<input type="text" name="shipping_depth" class="mw-ui-field" />
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label"> Weight (Optional) </label>
		<span class="mwsico-weight"></span>
		<input type="text" name="shipping_weight" class="mw-ui-field" />
	</div>
	<div class="mw-ui-field-holder">
		<label class="mw-ui-label">Fixed Shipping Cost (Optional) $</label>
		<span class="mwsico-cost"></span>
		<input type="text" name="shipping_cost" class="mw-ui-field left" />
	</div>
</div>
 