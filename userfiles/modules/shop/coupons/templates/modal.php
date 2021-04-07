<?php
/*
type: layout
name: Default
description: Default coupon template
*/
?>



<?php

//d($applied_coupon_data);


$applied_code = '';
?>

<div class="checkout-page">
    <div class="box-container">
        <div class="bootstrap3ns">
            <div class="mw-coupons-module">
                <?php if(isset($applied_coupon_data['coupon_code'])): ?>
                    <div class="coupon_code_apply_wrapper" >
                        <h5 class="mb-2"><?php _e("You are using coupon code"); ?></h5>
                        <div class="form-group d-flex">
                                    <input class="form-control" id="disabledInput" type="text" placeholder="<?php print $applied_coupon_data['coupon_name']  ?>" disabled="" style="max-width: 50%;">
                                    <a class="align-self-center mx-4" href="javascript:$('.coupon_code_apply_wrapper').toggle(); void(0);"><?php _e("Change"); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                    <div class="ml-3">
                        <div class="coupon_code_apply_wrapper" <?php if(isset($applied_coupon_data['coupon_code'])): ?>  style="display: none"    <?php endif; ?>  >
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h5 class="mb-2"><?php _e("Enter coupon code"); ?></h5>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6 pr-4">
                                        <input type="text" name="coupon_code" class="form-control js-coupon-code" placeholder="<?php _e("Enter coupon code"); ?>"/>
                                        <div class="js-coupon-code-messages text-danger mt-2"></div>
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="button" class="btn btn-outline-primary js-apply-coupon-code"><?php _e("Apply code"); ?></button>
                                    </div>
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
