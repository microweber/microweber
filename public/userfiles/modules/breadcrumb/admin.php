<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class=" ">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-breadcrumb-settings">
                    <?php $selected_start_depth = get_option('data-start-from', $params['id']); ?>

                    <div class="form-group">
                        <label class="form-label"><?php _e("Root level"); ?></label>
                        <div>
                            <select name="data-start-from" class="mw_option_field form-select" data-width="100%">
                                <option value='' <?php if ('' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>
                                <option value='page' <?php if ('page' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Page"); ?></option>
                                <?php ?>
                                <option value='category' <?php if ('category' == $selected_start_depth): ?>   selected="selected"  <?php endif; ?>><?php _e("Category"); ?></option>
                                <?php ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
