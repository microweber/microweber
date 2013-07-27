
    <h5 class="alert alert-success"><?php _e("Your card payments are secured by PayPal"); ?></h5>


    <label><?php _e("First Name"); ?> </label>
    <input name="cc_first_name"  type="text" value="" />

  
    <label><?php _e("Last Name"); ?></label>
    <input name="cc_last_name"  type="text" value="" />


    <label><?php _e("Credit Card"); ?></label>
    <select name="cc_type">
        <option value="Visa" selected><?php _e("Visa"); ?></option>
        <option value="MasterCard"><?php _e("MasterCard"); ?></option>
        <option value="Discover"><?php _e("Discover"); ?></option>
        <option value="Amex"><?php _e("American Express"); ?></option>
      </select>


    <label><?php _e("Credit Card Number"); ?></label>
    <input name="cc_number"  type="text" value="" />

  
    <label><?php _e("Expiration Date"); ?></label>
    <input name="cc_month" class="input-mini" placeholder="<?php _e("Month"); ?>" style="margin-right:10px;"  type="text" value="" />
    <input name="cc_year" class="input-mini" placeholder="<?php _e("Year"); ?>"  type="text" value="" />

    <label><?php _e("Verification Code"); ?></label>
    <input name="cc_verification_value"  type="text" value="" />
<div class="cc_process_error"></div>


<?php 

$paypal_is_test = (mw('option')->get('paypalpro_testmode', 'payments')) == 'y'; 
 
?>
 
<?php if($paypal_is_test == true and is_admin()): ?>
<small><?php print mw('format')->notif("You are using Paypal Pro in test mode!"); ?></small>
<?php endif; ?>






