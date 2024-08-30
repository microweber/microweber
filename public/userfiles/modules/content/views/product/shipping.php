<div class="card">
    <div class="card-body mb-3" x-data="{ physicalProduct: <?php if ($contentData['physical_product'] == 1):?> true <?php else: ?> false <?php endif;?>  }">
        <div class="card-header no-border mt-3">
            <label class="form-label font-weight-bold"><?php _e("Shipping"); ?></label>
        </div>

        <div>

            <div class="row py-0">
                <div class="col-md-12 ps-md-0">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox my-2">
                            <input x-model="physicalProduct" type="checkbox" class="form-check-input" id="customCheck4"
                                   name="content_data[physical_product]" value="1">
                            <label class="custom-control-label" for="customCheck4"><?php _e("This is a physical product"); ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="physicalProduct">
                <div class="row py-0">
                    <div class="col-md-6 ps-md-0">
                        <div class="form-group">
                            <label class="form-label"><?php _e("Fixed Cost"); ?></label>
                            <small class="text-muted d-block mb-3"><?php _e("Used to set your shipping price at checkout and label prices during fulfillment."); ?></small>
                            <div class="input-group mb-3 prepend-transparent">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-muted h-100"><?php echo get_currency_code(); ?></span>
                                </div>
                                <input type="text" class="form-control" name="content_data[shipping_fixed_cost]" value="<?php echo $contentData['shipping_fixed_cost']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><?php _e("Weight"); ?></label>
                            <small class="text-muted d-block mb-3"><?php _e("Used to calculate shipping rates at checkout and label prices during fulfillment."); ?></small>
                            <div class="form-group">
                                <div class="input-group mb-3 append-transparent">
                                    <input type="text" class="form-control" name="content_data[weight]" value="<?php echo $contentData['weight']; ?>">
                                    <div class="input-group-append">
                            <span style="width:70px;">
                                <select class="form-select h-100" name="content_data[weight_type]" data-width="100%">
                                    <option value="kg" <?php if ($contentData['weight_type']=='kg'):?>selected="selected"<?php endif; ?>><?php _e("kg"); ?></option>
                                    <option value="lb" <?php if ($contentData['weight_type']=='lb'):?>selected="selected"<?php endif; ?>><?php _e("lb"); ?></option>
                                    <option value="oz" <?php if ($contentData['weight_type']=='oz'):?>selected="selected"<?php endif; ?>><?php _e("oz"); ?></option>
                                    <option value="g" <?php if ($contentData['weight_type']=='g'):?>selected="selected"<?php endif; ?>><?php _e("g"); ?></option>
                                </select>
                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-0">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label d-block mb-3"><?php _e("Free Shipping"); ?></label>
                            <div class="custom-control custom-radio d-inline-block mr-3">
                                <input type="radio" id="customRadio1" class="form-check-input" name="content_data[free_shipping]" value="1" <?php if ($contentData['free_shipping']==1):?>checked="checked"<?php endif; ?>>
                                <label class="custom-control-label" for="customRadio1"><?php _e("Yes"); ?></label>
                            </div>
                            <div class="custom-control custom-radio d-inline-block mr-3">
                                <input type="radio" id="customRadio2" class="form-check-input" name="content_data[free_shipping]" value="0" <?php if ($contentData['free_shipping']==0):?>checked="checked"<?php endif; ?>>
                                <label class="custom-control-label" for="customRadio2"><?php _e("No"); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-link ps-4" data-bs-toggle="collapse" data-bs-target="#advanced-weight-settings"><?php _e("Show advanced weight settings"); ?></a>

                <div class="row collapse" id="advanced-weight-settings">
                    <label class="form-label ps-0"><?php _e("Advanced"); ?></label>
                    <small class="text-muted d-block mb-3 ps-0"><?php _e("Advanced product shipping settings."); ?></small>

                    <div class="row p-0">
                        <div class="col-lg-3 col-xl">
                            <div class="form-group">
                                <label><?php _e("Weight"); ?></label>
                                <input type="number" name="content_data[weight]" class="form-control" value="<?php echo $contentData['weight']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl">
                            <div class="form-group">
                                <label><?php _e("Width"); ?></label>
                                <input type="number" name="content_data[width]" class="form-control" value="<?php echo $contentData['width']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl">
                            <div class="form-group">
                                <label><?php _e("Height"); ?></label>
                                <input type="number" name="content_data[height]" class="form-control" value="<?php echo $contentData['height']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl">
                            <div class="form-group">
                                <label><?php _e("Depth"); ?></label>
                                <input type="number" name="content_data[depth]" class="form-control" value="<?php echo $contentData['depth']; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row p-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox my-2">
                                    <input type="checkbox" class="form-check-input" id="show-params-checkout-page" name="content_data[params_in_checkout]" value="1" <?php if ($contentData['params_in_checkout']==1):?>checked="checked"<?php endif; ?> />
                                    <label class="custom-control-label" for="show-params-checkout-page"><?php _e("Show parameters in checkout page"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
