<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="form-group">
    <label class="control-label"><?php _e("Custom head tags"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Advanced functionality"); ?>. <?php _e("You can put custom html in the site head-tags. Please put only valid meta tags or you can break your site."); ?></small>
    <textarea name="website_head" class="mw_option_field form-control" type="text" option-group="website" style="min-height: 150px;"><?php print get_option('website_head', 'website'); ?></textarea>
</div>
