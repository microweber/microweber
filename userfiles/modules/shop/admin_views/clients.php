





<div class="mw-admin-wrap">







<?php

  $clients = array();
  $orders = get_orders('order_by=created_on desc&group=email&is_paid=y');

?>




  <table class="mw-ui-admin-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th><?php _e("Name & Number"); ?></th>
        <th><?php _e("Email"); ?></th>
        <th><?php _e("Client's Phone"); ?></th>
        <th><?php _e("Country"); ?></th>
        <th><?php _e("City"); ?></th>
        <th><?php _e("Orders #"); ?></th>
        <th><?php _e("View & Delete"); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td><?php _e("Name & Number"); ?></td>
        <td><?php _e("Email"); ?></td>
        <td><?php _e("Client's Phone"); ?></td>
        <td><?php _e("Country"); ?></td>
        <td><?php _e("City"); ?></td>
        <td><?php _e("Orders #"); ?></td>
        <td><?php _e("View & Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <? foreach ($orders as $order) : ?>
       <tr>
        <td><?php print $order['first_name'] . " " . $order['last_name']; ?></td>
        <td><?php print $order['email']; ?></td>
        <td><?php print $order['phone']; ?></td>
        <td><?php print $order['country']; ?></td>
        <td><?php print $order['city']; ?></td>
        <td>
          <?php $total_ord = get_orders('count=1&email='.$order['email'].'&is_paid=y'); ?>
          <?php print $total_ord; ?>
        </td>
        <td>
            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn" href="#?clientorder=<?php print $order['id']; ?>"><?php _e("View client"); ?></a>
        </td>
      </tr>
      <? endforeach; ?>
    </tbody>
  </table>











</div>


<div id="mw-clientorder"></div>
