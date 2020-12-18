<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_cont_head_change_holder', function () {
            mw.notification.success("<?php _ejs("Advanced settings updated"); ?>.");
        });
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_robots_txt_change_holder', function () {
            mw.notification.success("<?php _ejs("Advanced settings updated"); ?>.");
        });
    });


    function settings_load_module(title, module) {

        $("#mw_admin_edit_settings_load_module_popup").remove()

        mw_admin_edit_settings_load_module = mw.modal({
            content: '<div id="mw_admin_edit_settings_load_module"></div>',
            title: title,
            id: 'mw_admin_edit_settings_load_module_popup'
        });

        var params = {}

        mw.load_module(module, '#mw_admin_edit_settings_load_module', null, params);


    }


    function clearMwCache() {
        mw.clear_cache();
        mw.notification.success("<?php _ejs("The cache was cleared"); ?>.");
    }
    function reloadMwDB() {
        api('mw_post_update');
        mw.notification.success("<?php _ejs("The DB was reloaded"); ?>.");
    }

</script>


<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2>
            <?php _e("Advanced"); ?>
        </h2>
    </div>
</div>

<div class="admin-side-content">
    <div class="admin-side-content">
        <div class="<?php print $config['module_class'] ?> mw-advanced-settings">
            <div class="mw-ui-row">
                <div class="mw-ui-col">
                    <span class="box-title"><i class="mw-icon-web-search"></i><br/>SEO</span>
                    <ul>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('SEO settings','settings/group/seo')">SEO <?php _e("settings"); ?></a>
                        </li>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Custom head tags','settings/group/custom_head_tags')"><?php _e("Custom head tags"); ?></a>
                        </li>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Custom footer tags','settings/group/custom_footer_tags')"><?php _e("Custom footer tags"); ?></a>
                        </li>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Robots.txt file','settings/group/robots_txt')">Robots.txt <?php _e("file"); ?></a>
                        </li>
                    </ul>
                </div>

                <div class="mw-ui-col">
                    <span class="box-title"><i class="mai-code"></i><br/><?php _e('Development settings'); ?></span>
                    <ul>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Developer tools','admin/developer_tools')"><?php _e('Developer tools'); ?></a>
                        </li>
                        <li><a class="mw-ui-btn" href="javascript:clearMwCache();"><?php _e("Clear cache"); ?></a></li>
                        <li><a class="mw-ui-btn" href="javascript:reloadMwDB();"><?php _e("Reload Database"); ?></a>
                        </li>
                    </ul>
                </div>

                <div class="mw-ui-col">
                    <span class="box-title"><i class="mai-setting2"></i><br/><?php _e("Other settings"); ?></span>
                    <ul>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Internal settings','settings/group/internal')"><?php _e("Internal settings"); ?></a>
                        </li>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Live Edit settings','settings/group/live_edit')"><?php _e("Live Edit settings"); ?></a>
                        </li>
                        <li><a class="mw-ui-btn"
                               href="javascript:settings_load_module('Statistics settings','site_stats/settings')"><?php _e("Statistics settings"); ?></a>
                        </li>
                        <?php if (is_module('shop') and get_option('shop_disabled', 'website') == "y") { ?>

                            <li><a class="mw-ui-btn"
                                   href="javascript:settings_load_module('Shop settings','shop/orders/settings/enable_disable_shop')"><?php _e("Shop settings"); ?></a>
                            </li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


