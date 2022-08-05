
<?php include('partials/header.php'); ?>


<div class="tree">
    DURVO
</div>

<script>
    $(document).ready(function () {
//        $('body > .main').addClass('show-sidebar-tree');
    });
</script>

<main>
    <div class="row">
        <div class="col-md-12">
            <div class="card bg-light style-1 mb-3">
                <div class="card-header no-border">
                    <h5><strong><?php _e("L / Red"); ?></strong></h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-success"><?php _e("Save"); ?></button>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card style-1 mb-3">
                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-4 px-0">
                                            <div class="img-circle-holder border-radius-10 border-silver w-auto">
                                                <img src="assets/img/no-image.jpg">
                                            </div>
                                        </div>

                                        <div class="col-8">
                                            <p class="mb-0"><?php _e("New product"); ?></p>
                                            <small class="text-muted"><?php _e("9 variants"); ?></small>
                                            <a href="#" class="btn btn-sm btn-link px-0"><?php _e("Back to products"); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong><?php _e("Variants"); ?></strong></h6>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-link px-0"><?php _e("Delete"); ?></a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="variants-select">
                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0"><?php _e("L / Red"); ?></p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0"><?php _e("L / Blue"); ?></p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0"><?php _e("L / Yellow"); ?></p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0"><?php _e("M / Red"); ?></p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0"><?php _e("M / Blue"); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong><?php _e("Options"); ?></strong></h6>
                                    <div>
                                        <select class="selectpicker" data-title="Add media from" data-style="btn-sm" data-width="auto">
                                            <option><?php _e("Add image from URL"); ?></option>
                                            <option><?php _e("Browse uploaded"); ?></option>
                                            <option><?php _e("Add file"); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label><?php _e("Size"); ?></label>
                                                <input type="text" class="form-control" placeholder="<?php _e("L"); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label><?php _e("Color"); ?></label>
                                                <input type="text" class="form-control" placeholder="<?php _e("Red"); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="dropable-zone small-zone square-zone w-100">
                                                <div class="holder">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm text-primary mb-2"><?php _e("Add file"); ?></button>
                                                    <p><?php _e("or drop"); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong><?php _e("Pricing"); ?></strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label><?php _e("Price"); ?></label>
                                            <div class="input-group mb-3 prepend-transparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-muted"><?php _e("BGN"); ?></span>
                                                </div>
                                                <input type="text" class="form-control" value="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Price on sale (compare at price)"); ?></label>
                                                <div class="input-group mb-3 prepend-transparent append-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-muted"><?php _e("BGN"); ?></span>
                                                    </div>
                                                    <input type="text" class="form-control" value="0.00">
                                                    <div class="input-group-append">
                                                            <span class="input-group-text" data-bs-toggle="tooltip" title=""
                                                                  data-original-title="<?php _e("To put a product on sale, make Compare at price the original price and enter the lower amount into Price"); ?>."><i class="mdi mdi-help-circle"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="thin no-padding"/>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label><?php _e("Cost per item"); ?></label>
                                            <small class="text-muted d-block mb-2"><?php _e("Customers won’t see this"); ?></small>

                                            <div class="input-group mb-3 prepend-transparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-muted"><?php _e("BGN"); ?></span>
                                                </div>
                                                <input type="text" class="form-control" value="0.00">
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                    <label class="custom-control-label" for="customCheck1"><?php _e("Charge tax on this product"); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong><?php _e("Inventory"); ?></strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label><?php _e("SKU (Stock Keeping Unit)"); ?></label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Barcode (ISBN, UPC, GTIN, etc.)"); ?></label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2" checked="">
                                                    <label class="custom-control-label" for="customCheck2"><?php _e("Track quantity"); ?></label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck3">
                                                    <label class="custom-control-label" for="customCheck3"><?php _e("Continue selling when out of stock"); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="thin no-padding"/>

                                    <h6><strong><?php _e("Quantity"); ?></strong></h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Available"); ?></label>
                                                <div class="input-group mb-1 append-transparent input-group-quantity">
                                                    <input type="text" class="form-control" value="0"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text plus-minus-holder">
                                                            <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                            <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <small class="text-muted"><?php _e("How many products you have"); ?></small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Max quantity per order"); ?></label>
                                                <div class="input-group mb-1 append-transparent input-group-quantity">
                                                    <input type="text" class="form-control" value="" placeholder="No limit"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text plus-minus-holder">
                                                            <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                            <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><?php _e("How many products can be ordered at once"); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong><?php _e("Shipping"); ?></strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck4" checked="">
                                                    <label class="custom-control-label" for="customCheck4"><?php _e("This is a physical product"); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="thin no-padding"/>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block"><?php _e("Free Shipping"); ?></label>
                                                <div class="custom-control custom-radio d-inline-block mr-3">
                                                    <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio1"><?php _e("Yes"); ?></label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline-block mr-3">
                                                    <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" checked="">
                                                    <label class="custom-control-label" for="customRadio2"><?php _e("No"); ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Fixed Cost"); ?></label>
                                                <div class="input-group mb-3 prepend-transparent append-transparent input-group-quantity">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-muted"><?php _e("BGN"); ?></span>
                                                    </div>
                                                    <input type="text" class="form-control" value="1.00">
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

                                    <h6><strong><?php _e("Weight"); ?></strong></h6>
                                    <p><?php _e("Used to calculate shipping rates at checkout and label prices during fulfillment"); ?>.</p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php _e("Weight"); ?></label>
                                                <div class="input-group mb-3 append-transparent">
                                                    <input type="text" class="form-control" value="3.0">
                                                    <div class="input-group-append">
                                                        <div style="width:70px;">
                                                            <select class="selectpicker" data-width="100%">
                                                                <option><?php _e("kg"); ?></option>
                                                                <option><?php _e("lb"); ?></option>
                                                                <option><?php _e("oz"); ?></option>
                                                                <option><?php _e("g"); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 d-flex align-items-center">
                                            <a href="javascript:;" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#advandec-weight-settings"><?php _e("Show advanced weight settings"); ?></a>
                                        </div>
                                    </div>

                                    <div class="collapse" id="advandec-weight-settings">
                                        <hr class="thin no-padding"/>

                                        <h6><strong><?php _e("Advanced product shipping settings"); ?></strong></h6>

                                        <div class="row">
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label><?php _e("Weight"); ?></label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label><?php _e("Width"); ?></label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label><?php _e("Height"); ?></label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label><?php _e("Depth"); ?></label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck4"/>
                                                        <label class="custom-control-label" for="customCheck4"><?php _e("Show parameters in checkout page"); ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row copyright mt-3">
        <div class="col-12">
            <p class="text-muted text-center small">Open-source website builder and CMS Microweber 2020. Version: 1.18</p>
        </div>
    </div>
</main>


<?php include('partials/footer.php'); ?>
