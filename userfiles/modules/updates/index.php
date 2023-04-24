<?php only_admin_access(); ?>
<?php
if (mw()->ui->disable_marketplace) {
    return;
}
?>

<div>
    <h1>System Updates</h1>

    <div class="mt-3">
        <module type="standalone-updater/admin" />
    </div>
</div>
