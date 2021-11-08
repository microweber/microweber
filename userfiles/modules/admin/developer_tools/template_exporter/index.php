<?php must_have_access(); ?>
<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>template_exporter.js");
</script>

<style>
    #mw_upsdfsdloader.disabled iframe {
        top: -9999px;
    }
</style>

<div id="mw-admin-content">
    <div class="mw_edit_page_default" id="mw_edit_page_left">
        <div class="mw-admin-side-nav">
            <div class="text-end text-right">
                <a href="javascript:mw.template_exporter.create()" class="btn btn-success btn-sm mb-1"><?php _e('Export template'); ?></a>
            </div>
            <div id="mw_backup_log" type="admin/developer_tools/template_exporter/log"></div>
        </div>
    </div>

    <div class="mw_edit_page_right">
        <module type="admin/developer_tools/template_exporter/manage"/>
    </div>
</div>
