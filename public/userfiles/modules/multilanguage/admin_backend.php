<?php only_admin_access(); ?>

<?php if (MW_VERSION < '1.2.0'): ?>
    <script>
        mw.lib.require('bootstrap4');


    </script>
<?php endif; ?>
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
        <?php $module_info = module_info($params['module']); ?>

    </div>

    <div class=" row">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#list">  <?php print _e('Languages'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#settings">  <?php print _e('Settings'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">

                <module type="multilanguage/language_settings"/>

            </div>

            <div class="tab-pane fade" id="settings">
                <module type="multilanguage/settings"/>
            </div>
        </div>

        <module type="help/modal_with_button" for_module="multilanguage"/>
    </div>
</div>
