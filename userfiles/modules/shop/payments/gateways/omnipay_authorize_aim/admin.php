<?php only_admin_access(); ?>


<label class="mw-ui-label">Api Login Id: </label>


<input type="text" class="mw-ui-field mw_option_field" name="authorize_net_apiLoginId"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('authorize_net_apiLoginId', 'payments'); ?>">

<label class="mw-ui-label">Transaction Key: </label>


<input type="text" class="mw-ui-field mw_option_field" name="authorize_net_transactionKey"
       placeholder="" data-option-group="payments"
       value="<?php print get_option('authorize_net_transactionKey', 'payments'); ?>">





<ul class="mw-ui-inline-list">
<li><label class="mw-ui-label"><?php _e("Test mode"); ?>:</label></li>
 

 <li><label class="mw-ui-check">
    <input name="authorize_net_testMode" class="mw_option_field"    data-option-group="payments"  value="1"  type="radio"  <?php if(get_option('authorize_net_testMode', 'payments') == 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("Yes"); ?></span></label></li>

     <li><label class="mw-ui-check">
    <input name="authorize_net_testMode" class="mw_option_field"    data-option-group="payments"  value="0"  type="radio"  <?php if(get_option('authorize_net_testMode', 'payments') != 1): ?> checked="checked" <?php endif; ?> >
    <span></span><span><?php _e("No"); ?></span></label></li>
</ul>
 



 