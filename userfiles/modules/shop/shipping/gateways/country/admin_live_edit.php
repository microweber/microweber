<?php only_admin_access(); ?>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-popup-settings">
                <module type="<?php print($config['module_name']); ?>/admin_backend" id="mw_shipping_cfg"  />
            </div>
        </div>
    </div>
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php _e("Skin/Template"); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-popup-settings">
                <module type="admin/modules/templates" id="shipping_list_templ"  for-module="<?php print($params['data-type']); ?>"  />
            </div>
        </div>
    </div>
</div>

