<div class="row">
    <div class="col-md-12">
        <div>
            <?php $module_info = module_info('admin/import_export_tool'); ?>
            <h5>
                <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
                <strong><?php _e($module_info['name']); ?></strong>
            </h5>
        </div>

        <div class="card style-1 mb-3 mt-3">
            <div class="card-body pt-3">

                <livewire:import_export_tool::install />

            </div>
        </div>
    </div>
</div>
