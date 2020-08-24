<?php $allCoupons = coupon_get_all(); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Coupon</th>
            <th>Code</th>
            <th>Discount</th>
            <th>Total</th>
            <th  class="text-center"><?php print _e('Actions'); ?></th>
        </tr>
        </thead>
        <?php
        if ($allCoupons): ?>
            <?php foreach ($allCoupons as $coupon): ?>
                <tr<?php if ($coupon['is_active'] == 1): ?> class="js-table-active" <?php else: ?> class="js-table-inactive" <?php endif; ?>>
                    <td><?php print($coupon['id']) ?></td>
                    <td><?php print($coupon['coupon_name']) ?></td>
                    <td><?php print($coupon['coupon_code']) ?></td>
                    <td>
                        <?php print($coupon['discount_value']) ?><?php if ($coupon['discount_type'] == 'precentage'): ?>%<?php else: ?>$<?php endif; ?>
                    </td>
                    <td><?php print($coupon['total_amount']) ?></td>
                    <td class="text-center">
                        <button onclick="editCoupon(<?php print($coupon['id']) ?>)" class="btn btn-outline-primary btn-sm" title="Edit"><?php print _e('Edit'); ?></button>
                        &nbsp;
                        <button onclick="deleteCoupon(<?php print($coupon['id']) ?>)" class="btn btn-outline-danger btn-sm" title="Delete"><?php print _e('Delete'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>