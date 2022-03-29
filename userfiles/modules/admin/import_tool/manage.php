<?php if (!is_admin()) {
    error("must be admin");
}; ?>

<div class="clearfix"></div>

<h5 class="font-weight-bold mb-3 mt-2"><?php _e('Available contents'); ?></h5>

<div id="backups_list">
    <?php
    $backups = app(\MicroweberPackages\Import\Http\Controllers\Admin\ImportController::class)->get();
    ?>
    <?php if (isarr($backups)): ?>
        <?php $i = 1; ?>
        <table class="table">
            <thead>
            <tr>
                <th style="width:300px;"><?php _e("Filename"); ?> </th>
                <th><?php _e("Date"); ?></th>
                <th><?php _e("Time"); ?></th>
                <th><?php _e("Size"); ?></th>
                <?php if (user_can_access('module.admin.migration_tool.create') || user_can_access('module.admin.migration_tool.edit') || user_can_access('module.admin.migration_tool.destroy')): ?>
                    <th class="text-center"><?php _e("Actions"); ?></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($backups as $item): ?>
                <tr class="mw_admin_import_item_<?php print $i ?> show-on-hover-root small valign-middle">
                    <td><?php print $item['filename'] ?></td>
                    <td><span class="mw-date"><?php print $item['date'] ?></span></td>
                    <td><span class="mw-date"><?php print $item['time'] ?></span></td>
                    <td><span class="mw-date"><?php print file_size_nice($item['size']) ?></span></td>
                    <?php if (user_can_access('module.admin.migration_tool.create') || user_can_access('module.admin.migration_tool.edit') || user_can_access('module.admin.migration_tool.destroy')): ?>
                        <td class="text-center">
                            <?php if (user_can_access('module.admin.migration_tool.create') || user_can_access('module.admin.migration_tool.edit')): ?>
                                <a class="btn btn-primary btn-sm show-on-hover" target="_blank" href="<?php echo route('admin.migration_tool.download'); ?>?file=<?php print $item['filename'] ?>"><?php _e("Download"); ?></a>
                                <a class="btn btn-success btn-sm show-on-hover" href="javascript:mw.backup_import.import('<?php print $item['filename'] ?>')"><?php _e("Import"); ?></a>
                            <?php endif; ?>
                            <?php if (user_can_access('module.admin.migration_tool.destroy')): ?>
                                <a class="btn btn-outline-danger btn-sm show-on-hover" href="javascript:mw.backup_import.remove('<?php print $item['filename'] ?>', '.mw_admin_import_item_<?php print $i ?>')"><?php _e("Delete"); ?></a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>

            </tbody>
        </table>
    <?php else: ?>

        <div class="icon-title">
            <i class="mdi mdi-harddisk"></i> <h5><?php _e('You don\'t have any import/exports.'); ?></h5>
        </div>
    <?php endif; ?>
</div>
