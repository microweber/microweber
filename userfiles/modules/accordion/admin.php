<?php only_admin_access() ?>
<script type="text/javascript">
    mw.lib.require('font_awesome5');
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
    mw.require('icon_selector.js');
    mw.require('prop_editor.js');
    mw.require('module_settings.js');
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
    'id' => 'accordion-' . uniqid(),
    'icon' => ''
);
$json = json_decode($settings, true);
if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>

<?php

$data = array();
$count = 0;
foreach ($json as $slide) {
    $count++;
    if (!isset($slide['id'])) {
        $slide['id'] = 'accordion-' .  $params['id']. '-'.$count;
    }
    array_push($data, $slide);
}


?>
<script>
    $(window).on('load', function(){
        this.accordionSettings = new mw.moduleSettings({
            element:'#accordion-settings',
            header:'<i class="mw-icon-drag"></i> <span data-bind="title">Move</span> <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data: <?php print json_encode($data); ?>,
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
        $(accordionSettings).on("change", function(e, val){
            $("#settingsfield").val(accordionSettings.toString()).trigger("change")
        });
    })
</script>
<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-accordion-settings">
                <input type="hidden" class="mw_option_field" name="settings" id="settingsfield"/>
                <div class="mw-ui-field-holder add-new-button text-right m-b-10">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="accordionSettings.addNew()">
                        <i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?>
                    </span>
                </div>
                <div id="accordion-settings"></div>
            </div>
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
