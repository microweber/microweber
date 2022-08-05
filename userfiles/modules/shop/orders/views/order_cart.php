<?php

must_have_access();
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
        var delconf = mw_delete_shop_order($ord, false);
        if (delconf) {
            window.location.href = '<?php print admin_url(); ?>view:shop/action:orders'
        }
    }

    function export_this_order_and_return($ord) {
        mw_export_shop_order($ord, false);
    }

    // $(document).ready(function () {
    //     $('.table-responsive .mw-order-item').mouseover(function () {
    //         $(this).next('tr').find('td').css({
    //             'background': 'rgba(0, 134, 219, 0.07)',
    //             'border-bottom': '1px solid #0086db'
    //         });
    //     });
    //
    //     $('.table-responsive .mw-order-item').mouseout(function () {
    //         $(this).next('tr').find('td').css({'background': 'none', 'border-bottom': '0'});
    //     });
    //
    //     $('.table-responsive .mw-order-custom-fields').mouseover(function () {
    //         $(this).find('td').css({
    //             'background': 'rgba(0, 134, 219, 0.07)',
    //             'border-bottom': '1px solid #0086db'
    //         });
    //         $(this).prev().trigger('mouseenter');
    //     });
    //
    //     $('.table-responsive .mw-order-custom-fields').mouseout(function () {
    //         $(this).find('td').css({'background': 'none', 'border-bottom': '0'});
    //         $(this).prev().trigger('mouseleave');
    //     });
    // });

    // $(document).ready(function () {
    //     $('.table-responsive .mw-order-item').mouseover(function () {
    //         $(this).find('td').css({
    //             'background': 'rgba(0, 134, 219, 0.07)',
    //             'border-bottom': '1px solid #0086db',
    //         });
    //     });
    //
    //     $('.table-responsive .mw-order-item').mouseout(function () {
    //         $(this).find('td').css({'background': 'none', 'border-bottom': '0'});
    //     });
    //
    //
    //     $('.table-responsive .mw-order-custom-fields').mouseover(function () {
    //         $(this).find('td').css({
    //             'background': 'rgba(0, 134, 219, 0.07)',
    //             'border-bottom': '1px solid #0086db'
    //         });
    //         $(this).prev().trigger('mouseenter');
    //     });
    //
    //     $('.table-responsive .mw-order-custom-fields').mouseout(function () {
    //         $(this).find('td').css({'background': 'none', 'border-bottom': '0'});
    //         $(this).prev().trigger('mouseleave');
    //     });
    // });
