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
    $item['shipping_cost_max'] = '';
}

if (!isset($item['shipping_cost_above'])) {
    $item['shipping_cost_above'] = '';
}

if (!isset($item['position'])) {
    $item['position'] = '999';
}

if (!isset($item['shipping_type'])) {
    $item['shipping_type'] = 'fixed';
}

if (!isset($item['shipping_price_per_size'])) {
    $item['shipping_price_per_size'] = '';
}

if (!isset($item['shipping_price_per_weight'])) {
    $item['shipping_price_per_weight'] = '';
}

if (!isset($item['shipping_type'])) {
    $item['shipping_type'] = 'fixed';
}

if (!isset($item['shipping_price_per_size'])) {
    $item['shipping_price_per_size'] = '';
}

if (!isset($item['shipping_price_per_weight'])) {
    $item['shipping_price_per_weight'] = '';
}

if (!isset($item['shipping_price_per_item'])) {
    $item['shipping_price_per_item'] = '';
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
        console.log($('.js-shipping-type-select').val());
        var parent = $('#<?php print $params['id'] ?>');
        if ($('.js-shipping-type-select').val() == 'dimensions') {
            mw.$(".shipping_dimensions", parent).show()
            mw.$(".shipping_per_item", parent).hide()
        } else if ($('.js-shipping-type-select').val() == 'per_item') {
            mw.$(".shipping_dimensions", parent).hide()
            mw.$(".shipping_per_item", parent).show()
        } else {
            mw.$(".shipping_dimensions", parent).hide()
            mw.$(".shipping_per_item", parent).hide()
        }


        mw.$(".js-shipping-type-select").on('change', function () {
            var parent = $('#<?php print $params['id'] ?>');
            if ($(this).val() == 'dimensions') {
                mw.$(".shipping_dimensions", parent).slideDown()
                mw.$(".shipping_per_item", parent).hide()
            } else if ($(this).val() == 'per_item') {
                mw.$(".shipping_dimensions", parent).hide()
                mw.$(".shipping_per_item", parent).slideDown()
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

        SaveShippingForm();
    }

    SaveShippingData = function (data) {
        if (typeof(data.id) == 'undefined') {
            data.id = $('.js-shipping-edit-item-id-value').val();
        }

        return $.post('<?php print $config['module_api']; ?>/shipping_firstclass/save', data)
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
        mw.form.post($(form), '<?php print $config['module_api']; ?>/shipping_firstclass/save', function () {

                var the_saved_id = parseInt(this);
                SaveShippingApplyIdAfterTheSave(the_saved_id);


                if (dataType == 'new') {
                    if (typeof(mw_admin_edit_country_item_popup_modal_opened) != "undefined") {
                        mw_admin_edit_country_item_popup_modal_opened.remove();
                    }

                }
                else {
                    /*      mw.reload_module(dataType, function () {
                     mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");
                     });*/
                }
                mw.reload_module_everywhere('shop/shipping/gateways/firstclass/admin_backend');
                mw.reload_module_everywhere('shop/shipping/gateways/firstclass/admin');


                if (window.parent != undefined && window.parent.mw != undefined) {
                    // window.parent.mw.reload_module('shop/shipping/gateways/firstclass');
                    mw.reload_module_everywhere('shop/shipping/gateways/firstclass');

                }
            mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");


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

    var SaveShippingFormTime = null;

    SaveShippingForm = function () {
        clearTimeout(SaveShippingFormTime);
        SaveShippingFormTime = setTimeout(function (){
            var formid = 'js-shipping-edit-item-form-<?php print $item['id']; ?>'
            var form_to_submit = document.getElementById(formid)
            <?php if ($new == true): ?>
            SaveShipping(form_to_submit, 'new');
            <?php else : ?>
            SaveShipping(form_to_submit, '<?php print $params['data-type'] ?>');
            <?php endif; ?>
        }, 400);

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

<script>mw.lib.require('mwui_init');</script>

<div data-field-id="<?php print $item['id']; ?>" onmousedown="mw.tools.focus_on(this);"
     class="shipping-country-holder country-id-<?php print $item['id']; ?>">
    <form onsubmit="SaveShipping(this, '<?php if ($new == false) {
        print $params['data-type'];
    } else {
        print 'new';
    } ?>');return false;" action="<?php print $config['module_api']; ?>/shipping_add_to_country" data-field-id="<?php print $item['id']; ?>" id="js-shipping-edit-item-form-<?php print $item['id']; ?>">
        <div class="<?php if ($item['is_active'] == 1 AND $new == false): ?>box-enabled-<?php elseif ($item['is_active'] == 0): ?>box-disabled-<?php endif; ?> <?php if ($new == false): ?>toggle-item closed-fields<?php endif; ?>">

            <input type="hidden" name="id" value="<?php print $item['id']; ?>" class="js-shipping-edit-item-id-value"/>

            <div class="form-group">
                <label class="control-label">
                    <?php if ($new == true): ?>
                        <?php _e("Add new"); ?>
                    <?php else : ?>
                        <?php _e("Shipping to country"); ?>
                    <?php endif; ?>
                </label>

                <small class="text-muted d-block mb-2">Please, select a country to add</small>

                <?php if ($new == false): ?>
                    <input type="hidden" name="id" value="<?php print $item['id']; ?>">
                <?php endif; ?>

                <select name="shipping_country" class="selectpicker" data-width="100%" data-size="5" onchange="SaveShippingForm();" <?php if (isset($item['id']) AND $item['id'] != 0): ?>disabled<?php endif; ?>>
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

            <div class="form-group">
                <label class="control-label"><?php _e("Allow shipping to this country"); ?></label>
                <div class="custom-control custom-switch pl-0">
                    <label class="d-inline-block mr-5" for="is_active"><?php _e("No"); ?></label>
                    <input onchange="ToggleShipping(event)" type="checkbox" name="is_active" id="is_active" class="custom-control-input" data-id="<?php print $item['id']; ?>" data-value-checked="1" data-value-unchecked="0" <?php if (isset($item['is_active']) and '1' == trim($item['is_active'])): ?>checked<?php endif; ?>>
                    <label class="custom-control-label" for="is_active"><?php _e("Yes"); ?></label>
                </div>
            </div>

            <div class="js-shipping-item-edit-needs-id">
                <div class="row">
                    <div class="col-7">
                        <div class="form-group">
                            <label class="control-label"><?php _e("Choose shipping type and set price "); ?></label>

                            <div class="form-group">
                                <div class="custom-control custom-radio mb-4">
                                    <input type="radio" id="shipping_type1" name="shipping_type" class="custom-control-input js-shipping-type-select" value="fixed" <?php if (isset($item['shipping_type']) and 'fixed' == trim($item['shipping_type'])): ?> checked <?php endif; ?> onchange="SaveShippingForm();"/>
                                    <label class="custom-control-label" for="shipping_type1"><?php _e("Fixed"); ?>
                                        <small class="text-muted d-block"><?php _e('Set a shipping price for the whole order'); ?></small>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio mb-4">
                                    <input type="radio" id="shipping_type2" name="shipping_type" class="custom-control-input js-shipping-type-select" value="dimensions" <?php if (isset($item['shipping_type']) and 'dimensions' == trim($item['shipping_type'])): ?> checked <?php endif; ?> onchange="SaveShippingForm();"/>
                                    <label class="custom-control-label" for="shipping_type2"><?php _e("Dimensions or Weight"); ?>
                                        <small class="text-muted d-block"><?php _e('Set a flexible shipping price depending on a product’s dimensions or weight'); ?></small>
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="shipping_type3" name="shipping_type" class="custom-control-input js-shipping-type-select" value="per_item" <?php if (isset($item['shipping_type']) and 'per_item' == trim($item['shipping_type'])): ?> checked <?php endif; ?> onchange="SaveShippingForm();"/>
                                    <label class="custom-control-label" for="shipping_type3"><?php _e("Per item"); ?>
                                        <small class="text-muted d-block"><?php _e('Charge a set shipping price for each product a customer orders'); ?></small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-5">
                        <div class="form-group">
                            <small class="control-label mb-2 d-block"><?php _e("Shipping cost"); ?></small>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                                </div>
                                <input class="form-control" type="number" oninput="SaveShippingForm();"   placeholder="0" name="shipping_cost" value="<?php print $item['shipping_cost']; ?>"/>
                            </div>
                        </div>

                        <div class="shipping_dimensions" style="display: none">
                            <div class="form-group">
                                <small class="control-label mb-2 d-block"><?php _e("Additional cost for"); ?> 1 <?php _e("cubic"); ?> <?php print $size_units ?></small>

                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                                    </div>
                                    <input type="text" name="shipping_price_per_size" value="<?php print  floatval($item['shipping_price_per_size']); ?>" onkeyup="mw.form.typeNumber(this);" onchange="SaveShippingForm();" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <small class="control-label mb-2 d-block"><?php _e("Additional cost for"); ?> 1 <?php print $weight_units ?></small>

                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                                    </div>
                                    <input type="text" name="shipping_price_per_weight" value="<?php print floatval($item['shipping_price_per_weight']); ?>" onkeyup="mw.form.typeNumber(this);" onchange="SaveShippingForm();" class="form-control"/>
                                </div>
                            </div>
                        </div>

                        <div class="shipping_per_item" style="display: none">
                            <div class="form-group">
                                <small class="control-label mb-2 d-block"><?php _e("Cost for shipping each item"); ?></small>

                                <div class="input-group input-group-sm mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                                    </div>
                                    <input type="text" name="shipping_price_per_item" value="<?php print  floatval($item['shipping_price_per_item']); ?>" onkeyup="mw.form.typeNumber(this);" onchange="SaveShippingForm();" class="form-control"/>
                                </div>
                                <small class="text-muted d-block"><?php _e("This cost will be added to the shipping price for the whole order from the box above"); ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin"/>

                <div class="js-shipping-item-edit-needs-id">






                    <div class="form-group">
                        <label class="control-label"><?php _e("Order amount to activate special shipping price"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Minimum amount in the shopping cart to activate special shipping price"); ?></small>


                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                            </div>
                            <input class="form-control" type="text" oninput="mw.form.typeNumber(this);SaveShippingForm();"  name="shipping_cost_above" value="<?php print $item['shipping_cost_above']; ?>"   placeholder="">
                        </div>


                         <small class="text-muted"><?php print  _e("Enter the amount to trigger the new shipping price") ; ?></small>
<br>
                        <small class="text-muted"> <?php print  _e("Example") . ' ' . currency_format(100); ?></small></small>


                    </div>


                    <div class="form-group">
                        <label class="control-label"><?php _e("Shipping cost"); ?></label>

                        <small class="text-muted d-block mb-2"><?php _e("Enter the cost if the special shipping price is activated"); ?></small>


                        <div class="input-group input-group-sm mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><?php print mw()->shop_manager->currency_symbol() ?></span>
                            </div>
                            <input class="form-control shipping-price-field" type="text" oninput="mw.form.typeNumber(this);SaveShippingForm();"   name="shipping_cost_max" value="<?php print $item['shipping_cost_max']; ?>"  placeholder=""/>
                        </div>

                        <small class="text-muted"><?php print  _e("example") . ' ' . currency_format(5); ?></small>
                    </div>


                </div>
            </div>
        </div>
    </form>
</div>
