<?php must_have_access(); ?>

<div class="mb-3 float-right">
    <img src="<?php print $config['url_to_module'] ?>omnipay_mollie.svg" style="width: 50px; margin-top: -70px;"/>
</div>

<div class="clearfix"></div>

<div class="form-group">
    <label class="control-label"><?php _e("Api Key"); ?>: </label>
    <input type="text" class="mw_option_field form-control" name="mollie_api_key" placeholder="" data-option-group="payments" value="<?php print get_option('mollie_api_key', 'payments'); ?>">
</div>