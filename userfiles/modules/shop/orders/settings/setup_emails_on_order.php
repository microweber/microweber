<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>
<h1 class="main-pages-title"><?php _e('Autorespond E-mail settings'); ?></h1>


<div class="card mb-7">
    <div class="card-body <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">
            <script type="text/javascript">
                $(document).ready(function () {
                    mw.options.form('.<?php print $config['module_class'] ?>', function () {
                        mw.notification.success("<?php _ejs("Saved"); ?>.");
                    });
                });
            </script>


            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('New order'); ?></h5>
                <small class="text-muted"><?php _e('Email on new order'); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="order_email_enabled" id="order_email_enabled" class="mw_option_field form-check-input" data-option-group="orders" data-value-checked="1" data-value-unchecked="0" <?php if (get_option('order_email_enabled', 'orders') == 1): ?>checked<?php endif; ?>>
                                        <label class="custom-control-label" for="order_email_enabled"><?php _e("Send email to the customer when new order is received"); ?></label>
                                    </div>
                                </div>

                               <div class="d-flex align-items-center justify-content-between">
                                   <small class="text-muted d-block mb-2">
                                       <?php _e("You must have a working email setup in order to send emails"); ?>.
                                   </small>
                                   <a class="btn btn-outline-primary btn-sm ml-2 mt-xl-0 mt-3" target="_blank" href="<?php print admin_url('settings?group=email'); ?>"><?php _e("check your e-mail settings"); ?></a>

                               </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="card mb-7">
    <div class="card-body <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">

            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Send email to'); ?></h5>
                <small class="text-muted"><?php _e('Choose to whom the system need to send an email'); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">

                                <?php $send_email_on_new_order = get_option('send_email_on_new_order', 'orders'); ?>

                                <div class="form-group">
                                    <label class="form-label"><?php _e("Send email to"); ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e('Send the autorespond emails to'); ?></small>
                                    <select class="mw_option_field form-select" data-width="100%" data-option-group="orders" name="send_email_on_new_order">
                                        <option value="" <?php if ($send_email_on_new_order == ''): ?>selected="selected"<?php endif; ?>><?php _e('Default (Admins & Client)'); ?></option>
                                        <option value="admins" <?php if ($send_email_on_new_order == 'admins'): ?>selected="selected"<?php endif; ?>><?php _e('Only Admins'); ?></option>
                                        <option value="client" <?php if ($send_email_on_new_order == 'client'): ?>selected="selected"<?php endif; ?>><?php _e('Only Client'); ?></option>
                                    </select>
                                </div>

                                <div class="row p-0">
                                    <label class="form-label d-block"><?php _e("Send email when"); ?></label>

                                    <div class="custom-control custom-radio d-inline-block my-1">
                                        <input name="order_email_send_when" id="order_email_send_when_1" class="mw_option_field form-check-input" data-option-group="orders" value="order_received" type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_received' ): ?> checked="checked" <?php endif; ?> >
                                        <label class="custom-control-label" for="order_email_send_when_1"><?php _e('Order is received'); ?></label>
                                    </div>

                                    <div class="custom-control custom-radio d-inline-block my-1">
                                        <input name="order_email_send_when" id="order_email_send_when_2" class="mw_option_field form-check-input" data-option-group="orders" value="order_paid" type="radio" <?php if (get_option('order_email_send_when', 'orders') == 'order_paid'): ?> checked="checked" <?php endif; ?> >
                                        <label class="custom-control-label" for="order_email_send_when_2"><?php _e('Order is paid'); ?></label>
                                    </div>

                                    <div class="custom-control custom-radio d-inline-block my-1">
                                        <input name="order_email_send_when" id="order_email_send_when_0" class="mw_option_field form-check-input" data-option-group="orders" value="" type="radio" <?php if (get_option('order_email_send_when', 'orders') == ''): ?> checked="checked" <?php endif; ?> >
                                        <label class="custom-control-label" for="order_email_send_when_0"><?php _e('Disable'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="card mb-7">
    <div class="card-body <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Choose an email provider'); ?></h5>
                <small class="text-muted"><?php _e('Select and set up the email provider with which you will send and collect emails'); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                <module type="admin/mail_providers/integration_select" option_group="shop"/>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-7">
    <div class="card-body <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">

            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Email Templates'); ?></h5>
                <small class="text-muted"><?php _e('Choose the email templates to send or add new one and use in special events '); ?></small>
            </div>
            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">

                                    <module type="admin/mail_templates/select_template" option_group="orders" mail_template_type="new_order"/>

                                    <a class="btn btn-primary mt-3" id="mail-test-btn" href="javascript:void(0);" onclick="$('#test_ord_eml_toggle').show();$(this).hide();"><?php _e("Send test email"); ?></a>
                                </div>

                                <div id="test_ord_eml_toggle" style="display:none">
                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Send test email to"); ?></label>
                                        <input name="test_email_to" id="test_email_to" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_to', 'email'); ?>"/>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" onclick="checkout_confirm_email_test();" class="btn btn-success" id="email_send_test_btn"><?php _e("Send the email"); ?></button>
                                        <pre id="email_send_test_btn_output"></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
