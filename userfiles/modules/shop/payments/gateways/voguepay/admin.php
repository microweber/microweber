<?php only_admin_access(); ?>

<div class="m-b-20">
    <img src="<?php print $config['url_to_module'] ?>voguepay.png" style="max-width: 140px;"/>
</div>

<div class="m-b-10">
    <label class="mw-ui-label">Developer Code: </label>
    <input type="text" class="mw-ui-field mw_option_field block-field" name="voguepay_developer_code"
           placeholder="" data-option-group="payments"
           value="<?php print get_option('voguepay_developer_code', 'payments'); ?>">
</div>
<div class="m-b-10">
    <label class="mw-ui-label">Merchant ID: </label>
    <input type="text" class="mw-ui-field mw_option_field block-field" name="voguepay_merchant_id"
           placeholder="" data-option-group="payments"
           value="<?php print get_option('voguepay_merchant_id', 'payments'); ?>">
</div>
<ul class="mw-ui-inline-list">
    <li>
        <label class="mw-ui-label p-10 bold">
            <?php _e("Test mode"); ?>
            :</label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="voguepay_test_mode" class="mw_option_field" data-option-group="payments" value="1" type="radio" <?php if (get_option('voguepay_test_mode', 'payments') == 1): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
      <?php _e("Yes"); ?>
      </span></label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="voguepay_test_mode" class="mw_option_field" data-option-group="payments" value="0" type="radio" <?php if (get_option('voguepay_test_mode', 'payments') != 1): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
      <?php _e("No"); ?>
      </span></label>
    </li>
</ul>
