<?php

/*

type: layout

name: Add to cart default

description: Add to cart default

*/


?>
<?php

if (isset($params['content-id'])) {
    $product = get_content_by_id($params["content-id"]);
    $title = $product['title'];
} else {
    $title = _e("Product", true);
}


?>
<div class="item__addtocart" style="display: inline-block;">
    <module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price" id="cart_fields_<?php print $params['id'] ?>"/>

    <?php if (is_array($data)): ?>
  <?php
	// TODO: move to taxmanager function
	$ex_tax = '';
	$taxes_enabled = get_option('enable_taxes', 'shop');
	if($taxes_enabled) {
		$defined_taxes = mw()->tax_manager->get();
		if(!empty($defined_taxes)) {
			if(count($defined_taxes) == 1) {
				$ex_tax = ' ex ' . $defined_taxes[0]['tax_name'];
			} else {
				$ex_tax = ' ex tax';
			}
		}
	}

	// check for offer prices
	if (mw()->modules->is_installed('shop/offers')) {
		$price_offers = offers_get_by_product_id($for_id);
	}
  ?>
        <?php foreach ($data as $key => $v): ?>

		  <?php
		  if (mw()->modules->is_installed('shop/offers') && is_array($price_offers) && isset($price_offers[$key])) {
		  ?>
		    <module type="shop/offers" data-content-id="<?php print intval($for_id); ?>" data-parent-id="<?php print $params['id']; ?>" data-title="<?php print $title; ?>" data-in-stock="<?php print $in_stock;?>" data-count="<?php print count($data);?>" data-price-name="<?php print $key;?>" data-offer-price="<?php print $price_offers[$key]['offer_price'];?>" data-retail-price="<?php print $v;?>" data-expires="<?php print $price_offers[$key]['expires_at'];?>" id="offer_price_<?php print $for_id . '_' . $price_offers[$key]['price_key'] ?>"  />
		  <?php } else { ?>
            <div class="price" id="price_<?php print intval($for_id) . $key; ?>" style="margin-bottom: 30px;">
                <div class="item__price" style="margin-bottom: 20px;">
                    <span>
                        <?php if (is_string($key) and trim(strtolower($key)) == 'price'): ?>
                            <?php _e($key); ?>
                        <?php else: ?>
                            <?php print $key; ?>
                        <?php endif; ?>: <?php print currency_format($v).$ex_tax; ?>
                    </span>
                </div>

<!--                <input type="text" value="1" name="qty"/>-->

                <?php if (!isset($in_stock) or $in_stock == false) : ?>
                    <button class="btn btn--primary" type="button" disabled="disabled"
                            onclick="alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');">
                        <?php _e("Out of stock"); ?>
                    </button>
                <?php else: ?>
                    <button class="btn btn--primary" type="button"
                            onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                        <?php _e($button_text !== false ? $button_text : "Add to cart"); ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>