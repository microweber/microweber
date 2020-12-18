<?php
only_admin_access();
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
    initEditor = function () {
        if (!window.editorLaunced) {
            editorLaunced = true;
            var fr = mw.editor({
                element: mwd.getElementById('editorAM'),
                hideControls: ['format', 'fontsize', 'justifyfull']
            });
            mw.tools.iframeAutoHeight(fr)
        }
    }

    $(document).ready(function () {
        initEditor()
    });
</script>
<?php
$mod_id = $params['id'];
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<h1>Contact Form Settings</h1>
<br/>
<div id="form_email_options">
    <label class="mw-ui-label" style="padding-bottom: 0;">
        <small><?php _e("Type your e-mail where you will receive the email from this form"); ?></small>
    </label>

    <div class="mw-flex-row">

    <div class="mw-flex-col-xs-6">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email From"); ?></label>
        <input name="email_from" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

    <div class="mw-flex-col-xs-6">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email From Name"); ?></label>
        <input name="email_from_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_from_name', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

     <div class="mw-flex-col-xs-4">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email To"); ?></label>
        <input name="email_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_to', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

 	<div class="mw-flex-col-xs-4">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email Reply To"); ?></label>
        <input name="email_reply" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_reply', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

	 <div class="mw-flex-col-xs-4">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("BCC Email To"); ?></label>
        <input name="email_bcc" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_bcc', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

 	<div class="mw-flex-col-xs-12">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Subject"); ?></label>
        <input name="email_autorespond_subject" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_subject', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
    </div>

	</div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Message"); ?></label>
        <textarea id="editorAM" name="email_autorespond" class="mw_option_field" option-group="<?php print $mod_id ?>">
    			<?php print get_option('email_autorespond', $mod_id); ?>
    		</textarea>

        <label class="mw-ui-label"><span class="ico ismall_warn"></span>
            <small><?php _e("Autorespond e-mail sent back to the user"); ?></small>
        </label>
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Email Attachment"); ?></label>

        <span>Attach the file that user will recive after from entry.
   		 <br> <br/>
   		 <module type="admin/components/file_append" option_group="<?php print $mod_id ?>"/>
    </div>

</div>

<module type="admin/mail_providers/integration_select" option_group="contact_form"/>

<hr>

<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" parent-reload="true" name="newsletter_subscription" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field" option-group="<?php print $mod_id ?>"
            <?php if(get_option('newsletter_subscription', $mod_id)=='y'): ?> checked="checked" <?php endif; ?> />
        <span></span><span><?php _e("Show newsletter subscription checkbox"); ?></span></label>
</div>

<hr>

<module type="contact_form/privacy_settings"/>

<?php if ($mod_id != 'contact_form_default') { ?>

    <hr>
    <label class="mw-ui-check">
        <input
                type="checkbox"
                name="disable_captcha"
                value="y"
                option-group="<?php print $mod_id ?>"
                class="mw_option_field"
            <?php if (get_option('disable_captcha', $mod_id) == 'y'): ?>   checked="checked"  <?php endif; ?>
        />
        <span></span> <span><?php _e("Disable Code Verification ex"); ?>.:</span> </label>
    <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt=""/>


    <hr>
    <label class="mw-ui-label"><?php _e("Redirect to URL after submit"); ?></label>

    <div class="mw-ui-field-holder">
        <input name="email_redirect_after_submit" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_redirect_after_submit', $mod_id); ?>" class="mw-ui-field w100 mw_option_field" type="text"/>
    </div>
<?php } ?>


