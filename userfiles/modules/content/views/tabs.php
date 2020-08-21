<?php if (!isset($data)) {
    $data = $params;
}

$custom_tabs = mw()->module_manager->ui('content.edit.tabs');
?>

<script>
    mw.lib.require('colorpicker');
</script>


<div id="settings-tabs">
    <div class="card style-1 mb-3 images">
        <div class="card-header no-border" id="post-media-card-header">
            <h6><strong><?php _e('Pictures'); ?></strong></h6>
            <div class="post-media-type-holder">
                <select class="selectpicker btn-as-link" data-title="Add media from" data-style="btn-sm" data-width="auto" id="mw-admin-post-media-type">
                    <option value="url">Add image from URL</option>
                    <option value="server">Browse uploaded</option>
                    <option value="library">Select from Unsplash</option>
                    <option value="file">Upload file</option>
                </select>
            </div>
        </div>
        <div class="card-body pt-3">
            <module id="edit-post-gallery-main" type="pictures/admin" class="pictures-admin-content-type-<?php print trim($data['content_type']) ?>" for="content" content_type="<?php print trim($data['content_type']) ?>" for-id="<?php print $data['id']; ?>"/>
        </div>
    </div>

    <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>

    <div class="bg-danger p-3">
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
                                    <span class="input-group-text" data-toggle="tooltip" title="" data-original-title="To put a product on sale, make Compare at price the original price and enter the lower amount into Price."><i class="mdi mdi-help-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="thin no-padding">

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

                <hr class="thin no-padding">

                <h6><strong>Quantity</strong></h6>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Available</label>
                            <div class="input-group mb-1 append-transparent input-group-quantity">
                                <input type="text" class="form-control" value="0">
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
                                <input type="text" class="form-control" value="" placeholder="No limit">
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

                <hr class="thin no-padding">

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
                                        <span style="width:70px;">
                                            <div class="dropdown bootstrap-select" style="width: 100%;"><select class="selectpicker" data-width="100%" tabindex="-98">
                                                <option>kg</option>
                                                <option>lb</option>
                                                <option>oz</option>
                                                <option>g</option>
                                            </select><button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" role="combobox" aria-owns="bs-select-2" aria-haspopup="listbox" aria-expanded="false" title="kg"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">kg</div></div> </div></button><div class="dropdown-menu "><div class="inner show" role="listbox" id="bs-select-2" tabindex="-1"><ul class="dropdown-menu inner show" role="presentation"></ul></div></div></div>
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
                    <hr class="thin no-padding">

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
                                    <input type="checkbox" class="custom-control-input" id="customCheck4">
                                    <label class="custom-control-label" for="customCheck4">Show parameters in checkout page</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card style-1 mb-3">
            <div class="card-header no-border">
                <h6><strong>Variants</strong></h6>
            </div>

            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-product-has-variants" id="the-product-has-variants" checked="">
                                <label class="custom-control-label" for="the-product-has-variants">This product has multiple options, like different sizes or colors</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="js-product-variants">
                    <hr class="thin no-padding">

                    <h6 class="text-uppercase mb-3"><strong>Create an option</strong></h6>

                    <div class="options">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h6 class="pb-1"><strong>Option 1</strong></h6>
                                    <div>
                                        <div class="dropdown bootstrap-select" style="width: 100%;"><select class="selectpicker" data-title="Option type" data-width="100%" tabindex="-98"><option class="bs-title-option" value=""></option>
                                                <option selected="">Size</option>
                                                <option>Color</option>
                                                <option>Material</option>
                                                <option>Title</option>
                                            </select><button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" role="combobox" aria-owns="bs-select-3" aria-haspopup="listbox" aria-expanded="false" title="Size"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">Size</div></div> </div></button><div class="dropdown-menu "><div class="inner show" role="listbox" id="bs-select-3" tabindex="-1"><ul class="dropdown-menu inner show" role="presentation"></ul></div></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="text-right">
                                    <a href="#" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</a>
                                    <a href="#" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2">Remove</a>
                                </div>
                                <div class="form-group">
                                    <div class="bootstrap-tagsinput"><div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">L</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">M</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">XL</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <input type="text" placeholder="Separate options with a comma"></div><input type="text" data-role="tagsinput" value="L,M,XL" placeholder="Separate options with a comma" style="display: none;">
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="thin">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h6 class="pb-1"><strong>Option 2</strong></h6>
                                    <div>
                                        <div class="dropdown bootstrap-select" style="width: 100%;"><select class="selectpicker" data-title="Option type" data-width="100%" tabindex="-98"><option class="bs-title-option" value=""></option>
                                                <option>Size</option>
                                                <option selected="">Color</option>
                                                <option>Material</option>
                                                <option>Title</option>
                                            </select><button type="button" class="btn dropdown-toggle btn-light" data-toggle="dropdown" role="combobox" aria-owns="bs-select-4" aria-haspopup="listbox" aria-expanded="false" title="Color"><div class="filter-option"><div class="filter-option-inner"><div class="filter-option-inner-inner">Color</div></div> </div></button><div class="dropdown-menu "><div class="inner show" role="listbox" id="bs-select-4" tabindex="-1"><ul class="dropdown-menu inner show" role="presentation"></ul></div></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="text-right">
                                    <a href="#" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</a>
                                    <a href="#" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2">Remove</a>
                                </div>
                                <div class="form-group">
                                    <div class="bootstrap-tagsinput"><div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">Red</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">Blue</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <div class="btn-group tag tag-xs mb-2 mr-1"><span class="btn-sm icon-left no-hover btn btn-primary">Yellow</span><button type="button" data-role="remove" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button></div> <input type="text" placeholder="Separate options with a comma"></div><input type="text" data-role="tagsinput" value="Red,Blue,Yellow" placeholder="Separate options with a comma" style="display: none;">
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="thin">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary text-dark">Add another option</button>

                    <hr class="thin no-padding">

                    <h6 class="text-uppercase mb-3"><strong>Preview</strong></h6>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" class="border-0">Variant</th>
                                <th scope="col" class="border-0">Price</th>
                                <th scope="col" class="border-0">Quantity</th>
                                <th scope="col" class="border-0">SKU</th>
                                <th scope="col" class="border-0">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row" style="vertical-align: middle;">
                                    <span>L / Red</span>
                                </th>
                                <td>
                                    <div class="input-group prepend-transparent m-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group append-transparent input-group-quantity m-0">
                                        <input type="text" class="form-control" value="0">
                                        <div class="input-group-append">
                                            <div class="input-group-text plus-minus-holder">
                                                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group m-0">
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row" style="vertical-align: middle;">
                                    <span>L / Red</span>
                                </th>
                                <td>
                                    <div class="input-group prepend-transparent m-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group append-transparent input-group-quantity m-0">
                                        <input type="text" class="form-control" value="0">
                                        <div class="input-group-append">
                                            <div class="input-group-text plus-minus-holder">
                                                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group m-0">
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row" style="vertical-align: middle;">
                                    <span>L / Red</span>
                                </th>
                                <td>
                                    <div class="input-group prepend-transparent m-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-muted">BGN</span>
                                        </div>
                                        <input type="text" class="form-control" value="0.00">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group append-transparent input-group-quantity m-0">
                                        <input type="text" class="form-control" value="0">
                                        <div class="input-group-append">
                                            <div class="input-group-text plus-minus-holder">
                                                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>
                                                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group m-0">
                                        <input type="text" class="form-control" value="">
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-outline-secondary btn-sm">Edit</a>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card style-1 mb-3 fields">
        <div class="card-body pt-3">
            <module type="custom_fields/admin" <?php if (trim($data['content_type']) == 'product'): ?> default-fields="price" <?php endif; ?> content-id="<?php print $data['id'] ?>" suggest-from-related="true" list-preview="true" id="fields_for_post_<?php print $data['id']; ?>"/>
            <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
        </div>
    </div>

    <?php if (trim($data['content_type']) == 'product'): ?>
        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <?php event_trigger('mw_edit_product_admin', $data); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>

    <?php if (!empty($custom_tabs)): ?>
        <?php foreach ($custom_tabs as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <div class="card style-1 mb-3 custom-tabs">
                <div class="card-body pt-3"><?php print $html; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <module type="content/views/advanced_settings" content-id="<?php print $data['id']; ?>" content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"/>
    <?php event_trigger('content/views/advanced_settings', $data); ?>
</div>


<script>
    $(document).ready(function () {
        pick1 = mw.colorPicker({
            element: '.mw-ui-color-picker',
            position: 'bottom-left',
            onchange: function (color) {

            }
        });
    });
</script>
