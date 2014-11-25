<?php
    only_admin_access();


    $ord = 'order_by=id desc';


	if(isset($params['order'])){
	$data['order_by'] =$params['order'];
	$ord = 'order_by='.$params['order'];
	}

	$ordert_type = 'completed';
	$kw = '';

    if(isset($params['keyword'])){
      $kw  = '&search_in_fields=email,first_name,last_name,country,created_on,transaction_id,city,state,zip,address,phone,user_ip,payment_gw&keyword='.$params['keyword'];
    }

	if(isset($params['order-type']) and $params['order-type'] == 'carts'){
	 		$ordert_type = 'carts';
			$ord = 'order_by=updated_on desc';
	 		$orders = get_cart('limit=1000&group_by=session_id&no_session_id=true&order_completed=n&'.$ord);
			//$orders = get_cart('debug=1&limit=1000&group_by=id&no_session_id=true&order_completed=n&'.$ord);
			
	} else {
		$orders = get_orders('order_completed=y&'.$ord.$kw);
		// 
 	}

?>


 

<div id="mw-order-table-holder">
  <?php if($ordert_type == 'completed' and isset($orders) and is_array($orders)) :?>
  <table class="mw-ui-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0" width="100%">
    <colgroup>
        <col>
        <col>
        <col>
        <col width="100">
        <col>
        <col>
        <col>

        <col width="120">
    </colgroup>
    <thead>
      <tr>
        <th><?php _e("ID"); ?></th>
        <th><?php _e("Date"); ?></th>
        <th><?php _e("Status"); ?></th>
        <th><?php _e("Amount"); ?></th>
        <th><?php _e("Paid"); ?></th>
        <th><?php _e("Names"); ?></th>
        <th><?php _e("Email"); ?></th>

        <th><?php _e("View & Delete"); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td><?php _e("ID"); ?></td>
        <td><?php _e("Date"); ?></td>
        <td><?php _e("Status"); ?></td>
        <td><?php _e("Amount"); ?></td>
        <td><?php _e("Paid"); ?></td>
        <td><?php _e("Names"); ?></td>
        <td><?php _e("Email"); ?></td>

        <td><?php _e("View & Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php foreach ($orders as $item) : ?>
      <tr class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-status-<?php print $item['order_status'] ?> tip" data-showon="#vieorder-<?php print $item['id']; ?>"  data-tipposition="top-center" data-tipcircle="true" data-tip="#product-tip-<?php print $item['id'] ?>">
          <?php  $cart_item = get_cart('no_session_id=true&order_completed=any&order_id=' . $item['id'] . '');  ?>


          <?php if(isset($cart_item[0]) and isset($cart_item[0]['rel_id'])) { ?>
          <?php $p = get_picture($cart_item[0]['rel_id']); ?>
          <?php if ($p != false): ?>
          <div id="product-tip-<?php print $item['id'] ?>" style="display: none">
            <span class="product-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($p, 120, 120); ?>)"></span>
          </div>
          <?php endif; ?>
          <?php } ?>

          <td class="mw-order-item-id tip" data-tipposition="top-center" data-tipcircle="true" data-tip="#product-tip-<?php print $item['id'] ?>">
            <a class="mw-ord-id" href="#vieworder=<?php print ($item['id']) ?>"><?php print $item['id'] ?></a>
          </td>
          <td title="<?php print mw('format')->ago($item['created_on'],1); ?>"><?php print mw('format')->date($item['created_on']);; ?></td>
        <td class="mw-order-item-status">
        <?php  if($item['order_status'] == false): ?>
          <?php _e("New"); ?>
          <?php elseif($item['order_status'] == 'completed'): ?>
         <span class="mw-order-item-status-completed"><?php _e("Completed"); ?></span>
          <?php else : ?>
          <span class="mw-order-item-status-pending"><?php _e("Pending"); ?> </span>
          <?php endif; ?></td>
        <td class="mw-order-item-amount"><?php  print currency_format(floatval($item['amount']) + floatval($item['shipping']),$item['currency']) ?></td>
        <td class="mw-order-item-paid"><?php if($item['is_paid'] == 'y'): ?>
          <?php _e("Yes"); ?>
          <?php else : ?>
          <?php _e("No"); ?>
          <?php endif; ?></td>
        <td class="mw-order-item-names"><?php print $item['first_name'] . ' ' . $item['last_name']; ?></td>
        <td class="mw-order-item-email"><?php print $item['email'] ?></td>
        <td class="mw-order-item-edit" width="90" align="center">
            <span class="mw-icon-close show-on-hover tip" data-tip="<?php _e("Delete"); ?>" data-tipposition="top-center"  onclick="mw_delete_shop_order('<?php print ($item['id']) ?>');"></span>
            <a class="show-on-hover mw-ui-btn-invert mw-ui-btn mw-ui-btn-small" id="vieorder-<?php print $item['id']; ?>" href="#vieworder=<?php print ($item['id']) ?>">
          <?php _e("View order"); ?>
          </a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php elseif($ordert_type == 'carts' and isset($orders) and is_array($orders)) :?>
    <label class="mw-ui-label"><?php _e("Abandoned Carts Section helps you analyze why some customers aren't checking out."); ?></label>
    <div class="mw-ui-box-content">
      <div id="orders_stat" style="height: 250px;"></div>
    </div>


    <?php foreach ($orders as $item) : ?>



  <h2><?php _e("Abandoned Cart ID"); ?>: <?php print $item['id']; ?></h2>

  <table class="mw-ui-table mw-order-table abandoned-cart" id="abandoned-cart-table<?php print $item['id'] ?>" cellpadding="0" cellspacing="0">

  <script>

        $(document).ready(function () {
            $("#abandoned-cart-table<?php print $item['id'] ?> .mw-order-item-image").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, "#abandoned-cart-table<?php print $item['id'] ?> .mw-order-item-index-" + index);
            });
            $("#abandoned-cart-table<?php print $item['id'] ?> tr.mw-order-item").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, "#abandoned-cart-table<?php print $item['id'] ?> .mw-order-item-image-" + index);
            });
            });

    </script>

    <thead>
      <tr>
        <th><?php _e("Cart"); ?></th>
        <th><?php _e("User statistics"); ?></th>
      </tr>
    </thead>

    <tbody>

      <tr class="mw-order-item-<?php print $item['id'] ?> no-hover" >
        <td><?php $cart_items = get_cart('order_completed=n&session_id='.$item['session_id']); ?>
          <?php if(is_array($cart_items) and !empty($cart_items)) :?>
          <?php
    			$recart_base =  site_url();
    			if(is_array($cart_items[0]) and isset($cart_items[0]['rel_id'])) {
    				$recart_base =  content_link($cart_items[0]['rel_id']);
    			}
    	  ?>
          <div class="mw-order-images">
            <?php for($i=0; $i<sizeof($cart_items); $i++){ ?>
            <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
            <?php if($p != false): ?>

            <span data-index="<?php print $i; ?>" class="bgimg mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width:70px;height:70px;background-image: url(<?php print thumbnail($p, 120,120); ?>);"></span>
            <?php endif; ?>
            <?php } ?>
          </div>
          <table class="mw-ui-table mw-ui-table-basic" cellspacing="0" cellpadding="0" width="100%">
            <thead>
              <tr>
                <th><?php _e("Product Name"); ?></th>
                <th><?php _e("Custom fields"); ?></th>
                <th><?php _e("Price"); ?></th>
                <th><?php _e("QTY"); ?></th>
                <th><?php _e("Total"); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $subtotal = 0; ?>
              <?php $index = -1; foreach ($cart_items as $item) : ?>
              <?php
                  $index++;
                  $item_total = floatval($item['qty']) * floatval($item['price']);
                  $subtotal = $subtotal + $item_total;
                  $grandtotal = $subtotal;
              ?>
              <tr
                data-index = "<?php print $index; ?>"
                class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>" >
                <td class="mw-order-item-id"><a href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a></td>
                <td class="mw-order-item-fields"><?php 	if(isset($item['custom_fields'])): ?>
                  <?php print $item['custom_fields'] ?>
                  <?php  endif ?></td>
                <td class="mw-order-item-amount"><?php print ($item['price']) ?></td>
                <td class="mw-order-item-count"><?php print $item['qty'] ?></td>
                <td class="nowrap"><?php print currency_format($item_total); ?></td>
              </tr>
              <?php endforeach; ?>
              <tr class="mw-ui-table-footer">
                <td colspan="3" style="padding-top: 37px;">&nbsp;</td>
                <td class="mw-ui-table-green">
                  <strong><?php _e("Total"); ?>:</strong>
                </td>
                <td class="nowrap"><b><?php print  currency_format($grandtotal); ?></b></td>
              </tr>
            </tbody>
          </table>
          <?php else: ?>
          <h2>
            <?php _e("The cart is empty"); ?>
          </h2>
          <?php endif;?></td>
        <td style="padding: 20px;">
            <label class="mw-ui-label pull-right">
                <span class="mw-icon-lite-clock-outline" style="font-size: 16px;top:-1px;right:2px;"></span>
                <span class="mw-ui-label-small tip" data-tipposition="top-center" data-tip="<?php _e("Last activity on"); ?>: <?php print $item['updated_on'] ?>"><?php print mw('format')->ago($item['updated_on']); ?></span>
            </label>
            <style scoped="scoped">
                .mw-ui-table thead tr th:last-child{
                  text-align:right
                }

            </style>
            <?php event_trigger('mw_admin_quick_stats_by_session',$item['session_id']); ?>
           <hr>
          <div class="mw-ui-field-holder" style="padding-bottom: 20px;">
            <label class="mw-ui-label mw-ui-label-inline" style="width: 120px;"><?php _e("Recover URL"); ?> <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("Use this if you need to send it to your clients. They'll be able to restore their Shopping Cart."); ?>"></span></label>

            <div style="font-size: 11px;color:#bbb;" onclick="mw.wysiwyg.select_all(this);"><?php print $recart_base.'?recart='.$item['session_id']; ?></div>

          </div>
            <div class="mw-ui-btn-nav pull-right">
              <a class="mw-ui-btn" href="javascript:mw_delete_shop_order('<?php print ($item['session_id']) ?>',1);"><?php _e("Delete cart"); ?></a>
              <a class="mw-ui-btn mw-ui-btn-invert" href="<?php print $recart_base.'?recart='.$item['session_id'] ?>" target="_blank"><?php _e("Recover"); ?></a>
            </div>
          </td>
      </tr>

    </tbody>
  </table><?php endforeach; ?>
    <?php
		$abandoned_carts = get_cart('count=1&no_session_id=true&order_completed=n&group_by=session_id');
        $completed_carts = get_orders('count=1&order_completed=y');
     ?>
  <script>mw.lib.require("morris");</script> 
  <script>
      $(document).ready(function(){
         mw.on.moduleReload("<?php print $params['id']; ?>", function(){
             OrdersChart = Morris.Donut({
                element: 'orders_stat',
                data: [
                  {label: "Completed Carts", value: <?php print intval($completed_carts); ?>},
                  {label: "Abandoned Carts", value: <?php print intval($abandoned_carts); ?>}
                ]
              });
         });
      });
    </script>
  <?php else: ?>
  <div class="mw-ui-box mw-ui-box-content info-box" style="margin-top: 15px;">
    <h2>
        <?php _e("You don't have any orders"); ?>
    </h2>
  </div>
  <?php endif;?>
</div>
