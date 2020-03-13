<?php only_admin_access() ?>

<script>mw.lib.require('font_awesome5');</script>

<script type="text/javascript">
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
    mw.require('icon_selector.js')
</script>

<?php

$settings = get_option('settings', $params['id']);

if ($settings == false) {
    if (isset($params['settings'])) {
        $settings = $params['settings'];
    }
}

$defaults = array(
    'title' => '',
    'id' => 'tab-' . time(),
    'icon' => ''
);

$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}

?>

<style>

    .mw-ui-box + .mw-ui-box{
        margin-top: 20px;
    }
</style>

<script>
    mw.require('prop_editor.js');
    mw.require('module_settings.js');
</script>
<script>



    $(window).on("load", function () {
        this.tabSettings = new mw.moduleSettings({
            element:'#settings-box',
            header:'<i class="mw-icon-drag"></i> <span data-bind="title">Move</span> <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data: <?php print json_encode($json); ?>,
            schema:[
                {
                    interface:'text',
                    label:['Title'],
                    id:'title'
                },
                {
                    interface:'icon',
                    label:['Icon'],
                    id:'icon'
                },
                {
                    interface:'hidden',
                    label:[''],
                    id:'id',
                    value: function(){
                        return 'tab-' + mw.random();
                    }
                }
            ]
        });
        $(tabSettings).on("change", function(e, val){
            $("#settingsfield").val(tabSettings.toString()).trigger("change")
        });
    });

</script>

<style>
    .mw-ui-box-header {
        cursor: -moz-grab;
        cursor: -webkit-grab;
        cursor: grab;
    }
</style>

<input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>
<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="mw-ui-field-holder add-new-button text-right m-b-10">
                <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:tabSettings.addNew(0)"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
            </div>
            <div id="settings-box"></div>
            <!-- Settings Content -->

            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
