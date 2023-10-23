<div class="card-body mb-3">
    <div class="card-header no-border">
        <h6><strong>Variants</strong></h6>
    </div>

    <div class=" ">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox my-2">
                        <input type="checkbox" class="form-check-input js-product-has-variants" id="the-product-has-variants" checked="">
                        <label class="custom-control-label" for="the-product-has-variants">This product has multiple options, like different sizes or colors</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-product-variants">


            <h6 class="text-uppercase mb-3"><strong>Create an option</strong></h6>

            <div class="options">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <h6 class="pb-1"><strong>Option 1</strong></h6>
                            <div>
                                <select class="form-select" data-title="Option type" data-width="100%">
                                    <option selected>Size</option>
                                    <option>Color</option>
                                    <option>Material</option>
                                    <option>Title</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="text-end text-right">
                            <a href="#" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</a>
                            <a href="#" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2">Remove</a>
                        </div>
                        <div class="form-group">
                            <input type="text" data-role="tagsinput" value="L,M,XL" placeholder="Separate options with a comma" />
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="thin" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <h6 class="pb-1"><strong>Option 2</strong></h6>
                            <div>
                                <select class="form-select" data-title="Option type" data-width="100%">
                                    <option>Size</option>
                                    <option selected="">Color</option>
                                    <option>Material</option>
                                    <option>Title</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="text-end text-right">
                            <a href="#" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</a>
                            <a href="#" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2">Remove</a>
                        </div>
                        <div class="form-group">
                            <input type="text" data-role="tagsinput" value="Red,Blue,Yellow" placeholder="Separate options with a comma" />
                        </div>
                    </div>
                    <div class="col-12">
                        <hr class="thin" />
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary  ">Add another option</button>



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
                                        <span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
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
                                        <span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
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
                                        <span class="input-group-text text-muted"><?php echo get_currency_code(); ?></span>
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
