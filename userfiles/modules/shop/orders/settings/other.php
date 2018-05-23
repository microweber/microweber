<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("Saved"); ?>.");
        });


    });


</script>


<div class="section-header">
    <h2 class="pull-left"><span class="mai-options"></span> <?php _e('Other shop settings'); ?></h2>
</div>

<div class="admin-side-content">
    <div id="shop-settings-accordion" class="mw-ui-box mw-ui-box-silver-blue active m-t-20">
        <div class="mw-ui-box-header" onclick="mw.accordion('#shop-settings-accordion');">
            <div class="header-holder">
                <i class="mai-shop"></i> <?php print _e('Shop settings'); ?>
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content">
            <div class="mw-ui-row">
                <div class="mw-ui-col">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10"><?php _e("Users must agree to Terms and Conditions"); ?></label>

                        <div class="mw-ui-check-selector">
                            <label class="mw-ui-check" style="margin-right: 15px;">
                                <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_terms', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                                <span></span><span><?php _e("No"); ?></span>
                            </label>
                            <label class="mw-ui-check">
                                <input name="shop_require_terms" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_terms', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                                <span></span><span><?php _e("Yes"); ?></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10"><?php _e("Purchasing requires registration"); ?></label>

                        <div class="mw-ui-check-selector">
                            <label class="mw-ui-check" style="margin-right: 15px;">
                                <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="0" type="radio" <?php if (get_option('shop_require_registration', 'website') != 1): ?> checked="checked" <?php endif; ?> >
                                <span></span><span><?php _e("No"); ?></span>
                            </label>
                            <label class="mw-ui-check">
                                <input name="shop_require_registration" class="mw_option_field" data-option-group="website" value="1" type="radio" <?php if (get_option('shop_require_registration', 'website') == 1): ?> checked="checked" <?php endif; ?> >
                                <span></span><span><?php _e("Yes"); ?></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10"><?php _e("Disable online shop"); ?></label>

                        <div class="mw-ui-check-selector">
                            <label class="mw-ui-check" style="margin-right: 15px;">
                                <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="n" type="radio" <?php if (get_option('shop_disabled', 'website') != "y"): ?> checked="checked" <?php endif; ?> >
                                <span></span><span><?php _e("No"); ?></span>
                            </label>
                            <label class="mw-ui-check">
                                <input name="shop_disabled" class="mw_option_field" data-option-group="website" value="y" type="radio" <?php if (get_option('shop_disabled', 'website') == "y"): ?> checked="checked" <?php endif; ?> >
                                <span></span> <span><?php _e("Yes"); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('.js-show-others').on('click', function () {
                        $('.js-others').toggleClass('hidden');
                    });
                });
            </script>
            <button type="button" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline js-show-others"><?php print _e('Advanced'); ?></button>
            <div class="mw-ui-row js-others hidden">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Checkout URL"); ?></label>
                    <?php $checkout_url = get_option('checkout_url', 'shop'); ?>
                    <input name="checkout_url" class="mw-ui-field mw_option_field block-field" type="text" option-group="shop" value="<?php print get_option('checkout_url', 'shop'); ?>" placeholder="<?php _e('Use default'); ?>"/>
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10"><?php _e("Custom order id"); ?></label>
                    <input name="custom_order_id" class="mw-ui-field mw_option_field block-field" type="text" option-group="shop" value="<?php print get_option('custom_order_id', 'shop'); ?>" placeholder="ORD-{id}"/>
                </div>
            </div>
        </div>
    </div>

    <module type="shop/shipping/set_units" id="mw_set_shipping_units"/>

    <div id="invoice-settings-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20 js-currency-setting">
        <div class="mw-ui-box-header" onclick="mw.accordion('#invoice-settings-accordion');">
            <div class="header-holder">
                <i class="mai-order"></i> Invoice settings
            </div>
        </div>

        <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
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
                <label class="mw-ui-label bold p-b-10">Company Logo: </label>
                <input type="file" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_logo" placeholder="" value="">
            </div>

            <div class="mw-ui-row">
                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">Company Name: </label>
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
                    <label class="mw-ui-label bold p-b-10">Company City: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_city" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">Company Address: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_address" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">Company VAT Number: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_vat_number" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">ID Company Number: </label>
                    <input type="text" class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_id_company_number" placeholder="" value="">
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">Additional information: </label>
                    <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="For example: reason for taxes"></textarea>
                </div>

                <div class="m-b-10">
                    <label class="mw-ui-label bold p-b-10">Bank transfer details: </label>
                    <textarea class="mw-ui-field mw_option_field block-field" data-option-group="shop" name="invoice_company_bank_details" placeholder="Enter your bank details here"></textarea>
                </div>
            </div>
        </div>
    </div>

</div>
