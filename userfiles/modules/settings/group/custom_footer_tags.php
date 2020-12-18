<?php only_admin_access(); ?>
<script type="text/javascript">
    function saveFooterTags() {
    	$("input, select, textarea", $('.<?php print $config['module_class'] ?>')).each(function () {
	        mw.options.save(this, function () {
	            mw.notification.success("<?php _ejs("Saved"); ?>.");
	        });
    	});
    }
</script>


<div class="mw-ui-field-holder">
    <label class="mw-ui-label">
        <h3>
            <?php _e("Custom footer tags"); ?>
        </h3>
        <br>
        <div class="mw-ui-box mw-ui-box-content mw-ui-box-warn">
            <?php _e("Advanced functionality"); ?>
            <?php _e("You can put custom html in the site footer-tags. Please put only valid meta tags or you can break your site."); ?>
        </div>
    </label>
    <textarea name="website_footer" class="mw_option_field mw-ui-field w100" type="text" option-group="website" style="min-height: 200px;"><?php print get_option('website_footer', 'website'); ?></textarea>

	<button onClick="saveFooterTags()" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Save</button>

</div>