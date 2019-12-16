<?php

$new = false;

if (!isset($item['id'])) {
    //  if ($data_key == 'data_active') {
    $item['id'] = 0;
    $item['is_active'] = 1;
    $item['shipping_country'] = 'new';
    $new = true;
    // }
}

if (!isset($item['shipping_cost'])) {
    $item['shipping_cost'] = '0';
}
if (!isset($item['shipping_cost_max'])) {
    $item['shipping_cost_max'] = '0';
}

if (!isset($item['shipping_cost_above'])) {
    $item['shipping_cost_above'] = '0';
}
if (!isset($item['position'])) {
    $item['position'] = '999';
}


if (!isset($item['shipping_type'])) {
    $item['shipping_type'] = 'fixed';
}
if (!isset($item['shipping_price_per_size'])) {
    $item['shipping_price_per_size'] = 0;
}

if (!isset($item['shipping_price_per_weight'])) {
    $item['shipping_price_per_weight'] = 0;
}


if (!isset($item['shipping_type'])) {
    $item['shipping_type'] = 'fixed';
}

if (!isset($item['shipping_price_per_size'])) {
    $item['shipping_price_per_size'] = 0;
}
if (!isset($item['shipping_price_per_weight'])) {
    $item['shipping_price_per_weight'] = 0;
}
if (!isset($item['shipping_price_per_item'])) {
    $item['shipping_price_per_item'] = 0;
}

$size_units = get_option('shipping_size_units', 'orders');
$weight_units = get_option('shipping_weight_units', 'orders');
if ($size_units == false) {
    $size_units = 'cm';
}
if ($weight_units == false) {
    $weight_units = 'kg';
}


?>


<script>


    $(document).ready(function () {

        mw.$(".shipping_type_dropdown").each(function () {
            var parent = $('#<?php print $params['id'] ?>');

            // var parent = mw.tools.firstParentWithTag(this, 'td');
            //parent = $(parent).next('td');
            if ($(this).val() == 'dimensions') {
                mw.$(".shipping_dimensions", parent).show()
                mw.$(".shipping_per_item", parent).hide()
            } else if ($(this).val() == 'per_item') {
                mw.$(".shipping_dimensions", parent).hide()
                mw.$(".shipping_per_item", parent).show()

            } else {
                mw.$(".shipping_dimensions", parent).hide()
                mw.$(".shipping_per_item", parent).hide()

            }
        });

        mw.$(".shipping_type_dropdown").on('change', function () {
            var parent = $('#<?php print $params['id'] ?>');
            //  var parent = mw.tools.firstParentWithTag(this, 'td');
            //  parent = $(parent).next('td');
            if ($(this).val() == 'dimensions') {
                mw.$(".shipping_dimensions", parent).slideDown()
                mw.$(".shipping_per_item", parent).hide()

            } else if ($(this).val() == 'per_item') {
                mw.$(".shipping_dimensions", parent).hide()
                mw.$(".shipping_per_item", parent).show()
            } else {
                mw.$(".shipping_dimensions", parent).slideUp()
                mw.$(".shipping_per_item", parent).hide()

            }
        });


    });


</script>


<script type="text/javascript">

    ToggleShipping = function (e) {

        var el = e.target;
        //var id = el.getAttribute('data-id').
        var id = $('.js-shipping-edit-item-id-value').val();


        if (id == 0) {
            return;
        }

        SaveShippingForm()
        /*  var eroot = $(el).parents('form')[0];
         mw.tools.loading(eroot, true)
         var data = {
         id: id,
         is_active: el.checked ? 1 : 0
         }


         SaveShippingData(data).always(function () {
         mw.reload_module('shop/shipping', function () {
         mw.tools.loading(eroot, false);
         });

         });*/
    }


    SaveShippingData = function (data) {

        if (typeof(data.id) == 'undefined') {
            data.id = $('.js-shipping-edit-item-id-value').val();
        }


        return $.post('<?php print $config['module_api']; ?>/shipping_to_country/save', data)
            .done(function (msg) {
                var the_saved_id = parseInt(msg);
                SaveShippingApplyIdAfterTheSave(the_saved_id);
                mw.notification.success('<?php _ejs("Saved"); ?>')
            })
    }
    SaveShipping = function (form, dataType) {
        var country = mw.$('[name="shipping_country"]', form).val();
        if (country == 'none') {
            mw.notification.warning('<?php _ejs("Please choose country"); ?>')
            return false;
        }
        mw.form.post($(form), '<?php print $config['module_api']; ?>/shipping_to_country/save', function () {

                var the_saved_id = parseInt(this);
                SaveShippingApplyIdAfterTheSave(the_saved_id);


                if (dataType == 'new') {
                    if (typeof(mw_admin_edit_country_item_popup_modal_opened) != "undefined") {
                        mw_admin_edit_country_item_popup_modal_opened.remove();
                    }
                    mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");

                }
                else {
                    /*      mw.reload_module(dataType, function () {
                     mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");
                     });*/
                }
            mw.reload_module_everywhere('shop/shipping/gateways/country/admin_backend');


            if (window.parent != undefined && window.parent.mw != undefined) {
                   // window.parent.mw.reload_module('shop/shipping/gateways/country');
                    mw.reload_module_everywhere('shop/shipping/gateways/country');

                }


                mw.reload_module('shop/shipping', function () {
                    var m = mw.$('#shipping-table-list-item-id-' + the_saved_id);

                    if (m.length) {
                        var mprev = m.prev();
                        if (mprev.length) {
                            mprev[0].scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});
                        }
                        mw.tools.highlight(m[0]);
                    }
                });


            }
        );
    }

    SaveShippingApplyIdAfterTheSave = function (saved_id) {
        $('.js-shipping-edit-item-id-value').val(saved_id);
        $('.js-shipping-item-edit-needs-id').show();
    }

    SaveShippingForm = function () {
        var formid = 'js-shipping-edit-item-form-<?php print $item['id']; ?>'
        var form_to_submit = document.getElementById(formid)
        <?php if ($new == true): ?>
        SaveShipping(form_to_submit, 'new');
        <?php else : ?>
        SaveShipping(form_to_submit, '<?php print $params['data-type'] ?>');
        <?php endif; ?>
    }
