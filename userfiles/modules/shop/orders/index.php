<?php
only_admin_access();


$ord = 'order_by=id desc';


if (isset($params['order'])) {
    $data['order_by'] = $params['order'];
    $ord = 'order_by=' . $params['order'];
}

$ordert_type = 'completed';
$kw = '';

if (isset($params['keyword'])) {
    $kw = '&search_in_fields=email,first_name,last_name,country,created_at,transaction_id,city,state,zip,address,phone,user_ip,payment_gw&keyword=' . $params['keyword'];
}

if (isset($params['order-type']) and $params['order-type'] == 'carts') {
    $ordert_type = 'carts';
    $ord = 'order_by=updated_at desc';
    $orders = get_cart('limit=1000&group_by=session_id&no_session_id=true&order_completed=0&' . $ord);
    //$orders = get_cart('debug=1&limit=1000&group_by=id&no_session_id=true&order_completed=0&'.$ord);

} else {
    $orders = get_orders('no_limit=true&order_completed=1&' . $ord . $kw);

}

?>


<div id="mw-order-table-holder">
    <?php if ($ordert_type == 'completed' and isset($orders) and is_array($orders)) : ?>
        <div class="orders-holder" id="shop-orders">
            <?php foreach ($orders as $item) : ?>
                <script>
                    $(document).ready(function () {
                        $('#order-n-<?php print $item['id'] ?>').on('click', function () {
                            $('.order-data-more').slideUp();
                            $('.order-holder').removeClass('active');
                            mw.accordion('#order-n-<?php print $item['id'] ?>');
                        });
                    });
                </script>

                <div class="order-holder" id="order-n-<?php print $item['id'] ?>">
                    <div class="order-data">
                        <div class="product-image">
                            <?php $cart_item = get_cart('no_session_id=true&order_completed=any&order_id=' . $item['id'] . ''); ?>
                            <?php if (isset($cart_item[0]) and isset($cart_item[0]['rel_id'])) { ?>
                                <?php $p = get_picture($cart_item[0]['rel_id'], $cart_item[0]['rel_type']); ?>
                                <?php if ($p == false and isset($cart_item[0]['item_image']) and $cart_item[0]['item_image'] != false): ?>
                                    <?php $p = $cart_item[0]['item_image']; ?>
                                <?php endif; ?>

                                <?php
                                if (isset($p) and $p != false): ?>
                                    <span class="product-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($p, 120, 120); ?>)"></span>
                                    <?php if (count($cart_item) > 1): ?>
                                        <div class="cnt-products">+<?php echo count($cart_item) - 1; ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php } ?>
                        </div>

                        <div class="order-number">
                            <a class="mw-ord-id" href="#vieworder=<?php print ($item['id']) ?>">#<?php print $item['id'] ?></a>
                        </div>

                        <div class="product-name">
                            <?php if (isset($cart_item[0]) and isset($cart_item[0]['rel_id'])): ?>
                                <?php print($cart_item[0]['title']); ?>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?php if ($item['is_paid'] == 1): ?>
                                <span class="is_paid">
                                <?php print currency_format(floatval($item['amount']), $item['currency'])

                                // print currency_format(floatval($item['amount']) + floatval($item['shipping']),$item['currency']) ?>
                                </span>
                            <?php else : ?>
                                <span class="not_paid">
                                <?php _e("Not paid"); ?>
                            </span>
                            <?php endif; ?>

                        </div>

                        <div><?php print date('M d, Y', strtotime($item['created_at'])); ?></div>


                        <div>
                            <?php if ($item['order_status'] == false): ?>
                                <?php _e("New"); ?>
                            <?php elseif ($item['order_status'] == 'completed'): ?>
                                <span class="mw-order-item-status-completed"><?php _e("Completed"); ?></span>
                            <?php else : ?>
                                <span class="mw-order-item-status-pending"><?php _e("Pending"); ?> </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="order-data-more mw-accordion-content">
                        <a class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info view-order-button" id="vieorder-<?php print $item['id']; ?>" href="#vieworder=<?php print ($item['id']) ?>">
                            <?php _e("View order"); ?>
                        </a>

                        <hr/>
                        <div class="pull-left">
                            <p class="title">Customer Information</p>

                            <?php if (isset($item['first_name']) AND isset($item['last_name'])): ?>
                                <div class="box">
                                    <p class="label">Client Name:</p>
                                    <p class="content"><?php print $item['first_name'] . ' ' . $item['last_name']; ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($item['phone'])): ?>
                                <div class="box">
                                    <p class="label">Phone:</p>
                                    <p class="content"><?php print $item['phone']; ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($item['email'])): ?>
                                <div class="box">
                                    <p class="label">E-mail:</p>
                                    <p class="content"><?php print $item['email'] ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="pull-left">
                            <p class="title">Shipping Information</p>

                            <div class="box">
                                <p class="label">Address:</p>
                                <p class="content">
                                    <?php if (isset($item['country'])): ?>
                                        <?php print $item['country']; ?>,
                                    <?php endif; ?>
                                    <?php if (isset($item['city'])): ?>
                                        <?php print $item['city']; ?>
                                    <?php endif; ?>
                                    <?php if (isset($item['zip'])): ?>
                                        <?php print $item['zip']; ?>,
                                    <?php endif; ?>
                                    <?php if (isset($item['address'])): ?>
                                        <?php print $item['address']; ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <?php if (isset($item['comment'])): ?>
                                <div class="box">
                                    <p class="label">Comment:</p>
                                    <p class="content"><?php print $item['comment'] ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <span class="mw-icon-close new-close tip" data-tip="<?php _e("Delete"); ?>" data-tipposition="top-center"
                          onclick="mw_delete_shop_order('<?php print ($item['id']) ?>');"></span>
                    <div class="clearfix"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mw-clear"></div>
    <br/>

        <script>
            export_orders_to_excel = function (id) {
                data = {}
                $.post(mw.settings.api_url + 'shop/export_orders', data,
                    function (resp) {
                        mw.notification.msg(resp);
                        if (resp.download != undefined) {
                            window.location = resp.download;
                        }
                    });
            }
        </script>
        <div class="export-label">
            <span><?php _e("Export data"); ?>:</span>
            <a class="mw-ui-btn mw-ui-btn-small" href="javascript:export_orders_to_excel()"><?php _e("Excel"); ?></a>
        </div>
    <?php elseif ($ordert_type == 'carts' and isset($orders) and is_array($orders)) : ?>
        <label class="mw-ui-label"><?php _e("Abandoned Carts Section helps you analyze why some customers aren't checking out."); ?></label>
        <div class="mw-ui-box-content">
            <div id="orders_stat" style="height: 250px;"></div>
        </div>


    <?php foreach ($orders

    as $item) : ?>


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

            mw.responsive.table('#shop-orders', {
                breakPoints: {
                    768: 4,
                    600: 2,
                    400: 1
                }
            })

        </script>

        <thead>
        <tr>
            <th><?php _e("Cart"); ?></th>
            <th><?php _e("User statistics"); ?></th>
        </tr>
        </thead>

        <tbody>

        <tr class="mw-order-item-<?php print $item['id'] ?> no-hover">
            <td><?php $cart_items = get_cart('order_completed=0&session_id=' . $item['session_id']); ?>
                <?php if (is_array($cart_items) and !empty($cart_items)) : ?>
                    <?php
                    $recart_base = site_url();
                    if (is_array($cart_items[0]) and isset($cart_items[0]['rel_id'])) {
                        $recart_base = content_link($cart_items[0]['rel_id']);
                    }
                    ?>
                    <div class="mw-order-images">
                        <?php for ($i = 0; $i < sizeof($cart_items); $i++) { ?>
                            <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
                            <?php if ($p != false): ?>

                                <span data-index="<?php print $i; ?>" class="bgimg mw-order-item-image mw-order-item-image-<?php print $i; ?>"
                                      style="width:70px;height:70px;background-image: url(<?php print thumbnail($p, 120, 120); ?>);"></span>
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
                        <?php $index = -1;
                        foreach ($cart_items as $item) : ?>
                            <?php
                            $index++;
                            $item_total = floatval($item['qty']) * floatval($item['price']);
                            $subtotal = $subtotal + $item_total;
                            $grandtotal = $subtotal;
                            ?>
                            <tr
                                    data-index="<?php print $index; ?>"
                                    class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>">
                                <td class="mw-order-item-id"><a href="<?php print content_link($item['rel_id']) ?>" target="_blank"><span><?php print $item['title'] ?></span></a></td>
                                <td class="mw-order-item-fields"><?php if (isset($item['custom_fields'])): ?>
                                        <?php print $item['custom_fields'] ?>
                                    <?php endif ?></td>
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
                <?php endif; ?></td>
            <td style="padding: 20px;">
                <label class="mw-ui-label pull-right">
                    <span class="mw-icon-lite-clock-outline" style="font-size: 16px;top:-1px;right:2px;"></span>
                    <span class="mw-ui-label-small tip" data-tipposition="top-center"
                          data-tip="<?php _e("Last activity on"); ?>: <?php print $item['updated_at'] ?>"><?php print mw('format')->ago($item['updated_at']); ?></span>
                </label>
                <style scoped="scoped">
                    .mw-ui-table thead tr th:last-child {
                        text-align: right
                    }

                </style>
                <?php event_trigger('mw_admin_quick_stats_by_session', $item['session_id']); ?>
                <hr>
                <div class="mw-ui-field-holder" style="padding-bottom: 20px;">
                    <label class="mw-ui-label mw-ui-label-inline" style="width: 120px;"><?php _e("Recover URL"); ?> <span class="mw-icon-help-outline mwahi tip"
                                                                                                                          data-tip="<?php _e("Use this if you need to send it to your clients. They'll be able to restore their Shopping Cart."); ?>"></span></label>

                    <div style="font-size: 11px;color:#bbb;" onclick="mw.wysiwyg.select_all(this);"><?php print $recart_base . '?recart=' . $item['session_id']; ?></div>

                </div>
                <div class="mw-ui-btn-nav pull-right">
                    <a class="mw-ui-btn" href="javascript:mw_delete_shop_order('<?php print ($item['session_id']) ?>',1);"><?php _e("Delete cart"); ?></a>
                    <a class="mw-ui-btn mw-ui-btn-invert" href="<?php print $recart_base . '?recart=' . $item['session_id'] ?>" target="_blank"><?php _e("Recover"); ?></a>
                </div>
            </td>
        </tr>

        </tbody>
    </table><?php endforeach; ?>
    <?php
    $abandoned_carts = get_cart('count=1&no_session_id=true&order_completed=0&group_by=session_id');
    $completed_carts = get_orders('count=1&order_completed=1');
    ?>
        <script>mw.lib.require("morris");</script>
        <script>
            $(document).ready(function () {
                mw.on.moduleReload("<?php print $params['id']; ?>", function () {
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
    <?php endif; ?>
</div>
