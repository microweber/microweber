<script>mw.lib.require('font_awesome5');</script>

<?php
$defaults = array(
    'images' => '',
    'primaryText' => 'My SlickSlider',
    'secondaryText' => 'Your slogan here',
    'seemoreText' => 'See more',
    'url' => '',
    'urlText' => '',
    'skin' => 'default'
);

$settings = get_option('settings', $params['id']);
$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
$module_template = get_option('data-template', $params['id']);
if (!$module_template) {
    $module_template = 'default';
}
$module_template_clean = str_replace('.php', '', $module_template);

$default_skins_path = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins';
$template_skins_path = template_dir() . 'modules/slickslider/templates/' . $module_template_clean . '/skins';
$skins = array();

if (is_dir($template_skins_path)) {
    $skins = scandir($template_skins_path);
}

if (empty($skins) and is_dir($default_skins_path)) {
    $skins = scandir($default_skins_path);
}


$count = 0;
?>
<script>mw.require('prop_editor.js')</script>
<script>mw.require('module_settings.js')</script>
<script>mw.require('icon_selector.js')</script>
<script>


    $(window).bind('load', function () {
        var skins = [];
        var fodlerItems = <?php print json_encode($skins); ?>;

        fodlerItems.forEach(function(item){
            if(item !== '.' && item !== '..'){
                skins.push(item.split('.')[0])
            }
        });
        var data = <?php print json_encode($json); ?>;
        $.each(data, function(key){
            if(typeof data[key].images === 'string') {
                data[key].images = data[key].images.split(',');
            }
        });

        this.slickSettings = new mw.moduleSettings({
            element:'#settings-box',
            header:'<i class="mw-icon-drag"></i> Slide {count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data: data,
            key:'settings',
            group:'<?php print $params['id']; ?>',
            autoSave:true,
            schema:[
                {
                    interface:'select',
                    label:['Skin'],
                    id:'skin',
                    options:skins
                },
                {
                    interface:'icon',
                    label:['Icon'],
                    id:'icon'
                },
                {
                    interface:'text',
                    label:['Main text'],
                    id:'primaryText'
                },
                {
                    interface:'text',
                    label:['URL'],
                    id:'url'
                },
                {
                    interface:'text',
                    label:['See more text'],
                    id:'seemoreText'
                },
                {
                    interface:'file',
                    id:'images',
                    label:'Add Image',
                    types:'images',
                    multiple:1
                }
            ]
        });
        $(slickSettings).on("change", function(e, val){
            var final = [];
            $.each(val, function(){
                var curr = $.extend({}, this);
                curr.images = curr.images.join(',');
                final.push(curr)
            });
            $("#settingsfield").val(JSON.stringify(final)).trigger("change")
        });
    });
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
                <div class="mw-ui-field-holder text-right">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="slickSettings.addNew();"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></span>
                </div>
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>
                <div id="settings-box"></div>
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