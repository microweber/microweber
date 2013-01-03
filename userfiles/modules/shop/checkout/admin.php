 <strong>Also show cart?</strong>
<?php $cart_show_enanbled =  get_option('data-show-cart', $params['id']); ?>
<select name="data-show-cart"  class="mw_option_field"  >
  <option    value="y"  <? if(('n' != strval($cart_show_enanbled))): ?>   selected="selected"  <? endif; ?>>Yes</option>
  <option    value="n"  <? if(('n' == strval($cart_show_enanbled))): ?>   selected="selected"  <? endif; ?>>No</option>
</select>
<br />
<br />
<br />
<br />
<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
