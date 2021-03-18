<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>
<script>mw.require('editor.js')</script>
<script>
    MWEditor.controllers.contactFormInsertEmailVariable = function (scope, api, rootScope, data) {
        this.checkSelection = function (opt) {
            opt.controller.element.disabled = !opt.api.isSelectionEditable();
        };
        this.render = function () {
            var dropdown = new MWEditor.core.dropdown({
                data: [

                    { label: 'email', value: '{email}' },

                ],
                placeholder: rootScope.lang('Insert variable')
            });
            dropdown.select.on('change', function (e, val) {
                api.insertHTML(val.value);
            });
            return dropdown.root;
        };
        this.element = this.render();
    };

    $(document).ready(function () {
        mweditor = mw.Editor({
            selector: '#editorAM',
            mode: 'div',
            smallEditor: false,
            minHeight: 250,
            maxHeight: '70vh',
            controls: [
                [
                    'undoRedo', '|', 'image', '|',
                    {
                        group: {
                            icon: 'mdi mdi-format-bold',
                            controls: ['bold', 'italic', 'underline', 'strikeThrough']
                        }
                    },
                    '|',
                    {
                        group: {
                            icon: 'mdi mdi-format-align-left',
                            controls: ['align']
                        }
                    },
                    '|', 'format',
                    {
                        group: {
                            icon: 'mdi mdi-format-list-bulleted-square',
                            controls: ['ul', 'ol']
                        }
                    },
                    '|', 'link', 'unlink', 'wordPaste', 'table', 'contactFormInsertEmailVariable'
                ],
            ]
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

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

        <?php  if ($mod_id !== 'contact_form_default'): ?>
        <div class="form-group">
            <label class="control-label"><?php _e("Contact form name"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e("What is the name of this contact form?"); ?></small>
            <input name="form_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('form_name', $mod_id); ?>" class="mw_option_field form-control col-6" type="text"/>
        </div>
        <?php endif; ?>

        <h5 class="font-weight-bold"><?php _e("Sender") ?></h5>

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

        <h5 class="font-weight-bold"><?php _e("Receivers") ?></h5>

        <!-- LIVE EDIT-->
        <div class="form-group mb-3">
            <label class="control-label"><?php _e("Use custom receivers settings"); ?></label>
            <small class="text-muted d-block mb-2">
                <?php _e('Use custom receivers settings for the current contact form.'); ?>
                <br />
                <?php _e('By default we will use contact form global settings.'); ?>
                <a href="<?php print admin_url('/view:modules/load_module:contact_form?tab=settings'); ?>" target="_blank"><?php _e('You can change the global settings here.'); ?></a>
            </small>
        </div>

        <div class="form-group mb-4">
            <?php  $enableCustomRecevbers = get_option('enable_custom_receivers', $mod_id); ?>
            <div class="custom-control custom-switch pl-0">
                <label class="d-inline-block mr-5" for="enable_custom_receivers">No</label>
                <input type="checkbox" onchange="toggleCustomReceivers(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field custom-control-input" name="enable_custom_receivers" option-group="<?php print $mod_id ?>" id="enable_custom_receivers" value="y" <?php if ($enableCustomRecevbers !== 'n'): ?>checked<?php endif; ?>>
                <label class="custom-control-label" for="enable_custom_receivers">Yes</label>
            </div>
        </div>
        <!-- LIVE EDIT-->

        <script type="text/javascript">
            toggleCustomReceivers = function (e) {
                var el = e.target;
                if ($(el).is(":checked")) {
                    $('.js-custom-receivers').fadeIn();
                } else {
                    $('.js-custom-receivers').fadeOut();
                }

            };
        </script>

        <div class="js-custom-receivers" <?php if ($enableCustomRecevbers !== 'y'): ?> style="display:none"<?php endif; ?>>
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
    </div>

    <hr class="thin"/>

    <h5 class="font-weight-bold mb-3"><?php _e("Auto-respond message to user"); ?></h5>

    <div class="">

        <div class="form-group mb-3">
            <label class="control-label"><?php _e("Enable auto-respond message to user"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e('Allow users to receive Thank you emails after subscription.'); ?></small>
        </div>

        <div class="form-group mb-4">
            <?php  $enableAutoRespond = get_option('enable_auto_respond', $mod_id); ?>
            <div class="custom-control custom-switch pl-0">
                <label class="d-inline-block mr-5" for="enable_auto_respond">No</label>
                <input type="checkbox" onchange="toggleAutoRespondFields(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field custom-control-input" name="enable_auto_respond" option-group="<?php print $mod_id ?>" id="enable_auto_respond" value="y" <?php if ($enableAutoRespond !== 'n'): ?>checked<?php endif; ?>>
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

        <div class="js-auto-respond-fields" <?php if ($enableAutoRespond !== 'y'): ?> style="display:none"<?php endif; ?>>
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
