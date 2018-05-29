<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>

    <script>

        initEditor = function(){
            if(!window.editorLaunced){
                editorLaunced = true;
                mw.editor({
                    element:mwd.getElementById('editorAM'),
                    hideControls:['format', 'fontsize', 'justifyfull']
                });
            }
        }



        $(document).ready(function(){
            initEditor()

        });
    </script>

<?php

$mod_id = $params['id'];
if(isset($params['for_module_id'])){
    $mod_id = $params['for_module_id'];
}




?>


<hr>
<div id="form_email_options">
    <label class="mw-ui-label" style="padding-bottom: 0;"><small><?php _e("Type your e-mail where you will receive the email from this form"); ?></small></label>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Email To"); ?></label>
        <input   name="email_to"    option-group="<?php print $mod_id ?>"   value="<?php print get_option('email_to', $mod_id); ?>"     class="mw-ui-field w100 mw_option_field" type="text" />
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("BCC Email To"); ?></label>
        <input name="email_bcc"    option-group="<?php print $mod_id ?>"  value="<?php print get_option('email_bcc', $mod_id); ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Subject"); ?></label>
        <input name="email_autorespond_subject"   option-group="<?php print $mod_id ?>"    value="<?php print get_option('email_autorespond_subject', $mod_id); ?>"     class="mw-ui-field w100 mw_option_field"  type="text" />
    </div>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Autorespond Message"); ?></label>
        <textarea id="editorAM" name="email_autorespond" class="mw_option_field"   option-group="<?php print $mod_id ?>">
    			<?php print get_option('email_autorespond', $mod_id); ?>
    		</textarea>

        <label class="mw-ui-label"><span class="ico ismall_warn"></span><small><?php _e("Autorespond e-mail sent back to the user"); ?></small></label>
    </div>
</div>

<hr>
<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input
                type="checkbox"
                parent-reload="true"
                name="require_terms"

                value="y"
                class="mw_option_field"
                option-group="<?php print $mod_id ?>"
            <?php if(get_option('require_terms', $mod_id)=='y'): ?>   checked="checked"  <?php endif; ?>
        />
        <span></span><span><?php _e("Users must agree to Terms and Conditions"); ?></span> </label>
</div>



<?php if($mod_id != 'contact_form_default'){ ?>

<hr>
<label class="mw-ui-check">
    <input
        type="checkbox"
        name="disable_captcha"
        value="y"
        option-group="<?php print $mod_id ?>"
        class="mw_option_field"
        <?php if(get_option('disable_captcha', $mod_id) =='y'): ?>   checked="checked"  <?php endif; ?>
    />
    <span></span> <span><?php _e("Disable Code Verification ex"); ?>.:</span> </label>
<img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt="" />

<?php } ?>


