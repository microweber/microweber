<?php

if(!is_admin()){
return;	
}



$cont_id = 0;

if(isset($params['content-id'])){
//	d($params);
$cont_id = $params['content-id'];
}

$data = false;

$fields = get_custom_fields("return_full=true&field_type=shipping&is_active=ANY&content_id=".$cont_id);
if(is_array($fields ) and !empty($fields) and isset($fields[0]) ){
	$data = $fields[0];

}
$val_shipping_width = 0;
$val_shipping_height = 0;
$val_shipping_depth = 0;
$val_shipping_weight = 0;
$val_shipping_fixed_shipping_cost = 0;
 
 if(is_array($data) and isset($data["custom_field_values"]) and isset($data["custom_field_values"]["shipping_width"])){
	 $val_shipping_width = array_pop($data["custom_field_values"]["shipping_width"]);

 }
  if(is_array($data) and isset($data["custom_field_values"]) and isset($data["custom_field_values"]["shipping_height"])){
	 $val_shipping_height = array_pop($data["custom_field_values"]["shipping_height"]);
	 
 }
  if(is_array($data) and isset($data["custom_field_values"]) and isset($data["custom_field_values"]["shipping_depth"])){
	 $val_shipping_depth = array_pop($data["custom_field_values"]["shipping_depth"]);
	 
 }
  if(is_array($data) and isset($data["custom_field_values"]) and isset($data["custom_field_values"]["shipping_weight"])){
	 $val_shipping_weight = array_pop($data["custom_field_values"]["shipping_weight"]);
	 
 }
  if(is_array($data) and isset($data["custom_field_values"]) and isset($data["custom_field_values"]["fixed_shipping_cost"])){
	 $val_shipping_fixed_shipping_cost = array_pop($data["custom_field_values"]["fixed_shipping_cost"]);
	 
 }
?>
<script>
mw_save_shipping_custom_field = function(){
		mw.custom_fields.save('#mw-custom-field-shipping-<?php print $cont_id ?>',mw_reload_shipping_custom_field);
}

mw_reload_shipping_custom_field = function(){
	mw.reload_module('#<?php print trim($params['id']) ?>')
	
}
</script>



<div class="mw-o-box">
  <div class="mw-o-box-content">
        <div class="mw-ui-field-holder" style="width: 175px;">
            <label class="mw-ui-label">Items in stock <span class="mw-help" data-help="How many items of this product you have in stock?">?</span></label>
            <span class="mwsico-instock mw-help" data-help="How many items of this product you have in stock?"></span>
            <select class="mw-ui-simple-dropdown" style="width: 105px;position: relative;top: 3px;">
                <option selected="selected" value="nolimit">&infin; No Limit</option>
                <?php for($i=1;$i<=100;$i++){ ?>
                    <option value="<?php print $i; ?>"><?php print $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mw-ui-field-holder" style="padding-left: 20px;">
			<label class="mw-ui-label" style="padding-left: 0;" data-help="Stock Keeping Unit - A number assigned to a product by a retail store to identify the price, product options and manufacturer of the merchandise.">SKU Number</label>

			<input type="text" class="mw-ui-field" style="width: 300px;" />
		</div>





  </div>
</div>

<div class="vSpace"></div>


<div id="mw-custom-field-shipping-<?php print $cont_id ?>">
	<input type="hidden" name="rel" value="content" />
	<input type="hidden" name="rel_id" value="<?php print $cont_id ?>" />
	<input type="hidden" name="custom_field_is_active" value="n" />
	<input type="hidden" name="custom_field_name" value="Shipping" />
	<input type="hidden" name="custom_field_type" value="shipping" />
	<!--	<a onClick="mw_save_shipping_custom_field()">save shipping</a>
-->
	<?php if (isset($data['id']) and intval($data['id']) != 0): ?>
	<input type="hidden" name="cf_id" value="<?php print intval($data['id']) ?>" />
	<?php endif; ?>
	<?php if (isset($data['rel']) and $data['rel'] != false): ?>
	<?php endif; ?>
	<a href="javascript:;" class="mw-ui-more" onclick="mw.tools.toggle('#mw-admin-product-shipping-options', this);" id="shipping-fields-toggler"
    data-for='#mw-admin-product-shipping-options'>
	<?php _e("Shipping options"); ?>
	</a>
	<div id="mw-admin-product-shipping-options">

        <div class="right" style="margin-top: -21px;">
          <span class="mw-onoff right" style="margin: 0 10px 0 12px;" onclick="$(this).toggleClass('active');"> <i>OFF</i> <i>ON</i> </span>
  		<label class="mw-ui-label right">Free Shipping</label>
        </div>
        <div class="vSpace"></div>
        <div class="mw-ui-field-holder">
			<label class="mw-ui-label"> Weight </label>
			<span class="mwsico-weight"></span>
			<input type="number" min="0" name="custom_field_value[shipping_weight]" class="mw-ui-field" value="<?php print floatval($val_shipping_weight) ?>"  onchange="mw_save_shipping_custom_field()" />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Width </label>
			<span class="mwsico-width"></span>
			<input type="number" min="0" name="custom_field_value[shipping_width]" class="mw-ui-field" value="<?php print floatval($val_shipping_width) ?>" onchange="mw_save_shipping_custom_field()" />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Height  </label>
			<span class="mwsico-height"></span>
			<input type="number" min="0" name="custom_field_value[shipping_height]" class="mw-ui-field" value="<?php print floatval($val_shipping_height) ?>"  onchange="mw_save_shipping_custom_field()" />
		</div>
		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Depth </label>
			<span class="mwsico-depth"></span>
			<input type="number" min="0" name="custom_field_value[shipping_depth]" class="mw-ui-field" value="<?php print floatval($val_shipping_depth) ?>"  onchange="mw_save_shipping_custom_field()" />
		</div>


		<div class="mw-ui-field-holder">
			<label class="mw-ui-label">Fixed Cost <span class="mw-help mw-help-right" data-help="Fixed Shipping Cost">?</span></label>
			<span class="mwsico-cost"></span>
			<input type="number" min="0" name="custom_field_value[fixed_shipping_cost]" class="mw-ui-field left" value="<?php print floatval($val_shipping_fixed_shipping_cost) ?>"  onchange="mw_save_shipping_custom_field()" />
		</div>
        <div class="vSpace"></div>

        <div class="vSpace"></div>
	</div>
</div>
