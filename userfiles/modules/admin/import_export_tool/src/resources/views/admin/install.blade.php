<div class="card">
    <div class="card-body">
        <div class="row">
        <div class="card-header">
            <?php $module_info = module_info('admin/import_export_tool'); ?>
            <h5>
                <img width="30" height="30" src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
                <strong><?php _e($module_info['name']); ?></strong>
            </h5>
        </div>
            <livewire:import_export_tool::install />
        </div>
    </div>
</div>
