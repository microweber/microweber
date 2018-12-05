<?php
$cart_show_payments = 'n';
?>
<div class="mw-ui-row shipping-and-payment mw-shop-checkout-personal-info-holder">
    <div class="mw-ui-col" style="width: 50%;">
        <div class="mw-ui-col-container">
            <?php $user = get_user(); ?>
            <h5 style="margin-top:0 " class="edit nodrop" field="checkout_personal_inforomation_title"
                rel="global" rel_id="<?php print $params['id'] ?>">
                <?php _e("Personal Information"); ?>
            </h5>
            <hr/>


            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("First Name"); ?></label>
                <input name="first_name" class="mw-ui-field mw-full-width" type="text" value="<?php if (isset($user['first_name'])) {
                    print $user['first_name'];
                } ?>"/>
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Last Name"); ?></label>
                <input name="last_name" class="mw-ui-field mw-full-width" type="text" value="<?php if (isset($user['last_name'])) {
                    print $user['last_name'];
                } ?>"/>
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Email"); ?></label>
                <input name="email" class="mw-ui-field mw-full-width" type="text" value="<?php if (isset($user['email'])) {
                    print $user['email'];
                } ?>"/>
            </div>

            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e("Phone"); ?></label>
                <input name="phone" class="mw-ui-field mw-full-width" type="text" value="<?php if (isset($user['phone'])) {
                    print $user['phone'];
                } ?>"/>
            </div>
        </div>
    </div>

    <?php if ($cart_show_shipping != 'n'): ?>
        <div class="mw-ui-col mw-shop-checkout-shipping-holder">
            <div class="mw-ui-col-container">
                <module type="shop/shipping" template="mw_default"/>
            </div>
        </div>
    <?php endif; ?>
</div>