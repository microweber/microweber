<?php only_admin_access(); ?>

<?php $got_lic = mw()->update->get_licenses('count=1') ?>





<div class="mw-ui-field-holder">
    <a class="mw-ui-btn" onclick="mw.load_module('admin/developer_tools/template_exporter','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Template exporter'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" onclick="mw.load_module('admin/developer_tools/media_cleanup','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Media cleanup'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" onclick="mw.load_module('admin/developer_tools/database_cleanup','#mw-advanced-settings-dev-tools-output')">
        <?php _e('Database cleanup'); ?>
    </a><br /><br />

    <a class="mw-ui-btn" onclick="mw.load_module('admin/notifications/system_log','#mw-advanced-settings-dev-tools-output')">
        <?php _e("Show system log"); ?>
    </a><br /><br />
    <?php if (($got_lic) >= 0): ?>
    <a class="mw-ui-btn" onclick="mw.load_module('settings/group/licenses','#mw-advanced-settings-dev-tools-output')">
        <?php _e("Licenses"); ?>
    </a>
    <?php endif; ?>

    <?php if (config('app.debug')): ?>



         <br /><br />
    <a class="mw-ui-btn" onclick="mw.load_module('settings/group/experimental','#mw-advanced-settings-dev-tools-output')">
        <?php _e("Experimental settings"); ?>
    </a>     <?php   /* <br /><br />
   <a class="mw-ui-btn" onclick="mw.load_module('admin/modules/packages','#mw-advanced-settings-dev-tools-output')">
        <?php  _e("Packages");  ?>
    </a>  */

        ?>

    <?php endif; ?>


    <div class="mw-clear" style="padding-bottom:10px;"></div>
    <div id="mw-advanced-settings-dev-tools-output"></div>
</div>
<div id="mw-advanced-settings-module-load-holder"></div>



