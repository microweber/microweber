<?php only_admin_access(); ?>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('.<?php print $config['module_class'] ?>', function(){
            mw.notification.success("<?php _e("All changes are saved"); ?>.");
        });
    });
</script>

<?php

if(isset($params['show_description_text']) and $params['show_description_text'] ){ ?>


    <h3><?php _e("Looks like you are trying to upload big images"); ?></h3>
    <h4><?php _e("For best experience you may want to enable \"Automatic image resize\""); ?></h4>


<?php

}

?>

<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <?php _e("Enable automatic image resize on upload"); ?>
    </label>
    <?php
    $automatic_image_resize_on_upload = get_option('automatic_image_resize_on_upload','website');

    ?>
    <select name="automatic_image_resize_on_upload" class="mw-ui-field mw_option_field"   type="text" option-group="website">
        <option value="y" <?php if($automatic_image_resize_on_upload == 'y'): ?> selected="selected" <?php endif; ?>>
            <?php _e("Yes"); ?>
        </option>
        <option value="n" <?php if(!$automatic_image_resize_on_upload or $automatic_image_resize_on_upload == 'n'): ?> selected="selected" <?php endif; ?>>
            <?php _e("No"); ?>
        </option>
        <option value="d" <?php if($automatic_image_resize_on_upload == 'd'): ?> selected="selected" <?php endif; ?>>
            <?php _e("Disable"); ?>
        </option>
    </select>
</div>
