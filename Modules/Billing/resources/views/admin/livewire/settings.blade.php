
<script type="text/javascript">
    mw.require('options.js');
    $(document).ready(function () {
        mw.options.form('#billing_credentials', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });

    });
</script>


<div class="clearfix"></div>
<div id="billing_credentials">
    <div class="form-group">
        <label class="control-label">Secret key: </label>
        <input type="text" class="mw_option_field form-control" name="cashier_stripe_api_key" placeholder=""
               data-option-group="payments" value="<?php print get_option('cashier_stripe_api_key', 'payments'); ?>">
    </div>

    <div class="form-group">
        <label class="control-label">Publishable key: </label>
        <input type="text" class="mw_option_field form-control" name="cashier_stripe_publishable_api_key" placeholder=""
               data-option-group="payments"
               value="<?php print get_option('cashier_stripe_publishable_api_key', 'payments'); ?>">
    </div>


    <div class="form-group">
        <label class="control-label">Webhook secret: </label>
        <input type="text" class="mw_option_field form-control" name="cashier_stripe_webhook_secret" placeholder=""
               data-option-group="payments"
               value="<?php print get_option('cashier_stripe_webhook_secret', 'payments'); ?>">
    </div>



    <div class="form-group">
        <label class="control-label">Callback URL: </label>
         <pre><?php print route('billing.webhook.stripe') ?></pre>
    </div>


    <div class="form-group">
        <label class="control-label">Currency: </label>
        <input type="text" class="mw_option_field form-control" name="currency" placeholder=""
               data-option-group="payments" value="<?php print get_option('currency', 'payments'); ?>">
    </div>

    <div class="form-group">
        <label class="control-label">Currency locale: </label>
        <input type="text" class="mw_option_field form-control" name="currency_locale" placeholder=""
               data-option-group="payments" value="<?php print get_option('currency_locale', 'payments'); ?>">
    </div>



    <div class="form-group">
        <label class="control-label">Success URL redirect: </label>
        <input type="text" class="mw_option_field form-control" name="cashier_success_url" placeholder=""
               data-option-group="payments"
               value="<?php print get_option('cashier_success_url', 'payments'); ?>">
    </div>


    <div class="form-group">
        <label class="control-label">Cancel URL redirect: </label>
        <input type="text" class="mw_option_field form-control" name="cashier_cancel_url" placeholder=""
               data-option-group="payments"
               value="<?php print get_option('cashier_cancel_url', 'payments'); ?>">
    </div>
</div>
