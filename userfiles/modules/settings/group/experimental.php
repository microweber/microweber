<?php only_admin_access(); ?>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('.<?php print $config['module_class'] ?>', function(){
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>

<h2>
    <?php _e("Experimental"); ?>
    <?php _e("settings"); ?>
</h2>
<div class="mw-ui-box mw-ui-box-content mw-ui-box-important">Those settings are eperimental and may lead to bugs. Please don't use them</div>
<div class="<?php print $config['module_class'] ?>">
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
            Open module settings in sidebar on live edit
        </label>
        <?php
        $open_module_settings_in_sidebar = get_option('open_module_settings_in_sidebar','live_edit');
         ?>
        <select name="open_module_settings_in_sidebar" class="mw-ui-field mw_option_field"   type="text" option-group="live_edit">
            <option value="0" <?php if(!$open_module_settings_in_sidebar): ?> selected="selected" <?php endif; ?>>
                <?php _e("No"); ?>

            </option>
            <option value="1" <?php if($open_module_settings_in_sidebar): ?> selected="selected" <?php endif; ?>>
                <?php _e("Yes"); ?>
            </option>
        </select>
    </div>


</div>
