<?php
only_admin_access();
/**
 * Dev: Bozhidar Slaveykov, Nikolay Radanov
 * Emai: bobi@microweber.com, niki@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 * Update: 04.03.2021
 * Time: 10:25 AM
 */
?>

<script>
    function mw_liveEdit_language_settings_open_modal(product_id = false) {
        var data = {};
        data.template = 'mw_default';
        addCartModal = mw.tools.open_module_modal('multilanguage/language_settings', data, {
            overlay: true,
            skin: 'simple',
            title: 'Language settings'
        })
    }

    $(function () {
        add_order_tabs = mw.tabs({
            nav: '#mw-language-settings #nav-language-settings a',
            tabs: '#mw-language-settings .mw-ui-box-content'
        });

        thismodal.resize(750, "auto");
    });
</script>
<div id="mw-language-settings">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs m-1 w-100">
        <nav id="nav-language-settings" class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center  active " data-bs-toggle="tab" href="#mw-lang-settings"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php print _e('Languages'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#mw-multilang-settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#mw-multilang-templates"><i class="mdi mdi-pencil-ruler mr-1"></i><?php print _e('Templates'); ?></a>
        </nav>

        <div id="mw-lang-settings" class="mw-accordion-content mw-ui-box-content">
            <module type="multilanguage/language_settings"/>
        </div>

        <div id="mw-multilang-settings" class="mw-accordion-content mw-ui-box-content">
            <module type="multilanguage/settings"/>
        </div>

        <div id="mw-multilang-templates" class="mw-accordion-content mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
