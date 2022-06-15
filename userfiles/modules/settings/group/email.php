<?php must_have_access(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        //mw.options.form('.<?php print $config['module_class'] ?> .js-holder-email-server-settings', function () {
        //mw.options.form('.<?php //print $config['module_class'] ?>// .js-holder-email-server-settings', function () {
        //    mw.notification.success("<?php //_ejs("Email settings are saved"); ?>//.",undefined,'email');
        //    mw.reload_module("<?php //print $config['module'] ?>//");
        //});







        $("select.js-email-transport-select").on('change', function () {
                mw.options.save(this, function () {


                    mw.notification.success("<?php _ejs("Email settings are saved"); ?>.",undefined,'email');
                    mw.reload_module("<?php print $config['module'] ?>");

                });

        });






        $("input, select, textarea", '.js-holder-email-names-settings').on('input', function () {
            mw.on.stopWriting(this, function () {
                 mw.options.save(this, function () {
                     mw.notification.success("<?php _ejs("Email settings are saved"); ?>.",undefined,'email');
                 });
            });
        });
 $("input, select, textarea", '.js-email-transport-select-user-pass').on('input', function () {
            mw.on.stopWriting(this, function () {
                 mw.options.save(this, function () {
                     mw.notification.success("<?php _ejs("Email settings are saved"); ?>.",undefined,'email');
                 });
            });
        });

    });
</script>



<script type="text/javascript">

    function saveEmailOptions(notification) {

        if (!!notification) {
            notification = true;
        }

        $("input, select, textarea", $('.<?php print $config['module_class'] ?>')).each(function () {
            mw.options.save(this, function () {
                // saved
            });
        });

        if (notification) {
            mw.notification.success("<?php _ejs("Email settings are saved"); ?>.",undefined,'email');
        }

        mw.reload_module("<?php print $config['module'] ?>");
    }

    $(document).ready(function () {
        //$('.js-email-transport-select').change(function () {
        //    saveEmailOptions();
        //    mw.reload_module("<?php //print $config['module'] ?>//");
        //});
    });


    /*
     $(document).ready(function(){
     mw.options.form(".<?php print $config['module_class'] ?>", function () {
     mw.notification.success("<?php _ejs("Email settings are saved"); ?>.");
     mw.reload_module("<?php print $config['module'] ?>");
     });
     });
     */
    mw.email_send_test = function () {

        $("input, select, textarea", $('.<?php print $config['module_class'] ?>')).each(function () {
            mw.options.save(this, function () {
                // saved
            });
        });

        var email_to = {}
        email_to.to = $('#test_email_to').val();
        email_to.subject = $('#test_email_subject').val();

        $.post("<?php print route('admin.notification.test_mail'); ?>", email_to, function (msg) {
            if(msg.success) {
                mw.dialog({
                    html: "<pre>Success</pre>",
                    title: "Email send results..."
                });
            } else {
                var err = 'Error';
                if(msg.error){
                    var err = msg.error;
                }
                mw.dialog({
                    html: "<pre>"+err+"</pre>",
                    title: "Email send failed..."
                });
            }
        });
    }
</script>

