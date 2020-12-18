<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="mw-ui-box mw-ui-settings-box mw-ui-box-content m-b-20">
    <div class="m-b-10">
        <h4 class=" pull-left"><?php _e("Enable email on new order"); ?></h4>
        <label class="mw-switch pull-left inline-switch">
            <input type="checkbox" name="order_email_enabled" class="mw_option_field" data-option-group="orders"
                   data-value-checked="1"
                   data-value-unchecked="0"
                <?php if (get_option('order_email_enabled', 'orders') == 1): ?> checked="1" <?php endif; ?>>
            <span class="mw-switch-off">OFF</span>
            <span class="mw-switch-on">ON</span>
            <span class="mw-switcher"></span>
        </label>
        <div class="clearfix"></div>
    </div>

    <small class="mw-ui-label-help">
        <?php _e("You must have a working email setup in order to send emails"); ?>.
        <a class="mw-ui-btn mw-ui-btn-small m-l-10" target="_blank" href="<?php print admin_url('view:settings#option_group=email'); ?>"><?php _e("Setup email here"); ?>.</a>
    </small>
</div>

<?php
$send_email_on_new_order = get_option('send_email_on_new_order', 'orders');
?>
<div class="mw-ui-row m-b-20">
    <div class="mw-ui-col">
        <label class="mw-ui-label bold"><?php _e("Send email to"); ?></label>
        <select class="mw-ui-field mw-full-width mw_option_field"  data-option-group="orders" name="send_email_on_new_order">
            <option value="" <?php if ($send_email_on_new_order == ''): ?>selected="selected"<?php endif; ?>><?php _e('Default (Admins & Client)');?></option>
            <option value="admins" <?php if ($send_email_on_new_order == 'admins'): ?>selected="selected"<?php endif; ?>><?php _e('Only Admins');?></option>
            <option value="client" <?php if ($send_email_on_new_order == 'client'): ?>selected="selected"<?php endif; ?>><?php _e('Only Client');?></option>
        </select>
    </div>
</div>

<div class="mw-ui-row m-b-20">
    <div class="mw-ui-col">
        <label class="mw-ui-label bold"><?php _e("Send email when"); ?></label>

        <label class="mw-ui-check" style="margin-right: 15px;">
            <input name="order_email_send_when" class="mw_option_field" data-option-group="orders" value="order_received"
                   type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_received' || get_option('order_email_send_when', 'orders') == ''): ?> checked="checked" <?php endif; ?> >
            <span></span><span><?php _e('Order is received');?></span>
        </label>

        <label class="mw-ui-check">
            <input name="order_email_send_when" class="mw_option_field" data-option-group="orders" value="order_paid" type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_paid'): ?> checked="checked" <?php endif; ?> >
            <span></span><span><?php _e('Order is paid');?></span>
        </label>
    </div>
</div>


<module type="admin/mail_providers/integration_select" option_group="shop" />
<hr />

<div class="mw-ui-row m-b-20" style="margin: 0 -10px 20px -10px;">
    <div class="mw-ui-col p-12">
        <label class="mw-ui-label bold">
        <?php _e("Select New Order Email Template"); ?>
        </label> 
        
        <module type="admin/mail_templates/select_template" option_group="orders" mail_template_type="new_order" />
	  	
	  	<br />
        <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" style="float: left;" id="mail-test-btn" href="javascript:void(0);" onclick="$('#test_ord_eml_toggle').show();$(this).hide();">
       		<?php _e("Send test email"); ?>
        </a>
    </div>
</div>

<div id="test_ord_eml_toggle" style="display:none">
    <div class="mw-ui-row valign-bottom">
        <div class="mw-ui-col">
            <div class="mw-ui-col-container">
                <label class="mw-ui-label bold">
                    <?php _e("Send test email to"); ?>
                </label>
                <input name="test_email_to" id="test_email_to" class="mw_option_field mw-ui-field" type="text" option-group="email" value="<?php print get_option('test_email_to', 'email'); ?>"/>
            </div>
        </div>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container"> <span onclick="mw.checkout_confirm_email_test();" class="mw-ui-btn mw-ui-btn-green pull-left" id="email_send_test_btn">
            <?php _e("Send the email"); ?>
            </span>
                <pre id="email_send_test_btn_output"></pre>
            </div>
        </div>
    </div>
</div>
