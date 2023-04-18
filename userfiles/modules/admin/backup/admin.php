<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card">
    <div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
        <div class="row">
            <div class="card-header">
                <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
            </div>


            <?php include($config['mp'] . 'index.php'); ?>
        </div>

    </div>
</div>


