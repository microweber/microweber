
<span class="mw_dlm"></span>
<div style="width: 112px;" data-value="" title="<?php _e("These values will be replaced with the actual content"); ?>" id="dynamic_vals" class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown-type-wysiwyg_blue mw_dropdown_action_dynamic_values">
    <span class="mw-dropdown-value">
        <span class="mw-dropdown-arrow"></span>
        <span class="mw-dropdown-val" style="width: 80px;"><?php _e("E-mail Values"); ?></span>
    </span>
  <div class="mw-dropdown-content">
      <ul style="position: absolute;right: 0;width: 140px;">
        <li value="{id}"><a href="javascript:;"><?php _e("Order ID"); ?></a></li>
        <li value="{cart_items}"><a href="javascript:;"><?php _e("Cart items"); ?></a></li>
        <li value="{amount}"><a href="javascript:;"><?php _e("Amount"); ?></a></li>
        <li value="{order_status}"><a href="javascript:;"><?php _e("Order Status"); ?></a></li>
        <li value="{email}"><a href="javascript:;"><?php _e("Email"); ?></a></li>
        <li value="{currency}"><a href="javascript:;"><?php _e("Currency Code"); ?></a></li>
        <li value="{first_name}"><a href="javascript:;"><?php _e("First Name"); ?></a></li>
        <li value="{last_name}"><a href="javascript:;"><?php _e("Last Name"); ?></a></li>
        <li value="{email}"><a href="javascript:;"><?php _e("Email"); ?></a></li>
        <li value="{country}"><a href="javascript:;"><?php _e("Country"); ?></a></li>

        <li value="{city}"><a href="javascript:;"><?php _e("City"); ?></a></li>
        <li value="{state}"><a href="javascript:;"><?php _e("State"); ?></a></li>
        <li value="{zip}"><a href="javascript:;"><?php _e("ZIP/Post Code"); ?></a></li>
        <li value="{address}"><a href="javascript:;"><?php _e("Address"); ?></a></li>
        <li value="{phone}"><a href="javascript:;"><?php _e("Phone"); ?></a></li>
      </ul>
    </div>
</div>
<script>
    mw.$("#dynamic_vals").change(function(){
        var val = $(this).getDropdownValue();
         mw.wysiwyg.insert_html(val);
    });
</script>