</script>

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

    <style>
        .mw-order-custom-fields ul {
            list-style-type: none;
        }

        .mw-order-custom-fields ul li {
            margin-bottom: 3px;
        }
    </style>

    <div class="table-responsive">
        <table class="table vertical-align-middle table-header-no-border table-primary-hover"
               id="order-information-table">
            <thead class="text-primary">
            <tr>
                <th><?php _e(""); ?></th>
                <th><?php _e("Parameters"); ?></th>
                <th><?php _e("Product"); ?></th>
                <th><?php _e("SKU"); ?></th>
                <th><?php _e("Price"); ?></th>
                <th class="text-center"><?php _e("Qty"); ?></th>
                <th><?php _e("Total"); ?></th>
                <!--<th><?php /*_e(""); */?></th>-->
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
                    <td class="mw-order-item-image">
                        <?php if (isset($cart_items[$i]['item_image']) and $cart_items[$i]['item_image'] != false): ?>
                            <?php $p = $item['item_image']; ?>
                            <?php if ($p != false): ?>
                                <a href="<?php print ($p); ?>" target="_blank"><img
                                            src="<?php print thumbnail($p, 120, 120); ?>"/></a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php $p = get_picture($item['rel_id']); ?>
                            <?php if ($p != false): ?>
                                <a href="<?php print ($p); ?>" target="_blank" data-index="<?php print $i; ?>"><img
                                            src="<?php print thumbnail($p, 120, 120); ?>"/></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($item['custom_fields']) and $item['custom_fields'] != false): ?>
                        <td class="mw-order-custom-fields"><small><?php print $item['custom_fields'] ?></small></td>
                         <?php else :?>
                        <td class="mw-order-custom-fields"><?php _e("N/A"); ?></td>
                    <?php endif ?>
                    <td class="mw-order-item-name">
                        <a href="<?php print content_link($item['rel_id']) ?>"
                           target="_blank"><span><?php print $item['title'] ?></span></a>
                    </td>
                    <td class="mw-order-item-sku">
                        <?php if ($item['rel_type'] == 'content'): ?>
                            <?php $data_fields = mw()->content_manager->data($item['rel_id']); ?>
                            <?php if (isset($data_fields['sku']) and $data_fields['sku'] != ''): ?>
                                <span class="text-muted"><?php print $data_fields['sku']; ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td class="mw-order-item-amount"><?php print  currency_format($item['price'], $ord['currency']); ?></td>
                    <td class="mw-order-item-qty text-center"><?php print $item['qty'] ?></td>
                    <td class="mw-order-item-total-amount"
                        width="100"><?php print  currency_format($item_total, $ord['currency']); ?></td>
                   <!-- <td class="mw-order-item-action" style="width: 10px">
                        <a href="#" class="text-muted" data-bs-toggle="tooltip" data-title="Remove"><i
                                    class="mdi mdi-trash-can-outline mdi-20px"></i></a>
                    </td>-->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="info-table col-md-8 col-lg-6 col-xl-5 ml-auto">
            <div class="row d-none">
                <div class="col-6"></div>
                <div class="col-6"></div>
            </div>

            <div class="row border-0">
                <div class="col-6">
                    <strong><?php _e('Total Amount'); ?></strong>
                </div>
                <div class="col-6"></div>
            </div>

            <div class="row">
                <div class="col-6"><?php _e('Subtotal'); ?></div>
                <div class="col-6"><?php print  currency_format($subtotal, $ord['currency']); ?></div>
            </div>

            <div class="row">
                <div class="col-6"><?php _e('Shipping price'); ?></div>
                <div class="col-6"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></div>
            </div>

            <?php if (isset($ord['taxes_amount']) and $ord['taxes_amount'] != false): ?>
                <div class="row">
                    <div class="col-6"><?php _e("Tax"); ?></div>
                    <div class="col-6"><?php print  currency_format($ord['taxes_amount'], $ord['currency']); ?></div>
                </div>
            <?php endif ?>

            <?php if (isset($ord['discount_value']) and $ord['discount_value'] > 0): ?>
                <div class="row">
                    <div class="col-6"><?php _e("Discount"); ?></div>
                    <div class="col-6">
                        -
                        <?php
                        if ($ord['discount_type'] == "fixed_amount") {
                            print currency_format($ord['discount_value'], $ord['currency']);
                        } else {
                            print $ord['discount_value'] . "%";
                        }
                        ?>
                    </div>
                </div>
            <?php endif ?>

            <div class="row">
                <div class="col-6"><strong><?php _e('Total'); ?></strong></div>
                <div class="col-6"><strong><?php print  currency_format($ord['amount'], $ord['currency']); ?></strong>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php
    $subtotal = 0;
    ?>
    <div class="row">
        <div class="info-table col-md-8 col-lg-6 col-xl-5 ml-auto">
            <div class="row d-none">
                <div class="col-6"></div>
                <div class="col-6"></div>
            </div>

            <div class="row border-0">
                <div class="col-6">
                    <strong><?php _e('Total Amount'); ?></strong>
                </div>
                <div class="col-6"></div>
            </div>

            <div class="row">
                <div class="col-6"><?php _e('Subtotal'); ?></div>
                <div class="col-6"><?php print  currency_format($subtotal, $ord['currency']); ?></div>
            </div>

            <div class="row">
                <div class="col-6"><?php _e('Shipping price'); ?></div>
                <div class="col-6"><?php print  currency_format($ord['shipping'], $ord['currency']); ?></div>
            </div>

            <?php if (isset($ord['taxes_amount']) and $ord['taxes_amount'] != false): ?>
                <div class="row">
                    <div class="col-6"><?php _e("Tax"); ?></div>
                    <div class="col-6"><?php print  currency_format($ord['taxes_amount'], $ord['currency']); ?></div>
                </div>
            <?php endif ?>

            <?php if (isset($ord['discount_value']) and $ord['discount_value'] > 0): ?>
                <div class="row">
                    <div class="col-6"><?php _e("Discount"); ?></div>
                    <div class="col-6">
                        -
                        <?php
                        if ($ord['discount_type'] == "fixed_amount") {
                            print currency_format($ord['discount_value'], $ord['currency']);
                        } else {
                            print $ord['discount_value'] . "%";
                        }
                        ?>
                    </div>
                </div>
            <?php endif ?>

            <div class="row">
                <div class="col-6"><strong><?php _e('Total'); ?></strong></div>
                <div class="col-6"><strong><?php print  currency_format($ord['amount'], $ord['currency']); ?></strong>
                </div>
            </div>

            <div class="row">
                <div class="col-6"><strong><?php _e('Paid'); ?></strong></div>
                <div class="col-6">
                    <strong><?php print currency_format($ord['payment_amount'], $ord['payment_currency']); ?></strong>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="mt-4 d-flex justify-content-between">
    <button type="button" onclick="del_this_order_and_return('<?php print $show_ord_id ?>')"
            class="btn btn-outline-danger btn-sm"><?php _e('Delete'); ?></button>
    <!-- <button type="button" onclick="export_this_order_and_return('<?php /*print $show_ord_id */ ?>')" class="btn btn-outline-primary btn-sm"><?php /*_e('Export Excel'); */ ?></button>-->
</div>
