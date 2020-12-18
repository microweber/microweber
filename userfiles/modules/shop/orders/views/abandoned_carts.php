<?php
if(!isset($orders) or !$orders){
    return;
}
?>
    <script>
        function del_this_abandoned_cart($ord) {
            mw_delete_shop_order($ord, true);
          //  window.location.href = '<?php print admin_url(); ?>view:shop/action:orders'
        }

        //
    </script>

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
            <?php // event_trigger('mw_admin_quick_stats_by_session', $item['session_id']); ?>
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