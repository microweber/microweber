<?php only_admin_access(); ?>

<div class="m-b-20">
    <img src="<?php print $config['url_to_module'] ?>bank_transfer.png" style="max-width: 140px;"/>
</div>

<ul class="mw-ui-inline-list">
    <li>
        <label class="mw-ui-label p-10 bold"><?php _e("Show message"); ?>?:</label>
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


<label class="mw-ui-label"><?php _e('Message'); ?></label>
<textarea name="bank_transfer_msg" class="mw-ui-field mw_option_field block-field" data-option-group="payments"><?php print get_option('bank_transfer_msg', 'payments') ?></textarea>
 