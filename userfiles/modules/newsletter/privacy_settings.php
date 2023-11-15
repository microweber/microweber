<?php must_have_access(); ?>
<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-body pt-3 px-0">
        <?php if (!isset($params['data-no-hr'])) : ?>
            <hr class="thin mt-0 mb-5"/>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-3">
                <h5 class="font-weight-bold">Newsletter settings</h5>
                <small class="text-muted">Make settings for your contact form (there may be more than one) related to the conditions for sending data and using the website.</small>
            </div>
            <div class="col-md-9">
                <div class="card bg-light style-1 mb-3">
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-12">

                                <div class="form-group mb-3">
                                    <label class="control-label">Users must agree to the terms and conditions</label>
                                    <small class="text-muted d-block mb-2">If the user does not agree to the terms, he will not be able to use the newsletter</small>
                                </div>

                                <module type="users/terms/set_for_module" for_module="newsletter" />

                                <div class="form-group mb-3">
                                    <label class="control-label d-block">Want to view and edit the text and the page?</label>
                                    <button class="btn btn-sm btn-outline-primary mt-2" data-toggle="collapse" data-target="#newsletter-settings">Edit the text and URL</button>
                                </div>

                                <div class="collapse" id="newsletter-settings">
                                    <module type="users/terms/edit" terms-group="newsletter"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>