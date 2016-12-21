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

<div class="module-product-options-settings">


        <div class="mw-ui-row">
            <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
        			<label class="mw-ui-label"><?php _e("Items in stock"); ?> <span class="mw-help mw-help-right-top" data-help="<?php _e("How many items of this product you have in stock"); ?>?">?</span></label>
        			<select name="data_qty" class="mw-ui-field w100">
        				<option <?php if (!isset($data_fields['qty']) or ($data_fields['qty']) == 'nolimit'): ?> selected="selected" <?php endif; ?> value="nolimit">&infin; No Limit</option>
        				<option <?php if (isset($data_fields['qty']) and $data_fields['qty']  != 'nolimit' and (intval($data_fields['qty'])) == 0): ?>  selected="selected" <?php endif; ?> value="0" title="This item is out of stock and cannot be ordered.">Out of stock</option>
        				<?php for($i=1;$i<=100;$i++){ ?>
        				<option value="<?php print $i; ?>" <?php if (isset($data_fields['qty']) and intval($data_fields['qty']) == $i): ?> selected="selected" <?php endif; ?> ><?php print $i; ?></option>
        				<?php } ?>
        			</select>
        		</div>
            </div>
            </div>
            <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <div class="mw-ui-field-holder">
        			<label class="mw-ui-label"><?php _e("SKU Number"); ?> <span class="tip" data-tip="<?php _e("Stock Keeping Unit - The number assigned to a product by a retail store to identify the price,\n product options and manufacturer of the merchandise"); ?>.">?</span></label>
        			<input name="data_sku" type="text" class="mw-ui-field w100" <?php if (isset($data_fields['sku'])): ?> value="<?php print $data_fields['sku']; ?>" <?php endif; ?> />
        		</div>
            </div>
            </div>
            <div class="mw-ui-col">
            <div class="mw-ui-col-container">
               <div class="mw-ui-field-holder">
                    <span
                        data-help="<?php _e("Set your shipping options"); ?>"
                        class="mw-ui-btn left"
                        onclick="mw.$('#mw-admin-product-shipping-options').toggle(); mw.$('#mw-admin-product-order-options').hide();"> <span><?php _e("Shipping Options"); ?></span> <span class="mw-icon-dropdown"></span> </span>
                        
                        
                        
                        <span
                        data-help="<?php _e("Set your order options"); ?>"
                        class="mw-ui-btn left"
                        onclick="mw.$('#mw-admin-product-order-options').toggle(); mw.$('#mw-admin-product-shipping-options').hide();"> <span><?php _e("Order Options"); ?></span> <span class="mw-icon-dropdown"></span> </span>
                        
                        
                        
                </div>
            </div>
            </div>
            
            
        </div>




		<div id="mw-admin-product-shipping-options" style="display: none">
        
        
        <h4><?php _e("Shipping Options"); ?></h4>
<hr />
        
        
			<div>


            <label class="mw-ui-inline-label"><?php _e("Free Shipping"); ?></label>
            <label class="mw-ui-check" style="margin-right:10px;"><input type="radio" <?php if (isset($data_fields['is_free_shipping']) and $data_fields['is_free_shipping'] == "y"): ?>checked="checked"<?php endif; ?> name="data_is_free_shipping" value="y"><span></span><span><?php _e("Yes"); ?></span></label>
            <label class="mw-ui-check"><input type="radio" <?php if (isset($data_fields['is_free_shipping']) and $data_fields['is_free_shipping'] == "n"): ?>checked="checked"<?php endif; ?> name="data_is_free_shipping" value="n"><span></span><span><?php _e("No"); ?></span></label>




				<div id="data_shipping_fields" >
                     <div class="mw-ui-row">
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                                <div class="mw-ui-field-holder">
            						<label class="mw-ui-label"> <?php _e("Weight"); ?> </label>
            						<span class="mwsico-weight"></span>
            						<input type="number" min="0" step=".001" name="data_shipping_weight" class="mw-ui-field w100"  <?php if (isset($data_fields['shipping_weight'])): ?> value="<?php print $data_fields['shipping_weight']; ?>" <?php endif; ?>  />
            					</div>
                           </div>
                         </div>
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                                <div class="mw-ui-field-holder">
            						<label class="mw-ui-label"><?php _e("Width"); ?> </label>
            						<span class="mwsico-width"></span>
            						<input type="number" min="0" step=".001" name="data_shipping_width" class="mw-ui-field w100"  <?php if (isset($data_fields['shipping_width'])): ?> value="<?php print $data_fields['shipping_width']; ?>" <?php endif; ?>  />
            					</div>
                           </div>
                         </div>
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                              <div class="mw-ui-field-holder">
          						<label class="mw-ui-label"><?php _e("Height"); ?> </label>
          						<span class="mwsico-height"></span>
          						<input type="number" min="0" step=".001" name="data_shipping_height" class="mw-ui-field w100"  <?php if (isset($data_fields['shipping_height'])): ?> value="<?php print $data_fields['shipping_height']; ?>" <?php endif; ?>  />
          					</div>
                           </div>
                         </div>
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                           <div class="mw-ui-field-holder">
        						<label class="mw-ui-label"><?php _e("Depth"); ?> </label>
        						<span class="mwsico-depth"></span>
        						<input type="number" min="0" step=".001" name="data_shipping_depth" class="mw-ui-field w100"  <?php if (isset($data_fields['shipping_depth'])): ?> value="<?php print $data_fields['shipping_depth']; ?>" <?php endif; ?>  />
        					</div>
                           </div>
                         </div>
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                             <div class="mw-ui-field-holder">
        						<label class="mw-ui-label"><?php _e("Fixed Cost"); ?> <span class="mw-help mw-help-right" data-help="<?php _e("Additional Shipping Cost will be added on purchase"); ?>">?</span></label>
        						<input type="number" min="0" step=".01" name="data_additional_shipping_cost" class="mw-ui-field w100"  <?php if (isset($data_fields['additional_shipping_cost'])): ?> value="<?php print $data_fields['additional_shipping_cost']; ?>" <?php endif; ?>  />
        					</div>
                           </div>
                         </div>

                     </div>








				</div>
			</div>
		</div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <div id="mw-admin-product-order-options" style="display: none">
			<div >
<h4><?php _e("Order Options"); ?></h4>
<hr />

           <div class="mw-ui-row">
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                                <div class="mw-ui-field-holder">
            						<label class="mw-ui-label"> <?php _e("Max quantity per order"); ?> </label>
            						<span class="mwsico-weight"></span>
            						<input type="number" min="1" step="1" name="data_max_qty_per_order" class="mw-ui-field"  <?php if (isset($data_fields['max_qty_per_order'])): ?> value="<?php print $data_fields['max_qty_per_order']; ?>" <?php endif; ?>  />
            					</div>
                           </div>
                         </div>
                         <div class="mw-ui-col">
                           <div class="mw-ui-col-container">
                                
                           </div>
                         </div>
                         
                     </div>



				 
			</div>
		</div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

</div>

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
