<div class="col-md-3 manage-content-sidebar">
    <div class="card-body mb-3">
        <div class="  pb-0">
            <div class="row">
                <div class="col-12">
                    <strong><?php _e('Visibility'); ?></strong>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12">
                    <div class="form-group">
                        <div class="custom-control custom-radio my-2">
                            <input type="radio" id="customRadio1" name="customRadio" class="form-check-input" checked="">
                            <label class="custom-control-label" for="customRadio1"><?php _e('Visible'); ?></label>
                        </div>
                        <div class="custom-control custom-radio my-2">
                            <input type="radio" id="customRadio2" name="customRadio" class="form-check-input">
                            <label class="custom-control-label" for="customRadio2"><?php _e('Hidden'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <a href="#" class="btn btn-link px-0"><?php _e('Set a specific publish date'); ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body mb-3">
        <div class=" ">
            <div class="row">
                <div class="col-12">
                    <strong>Categories</strong>
                    <a href="#" class="btn btn-link float-right py-1 px-0"><?php _e('Manage'); ?></a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-shopping"></i> <?php _e('Shop'); ?></button>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-folder"></i> <?php _e('Category'); ?></button>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-folder"></i> <?php _e('Accessoaries'); ?></button>
                </div>
            </div>



            <div class="row mb-3">
                <div class="col-12">
                    <small class="text-muted"><?php _e('Want to add the product in more categories'); ?>?</small>
                    <br />
                    <button type="button" class="btn btn-outline-primary btn-sm   my-3"><?php _e('Add to category'); ?></button>
                    <br />
                    TREE
                </div>
            </div>
        </div>
    </div>

    <div class="card-body mb-3">
        <div class=" ">
            <div class="row mb-3">
                <div class="col-12">
                    <strong><?php _e('Tags'); ?></strong>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="text" placeholder="<?php _e('healthy, beauty, travel'); ?>" />
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> <?php _e('car'); ?></span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> <?php _e('someother'); ?></span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>

                    <div class="btn-group tag tag-xs mb-2 mr-1">
                        <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> <?php _e('topsellproduct'); ?></span>
                        <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
