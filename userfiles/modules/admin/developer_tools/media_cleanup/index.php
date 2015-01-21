<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>media_cleanup.js");
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
        <div class="mw-admin-sidebar">
            <h2><span class="ico imanage-module"></span>&nbsp;
                Media cleanup </h2>
        </div>
        <p>This module will remove media from database, which is not present on the hard drive </p>

        <div class="mw-admin-side-nav">
            <div>
                <div class="back-up-nav-btns">
                    <div class="vSpace"></div>
                    <a href="javascript:mw.media_cleanup.run()"
                       class="mw-ui-btn mw-ui-btn-green"><span class="mw-icon-plus"></span><span> Cleanup Images </span></a>

                    <div class="vSpace"></div>
                </div>
                <div id="mw_media_cleanup_log" type="admin/developer_tools/media_cleanup/log"></div>
            </div>
        </div>
    </div>

</div>
