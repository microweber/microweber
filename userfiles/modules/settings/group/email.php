<?php must_have_access(); ?>

<script>
    mw.require('forms.js');
</script>

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

<h1 class="main-pages-title"><?php _e('E-Mail'); ?></h1>


<div class="<?php print $config['module_class'] ?>">
    <div class="card mb-5 card-settings  js-holder-email-names-settings">

        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("System e-mail website settings"); ?></h5>
                    <small class="text-muted">
                        <?php _e("Deliver messages related with new registration, password resets and others system functionalities."); ?>
                    </small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-4">
                                        <label class="form-label"><?php _e("From e-mail address"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("The website will send emails on behalf of this address"); ?></small>
                                        <input name="email_from" class="mw_option_field form-control" type="email" type="text" option-group="email" value="<?php print get_option('email_from', 'email'); ?>" placeholder="e.g. noreply@yourwebsite.com"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><?php _e("From name"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("The website will use this name for the emails"); ?></small>
                                        <input name="email_from_name" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('email_from_name', 'email'); ?>" placeholder="<?php _e("e.g. Your Website Name"); ?>"/>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <?php
                                        $logo = get_option('logo', 'email');

                                        $nologo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/no-image.svg';
                                        if (!$logo) {
                                            $logo = $nologo;
                                        }
                                        ?>
                                        <script>
                                            $(document).ready(function () {
                                                websiteLogo = mw.uploader({
                                                    element: document.getElementById('js-upload-logo-image'),
                                                    filetypes: 'images',
                                                    multiple: false
                                                });
                                                $(websiteLogo).on('FileUploaded', function (a, b) {
                                                    mw.$("#logo-preview").val(b.src).trigger('input');
                                                    mw.$(".js-logo").attr('src', b.src);
                                                    // mw.$("link[rel*='icon']").attr('href', b.src);
                                                });
                                                mw.element('#remove-logo-btn').on('click', function(){
                                                    mw.element('#logo-preview').val('').trigger('change')
                                                    mw.element('.js-logo').attr('src', '<?php print $nologo; ?>');
                                                })
                                            })

                                        </script>
                                        <br>

                                        <label class="form-label"><?php _e("Email Logo"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Select an logo for your website emails.'); ?></small>
                                        <div class="d-flex">
                                            <div class="avatar img-absolute border-radius-0 border-silver me-3" >
                                                <img src="<?php print $logo; ?>" class="js-logo" />
                                                <input type="hidden" class="mw_option_field" name="logo" id="logo-preview" value="<?php print $logo; ?>" option-group="email" />
                                            </div>
                                            <button type="button" class="btn btn-outline-primary" id="js-upload-logo-image"><?php _e("Upload logo"); ?></button>
                                            <span class="tip mw-btn-icon" id="remove-logo-btn"   data-bs-toggle="tooltip" aria-label="Clear logo" data-bs-original-title="Clear logo">
                                                <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/trash.svg" alt="">

                                             </span>
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

    <div class="card mb-3 card-settings js-holder-email-server-settings">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("General e-mail provider settings"); ?></h5>
                    <small class="text-muted">
                        <?php _e("Set up your email provider."); ?>
                        <?php _e("The general e-mail provider will deliver all messages related with the website. Including system messages and contact form messages."); ?>
                    </small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">

                            <div class="row" style="padding-bottom: 0;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Send email function"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Select a provider or email feature to use for sending emails"); ?></small>
                                        <?php
                                        $email_transport = get_option('email_transport', 'email');
                                        if ($email_transport == false) {
                                            $email_transport = 'php';
                                        }
                                        ?>
                                        <select name="email_transport"  autocomplete="off" class="xxxmw_option_field form-select js-email-transport-select" data-width="100%" type="text" option-group="email" data-refresh="settings/group/email">
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
                                            <option value="config" <?php if ($email_transport == 'config'): ?> selected="selected" <?php endif; ?>><?php _e("Read from Laravel - config/mail.php"); ?></option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="px-md-3">
                                <div class="card mx-md-3 row js-email-transport-select-user-pass">

                                    <div class="card-body">
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

                                        <?php if ($email_transport == 'smtp' or $email_transport == 'cpanel' or $email_transport == 'plesk' or $email_transport == 'gmail' or $email_transport == 'yahoo' or $email_transport == 'hotmail' or $email_transport == 'smtp'): ?>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php print titlelize($email_transport) . ' ' . _e("Username", true); ?></label>
                                                    <input name="smtp_username" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('smtp_username', 'email'); ?>" placeholder="<?php _e("e.g."); ?>: <?php _e("user@email.com"); ?>"/>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php print titlelize($email_transport) . ' ' . _e("Password", true); ?></label>
                                                    <input name="smtp_password" class="mw_option_field form-control" autocomplete="one-time-code" type="password" option-group="email" value="<?php print get_option('smtp_password', 'email'); ?>"/>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($email_transport == 'smtp' || $email_transport == 'plesk' || $email_transport == 'cpanel'): ?>
                                            <div class="<?php if ($email_transport == 'smtp'): ?>col-12<?php else: ?>col-12<?php endif; ?>">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php _e("Smtp Email Host"); ?></label>
                                                    <input name="smtp_host" class="mw_option_field form-control" autocomplete="off" type="text" option-group="email" value="<?php print get_option('smtp_host', 'email'); ?>" placeholder="<?php _e("e.g."); ?>: smtp.gmail.com"/>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($email_transport == 'smtp'): ?>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php _e("Smtp Email Port"); ?></label>
                                                    <input name="smtp_port" class="mw_option_field form-control"  autocomplete="off" type="text" option-group="email" value="<?php print get_option('smtp_port', 'email'); ?>" placeholder="<?php _e("e.g."); ?>: 587 or 995, 465, 110, 25"/>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php _e("Enable SMTP authentication"); ?></label>
                                                    <?php $email_smtp_auth = get_option('smtp_auth', 'email'); ?>

                                                    <select  autocomplete="off" name="smtp_auth" class="form-select mw_option_field" type="text" option-group="email" data-refresh="settings/group/email">
                                                        <option value="" <?php if ($email_smtp_auth == ''): ?> selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
                                                        <option value="ssl" <?php if ($email_smtp_auth == 'ssl'): ?> selected="selected" <?php endif; ?>><?php _e("SSL"); ?></option>
                                                        <option value="tls" <?php if ($email_smtp_auth == 'tls'): ?> selected="selected" <?php endif; ?>><?php _e("TLS"); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label form-label-inner"><?php _e("Enable SMTP Secure Method"); ?></label>
                                                    <?php $email_smtp_secure = get_option('smtp_secure', 'email'); ?>

                                                    <select  autocomplete="off" name="smtp_secure" class="form-select mw_option_field" type="text" option-group="email" data-refresh="settings/group/email">
                                                        <option value="0" <?php if ($email_smtp_secure == ''): ?> selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
                                                        <option value="1" <?php if ($email_smtp_secure == '1'): ?> selected="selected" <?php endif; ?>><?php _e("SSL"); ?></option>
                                                        <option value="2" <?php if ($email_smtp_secure == '2'): ?> selected="selected" <?php endif; ?>><?php _e("TLS"); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="col-12 d-flex flex-wrap align-items-center gap-2 justify-content-between mt-4">
                                            <a class="btn btn-primary"  href="javascript:;" onclick="$('.js-test-email').mwDialog();"><?php _e("Test mail sending method"); ?></a>

                                            <button onClick="saveEmailOptions(1)" class="btn btn-success"><?php _e("Save email settings"); ?></button>
                                        </div>
                                    </div>



                                    <div class="col-12 d-none">
                                        <div class="js-test-email">

                                            <h4><?php _e("Send test email"); ?></h4>
                                            <p class="text-muted"><?php _e("Send test email to check settings are they work correctly."); ?></p>

                                            <div class="form-group">
                                                <label class="form-label" for="test_email_to"><?php _e("Send test email to"); ?></label>
                                                <input name="test_email_to" id="test_email_to" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_to', 'email'); ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="test_email_subject"><?php _e("Test mail subject"); ?></label>
                                                <input name="test_email_subject" id="test_email_subject" class="mw_option_field form-control" type="text" option-group="email" value="<?php print get_option('test_email_subject', 'email'); ?>"/>
                                            </div>

                                            <pre id="email_send_test_btn_output"></pre>

                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-between">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="mw.dialog.get(this).remove()"><?php _e("Cancel"); ?></button>
                                                    <button type="button" onclick="mw.email_send_test();" class="btn btn-success btn-sm" id="email_send_test_btn"><?php _e("Send test email"); ?></button>
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
</div>
