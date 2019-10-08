<?php only_admin_access(); ?>

<div class="m-b-20">
    <img src="<?php print $config['url_to_module'] ?>bank_transfer.png" style="max-width: 140px;"/>
</div>

<ul class="mw-ui-inline-list">
    <li>
        <label class="mw-ui-label p-10 bold"><?php _e("Show additional details"); ?>?:</label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_show_msg" class="mw_option_field" data-option-group="payments" value="y" type="radio" <?php if (get_option('bank_transfer_show_msg', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("Yes"); ?></span>
        </label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_show_msg" class="mw_option_field" data-option-group="payments" value="n" type="radio" <?php if (get_option('bank_transfer_show_msg', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("No"); ?></span>
        </label>
    </li>
</ul>


<label class="mw-ui-label"><?php _e('Additional details'); ?></label>
<textarea name="bank_transfer_msg" class="mw-ui-field mw_option_field block-field" data-option-group="payments"><?php print get_option('bank_transfer_msg', 'payments') ?></textarea>
<p><?php _e('Displays to customers when they’re choosing a payment method.'); ?></p>


<ul class="mw-ui-inline-list" style="margin-top:20px;">
    <li>
        <label class="mw-ui-label p-10 bold"><?php _e("Show payment instructions"); ?>?:</label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_show_instructions" class="mw_option_field" data-option-group="payments" value="y" type="radio" <?php if (get_option('bank_transfer_show_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("Yes"); ?></span>
        </label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_show_instructions" class="mw_option_field" data-option-group="payments" value="n" type="radio" <?php if (get_option('bank_transfer_show_instructions', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("No"); ?></span>
        </label>
    </li>
</ul>

<ul class="mw-ui-inline-list" style="margin-bottom:20px;">
    <li>
        <label class="mw-ui-label p-10 bold"><?php _e("Send email with payment instructions"); ?>?:</label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_send_email_instructions" class="mw_option_field" data-option-group="payments" value="y" type="radio" <?php if (get_option('bank_transfer_send_email_instructions', 'payments') == 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("Yes"); ?></span>
        </label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input name="bank_transfer_send_email_instructions" class="mw_option_field" data-option-group="payments" value="n" type="radio" <?php if (get_option('bank_transfer_send_email_instructions', 'payments') != 'y'): ?> checked="checked" <?php endif; ?> >
            <span></span>
            <span><?php _e("No"); ?></span>
        </label>
    </li>
</ul>


<label class="mw-ui-label"><?php _e('Payment instructions'); ?></label>
<br >
<p>Use <b>{{order_id}}</b> to show id of order in payment instructions.</p>
<textarea name="bank_transfer_instructions" class="mw-ui-field mw_option_field block-field" data-option-group="payments"><?php print get_option('bank_transfer_instructions', 'payments') ?></textarea>
<p style="margin-bottom:20px;"><?php _e('Displays to customers after they place an order with this payment method.'); ?></p>