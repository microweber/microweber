<?php $allCoupons = coupon_get_all(); ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th><?php _e('Coupon'); ?></th>
            <th><?php _e('Code'); ?></th>
            <th><?php _e('Discount'); ?></th>
            <th><?php _e('Discount Type'); ?></th>
            <th><?php _e('Total'); ?></th>
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M300 536q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T360 396q0-25-17.5-42.5T300 336q-25 0-42.5 17.5T240 396q0 25 17.5 42.5T300 456Zm360 440q-58 0-99-41t-41-99q0-58 41-99t99-41q58 0 99 41t41 99q0 58-41 99t-99 41Zm0-80q25 0 42.5-17.5T720 756q0-25-17.5-42.5T660 696q-25 0-42.5 17.5T600 756q0 25 17.5 42.5T660 816Zm-444 80-56-56 584-584 56 56-584 584Z"/></svg>
                        <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="24" viewBox="0 96 960 960" width="24"><path d="M640 536q17 0 28.5-11.5T680 496q0-17-11.5-28.5T640 456q-17 0-28.5 11.5T600 496q0 17 11.5 28.5T640 536Zm-320-80h200v-80H320v80ZM180 936q-34-114-67-227.5T80 476q0-92 64-156t156-64h200q29-38 70.5-59t89.5-21q25 0 42.5 17.5T720 236q0 6-1.5 12t-3.5 11q-4 11-7.5 22.5T702 305l91 91h87v279l-113 37-67 224H480v-80h-80v80H180Zm60-80h80v-80h240v80h80l62-206 98-33V476h-40L620 336q0-20 2.5-38.5T630 260q-29 8-51 27.5T547 336H300q-58 0-99 41t-41 99q0 98 27 191.5T240 856Zm240-298Z"/></svg>
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
