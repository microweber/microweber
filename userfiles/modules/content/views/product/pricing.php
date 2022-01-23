<div class="card style-1 mb-3 js-product-pricing-card">
    <div class="card-header no-border">
        <h6><strong><?php _e("Pricing"); ?></strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">

            <div class="col-md-12">
                <label><?php _e("Price"); ?></label>
                <div class="input-group mb-3 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
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
                <module type="shop/offers/special_price_field" product_id="<?php echo $product['id'];?>" />
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
                        <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                        <label class="custom-control-label" for="customCheck1">Charge tax on this product</label>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>
