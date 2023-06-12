<div class="card-body mb-3 js-product-pricing-card">
    <div class="card-header no-border">
        <label for="" class="form-label"><?php _e("Pricing"); ?></label>
    </div>

    <div class=" ">
        <div class="row py-0">

            <div class="col-md-12 ps-md-0">
                <label><?php _e("Price"); ?></label>
                <div class="input-group mb-3 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted h-100"><?php echo get_currency_code(); ?></span>
                    </div>
                    <input type="text" class="form-control js-product-price" name="price" value="<?php echo $productPrice; ?>">
                </div>
            </div>

            <script>
                $(document).ready(function () {
                    $('.js-product-price').on('input', function () {
                        mw.on.stopWriting(this, function () {
                            var textPrice = $('.js-product-price').val();
                            var formatPrice = textPrice.replaceAll(",", "");
                            $('.js-product-price').val(formatPrice);
                        });
                    });
                });
            </script>

            <?php
            if (is_module('shop/offers')):
            ?>
                <module class="ps-md-0" type="shop/offers/special_price_field" product_id="<?php echo $product['id'];?>" />
            <?php endif; ?>

        </div>

     <!--   <hr class="thin no-padding"/>

        <div class="row">
            <div class="col-md-12">
                <label>Cost per item</label>
                <small class="text-muted d-block mb-2">Customers won’t see this</small>

                <div class="input-group mb-3 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
                    </div>
                    <input type="text" class="form-control" name="unit_price" value="0.00">
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="form-check-input" id="customCheck1" checked="">
                        <label class="custom-control-label" for="customCheck1">Charge tax on this product</label>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>
