<?php
must_have_access();

$defined_taxes = mw()->tax_manager->get();
//d($defined_taxes);
?>

<?php if (!empty($defined_taxes)) : ?>
    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" class="table-style-3 mw-ui-table layout-auto">
            <thead>
            <tr>
                <th><?php _e('Tax name'); ?></th>
                <th><?php _e('Tax rate'); ?></th>
                <th><?php _e('Tax type'); ?></th>
                <th class="center" style="width: 200px;"><?php _e('Actions'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($defined_taxes as $item) : ?>
                <tr>
                    <td><?php print $item['name']; ?></td>
                    <td>
                        <?php if ($item['type'] == 'percent'): ?>
                            <?php print $item['rate']; ?>%
                        <?php endif; ?>

                        <?php if ($item['type'] == 'fixed'): ?>
                            <?php print mw()->shop_manager->currency_format($item['rate']); ?>
                        <?php endif; ?>
                    </td>

                    <td><?php print $item['type']; ?></td>

                    <td class="center" style="padding-left: 0; padding-right: 0;">
                        <button onclick="mw_admin_edit_tax_item_popup('<?php print $item['id']; ?>')" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" title="Edit"><?php print _e('Edit'); ?></button>
                        &nbsp;
                        <button onclick="mw_admin_delete_tax_item_confirm('<?php print $item['id']; ?>')" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium" title="Delete"><?php print _e('Delete'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" class="table-style-3 mw-ui-table layout-auto">
            <thead>
            <tr>
                <th class="text-center">
                    <br/>
                    <h3><?php _e('You don\'t have any defined taxes'); ?></h3>
                    <br/>
                    <a class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline" href="javascript:mw_admin_edit_tax_item_popup(0)"><span> <?php _e('Add new tax'); ?> </span></a>
                    <br/> <br/> <br/>
                </th>
            </tr>
            </thead>
        </table>
    </div>
<?php endif; ?>


<br/><br/>
<?php if (!empty($defined_taxes)) : ?>
    <span class="mw-ui-label-help"><?php _e('Example tax for 1000.00 is'); ?><?php print mw()->tax_manager->calculate(1000) ?></span>
<?php endif; ?>
