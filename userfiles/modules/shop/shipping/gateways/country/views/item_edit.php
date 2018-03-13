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

<div data-field-id="<?php print $item['id']; ?>" onmousedown="mw.tools.focus_on(this);"
     class="shipping-country-holder country-id-<?php print $item['id']; ?>">
    <form onsubmit="SaveShipping(this, '<?php if ($new == false) {
        print $params['data-type'];
    } else {
        print 'new';
    } ?>');return false;" action="<?php print $config['module_api']; ?>/shipping_add_to_country" data-field-id="<?php print $item['id']; ?>">
        <div class="mw-ui-box mw-ui-settings-box box-white mw-ui-box-content <?php if ($new == false): ?>toggle-item closed-fields<?php endif; ?>">
            <table class="mw-ui-table mw-ui-table-basic admin-shipping-table">
                <tr class="shipping-country-row">
                    <td class="shipping-country-label">
                        <?php if ($new == true): ?>
                            <?php _e("Add new"); ?>
                        <?php else : ?>
                            <?php _e("Shipping to country"); ?>
                        <?php endif; ?>
                        <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("Select a country to deliver your products to"); ?>." data-tipposition="right-center"></span>

                    </td>
                    <td class="shipping-country-setting"><?php if ($new == false): ?>
                            <input type="hidden" name="id" value="<?php print $item['id']; ?>">
                        <?php endif; ?>
                        <select name="shipping_country" class="mw-ui-field">
                            <?php if ($new == true): ?>
                                <option value="none">
                                    <?php _e("Choose country"); ?>
                                </option>
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
                    </td>
                    <td>
                        <?php _e("Allow shipping to this country"); ?>

                        <label class="mw-switch mw-switch-action pull-left inline-switch">
                            <input
                                    type="checkbox"
                                    name="is_active"
                                    class="mw_option_field"
                                    data-value-checked="1"
                                    data-value-unchecked="0"
                                <?php if (isset($item['is_active']) and '1' == trim($item['is_active'])): ?> checked="1" <?php endif; ?>>
                            <span class="mw-switch-off">NO</span>
                            <span class="mw-switch-on">YES</span>
                            <span class="mw-switcher"></span>
                        </label>

                        <ul class="mw-ui-inline-list">
                            <li> <span>
                                                    <span class="mw-icon-help-outline mwahi tip" data-tip="#shippingtip" data-tipposition="right-center"></span> </span></li>
                            <li>
                                <label class="mw-ui-check">
                                    <input
                                            name="is_active" type="radio" class="semi_hidden is_active_n"
                                            value="0" <?php if (!isset($item['is_active']) or 1 != ($item['is_active'])): ?>   checked="checked"  <?php endif; ?> />
                                    <span></span> <span>
                    <?php _e("No"); ?>
                    </span> </label>
                            </li>
                            <li>
                                <label class="mw-ui-check">
                                    <input name="is_active" type="radio" class="semi_hidden is_active_y"
                                           value="1" <?php if (isset($item['is_active']) and '1' == trim($item['is_active'])): ?>   checked="checked"  <?php endif; ?> />
                                    <span></span> <span>
                    <?php _e("Yes"); ?>
                    </span> </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr class="shipping-country-row hide-item hidden">
                    <td class="shipping-country-label"><?php _e("Shipping type"); ?>
                        <span class="mw-icon-help-outline mwahi tip" data-tip="#shipping-type-tooltip" data-tipposition="right-center"></span></td>
                    <td class="shipping-country-setting">
                        <div class="mw-ui-row" style="width: auto">
                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <span class="mw-ui-label"><?php _e("Price per order"); ?></span>
                                    <select name="shipping_type" class="mw-ui-field shipping_type_dropdown">
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
                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <label class="mw-ui-label">
                                        <?php _e("Shipping cost"); ?>
                                    </label>
                                    <div class="input-with-currency">
                                        <span><?php print mw()->shop_manager->currency_symbol() ?></span>

                                        <input class="mw-ui-field shipping-price-field price-field" type="text" onkeyup="mw.form.typeNumber(this);"
                                               placeholder="0" name="shipping_cost" value="<?php print $item['shipping_cost']; ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="shipping_dimensions" style="display: none">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label">
                                    <?php _e("Additional cost for"); ?>
                                    <em>1 <?php _e("cubic"); ?> <?php print $size_units ?></em>
                                </label>
                                <span class="mwsico-width"></span>
                                <div class="input-with-currency">
                                    <span><?php print mw()->shop_manager->currency_symbol() ?></span>
                                    <input type="text" name="shipping_price_per_size" value="<?php print  floatval($item['shipping_price_per_size']); ?>"
                                           class="mw-ui-field price-field"/>
                                </div>
                            </div>

                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label">
                                    <?php _e("Additional cost for"); ?>
                                    <em>1 <?php print $weight_units ?></em>
                                </label>
                                <span class="mwsico-usd"></span>
                                <div class="input-with-currency">
                                    <div class="input-with-currency">
                                        <span><?php print mw()->shop_manager->currency_symbol() ?></span>
                                        <input type="text" name="shipping_price_per_weight" value="<?php print floatval($item['shipping_price_per_weight']); ?>"
                                               class="mw-ui-field price-field"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="shipping_per_item" style="display: none">
                            <div class="mw-ui-field-holder">
                                <label class="mw-ui-label">
                                    <?php _e("Cost for shipping"); ?>
                                    <em>
                                        <?php _e("each item in the shopping cart"); ?>
                                    </em> <span class="mw-icon-help-outline mwahi tip"
                                                data-tip="<?php _e("This cost will be added to the shipping price for the whole order from the box above"); ?>"></span></label>
                                <div class="input-with-currency">
                                    <span><?php print mw()->shop_manager->currency_symbol() ?></span>
                                    <input type="text" name="shipping_price_per_item"
                                           value="<?php print  floatval($item['shipping_price_per_item']); ?>"
                                           class="mw-ui-field price-field"/>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="shipping-country-row hide-item hidden">
                    <td class="shipping-country-label"><?php _e("Shipping Discount cost"); ?>
                        <span class="mw-icon-help-outline mwahi tip" data-tip="<?php _e("Set a discount shipping price for orders exceeding certain value"); ?>."
                              data-tipposition="right-center"></span></td>
                    <td class="shipping-country-setting">
                        <div class="mw-ui-row" style="width: auto">
                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <div class="same-as-country-selector">
                                        <label class="mw-ui-label">
                                            <?php _e("For orders above:"); ?>
                                        </label>
                                        <div class="input-with-currency">
                                            <span><?php print mw()->shop_manager->currency_symbol() ?></span>
                                            <input
                                                    class="mw-ui-field shipping-price-field price-field"
                                                    type="text"
                                                    onkeyup="mw.form.typeNumber(this);" onblur="mw.form.fixPrice(this);"
                                                    name="shipping_cost_above"
                                                    value="<?php print $item['shipping_cost_above']; ?>"
                                                    placeholder="0">
                                        </div>
                                        <span class="mw-ui-label-help">
                      <?php _e("example"); ?>
                      <?php print currency_format(100); ?></span></div>
                                </div>
                            </div>
                            <div class="mw-ui-col">
                                <div class="mw-ui-col-container">
                                    <label class="mw-ui-label">
                                        <?php _e("Shipping cost"); ?></label>
                                    <div class="input-with-currency">
                                        <span><?php print mw()->shop_manager->currency_symbol() ?></span>
                                        <input class="mw-ui-field price-field shipping-price-field"
                                               type="text" onkeyup="mw.form.typeNumber(this);"
                                               onblur="mw.form.fixPrice(this);"
                                               name="shipping_cost_max"
                                               value="<?php print $item['shipping_cost_max']; ?>"
                                               placeholder="0"/>
                                    </div>
                                    <span class="mw-ui-label-help">
                    <?php _e("Price per order"); ?>
                    </span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="hide-item hidden">
                <button class="mw-ui-btn mw-ui-btn-invert save_shipping_btn" type="submit">
                    <?php if ($new == true): ?>
                        <?php _e("Add"); ?>
                    <?php else: ?>
                        <?php _e("Save"); ?>
                    <?php endif; ?>
                </button>
                <?php if ($new == false): ?>
                    <span title="<?php _e("Reorder shipping countries"); ?>" class="mw-icon-drag shipping-handle-field"></span> <span
                            onclick="mw.shipping_country.delete_country('<?php print $item['id']; ?>');" class="mw-icon-close show-on-hover tip"
                            data-tip="<?php _e("Delete"); ?>"></span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>