<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0 card-settings  js-holder-email-names-settings">
        <div class="card-header px-0">
            <h5><i class="mdi mdi-email-outline text-primary mr-3"></i> <strong><?php _e("E-mail"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3 px-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("System e-mail website settings"); ?></h5>
                    <small class="text-muted">
                        <?php _e("Deliver messages related with new registration, password resets and others system functionalities."); ?>
                    </small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("From e-mail address"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("The website will send emails on behalf of this address"); ?></small>
                                        <input name="email_from" class="mw_option_field form-control" type="email" type="text" option-group="email" value="<?php print get_option('email_from', 'email'); ?>" placeholder="Ex. noreply@yourwebsite.com"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("From name"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("The website will use this name for the emails"); ?></small>
                                        <input name="email_from_name" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('email_from_name', 'email'); ?>" placeholder="<?php _e("Ex. Your Website Name"); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-none style-1 mb-0 card-settings js-holder-email-server-settings">
        <div class="card-body pt-3 px-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("General e-mail provider settings"); ?></h5>
                    <small class="text-muted">
                        <?php _e("Set up your email provider."); ?>
                        <?php _e("The general e-mail provider will deliver all messages related with the website. Including system messages and contact form messages."); ?>
                    </small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Send email function"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Select a provider or email feature to use for sending emails"); ?></small>
                                        <?php
                                        $email_transport = get_option('email_transport', 'email');
                                        if ($email_transport == false) {
                                            $email_transport = 'php';
                                        }
                                        ?>
                                        <select name="email_transport"  autocomplete="off" class="xxxmw_option_field selectpicker js-email-transport-select" data-width="100%" type="text" option-group="email" data-refresh="settings/group/email">
                                            <option value="php" <?php if ($email_transport == 'php'): ?> selected="selected" <?php endif; ?>><?php _e("PHP mail function"); ?></option>
                                            <option value="gmail" <?php if ($email_transport == 'gmail'): ?> selected="selected" <?php endif; ?>><?php _e("GMail"); ?></option>
                                            <?php /* <option value="yahoo" <?php if($email_transport == 'yahoo'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("Yahoo"); ?>
				        </option>
				        <option value="hotmail" <?php if($email_transport == 'hotmail'): ?> selected="selected" <?php endif; ?>>
				        <?php _e("HotMail"); ?>
				        </option>*/ ?>
                                            <option value="smtp" <?php if ($email_transport == 'smtp'): ?> selected="selected" <?php endif; ?>><?php _e("SMTP server"); ?></option>
                                            <option value="cpanel" <?php if ($email_transport == 'cpanel'): ?> selected="selected" <?php endif; ?>><?php _e("cPanel"); ?></option>
                                            <option value="plesk" <?php if ($email_transport == 'plesk'): ?> selected="selected" <?php endif; ?>><?php _e("Plesk"); ?></option>
                                            <option value="config" <?php if ($email_transport == 'config'): ?> selected="selected" <?php endif; ?>><?php _e("Use system configuration"); ?></option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row js-email-transport-select-user-pass">

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <?php if ($email_transport == 'php'): ?>
                                                <small class="text-muted d-block mb-4 mt-0"><?php _e("PHP mail is the built in PHP function that is used to send emails from PHP scripts."); ?></small>
                                            <?php elseif ($email_transport == 'gmail'): ?>
                                                <small class="text-muted d-block mb-4 mt-0"><?php _e("Type your gmail account to use a Gmail provider."); ?></small>
                                            <?php elseif ($email_transport == 'smtp'): ?>
                                                <small class="text-muted d-block mb-4 mt-0"><?php _e("Type your credentials below."); ?></small>
                                            <?php elseif ($email_transport == 'cpanel'): ?>
                                                <small class="text-muted d-block mb-4 mt-0"><?php _e("Type your cPanel account to use it."); ?></small>
                                            <?php elseif ($email_transport == 'plesk'): ?>
                                                <small class="text-muted d-block mb-4 mt-0"><?php _e("Type your Plesk account to use it."); ?></small>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($email_transport == 'smtp' or $email_transport == 'cpanel' or $email_transport == 'plesk' or $email_transport == 'gmail' or $email_transport == 'yahoo' or $email_transport == 'hotmail' or $email_transport == 'smtp'): ?>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php print titlelize($email_transport) . ' ' . _e("Username", true); ?></label>
                                                    <input name="smtp_username" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('smtp_username', 'email'); ?>" placeholder="<?php _e("example"); ?>: <?php _e("user@email.com"); ?>"/>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php print titlelize($email_transport) . ' ' . _e("Password", true); ?></label>
                                                    <input name="smtp_password" class="mw_option_field form-control" autocomplete="one-time-code" type="password" option-group="email" value="<?php print get_option('smtp_password', 'email'); ?>"/>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($email_transport == 'smtp' || $email_transport == 'plesk' || $email_transport == 'cpanel'): ?>
                                            <div class="<?php if ($email_transport == 'smtp'): ?>col-12<?php else: ?>col-12<?php endif; ?>">
                                                <div class="form-group">
                                                    <label class="control-label"><?php _e("Smtp Email Host"); ?></label>
                                                    <input name="smtp_host" class="mw_option_field form-control" autocomplete="off" type="text" option-group="email" value="<?php print get_option('smtp_host', 'email'); ?>" placeholder="<?php _e("example"); ?>: smtp.gmail.com"/>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($email_transport == 'smtp'): ?>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php _e("Smtp Email Port"); ?></label>
                                                    <input name="smtp_port" class="mw_option_field form-control"  autocomplete="off" type="text" option-group="email" value="<?php print get_option('smtp_port', 'email'); ?>" placeholder="<?php _e("example"); ?>: 587 or 995, 465, 110, 25"/>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php _e("Enable SMTP authentication"); ?></label>
                                                    <?php $email_smtp_auth = get_option('smtp_auth', 'email'); ?>

                                                    <select  autocomplete="off" name="smtp_auth" class="form-control mw_option_field" type="text" option-group="email" data-refresh="settings/group/email">
                                                        <option value="ssl" <?php if ($email_smtp_auth == 'ssl'): ?> selected="selected" <?php endif; ?>>ssl</option>
                                                        <option value="tls" <?php if ($email_smtp_auth == 'tls'): ?> selected="selected" <?php endif; ?>>tls</option>
                                                        <option value="" <?php if ($email_smtp_auth == ''): ?> selected="selected" <?php endif; ?>>none</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="control-label"><?php _e("Enable SMTP Secure Method"); ?></label>
                                                    <?php $email_smtp_secure = get_option('smtp_secure', 'email'); ?>

                                                    <select  autocomplete="off" name="smtp_secure" class="form-control mw_option_field" type="text" option-group="email" data-refresh="settings/group/email">
                                                        <option value="0" <?php if ($email_smtp_secure == ''): ?> selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
                                                        <option value="1" <?php if ($email_smtp_secure == '1'): ?> selected="selected" <?php endif; ?>><?php _e("SSL"); ?></option>
                                                        <option value="2" <?php if ($email_smtp_secure == '2'): ?> selected="selected" <?php endif; ?>><?php _e("TLS"); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <a class="btn btn-outline-primary btn-sm"  href="javascript:;" onclick="$('.js-test-email').mwDialog();"><span class="mw-icon-beaker mr-1"></span> <?php _e("Test Mail Sending Method"); ?></a>

                                    <button onClick="saveEmailOptions(1)" class="btn btn-success btn-sm"><?php _e("Save email settings"); ?></button>
                                </div>


                                <div class="col-12 d-none">
                                    <div class="js-test-email">

                                        <h4><?php _e("Send test email"); ?></h4>
                                        <p class="text-muted"><?php _e("Send test email to check settings are they work correctly."); ?></p>

                                        <div class="form-group">
                                            <label class="control-label" for="test_email_to"><?php _e("Send test email to"); ?></label>
                                            <input name="test_email_to" id="test_email_to" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_to', 'email'); ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="test_email_subject"><?php _e("Test mail subject"); ?></label>
                                            <input name="test_email_subject" id="test_email_subject" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_subject', 'email'); ?>"/>
                                        </div>

                                        <pre id="email_send_test_btn_output"></pre>

                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-between">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="mw.dialog.get(this).remove()"><?php _e("Cancel"); ?></button>
                                                <button type="button" onclick="mw.email_send_test();" class="btn btn-success btn-sm" id="email_send_test_btn"><?php _e("Send Test Email"); ?></button>
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
    </div>
</div>
