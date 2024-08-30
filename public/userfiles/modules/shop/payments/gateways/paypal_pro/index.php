<?PHP

/*<div class="paypal-pro-gateway"><h5 class="alert alert-success"><?php _e("Your card payments are secured by PayPal"); ?></h5>

    <div class="form-group">
        <label class="form-label"><?php _e("First Name"); ?> </label>
        <input name="cc_first_name" type="text" class="form-control" value=""/>
    </div>
    <div class="form-group">
        <label class="form-label"><?php _e("Last Name"); ?></label>
        <input name="cc_last_name" type="text" class="form-control" value=""/>
    </div>
    <div class="form-group">
        <label class="form-label"><?php _e("Credit Card"); ?></label>
        <select name="cc_type" class="form-control">
            <option value="Visa" selected><?php _e("Visa"); ?></option>
            <option value="MasterCard"><?php _e("MasterCard"); ?></option>
            <option value="Discover"><?php _e("Discover"); ?></option>
            <option value="Amex"><?php _e("American Express"); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="form-label"><?php _e("Credit Card Number"); ?></label>
        <input name="cc_number" type="text" value="" class="form-control"/>
    </div>
    <div class="form-group">
        <label class="form-label"><?php _e("Expiration Date"); ?></label>
        <div class="row m-t-0">
            <div class="col-xs-6">
                <input name="cc_month" class="form-control" placeholder="<?php _e("Month"); ?>" type="text" value="" class="form-control"/>
            </div>
            <div class="col-xs-6">
                <input name="cc_year" class="form-control" placeholder="<?php _e("Year"); ?>" type="text" value="" class="form-control"/>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="form-label"><?php _e("Verification Code"); ?></label>
        <input name="cc_verification_value" type="text" value="" class="form-control"/>
        <div class="cc_process_error"></div>
    </div>
</div>
*/

?>

<?php
require(dirname(__DIR__).DS.'lib'.DS.'omnipay'.DS.'cc_form_fields.php');

$paypal_is_test = (get_option('paypalpro_testmode', 'payments')) == 'y';

?>

<?php if ($paypal_is_test == true and is_admin()): ?>
    <?php print notif("You are using Paypal Pro in test mode!"); ?>
<?php endif; ?>






