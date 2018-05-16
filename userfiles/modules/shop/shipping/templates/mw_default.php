<div style="display: none" class="mw-ui-field-holder">
    <select onchange="Gateway(this);" name="shipping_gw"
            class="mw-ui-field mw-ui-field-medium element-block mw-shipping-gateway mw-shipping-gateway-<?php print $params['id']; ?> <?php if (count($shipping_options) == 1): ?> semi_hidden <?php endif; ?>">
        <?php foreach ($shipping_options as $item) : ?>
            <option value="<?php print  $item['module_base']; ?>"><?php print  $item['name']; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<h5 style="margin-top:0 " class="edit nodrop" field="checkout_shipping_information_title" rel="global"
    rel_id="<?php print $params['id'] ?>">
    <?php _e("Shipping Information"); ?>
</h5>
<hr/>
<?php if (isset($shipping_options[0]) and isset($shipping_options[0]['module_base'])): ?>
    <div id="mw-shipping-gateway-selected-<?php print $params['id']; ?>">
        <module type="<?php print $shipping_options[0]['module_base'] ?>" template="mw_default"/>
    </div>
<?php endif; ?>
