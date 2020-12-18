<?php must_have_access(); ?>

<?php

$disable_default_shipping_fields = get_option('disable_default_shipping_fields', 'shipping');
$enable_custom_shipping_fields = get_option('enable_custom_shipping_fields', 'shipping');

?>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Disable default shipping fields"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="disable_default_shipping_fields1" name="disable_default_shipping_fields" class="mw_option_field custom-control-input" data-option-group="shipping" value="1" <?php echo $disable_default_shipping_fields ? 'checked="checked"' : ''?> >
        <label class="custom-control-label" for="disable_default_shipping_fields1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="disable_default_shipping_fields2" name="disable_default_shipping_fields" class="mw_option_field custom-control-input" data-option-group="shipping" value="0" <?php echo $disable_default_shipping_fields ? '' : 'checked="checked"'?>  >
        <label class="custom-control-label" for="disable_default_shipping_fields2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="control-label d-block"><?php _e("Enable custom shipping fields"); ?></label>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="enable_custom_shipping_fields1" name="enable_custom_shipping_fields" class="mw_option_field custom-control-input" data-option-group="shipping" value="1" <?php echo $enable_custom_shipping_fields ? 'checked="checked"' : ''?> >
        <label class="custom-control-label" for="enable_custom_shipping_fields1"><?php _e("Yes"); ?></label>
    </div>

    <div class="custom-control custom-radio d-inline-block mr-2">
        <input type="radio" id="enable_custom_shipping_fields2" name="enable_custom_shipping_fields" class="mw_option_field custom-control-input" data-option-group="shipping" value="0" <?php echo $enable_custom_shipping_fields ? '' : 'checked="checked"'?>  >
        <label class="custom-control-label" for="enable_custom_shipping_fields2"><?php _e("No"); ?></label>
    </div>
</div>

<div class="d-none" id="custom-fields-wrapper">
    <module type="custom_fields/admin" for-id="shipping"  for-id="shipping"  default-fields="message"   />
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

