<?php must_have_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<div class="form-group">
    <label class="control-label">Robots.txt <?php _e("content"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("The robots. txt file, also known as the robots exclusion protocol or standard, is a text file that tells web robots (most often search engines) which pages on your site to crawl. It also tells web robots which pages not to crawl."); ?></small>
    <textarea name="robots_txt" class="mw_option_field form-control" type="text" option-group="website" style="min-height: 150px;"><?php print get_option('robots_txt', 'website'); ?></textarea>
</div>
