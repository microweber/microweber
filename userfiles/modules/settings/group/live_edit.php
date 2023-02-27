<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
        });
    });
</script>

<div class="<?php print $config['module_class'] ?>">
    <div class="form-group">
        <label class="control-label"><?php _e("Enable keyboard shortcuts"); ?></label>
        <?php $disable_keyboard_shortcuts = get_option('disable_keyboard_shortcuts', 'website'); ?>

        <select name="disable_keyboard_shortcuts" class="mw_option_field selectpicker" data-width="100%" option-group="website">
            <option value="" <?php if (!$disable_keyboard_shortcuts): ?> selected="selected" <?php endif; ?>><?php _e("Yes"); ?></option>
            <option value="1" <?php if ($disable_keyboard_shortcuts): ?> selected="selected" <?php endif; ?>><?php _e("No"); ?></option>
        </select>
    </div>

    <module type="settings/group/image_upload"/>
    <?php $enabled_custom_fonts = \MicroweberPackages\Utils\Misc\GoogleFonts::getEnabledFonts(); ?>
</div>
