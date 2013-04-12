<?

if(is_admin() == false){
return;
}


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
<? if(isarr($orders)) :?>

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
      <? foreach ($orders as $item) : ?>
      <tr class="mw-order-item mw-order-item-<? print $item['id'] ?> mw-order-status-<? print $item['order_status'] ?>">
        <td class="mw-order-item-id"><a href="#vieworder=<? print ($item['id']) ?>"><span><? print $item['items_count'] . ' ' . _e("items", true); ?></span>&nbsp;<span class="mw-items-rate mw-items-rate-<?php print $item['items_count']; ?>"></span> <br />
          <span class="mw-ord-id">ORD-<? print $item['id'] ?></span></a>
          <div class="mw-date" title="<? print ago($item['created_on'],1); ?>"><? print mw_date($item['created_on']);; ?></div>
        </td>
        <td class="mw-order-item-status"><?
		 if($item['order_status'] == false): ?>
          New
          <? elseif($item['order_status'] == 'completed'): ?>
          Completed
          <? else : ?>
          Pending
          <? endif; ?></td>
        <td class="mw-order-item-amount"><? print currency_format($item['amount'],$item['currency']) ?></td>
        <td class="mw-order-item-paid"><? if($item['is_paid'] == 'y'): ?>
          Yes
          <? else : ?>
          No
          <? endif; ?></td>
        <td class="mw-order-item-names"><? print $item['first_name'] . ' ' . $item['last_name']; ?></td>
        <td class="mw-order-item-email"><? print $item['email'] ?></td>
        <td class="mw-order-item-phone"><? print $item['phone'] ?></td>
        <td class="mw-order-item-country"><? print $item['country'] ?></td>
        <td class="mw-order-item-edit" width="80" align="center">
            <span class="mw-ui-admin-table-show-on-hover del-row" style="margin: -12px -7px auto auto;" onclick="mw_delete_shop_order('<? print ($item['id']) ?>');"></span>

            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-small" href="#vieworder=<? print ($item['id']) ?>">
            <?php _e("View order"); ?>
            </a>
          </td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>
<? else: ?>
<h2>You don't have any orders</h2>
<? endif;?>
