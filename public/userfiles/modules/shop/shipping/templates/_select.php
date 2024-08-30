<div class="mw-shipping-select">
    <div   <?php if(count($shipping_options) == 1): ?>style="display: none" <?php endif; ?>>
        <select autocomplete="off" onchange="Gateway(this);" name="shipping_gw"
                class="field-full form-select mw-shipping-gateway mw-shipping-gateway-<?php print $params['id']; ?> <?php if (count($shipping_options) == 1): ?> semi_hidden <?php endif; ?>">
            <?php foreach ($shipping_options as $item) : ?>
                <option value="<?php print  $item['module_base']; ?>" <?php if ($selected_shipping_gateway == $item['module_base']): ?> selected <?php endif; ?>    ><?php print  $item['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (isset($selected_shipping_gateway) and is_module($selected_shipping_gateway)): ?>
        <div id="mw-shipping-gateway-selected-wrapper-<?php print $params['id']; ?>">
            <module id="mw-shipping-gateway-selected-<?php print $params['id']; ?>" type="<?php print $selected_shipping_gateway ?>" template="modal"/>
        </div>
    <?php endif; ?>
</div>
