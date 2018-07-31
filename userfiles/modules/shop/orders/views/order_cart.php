<?php

only_admin_access();
$ord = mw()->shop_manager->get_order_by_id($params['order-id']);

$cart_items = array();
if (is_array($ord)) {
    $cart_items = false;
    if (empty($cart_items)) {
        $cart_items = mw()->shop_manager->order_items($ord['id']);
    }
} else {
 //   mw_error("Invalid order id");
}

$show_ord_id = $ord['id'];
if (isset($ord['order_id']) and $ord['order_id'] != false) {
    $show_ord_id = $ord['order_id'];
}
?>
<script>
    function del_this_order_and_return($ord) {
        mw_delete_shop_order($ord, false);
        window.location.href = '<?php print admin_url(); ?>view:shop/action:orders'
    }

   //
</script>
<div class="mw-ui-box mw-ui-box-order-info">
    <div class="mw-ui-box-header">
        <a href="javascript:del_this_order_and_return('<?php print $show_ord_id ?>')"
           class="mw-ui-btn mw-ui-btn-info mw-ui-btn-small mw-ui-btn-outline pull-right"> <span class="mai-bin"></span>Delete</a>
        <span class=" bold"><?php _e("Order Information"); ?></span>
    </div>
    <div class="mw-ui-box-content p-0">
        <?php if ($cart_items and is_array($cart_items)) : ?>
            <div class="mw-order-images" style="display: none;">
                <?php for ($i = 0; $i < sizeof($cart_items); $i++) { ?>
                    <?php if (isset($cart_items[$i]['item_image']) and $cart_items[$i]['item_image'] != false): ?>
                        <?php

                        $p = $cart_items[$i]['item_image']; ?>
                        <?php if ($p != false): ?>
                            <a data-index="<?php print $i; ?>"
                               class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>"
                               style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"
                               href="<?php print ($p); ?>" target="_blank"></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $p = get_picture($cart_items[$i]['rel_id']); ?>
                        <?php if ($p != false): ?>
                            <span data-index="<?php print $i; ?>"
                                  class="bgimage mw-order-item-image mw-order-item-image-<?php print $i; ?>"
                                  style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"></span>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php } ?>
            </div>
            <script>
                $(document).ready(function () {
                    $('.table-responsive .mw-order-item').mouseover(function () {
                        $(this).next('tr').find('td').css({
                            'background': 'rgba(0, 134, 219, 0.07)',
                            'border-bottom': '1px solid #0086db'
                        });
                    });
                    $('.table-responsive .mw-order-item').mouseout(function () {
                        $(this).next('tr').find('td').css({'background': 'none', 'border-bottom': '0'});
                    });


                    $('.table-responsive .mw-order-custom-fields').mouseover(function () {
                        $(this).find('td').css({
                            'background': 'rgba(0, 134, 219, 0.07)',
                            'border-bottom': '1px solid #0086db'
                        });
                        $(this).prev().trigger('mouseenter');
                    });
                    $('.table-responsive .mw-order-custom-fields').mouseout(function () {
                        $(this).find('td').css({'background': 'none', 'border-bottom': '0'});
                        $(this).prev().trigger('mouseleave');
                    });
                });
            </script>
            <div class="table-responsive">
                <table class="mw-ui-table mw-ui-table-basic" cellspacing="0" cellpadding="0" width="100%" id="order-information-table">
                    <thead>
                    <tr>
                        <th><?php _e("Image"); ?></th>
                        <th><?php _e("Product"); ?></th>
                        <!--  <th><?php _e("Custom fields"); ?></th>-->
                        <th><?php _e("Price"); ?></th>
                        <th class="center"><?php _e("QTY"); ?></th>
                        <th><?php _e("Total"); ?></th>
                        <th></th>
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
                        $grandtotal = $subtotal + $ord['shipping'];
                        ?>
                        <tr data-index="<?php print $index; ?>"
                            class="mw-order-item mw-order-item-<?php print $item['id'] ?> mw-order-item-index-<?php print $index; ?>">
                            <td>
                                <?php if (isset($cart_items[$i]['item_image']) and $cart_items[$i]['item_image'] != false): ?>
                                    <?php

                                    $p = $item['item_image']; ?>
                                    <?php if ($p != false): ?>
                                        <a class="bgimage mw-order-item-image>"
                                           style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"
                                           href="<?php print ($p); ?>" target="_blank"></a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php $p = get_picture($item['rel_id']); ?>
                                    <?php if ($p != false): ?>
                                        <span data-index="<?php print $i; ?>"
                                              class="bgimage mw-order-item-image"
                                              style="width: 70px;height:70px;background-image:url(<?php print thumbnail($p, 120, 120); ?>);"></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="mw-order-item-id">
                                <a href="<?php print content_link($item['rel_id']) ?>"
                                   target="_blank"><span><?php print $item['title'] ?></span></a>
                                <?php if ($item['rel_type'] == 'content'): ?>
                                    <?php $data_fields = mw()->content_manager->data($item['rel_id']); ?>
                                    <?php if (isset($data_fields['sku']) and $data_fields['sku'] != ''): ?>
                                        <small class="mw-ui-label-help">
                                            <?php _e("SKU"); ?>: <?php print $data_fields['sku']; ?>
                                        </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <!--  <td class="mw-order-item-fields"></td>-->
                            <td class="mw-order-item-amount nowrap"><?php print  currency_format($item['price'], $ord['currency']); ?></td>
                            <td class="mw-order-item-count center"><?php print $item['qty'] ?></td>
                            <td class="mw-order-item-count"
                                width="100"><?php print  currency_format($item_total, $ord['currency']); ?></td>
                            <td class="mw-order-item-count" style="width: 10px"><a class="show-on-hover"><i
                                            class="mw-icon-close"</a></td>
                        </tr>
                        <?php if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
                            <tr class="mw-order-custom-fields">
                                <td colspan="6"
                                    class="mw-order-product-custom-fields"><?php print $item['custom_fields'] ?></td>
                            </tr>
                        <?php endif ?>
                    <?php endforeach; ?>

                    <tr class="mw-ui-table-footer" style="background: #fafafa; font-weight: bold;">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="4"><?php print _e('TOTAL AMOUNT'); ?></td>
                    </tr>

                    <tr class="mw-ui-table-footer">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="2"><?php _e("Subtotal"); ?></td>
                        <td class="mw-ui-table-green" colspan="2"><?php print  currency_format($subtotal, $ord['currency']); ?></td>
                    </tr>

                    <tr class="mw-ui-table-footer">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="2"><?php _e("Shipping price"); ?></td>
                        <td class="mw-ui-table-green" colspan="2"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></td>
                    </tr>

                    <?php if (isset($ord['taxes_amount']) and $ord['taxes_amount'] != false): ?>
                        <tr class="mw-ui-table-footer">
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2"><?php _e("Tax"); ?></td>
                            <td class="mw-ui-table-green" colspan="2"><?php print  currency_format($ord['taxes_amount'], $ord['currency']); ?></td>
                        </tr>
                    <?php endif ?>

                    <tr class="mw-ui-table-footer last">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="2" class="mw-ui-table-green"><strong><?php _e("Total:"); ?></strong></td>
                        <td class="mw-ui-table-green" colspan="2"><strong><?php print  currency_format($ord['amount'], $ord['currency']); ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="table-responsive">

            <table class="mw-ui-table mw-ui-table-basic" cellspacing="0" cellpadding="0" width="100%" id="order-information-table">


                <tr class="mw-ui-table-footer" style="background: #fafafa; font-weight: bold;">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="4"><?php print _e('TOTAL AMOUNT'); ?></td>
                </tr>



                <tr class="mw-ui-table-footer">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2"><?php _e("Shipping price"); ?></td>
                    <td class="mw-ui-table-green" colspan="2"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></td>
                </tr>

                <?php if (isset($ord['taxes_amount']) and $ord['taxes_amount'] != false): ?>
                    <tr class="mw-ui-table-footer">
                        <td colspan="2">&nbsp;</td>
                        <td colspan="2"><?php _e("Tax"); ?></td>
                        <td class="mw-ui-table-green" colspan="2"><?php print  currency_format($ord['taxes_amount'], $ord['currency']); ?></td>
                    </tr>
                <?php endif ?>

                <tr class="mw-ui-table-footer last">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2" class="mw-ui-table-green"><strong><?php _e("Total:"); ?></strong></td>
                    <td class="mw-ui-table-green" colspan="2"><strong><?php print  currency_format($ord['amount'], $ord['currency']); ?></strong></td>
                </tr>
                <tr class="mw-ui-table-footer last">
                    <td colspan="2">&nbsp;</td>
                    <td colspan="2" class="mw-ui-table-green"><strong><?php _e("Paid:"); ?></strong></td>
                    <td class="mw-ui-table-green" colspan="2"><strong><?php print currency_format($ord['payment_amount'], $ord['payment_currency']); ?></strong></td>
                </tr>

        </table>
    </div>
        <?php endif; ?>

    </div>
</div>