</script>

<script>
    <?php if ($new == true): ?>
    $(document).ready(function () {
        $('.js-shipping-item-edit-needs-id').hide();
    });

    <?php else : ?>
    <?php endif; ?>
</script>


<div data-field-id="<?php print $item['id']; ?>" onmousedown="mw.tools.focus_on(this);"
     class="shipping-country-holder country-id-<?php print $item['id']; ?>">
    <form onsubmit="SaveShipping(this, '<?php if ($new == false) {
        print $params['data-type'];
    } else {
        print 'new';
    } ?>');return false;" action="<?php print $config['module_api']; ?>/shipping_add_to_country" data-field-id="<?php print $item['id']; ?>" id="js-shipping-edit-item-form-<?php print $item['id']; ?>">
        <div class="<?php if ($item['is_active'] == 1 AND $new == false): ?>box-enabled-<?php elseif ($item['is_active'] == 0): ?>box-disabled-<?php endif; ?> <?php if ($new == false): ?>toggle-item closed-fields<?php endif; ?>">

            <input type="hidden" name="id" value="<?php print $item['id']; ?>" class="js-shipping-edit-item-id-value"/>

            <div class="mw-field-holder">
                <label class="mw-ui-label">
                    <?php if ($new == true): ?>
                        <?php _e("Add new"); ?>
                    <?php else : ?>
                        <?php _e("Shipping to country"); ?>
                    <?php endif; ?>

                    <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("Select a country to deliver your products to"); ?>." data-tipposition="right-center"></span>
                </label>

                <?php if ($new == false): ?>
                    <input type="hidden" name="id" value="<?php print $item['id']; ?>">
                <?php endif; ?>
                <select name="shipping_country" class="mw-ui-field silver-field" onchange="SaveShippingForm();" <?php if (isset($item['id']) AND $item['id'] != 0): ?>disabled<?php endif; ?>>
                    <?php if ($new == true): ?>
                        <option value="none"><?php _e("Choose country"); ?></option>
                    <?php endif; ?>
                    <?php if (is_array($countries)) : ?>
                        <?php foreach ($countries as $item1): ?>
                            <?php
                            $disabled = '';
                            foreach ($countries_used as $item2):
                                if ($item2 == $item1) {
                                    $disabled = 'disabled="disabled"';
                                }
                            endforeach;
                            ?>
                            <option value="<?php print $item1 ?>" <?php if (isset($item['shipping_country']) and $item1 == $item['shipping_country']): ?> selected="selected" <?php else : ?><?php print $disabled ?><?php endif; ?> ><?php print $item1 ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mw-ui-row m-t-10">
                <div class="mw-ui-col">
                    <span class="switcher-label-left" style="padding-left: 0;"><?php _e("Allow shipping to this country"); ?></span>
                </div>
                <div class="mw-ui-col right">
                    <label class="mw-switch mw-switch-action inline-switch">
                        <input onchange="ToggleShipping(event)" type="checkbox" name="is_active" data-id="<?php print $item['id']; ?>" data-value-checked="1" data-value-unchecked="0" <?php if (isset($item['is_active']) and '1' == trim($item['is_active'])): ?> checked="1" <?php endif; ?>>
                        <span class="mw-switch-off"><?php _e("No"); ?></span>
                        <span class="mw-switch-on"><?php _e("Yes"); ?></span>
                        <span class="mw-switcher"></span>
                    </label>
                </div>
            </div>


            <div class="shipping-country-row js-shipping-item-edit-needs-id">
                <div class=" shipping-country-setting">
                    <div class=" mw-ui-row m-t-10">
                        <div class=" mw-ui-col">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label">
                                    <?php _e("Shipping type"); ?>
                                    <span class="mw-icon-help-outline mwahi tip" data-tip="#shipping-type-tooltip" data-tipposition="right-center"></span>
                                </label>

                                <select name="shipping_type" class="mw-ui-field shipping_type_dropdown" onchange="SaveShippingForm();">
                                    <option value="fixed" <?php if (isset($item['shipping_type']) and 'fixed' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?> >
                                        <?php _e("Fixed"); ?>
                                    </option>
                                    <option value="dimensions" <?php if (isset($item['shipping_type']) and 'dimensions' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?>>
                                        <?php _e("Dimensions or Weight"); ?>
                                    </option>
                                    <option value="per_item" <?php if (isset($item['shipping_type']) and 'per_item' == trim($item['shipping_type'])): ?>   selected="selected" <?php endif; ?>>
                                        <?php _e("Per item"); ?>
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class=" mw-ui-col">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label"><?php _e("Shipping cost"); ?></label>

                                <div class="shipping-cost">
                                    <div class="mw-ui-btn-nav input-with-currency">
                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                        <input class="mw-ui-field shipping-price-field price-field" type="text" onkeyup="mw.form.typeNumber(this);" onchange="SaveShippingForm()" placeholder="0" name="shipping_cost" value="<?php print $item['shipping_cost']; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shipping_dimensions m-t-10" style="display: none">
                        <div class="mw-ui-row">
                            <div class="mw-ui-col">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label">
                                        <?php _e("Additional cost for"); ?>
                                        <em>1 <?php _e("cubic"); ?> <?php print $size_units ?></em>
                                    </label>
                                    <div class="mw-ui-btn-nav input-with-currency">
                                        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                        <input type="text" name="shipping_price_per_size" value="<?php print  floatval($item['shipping_price_per_size']); ?>" onchange="SaveShippingForm();" class="mw-ui-field price-field"/>
                                    </div>
                                </div>
                            </div>

                            <div class="mw-ui-col">
                                <div class="mw-ui-field-holder">
                                    <label class="mw-ui-label">
                                        <?php _e("Additional cost for"); ?>
                                        <em>1 <?php print $weight_units ?></em>
                                    </label>
                                    <span class="mwsico-usd"></span>
                                    <div class="input-with-currency">
                                        <div class="input-with-currency">
                                            <div class="mw-ui-btn-nav input-with-currency">
                                                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                                <input type="text" name="shipping_price_per_weight" value="<?php print floatval($item['shipping_price_per_weight']); ?>" onchange="SaveShippingForm();" class="mw-ui-field price-field"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shipping_per_item" style="display: none">
                        <div class="mw-ui-field-holder">
                            <label class="mw-ui-label">
                                <?php _e("Cost for shipping"); ?>
                                <em><?php _e("each item in the shopping cart"); ?></em>
                                <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("This cost will be added to the shipping price for the whole order from the box above"); ?>"></span>
                            </label>

                            <div class="input-with-currency">
                                <div class="mw-ui-btn-nav input-with-currency">
                                    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                    <input type="text" name="shipping_price_per_item" value="<?php print  floatval($item['shipping_price_per_item']); ?>" onchange="SaveShippingForm();" class="mw-ui-field price-field"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="mw-ui-field-holder shipping-discount-row js-shipping-item-edit-needs-id">
                    <div class="shipping-country-setting">
                        <div class="mw-ui-row">
                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <div class="same-as-country-selector">
                                        <label class="mw-ui-label"><?php _e("Shipping cost for Price above"); ?></label>
                                        <div class="input-with-currency">
                                            <div class="mw-ui-btn-nav input-with-currency">
                                                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                                <input class="mw-ui-field shipping-price-field price-field" type="text" onkeyup="mw.form.typeNumber(this);" onblur="mw.form.fixPrice(this);" name="shipping_cost_above" value="<?php print $item['shipping_cost_above']; ?>" onchange="SaveShippingForm();"
                                                       placeholder="0">
                                            </div>
                                        </div>
                                        <span class="mw-ui-label-help"><?php print  _e("example") . ' ' . currency_format(100); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mw-ui-col shipping-cost">
                                <div class="mw-ui-col-container">
                                    <label class="mw-ui-label"><?php _e("Shipping cost"); ?></label>
                                    <div class="input-with-currency">
                                        <div class="mw-ui-btn-nav input-with-currency">
                                            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><?php print mw()->shop_manager->currency_symbol() ?></a>
                                            <input class="mw-ui-field price-field shipping-price-field" type="text" onkeyup="mw.form.typeNumber(this);" onblur="mw.form.fixPrice(this);" name="shipping_cost_max" value="<?php print $item['shipping_cost_max']; ?>" onchange="SaveShippingForm();"
                                                   placeholder="0"/>
                                        </div>
                                    </div>
                                    <span class="mw-ui-label-help"><?php _e("Price per order"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>