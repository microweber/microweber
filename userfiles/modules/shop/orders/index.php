<?

$ord = '';
 

    if(isset($params['order'])){
               $ord   = '&order_by='.$params['order'];
			  
              }
 

$kw = '';
 

    if(isset($params['keyword'])){
                $kw   = '&keyword='.$params['keyword'];
              }
$orders = get_orders('order_completed=y&is_paid=y'.$ord.$kw);
 
?>
<? if(isarr($orders)) :?>


<div id="mw-order-table-holder">
  <table class="mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th><?php _e("Order ID"); ?></th>
        <th><?php _e("Amount"); ?></th>
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
        <td><?php _e("Amount"); ?></td>
        <td><?php _e("Names"); ?></td>
        <td><?php _e("Email"); ?></td>
        <td><?php _e("Client Phone"); ?></td>
        <td><?php _e("Country"); ?></td>
        <td><?php _e("View & Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <? foreach ($orders as $item) : ?>
      <tr class="mw-order-item mw-order-item-<? print $item['id'] ?>">
        <td class="mw-order-item-id"><a href="#vieworder=<? print ($item['id']) ?>"><span><? print $item['items_count'] . ' ' . _e("items", true); ?></span>&nbsp;<span class="mw-items-rate mw-items-rate-<?php print $item['items_count']; ?>"></span> <br />
        <span class="mw-ord-id">ORD-<? print $item['id'] ?></span></a></td>
        <td class="mw-order-item-amount"><? print $item['amount'] ?></td>
        <td class="mw-order-item-names"><? print $item['first_name'] . ' ' . $item['last_name']; ?></td>
        <td class="mw-order-item-email"><? print $item['email'] ?></td>
        <td class="mw-order-item-phone"><? print $item['phone'] ?></td>
        <td class="mw-order-item-country"><? print $item['country'] ?></td>
        <td class="mw-order-item-edit"><span class="del-row"></span>
          <div class="mw_clear"></div>
          <center>
            <a class="mw-ui-btn" href="#vieworder=<? print ($item['id']) ?>">
            <?php _e("View order"); ?>
            </a>
          </center></td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>
<? else: ?>
<h2>You don't have any orders</h2>
<? endif;?>
