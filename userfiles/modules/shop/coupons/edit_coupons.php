<?php $allCoupons = coupon_get_all(); ?>
<div class="table-responsive">
    <table class="table card-table table-vcenter">
        <thead>
        <tr>
            <th><?php _e('Coupon'); ?></th>
            <th><?php _e('Code'); ?></th>
            <th><?php _e('Discount'); ?></th>
            <th><?php _e('Discount Type'); ?></th>
            <th><?php _e('Total'); ?></th>
            <th><?php _e('Status'); ?></th>
            <th  class="text-center"><?php _e('Actions'); ?></th>
        </tr>
        </thead>
        <?php
        if ($allCoupons): ?>
            <?php foreach ($allCoupons as $coupon): ?>
                <tr<?php if ($coupon['is_active'] == 1): ?> class="js-table-active" <?php else: ?> class="js-table-inactive" <?php endif; ?>>

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
                    <td>
                        <?php if ($coupon['discount_type'] == 'percentage'): ?>
                        <?php _e('Percentage'); ?>
                        <?php else: ?>
                         <?php _e('Fixed Amount'); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php print($coupon['total_amount']) ?></td>
                    <td>
                        <?php if ($coupon['is_active'] == '1'): ?>
                            <?php _e('Active'); ?>
                        <?php else: ?>
                            <?php _e('Inactive'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="d-flex align-items-center justify-content-center">
                        <a onclick="editCoupon(<?php print($coupon['id']) ?>)" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"> <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="18px" viewBox="0 0 24 24" width="18px"><g><rect fill="none" height="24" width="24"></rect></g><g><g><g><path d="M3,21l3.75,0L17.81,9.94l-3.75-3.75L3,17.25L3,21z M5,18.08l9.06-9.06l0.92,0.92L5.92,19L5,19L5,18.08z"></path></g><g><path d="M18.37,3.29c-0.39-0.39-1.02-0.39-1.41,0l-1.83,1.83l3.75,3.75l1.83-1.83c0.39-0.39,0.39-1.02,0-1.41L18.37,3.29z"></path></g></g></g></svg> </a>
                        &nbsp;
                        <a onclick="deleteCoupon(<?php print($coupon['id']) ?>)" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete" class="text-danger ms-2"> <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
