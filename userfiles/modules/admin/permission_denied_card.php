<div class="card card-danger mb-3">
    <div class="card-body p-4">
        <div class="d-flex justify-content-center align-items-center">
            <div class="icon-title">
                <i class="mdi mdi-account-lock text-dark"></i>
                <h5 class="mb-0">Permission denied!</h5>
            </div>
        </div>
        <p class="text-center">
            You don't have permissions to see <b><?php echo $module_info['name']; ?></b> module. <br /><br />
            <button onclick="window.history.back()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-chevron-double-left"></i> Back to modules</button>
        </p>
    </div>
</div>