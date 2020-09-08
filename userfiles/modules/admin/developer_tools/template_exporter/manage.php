<?php if (!is_admin()) {

    return;

}

$keyword = false;
if (isset($params['keyword'])) {
    $keyword = $params['keyword'];
}
?>


<style>
    .restore-loading-indicator {
        display: none;
    }

    .restore-loading-indicator.restoring-backup {
        display: block !important;
        width: 20px;
        height: 20px;
        -webkit-animation: spin 4s linear infinite;
        -moz-animation: spin 4s linear infinite;
        animation: spin 4s linear infinite;
    }

    @-moz-keyframes spin {
        100% {
            -moz-transform: rotate(360deg);
        }
    }

    @-webkit-keyframes spin {
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>

<div id="backups_list">
    <h6><?php _e("Available Backups"); ?></h6>
    <table cellspacing="0" cellpadding="0" class="table table-bordered" width="80%">
        <thead class="bg-secondary">
        <tr>
            <th><?php _e("Filename"); ?></th>
            <th><?php _e("Date"); ?></th>
            <th><?php _e("Size"); ?></th>
            <th><?php _e("Download"); ?></th>
            <th><?php _e("Delete"); ?></th>
        </tr>
        </thead>
        <tfoot class="bg-secondary">
        <tr>
            <td><?php _e("Filename"); ?></td>
            <td><?php _e("Date"); ?></td>

            <td><?php _e("Size"); ?></td>
            <td><?php _e("Download"); ?></td>

            <td><?php _e("Delete"); ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php $backups = mw('admin\developer_tools\template_exporter\Worker')->get($keyword);
        if (isarr($backups)): ?>
            <?php
            $i = 1;
            foreach ($backups as $item): ?>
                <tr class="mw_admin_backup_item_<?php print $i ?>">
                    <td><?php print $item['filename'] ?> <span id="restore-<?php print md5($item['filename']) ?>" class="restore-loading-indicator mw-icon-load-c" title="Working"> </span></td>
                    <td><?php print $item['date'] ?><?php print $item['time'] ?></td>

                    <td><?php print file_size_nice($item['size']) ?></td>
                    <td class="text-center"><a target="_blank" title="<?php _e("Download"); ?>" class="text-success" href="<?php print api_url('admin/developer_tools/template_exporter/Worker/download'); ?>?file=<?php print $item['filename'] ?>"><i class="mdi mdi-content-save-move mdi-20px"></i></a></td>

                    <td class="text-center"><a href="javascript:;" title="<?php _e("Delete"); ?>" class="text-danger" onclick="mw.template_exporter.remove('<?php print $item['filename'] ?>', '.mw_admin_backup_item_<?php print $i ?>');"><i class="mdi mdi-trash-can-outline mdi-20px"></i></a></td>
                </tr>
                <?php $i++; endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
