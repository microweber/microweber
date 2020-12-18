<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
        $("input[name='require_terms']").on('click', function () {
            if ($("input[name='require_terms']").is(':checked')) {
                $("#agree_when").show();
            } else {
                $("#agree_when").hide();
            }
        });
    });
</script>

<?php
$mod_id = 'contact_form_default';
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>


<module type="users/terms/set_for_module" for_module="<?php print $mod_id ?>" />




<?php

/*<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" parent-reload="true" name="require_terms" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field" option-group="<?php print $mod_id ?>"
            <?php if (get_option('require_terms', $mod_id) == 'y'): ?> checked="checked" <?php endif; ?> />
        <span></span><span><?php _e("Users must agree to the Terms and Conditions"); ?></span></label>
</div>

<div class="mw-ui-field-holder" id="agree_when"<?php if (get_option('require_terms', $mod_id) != 'y'): ?> style="display:none;"<?php endif; ?>>
<ul class="mw-ui-inline-list">
    <li>
        <label class="mw-ui-check">
            <input type="radio" parent-reload="true" name="require_terms_when" value="b" data-value-checked="b" class="mw_option_field" option-group="<?php print $mod_id ?>"
                <?php if (get_option('require_terms_when', $mod_id) == 'b'): ?>   checked="checked"  <?php endif; ?> />
            <span></span>
            <span><?php _e("Agree before form submission"); ?></span>
        </label>
    </li>
    <li>
        <label class="mw-ui-check">
            <input type="radio" parent-reload="true" name="require_terms_when" value="a" data-value-checked="a" class="mw_option_field" option-group="<?php print $mod_id ?>"
                <?php if (get_option('require_terms_when', $mod_id) == 'a'): ?>   checked="checked"  <?php endif; ?> />
            <span></span>
            <span><?php _e("Agree after form submission"); ?></span>
        </label>
    </li>
</ul>
</div>*/

?>

<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" parent-reload="true" name="skip_saving_emails" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field" option-group="<?php print $mod_id ?>"
            <?php if (get_option('skip_saving_emails', $mod_id) == 'y'): ?> checked="checked"  <?php endif; ?> />
        <span></span>
        <span><?php _e("Skip saving emails in database."); ?></span>
    </label>
</div>
