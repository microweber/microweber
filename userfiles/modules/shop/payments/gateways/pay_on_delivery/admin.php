<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--pay_on_delivery.svg"  style="width: 60px; margin-top: -70px;"/>-->
<!--</div>-->

<?php if (get_option('payment_gw_shop/payments/gateways/pay_on_delivery', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>


<div class="form-group d-flex align-items-center justify-content-between">
    <div>
        <label class="form-label d-block"><?php _e("Additional message to the user") ?></label>
        <small class="text-muted d-block mb-2"><?php _e('Show additional message when pa on delivery is selected.'); ?></small>

    </div>
    <div class="form-check form-check-single form-switch" style="width: unset;">

    <input type="checkbox" id="pay_on_delivery_show_msg1" name="pay_on_delivery_show_msg" class="mw_option_field form-check-input" data-value-unchecked="n" data-value-checked="y" data-bs-toggle="collapse" data-bs-target="#show-additional-message-to-the-user" data-option-group="payments" value="y" <?php if (get_option('pay_on_delivery_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?>>

    </div>
</div>

<div class="form-group collapse <?php if (get_option('pay_on_delivery_show_msg', 'payments') == 'y'): ?> show <?php endif; ?>" id="show-additional-message-to-the-user">
    <label class="form-label d-block"><?php _e('Message'); ?></label>
    <textarea name="pay_on_delivery_msg" class="mw_option_field form-control" placeholder="<?php _e("e.g. Thank you for your order!"); ?>" data-option-group="payments"><?php print get_option('pay_on_delivery_msg', 'payments') ?></textarea>
</div>
