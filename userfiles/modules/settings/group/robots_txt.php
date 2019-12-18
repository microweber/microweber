<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });

    });


</script>


<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <h3>Robots.txt
            <?php _e("content"); ?>
        </h3>
        <br>
        <div class="mw-ui-box mw-ui-box-content mw-ui-box-warn">
            <?php _e("Advanced functionality"); ?>
         </div>
    </label>
    <textarea name="robots_txt" class="mw_option_field mw-ui-field w100" type="text" option-group="website" style="min-height: 200px;"><?php print get_option('robots_txt', 'website'); ?></textarea>
</div>



