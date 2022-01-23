<?php must_have_access(); ?>
<script>
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_cont_head_change_holder', function () {
            mw.notification.success("<?php _ejs("Advanced settings updated"); ?>.");
        });
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_robots_txt_change_holder', function () {
            mw.notification.success("<?php _ejs("Advanced settings updated"); ?>.");
        });
    });

    function settings_load_module(title, module) {
        $("#mw_admin_edit_settings_load_module_popup").remove();
        mw_admin_edit_settings_load_module = mw.dialog({
            content: '<div id="mw_admin_edit_settings_load_module"></div>',
            title: title,
            id: 'mw_admin_edit_settings_load_module_popup',
        });
        var params = {}
        mw.load_module(module, '#mw_admin_edit_settings_load_module', null, params);
    }

    function reloadMwDB() {
        $.post( mw.settings.api_url + 'mw_post_update' );
        mw.notification.success("<?php _ejs("The DB was reloaded"); ?>.");
    }
</script>

<div class="<?php print $config['module_class'] ?>">
    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-header px-0">
            <h5><i class="mdi mdi-cog text-primary mr-3"></i> <strong><?php _e("Advanced"); ?></strong></h5>
            <div>

            </div>
        </div>

        <div class="card-body pt-3 pb-0 px-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("SEO Settings"); ?></h5>
                    <small class="text-muted"><?php _e('Make these settings to get the best results when finding your website.'); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-3">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <module type="settings/group/seo"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="thin"/>

                    <div class="card bg-light style-1 mb-1">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <module type="settings/group/custom_head_tags"/>
                                    <module type="settings/group/custom_footer_tags"/>
                                    <module type="settings/group/robots_txt"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="thin mx-4"/>

        <div class="card-body pt-2 pb-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Development settings"); ?></h5>
                    <small class="text-muted"><?php _e('If you are developer you will find great tools to make your website.'); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-1">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <module type="admin/developer_tools"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="thin mx-4"/>

        <div class="card-body pt-2 pb-0">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="font-weight-bold"><?php _e("Other settings"); ?></h5>
                    <small class="text-muted"><?php _e('Other settings for your website.'); ?></small>
                </div>
                <div class="col-md-9">
                    <div class="card bg-light style-1 mb-1">
                        <div class="card-body pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Internal Settings"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Internal settings for developers'); ?></small>
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Internal settings','settings/group/internal')"><?php _e("Internal settings"); ?></a>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Live Edit settings"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Configure Live Edit settings'); ?></small>


                                        <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Live Edit settings','settings/group/live_edit')"><?php _e("Live Edit settings"); ?></a>
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Live Edit elements','admin/elements')"><?php _e("Live Edit elements"); ?></a>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Setup statistics"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Configure website statistics'); ?></small>
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Statistics settings','site_stats/settings')"><?php _e("Statistics settings"); ?></a>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Other"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e('Other settings'); ?></small>
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:reloadMwDB();"><?php _e("Reload Database"); ?></a>

                                        <?php if (is_module('shop') and get_option('shop_disabled', 'website') == "y") { ?>
                                            <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Shop settings','shop/orders/settings/enable_disable_shop')"><?php _e("Shop settings"); ?></a>
                                        <?php } ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><?php _e("Experimental"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Experimental settings for developers"); ?></small>
                                        <a class="btn btn-outline-primary btn-sm" href="javascript:settings_load_module('Experimental settings','settings/group/experimental')"><?php _e("Experimental settings"); ?></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


