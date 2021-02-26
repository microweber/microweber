<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
            mw.reload_module('#<?php print $params['id'];?>');
        });
    });
</script>
<?php

$maintenance_mode = get_option('maintenance_mode', 'website');
$maintenance_mode_text = get_option('maintenance_mode_text', 'website');

?>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Maintenance mode"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Turn on Under construction mode of your site"); ?></small>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input name="maintenance_mode" class="mw_option_field custom-control-input" id="maintenance_mode_1"
               data-option-group="website" value="n"
               type="radio" <?php if (get_option('maintenance_mode', 'website') !== "y"): ?> checked="checked" <?php endif; ?> >
        <label class="custom-control-label" for="maintenance_mode_1"><?php _e("Disable"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input name="maintenance_mode" class="mw_option_field custom-control-input" id="maintenance_mode_0"
               data-option-group="website" value="y"
               type="radio" <?php if (get_option('maintenance_mode', 'website') === "y"): ?> checked="checked" <?php endif; ?> >
        <label class="custom-control-label" for="maintenance_mode_0"><?php _e("Enable"); ?></label>
    </div>



</div>
<?php if($maintenance_mode == 'y'){ ?>
<div class="form-group mb-t">
    <label class="control-label"><?php _e("Maintenance mode text"); ?></label>
    <textarea autocomplete="off" name="maintenance_mode_text" class="mw_option_field form-control" rows="3" type="text" option-group="website"><?php print $maintenance_mode_text ?></textarea>
</div>
<?php } ?>

