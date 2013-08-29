 <strong><?php _e("Also show cart"); ?>?</strong>
<?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
<select name="data-show-cart"  class="mw_option_field"  >
  <option value="y"  <?php if(('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("Yes"); ?></option>
  <option value="n"  <?php if(('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <?php endif; ?>><?php _e("No"); ?></option>
</select>
<br />
<br />
<br />
<br />
<strong><?php _e("Skin/Template"); ?></strong>
<module type="admin/modules/templates"  />
