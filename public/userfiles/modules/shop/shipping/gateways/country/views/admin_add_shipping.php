<?php $rand1 = 'shipping_to_country_holder' ?>

<script type="text/javascript">
    if (mw.shipping_country == undefined) {
        mw.shipping_country = {};
    }
    mw.require('forms.js');
    if (window.thismodal) {
        thismodal.width(1000);
        thismodal.center(1000);
    }
</script>

<script type="text/javascript">

    $(document).ready(function () {
        mw.$(".shipping_to_country_holder").sortable({
            items: '.shipping-country-holder',
            //helper:"clone",
            axis: 'y',
            cancel: ".country-id-0",
            handle: '.shipping-handle-field',
            update: function () {
                var obj = {cforder: []}
                $(this).find('.shipping-country-holder').each(function () {
                    var id = this.attributes['data-field-id'].nodeValue;
                    obj.cforder.push(id);
                });
                $.post("<?php print $config['module_api']; ?>/shipping_to_country/reorder", obj, function () {
                    mw.reload_module('[data-parent-module="shop/shipping"]');
                    if (window.parent != undefined && window.parent.mw != undefined) {
                        window.mw.parent().reload_module('shop/shipping/gateways/country');
                    }
                });
            },
            start: function (a, ui) {
                $(this).height($(this).outerHeight());
                $(ui.placeholder).height($(ui.item).outerHeight())
                $(ui.placeholder).width($(ui.item).outerWidth())
            },
            stop: function () {
                mw.$(".<?php print $rand1 ?>").height("auto");
            },
            scroll: false,
            placeholder: "custom-field-main-table-placeholder"
        });

        <?php if(empty($data_orig)): ?>
        mw.$('.country-id-0').show()
        <?php endif;?>

       /* mw.$(".shipping_type_dropdown").each(function () {
            var parent = mw.tools.firstParentWithTag(this, 'td');
            parent = $(parent).next('td');
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
            var parent = mw.tools.firstParentWithTag(this, 'td');
            parent = $(parent).next('td');
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
        });*/
    });
</script>

<script type="text/javascript">

   /* ToggleShipping = function (e) {

        var el = e.target;
        var eroot = $(el).parents('form')[0];
        mw.tools.loading(eroot, true)
        var data = {
            id: el.getAttribute('data-id'),
            is_active: el.checked ? 1 : 0
        }
        SaveShippingData(data).always(function () {
            mw.reload_module('shop/shipping', function () {
                mw.tools.loading(eroot, false);
            });

        });
    }


    SaveShippingData = function (data) {
        return $.post('<?php print $config['module_api']; ?>/shipping_to_country/save', data)
            .done(function () {
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
                if (dataType == 'new') {

                    mw.reload_module('<?php print $config['the_module']; ?>', function () {
                        mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");
                    });
                }
                else {
                    mw.reload_module(dataType, function () {
                        mw.notification.success("<?php _ejs("Shipping changes are saved"); ?>");
                    });
                }
                if (window.parent != undefined && window.parent.mw != undefined) {
                    window.mw.parent().reload_module('shop/shipping/gateways/country');
                }
                mw.reload_module('shop/shipping');

            }
        );
    }*/
</script>

<script>
    //    $(document).ready(function () {
    //
    //        $('.toggle-item', '#<?php //print $params['id'] ?>//').on('click', function (e) {
    //
    //            if ($(e.target).hasClass('toggle-item') || (e.target).nodeName == 'TD') {
    //                $(this).find('.hide-item').toggleClass('hidden');
    //                $(this).closest('.toggle-item').toggleClass('closed-fields');
    //            }
    //        });
    //    });
</script>


<div id="shipping-type-tooltip" style="display: none">
    <p><?php _e("Select a shipping type applicable to customer orders"); ?>:</p>
    <ol>
        <li><strong><?php _e("Fixed"); ?></strong> - <?php _e("Set a shipping price for the whole order"); ?>
        </li>
        <li><strong><?php _e("Dimensions or Weight"); ?></strong>
            - <?php _e("Set a flexible shipping price depending on a product’s dimensions or weight"); ?></li>
        <li><strong><?php _e("Per item"); ?></strong>
            -<?php _e("Charge a set shipping price for each product a customer orders"); ?></li>
    </ol>
</div>

<div id="shippingtip" style="display: none">
    <div style="width: 320px;">
        <?php _e("You are able to allow or disallow shipping to the selected country. For example if you ship worldwide you can disallow shipping to one or more countries."); ?>
    </div>
</div>


<!--<div class="add-new-country mw-shipping-items" <?php /*if ($has_data == false): */?>style="display: block;" <?php /*endif; */?>>
    <div>
        <p class="disabled-and-enabled-label"><?php /*print _e('Add shipping to country'); */?></p>
    </div>

    <?php /*include __DIR__ . "/item_edit.php"; */?>
</div>-->
