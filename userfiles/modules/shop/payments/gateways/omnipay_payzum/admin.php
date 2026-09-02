<?php must_have_access(); ?>

<div class="clearfix"></div>

<?php if (get_option('payment_gw_shop/payments/gateways/omnipay_payzum', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>

<div class="form-group">
    <label class="form-label"><?php _e("Api Key"); ?>: </label>
    <input type="text" class="mw_option_field form-control" name="payzum_api_key" placeholder="" data-option-group="payments" value="<?php print get_option('payzum_api_key', 'payments'); ?>">
    <small class="text-muted"><?php _e("From Dashboard → Settings → API Keys at merchant.payzum.com"); ?></small>
</div>

<div class="form-group">
    <label class="form-label"><?php _e("Webhook Secret"); ?>: </label>
    <input type="text" class="mw_option_field form-control" name="payzum_webhook_secret" placeholder="" data-option-group="payments" value="<?php print get_option('payzum_webhook_secret', 'payments'); ?>">
    <small class="text-muted"><?php _e("Shown once, at merchant creation or rotation. Used to verify the signature of payment notifications — orders are marked as paid from those notifications, not from the buyer's redirect."); ?></small>
</div>

<div class="form-group">
    <label class="form-label"><?php _e("Use Sandbox"); ?>: </label>
    <select class="mw_option_field form-control" name="payzum_test_mode" data-option-group="payments">
        <option value="n" <?php if (get_option('payzum_test_mode', 'payments') != 'y'): ?>selected<?php endif; ?>><?php _e("No"); ?></option>
        <option value="y" <?php if (get_option('payzum_test_mode', 'payments') == 'y'): ?>selected<?php endif; ?>><?php _e("Yes"); ?></option>
    </select>
    <small class="text-muted"><?php _e("Points at staging.payzum.com — an isolated environment with separate API keys."); ?></small>
</div>
