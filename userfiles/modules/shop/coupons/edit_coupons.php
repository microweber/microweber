<?php
//include 'src/CouponClass.php';

//$coupon_code = 'da2d1e15';
//$total_amount = 10.00;
//$customer_id = 1;

//$newPrice = CouponClass::calculate_new_price($coupon_code, $total_amount, $customer_id);

//echo $newPrice;

// coupon_log_customer($coupon_code, $customer_id);

$allCoupons = coupon_get_all();
?>
<table class="table-style-3 mw-ui-table layout-auto">
    <thead>
    <tr>
        <th>#</th>
        <th>Coupon</th>
        <th>Code</th>
        <th>Discount</th>
        <th>Total</th>
        <th class="center" style="width:200px;"><?php print _e('Actions'); ?></th>
    </tr>
    </thead>
    <?php
    if ($allCoupons):
        foreach ($allCoupons as $coupon):
            ?>
            <tr<?php if ($coupon['is_active'] == 1): ?> class="js-table-active" <?php else: ?> class="js-table-inactive" <?php endif; ?>>
                <td><?php print($coupon['id']) ?></td>
                <td><?php print($coupon['coupon_name']) ?></td>
                <td><?php print($coupon['coupon_code']) ?></td>
                <td>
                    <?php print($coupon['discount_value']) ?><?php if ($coupon['discount_type'] == 'precentage'): ?>%<?php else: ?>$<?php endif; ?>
                </td>
                <td><?php print($coupon['total_amount']) ?></td>
                <td class="center" style="padding-left: 0; padding-right: 0;">
                    <button onclick="editCoupon(<?php print($coupon['id']) ?>)" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-medium" title="Edit"><?php print _e('Edit'); ?></button>
                    &nbsp;
                    <button onclick="deleteCoupon(<?php print($coupon['id']) ?>)" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium" title="Delete"><?php print _e('Delete'); ?></button>
                </td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>
</table>