<?
only_admin_access();


$ord = '';


if(isset($params['order'])){
$data['order_by'] =$params['order'];
$ord = 'order_by='.$params['order'];
}


$kw = '';


    if(isset($params['keyword'])){
                $kw   = '&search_in_fields=email,first_name,last_name,country,created_on,transaction_id,city,state,zip,address,phone,user_ip,payment_gw&keyword='.$params['keyword'];
     }
$orders = get_orders('order_by=id desc&order_completed=y&'.$ord.$kw);

?>
<?php if(isarr($orders)) :?>

<div id="mw-order-table-holder">
  <table class="mw-ui-admin-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0" width="960">
    <thead>
      <tr>
        <th><?php _e("Order ID"); ?></th>
         <th><?php _e("Status"); ?></th>
        <th><?php _e("Amount"); ?></th>
        <th><?php _e("Paid"); ?></th>
        <th><?php _e("Names"); ?></th>
        <th><?php _e("Email"); ?></th>
        <th><?php _e("Client Phone"); ?></th>
        <th><?php _e("Country"); ?></th>
        <th><?php _e("View & Delete"); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td><?php _e("Order ID"); ?></td>
         <th><?php _e("Status"); ?></th>
        <td><?php _e("Amount"); ?></td>
        <th><?php _e("Paid"); ?></th>
        <td><?php _e("Names"); ?></td>
        <td><?php _e("Email"); ?></td>
        <td><?php _e("Client Phone"); ?></td>
        <td><?php _e("Country"); ?></td>
        <td><?php _e("View & Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php foreach ($orders as $item) : ?>
      <tr class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-status-<?php print $item['order_status'] ?>">
        <td class="mw-order-item-id"><a href="#vieworder=<?php print ($item['id']) ?>"><span><?php print $item['items_count'] . ' ' . _e("items", true); ?></span>&nbsp;<span class="mw-items-rate mw-items-rate-<?php print $item['items_count']; ?>"></span> <br />
          <span class="mw-ord-id">ORD-<?php print $item['id'] ?></span></a>
          <div class="mw-date" title="<?php print ago($item['created_on'],1); ?>"><?php print mw_date($item['created_on']);; ?></div>
        </td>
        <td class="mw-order-item-status"><?
		 if($item['order_status'] == false): ?>
          New
          <?php elseif($item['order_status'] == 'completed'): ?>
          Completed
          <?php else : ?>
          Pending
          <?php endif; ?></td>
        <td class="mw-order-item-amount"><?
		 
		
		
		 print currency_format(floatval($item['amount'])+floatval($item['shipping']),$item['currency']) ?></td>
        <td class="mw-order-item-paid"><?php if($item['is_paid'] == 'y'): ?>
          Yes
          <?php else : ?>
          No
          <?php endif; ?></td>
        <td class="mw-order-item-names"><?php print $item['first_name'] . ' ' . $item['last_name']; ?></td>
        <td class="mw-order-item-email"><?php print $item['email'] ?></td>
        <td class="mw-order-item-phone"><?php print $item['phone'] ?></td>
        <td class="mw-order-item-country"><?php print $item['country'] ?></td>
        <td class="mw-order-item-edit" width="80" align="center">
            <span class="mw-ui-admin-table-show-on-hover del-row" style="margin: -12px -7px auto auto;" onclick="mw_delete_shop_order('<?php print ($item['id']) ?>');"></span>

            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-small" href="#vieworder=<?php print ($item['id']) ?>">
            <?php _e("View order"); ?>
            </a>
          </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<h2>You don't have any orders</h2>
<?php endif;?>
