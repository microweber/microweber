<?php must_have_access(); ?>
<!---->
<!--<div class="mb-3 float-right">-->
<!--    <img src="--><?php //print $config['url_to_module'] ?><!--omnipay_authorize_aim.svg" style="width: 40px; margin-top: -70px;"/>-->
<!--</div>-->

<div class="clearfix"></div>

<?php if (get_option('payment_gw_shop/payments/gateways/omnipay_authorize_aim', 'payments')): ?>
    <div class="d-flex align-items-center mb-3">
        <span class="badge bg-green me-2"></span>
        <p class="text-success mb-0"><?php _e("Activated") ?> </p>
    </div>
<?php endif; ?>

<div class="form-group">
    <label class="form-label">Api Login Id: </label>
    <input type="text" class="mw_option_field form-control" name="authorize_net_apiLoginId" placeholder="" data-option-group="payments" value="<?php print get_option('authorize_net_apiLoginId', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label">Transaction Key: </label>
    <input type="text" class="mw_option_field form-control" name="authorize_net_transactionKey" placeholder="" data-option-group="payments" value="<?php print get_option('authorize_net_transactionKey', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="authorize_net_testMode1" name="authorize_net_testMode" class="mw_option_field form-check-input" data-option-group="payments" value="1" <?php if (get_option('authorize_net_testMode', 'payments') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="authorize_net_testMode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="authorize_net_testMode2" name="authorize_net_testMode" class="mw_option_field form-check-input" data-option-group="payments" value="0" <?php if (get_option('authorize_net_testMode', 'payments') != 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="authorize_net_testMode2"><?php _e("No"); ?></label>
    </div>
</div>


