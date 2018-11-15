<?php include('settings_header.php'); ?>

<?php
$curr_symbol = mw()->shop_manager->currency_symbol();

// TODO: move tax code to TaxManager
$ex_tax = '';
$taxes_enabled = get_option('enable_taxes', 'shop');
if($taxes_enabled) {
	$defined_taxes = mw()->tax_manager->get();
	if(!empty($defined_taxes)) {
		if(count($defined_taxes) == 1) {
			$ex_tax = 'ex ' . $defined_taxes[0]['tax_name'];
		} else {
			$ex_tax = 'ex tax';
		}
	}
}

$offers_enabled = (mw()->modules->is_installed('shop/offers')?true:false);
?>
<?php if($offers_enabled) { ?>
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
			data.price_key = $input.data('price-key');
			data.id = $input.data('offer-id');
			data.offer_price = obj.value;
			data.is_active = 1;

			// send ajax request to save offer
			$.ajax({
				type: "POST",
				dataType: "json",
				url: '<?php print api_url('offer_save'); ?>',
				data: data,
				success: function(data) {
					if (typeof(data.error_message) !== "undefined") {
						mw.notification.error(data.error_message);
					}
					$input.data('offer-id',data.offer_id);
				}
			});
		}
</script>
<?php } ?>

<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="input_field_label<?php print $rand; ?>">
    <?php _e('Title'); ?>
  </label>
  <input type="text" class="mw-ui-field" value="<?php print ($data['name']) ?>" name="name" id="input_field_label<?php print $rand; ?>">
</div>
<?php 
    if($data['value'] == ''){
        $data['value'] = 0;
    }
?>
<div class="mw-ui-field-holder">
  <label class="mw-ui-label" for="value<?php print $rand; ?>">Value <b><?php print $curr_symbol; ?> </b><?php print $ex_tax;?></label>
  <input type="text"
        class="mw-ui-field"
        name="value"
        value="<?php print ($data['value']) ?>" />
</div>

<?php if($offers_enabled && !$is_subscription_set) {
  $is_offer_set = false;
  $offer = offers_get_price($data['rel_id'], $data['name_key']);
  if(isset($offer->id) && isset($offer->offer_price)) {
  	$is_offer_set = true;
  }
?>
	<div class="mw-ui-field-holder offer-checkbox" style="display:<?php print ($is_offer_set ? 'none':'block'); ?>;">
		<label class="mw-ui-inline-label">
			<input type="checkbox" class="mw_option_field" name="offer_set" value="1" <?php if($is_offer_set) print 'checked="checked"'; ?> onclick="toggleOffer(this);">
			Set offer price</label>
	</div>

	<div class="mw-ui-field-holder offer-value" style="display:<?php print ($is_offer_set ? 'block':'none'); ?>;">
		  <label class="mw-ui-label" for="offer<?php print $rand; ?>">Offer <b><?php print $curr_symbol; ?> </b><?php print $ex_tax;?></label>
		  <input type="text"
				class="mw-ui-field"
				name="offer"
				value="<?php print ($is_offer_set? floatval($offer->offer_price):''); ?>"
				data-product-id="<?php print $data['rel_id'];?>"
				data-price-key="<?php print $data['name_key'];?>"
				data-offer-id="<?php print ($is_offer_set? $offer->id:'');?>"
				onblur="saveOffer(this)"
				<?php print $readonly;?>>
	</div>
<?php } ?>

<?php include('settings_footer.php'); ?>