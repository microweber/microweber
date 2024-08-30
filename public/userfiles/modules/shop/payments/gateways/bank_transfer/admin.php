<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--bank_transfer.svg"  style="width: 40px; margin-top: -70px;"/>-->
<!--</div>-->

<?php if (get_option('payment_gw_shop/payments/gateways/bank_transfer', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>

<div class="form-group d-flex align-items-center justify-content-between">
    <div>
        <label class="form-label d-block"><?php _e("Show additional details"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('Additional details explanation.'); ?></small>
    </div>

    <div class="form-check form-check-single form-switch" style="width: unset;">
        <input type="checkbox" id="bank_transfer_show_msg1" name="bank_transfer_show_msg" class="mw_option_field form-check-input" data-bs-toggle="collapse" data-bs-target="#show-additional-settings-payments" data-option-group="payments"  data-value-unchecked="n" data-value-checked="y" <?php if (get_option('bank_transfer_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
    </div>
</div>

<div class="form-group collapse <?php if (get_option('bank_transfer_show_msg', 'payments') == 'y'): ?> show <?php endif; ?>" id="show-additional-settings-payments">
    <label class="form-label d-block"><?php _e('Additional details'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Displays to customers when they’re choosing a payment method.'); ?></small>
    <textarea name="bank_transfer_msg" placeholder="<?php _e("e.g. You are able to pay this order with bank transfer") ?>." class="mw_option_field form-control" data-option-group="payments"><?php print get_option('bank_transfer_msg', 'payments') ?></textarea>
</div>

<div class="form-group d-flex align-items-center justify-content-between">
    <label class="form-label d-block"><?php _e("Show payment instructions"); ?></label>

    <div class="form-check form-check-single form-switch" style="width: unset;">
        <input type="checkbox" id="bank_transfer_show_instructions1" name="bank_transfer_show_instructions" class="mw_option_field form-check-input" data-value-unchecked="n" data-value-checked="y" data-bs-toggle="collapse" data-bs-target="#show-payments-instructions" data-option-group="payments" value="y" <?php if (get_option('bank_transfer_show_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
    </div>
</div>

<div class="form-group collapse <?php if (get_option('bank_transfer_show_instructions', 'payments') == 'y'): ?> show <?php endif; ?>" id="show-payments-instructions">
    <div class="form-group d-flex align-items-center justify-content-between">
        <label class="form-label d-block"><?php _e("Send email with payment instructions"); ?></label>

        <div class="form-check form-check-single form-switch" style="width: unset;">
            <input type="checkbox" id="bank_transfer_send_email_instructions1" name="bank_transfer_send_email_instructions" class="mw_option_field form-check-input" data-value-unchecked="n" data-value-checked="y" data-option-group="payments" value="y" <?php if (get_option('bank_transfer_send_email_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>
        </div>
    </div>

    <div class="form-group">

        <label class="form-label"><?php _e('Payment instructions to order'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e(" At the last step in the checkout, add additional information about the order. Use {{order_id}} to show order id in payment instructions.") ?></small>
        <textarea name="bank_transfer_instructions" <?php _e("e.g. Your order number is {{order_id}}") ?> class="mw_option_field form-control" data-option-group="payments"><?php print get_option('bank_transfer_instructions', 'payments') ?></textarea>
        <small class="text-muted d-block mt-2"><?php _e('Displays to customers after they place an order with this payment method.'); ?></small>
    </div>


</div>



