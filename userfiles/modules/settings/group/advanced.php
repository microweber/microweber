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

<h1 class="main-pages-title"><?php _e('Advanced'); ?></h1>

<div class="<?php print $config['module_class'] ?>">

    <div class="card  mb-1">
        <div class="card-body ">
            <div class="row">

                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Custom tags"); ?></h5>
                    <small class="advanced-settings-small-helper text-muted"><?php _e('Allows you to insert custom code in the website header and footer. For e.g. Live chat, Google Ads and others.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt ">
                        <div class="card-body ">
                            <div class="row">
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

    <div class="card my-5">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 mb-xl-0 mb-3">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Development settings"); ?></h5>
                    <small class="advanced-settings-small-helper text-muted"><?php _e('If you are developer you will find great tools to make your website.'); ?></small>
                </div>
                <div class="col-xl-9">
                    <div class="card bg-azure-lt  mb-1">
                        <div class=" ">
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
    </div>

   <div class="card my-5">
       <div class="card-body">
           <div class="row">
               <div class="col-xl-3 mb-xl-0 mb-3">
                   <h5 class="font-weight-bold settings-title-inside"><?php _e("Other settings"); ?></h5>
                   <small class="advanced-settings-small-helper text-muted"><?php _e('Other settings for your website.'); ?></small>
               </div>
              <div class="col-xl-9">
                   <div class="card bg-azure-lt">
                      <div class="row">
                          <div class="d-flex flex-wrap mx-auto justify-content-center mt-3">
                              <div class="col-xl-6 col-12 my-xl-0 my-3">
                                  <div class="card mx-2 h-100">
                                      <div class="card-body">
                                              <label class="form-label"><?php _e("Internal Settings"); ?></label>
                                              <small class="advanced-settings-small-helper d-block mb-2"><?php _e('Internal settings for developers'); ?></small>
                                              <a class="btn btn-sm btn-outline-primary" href="javascript:settings_load_module('Internal settings','settings/group/internal')"><?php _e("Internal settings"); ?></a>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-xl-6 col-12 my-xl-0 my-3">
                                  <div class="card mx-2 h-100">
                                      <div class="card-body">
                                              <label class="form-label"><?php _e("Live Edit settings"); ?></label>
                                              <small class="advanced-settings-small-helper text-muted d-block mb-2"><?php _e('Configure Live Edit settings'); ?></small>


                                              <a class="btn btn-sm btn-outline-primary mb-2" href="javascript:settings_load_module('Live Edit settings','settings/group/live_edit')"><?php _e("Live Edit settings"); ?></a>
                                              <a class="btn btn-sm btn-outline-primary" href="javascript:settings_load_module('Live Edit elements','admin/elements')"><?php _e("Live Edit elements"); ?></a>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex flex-wrap mx-auto justify-content-center mt-3">
                              <div class="col-xl-6 col-12 my-xl-0 my-3">
                                  <div class="card mx-2 h-100">
                                      <div class="card-body">
                                              <label class="form-label"><?php _e("Setup statistics"); ?></label>
                                              <small class="advanced-settings-small-helper d-block mb-2"><?php _e('Configure website statistics'); ?></small>
                                              <a class="btn btn-sm btn-outline-primary" href="javascript:settings_load_module('Statistics settings','site_stats/settings')"><?php _e("Statistics settings"); ?></a>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-xl-6 col-12 my-xl-0 my-3">
                                  <div class="card mx-2 h-100">
                                      <div class="card-body">
                                              <label class="form-label"><?php _e("Other"); ?></label>
                                              <small class="advanced-settings-small-helper d-block mb-2"><?php _e('Other settings'); ?></small>
                                              <a class="btn btn-sm btn-outline-primary" href="javascript:reloadMwDB();"><?php _e("Reload Database"); ?></a>

                                              <?php if (is_module('shop') and get_option('shop_disabled', 'website') == "y") { ?>
                                                  <a class="btn btn-sm btn-outline-primary" href="javascript:settings_load_module('Shop settings','shop/orders/settings/enable_disable_shop')"><?php _e("Shop settings"); ?></a>
                                              <?php } ?>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="d-flex flex-wrap mx-auto justify-content-start mt-3">
                              <div class="col-xl-6 col-12 my-xl-0 my-3">
                                  <div class="card mx-2 h-100">
                                      <div class="card-body">
                                          <label class="form-label"><?php _e("Experimental"); ?></label>
                                          <small class="advanced-settings-small-helper d-block mb-2"><?php _e("Experimental settings for developers"); ?></small>
                                          <a class="btn btn-sm btn-outline-primary" href="javascript:settings_load_module('Experimental settings','settings/group/experimental')"><?php _e("Experimental settings"); ?></a>
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
               </div>
           </div>
       </div>
   </div>
</div>
