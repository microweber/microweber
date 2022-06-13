<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong><?php _e("Search engine"); ?></strong></h6>
        <a href="javascript:;" class="btn btn-link" data-bs-toggle="collapse" data-target="#seo-settings"><?php _e("Show SEO settings"); ?></a>
    </div>

    <div class="card-body py-0">
        <div class="collapse" id="seo-settings">
            <p><?php _e("Add a title and description to see how this product might appear in a search engine listing"); ?></p>

            <hr class="thin no-padding"/>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group js-count-letters">
                        <div class="d-flex justify-content-between">
                            <label><?php _e("Meta title"); ?></label>
                            <span class="text-muted"><span class="js-typed-letters">0</span> <?php _e("of 70 characters used"); ?></span>
                        </div>
                        <input type="text" class="form-control" value="">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group js-count-letters">
                        <div class="d-flex justify-content-between">
                            <label><?php _e("Meta descriptions"); ?></label>
                            <span class="text-muted"><span class="js-typed-letters">0</span> <?php _e("of 70 characters used"); ?></span>
                        </div>
                        <textarea class="form-control"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php _e("Meta keywords"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Separate keywords with a comma and space"); ?></small>
                        <textarea class="form-control" placeholder="<?php _e("e.g. Summer, Ice cream, Beach"); ?>"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php _e("OG Images"); ?></label>
                        <small class="text-muted d-block mb-2">
                            <?php _e("Those images will be shown as a post image at facebook shares"); ?>.<br />
                            <?php _e("If you want to attach og images, you must upload them to gallery from Add media"); ?>.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong><?php _e("Advanced settings"); ?></strong></h6>
        <a href="javascript:;" class="btn btn-link" data-bs-toggle="collapse" data-target="#advenced-settings"><?php _e("Show advanced settings"); ?></a>
    </div>

    <div class="card-body py-0">
        <div class="collapse" id="advenced-settings">
            <p><?php _e("Use the advanced settings to customize your blog post"); ?></p>

            <hr class="thin no-padding"/>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php _e("Redirect to URL"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("If set this, the user will be redirected to the new URL when visits the page"); ?></small>
                        <input type="text" class="form-control" value="">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label><?php _e("Require login"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("If set to ON, this page will require login from a registered user in order to be opened"); ?></small>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2"><?php _e("ON / OFF"); ?></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" value="Administrator">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
