<?php

namespace Modules\Coupons\Listeners;

class OrderWasCreatedCouponCodeLogger
{
    public function handle($event)
    {

        $data = $event->getData();
        $model = $event->getModel();

        if ($data) {
             if (isset($data['promo_code']) and isset($data['email'])) {
                $couponCode = $data['promo_code'];
                $couponEmail = $data['email'];

                if (!empty($couponCode)) {
                    coupon_consume($couponCode, $couponEmail);
                }
            }
        }
    }
}
