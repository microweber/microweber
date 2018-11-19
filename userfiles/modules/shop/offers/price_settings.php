<?php
$curr_symbol = mw()->shop_manager->currency_symbol();

$data = $params;

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
                    if (typeof(data.error_message) !== "undefined") {
                        mw.notification.error(data.error_message);
                    }
                    $input.data('offer-id', data.offer_id);
                }
            });
        }
    </script>
<?php } ?>
<?php if ($offers_enabled) {
    $is_offer_set = false;
    $offer = offers_get_price($data['rel_id'], $data['id']);

    if (isset($offer->id) && isset($offer->offer_price)) {
        $is_offer_set = true;
    }
    ?>
    <div class="mw-ui-field-holder offer-checkbox" style="display:<?php print ($is_offer_set ? 'none' : 'block'); ?>;">
        <label class="mw-ui-inline-label">
            <input type="checkbox" class="mw_option_field" name="offer_set"
                   value="1" <?php if ($is_offer_set) print 'checked="checked"'; ?> onclick="toggleOffer(this);">
            Set offer price</label>
    </div>

    <div class="mw-ui-field-holder offer-value" style="display:<?php print ($is_offer_set ? 'block' : 'none'); ?>;">
        <label class="mw-ui-label" for="offer">Offer <b><?php print $curr_symbol; ?> </b></label>
        <input type="text"
               class="mw-ui-field"
               name="offer"
               value="<?php print ($is_offer_set ? floatval($offer->offer_price) : ''); ?>"
               data-product-id="<?php print $data['rel_id']; ?>"
               data-price-id="<?php print $data['id']; ?>"
               data-offer-id="<?php print ($is_offer_set ? $offer->id : ''); ?>"
               onblur="saveOffer(this)"/>
    </div>
<?php } ?>