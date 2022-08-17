<?php must_have_access(); ?>

<?php
if (!isset($params['product_id']) or !class_exists('\MicroweberPackages\Offer\Models\Offer')) {
    return;
}

$productId = $params['product_id'];
//WAS $offer = offers_get_by_product_id($productId);
$offer = app()->offer_repository->getByProductId($productId);

if (!isset($offer['price']['offer_price'])) {
    $offer['price']['offer_price'] = '';
}
?>
<script>
    $(document).ready(function () {
        var specialPriceElement = $('.js-product-special-price');
        var specialPriceSet = "<?php echo !empty($offer['price']['offer_price']) ? 1 : 0?>";

        if(specialPriceSet == 1) {
            $('#customCheck322').attr('checked','checked');
            $('.js-offer-price-form-group').show();
        }

        $(specialPriceElement).on('input', function () {
            mw.on.stopWriting(this, function () {
                var textPrice = $(specialPriceElement).val();
                var formatPrice = textPrice.replaceAll(",", "");
                $(specialPriceElement).val(formatPrice);
            });
        });

    });

    function openOfferEdit(offer_id) {
        var data = {};
        var mTitle = (offer_id ? 'Edit offer' : 'Add new offer');
        data.offer_id = offer_id;
        editModal = mw.tools.open_module_modal('shop/offers/edit_offer', data, {overlay: true, skin: 'simple', title: mTitle})
    }

    function toggleOfferPrice() {
        $('.js-offer-price-form-group').toggle();
    }
</script>

<div class="col-md-12 px-0">

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input autocomplete="off" type="checkbox" name="content_data[has_special_price]" class="custom-control-input js-toggle-offer-price-button"  id="customCheck322" data-value-checked="1" data-value-unchecked="0" onchange="toggleOfferPrice()" value="1"  />
        <label class="custom-control-label" for="customCheck322"><?php _e('Make offer price for product'); ?></label>
    </div>
</div>


<div class="form-group js-offer-price-form-group" style="display: none">
	<label><?php _e('Offer Price'); ?></label>
	<div class="input-group mb-3 prepend-transparent append-transparent">
		<div class="input-group-prepend">
			<span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
		</div>

		<input autocomplete="off" type="text" class="form-control js-product-special-price" name="content_data[special_price]" value="<?php echo $offer['price']['offer_price'];?>">

        <?php if (isset($offer['price']['offer_id'])): ?>
            <div class="input-group-append">
                <span class="input-group-text cursor-pointer" onclick="openOfferEdit('<?php echo $offer['price']['offer_id']; ?>');" data-bs-toggle="tooltip" title="Settings">
                    <i class="mdi mdi-offer text-muted mdi-20px"></i></span>
            </div>
        <?php else: ?>
            <div class="input-group-append" data-bs-toggle="tooltip" data-original-title="To put a product on sale, make Compare at price the original price and enter the lower amount into Price.">
                <span class="input-group-text"><i class="mdi mdi-help-circle"></i></span>
            </div>
        <?php endif; ?>

	</div>
    <?php if(isset($offer['price']['expires_at']) && $offer['price']['expires_at'] > 0): ?>
        <div class="text-muted">
            <?php _e('Expires at'); ?>    <?php echo $offer['price']['expires_at']; ?>
        </div>
    <?php endif; ?>
</div>

</div>
