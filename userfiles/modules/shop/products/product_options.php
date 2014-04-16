<?php

if(!is_admin()){
return;	
}
$cont_id = 0;
if(isset($params['content-id'])){
$cont_id = $params['content-id'];
}
 
$data_fields = content_data($cont_id);
 
$out_of_stock = false;
?>

<div class="mw-ui-box">
	<div class="mw-ui-box-content">
		<div class="mw-ui-field-holder" style="width: 175px;">
			<label class="mw-ui-label">Items in stock <span class="mw-help" data-help="How many items of this product you have in stock?">?</span></label>
			<span class="mwsico-instock mw-help" data-help="How many items of this product you have in stock?"></span>
			<select name="data_qty" class="mw-ui-simple-dropdown" style="width: 115px;position: relative;top: 3px;">
				<option <?php if (!isset($data_fields['qty']) or ($data_fields['qty']) == 'nolimit'): ?> selected="selected" <?php endif; ?> value="nolimit">&infin; No Limit</option>
				<option <?php if (isset($data_fields['qty']) and $data_fields['qty']  != 'nolimit' and (intval($data_fields['qty'])) == 0): ?>  selected="selected" <?php endif; ?> value="0" title="This item is out of stock and cannot be ordered.">Out of stock</option>
				<?php for($i=1;$i<=100;$i++){ ?>
				<option value="<?php print $i; ?>" <?php if (isset($data_fields['qty']) and intval($data_fields['qty']) == $i): ?> selected="selected" <?php endif; ?> ><?php print $i; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="mw-ui-field-holder" style="padding-left: 20px;width: 215px;">
			<label class="mw-ui-label" style="padding-left: 0;" data-help="Stock Keeping Unit - The number assigned to a product by a retail store to identify the price, product options and manufacturer of the merchandise.">SKU Number</label>
			<input name="data_sku" type="text" class="mw-ui-field" style="width: 190px;" <?php if (isset($data_fields['sku'])): ?> value="<?php print $data_fields['sku']; ?>" <?php endif; ?> />
		</div>
		<div class="mw-ui-field-holder" style="padding-left: 20px;width: 150px;"> <span
                data-help="Set your shipping options"
                class="mw-ui-btn mws-btn"
                style="padding: 0 5px;"
                onclick="mw.$('#mw-admin-product-shipping-options').toggle();"> <span class="mwsico-cost"  style="cursor:pointer;margin-right:0;"></span> <span>Shipping Options</span> </span> </div>
		<div class="mw_clear"></div>
		<div class="vSpace"></div>
		<div id="mw-admin-product-shipping-options" style="display: none">
			<div >
				<div class="vSpace"></div>
				<hr>
				<div class="vSpace"></div>
				<label class="mw-ui-label left" style="margin: 0 12px 5px 2px;">Free Shipping</label>
				<span
              class="mw-onoff left <?php if (isset($data_fields['is_free_shipping']) and $data_fields['is_free_shipping'] == "y"): ?> active <?php endif; ?>"
              id="toggle_free_shipping"
              onclick="toggle_free_shipping();"> <i>OFF</i> <i>ON</i> </span>
				<input
                type="hidden"
                name="data_is_free_shipping"
                id="data_is_free_shipping"
                class="mw-ui-field"
                <?php if (isset($data_fields['is_free_shipping'])): ?> value="<?php print $data_fields['is_free_shipping']; ?>" <?php endif; ?>
            />
				<div class="mw_clear"></div>
				<div id="data_shipping_fields" >
					<div class="mw-ui-field-holder">
						<label class="mw-ui-label"> Weight </label>
						<span class="mwsico-weight"></span>
						<input type="number" min="0" step=".001" name="data_shipping_weight" class="mw-ui-field"  <?php if (isset($data_fields['shipping_weight'])): ?> value="<?php print $data_fields['shipping_weight']; ?>" <?php endif; ?>  />
					</div>
					<div class="mw-ui-field-holder">
						<label class="mw-ui-label">Width </label>
						<span class="mwsico-width"></span>
						<input type="number" min="0" step=".001" name="data_shipping_width" class="mw-ui-field"  <?php if (isset($data_fields['shipping_width'])): ?> value="<?php print $data_fields['shipping_width']; ?>" <?php endif; ?>  />
					</div>
					<div class="mw-ui-field-holder">
						<label class="mw-ui-label">Height </label>
						<span class="mwsico-height"></span>
						<input type="number" min="0" step=".001" name="data_shipping_height" class="mw-ui-field"  <?php if (isset($data_fields['shipping_height'])): ?> value="<?php print $data_fields['shipping_height']; ?>" <?php endif; ?>  />
					</div>
					<div class="mw-ui-field-holder">
						<label class="mw-ui-label">Depth </label>
						<span class="mwsico-depth"></span>
						<input type="number" min="0" step=".001" name="data_shipping_depth" class="mw-ui-field"  <?php if (isset($data_fields['shipping_depth'])): ?> value="<?php print $data_fields['shipping_depth']; ?>" <?php endif; ?>  />
					</div>
					<div class="mw-ui-field-holder" style="width: 120px;">
						<label class="mw-ui-label">Fixed Cost <span class="mw-help mw-help-right" data-help="Additional Shipping Cost will be added on purchase">?</span></label>
						<span class="mwsico-cost"></span>
						<input type="number" min="0" step=".01" name="data_additional_shipping_cost" class="mw-ui-field"  <?php if (isset($data_fields['additional_shipping_cost'])): ?> value="<?php print $data_fields['additional_shipping_cost']; ?>" <?php endif; ?>  />
					</div>
				</div>
				<div class="vSpace"></div>
				<div class="vSpace"></div>
			</div>
		</div>
	</div>
</div>
<div class="vSpace"></div>
<script>

toggle_free_shipping = function(){
    var t = mw.$("#toggle_free_shipping");
    var f = mw.$("#data_is_free_shipping");
    if(t.hasClass("active")){
      f.val("n");
      t.removeClass("active");
    }
    else{
       f.val("y");
       t.addClass("active");
    }
}

</script> 
