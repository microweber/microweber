<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("User settings updated"); ?>.");
        });
    });
</script>

<h1 class="main-pages-title"><?php _e('Privacy policy'); ?></h1>


<div class="card mb-7">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-3 mb-xl-0 mb-3">
                <h5 class="font-weight-bold settings-title-inside"><?php _e('Privacy policy settings'); ?></h5>
                <small class="text-muted d-block mb-3">
                    <?php _e('A Privacy Policy is a legal agreement that explains what kinds of personal information you gather from website visitors, how you use this information, and how you keep it safe. Examples of personal information might include: Names. Dates of birth.'); ?>
                </small>

                <small class="text-muted d-block">
                    <?php _e('The General Data Protection Regulation (EU) 2016/679 (GDPR) is a regulation in EU law on data protection and privacy in the European Union (EU) and the European Economic Area (EEA).'); ?>
                </small>
            </div>

            <div class="col-xl-9">
                <div class="card bg-azure-lt ">
                    <div class=" ">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label"><?php _e("Users must agree to the Terms and Conditions"); ?></label>
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
