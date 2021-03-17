<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("User settings updated"); ?>.");
        });
    });
</script>

<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-header px-0">
        <h5><i class="mdi mdi-shield-edit-outline text-primary mr-3"></i> <strong><?php _e('Privacy policy'); ?></strong></h5>
        <div>

        </div>
    </div>

    <div class="card-body pt-3 px-0">
        <div class="row">
            <div class="col-md-3">
                <h5 class="font-weight-bold"><?php _e('Privacy policy settings'); ?></h5>
                <small class="text-muted d-block mb-3">
                    <?php _e('A Privacy Policy is a legal agreement that explains what kinds of personal information you gather from website visitors, how you use this information, and how you keep it safe. Examples of personal information might include: Names. Dates of birth.'); ?>
                </small>

                <small class="text-muted d-block">
                    <?php _e('The General Data Protection Regulation (EU) 2016/679 (GDPR) is a regulation in EU law on data protection and privacy in the European Union (EU) and the European Economic Area (EEA).'); ?>
                </small>
            </div>

            <div class="col-md-9">
                <div class="card bg-light style-1 mb-3">
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="control-label"><?php _e("Users must agree to the Terms and Conditions"); ?></label>
                                    <small class="text-muted d-block mb-2"><?php _e('Should your users agree to the terms of use of the website'); ?></small>
                                </div>

                                <module type="users/terms/set_for_module" for_module="users"/>
                                <module type="users/terms/edit"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php event_trigger('website.privacy_settings') ?>