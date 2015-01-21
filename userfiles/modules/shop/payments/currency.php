<h2>
  <?php _e("Currency settings"); ?>
</h2>
<?php ?>
<?php $cur = get_option('currency', 'payments');  ?>
<?php $curencies = mw()->shop_manager->currency_get();

?>
<?php if(is_array($curencies )): ?>
<select name="currency" class="mw-ui-field mw_option_field" data-option-group="payments" data-reload="mw_curr_rend">
  <?php foreach($curencies  as $item): ?>
  <option  value="<?php print $item[1] ?>" <?php if($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
  <?php endforeach ; ?>
</select>
<?php endif; ?>
<module type="shop/payments/currency_render" id="mw_curr_rend" />
