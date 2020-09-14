
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
                    <h5><strong>L / Red</strong></h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-success">Save</button>
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
                                            <p class="mb-0">New product</p>
                                            <small class="text-muted">9 variants</small>
                                            <a href="#" class="btn btn-sm btn-link px-0">Back to products</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong>Variants</strong></h6>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-link px-0">Delete</a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="variants-select">
                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0">L / Red</p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0">L / Blue</p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0">L / Yellow</p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0">M / Red</p>
                                        </div>

                                        <div class="variant">
                                            <div class="img-circle-holder border-radius-10 border-silver w-40 mr-3">
                                                <img src="assets/img/no-image.jpg">
                                            </div>

                                            <p class="mb-0">M / Blue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong>Options</strong></h6>
                                    <div>
                                        <select class="selectpicker" data-title="Add media from" data-style="btn-sm" data-width="auto">
                                            <option>Add image from URL</option>
                                            <option>Browse uploaded</option>
                                            <option>Add file</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Size</label>
                                                <input type="text" class="form-control" placeholder="L">
                                            </div>
                                            <div class="form-group">
                                                <label>Color</label>
                                                <input type="text" class="form-control" placeholder="Red">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="dropable-zone small-zone square-zone w-100">
                                                <div class="holder">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm text-primary mb-2">Add file</button>
                                                    <p>or drop file to upload</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong>Pricing</strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Price</label>
                                            <div class="input-group mb-3 prepend-transparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-muted">BGN</span>
                                                </div>
                                                <input type="text" class="form-control" value="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price on sale (compare at price)</label>
                                                <div class="input-group mb-3 prepend-transparent append-transparent">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-muted">BGN</span>
                                                    </div>
                                                    <input type="text" class="form-control" value="0.00">
                                                    <div class="input-group-append">
                                                            <span class="input-group-text" data-toggle="tooltip" title=""
                                                                  data-original-title="To put a product on sale, make Compare at price the original price and enter the lower amount into Price."><i class="mdi mdi-help-circle"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="thin no-padding"/>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Cost per item</label>
                                            <small class="text-muted d-block mb-2">Customers won’t see this</small>

                                            <div class="input-group mb-3 prepend-transparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-muted">BGN</span>
                                                </div>
                                                <input type="text" class="form-control" value="0.00">
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" checked="">
                                                    <label class="custom-control-label" for="customCheck1">Charge tax on this product</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong>Inventory</strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>SKU (Stock Keeping Unit)</label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Barcode (ISBN, UPC, GTIN, etc.)</label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2" checked="">
                                                    <label class="custom-control-label" for="customCheck2">Track quantity</label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck3">
                                                    <label class="custom-control-label" for="customCheck3">Continue selling when out of stock</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="thin no-padding"/>

                                    <h6><strong>Quantity</strong></h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Available</label>
                                                <div class="input-group mb-1 append-transparent input-group-quantity">
                                                    <input type="text" class="form-control" value="0"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text plus-minus-holder">
                                                            <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                            <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <small class="text-muted">How many products you have</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Max quantity per order</label>
                                                <div class="input-group mb-1 append-transparent input-group-quantity">
                                                    <input type="text" class="form-control" value="" placeholder="No limit"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text plus-minus-holder">
                                                            <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                            <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted">How many products can be ordered at once</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card style-1 mb-3">
                                <div class="card-header no-border">
                                    <h6><strong>Shipping</strong></h6>
                                </div>

                                <div class="card-body pt-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck4" checked="">
                                                    <label class="custom-control-label" for="customCheck4">This is a physical product</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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

                                    <h6><strong>Weight</strong></h6>
                                    <p>Used to calculate shipping rates at checkout and label prices during fulfillment.</p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Weight</label>
                                                <div class="input-group mb-3 append-transparent">
                                                    <input type="text" class="form-control" value="3.0">
                                                    <div class="input-group-append">
                                                        <div style="width:70px;">
                                                            <select class="selectpicker" data-width="100%">
                                                                <option>kg</option>
                                                                <option>lb</option>
                                                                <option>oz</option>
                                                                <option>g</option>
                                                            </select>
                                                        </div>
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
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label>Width</label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label>Height</label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xl">
                                                <div class="form-group">
                                                    <label>Depth</label>
                                                    <input type="number" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck4"/>
                                                        <label class="custom-control-label" for="customCheck4">Show parameters in checkout page</label>
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