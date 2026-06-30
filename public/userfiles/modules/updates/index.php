<?php only_admin_access(); ?>
<?php
if (app()->ui->disable_marketplace) {
    return;
}
?>

<div>
    <h1 class="main-pages-title">System Updates</h1>


        <module type="standalone-updater/admin" />

</div>
