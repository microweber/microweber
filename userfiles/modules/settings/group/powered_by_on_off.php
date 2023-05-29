<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
            mw.reload_module('#<?php print $params['id'];?>');
        });
    });
</script>
<?php

$powered_by_microweber = get_option('powered_by_microweber', 'website');
$powered_by_microweber_text = get_option('powered_by_microweber_text', 'website');

?>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Powered by Microweber"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Turn on Powered by Microweber of your site"); ?></small>

    <label class="form-check form-switch">
        <input name="powered_by_microweber" class="form-check-input mw_option_field " data-option-group="website" data-value-checked="y" data-value-unchecked="n" type="checkbox" <?php if (get_option('powered_by_microweber', 'website') !== "n"): ?> checked="checked" checked="checked" <?php endif; ?>>
    </label>
</div>


