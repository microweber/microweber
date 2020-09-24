<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong>Pricing</strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">

            <?php
            $col_size = 12;
            if (is_module('shop/offers')) {
                $col_size = 6;
            }
            ?>

            <div class="col-md-<?php echo $col_size; ?>">
                <label>Price</label>
                <div class="input-group mb-3 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted">BGN</span>
                    </div>
                    <input type="text" class="form-control js-product-price" name="price" value="<?php echo $productPrice; ?>">
                </div>
            </div>

            <?php
            if (is_module('shop/offers')):
            ?>
            <div class="col-md-<?php echo $col_size; ?>">
                <module type="shop/offers/special_price_field" product_id="<?php echo $product['id'];?>" />
            </div>
            <?php endif; ?>

        </div>

     <!--   <hr class="thin no-padding"/>

        <div class="row">
            <div class="col-md-12">
                <label>Cost per item</label>
                <small class="text-muted d-block mb-2">Customers won’t see this</small>

                <div class="input-group mb-3 prepend-transparent">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted">BGN</span>
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
