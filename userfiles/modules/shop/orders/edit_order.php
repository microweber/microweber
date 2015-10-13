<?php

only_admin_access();

$ord = mw()->shop_manager->get_order_by_id($params['order-id']);

$cart_items = array();
if (is_array($ord)) {
    $cart_items = false;
    if (empty($cart_items)) {
        $cart_items = mw()->shop_manager->order_items($ord['id']);
    }
}
else {
    mw_error("Invalid order id");
}

 
?>

 
<?php 
			  
$show_ord_id = $ord['id'];	
if(isset($ord['order_id']) and $ord['order_id'] != false){
	$show_ord_id = $ord['order_id'];
} 

?>




<div id="mw-order-table-holder">
  <div class="section-header"> <a class="mw-ui-btn pull-right" href="#vieworder=0"><span class="mw-icon-back"></span>
    <?php _e("Back to Orders"); ?>
    </a>
    <h2>
      <?php _e("Order"); ?>
      #<?php print $show_ord_id ?> </h2>
  </div>
  <div class="mw-ui-row" id="orders-info-row">
    <div class="mw-ui-col">
      <div class="mw-ui-box mw-ui-box-order-info">
        <div class="mw-ui-box-header"> <span>
          <?php _e("Order Information"); ?>
          </span> </div>
        <div class="mw-ui-box-content">
          <?php if (is_array($cart_items)) : ?>
          <div class="mw-order-images">
            <?php for ($i = 0; $i < sizeof($cart_items); $i++) { ?>
            <?php  if(isset($cart_items[$i]['item_image']) and $cart_items[$i]['item_image'] != false): ?>
            <?php 
	  
	  $p = $cart_items[$i]['item_image']; ?>
            <?php if ($p != false): ?>
            <a data-index="<?php print $i; ?>" class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);" href="<?php print ($p); ?>" target="_blank"></a>
            <?php endif; ?>
            <?php else: ?>
            <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
            <?php if ($p != false): ?>
            <span data-index="<?php print $i; ?>" class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>" style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"></span>
            <?php endif; ?>
            <?php endif; ?>
            <?php } ?>
          </div>
          <table class="mw-ui-table mw-ui-table-basic" cellspacing="0" cellpadding="0" width="100%" id="order-information-table">
            <thead>
              <tr>
                <th><?php _e("Product Name"); ?></th>
                <!--  <th><?php _e("Custom fields"); ?></th>-->
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
                $grandtotal = $subtotal + $ord['shipping'];
                ?>
              <tr
                    data-index="<?php print $index; ?>"
                    class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>">
                <td   class="mw-order-item-id"><a href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a>
                  <?php if ($item['rel_type'] == 'content'): ?>
                  <?php $data_fields = mw()->content_manager->data($item['rel_id']); ?>
                  <?php if (isset($data_fields['sku']) and $data_fields['sku'] != ''): ?>
                  <small class="mw-ui-label-help">
                  <?php _e("SKU"); ?>
                  : <?php print $data_fields['sku']; ?></small>
                  <?php endif; ?>
                  <?php endif; ?></td>
                <!--  <td class="mw-order-item-fields"></td>-->
                <td class="mw-order-item-amount nowrap"><?php print  currency_format($item['price'], $ord['currency']); ?></td>
                <td class="mw-order-item-count"><?php print $item['qty'] ?></td>
                <td class="mw-order-item-count" width="100"><?php print  currency_format($item_total, $ord['currency']); ?></td>
              </tr>
              <?php    if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
              <tr>
                <td colspan="4"><?php print $item['custom_fields'] ?></td>
              </tr>
              <?php endif ?>
              <?php endforeach; ?>
              <tr class="mw-ui-table-footer">
                <td colspan="2">&nbsp;</td>
                <td><?php _e("Subtotal"); ?></td>
                <td class="mw-ui-table-green"><?php print  currency_format($subtotal, $ord['currency']); ?></td>
              </tr>
              <tr class="mw-ui-table-footer">
                <td colspan="2">&nbsp;</td>
                <td><?php _e("Shipping price"); ?></td>
                <td class="mw-ui-table-green"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></td>
              </tr>
              <tr class="mw-ui-table-footer last">
                <td colspan="2">&nbsp;</td>
                <td class="mw-ui-table-green"><strong>
                  <?php _e("Total:"); ?>
                  </strong></td>
                <td class="mw-ui-table-green"><strong><?php print  currency_format($grandtotal, $ord['currency']); ?></strong></td>
              </tr>
            </tbody>
          </table>
          <?php else: ?>
          <h2>
            <?php _e("The cart is empty"); ?>
          </h2>
          <?php endif;?>
          <div class="mw-ui-box" id="order-status">
            <div class="mw-ui-box-header" ><span>
              <?php _e("Order Status"); ?>
              </span></div>
            <div class="mw-ui-box-content">
              <div class="order-status-selector">
                <ul class="mw-ui-inline-list">
                  <li><span>
                    <?php _e("What is the status of this order"); ?>
                    ?</span></li>
                  <li>
                    <label class="mw-ui-check">
                      <input <?php if ($ord['order_status'] == 'pending'): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="pending"/>
                      <span></span><span>
                      <?php _e("Pending"); ?>
                      </span> </label>
                  </li>
                  <li>
                    <label class="mw-ui-check">
                      <input <?php if ($ord['order_status'] == 'completed' or $ord['order_status'] == ''): ?>checked="checked"<?php endif; ?> type="radio" name="order_status" value="completed"/>
                      <span></span><span>
                      <?php _e("Completed Order"); ?>
                      </span> </label>
                  </li>
                </ul>
              </div>
              <script type="text/javascript">


        $(document).ready(function () {
            $(".mw-order-item-image").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, ".mw-order-item-index-" + index);
            });
            $("tr.mw-order-item").bind("mouseenter mouseleave", function (e) {
                var index = $(this).dataset('index');
                mw.tools.multihover(e, this, ".mw-order-item-image-" + index);
            });

            var obj = {
                id: "<?php print $ord['id']; ?>"
            }

            mw.$(".mw-order-is-paid-change").change(function () {
                var val = this.value;
                obj.is_paid = val;
                $.post(mw.settings.site_url + "api/shop/update_order", obj, function () {
                    var upd_msg = "<?php _e("Order is marked as un-paid"); ?>"
                    if (obj.is_paid == 'y') {
                        var upd_msg = "<?php _e("Order is marked as paid"); ?>";
                    }
                    mw.notification.success(upd_msg);
                    mw.reload_module('shop/orders');
                });
            });


            mw.$("input[name='order_status']").commuter(function () {
                var val = this.value;
                obj.order_status = val;
				 
                $.post(mw.settings.site_url + "api/shop/update_order", obj, function () {
                    mw.tools.el_switch(mwd.querySelectorAll('#mw_order_status .mw-notification'), 'semi');
                    var states = {
                        'y': '<?php _e("Completed"); ?>',
                        'n': '<?php _e("Pending"); ?>',
                    }
                    mw.which(val, states, function () {
                        mw.$(".mw-order-item-<?php print $ord['id']; ?> .mw-order-item-status").html(this.toString());
                    });
					mw.reload_module('shop/orders');
                });
            });
        });
    </script>
              <div id="mw_order_status" style="overflow: hidden">
                <div style="margin-right: 10px;width: 238px;"
             class="mw-notification mw-warning right <?php if ($ord['order_status'] == 'completed'): ?>semi_hidden<?php endif; ?>">
                  <div style="height: 55px;">
                    <?php _e("Pending"); ?>
                  </div>
                </div>
                <div style="margin-right: 10px;width: 238px;"
             class="mw-notification mw-success right <?php if ($ord['order_status'] != 'completed'): ?>semi_hidden<?php endif; ?>">
                  <div style="height: 55px;"><span>
                    <?php _e("Successfully Completed"); ?>
                    </span></div>
                </div>
              </div>
            </div>
          </div>
          <?php event_trigger('mw.ui.admin.shop.order.edit.status.after', $ord); ?>
          <?php $edit_order_custom_items = mw()->ui->module('mw.ui.admin.shop.order.edit.status.after'); ?>
          <?php if (!empty($edit_order_custom_items)): ?>
          <?php foreach ($edit_order_custom_items as $item): ?>
          <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
          <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
          <?php $text = (isset($item['text']) ? $item['text'] : false); ?>
          <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
          <?php $html = (isset($item['html']) ? $item['html'] : false); ?>

          <?php if ($view==false and $link!=false){
                                    $btnurl = $link;
                                } else {
                                    $btnurl = admin_url('view:') . $view;
           } ?>
          <div class="mw-ui-box" style="margin-bottom: 20px;">
            <div class="mw-ui-box-header"><?php if ($icon){ ?><span class="<?php print $icon; ?>"></span><?php } ?><span><?php print $text; ?></span></div>
            <div class="mw-ui-box-content"><?php print $html; ?></div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
          <div class="mw-ui-box">
            <div class="mw-ui-box-header">
              <?php _e("Payment Information"); ?>
              <span class="mw-icon-help-outline mwahi tip" style="float: none" data-tip="<?php _e("Payment Information that we have from the payment provider"); ?>" data-tipposition="top-center"></span></div>
            <div class="mw-ui-box-content">
              <ul class="order-table-info-list">
                <li>
                  <?php _e("Payment Method"); ?>
                  <?php $gw = str_replace('shop/payments/gateways/','',$ord['payment_gw']); ?>
                  : <strong><?php print $gw; ?></strong></li>
                <li>
                  <?php _e("Is Paid"); ?>
                  :
                  <select name="is_paid" class="mw-ui-field mw-ui-field-medium mw-order-is-paid-change">
                    <option value="1" <?php if (isset($ord['is_paid']) and $ord['is_paid'] == 1): ?> selected="selected" <?php endif; ?>>
                    <?php _e("Yes"); ?>
                    </option>
                    <option value="0" <?php if (isset($ord['is_paid']) and $ord['is_paid'] != 1): ?> selected="selected" <?php endif; ?>>
                    <?php _e("No"); ?>
                    </option>
                  </select>
                </li>
                <?php if (isset($ord['transaction_id']) and $ord['transaction_id'] != ''): ?>
                <li>
                  <?php _e("Transaction ID"); ?>
                  : <?php print $ord['transaction_id']; ?></li>
                <?php endif; ?>
                <?php if (isset($ord['payment_amount']) and $ord['payment_amount'] != ''): ?>
                <li>
                  <?php _e("Payment Amount"); ?>
                  : <?php print $ord['payment_amount']; ?>
                  <?php if (isset($ord['payment_shipping']) and $ord['payment_shipping'] != ''): ?>
                  <span>+ <?php print $ord['payment_shipping']; ?>
                  <?php _e("for shipping"); ?>
                  </span>
                  <?php endif; ?>
                  <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("Amount paid by the user"); ?>"></span></li>
                <?php endif; ?>
                <?php if (isset($ord['payment_currency']) and $ord['payment_currency'] != ''): ?>
                <li>
                  <?php _e("Payment currency"); ?>
                  : <?php print $ord['payment_currency']; ?></li>
                <?php endif; ?>
                <?php if (isset($ord['payer_id']) and $ord['payer_id'] != ''): ?>
                <li>
                  <?php _e("Payer ID"); ?>
                  : <?php print $ord['payer_id']; ?></li>
                <?php endif; ?>
                <?php if (isset($ord['payment_status']) and $ord['payment_status'] != ''): ?>
                <li>
                  <?php _e("Payment Status"); ?>
                  : <?php print $ord['payment_status']; ?></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mw-ui-col">
      <div class="mw-ui-box">
        <div class="mw-ui-box-header"> <a href="<?php print $config['url_main']; ?>/../action:clients#?clientorder=<?php print $ord['id'] ?>" class="mw-ui-btn mw-ui-btn-medium pull-right"> <span class="mw-icon-pen"></span>
          <?php _e("Edit"); ?>
          </a> <span>
          <?php _e("Client Information"); ?>
          </span> </div>
        <div class="mw-ui-box-content">
          <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic">
            <col width="50%"/>
            <tr>
              <td><?php _e("Customer Name"); ?></td>
              <td><?php print $ord['first_name'] . ' ' . $ord['last_name']; ?></td>
            </tr>
            <tr>
              <td><?php _e("Email"); ?></td>
              <td><a href="mailto:<?php print $ord['email'] ?>"><?php print $ord['email'] ?></a></td>
            </tr>
            <tr>
              <td><?php _e("Phone Number"); ?></td>
              <td><b><?php print $ord['phone']; ?></b></td>
            </tr>
            <tr>
              <td><?php _e("User IP"); ?></td>
              <td><?php print $ord['user_ip']; ?>
                <?php if (function_exists('ip2country')): ?>
                <?php print ip2country($ord['user_ip']); ?>
                <?php endif; ?></td>
            </tr>
          </table>
          <div class="mw-ui-box order-details-box">
            <div class="mw-ui-box-header">
              <?php _e("Shipping Address"); ?>
            </div>
            <div class="mw-ui-box-content">
              <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" style="margin-top: 0;">
                <col width="50%"/>
                <tr>
                  <td valign="top"><?php
                $map_click_str = false;
                $map_click = array();?>
                    <ul class="order-table-info-list">
                      <?php if (isset($ord['country']) and $ord['country'] != ''): ?>
                      <li><?php print $ord['country'] ?></li>
                      <?php $map_click[] = $ord['country']; ?>
                      <?php endif; ?>
                      <?php if (isset($ord['city']) and $ord['city'] != ''): ?>
                      <li><?php print $ord['city'] ?></li>
                      <?php $map_click[] = $ord['city']; ?>
                      <?php endif; ?>
                      <?php if (isset($ord['state']) and $ord['state'] != ''): ?>
                      <li><?php print $ord['state'] ?></li>
                      <?php $map_click[] = $ord['city']; ?>
                      <?php endif; ?>
                      <?php if (isset($ord['zip']) and $ord['zip'] != ''): ?>
                      <li><?php print $ord['zip'] ?></li>
                      <?php endif; ?>
                      <?php if (isset($ord['address']) and $ord['address'] != ''): ?>
                      <li><?php print $ord['address'] ?></li>
                      <?php $map_click[] = $ord['address']; ?>
                      <?php endif; ?>
                      <?php if (isset($ord['address2']) and $ord['address2'] != ''): ?>
                      <li><?php print $ord['address2'] ?></li>
                      <?php endif; ?>
                      <?php if (isset($ord['phone']) and $ord['phone'] != ''): ?>
                      <li>
                        <?php _e("Phone"); ?>
                        <?php print $ord['phone'] ?> </li>
                      <?php endif; ?>
                    </ul></td>
                  <td><?php
                if (!empty($map_click)) {
                    $map_click = array_unique($map_click);
                    $map_click_str = implode(', ', $map_click);
                }

                ?>
                    <a target="_blank"
                   href="https://maps.google.com/maps?q=<?php print urlencode($map_click_str) ?>&safe=off"> <img class="map-shipping-address"
                        src="http://maps.googleapis.com/maps/api/staticmap?size=320x320&zoom=17&markers=icon:http://microweber.com/order.png|<?php print urlencode($map_click_str) ?>&sensor=true&center=<?php print urlencode($map_click_str) ?>"/> </a></td>
                </tr>
              </table>
            </div>
          </div>
          <?php if (isset($ord['custom_fields']) and $ord['custom_fields'] != ''): ?>
          <table class="mw-ui-table" cellspacing="0" cellpadding="0">
            <col width="50%"/>
            <tr>
              <td valign="top"><span class="order-detail-title">
                <?php _e("Additional Details"); ?>
                </span> <?php print $ord['custom_fields'] ?></td>
            </tr>
          </table>
          <?php endif; ?>
          <div class="mw-ui-box order-details-box" style="margin-top: 20px;">
            <div class="mw-ui-box-header">
              <?php _e("Billing Details"); ?>
            </div>
            <div class="mw-ui-box-content">
              <table  cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" style="margin-top:0">
                <col width="50%"/>
                <tr>
                  <td valign="top"><ul class="order-table-info-list">
                      <li><?php print $ord['payment_name'] ?></li>
                      <li><?php print $ord['payment_country'] ?></li>
                      <li><?php print $ord['payment_email'] ?></li>
                      <li><?php print $ord['payment_city'] ?></li>
                      <li><?php print $ord['payment_state'] ?></li>
                      <li><?php print $ord['payment_zip'] ?></li>
                      <li><?php print $ord['payment_address'] ?></li>
                    </ul></td>
                  <td valign="top"><a target="_blank"
                       href="https://maps.google.com/maps?q=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&safe=off"> <img class="map-shipping-address"
                    src="https://maps.googleapis.com/maps/api/staticmap?size=320x320&zoom=17&markers=icon:https://microweber.com/user.png|<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&sensor=true&center=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>"/> </a></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
