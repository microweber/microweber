<?php
only_admin_access();

$defined_taxes = mw()->tax_manager->get();
//d($defined_taxes);
?>

<?php if (!empty($defined_taxes)) : ?>
    <table cellspacing="0" cellpadding="0" class="table-style-3 mw-ui-table">
        <thead>
        <tr>
            <th><?php _e('Tax name'); ?></th>
            <th><?php _e('Amount'); ?></th>
            <th width="100" class="text-center"><?php _e('Edit'); ?></th>
            <th width="100" class="text-center"><?php _e('Delete'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($defined_taxes as $item) : ?>
            <tr>
                <td><?php print $item['tax_name']; ?></td>
                <td>
                    <?php if ($item['tax_modifier'] == 'percent'): ?>
                        <?php print $item['amount']; ?>%
                    <?php endif; ?>

                    <?php if ($item['tax_modifier'] == 'fixed'): ?>
                        <?php print mw()->shop_manager->currency_format($item['amount']); ?>
                    <?php endif; ?>
                </td>
                <td class="action-buttons">
                    <button onclick="mw_admin_edit_tax_item_popup('<?php print $item['id']; ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" title="Edit">Edit</button>
                </td>
                <td class="action-buttons">
                    <button onclick="mw_admin_delete_tax_item_confirm('<?php print $item['id']; ?>')" class="act act-remove" title="Delete">X</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <table cellspacing="0" cellpadding="0" class="table-style-3 mw-ui-table">
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
<?php endif; ?>


<br/><br/>
<?php if (!empty($defined_taxes)) : ?>
    <span class="mw-ui-label-help"><?php _e('Example tax for 1000.00 is'); ?><?php print mw()->tax_manager->calculate(1000) ?></span>
<?php endif; ?>
