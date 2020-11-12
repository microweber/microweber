<?php must_have_access(); ?>

<?php
if (!isset($params['product_id'])) {
    return;
}

$productId = $params['product_id'];
$offer = offers_get_by_product_id($productId);

if (!isset($offer['price']['offer_price'])) {
    $offer['price']['offer_price'] = 0;
}
?>
<script>

    $(document).ready(function () {
        var specialPriceElement = $('.js-product-special-price');
        $(specialPriceElement).on('input', function () {
            mw.on.stopWriting(this, function () {
               if (parseFloat($('.js-product-price').val()) === parseFloat($(this).val())) {
                    mw.notification.warning('<?php _e('Special price must be different from the original price!'); ?>');
               }
            });
        });

    });

    function openOfferEdit(offer_id) {
        var data = {};
        var mTitle = (offer_id ? 'Edit offer' : 'Add new offer');
        data.offer_id = offer_id;
        editModal = mw.tools.open_module_modal('shop/offers/edit_offer', data, {overlay: true, skin: 'simple', title: mTitle})
    }
</script>


<div class="form-group">
	<label><?php _e('Offer Price'); ?></label>
	<div class="input-group mb-3 prepend-transparent append-transparent">
		<div class="input-group-prepend">
			<span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
		</div>
		<input type="text" class="form-control js-product-special-price" name="special_price" value="<?php echo $offer['price']['offer_price'];?>">

        <?php if (isset($offer['price']['offer_id'])): ?>
            <div class="input-group-append">
                <span class="input-group-text cursor-pointer" onclick="openOfferEdit('<?php echo $offer['price']['offer_id']; ?>');" data-toggle="tooltip" title="Settings">
                    <i class="mdi mdi-offer text-muted mdi-20px"></i></span>
            </div>
        <?php else: ?>
            <div class="input-group-append" data-toggle="tooltip" data-original-title="To put a product on sale, make Compare at price the original price and enter the lower amount into Price.">
                <span class="input-group-text"><i class="mdi mdi-help-circle"></i></span>
            </div>
        <?php endif; ?>

	</div>
</div>