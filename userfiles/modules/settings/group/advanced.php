<?php only_admin_access(); ?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_cont_head_change_holder', function () {
            mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
        });
        mw.options.form('.<?php print $config['module_class'] ?>,.mw_adm_robots_txt_change_holder', function () {
            mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
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


</script>


<div class="mw-ui-row admin-section-bar">
    <div class="mw-ui-col">
        <h2>
            <?php _e("Advanced"); ?>
        </h2>
    </div>
</div>


<div class="admin-side-content">
    <div class="<?php print $config['module_class'] ?>">


        <hr>
        Seo
        <ul>


            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('SEO <?php _e("settings"); ?>','settings/group/seo')">
                    SEO <?php _e("settings"); ?>
                </a>


            </li>


            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('<?php _e('Custom head tags'); ?>','settings/group/custom_head_tags')">
                    <?php _e("Custom head tags"); ?>
                </a>


            </li>

            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('Robots.txt <?php _e("file"); ?>','settings/group/custom_head_tags')">
                    Robots.txt <?php _e("file"); ?>
                </a>


            </li>


        </ul>


        <hr>
        System settings
        <ul>


            <li>
                <a class="mw-ui-btn" href="javascript:mw.clear_cache()">
                    <?php _e("Clear cache"); ?>
                </a>
            </li>
            <li><a class="mw-ui-btn" href="javascript:api('mw_post_update'); void(0);">
                    <?php _e("Reload Database"); ?>
                </a></li>

        </ul>


        <hr>
        Other settings
        <ul>


            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('<?php _e("Internal settings"); ?>','settings/group/internal')">
                    <?php _e("Internal settings"); ?>
                </a>
            </li>

            <li>

                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('<?php _e("Live Edit"); ?> <?php _e("settings"); ?>','settings/group/live_edit')">

                    <?php _e("Live Edit"); ?><?php _e("settings"); ?>


                </a>


            </li>

            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('<?php _e("Statistics settings"); ?>','site_stats/settings')">
                    <?php print("Statistics settings"); ?>
                </a>


            </li>
            <li>
                <a class="mw-ui-btn"
                   href="javascript:settings_load_module('<?php _e('Developer tools'); ?>','admin/developer_tools')">
                    <?php _e('Developer tools'); ?>
                </a>


            </li>

        </ul>


    </div>


</div>


