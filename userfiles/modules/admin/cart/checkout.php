<? if($params['module_id']) :   ?>

<span class="mw_sidebar_module_box_title">Checkout settings</span>
<div class="mw_admin_rounded_box">
  <div class="mw_admin_box_padding">
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td ><label>Title</label>
          <input name="title" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('title', $params['module_id']) ?>" />
          <!--           <input  value="<?php print option_get('media_name', $params['module_id']) ?>" />
                 --></td>
      </tr>
      <tr>
        <td ><label>Empty cart text</label>
          <input name="empty_cart_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('empty_cart_text', $params['module_id']) ?>" /></td>
      </tr>
      <tr>
        <td ><label>Order completed text</label>
          <input name="order_complete_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_complete_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
      
        <tr>
        <td ><label>Terms text</label>
          <input name="order_terms_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_terms_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
           <tr>
        <td ><label>Terms link</label>
          <input name="order_terms_link" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_terms_link', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
      
        <tr>
        <td ><label>Order form names text</label>
          <input name="order_form_names_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_names_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
         <tr>
        <td ><label>Order form email text</label>
          <input name="order_form_email_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_email_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
      
         
      
      
      
         <tr>
        <td ><label>Order form address text</label>
          <input name="order_form_address_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_address_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
      
      
         <tr>
        <td ><label>Order form city text</label>
          <input name="order_form_city_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_city_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
      
         <tr>
        <td ><label>Order form post code text</label>
          <input name="order_form_post_code_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_post_code_text', $params['module_id']) ?>" /></td>
      </tr>
      
      
      
       
         <tr>
        <td ><label>Order form phone text</label>
          <input name="order_form_phone_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_phone_text', $params['module_id']) ?>" /></td>
      </tr>





 <tr>
        <td ><label>Order form mandatory fields text</label>
          <input name="order_form_mandatory_fields_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_mandatory_fields_text', $params['module_id']) ?>" /></td>
      </tr>





<tr>
        <td ><label>Order button text</label>
          <input name="order_form_btn_text" class="mw_option_field" option_group="<? print $params['module_id'] ?>" type="text" refresh_modules="cart/checkout"  value="<?php print option_get('order_form_btn_text', $params['module_id']) ?>" /></td>
      </tr>
      

  
      
      
    </table>
  </div>
</div>
<? endif; ?>
