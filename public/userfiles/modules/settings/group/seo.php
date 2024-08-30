<?php must_have_access(); ?>

<h1 class="main-pages-title"><?php _e('SEO'); ?></h1>

<div class="<?php print $config['module_class'] ?>">

    <div class="card mb-7">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("SEO Settings"); ?></h5>
                    <small class="advanced-settings-small-helper text-muted"><?php _e('Make these settings to get the best results when finding your website.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-12">
                                    <module type="settings/group/seo_tab_content"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
