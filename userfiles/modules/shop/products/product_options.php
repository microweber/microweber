<?php

if(!is_admin()){
return;	
}
$cont_id = 0;
if(isset($params['content-id'])){
$cont_id = $params['content-id'];
}
 
$data_fields = content_data($cont_id);
 
 
?>
<div class="mw-o-box">
	<div class="mw-o-box-content">
		<div class="mw-ui-field-holder" style="width: 175px;">
			<label class="mw-ui-label">Items in stock <span class="mw-help" data-help="How many items of this product you have in stock?">?</span></label>
			<span class="mwsico-instock mw-help" data-help="How many items of this product you have in stock?"></span>
			<select name="data_qty" class="mw-ui-simple-dropdown" style="width: 105px;position: relative;top: 3px;">
				<option <?php if (!isset($data_fields['qty']) or ($data_fields['qty']) == 'nolimit'): ?> selected="selected" <?php endif; ?> value="nolimit">&infin; No Limit</option>
				<?php for($i=1;$i<=100;$i++){ ?>
				<option value="<?php print $i; ?>" <?php if (isset($data_fields['qty']) and intval($data_fields['qty']) == $i): ?> selected="selected" <?php endif; ?> ><?php print $i; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="mw-ui-field-holder" style="padding-left: 20px;">
			<label class="mw-ui-label" style="padding-left: 0;" data-help="Stock Keeping Unit - The number assigned to a product by a retail store to identify the price, product options and manufacturer of the merchandise.">SKU Number</label>
			<input name="data_sku" type="text" class="mw-ui-field" style="width: 300px;" <?php if (isset($data_fields['sku'])): ?> value="<?php print $data_fields['sku']; ?>" <?php endif; ?> />
		</div>
	</div>
</div>
<div class="vSpace"></div>
<div id="mw-custom-field-shipping-<?php print $cont_id ?>"> <a href="javascript:;" class="mw-ui-more" onclick="mw.tools.toggle('#mw-admin-product-shipping-options', this);" id="shipping-fields-toggler"
    data-for='#mw-admin-product-shipping-options'>
	<?php _e("Shipping options"); ?>
	</a>
	<div id="mw-admin-product-shipping-options">
		<div class="right" style="margin-top: -21px;"> <span class="mw-onoff right" style="margin: 0 10px 0 12px;" onclick="$(this).toggleClass('active');"> <i>OFF</i> <i>ON</i> </span>
			<label class="mw-ui-label right">Free Shipping</label>
		</div>
		<div class="vSpace"></div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label"> Weight </label>
			<span class="mwsico-weight"></span>
			<input type="number" min="0" name="data_shipping_weight" class="mw-ui-field"  <?php if (isset($data_fields['shipping_weight'])): ?> value="<?php print $data_fields['shipping_weight']; ?>" <?php endif; ?>  />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Width </label>
			<span class="mwsico-width"></span>
			<input type="number" min="0" name="data_shipping_width" class="mw-ui-field"  <?php if (isset($data_fields['shipping_width'])): ?> value="<?php print $data_fields['shipping_width']; ?>" <?php endif; ?>  />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Height </label>
			<span class="mwsico-height"></span>
			<input type="number" min="0" name="data_shipping_height" class="mw-ui-field"  <?php if (isset($data_fields['shipping_height'])): ?> value="<?php print $data_fields['shipping_height']; ?>" <?php endif; ?>  />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Depth </label>
			<span class="mwsico-depth"></span>
			<input type="number" min="0" name="data_shipping_depth" class="mw-ui-field"  <?php if (isset($data_fields['shipping_depth'])): ?> value="<?php print $data_fields['shipping_depth']; ?>" <?php endif; ?>  />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Fixed Cost <span class="mw-help mw-help-right" data-help="Additional Shipping Cost will be added on purchase">?</span></label>
			<span class="mwsico-cost"></span>
			<input type="number" min="0" name="data_additional_shipping_cost" class="mw-ui-field"  <?php if (isset($data_fields['additional_shipping_cost'])): ?> value="<?php print $data_fields['additional_shipping_cost']; ?>" <?php endif; ?>  />
		</div>
		<div class="vSpace"></div>
		<div class="vSpace"></div>
	</div>
</div>
