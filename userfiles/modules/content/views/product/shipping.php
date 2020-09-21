<style>
    .js-physical-product {
        display: none;
    }
</style>

<script>
    $(document).ready(function () {
        $('.js-physical-product-check').click(function () {
            $('.js-physical-product').toggle();
        });
    });
</script>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong>Shipping</strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-physical-product-check" id="customCheck4">
                        <label class="custom-control-label" for="customCheck4">This is a physical product</label>
                    </div>
                </div>
            </div>
        </div>


        <div class="js-physical-product">

        <hr class="thin no-padding"/>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="d-block">Free Shipping</label>
                    <div class="custom-control custom-radio d-inline-block mr-3">
                        <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                        <label class="custom-control-label" for="customRadio1">Yes</label>
                    </div>
                    <div class="custom-control custom-radio d-inline-block mr-3">
                        <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" checked="">
                        <label class="custom-control-label" for="customRadio2">No</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Fixed Cost</label>
                    <div class="input-group mb-3 prepend-transparent append-transparent input-group-quantity">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-muted">BGN</span>
                        </div>
                        <input type="text" class="form-control" name="fixed_cost" value="<?php echo $contentData['fixed_cost']; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text plus-minus-holder">
                                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h6><strong>Weight</strong></h6>
        <p>Used to calculate shipping rates at checkout and label prices during fulfillment.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Weight</label>
                    <div class="input-group mb-3 append-transparent">
                        <input type="text" class="form-control" name="weight_type" value="<?php echo $contentData['weight_type']; ?>">
                        <div class="input-group-append">
                            <span style="width:70px;">
                                <select class="selectpicker" data-width="100%">
                                    <option>kg</option>
                                    <option>lb</option>
                                    <option>oz</option>
                                    <option>g</option>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <a href="javascript:;" class="btn btn-link" data-toggle="collapse" data-target="#advandec-weight-settings">Show advanced weight setttings</a>
            </div>
        </div>

        <div class="collapse" id="advandec-weight-settings">
            <hr class="thin no-padding"/>

            <h6><strong>Advanced product shipping settings</strong></h6>

            <div class="row">
                <div class="col-lg-3 col-xl">
                    <div class="form-group">
                        <label>Weight</label>
                        <input type="number" name="weight" class="form-control" value="<?php echo $contentData['weight']; ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-xl">
                    <div class="form-group">
                        <label>Width</label>
                        <input type="number" name="width" class="form-control" value="<?php echo $contentData['width']; ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-xl">
                    <div class="form-group">
                        <label>Height</label>
                        <input type="number" name="height" class="form-control" value="<?php echo $contentData['height']; ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-xl">
                    <div class="form-group">
                        <label>Depth</label>
                        <input type="number" name="depth" class="form-control" value="<?php echo $contentData['depth']; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="show-params-checkout-page" />
                            <label class="custom-control-label" for="show-params-checkout-page">Show parameters in checkout page</label>
                        </div>
                    </div>
                </div>
            </div>

         <!--   <span class="help-box">
                Customers won’t enter their shipping address or choose a shipping method when buying this product.
            </span>-->

        </div>
    </div>
    </div>
</div>