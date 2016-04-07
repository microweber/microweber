<?php only_admin_access(); ?>

<label class="mw-ui-label">Developer Code: </label>
<input type="text" class="mw-ui-field mw_option_field" name="voguepay_developer_code"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('voguepay_developer_code', 'payments'); ?>">
<label class="mw-ui-label">Merchant ID: </label>
<input type="text" class="mw-ui-field mw_option_field" name="voguepay_merchant_id"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('voguepay_merchant_id', 'payments'); ?>">
<ul class="mw-ui-inline-list">
  <li>
    <label class="mw-ui-label">
      <?php _e("Test mode"); ?>
      :</label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="voguepay_test_mode" class="mw_option_field"    data-option-group="payments"  value="1"  type="radio"  <?php if(get_option('voguepay_test_mode', 'payments') == 1): ?> checked="checked" <?php endif; ?> >
      <span></span><span>
      <?php _e("Yes"); ?>
      </span></label>
  </li>
  <li>
    <label class="mw-ui-check">
      <input name="voguepay_test_mode" class="mw_option_field"    data-option-group="payments"  value="0"  type="radio"  <?php if(get_option('voguepay_test_mode', 'payments') != 1): ?> checked="checked" <?php endif; ?> >
      <span></span><span>
      <?php _e("No"); ?>
      </span></label>
  </li>
</ul>
