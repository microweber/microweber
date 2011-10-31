<? if($params['module_id']) :   ?>
<span class="mw_sidebar_module_box_title">Cart settings</span>
<div class="mw_admin_rounded_box">
  <div class="mw_admin_box_padding">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td ><label>Name column text</label>
          <input name="name" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('name', $params['module_id']) ?>" />
          <!--           <input  value="<?php print option_get('media_name', $params['module_id']) ?>" />
                 --></td>
      </tr>
      
      
      
      
      
      
      
      
      
      <tr>
        <td ><label>Details column text</label>
          <input name="details" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('details', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
      
         <tr>
        <td ><label>Qty column text</label>
          <input name="qty_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('qty_text', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
      
      <tr>
        <td ><label>Price column text</label>
          <input name="single_price_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('single_price_text', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
      
        <tr>
        <td ><label>Delete column text</label>
          <input name="remove_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('remove_text', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
      
         <tr>
        <td ><label>Total price text</label>
          <input name="total_price_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('total_price_text', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
       <tr>
        <td ><label>Shipping price_text</label>
          <input name="shipping_price_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/items"  value="<?php print option_get('shipping_price_text', $params['module_id']) ?>" />
        
            </td>
      </tr>
      
      
      
      
      

    </table>
 
 
  </div>
</div>




<? endif; ?>


