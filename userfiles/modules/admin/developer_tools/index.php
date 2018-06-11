<?php only_admin_access(); ?>


<div class="mw-ui-field-holder">
    <a class="mw-ui-btn" href="javascript:mw.load_module('admin/developer_tools/template_exporter','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Template exporter'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" href="javascript:mw.load_module('admin/developer_tools/media_cleanup','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Media cleanup'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" href="javascript:mw.load_module('admin/developer_tools/database_cleanup','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Database cleanup'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" href="javascript:mw.load_module('admin/notifications/system_log','#mw-advanced-settings-dev-tools-output')">
        <?php _e("Show system log"); ?>
    </a>
    <!--  <a class="mw-ui-btn" href="javascript:mw.load_module('admin/modules/packages','#mw-advanced-settings-dev-tools-output')">
        <?php /*_e("Packages"); */ ?>
    </a>-->
    <div class="mw-clear" style="padding-bottom:10px;"></div>
    <div id="mw-advanced-settings-dev-tools-output"></div>
</div>
<div id="mw-advanced-settings-module-load-holder"></div>



