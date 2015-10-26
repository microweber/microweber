<?php 
only_admin_access();

$defined_taxes = mw()->tax_manager->get();
//d($defined_taxes);
?>

<table cellspacing="0" cellpadding="0" class="mw-ui-table">
  <thead>
    <tr>
      <th>Tax name</th>
      <th>Modifier</th>
      <th>Amount</th>
      <th width="100"></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($defined_taxes)) : ?>
    <?php foreach($defined_taxes as $item) : ?>
    <tr>
      <td><?php print $item['tax_name']; ?></td>
      <td><?php print $item['tax_modifier']; ?></td>
      <td><?php print $item['amount']; ?></td>
      <td><button onclick="mw_admin_edit_tax_item_popup('<?php print $item['id']; ?>')" class="mw-icon-edit show-on-hover" title="Edit"></button>
        <button onclick="mw_admin_delete_tax_item_confirm('<?php print $item['id']; ?>')" class="mw-icon-bin show-on-hover" title="Delete"></button></td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td colspan="5">You don't have any defined taxes</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>


<br /><br />
<?php if(!empty($defined_taxes)) : ?>
<span class="mw-ui-label-help">Example tax for 1000.00 is <?php print mw()->tax_manager->calculate(1000) ?></span>
<?php endif; ?>
