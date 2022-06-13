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
                id: "<?php print $ord['id']; ?>"
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
                    mw.reload_module('shop/orders');
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
                    mw.reload_module('shop/orders');
                });
            });
        });
    </script>

    <div class="main-toolbar">
        <a href="#vieworder=0" class="btn btn-link text-silver px-0" data-bs-toggle="tooltip" data-title="Back to list"><i class="mdi mdi-chevron-left"></i> <?php _e('Back to orders'); ?></a>
    </div>

    <div class="card bg-light style-1 mb-3">
        <div class="card-header">
            <h5><i class="mdi mdi-shopping text-primary mr-3"></i> <strong><?php _e("Order"); ?> #<?php print $show_ord_id ?></strong></h5>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary"><?php _e('Edit order'); ?></a>
            </div>
        </div>
        <div class="card-body">
            <h5 class="font-weight-bold"><?php _e('Order Information'); ?></h5>

            <module type="shop/orders/views/order_cart" order-id="<?php print $show_ord_id; ?>"/>
        </div>
    </div>

    <div class="card bg-light style-1 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h5 class="font-weight-bold"><?php _e("Client Information"); ?></h5>
                <?php
                if (isset($ord['customer_id']) && $ord['customer_id'] > 0):
                ?><small>
                    Edit client information
                    <a href="<?php echo route('admin.customers.edit', $ord['customer_id']) ?>" class="btn btn-sm btn-outline-primary ml-2 text-dark">
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
                        <?php print $ord['first_name'] . ' ' . $ord['last_name']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <span class="text-primary"><?php _e("Email"); ?></span>
                    </div>
                    <div class="col-6">
                        <a href="mailto:<?php print $ord['email'] ?>"><?php print $ord['email'] ?></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <span class="text-primary"><?php _e('Phone number'); ?></span>
                    </div>
                    <div class="col-6">
                        <?php print $ord['phone']; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <span class="text-primary"><?php _e('User IP'); ?></span>
                    </div>
                    <div class="col-6">
                        <?php if ($ord['user_ip'] == '::1'): ?>
                            localhost
                        <?php else: ?>
                            <?php print $ord['user_ip']; ?>
                            <?php if (function_exists('ip2country')): ?>
                                <?php print ip2country($ord['user_ip']); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($ord['custom_fields']) and $ord['custom_fields'] != ''): ?>
                    <div class="row">
                        <div class="col-6"><?php _e("Additional Details"); ?></div>
                        <div class="col-6">
                            <?php print $ord['custom_fields'] ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card bg-light style-1 mb-3">
        <div class="card-body">
            <h5 class="mb-4 font-weight-bold"><?php _e('Shipping Address'); ?></h5>

            <div class="row d-flex align-items-center">
                <div class="col-md-6">
                    <?php
                    $map_click_str = false;
                    $map_click = array();
                    ?>

                    <?php if (isset($ord['country']) and $ord['country'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("Country"); ?>:</strong> <?php print $ord['country'] ?>
                        </div>
                        <?php $map_click[] = $ord['country']; ?>
                    <?php endif; ?>

                    <?php if (isset($ord['city']) and $ord['city'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("City"); ?>:</strong> <?php print $ord['city'] ?>
                        </div>
                        <?php $map_click[] = $ord['city']; ?>
                    <?php endif; ?>

                    <?php if (isset($ord['state']) and $ord['state'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("State"); ?>:</strong> <?php print $ord['state'] ?>
                        </div>
                        <?php $map_click[] = $ord['city']; ?>
                    <?php endif; ?>

                    <?php if (isset($ord['zip']) and $ord['zip'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("Post code"); ?>:</strong> <?php print $ord['zip'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($ord['address']) and $ord['address'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("Address"); ?>:</strong> <?php print $ord['address'] ?>
                        </div>
                        <?php $map_click[] = $ord['address']; ?>
                    <?php endif; ?>

                    <?php if (isset($ord['address2']) and $ord['address2'] != ''): ?>
                        <div class="mb-2">
                            <strong><?php _e("Address 2"); ?>:</strong> <?php print $ord['address2'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($ord['phone']) and $ord['phone'] != ''): ?>
                        <div class="mb-4">
                            <strong><?php _e("Phone"); ?>:</strong> <?php print $ord['phone'] ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-2">
                        <strong><?php _e('Additional information'); ?>:</strong> <br/>
                        <?php if (isset($ord['other_info']) and $ord['other_info'] != ''): ?>
                            <small class="text-muted"><?php print $ord['other_info'] ?></small>
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
                            <option value="pending" <?php if ($ord['order_status'] == 'pending'): ?>selected<?php endif; ?>>Pending
                                <small class="text-muted"><?php _e('(the order is not finished yet)'); ?></small>
                            </option>
                            <option value="completed" <?php if ($ord['order_status'] == 'completed' or $ord['order_status'] == null or $ord['order_status'] == ''): ?>selected<?php endif; ?>><?php _e('Back to orders'); ?>Completed
                                <small class="text-muted"><?php _e('(the order is finished)'); ?></small>
                            </option>
                        </select>
                    </div>

                    <div id="mw_order_status" style="overflow: hidden;">
                        <div class="bg-white p-3 mt-1 mb-2 text-center btn btn-outline-warning btn-block rounded-0 <?php if ($ord['order_status'] == 'completed'): ?>semi_hidden<?php endif; ?>">
                            <small class="d-block bg-warning text-white px-3 py-1 mx-auto"><?php _e("Pending"); ?></small>
                        </div>

                        <div class="bg-white p-3 mt-1 mb-2 text-center btn btn-outline-success btn-block rounded-0 <?php if ($ord['order_status'] != 'completed'): ?>semi_hidden<?php endif; ?>">
                            <small class="d-block bg-success text-white px-3 py-1 mx-auto"><?php _e("Successfully Completed"); ?></small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted"><?php _e('Set additional information to your order, helps you to track the order status more effective'); ?></small>
                    </div>
                </div>

                <div class="col-md-6 border-left">
                    <h5 class="mb-3 font-weight-bold"><?php _e("Payment Information"); ?></h5>

                    <div class="mb-3">
                        <?php _e("Payment Method"); ?>:
                        <?php $gw = str_replace('shop/payments/gateways/', '', $ord['payment_gw']); ?>:
                        <strong><?php print $gw; ?></strong>
                    </div>

                    <div class="mb-3">
                        <?php _e("Is paid"); ?>:
                        <div class="d-inline">
                            <select name="is_paid" class="mw-order-is-paid-change selectpicker" data-style="btn-sm" data-width="100px">
                                <option value="1" <?php if (isset($ord['is_paid']) and intval($ord['is_paid']) == 1): ?> selected="selected" <?php endif; ?>>
                                    <?php _e("Yes"); ?>
                                </option>
                                <option value="0" <?php if (!isset($ord['is_paid']) or (isset($ord['is_paid']) and intval($ord['is_paid']) == 0)): ?> selected="selected" <?php endif; ?>>
                                    <?php _e("No"); ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <?php if (isset($ord['transaction_id']) and $ord['transaction_id'] != ''): ?>
                        <div class="mb-3">
                            <?php _e("Transaction ID"); ?>: <?php print $ord['transaction_id']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($ord['payment_amount']) and $ord['payment_amount'] != ''): ?>
                        <div class="mb-3">
                            <?php _e("Payment amount"); ?>: <?php print $ord['payment_amount']; ?>
                            <i class="mdi mdi-help-circle" data-bs-toggle="tooltip" data-title="<?php _e("Amount paid by the user"); ?>"></i>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($ord['payment_currency']) and $ord['payment_currency'] != ''): ?>
                        <div class="mb-3">
                            <?php _e("Payment currency"); ?>: <?php print $ord['payment_currency']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($ord['payer_id']) and $ord['payer_id'] != ''): ?>
                        <div class="mb-3">
                            <?php _e("Payer ID"); ?>: <?php print $ord['payer_id']; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($ord['payment_status']) and $ord['payment_status'] != ''): ?>
                        <div class="mb-3">
                            <?php _e("Payment status"); ?>: <?php print $ord['payment_status']; ?>
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
        <?php event_trigger('mw.ui.admin.shop.order.edit.status.after', $ord); ?>
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

<?php if ($ord['payment_name']
    || $ord['payment_country']
    || $ord['payment_city']
    || $ord['payment_state']
    || $ord['payment_zip']
    || $ord['payment_address']
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
                                <li><?php print $ord['payment_name'] ?></li>
                                <li><?php print $ord['payment_country'] ?></li>
                                <li><?php print $ord['payment_email'] ?></li>
                                <li><?php print $ord['payment_city'] ?></li>
                                <li><?php print $ord['payment_state'] ?></li>
                                <li><?php print $ord['payment_zip'] ?></li>
                                <li><?php print $ord['payment_address'] ?></li>
                            </ul>
                        </td>
                        <td valign="top">
                            <div style="height: 180px; position: relative;">

                                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?hl=en&amp;q=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&amp;ie=UTF8&amp;z=<?php print intval($zoom); ?>&amp;output=embed"></iframe>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
