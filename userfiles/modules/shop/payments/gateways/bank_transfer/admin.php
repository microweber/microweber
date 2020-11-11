<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>bank_transfer.svg"  style="width: 40px; margin-top: -70px;"/>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Show additional details"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_show_msg1" name="bank_transfer_show_msg" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('bank_transfer_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_show_msg1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_show_msg2" name="bank_transfer_show_msg" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('bank_transfer_show_msg', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_show_msg2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e('Additional details'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Displays to customers when they’re choosing a payment method.'); ?></small>
    <textarea name="bank_transfer_msg" class="mw_option_field form-control" data-option-group="payments"><?php print get_option('bank_transfer_msg', 'payments') ?></textarea>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Show payment instructions"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_show_instructions1" name="bank_transfer_show_instructions" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('bank_transfer_show_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_show_instructions1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_show_instructions2" name="bank_transfer_show_instructions" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('bank_transfer_show_instructions', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_show_instructions2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Send email with payment instructions"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_send_email_instructions1" name="bank_transfer_send_email_instructions" class="mw_option_field custom-control-input" data-option-group="payments" value="y" <?php if (get_option('bank_transfer_send_email_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_send_email_instructions1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="bank_transfer_send_email_instructions2" name="bank_transfer_send_email_instructions" class="mw_option_field custom-control-input" data-option-group="payments" value="n" <?php if (get_option('bank_transfer_send_email_instructions', 'payments') != 'y'): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="bank_transfer_send_email_instructions2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php _e('Payment instructions'); ?></label>
    <small class="text-muted d-block mb-2">Use <b>{{order_id}}</b> to show id of order in payment instructions.</small>
    <textarea name="bank_transfer_instructions" class="mw_option_field form-control" data-option-group="payments"><?php print get_option('bank_transfer_instructions', 'payments') ?></textarea>
    <small class="text-muted d-block mt-2"><?php _e('Displays to customers after they place an order with this payment method.'); ?></small>
</div>