<?php
only_admin_access();

if (!isset($params['price-id']) or !isset($params['price-id']) or !isset($params['id'])) {
    return;
}


$curr_symbol = mw()->shop_manager->currency_symbol();

$price_id = $params['price-id'];
$product_id = $params['product-id'];

 
$offers_enabled = (mw()->modules->is_installed('shop/offers') ? true : false);
?>
<?php if ($offers_enabled) { ?>
    <script type="text/javascript">
        function toggleOffer(obj) {
            var $input = $(obj);
            var offerCheck = '.offer-checkbox';
            var offerValue = '.offer-value';
            if ($input.prop('checked')) {
                $(offerCheck).hide();
                $(offerValue).show();
            }
        }

        function saveOffer(obj) {
            var $input = $(obj);
            var data = {};
            data.product_id = $input.data('product-id');
            data.price_id = $input.data('price-id');
            data.id = $input.data('offer-id');
            data.offer_price = obj.value;
            data.is_active = 1;

            // send ajax request to save offer
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php print api_url('offer_save'); ?>',
                data: data,
                success: function (data) {
                    mw.notification.success('Price is saved')
                    if (typeof(data.error_message) !== "undefined") {
                        mw.notification.error(data.error_message);
                    }


                    $input.data('offer-id', data.offer_id);
                    mw.reload_module_parent('custom_fields')

                }
            });
        }

        function deleteOffer(offer_id) {
            var confirmUser = confirm('<?php _e('Are you sure you want to delete this offer?'); ?>');
            if (confirmUser == true) {
                $.ajax({
                    url: '<?php print api_url('offer_delete');?>',
                    data: 'offer_id=' + offer_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        mw.notification.success('Price is deleted')
                        if (typeof(reload_offer_after_save) != 'undefined') {
                            reload_offer_after_save();
                        }
                        mw.reload_module('#<?php print $params['id'] ?>')
                        mw.reload_module_parent('custom_fields')

                    }
                });
            }
        }


    </script>
<?php } ?>
<?php if ($offers_enabled) {
    $is_offer_set = false;
    $offer = offers_get_price($product_id,$price_id);

    if (isset($offer['id']) && isset($offer['offer_price'])) {
        $is_offer_set = true;
    }



    ?>
    <div class="mw-ui-field-holder offer-checkbox" style="display:<?php print ($is_offer_set ? 'none' : 'block'); ?>;">
        <label class="mw-ui-inline-label">
            <input type="checkbox" class="mw_option_field" name="offer_set"
                   value="1" <?php if ($is_offer_set) print 'checked="checked"'; ?> onclick="toggleOffer(this);">
            <?php _e('Set offer price') ?></label>
    </div>

    <div class="mw-ui-field-holder offer-value" style="display:<?php print ($is_offer_set ? 'block' : 'none'); ?>;">
        <label class="mw-ui-label" for="offer"><?php _e('New price') ?> <b><?php print $curr_symbol; ?> </b></label>
        <input type="text"
               class="mw-ui-field"
               name="offer"
               value="<?php print ($is_offer_set ? floatval($offer['offer_price']) : ''); ?>"
               data-product-id="<?php print $product_id; ?>"
               data-price-id="<?php print $price_id; ?>"
               data-offer-id="<?php print ($is_offer_set ? $offer['id'] : ''); ?>"
               onblur="saveOffer(this)"/>




        <?php if ($is_offer_set): ?>
            <a href="#" onclick="deleteOffer('<?php print $offer['id']; ?>')">x</a>
        <?php endif; ?>

    </div>
<?php } ?>