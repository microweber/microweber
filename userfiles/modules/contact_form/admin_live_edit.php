<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>

<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings" id="form_options"><i class="mdi mdi-email-outline mr-1"></i> <?php _e('Settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings-advanced"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Advanced Settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#custom-fields"><i class="mdi mdi-pencil-box-outline mr-1"></i> <?php _e('Custom Fields'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
</nav>

<div class="tab-content py-3">
    <script>
        mw.require('editor.js')
        initEditor = function () {
            if (!window.editorLaunced) {
                window.editorLaunced = true;

                mw.Editor({
                    element: document.getElementById('editorAM'),
                });
            }
        }
        $(document).ready(function () {
            $('#form_options').on('click', function () {
                initEditor();
            });
            initEditor()
        });
    </script>

    <div class="tab-pane fade show active" id="settings">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-contact-form-settings">
            <module type="settings/list" for_module="<?php print($config['module']) ?>" for_module_id="<?php print $params['id'] ?>"/>
            <module type="contact_form/settings_live_edit" for_module_id="<?php print $params['id'] ?>"/>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="settings-advanced">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-contact-form-settings">
            <?php
            $mod_id = $params['id'];
            if (isset($params['for_module_id'])) {
                $mod_id = $params['for_module_id'];
            }
            ?>

            <module type="contact_form/manager/assign_list_to_module" data-for-module="<?php print($config['module_name']) ?>" data-for-module-id="<?php print $params['id'] ?>"/>
            <hr class="thin"/>

            <h5 class="font-weight-bold mb-3"><?php _e("Contact form advanced settings") ?></h5>

            <module type="admin/mail_providers/integration_select" option_group="contact_form"/>

            <hr class="thin"/>

            <div class="form-group">
                <label class="control-label"><?php _e("Newsletter") ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Show the newsletter subscription checkbox?") ?></small>

                <div class="custom-control custom-checkbox mb-4">
                    <input type="checkbox" parent-reload="true" name="newsletter_subscription" id="newsletter_subscription" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field custom-control-input" option-group="<?php print $mod_id ?>" <?php if (get_option('newsletter_subscription', $mod_id) == 'y'): ?>checked<?php endif; ?> />
                    <label class="custom-control-label" for="newsletter_subscription"><?php _e("Enable newsletter checkbox"); ?></label>
                </div>
            </div>

            <hr class="thin"/>

            <module type="contact_form/privacy_settings" simple="true"/>

            <?php if ($mod_id != 'contact_form_default') : ?>
                <br/>
                <div class="form-group">
                    <label class="control-label"><?php _e("Captcha settings") ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("Setup your captcha preferences from ") ?><a href="<?php print admin_url('view:modules/load_module:captcha'); ?>" target="_blank"><?php _e("Captcha module") ?></a></small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="disable_captcha" id="disable_captcha" value="y" option-group="<?php print $mod_id ?>" class="mw_option_field custom-control-input" <?php if (get_option('disable_captcha', $mod_id) == 'y'): ?>checked <?php endif; ?>/>
                        <label class="custom-control-label" for="disable_captcha"><?php _e("Disable Code Verification ex"); ?>.: <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" alt="" style="margin-top: -8px;"/></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e("Redirect URL"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("Redirect to URL after submit for example for “Thank you” page") ?></small>
                    <input name="email_redirect_after_submit" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_redirect_after_submit', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
                </div>
            <?php endif; ?>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="custom-fields">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-contact-form-settings">
            <h5 class="font-weight-bold"><?php _e("Contact Form Fields"); ?></h5>
            <small class="text-muted d-block"><?php _e("Add / Edit fields of the form using the custom fields") ?></small>
            <module type="custom_fields" view="admin" data-for="module" for-id="<?php print $params['id'] ?>"/>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="templates">
        <module type="admin/modules/templates"/>
    </div>
</div>
