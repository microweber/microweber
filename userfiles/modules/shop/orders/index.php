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
	} else {
		$orders = get_orders('order_completed=y&'.$ord.$kw);
	}

?>




<div id="mw-order-table-holder">
	<?php if($ordert_type == 'completed' and isset($orders) and is_array($orders)) :?>
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
					<div class="mw-date" title="<?php print mw('format')->ago($item['created_on'],1); ?>"><?php print mw('format')->date($item['created_on']);; ?></div></td>
				<td class="mw-order-item-status"><?php
		 if($item['order_status'] == false): ?>
					New
					<?php elseif($item['order_status'] == 'completed'): ?>
					Completed
					<?php else : ?>
					Pending
					<?php endif; ?></td>
				<td class="mw-order-item-amount"><?php
		 
		

		 print mw('shop')->currency_format(floatval($item['amount']) + floatval($item['shipping']),$item['currency']) ?></td>
				<td class="mw-order-item-paid"><?php if($item['is_paid'] == 'y'): ?>
					<?php _e("Yes"); ?>
					<?php else : ?>
					<?php _e("No"); ?>
					<?php endif; ?></td>
				<td class="mw-order-item-names"><?php print $item['first_name'] . ' ' . $item['last_name']; ?></td>
				<td class="mw-order-item-email"><?php print $item['email'] ?></td>
				<td class="mw-order-item-phone"><?php print $item['phone'] ?></td>
				<td class="mw-order-item-country"><?php print $item['country'] ?></td>
				<td class="mw-order-item-edit" width="80" align="center"><span class="mw-ui-admin-table-show-on-hover del-row" style="margin: -12px -7px auto auto;" onclick="mw_delete_shop_order('<?php print ($item['id']) ?>');"></span> <a class="mw-ui-admin-table-show-on-hover mw-ui-btn mw-ui-btn-small" href="#vieworder=<?php print ($item['id']) ?>">
					<?php _e("View order"); ?>
					</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php elseif($ordert_type == 'carts' and isset($orders) and is_array($orders)) :?>



    <label class="mw-ui-label">

        Abandoned Carts Section helps you analyze why some customers aren't checking out.

    </label>

      <div class="mw-o-box">
        <div class="mw-o-box-content">
           <div id="orders_stat" style="height: 250px;"></div>
        </div>
      </div>
      <div class="vSpace"></div>
      <table class="mw-ui-admin-table mw-order-table" id="shop-orders" cellpadding="0" cellspacing="0" width="960">
    		<thead>
    			<tr>
    				<td><?php _e("Cart"); ?></td>
    				<td><?php _e("User stats"); ?></td>
    			</tr>
    		</thead>
    		<tfoot>
    			<tr>
    				<td><?php _e("Cart"); ?></td>
    				<td><?php _e("User stats"); ?></td>
    			</tr>
    		</tfoot>
    		<tbody>
    			<?php foreach ($orders as $item) : ?>
    			<tr class="mw-order-item-<?php print $item['id'] ?> no-hover" >
    				<td  >
    					<?php $cart_items = get_cart('order_completed=n&session_id='.$item['session_id']); ?>
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
    						<img
                                class="mw-order-item-image mw-order-item-image-<?php print $i; ?>"
                                data-index="<?php print $i; ?>"
                                src="<?php print thumbnail($p, 70,70); ?>"
                            />
    						<?php endif; ?>
    						<?php } ?>
    					</div>
    					<table class="mw-o-box-table" cellspacing="0" cellpadding="0" width="100%">
    						<thead>
    							<tr>
    								<th><?php _e("Product Name"); ?></th>
    								<th><?php _e("Custom fields"); ?></th>
    								<th><?php _e("Price"); ?></th>
    								<th><?php _e("QTY"); ?></th>
    								<?php /* <th><?php _e("Promo Code"); ?></th> */ ?>
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
    								<td class="mw-order-item-count" width="100"><?php print  mw('shop')->currency_format($item_total); ?></td>
    							</tr>
    							<?php endforeach; ?>
    							<tr class="mw-o-box-table-footer last">
    								<td colspan="3">&nbsp;</td>
    								<td class="mw-o-box-table-green"><b>
    									<?php _e("Total:"); ?>
    									</b></td>
    								<td class="mw-o-box-table-green"><b><?php print  mw('shop')->currency_format($grandtotal); ?></b></td>
    							</tr>
    						</tbody>
    					</table>
    					<?php else: ?>
    					<h2>
    						<?php _e("The cart is empty"); ?>
    					</h2>
    					<?php endif;?>
                        </td>
    				<td>
                     <label class="mw-ui-label">
                         Last activity:
        				 <span class="mw-ui-label-small" style="font-weight: 100" data-help="<?php print $item['updated_on'] ?>"><?php print mw('format')->ago($item['updated_on']); ?></span>
                     </label>
                        <div class="mw-o-box" style="margin-bottom: 20px;border-bottom: none"><?php event_trigger('mw_admin_quick_stats_by_session',$item['session_id']); ?></div>
                        <div class="mw-ui-field-holder">
                        <label class="mw-ui-label mw-ui-label-inline" style="width: 120px;">Recover URL <span class="mw-help" data-help="Use this if you need to send it to your clients. They'll be able to restore their Shopping Cart">(?)</span></label>
                        <input type="text" class="mw-ui-field right" style="width: 330px;font-size: 11px;" readonly="readonly" onfocus="$(this).select()" value="<?php print $recart_base.'?recart='.$item['session_id'] ?>">
                        </div>
                        <div class="vSpace"></div>
                            <a class="mw-ui-btn mw-ui-btn-green right" style="margin-left: 12px;" href="<?php print $recart_base.'?recart='.$item['session_id'] ?>" target="_blank">Recover</a>
        					<a class="mw-ui-btn right" href="javascript:mw_delete_shop_order('<?php print ($item['session_id']) ?>',1);">Delete cart</a>


                    </td>
    			</tr>
    			<?php endforeach; ?>
    		</tbody>
      </table>


     <?php
        $abandoned_carts = get_cart('count=1&group_by=session_id&no_session_id=true&order_completed=n');
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
	<h2>
		<?php _e("You don't have any orders"); ?>
	</h2>
	<?php endif;?>
</div>
