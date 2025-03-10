<script>
    function updaterDashboardNoticeDissmiss()
    {
        $('.js-updater-dashboard-notice').slideUp();
        $.ajax({
            url: "<?php echo route('api.updater.remove-dashboard-notice'); ?>",
            method: "GET",
            success: function (response) {
            },
            error: function () {
            }
        });
    }
</script>

<div class="js-updater-dashboard-notice card border-primary mb-3">
    <div class="card-body">
        <h4 class="card-title">New version is available!</h4>
        <p class="card-text">Current version (<?php echo MW_VERSION; ?>)</p>
        <p class="card-text">There is a new version (<?php echo $params['new-version']; ?>) available, do you want to update now?</p>

        <br />

        <a href="#" onclick="updaterDashboardNoticeDissmiss()" class="btn btn-outline-primary">Later</a>
        <a href="<?php echo route('filament.admin.resources.updater.index'); ?>" class="btn btn-outline-primary">Update now</a>
    </div>
</div>
