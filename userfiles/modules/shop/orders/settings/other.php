<div class="section-header">
    <h2 class="pull-left"><span class="mai-options"></span> <?php _e('Other shop settings'); ?></h2>
</div>

<div class="admin-side-content">
    <h4>
        <?php _e("Users must agree to Terms and Conditions"); ?>
    </h4>
    <label class="mw-ui-check" style="margin-right: 15px;">
        <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
    <?php _e("No"); ?>
    </span></label>
    <label class="mw-ui-check">
        <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
    <hr>
    <h4>
        <?php _e("Purchasing requires registration"); ?>
    </h4>
    <label class="mw-ui-check" style="margin-right: 15px;">
        <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
    <?php _e("No"); ?>
    </span></label>
    <label class="mw-ui-check">
        <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
    <?php _e("Yes"); ?>
    </span></label>
    <hr>
    <h4>
        <?php _e("Disable online shop"); ?>
    </h4>
    <label class="mw-ui-check" style="margin-right: 15px;">
        <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="n" type="radio" <?php if (get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
    <?php _e("No"); ?>
    </span> </label>
    <label class="mw-ui-check">
        <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="y" type="radio" <?php if (get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
        <span></span> <span>
    <?php _e("Yes"); ?>
    </span> </label>
    <br/>
    <hr>
    <a class="mw-ui-btn mw-ui-btn-small"
       href="javascript:$('.mw_adm_shop_advanced_settings').toggle();void(0);"><?php _e('Advanced'); ?><span
                class="mw-ui-arr mw-ui-arr-down" style="opacity:0.3"></span> </a>
    <div class="mw_adm_shop_advanced_settings mw-ui-box mw-ui-box-content" style="display:none;margin-top: 12px;">
        <h2>
            <?php _e("Checkout URL"); ?>
        </h2>
        <?php ?>
        <?php $checkout_url = get_option('checkout_url', 'shop'); ?>
        <input name="checkout_url" class="mw_option_field mw-ui-field" type="text" option-group="shop" value="<?php print get_option('checkout_url', 'shop'); ?>" placeholder="<?php _e('Use default'); ?>"/>
        <h2>
            <?php _e("Custom order id"); ?>
        </h2>
        <?php ?>
        <input name="custom_order_id" class="mw_option_field mw-ui-field" type="text" option-group="shop" value="<?php print get_option('custom_order_id', 'shop'); ?>" placeholder="ORD-{id}"/>
    </div>


    <hr>
    <module type="shop/shipping/set_units" id="mw_set_shipping_units"/>

    <hr/>


    <div id="invoice-settings-accordion" class="mw-ui-box mw-ui-box-silver-blue active m-t-20 js-currency-setting">
        <div class="mw-ui-box-header" onclick="mw.accordion('#invoice-settings-accordion');">
            <div class="header-holder">
                <i class="mai-order"></i> Invoice settings
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content">


            <div class="mw-ui-row m-b-10">
                <div class="">
                    <p class="bold">Enable invoicing</p>
                </div>

                <div class="">
                    <label class="mw-switch inline-switch m-0 m-t-10 m-b-10">
                        <input type="checkbox" name="enable_invoices" data-option-group="shop" data-value-checked="1" data-value-unchecked="0" class="mw_option_field" <?php if (true): ?> checked="checked" <?php endif; ?>>
                        <span class="mw-switch-off">OFF</span>
                        <span class="mw-switch-on">ON</span>
                        <span class="mw-switcher"></span>
                    </label>
                </div>
            </div>

            <div class="m-b-10">
                <label class="mw-ui-label">Company Logo: </label>
                <input type="file" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_logo" placeholder="" value="">
            </div>

            <div class="mw-ui-row">
                <div class="m-b-10">
                    <label class="mw-ui-label">Company Name: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_name" placeholder="" value="">
                </div>
            </div>

            <div class="mw-ui-row m-b-10">
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <label class="mw-ui-label bold p-b-10">Company Country:</label>

                        <select name="invoice_company_country" class="mw-ui-field mw_option_field w100 silver-field" data-option-group="shop">
                            <?php if (countries_list()): ?>
                                <?php foreach (countries_list() as $country): ?>
                                    <option value="<?php print $country; ?>" selected="selected"><?php print $country; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mw-ui-row">
                <div class="m-b-10">
                    <label class="mw-ui-label">Company City: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_city" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label">Company Address: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_address" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label">Company VAT Number: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label">ID Company Number: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label">Additional information: </label>
                    <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="For example: reason for taxes"></textarea>
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label">Bank transfer details: </label>
                    <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="Enter your bank details here"></textarea>
                </div>
            </div>
        </div>
    </div>

</div>
