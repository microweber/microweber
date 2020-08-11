<?php must_have_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>media_cleanup.js");
    mw.require("files.js");
</script>

<style>
    #mw_upsdfsdloader.disabled iframe {
        top: -9999px;
    }
</style>

<div class="mw_edit_page_default" id="mw_edit_page_left">
    <p><?php _e('This module will remove media from database, which is not present on the hard drive'); ?>.</p>

    <div class="mw-admin-side-nav">
        <div class="back-up-nav-btns">
            <a href="javascript:mw.media_cleanup.run()" class="btn btn-success btn-sm"><?php _e('Cleanup Images'); ?></a>

            <div class="vSpace"></div>
        </div>
        <div id="mw_media_cleanup_log" type="admin/developer_tools/media_cleanup/log"></div>
    </div>
</div>