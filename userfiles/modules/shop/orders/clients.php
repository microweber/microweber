<script  type="text/javascript">
function mw_delete_shop_client($email){
	 var r=confirm("Are you sure you want to delete this client?")
if (r==true)
  {
 
  var r1=confirm("ATTENTION!!!!!! All ORDERS OF THIS CLIENT WILL BE DELETED!")
if (r1==true)
  {
 
	 $.post("<? print api_url('delete_client') ?>", { email: $email } ,function(data) {
		mw.reload_module('shop/orders/clients');
		
		
	});

  }
 
 
 
   }
 
}

</script>



<?php

  $clients = array();
  $orders = get_orders('order_by=created_on desc&group=email&is_completed=y&email=[not_null]');
   $is_orders = get_orders('count=1');

?>
<div class="mw-table-sorting-controller">
  <h2 class="mw-side-main-title" style="padding-top: 0"><span class="ico iusers-big"></span><span><?php _e("Clients List"); ?></span></h2>
</div>

<?php if($is_orders != 0){   ?>
  <table class="mw-ui-admin-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0" width="960">
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
      <? if(!empty($orders)): foreach ($orders as $order) : ?>
       <tr>
        <td><?php print $order['first_name'] . " " . $order['last_name']; ?></td>
        <td><?php print $order['email']; ?></td>
        <td><?php print $order['phone']; ?></td>
        <td><?php print $order['country']; ?></td>
        <td><?php print $order['city']; ?></td>
        <td>
          <?php $total_ord = get_orders('count=1&email='.$order['email'].'&is_completed=y'); ?>
          <?php print $total_ord; ?>
        </td>
        <td width="85">


            <span class="mw-ui-admin-table-show-on-hover del-row" style="margin: -8px -7px auto auto;" onclick="mw_delete_shop_client('<? print ($order['email']) ?>');"></span>
            <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-small" href="#?clientorder=<?php print $order['id']; ?>"><?php _e("View client"); ?></a>
        </td>
      </tr>
      <? endforeach; endif; ?>
    </tbody>
  </table>

  <?php  }  else { ?>

      <h2><?php _e("You don't have any clients"); ?></h2>

  <?php  }  ?>




