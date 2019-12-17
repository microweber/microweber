<?php $zoom = 10; ?>
<div class="section-header">
    <h2 class="inline-element m-r-10"><?php _e("Order"); ?> #<?php print $show_ord_id ?></h2>

    <!--        <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium tip mw-ui-btn-circle btn-back" data-tip="Back to list" data-tipposition="bottom-center" href="#vieworder=0">-->
    <!--            <span class="mw-icon-arrowleft"></span>-->
    <!--        </a>-->
    <a class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium  btn-back" data-tip="Back to list"
       data-tipposition="bottom-center" href="#vieworder=0">
        <span class="mw-icon-arrowleft"></span> Orders List
    </a>
</div>

<div class="admin-side-content ">
    <div class="mw-ui-row" id="orders-info-row">
        <div class="mw-ui-col">


            <module type="shop/orders/views/order_cart" order-id="<?php print $show_ord_id; ?>"/>


            <div class="mw-ui-box m-t-10" id="order-status">
                <div class="mw-ui-box-header bold"><span><?php _e("Order Status"); ?></span></div>
                <div class="mw-ui-box-content">
                    <div class="order-status-selector">
                        <ul class="mw-ui-inline-list">
                            <li><span><?php _e("What is the status of this order?"); ?></span></li>
                            <li>
                                <label class="mw-ui-check">
                                    <input
                                        <?php if ($ord['order_status'] == 'pending'): ?>checked="checked"<?php endif; ?>
                                        type="radio" name="order_status" value="pending"/>
                                    <span></span>
                                    <span><?php _e("Pending"); ?></span>
                                </label>
                            </li>

                            <li>
                                <label class="mw-ui-check">
                                    <input
                                        <?php if ($ord['order_status'] == 'completed' or $ord['order_status'] == null or $ord['order_status'] == ''): ?>checked="checked"<?php endif; ?>
                                        type="radio" name="order_status" value="completed"/>
                                    <span></span><span><?php _e("Completed Order"); ?></span>
                                </label>
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
                                    var upd_msg = "<?php _ejs("Order is marked as un-paid"); ?>"
                                    if (obj.is_paid == 'y') {
                                        var upd_msg = "<?php _ejs("Order is marked as paid"); ?>";
                                    }
                                    mw.notification.success(upd_msg);
                                    mw.reload_module('shop/orders');
                                });
                            });

                            mw.$("input[name='order_status']").on('change', function () {
                                var data = {id: obj.id, order_status: this.value};
                                $.post(mw.settings.site_url + "api/shop/update_order", data, function () {
                                    if (data.order_status === 'pending') {
                                        mw.$('#mw_order_status .mw-warning').removeClass('semi_hidden');
                                        mw.$('#mw_order_status .mw-success').addClass('semi_hidden');
                                    } else {
                                        mw.$('#mw_order_status .mw-warning').addClass('semi_hidden');
                                        mw.$('#mw_order_status .mw-success').removeClass('semi_hidden');
                                    }
                                    mw.reload_module('shop/orders');
                                });
                            });
                        });
                    </script>

                    <div id="mw_order_status" style="overflow: hidden;">
                        <div style="margin-right: 10px;width: 100%;"
                             class="mw-notification mw-warning right <?php if ($ord['order_status'] == 'completed'): ?>semi_hidden<?php endif; ?>">
                            <div style="height: 55px; text-align: center; ">
                                <?php _e("Pending"); ?>
                            </div>
                        </div>

                        <div style="margin-right: 10px;width: 100%;"
                             class="mw-notification mw-success right <?php if ($ord['order_status'] != 'completed'): ?>semi_hidden<?php endif; ?>">
                            <div style="height: 55px; text-align: center;">
                                <span><?php _e("Successfully Completed"); ?></span></div>
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
            <div class="mw-ui-box">
                <div class="mw-ui-box-header bold">
                    <?php _e("Payment Information"); ?>
                    <span class="mw-icon-help-outline mwahi tip" style="float: none"
                          data-tip="<?php _e("Payment Information that we have from the payment provider"); ?>"
                          data-tipposition="top-center"></span>
                </div>

                <div class="mw-ui-box-content">
                    <ul class="order-table-info-list">
                        <li>
                            <?php _e("Payment Method"); ?>
                            <?php $gw = str_replace('shop/payments/gateways/', '', $ord['payment_gw']); ?>:
                            <strong><?php print $gw; ?></strong>
                        </li>
                        <li>
                            <?php _e("Is Paid"); ?>:
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
                                <?php _e("Transaction ID"); ?>: <?php print $ord['transaction_id']; ?>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($ord['payment_amount']) and $ord['payment_amount'] != ''): ?>
                            <li>
                                <?php _e("Payment Amount"); ?>: <?php print $ord['payment_amount']; ?>
                                <?php

                                /*		  <?php if (isset($ord['payment_shipping']) and $ord['payment_shipping'] != ''): ?>
                                          <span> with <?php print $ord['payment_shipping']; ?>
                                          <?php _e("for shipping"); ?>
                                          </span>
                                          <?php endif; ?>*/


                                ?>

                                <span class="mw-icon-help-outline mwahi tip"
                                      data-tip="<?php _e("Amount paid by the user"); ?>"></span>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($ord['payment_currency']) and $ord['payment_currency'] != ''): ?>
                            <li>
                                <?php _e("Payment currency"); ?>: <?php print $ord['payment_currency']; ?>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($ord['payer_id']) and $ord['payer_id'] != ''): ?>
                            <li>
                                <?php _e("Payer ID"); ?>: <?php print $ord['payer_id']; ?>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($ord['payment_status']) and $ord['payment_status'] != ''): ?>
                            <li>
                                <?php _e("Payment Status"); ?>: <?php print $ord['payment_status']; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mw-ui-col">
            <div class="mw-ui-box">
                <div class="mw-ui-box-header bold">
                    <a href="<?php print admin_url(); ?>view:shop/action:clients#?clientorder=<?php print $ord['id'] ?>"
                       class="mw-ui-btn mw-ui-btn-info mw-ui-btn-small mw-ui-btn-outline pull-right"><span
                                class="mw-icon-pen"></span> <?php _e("Edit"); ?></a>
                    <span><?php _e("Client Information"); ?></span>
                </div>

                <div class="mw-ui-box-content">
                    <div class="table-responsive">
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
                                <td>
                                    <?php if ($ord['user_ip'] == '::1'): ?>
                                        localhost
                                    <?php else: ?>
                                        <?php print $ord['user_ip']; ?>
                                        <?php if (function_exists('ip2country')): ?>
                                            <?php print ip2country($ord['user_ip']); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>

                        <?php if (isset($ord['custom_fields']) and $ord['custom_fields'] != ''): ?>
                            <div class="table-responsive">
                                <table class="mw-ui-table" cellspacing="0" cellpadding="0">
                                    <col width="50%"/>
                                    <tr>
                                        <td valign="top">
                                            <span class="order-detail-title"><?php _e("Additional Details"); ?></span>
                                            <?php print $ord['custom_fields'] ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php endif; ?>


                        <?php if ($ord['payment_name']
                            || $ord['payment_country']
                            || $ord['payment_city']
                            || $ord['payment_state']
                            || $ord['payment_zip']
                            || $ord['payment_address']
                        ): ?>

                            <div class="mw-ui-box order-details-box" style="margin-top: 20px;">
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

                                                            <iframe width="100%" height="100%" frameborder="0" scrolling="no"
                                                                    marginheight="0" marginwidth="0"
                                                                    src="https://maps.google.com/maps?hl=en&amp;q=<?php print urlencode($ord['payment_country'] . ',' . $ord['payment_city'] . ',' . $ord['payment_address']); ?>&amp;ie=UTF8&amp;z=<?php print intval($zoom); ?>&amp;output=embed">
                                                            </iframe>
                                                        </div>





                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mw-ui-box order-details-box m-t-10">
                <div class="mw-ui-box-header bold">
                    <?php _e("Shipping Address"); ?>
                </div>

                <div class="mw-ui-box-content">
                    <div class="table-responsive">
                        <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic"
                               style="margin-top: 0;">
                            <col width="50%"/>
                            <tr>
                                <td valign="top"><?php
                                    $map_click_str = false;
                                    $map_click = array(); ?>
                                    <ul class="order-table-info-list">
                                        <?php if (isset($ord['country']) and $ord['country'] != ''): ?>
                                            <li><strong><?php _e("Country"); ?>:</strong>
                                                <?php print $ord['country'] ?></li>
                                            <?php $map_click[] = $ord['country']; ?>
                                        <?php endif; ?>
                                        <?php if (isset($ord['city']) and $ord['city'] != ''): ?>
                                            <li><strong><?php _e("City"); ?>:</strong>
                                                <?php print $ord['city'] ?></li>
                                            <?php $map_click[] = $ord['city']; ?>
                                        <?php endif; ?>
                                        <?php if (isset($ord['state']) and $ord['state'] != ''): ?>
                                            <li>
                                                <strong><?php _e("State"); ?>:</strong>
                                                <?php print $ord['state'] ?></li>
                                            <?php $map_click[] = $ord['city']; ?>
                                        <?php endif; ?>
                                        <?php if (isset($ord['zip']) and $ord['zip'] != ''): ?>
                                            <li>
                                                <strong><?php _e("ZIP"); ?>:</strong>
                                                <?php print $ord['zip'] ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (isset($ord['address']) and $ord['address'] != ''): ?>
                                            <li><strong><?php _e("Address"); ?>:</strong><br/>
                                                <?php print $ord['address'] ?>
                                            </li>
                                            <?php $map_click[] = $ord['address']; ?>
                                        <?php endif; ?>
                                        <?php if (isset($ord['address2']) and $ord['address2'] != ''): ?>
                                            <li>
                                                <strong><?php _e("Address 2"); ?>:</strong><br/>
                                                <?php print $ord['address2'] ?>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (isset($ord['phone']) and $ord['phone'] != ''): ?>
                                            <li>
                                                <br/>
                                                <br/>
                                                <br/>
                                                <br/>
                                                <br/>
                                                <strong><?php _e("Phone"); ?>:</strong>
                                                <?php print $ord['phone'] ?> </li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                                <td><?php
                                    if (!empty($map_click)) {
                                        $map_click = array_unique($map_click);
                                        $map_click_str = implode(', ', $map_click);
                                    }

                                    ?>
                                    <?php if ($map_click): ?>

                                        <div style="height: 180px; position: relative;">

                                            <iframe width="100%" height="100%" frameborder="0" scrolling="no"
                                                    marginheight="0" marginwidth="0"
                                                    src="https://maps.google.com/maps?hl=en&amp;q=<?php print urlencode($map_click_str) ?>&amp;ie=UTF8&amp;z=<?php print intval($zoom); ?>&amp;output=embed">
                                            </iframe>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <?php if (isset($ord['other_info']) and $ord['other_info'] != ''): ?>
                                        <strong><?php _e("Additional information"); ?></strong><br/><br/>
                                        <?php print $ord['other_info'] ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
