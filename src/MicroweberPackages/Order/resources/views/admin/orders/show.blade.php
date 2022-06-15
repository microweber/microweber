<?php $zoom = 10; ?>
<script>mw.lib.require('mwui_init');</script>

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
            id: "<?php print $order['id']; ?>"
        }

        mw.$(".mw-order-is-paid-change").change(function () {
            var val = this.value;
            obj.is_paid = val;
            $.post(mw.settings.site_url + "api/shop/update_order", obj, function () {
                var upd_msg = "<?php _ejs("Order is marked as un-paid"); ?>"
                if (obj.is_paid == 'y') {
                    var upd_msg = "<?php _ejs("Order is marked as paid"); ?>";
                }
                mw.notification.success(upd_msg);
            });
        });

        mw.$("select[name='order_status']").on('change', function () {
            var data = {id: obj.id, order_status: this.value};
            $.post(mw.settings.site_url + "api/shop/update_order", data, function () {
                if (data.order_status === 'pending') {
                    mw.$('#mw_order_status .btn-outline-warning').removeClass('semi_hidden');
                    mw.$('#mw_order_status .btn-outline-success').addClass('semi_hidden');
                } else {
                    mw.$('#mw_order_status .btn-outline-warning').addClass('semi_hidden');
                    mw.$('#mw_order_status .btn-outline-success').removeClass('semi_hidden');
                }
            });
        });
    });
</script>

@include('order::admin.orders.partials.javascripts')

<div class="main-toolbar">
    <a href="{{route('admin.order.index')}}" class="btn btn-link text-silver px-0" data-bs-toggle="tooltip" data-title="Back to list"><i class="mdi mdi-chevron-left"></i> <?php _e('Back to orders'); ?></a>
</div>

<div class="card bg-light style-1 mb-3">
    <div class="card-header">
        <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("Order"); ?> #<?php print $order['id'] ?></strong></h5>
       <div  data-bs-toggle="tooltip" title="<?php print mw()->format->ago($order['created_at']); ?>">
        <?php print date('M d, Y', strtotime($order['created_at'])); ?>
       </div>
        {{--<div>
            <a href="#" class="btn btn-sm btn-outline-secondary"><?php _e('Edit order'); ?></a>
        </div>--}}
    </div>
    <div class="card-body">
        <h5 class="font-weight-bold"><?php _e('Order Information'); ?></h5>

        <module type="shop/orders/views/order_cart" order-id="<?php print $order['id']; ?>"/>

    </div>
</div>

