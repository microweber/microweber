<?php only_admin_access(); ?>
<?php
$templates_params = array();
$templates_params['no_limit'] = true;
$templates_params['order_by'] = "created_at desc";
$templates = newsletter_get_templates($templates_params);
?>
<?php if ($templates): ?>
    <div class="table-responsive">
        <table width="100%" border="0" class="mw-ui-table" style="table-layout:fixed">
            <thead>
            <tr>
                <th><?php _e('Title'); ?></th>
                <th><?php _e('Date'); ?></th>
                <th width="140px">&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($templates as $template): ?>
                <tr>
                    <td>
                        <?php print $template['title']; ?>
                    </td>
                    <td><?php print $template['created_at']; ?></td>
                    <td>
                        <button class="mw-ui-btn" onclick="edit_template('<?php print $template['id']; ?>')"><?php _e('Edit'); ?></button>
                        <a class="mw-ui-btn mw-ui-btn-icon" href="javascript:;" onclick="delete_template('<?php print $template['id']; ?>')"> <span class="mw-icon-bin"></span> </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <b>No templates found.</b>
<?php endif; ?>