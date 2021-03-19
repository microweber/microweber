<?php $allCoupons = coupon_get_all(); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th><?php _e('Coupon'); ?></th>
            <th><?php _e('Code'); ?></th>
            <th><?php _e('Discount'); ?></th>
            <th><?php _e('Total'); ?></th>
            <th  class="text-center"><?php _e('Actions'); ?></th>
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

                        <?php if ($coupon['discount_type'] == 'percentage'): ?>

                            <?php print($coupon['discount_value']); ?>%
                        <?php else: ?>
                        <?php
                            $currencyPrice = currency_format($coupon['discount_value']);
                            $currencyPrice = str_replace(' ', '', $currencyPrice);
                            echo $currencyPrice;
                            ?>
                        <?php endif; ?>
                    </td>
                    <td><?php print($coupon['total_amount']) ?></td>
                    <td class="text-center">
                        <button onclick="editCoupon(<?php print($coupon['id']) ?>)" class="btn btn-outline-primary btn-sm" title="Edit"><?php _e('Edit'); ?></button>
                        &nbsp;
                        <button onclick="deleteCoupon(<?php print($coupon['id']) ?>)" class="btn btn-outline-danger btn-sm" title="Delete"><?php _e('Delete'); ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
