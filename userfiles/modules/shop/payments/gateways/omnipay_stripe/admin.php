<?php only_admin_access(); ?>

<div class="m-b-20">
    <img src="<?php print $config['url_to_module'] ?>omnipay_stripe.png" style="max-width: 140px;"/>
</div>

<div class="m-b-10">
    <label class="mw-ui-label">Secret key: </label>

    <input type="text" class="mw-ui-field mw_option_field block-field" name="stripe_api_key" placeholder="" data-option-group="payments"
           value="<?php print get_option('stripe_api_key', 'payments'); ?>">
</div>

<div class="m-b-10">
    <label class="mw-ui-label">Publishable key: </label>

    <input type="text" class="mw-ui-field mw_option_field block-field" name="stripe_publishable_api_key" placeholder="" data-option-group="payments"
           value="<?php print get_option('stripe_publishable_api_key', 'payments'); ?>">
</div>