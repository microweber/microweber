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
                    <div class="coupon_code_apply_wrapper-<?php echo $params['id']; ?>" >
                        <h5 class="mb-2"><?php _e("You are using coupon code"); ?></h5>
                        <div class="form-group d-flex">
                                    <input class="form-control" id="disabledInput" type="text" placeholder="<?php print $applied_coupon_data['coupon_name']  ?>" disabled="" style="max-width: 50%;">
                                    <a class="align-self-center mx-4" href="javascript:$('.coupon_code_apply_wrapper-<?php echo $params['id']; ?>').toggle(); void(0);"><?php _e("Change"); ?></a>
                        </div>
                    </div>
                <?php endif; ?>

                    <div class="coupon_code_apply_wrapper-<?php echo $params['id']; ?>" <?php if(isset($applied_coupon_data['coupon_code'])): ?>  style="display: none"    <?php endif; ?>  >
                            <div class="row">
                                <div class="col-xs-12">
                                    <label class="control-label font-weight-bold ms-3 mb-3"><?php _e("Enter coupon code"); ?></label>
                                </div>
                            </div>

                            <div class="row col-12 px-0 mx-0">
                                <div class="col-9 pr-4">
                                    <input type="text" name="coupon_code" class="form-control js-coupon-code-<?php echo $params['id']; ?>" placeholder="<?php _e("Enter coupon code"); ?>"/>
                                    <div class="js-coupon-code-messages-<?php echo $params['id']; ?> text-danger mt-2"></div>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-outline-primary checkout-v2-coupon-code-button js-apply-coupon-code-<?php echo $params['id']; ?> px-4"><?php _e("Apply"); ?></button>
                                </div>
                            </div>
                    </div>

            </div>
        </div>
    </div>
</div>
