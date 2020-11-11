<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>omnipay_authorize_aim.svg" style="width: 40px; margin-top: -70px;"/>
</div>

<div class="clearfix"></div>

<div class="form-group">
    <label class="control-label">Api Login Id: </label>
    <input type="text" class="mw_option_field form-control" name="authorize_net_apiLoginId" placeholder="" data-option-group="payments" value="<?php print get_option('authorize_net_apiLoginId', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label">Transaction Key: </label>
    <input type="text" class="mw_option_field form-control" name="authorize_net_transactionKey" placeholder="" data-option-group="payments" value="<?php print get_option('authorize_net_transactionKey', 'payments'); ?>">
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Test mode"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="authorize_net_testMode1" name="authorize_net_testMode" class="mw_option_field custom-control-input" data-option-group="payments" value="1" <?php if (get_option('authorize_net_testMode', 'payments') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="authorize_net_testMode1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="authorize_net_testMode2" name="authorize_net_testMode" class="mw_option_field custom-control-input" data-option-group="payments" value="0" <?php if (get_option('authorize_net_testMode', 'payments') != 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="authorize_net_testMode2"><?php _e("No"); ?></label>
    </div>
</div>


 