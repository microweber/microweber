<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>pay_on_delivery.svg"  style="width: 60px; margin-top: -70px;"/>
</div>

<div class="form-group">
    <label class="control-label d-block">Show message?</label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="pay_on_delivery_show_msg1" name="pay_on_delivery_show_msg" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('pay_on_delivery_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="pay_on_delivery_show_msg1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="pay_on_delivery_show_msg2" name="pay_on_delivery_show_msg" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('pay_on_delivery_show_msg', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="pay_on_delivery_show_msg2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e('Message'); ?></label>
    <textarea name="pay_on_delivery_msg" class="mw_option_field form-control" data-option-group="payments"><?php print get_option('pay_on_delivery_msg', 'payments') ?></textarea>
</div>