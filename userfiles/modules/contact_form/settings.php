<?php
include(__DIR__ . '/settings_javascript.php');
?>

<?php
$mod_id = $params['id'];
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<div id="form_email_options">
    <div class="row d-flex align-items-center">

        <?php  if ($mod_id == 'contact_form_default'): ?>
            <div class="col">
                <h5 class="font-weight-bold mb-3"><?php _e("Global contact form settings") ?></h5>
            </div>
        <?php else: ?>

            <div class="col">
                <h5 class="font-weight-bold"><?php _e("Current contact form settings") ?></h5>
            </div>
        <?php endif; ?>

        <div class="col text-right">
            <a class="btn btn-outline-primary btn-sm" href="<?php print admin_url('view:settings#option_group=email') ?>" target="_blank"><i class="mdi mdi-email-send"></i> <?php _e("E-mail sending options"); ?></a>
        </div>
    </div>

    <div class="mt-2">

        <h5 class="font-weight-bold"><?php _e("Global sender") ?></h5>

        <div class="form-group mb-3">
            <label class="control-label"><?php _e("Use custom sender settings"); ?></label>
            <small class="text-muted d-block mb-2">
                <?php _e('Use custom sender settings for the global contact forms.'); ?>
                <br />
                <?php _e('By default we will use website system e-mail settings.'); ?>
                <a href="<?php print admin_url('view:settings#option_group=email'); ?>" target="_blank"><?php _e('You can change the system e-mail settings here.'); ?></a>
            </small>
        </div>

        <div class="form-group mb-4">
            <?php  $enableCustomSender = \MicroweberPackages\Option\Facades\Option::getValue('enable_custom_sender', $mod_id); ?>
            <div class="custom-control custom-switch pl-0">
                <label class="d-inline-block mr-5" for="enable_custom_sender">No</label>
                <input type="checkbox" onchange="toggleCustomSender(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field custom-control-input" name="enable_custom_sender" option-group="<?php print $mod_id ?>" id="enable_custom_sender" value="y" <?php if ($enableCustomSender): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="enable_custom_sender">Yes</label>
            </div>
        </div>

        <script type="text/javascript">
            toggleCustomSender = function (e) {
                var el = e.target;
                if ($(el).is(":checked")) {
                    $('.js-custom-sender').fadeIn();
                } else {
                    $('.js-custom-sender').fadeOut();
                }

            };
        </script>

        <div class="js-custom-sender" <?php if (!$enableCustomSender): ?> style="display:none"<?php endif; ?>>
            <div class="form-group">
                <label class="control-label"><?php _e("From e-mail address"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("The e-mail address which will send the message"); ?></small>
                <input name="email_from" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("From name"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("e.x. your name, company or brand name"); ?></small>
                <input name="email_from_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from_name', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
            </div>
        </div>

        <hr class="thin"/>

        <h5 class="font-weight-bold"><?php _e("Global Receivers") ?></h5>

        <div class="form-group">
            <label class="control-label"><?php _e("To e-mail address"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("E-mail address of the receiver"); ?></small>
            <input name="email_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_to', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Reply to e-mail"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Send a copy of the email to one more email address"); ?></small>
            <input name="email_reply" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_reply', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("BCC e-mails"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("Those addresses seperated with comma are invisible to the recipients of the email"); ?></small>
            <input name="email_bcc" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_bcc', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>
    </div>

    <hr class="thin"/>

    <h5 class="font-weight-bold mb-3"><?php _e("Global auto-respond message to user"); ?></h5>

    <div class="">

        <div class="form-group mb-3">
            <label class="control-label"><?php _e("Enable auto-respond message to user"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e('Allow users to receive Thank you emails after subscription.'); ?></small>
        </div>

        <div class="form-group mb-4">
            <?php  $enableAutoRespond = \MicroweberPackages\Option\Facades\Option::getValue('enable_auto_respond', $mod_id); ?>
            <div class="custom-control custom-switch pl-0">
                <label class="d-inline-block mr-5" for="enable_auto_respond">No</label>
                <input type="checkbox" onchange="toggleAutoRespondFields(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field custom-control-input" name="enable_auto_respond" option-group="<?php print $mod_id ?>" id="enable_auto_respond" value="y" <?php if ($enableAutoRespond): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="enable_auto_respond">Yes</label>
            </div>
        </div>

        <script type="text/javascript">
            toggleAutoRespondFields = function (e) {
                var el = e.target;
                if ($(el).is(":checked")) {
                    $('.js-auto-respond-fields').fadeIn();
                } else {
                    $('.js-auto-respond-fields').fadeOut();
                }

            };
        </script>

        <div class="js-auto-respond-fields" <?php if (!$enableAutoRespond): ?> style="display:none"<?php endif; ?>>
            <div class="form-group">
                <label class="control-label"><?php _e("Autorespond Subject"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Auto-responders allows you to set up automated replies to incoming email"); ?> <br/><?php _e("E.x. “Thank you for your request”"); ?></small>
                <input name="email_autorespond_subject" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_subject', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Autorespond Message"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Autorespond e-mail sent back to the user"); ?></small>
                <textarea id="editorAM" name="email_autorespond" class="mw_option_field form-control" option-group="<?php print $mod_id ?>"><?php print get_option('email_autorespond', $mod_id); ?></textarea>

                <label class="control-label"><span class="ico ismall_warn"></span>
                    <small><?php _e("Autorespond e-mail sent back to the user"); ?></small>
                </label>
            </div>
            <div class="form-group">
                <module type="admin/components/file_append" option_group="<?php print $mod_id ?>"/>
            </div>
        </div>

    </div>
</div>