<div class="card bg-light style-1 mb-3">
    <div class="card-body">
        <div class="d-flex mb-3">
            <h5 class="font-weight-bold"><?php _e("Client Information"); ?></h5>
            <?php
            if (isset($order['customer_id']) && $order['customer_id'] > 0):
            ?><small>
                <a href="<?php echo route('admin.customers.edit', $order['customer_id']) ?>" class="btn btn-sm btn-outline-primary ml-2 text-dark">
                    <?php _e("Edit"); ?>
                </a>
            </small>
            <?php endif;?>
        </div>

        <div class="info-table">
            <div class="row">
                <div class="col-6">
                    <span class="text-primary"><?php _e('Customer name'); ?></span>
                </div>
                <div class="col-6">
                    <?php print $order['first_name'] . ' ' . $order['last_name']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <span class="text-primary"><?php _e("Email"); ?></span>
                </div>
                <div class="col-6">
                    <a href="mailto:<?php print $order['email'] ?>"><?php print $order['email'] ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <span class="text-primary"><?php _e('Phone number'); ?></span>
                </div>
                <div class="col-6">
                    <?php print $order['phone']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <span class="text-primary"><?php _e('User IP'); ?></span>
                </div>
                <div class="col-6">
                    <?php if ($order['user_ip'] == '::1'): ?>
                    localhost
                    <?php else: ?>
                            <?php print $order['user_ip']; ?>
                            <?php if (function_exists('ip2country')): ?>
                                <?php print ip2country($order['user_ip']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                </div>
            </div>

            <?php if (isset($order['custom_fields']) and $order['custom_fields'] != ''): ?>
            <div class="row">
                <div class="col-6"><?php _e("Additional Details"); ?></div>
                <div class="col-6">
                    <?php print $order['custom_fields'] ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="card bg-light style-1 mb-3">
    <div class="card-body">
        <h5 class="mb-4 font-weight-bold"><?php _e('Shipping details'); ?></h5>
        <div class="row d-flex align-items-center">


            <?php
            $shippingGatewayModuleInfo = module_info($order['shipping_service']);
            $icon = (isset($shippingGatewayModuleInfo['settings']['icon_class']) ? $shippingGatewayModuleInfo['settings']['icon_class'] : false);
            if (isset($shippingGatewayModuleInfo['name'])):
            ?>
            <div class="col-md-12">
                <div class="mb-4">
                    <strong><?php _e("Shipping type"); ?>:</strong>
                    <i class="<?php echo $icon; ?>" style="font-size:23px"></i>  <?php echo $shippingGatewayModuleInfo['name'];?>
                </div>
            </div>
             <?php endif; ?>


            <?php
            if ($order['shipping_service'] == 'shop/shipping/gateways/country'):
            ?>
            <div class="col-md-6">

                <?php
                $map_click_str = false;
                $map_click = array();
                ?>

                <?php if (isset($order['country']) and $order['country'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("Country"); ?>:</strong> <?php print $order['country'] ?>
                </div>
                <?php $map_click[] = $order['country']; ?>
                <?php endif; ?>

                <?php if (isset($order['city']) and $order['city'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("City"); ?>:</strong> <?php print $order['city'] ?>
                </div>
                <?php $map_click[] = $order['city']; ?>
                <?php endif; ?>

                <?php if (isset($order['state']) and $order['state'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("State"); ?>:</strong> <?php print $order['state'] ?>
                </div>
                <?php $map_click[] = $order['city']; ?>
                <?php endif; ?>

                <?php if (isset($order['zip']) and $order['zip'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("Post code"); ?>:</strong> <?php print $order['zip'] ?>
                </div>
                <?php endif; ?>

                <?php if (isset($order['address']) and $order['address'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("Address"); ?>:</strong> <?php print $order['address'] ?>
                </div>
                <?php $map_click[] = $order['address']; ?>
                <?php endif; ?>

                <?php if (isset($order['address2']) and $order['address2'] != ''): ?>
                <div class="mb-2">
                    <strong><?php _e("Address 2"); ?>:</strong> <?php print $order['address2'] ?>
                </div>
                <?php endif; ?>

                <?php if (isset($order['phone']) and $order['phone'] != ''): ?>
                <div class="mb-4">
                    <strong><?php _e("Phone"); ?>:</strong> <?php print $order['phone'] ?>
                </div>
                <?php endif; ?>

                <div class="mb-2">
                    <strong><?php _e('Additional information'); ?>:</strong> <br/>
                    <?php if (isset($order['other_info']) and $order['other_info'] != ''): ?>
                    <small class="text-muted"><?php print $order['other_info'] ?></small>
                    <?php else: ?>
                    <small class="text-muted"><?php _e('No additional information regarding delivery specification of this order.'); ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <?php
                if (!empty($map_click)) {
                    $map_click = array_unique($map_click);
                    $map_click_str = implode(', ', $map_click);
                }

                ?>
                <?php if ($map_click): ?>
                <div style="height: 250px; position: relative;">
                    <iframe width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"
                            src="https://maps.google.com/maps?hl=en&amp;q=<?php print urlencode($map_click_str) ?>&amp;ie=UTF8&amp;z=<?php print intval($zoom); ?>&amp;output=embed">
                    </iframe>
                </div>
                <?php endif; ?>
            </div>
                <?php endif; ?>
        </div>
    </div>
</div>

<!--
    <div class="card bg-light style-1 mb-3">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-4 font-weight-bold">Shipping information</h5>

                    <div class="mb-4">
                        <strong>Provider:</strong> ECONT Bulgaria
                        <div class="d-inline ml-3">
                            <select class="selectpicker" data-style="btn-sm" data-width="150px">
                                <option>ECONT Bulgaria</option>
                                <option>Speedy</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Shipping number:</strong> 112-22334-44<br/>
                        <small class="text-muted">Shipping traking number</small>
                    </div>

                    <div class="mb-4">
                        <strong>Shipping status:</strong><br/>
                        <small class="text-muted">Shipping Status</small>
                    </div>

                    <div class="mb-4">
                        <strong>Additional information:</strong><br/>
                        <small class="text-muted">No additional information regarding delivery specification of this order.</small>
                    </div>
                </div>

                <div class="col-md-6 text-md-right">
                    <small>Edit shipment information <a href="#" class="btn btn-sm btn-outline-primary ml-2 text-dark">Edit</a></small>
                    <br/>
                    <br/>
                    <img src="<?php /*print modules_url(); */?>microweber/api/libs/mw-ui/assets/img/shipping_EcontExpress.jpg"/>
                </div>
            </div>
        </div>
    </div>-->

<div class="card bg-light style-1 mb-3">
    <div class="card-body">

        <div class="row d-flex">
            <div class="col-md-6">
                <h5 class="mb-3 font-weight-bold"><?php _e('Order Status'); ?></h5>

                <div class="mb-2">
                    <?php _e("What is the status of this order?"); ?>
                </div>
                <div class="mb-2">
                    <select name="order_status" class="selectpicker" data-style="btn-sm" data-width="100%">
                        <option value="pending" <?php if ($order['order_status'] == 'pending'): ?>selected<?php endif; ?>>Pending
                            <small class="text-muted">(<?php _e('the order is not completed yet'); ?>)</small>
                        </option>
                        <option value="completed" <?php if ($order['order_status'] == 'completed' or $order['order_status'] == null or $order['order_status'] == ''): ?>selected<?php endif; ?>><?php _e('Completed'); ?>
                            <small class="text-muted">(<?php _e('the order is completed'); ?>)</small>
                        </option>
                    </select>
                </div>

                <div id="mw_order_status" style="overflow: hidden;">
                    <div class="bg-white p-3 mt-1 mb-2 text-center btn btn-outline-warning btn-block rounded-0 <?php if ($order['order_status'] == 'completed'): ?>semi_hidden<?php endif; ?>">
                        <small class="d-block bg-warning text-white px-3 py-1 mx-auto"><?php _e("Pending"); ?></small>
                    </div>

                    <div class="bg-white p-3 mt-1 mb-2 text-center btn btn-outline-success btn-block rounded-0 <?php if ($order['order_status'] != 'completed'): ?>semi_hidden<?php endif; ?>">
                        <small class="d-block bg-success text-white px-3 py-1 mx-auto"><?php _e("Successfully Completed"); ?></small>
                    </div>
                </div>

                <div class="mb-2">
                    <small class="text-muted"><?php _e('Set additional information to your order, helps you to track the order status more effective'); ?></small>
                </div>


                <?php if (isset($order['created_at']) and $order['created_at'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Created at"); ?>: <?php print date('M d, Y H:i', strtotime($order['created_at'])); ?>
                    <small class="text-muted  "><?php print mw()->format->ago($order['created_at']); ?>  </small>

                </div>
                <?php endif; ?>

                <?php if (isset($order['updated_at']) and $order['updated_at'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Updated at"); ?>: <?php print date('M d, Y H:i', strtotime($order['updated_at'])); ?>
                    <small class="text-muted  "><?php print mw()->format->ago($order['updated_at']); ?>  </small>

                </div>
                <?php endif; ?>

                <?php if (isset($order['created_by']) and $order['created_by'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Created by"); ?>: <?php print user_name($order['created_by']); ?> (ID: <?php print ($order['created_by']); ?> )
                </div>
                <?php endif; ?>





            </div>

            <div class="col-md-6 border-left">
                <h5 class="mb-3 font-weight-bold"><?php _e("Payment Information"); ?></h5>

                <div class="mb-3">
                    <?php _e("Payment Method"); ?>:
                        <?php
                        $paymentGatewayModuleInfo = module_info($order['payment_gw']);
                        if($paymentGatewayModuleInfo){


                            if (isset($paymentGatewayModuleInfo['settings']['icon_class'])):
                            ?>
                            <i class="<?php echo $paymentGatewayModuleInfo['settings']['icon_class'];?>" style="font-size:23px"></i>
                            <?php else: ?>
                        <?php if (isset($paymentGatewayModuleInfo['icon'])): ?>

                            <img src="<?php echo $paymentGatewayModuleInfo['icon'];?>" style="width:23px" />

                        <?php endif; ?>


                            <?php endif; ?>

                            <?php echo $paymentGatewayModuleInfo['name'];?>

                        <?php } ?>



                </div>

                <div class="mb-3">
                    <?php _e("Is paid"); ?>:
                    <div class="d-inline">
                        <select name="is_paid" class="mw-order-is-paid-change selectpicker" data-style="btn-sm" data-width="100px">
                            <option value="1" <?php if (isset($order['is_paid']) and intval($order['is_paid']) == 1): ?> selected="selected" <?php endif; ?>>
                                <?php _e("Yes"); ?>
                            </option>
                            <option value="0" <?php if (!isset($order['is_paid']) or (isset($order['is_paid']) and intval($order['is_paid']) == 0)): ?> selected="selected" <?php endif; ?>>
                                <?php _e("No"); ?>
                            </option>
                        </select>
                    </div>
                </div>

                <?php if (isset($order['transaction_id']) and $order['transaction_id'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Transaction ID"); ?>: <?php print $order['transaction_id']; ?>
                </div>
                <?php endif; ?>

                <?php if (isset($order['payment_amount']) and $order['payment_amount'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Payment amount"); ?>: <?php print $order['payment_amount']; ?>
                    <i class="mdi mdi-help-circle" data-bs-toggle="tooltip" data-title="<?php _e("Amount paid by the user"); ?>"></i>
                </div>
                <?php endif; ?>
                <?php if (isset($order['payment_currency']) and $order['payment_currency'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Payment currency"); ?>: <?php print $order['payment_currency']; ?>
                </div>
                <?php endif; ?>
                <?php if (isset($order['payer_id']) and $order['payer_id'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Payer ID"); ?>: <?php print $order['payer_id']; ?>
                </div>
                <?php endif; ?>
                <?php if (isset($order['payment_status']) and $order['payment_status'] != ''): ?>
                <div class="mb-3">
                    <?php _e("Payment status"); ?>: <?php print $order['payment_status']; ?>
                </div>
                <?php endif; ?>





            </div>
        </div>
    </div>
</div>

<!--  <div class="card bg-light style-1 mb-3">
      <div class="card-body">
          <h5 class="mb-3 font-weight-bold">Invoices</h5>

          <div class="info-table">
              <div class="row d-flex align-items-center">
                  <div class="col-md-6">
                      <span class="text-primary">Invoice SAJ/2020/003</span>
                  </div>
                  <div class="col-md-6 text-end text-right">
                      <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                  </div>
              </div>
              <div class="row d-flex align-items-center">
                  <div class="col-md-6">
                      <span class="text-primary">Invoice SAJ/2020/003</span>
                  </div>
                  <div class="col-md-6 text-end text-right">
                      <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                  </div>
              </div>
          </div>
      </div>
  </div>-->

<div>
    <?php event_trigger('mw.ui.admin.shop.order.edit.status.after', $order); ?>
    <?php $edit_order_custom_items = mw()->ui->module('mw.ui.admin.shop.order.edit.status.after'); ?>
    <?php if (!empty($edit_order_custom_items)): ?>
    <?php foreach ($edit_order_custom_items as $item): ?>
    <?php $view = (isset($item['view']) ? $item['view'] : false); ?>
    <?php $link = (isset($item['link']) ? $item['link'] : false); ?>
    <?php $text = (isset($item['text']) ? $item['text'] : false); ?>
    <?php $icon = (isset($item['icon_class']) ? $item['icon_class'] : false); ?>
    <?php $html = (isset($item['html']) ? $item['html'] : false); ?>

    <?php if ($view == false and $link != false) {
        $btnurl = $link;
    } else {
        $btnurl = admin_url('view:') . $view;
    } ?>
    <div class="mw-ui-box" style="margin-bottom: 20px;">
        <div class="mw-ui-box-header"><?php if ($icon) { ?><span
                    class="<?php print $icon; ?>"></span><?php } ?>
            <span><?php print $text; ?></span></div>
        <div class="mw-ui-box-content"><?php print $html; ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($order['payment_name']
|| $order['payment_country']
|| $order['payment_city']
|| $order['payment_state']
|| $order['payment_zip']
|| $order['payment_address']
): ?>

<div class="mw-ui-box order-details-box">
    <div class="mw-ui-box-header">
        <?php _e("Billing Details"); ?>
    </div>
    <div class="mw-ui-box-content">
        <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic"
                   style="margin-top:0">
                <col width="50%"/>
                <tr>
                    <td valign="top">
                        <ul class="order-table-info-list">
                            <li><?php print $order['payment_name'] ?></li>
                            <li><?php print $order['payment_country'] ?></li>
                            <li><?php print $order['payment_email'] ?></li>
                            <li><?php print $order['payment_city'] ?></li>
                            <li><?php print $order['payment_state'] ?></li>
                            <li><?php print $order['payment_zip'] ?></li>
                            <li><?php print $order['payment_address'] ?></li>
                        </ul>
                    </td>
                    <td valign="top">
                        <div style="height: 180px; position: relative;">

                            <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?hl=en&amp;q=<?php print urlencode($order['payment_country'] . ',' . $order['payment_city'] . ',' . $order['payment_address']); ?>&amp;ie=UTF8&amp;z=<?php print intval($zoom); ?>&amp;output=embed"></iframe>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
