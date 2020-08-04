<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

        <div class="mw-modules-tabs">
            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <!-- Settings Content -->
                    <div class="module-live-edit-settings module-breadcrumb-settings">
                        <?php $selected_start_depth = get_option('data-start-from', $params['id']); ?>

                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label"><?php _e("Root level"); ?></label>
                            <select name="data-start-from" class="mw-ui-field mw_option_field mw-full-width">
                                <option value='' <?php if ('' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                                <option value='page' <?php if ('page' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Page"); ?></option>
                                <?php ?>
                                <option value='category' <?php if ('category' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Category"); ?></option>
                                <?php ?>
                            </select>
                        </div>
                    </div>
                    <!-- Settings Content - End -->
                </div>
            </div>

            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <module type="admin/modules/templates"/>
                </div>
            </div>
        </div>

    </div>
</div>