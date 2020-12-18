<?php only_admin_access(); ?>

<div class="m-b-20">
    <img src="<?php print $config['url_to_module'] ?>omnipay_mollie.png"/>
</div>

<label class="mw-ui-label">Api Key: </label>


<input type="text" class="mw-ui-field mw_option_field block-field" name="mollie_api_key"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('mollie_api_key', 'payments'); ?>">


