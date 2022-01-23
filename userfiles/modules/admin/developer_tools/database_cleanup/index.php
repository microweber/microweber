<?php must_have_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>database_cleanup.js");
    mw.require("files.js");
</script>
<style>
    #mw_upsdfsdloader.disabled iframe {
        top: -9999px;
    }

    .back-up-nav-btns .mw-ui-btn {
        width: 170px;
        text-align: left;
    }
</style>

<div id="mw-admin-content">
    <div class="mw_edit_page_default" id="mw_edit_page_left">
        <p><?php _e("This module will remove categories, images and custom fields which are connected to content that is manually deleted from the database"); ?>.</p>

        <div class="mw-admin-side-nav">
            <div class="back-up-nav-btns">
                <a href="javascript:mw.database_cleanup.run()" class="btn btn-success btn-sm"><?php _e("Cleanup Database"); ?></a>

                <div class="vSpace"></div>
            </div>

            <div id="mw_database_cleanup_log" type="admin/developer_tools/database_cleanup/log"></div>
        </div>
    </div>
</div>
