<?php must_have_access(); ?>

<?php

$disable_default_shipping_fields = get_option('disable_default_shipping_fields', 'shipping');
$enable_custom_shipping_fields = get_option('enable_custom_shipping_fields', 'shipping');

?>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Disable default shipping fields"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("This will remove all shipping fields from the checkout form"); ?></small>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="disable_default_shipping_fields1" name="disable_default_shipping_fields" class="mw_option_field form-check-input" data-option-group="shipping" value="1" <?php echo $disable_default_shipping_fields ? 'checked="checked"' : ''?> >
        <label class="custom-control-label" for="disable_default_shipping_fields1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="disable_default_shipping_fields2" name="disable_default_shipping_fields" class="mw_option_field form-check-input" data-option-group="shipping" value="0" <?php echo $disable_default_shipping_fields ? '' : 'checked="checked"'?>  >
        <label class="custom-control-label" for="disable_default_shipping_fields2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="form-label d-block"><?php _e("Enable custom shipping fields"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Include additional custom fields in the shipping form"); ?></small>


    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="enable_custom_shipping_fields1" name="enable_custom_shipping_fields" class="mw_option_field form-check-input" data-option-group="shipping" value="1" <?php echo $enable_custom_shipping_fields ? 'checked="checked"' : ''?> >
        <label class="custom-control-label" for="enable_custom_shipping_fields1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block me-2">
        <input type="radio" id="enable_custom_shipping_fields2" name="enable_custom_shipping_fields" class="mw_option_field form-check-input" data-option-group="shipping" value="0" <?php echo $enable_custom_shipping_fields ? '' : 'checked="checked"'?>  >
        <label class="custom-control-label" for="enable_custom_shipping_fields2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="d-none" id="custom-fields-wrapper">
    <module type="custom_fields/admin" for-id="shipping"  for-id="shipping"  default-fields="message"   />
</div>

<div class="form-group d-none">

    <label class="form-label d-block"><?php _e("Require fields for checkout"); ?></label>


    <div class="custom-control custom-checkbox my-2">
        <input type="checkbox" name="require_country" data-option-group="shipping" value="1" class="mw_option_field  form-check-input" id="check-shop_require_country" <?php if (get_option('require_country', 'website') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="check-shop_require_country"></label> <?php _e("Country"); ?>
    </div>


    <div class="custom-control custom-checkbox my-2">
        <input type="checkbox" name="require_city" data-option-group="shipping" value="1" class="mw_option_field  form-check-input" id="check-shop_require_city" <?php if (get_option('require_city', 'website') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="check-shop_require_city"></label> <?php _e("City"); ?>
    </div>


    <div class="custom-control custom-checkbox my-2">
        <input type="checkbox" name="require_address" data-option-group="shipping" value="1" class="mw_option_field  form-check-input" id="check-shop_require_address" <?php if (get_option('require_address', 'website') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="check-shop_require_address"></label> <?php _e("Address"); ?>
    </div>

    <div class="custom-control custom-checkbox my-2">
        <input type="checkbox" name="require_state" data-option-group="shipping" value="1" class="mw_option_field  form-check-input" id="check-shop_require_state" <?php if (get_option('require_state', 'website') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="check-shop_require_state"></label> <?php _e("State"); ?>
    </div>


    <div class="custom-control custom-checkbox my-2">
        <input type="checkbox" name="require_zip" data-option-group="shipping" value="1" class="mw_option_field  form-check-input" id="check-shop_require_zip" <?php if (get_option('require_zip', 'website') == 1): ?> checked="checked" <?php endif; ?>>
        <label class="custom-control-label" for="check-shop_require_zip"></label> <?php _e("Zip"); ?>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function () {
        toggleShippingFieldsVisibility();

        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");

            mw.reload_module_everywhere('shop/shipping');

            toggleShippingFieldsVisibility();
        });

        function toggleShippingFieldsVisibility() {

            var cucstomFieldsEl = $('#custom-fields-wrapper');
            var isEnableShippindFields = $('input[name=enable_custom_shipping_fields]:checked').val();

            if(isEnableShippindFields == 1) {
                cucstomFieldsEl.removeClass('d-none');
            } else {
                cucstomFieldsEl.addClass('d-none');
            }
        }
    });
</script>

