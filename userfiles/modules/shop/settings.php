<div class="section-header m-b-10">
    <h2><span class="mai-setting2"></span><?php _e("Shop settings"); ?></h2>
</div>

<div class="admin-side-content">
    <div id="payments-accordion" class="mw-ui-box mw-ui-box-silver-blue  m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#payments-accordion');">
            <div class="header-holder">
                <i class="mai-order"></i><?php _e("Payment methods"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display:none;">
            <module type="shop/payments" view="admin"/>
        </div>
    </div>

    <module type="shop/payments/currency" id="mw_curr_select"/>


    <div id="shipping-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#shipping-accordion');">
            <div class="header-holder">
                <i class="mai-shipping"></i> <?php _e("Shipping"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/shipping" view="admin"/>
        </div>
    </div>

    <div id="taxes-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#taxes-accordion');">
            <div class="header-holder">
                <i class="mai-percent"></i><?php _e("Taxes"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/taxes" view="admin"/>
        </div>
    </div>



    <?php event_trigger('mw.admin.shop.settings', $params); ?>



<?php
/*

    <div id="offers-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#offers-accordion');">
            <div class="header-holder">
                <i class="mai-percent"></i><?php _e("Offers"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/offers" view="admin"/>
        </div>
    </div>



<div id="coupons-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#coupons-accordion');">
            <div class="header-holder">
                <i class="mai-percent"></i><?php _e("Coupons"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/coupons" view="admin"/>
        </div>
    </div>

*/

?>



    <div id="emails-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#emails-accordion');">
            <div class="header-holder">
                <i class="mai-mail"></i> <?php _e("Send email to the client on new order"); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/orders/settings/setup_emails_on_order"/>
        </div>
    </div>

    <div id="others-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#others-accordion');">
            <div class="header-holder">
                <i class="mai-options"></i> <?php _e('Other shop settings'); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/orders/settings/other" id="mw_shop_set_other_settings"/>
        </div>
    </div>

    <div id="invoices-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#invoices-accordion');">
            <div class="header-holder">
                <i class="mai-order"></i> <?php print _e('Invoice settings'); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
            <module type="shop/orders/settings/invoice_settings"/>
        </div>
    </div>
</div>








