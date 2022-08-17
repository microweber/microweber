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
<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <?php if (isset($module_info['icon'])):?>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
            <strong><?php echo _e($module_info['name']); ?></strong>
            <?php endif; ?>
        </h5>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php print _e('Languages'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
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
