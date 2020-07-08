<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("User settings updated"); ?>.");
        });
    });
</script>

<div class="card bg-none style-1 mb-0">
    <div class="card-header">
        <h5><i class="mdi mdi-shield-edit-outline text-primary mr-3"></i> <strong>Privacy policy</strong></h5>
        <div>

        </div>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-4">
                <h5 class="font-weight-bold">Privacy policy settings</h5>
                <small class="text-muted d-block mb-3">A Privacy Policy is a legal agreement
                    that explains what kinds of personal
                    information you gather from website
                    visitors, how you use this information,
                    and how you keep it safe. Examples of
                    personal information might include:
                    Names. Dates of birth.
                </small>

                <small class="text-muted d-block">The General Data Protection Regulation
                    (EU) 2016/679 (GDPR) is a regulation in
                    EU law on data protection and privacy in
                    the European Union (EU) and the
                    European Economic Area (EEA).
                    It also addresses the transfer of personal
                    data outside the EU and EEA areas.
                </small>
            </div>

            <div class="col-md-8">
                <div class="card bg-light style-1 mb-3">
                    <div class="card-body pt-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="control-label"><?php _e("Users must agree to the Terms and Conditions"); ?></label>
                                    <small class="text-muted d-block mb-2">Should your users agree to the terms of use of the website</small>
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