<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <h5>
            <i class="mdi mdi-email-edit-outline module-icon-svg-fill"></i> <strong><?php _e("Autorespond E-mail settings"); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.<?php print $config['module_class'] ?>', function () {
                    mw.notification.success("<?php _ejs("Saved"); ?>.");
                });
            });
        </script>

        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="order_email_enabled" id="order_email_enabled" class="mw_option_field custom-control-input" data-option-group="orders" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('order_email_enabled', 'orders') == 1): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="order_email_enabled"><?php _e("Enable e-mail on new order"); ?></label>
            </div>
        </div>

        <small class="text-muted d-block mb-2">
            <?php _e("You must have a working email setup in order to send emails"); ?>.
            <a class="btn btn-outline-primary btn-sm ml-2" target="_blank" href="<?php print admin_url('view:settings#option_group=email'); ?>"><?php _e("check your e-mail settings"); ?></a>
        </small>

        <hr class="thin" />

        <?php $send_email_on_new_order = get_option('send_email_on_new_order', 'orders'); ?>

        <div class="form-group">
            <label class="control-label"><?php _e("Send email to"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e('Send the autorespond emails to'); ?></small>
            <select class="mw_option_field selectpicker" data-width="100%" data-option-group="orders" name="send_email_on_new_order">
                <option value="" <?php if ($send_email_on_new_order == ''): ?>selected="selected"<?php endif; ?>><?php _e('Default (Admins & Client)'); ?></option>
                <option value="admins" <?php if ($send_email_on_new_order == 'admins'): ?>selected="selected"<?php endif; ?>><?php _e('Only Admins'); ?></option>
                <option value="client" <?php if ($send_email_on_new_order == 'client'): ?>selected="selected"<?php endif; ?>><?php _e('Only Client'); ?></option>
            </select>
        </div>

        <div class="form-group">
            <label class="control-label d-block"><?php _e("Send email when"); ?></label>

            <div class="custom-control custom-radio d-inline-block mr-3">
                <input name="order_email_send_when" id="order_email_send_when_0" class="mw_option_field custom-control-input" data-option-group="orders" value="" type="radio" <?php if (get_option('order_email_send_when', 'orders') == ''): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="order_email_send_when_0"><?php _e('Disable'); ?></label>
            </div>


            <div class="custom-control custom-radio d-inline-block mr-3">
                <input name="order_email_send_when" id="order_email_send_when_1" class="mw_option_field custom-control-input" data-option-group="orders" value="order_received" type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_received' ): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="order_email_send_when_1"><?php _e('Order is received'); ?></label>
            </div>

            <div class="custom-control custom-radio d-inline-block mr-3">
                <input name="order_email_send_when" id="order_email_send_when_2" class="mw_option_field custom-control-input" data-option-group="orders" value="order_paid" type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_paid'): ?> checked="checked" <?php endif; ?> >
                <label class="custom-control-label" for="order_email_send_when_2"><?php _e('Order is paid'); ?></label>
            </div>
        </div>

        <hr class="thin" />

        <module type="admin/mail_providers/integration_select" option_group="shop"/>

        <hr class="thin"/>

        <div class="form-group">
            <h5 class="font-weight-bold mb-2"><?php _e("Select New Order Email Template"); ?></h5>

            <module type="admin/mail_templates/select_template" option_group="orders" mail_template_type="new_order"/>

            <a class="btn btn-primary mt-3" id="mail-test-btn" href="javascript:void(0);" onclick="$('#test_ord_eml_toggle').show();$(this).hide();"><?php _e("Send test email"); ?></a>
        </div>

        <div id="test_ord_eml_toggle" style="display:none">
            <div class="form-group">
                <label class="control-label"><?php _e("Send test email to"); ?></label>
                <input name="test_email_to" id="test_email_to" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_to', 'email'); ?>"/>
            </div>

            <div class="form-group">
                <button type="button" onclick="checkout_confirm_email_test();" class="btn btn-success" id="email_send_test_btn"><?php _e("Send the email"); ?></button>
                <pre id="email_send_test_btn_output"></pre>
            </div>
        </div>
    </div>
</div>
<script>checkout_confirm_email_test = function () {
        var email_to = {}
        email_to.to = $('#test_email_to').val();
        //email_to.subject = $('#test_email_subject').val();
        $.post("<?php print site_url('api_html/checkout_confirm_email_test'); ?>", email_to, function (msg) {
             mw.alert(msg);

        });
    }</script>
