<div class="mw-shipping-select">
    <div  class="my-4" <?php if(count($shipping_options) == 1): ?>style="display: none" <?php endif; ?>>
        <h4 class="mt-5"><?php _e("How you prefer to receive your order ?"); ?></h4>
        <small class="text-muted d-block mb-2"> <?php _e("Choose the right method for deliver your order."); ?></small>
        <?php $count = 0;
         foreach ($shipping_options as $item) : $count++; ?>
                <div class="form-group">
                    <div class="custom-control custom-radio checkout-v2-radio pl-0 pt-3">
                        <input type="radio" onchange="Gateway(this);" <?php if ($count == 1): ?> checked="checked" <?php endif; ?> name="shipping_gw" value="<?php print  $item['module_base']; ?>" <?php if ($selected_shipping_gateway == $item['module_base']): ?> <?php endif; ?>">
                        <label class="control-label">
                            <?php
                            if (isset($item['settings']['icon_class'])):
                            ?>
                            <i class="shipping-icons-checkout-v2 <?php echo $item['settings']['icon_class']; ?>"></i>
                            <?php
                            endif;
                            ?>
                            <?php print $item['name']; ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>

    <?php if (isset($selected_shipping_gateway) and is_module($selected_shipping_gateway)): ?>
        <div id="mw-shipping-gateway-selected-wrapper-<?php print $params['id']; ?>">
            <module id="mw-shipping-gateway-selected-<?php print $params['id']; ?>" type="<?php print $selected_shipping_gateway ?>" template="checkout_v2" />
        </div>
    <?php endif; ?>
</div>


