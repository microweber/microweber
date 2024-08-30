<?php must_have_access(); ?>

<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--omnipay_stripe.svg" style="width: 40px; margin-top: -70px;"/>-->
<!--</div>-->

<div class="clearfix"></div>

<?php if (get_option('payment_gw_shop/payments/gateways/omnipay_stripe', 'payments')): ?>
<div class="d-flex align-items-center mb-3">
    <span class="badge bg-green me-2"></span>
    <p class="text-success mb-0"><?php _e("Activated") ?> </p>
</div>
<?php endif; ?>

<div class="form-group">
    <label class="form-label"><?php _e("Secret key") ?> : </label>
    <input type="text" class="mw_option_field form-control" name="stripe_api_key" placeholder="" data-option-group="payments" value="<?php print get_option('stripe_api_key', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label"><?php _e("Publishable key") ?> : </label>
    <input type="text" class="mw_option_field form-control" name="stripe_publishable_api_key" placeholder="" data-option-group="payments" value="<?php print get_option('stripe_publishable_api_key', 'payments'); ?>">
</div>
