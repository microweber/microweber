<div class="mw-ui-row shipping-and-payment mw-shop-checkout-personal-info-holder">
    <div class="mw-ui-col" style="width: 33%;">
        <div class="mw-ui-col-container">
            <div class="well">
                <?php $user = get_user(); ?>
                <h2 style="margin-top:0 " class="edit nodrop" field="checkout_personal_inforomation_title"
                    rel="global" rel_id="<?php print $params['id'] ?>">
                    <?php _e("Personal Information"); ?>
                </h2>
                <hr/>
                <label>
                    <?php _e("First Name"); ?>
                </label>
                <input name="first_name" class="field-full form-control" type="text"
                       value="<?php if (isset($user['first_name'])) {
                           print $user['first_name'];
                       } ?>"/>
                <label>
                    <?php _e("Last Name"); ?>
                </label>
                <input name="last_name" class="field-full form-control" type="text"
                       value="<?php if (isset($user['last_name'])) {
                           print $user['last_name'];
                       } ?>"/>
                <label>
                    <?php _e("Email"); ?>
                </label>
                <input name="email" class="field-full form-control" type="text"
                       value="<?php if (isset($user['email'])) {
                           print $user['email'];
                       } ?>"/>
                <label>
                    <?php _e("Phone"); ?>
                </label>
                <input name="phone" class="field-full form-control" type="text"
                       value="<?php if (isset($user['phone'])) {
                           print $user['phone'];
                       } ?>"/>
            </div>
        </div>
    </div>
    <?php if ($cart_show_shipping != 'n'): ?>
        <div class="mw-ui-col mw-shop-checkout-shipping-holder">
            <div class="mw-ui-col-container">
                <module type="shop/shipping"/>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($cart_show_payments != 'n'): ?>
        <div class="mw-ui-col">
            <div class="mw-ui-col-container mw-shop-checkout-payments-holder">
                <module type="shop/payments"/>
            </div>
        </div>
    <?php endif; ?>
</div>