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


$has_new = true;
?>


<?php
//$notif_html = '';
//$notif_count = mw()->notifications_manager->get('module=shop&rel_type=cart_orders&is_read=0&count=1');
//
//print $notif_count;
//if( $notif_count > 0){
//    $notif_html = '<sup class="mw-notification-count">'.$notif_count.'</sup>';
//}
?>


<div id="mw-order-table-holder">
    <?php if ($ordert_type == 'completed' and isset($orders) and is_array($orders)) : ?>
        <div class="orders-holder" id="shop-orders">
            <?php if ($has_new): ?>
                <div class="you-have-new-orders">
                    <p class="new-orders p-b-10 p-t-10 ">You have <?php //print $notif_html; ?> new orders</p>

                    <?php include(__DIR__ . '/views/orders-list.php'); ?>
                </div>
            <?php endif; ?>

            <p class="bold p-b-10 p-t-10 m-t-20">List of all orders</p>
            <?php include(__DIR__ . '/views/orders-list.php'); ?>
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
                        <?php for ($i = 0;
                                   $i < sizeof($cart_items);
                                   $i++) { ?>
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
        <div class="orders-holder mw-ui-box mw-ui-box-content">
            <div class="you-have-new-orders p-40">
                <p class="no-new-orders">
                    <span class="mw-icon-shopcart"></span><br/>
                    <?php _e("You don't have any orders"); ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>
