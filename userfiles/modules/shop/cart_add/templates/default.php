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

<br class="mw-add-to-cart-spacer"/>
<module type="custom_fields" data-content-id="<?php print intval($for_id); ?>" data-skip-type="price"
        id="cart_fields_<?php print $params['id'] ?>"/>
<?php if (is_array($data)): ?>
    <div class="price">
        <?php $i = 1;

        // TODO: move to taxmanager function
        $ex_tax = '';
        $taxes_enabled = get_option('enable_taxes', 'shop');
        if ($taxes_enabled) {
            $defined_taxes = mw()->tax_manager->get();
            if (!empty($defined_taxes)) {
                if (count($defined_taxes) == 1) {
                    $ex_tax = ' ex ' . $defined_taxes[0]['tax_name'];
                } else {
                    $ex_tax = ' ex tax';
                }
            }
        }


        foreach ($data as $key => $v): ?>
            <div class="mw-price-item">


		  <?php
          if (mw()->modules->is_installed('shop/offers') && is_array($price_offers) && isset($price_offers[$key]) && isset($price_offers[$key]['offer_price'])) {
              $v = $price_offers[$key]['offer_price'];

              ?>
          <?php } else { ?>
            <?php if (is_string($key) and trim(strtolower($key)) == 'price'): ?>
                <?php _e($key); ?>
            <?php else: ?>
                <?php print $key; ?>
            <?php endif; ?>: <?php print currency_format($v) . $ex_tax; ?>
        <?php } ?>
                <?php if (!isset($in_stock) or $in_stock == false) : ?>
                    <button class="btn btn-default pull-right" type="button" disabled="disabled"
                            onclick="Alert('<?php print addslashes(_e("This item is out of stock and cannot be ordered", true)); ?>');">
                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                        <?php _e("Out of stock"); ?>
                    </button>
                <?php else: ?>

                    <?php if ($price_offers && isset($price_offers[$key])): ?>

                        <module type="shop/offers" data-offer-id="<?php print intval($price_offers[$key]['id']); ?>"/>

                        <button class="btn btn-default  " type="button"
                                onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                            <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                            <?php _e($button_text !== false ? $button_text : "Add to cart"); ?>
                        </button>
                    <?php else: ?>


                    <button class="btn btn-default pull-right" type="button"
                            onclick="mw.cart.add('.mw-add-to-cart-<?php print $params['id'] ?>','<?php print $v ?>', '<?php print $title; ?>');">
                        <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>
                        <?php _e($button_text !== false ? $button_text : "Add to cart"); ?>
                    </button>



                <?php endif; ?>

                    <?php $i++; endif; ?>


            </div>
            <?php if ($i > 1) : ?>
                <br/>
            <?php endif; ?>
            <?php $i++; endforeach; ?>
    </div>
<?php endif; ?>
