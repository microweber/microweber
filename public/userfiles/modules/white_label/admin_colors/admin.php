

<?php
$haveLicense = false;
if (function_exists('have_license')) {
    $haveLicense = have_license('modules/white_label');
}
if (defined('HAS_ACTIVE_SUBSCRIPTION') && HAS_ACTIVE_SUBSCRIPTION == true) {
    $haveLicense = true;
}

if (!$haveLicense): ?>
    <div class="module-live-edit-settings">
        <module type="admin/modules/activate" prefix="modules/white_label"/>
    </div>
    <?php return; ?>
<?php endif; ?>




<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}

include __DIR__."/index.php";
return;
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill" /> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class=" ">
        <div class="module-live-edit-settings module-admin-colors-settings">

        </div>
    </div>
</div>
