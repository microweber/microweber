<?php only_admin_access(); ?>


<label class="mw-ui-label">Secret key: </label>


<input type="text" class="mw-ui-field mw_option_field" name="stripe_api_key"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('stripe_api_key', 'payments'); ?>">

<label class="mw-ui-label">Publishable key: </label>

<input type="text" class="mw-ui-field mw_option_field" name="stripe_publishable_api_key"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('stripe_publishable_api_key', 'payments'); ?>">


