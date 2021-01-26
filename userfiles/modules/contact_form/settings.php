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
        <div class="col">
            <h5 class="font-weight-bold mb-3"><?php _e("Contact Form") ?></h5>
        </div>

        <div class="col text-right">
            <a class="btn btn-primary btn-sm" href="<?php print admin_url('view:') . $params['module'] ?>" target="_blank"><?php _e("Check your Inbox"); ?></a>
        </div>
    </div>

    <div class="">
        <div class="form-group">
            <label class="control-label"><?php _e("E-mail address from"); ?></label>
            <small class="text-muted d-block mb-2">The e-mail address which will send the message</small>
            <input name="email_from" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("E-mail sender name"); ?></label>
            <small class="text-muted d-block mb-2">e.x. your name, company or brand name</small>
            <input name="email_from_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from_name', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Receiver e-mail address"); ?></label>
            <small class="text-muted d-block mb-2">E-mail address of the receiver</small>
            <input name="email_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_to', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("E-mail Reply To"); ?></label>
            <small class="text-muted d-block mb-2">Send a copy of the email to one more email address</small>
            <input name="email_reply" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_reply', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("BCC E-mail To"); ?></label>
            <small class="text-muted d-block mb-2">Those addresses are invisible to the recipients of the email</small>
            <input name="email_bcc" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_bcc', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>
    </div>

    <hr class="thin"/>

    <h5 class="font-weight-bold mb-3">Auto-respond Message</h5>

    <div class="">
        <div class="form-group">
            <label class="control-label"><?php _e("Autorespond Subject"); ?></label>
            <small class="text-muted d-block mb-2">Auto-responders allows you to set up automated replies to incoming email <br/>E.x. “Thank you for your request”</small>
            <input name="email_autorespond_subject" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_subject', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
        </div>

        <div class="form-group">
            <label class="control-label"><?php _e("Autorespond Message"); ?></label>
            <small class="text-muted d-block mb-2">Autorespond e-mail sent back to the user</small>
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
