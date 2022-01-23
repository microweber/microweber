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

                <p><?php _e("You are using coupon code"); ?> <i title="<?php print $applied_coupon_data['coupon_code']  ?>">
                        <?php print $applied_coupon_data['coupon_name']  ?></i>
                    <a href="javascript:$('.coupon_code_apply_wrapper-<?php echo $params['id']; ?>').toggle(); void(0);">(change)</a>
                </p>
                </div>
               <?php endif; ?>

                <div class="coupon_code_apply_wrapper-<?php echo $params['id']; ?>" <?php if(isset($applied_coupon_data['coupon_code'])): ?>  style="display: none"    <?php endif; ?>  >
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 class="mb-2"><?php _e("Enter coupon code"); ?></h4>
                            <hr />
                            <div class="js-coupon-code-messages-<?php echo $params['id']; ?>"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <input type="text" name="coupon_code" class="form-control js-coupon-code-<?php echo $params['id']; ?>" placeholder="<?php _e("Enter coupon code"); ?>"/>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="btn btn-default checkout-v2-coupon-code-button js-apply-coupon-code-<?php echo $params['id']; ?>"><?php _e("Apply code"); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
