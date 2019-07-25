<?php

/*

type: layout

name: Default

description: Default

*/
?>


<script>
    $(document).ready(function () {

        if (!!$.fn.selectpicker) {
            $('.selectpicker').selectpicker();
        }


    });
</script>


<div class="<?php print $config['module_class'] ?>" id="<?php print $module_wrapper_id ?>">


    <?php $selected_country = mw()->user_manager->session_get('shipping_country'); ?>


    <div class="m-t-20 edit nodrop" field="checkout_shipping_information_title" rel="global"
         rel_id="<?php print $params['id'] ?>">
        <div class="pull-right red">* All fields are required</div>
        <p class="bold m-b-10"><?php _e("Shipping Information"); ?></p>
    </div>


    <div class="row">


        <?php if ($data) { ?>
            <div class="col-6">

                <div class="field-holder">
                    <select name="country" class="selectpicker shipping-country-select">
                        <option value=""><?php _e("Country"); ?></option>
                        <?php foreach ($data as $item): ?>
                            <option value="<?php print $item['shipping_country'] ?>" <?php if (isset($selected_country) and $selected_country == $item['shipping_country']): ?> selected="selected" <?php endif; ?>><?php print $item['shipping_country'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        <?php } ?>

        <div class="col-6">
            <div class="field-holder">
                <input name="Address[city]" class="form-control input-lg" required type="text" value=""
                       placeholder="<?php _e("Town / City"); ?>"/>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-6">

            <div class="field-holder">
                <input name="Address[zip]" class="form-control input-lg" type="text" value=""
                       placeholder="<?php _e("ZIP / Postal Code"); ?>"/>
            </div>

        </div>


        <div class="col-6">

            <div class="field-holder">
                <input name="Address[state]" class="form-control input-lg" type="text" value=""
                       placeholder="<?php _e("State / Province"); ?>"/>
            </div>
        </div>


    </div>


    <div class="row">

        <div class="col-6">
            <div class="field-holder">
                <input name="Address[address]" class="form-control input-lg" required type="text" value=""
                       placeholder="<?php _e("Address / Street address, Floor, Apartment, etc..."); ?>"/>
            </div>

        </div>

        <div class="col-6">

            <div class="field-holder">
                <input name="other_info" class="form-control input-lg" type="text" value=""
                       placeholder="Additional Information ( Special notes for delivery - Optional )"/>
            </div>
        </div>
    </div>


</div>
