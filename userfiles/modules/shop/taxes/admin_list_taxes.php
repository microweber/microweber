<?php
must_have_access();

$defined_taxes = mw()->tax_manager->get();
//d($defined_taxes);
?>

<?php if (!empty($defined_taxes)) : ?>
    <h6 class="font-weight-bold"><?php _e("Taxes list"); ?></h6>

    <div class="table-responsive">
        <table cellspacing="0" cellpadding="0" class="table">
            <thead>
            <tr>
                <th><?php _e('Tax name'); ?></th>
                <th><?php _e('Tax rate'); ?></th>
                <th><?php _e('Tax type'); ?></th>
                <th class="text-center" style="width: 200px;"><?php _e('Actions'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($defined_taxes as $item) : ?>
                <tr class="small">
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

                    <td class="text-center" style="padding-left: 0; padding-right: 0;">
                        <button onclick="mw_admin_edit_tax_item_popup('<?php print $item['id']; ?>')" class="btn btn-outline-primary btn-sm" title="Edit"><?php _e('Edit'); ?></button>
                        &nbsp;
                        <button onclick="mw_admin_delete_tax_item_confirm('<?php print $item['id']; ?>')" class="btn btn-outline-danger btn-sm" title="Delete"><?php _e('Delete'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center py-3">
        <h4 class="m-0"><?php _e('You don\'t have any defined taxes'); ?></h4>
    </div>
<?php endif; ?>

<?php if (!empty($defined_taxes)) : ?>
    <!--<span class="text-muted"><?php _e('Example tax for 1000.00 is'); ?><?php print ' ' . mw()->tax_manager->calculate(1000) ?></span>-->
<?php endif; ?>
