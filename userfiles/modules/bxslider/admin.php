<script>mw.lib.require('font_awesome5');</script>

<?php
$defaults = array(
    'images' => '',
    'primaryText' => 'My bxSlider',
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
$template_skins_path = template_dir() . 'modules/bxslider/templates/' . $module_template_clean . '/skins';
$skins = array();

if (is_dir($template_skins_path)) {
    $skins = scandir($template_skins_path);
}

if (empty($skins) and is_dir($default_skins_path)) {
    $skins = scandir($default_skins_path);
}


$count = 0;
?>

<?php
$pager = get_option('pager', $params['id']);
if ($pager) {
    $pager = $pager;
} elseif (isset($params['pager'])) {
    $pager = $params['pager'];
} else {
    $pager = true;
}

$controls = get_option('controls', $params['id']);
if ($controls) {
    $controls = $controls;
} elseif (isset($params['controls'])) {
    $controls = $params['controls'];
} else {
    $controls = true;
}

$loop = get_option('loop', $params['id']);
if ($loop) {
    $loop = $loop;
} elseif (isset($params['loop'])) {
    $loop = $params['loop'];
} else {
    $loop = true;
}

if (isset($params['hideControlOnEnd'])) {
    $hideControlOnEnd = $params['hideControlOnEnd'];
} else {
    $hideControlOnEnd = true;
}

if (isset($params['mode'])) {
    $mode = $params['mode'];
} else {
    $mode = 'horizontal';
}

if (isset($params['speed'])) {
    $speed = $params['speed'];
} else {
    $speed = '500';
}

$adaptiveHeight = get_option('adaptive_height', $params['id']);
if ($adaptiveHeight) {
    $adaptiveHeight = $adaptiveHeight;
} elseif (isset($params['adaptive_height'])) {
    $adaptiveHeight = $params['adaptive_height'];
} else {
    $adaptiveHeight = '500';
}

if (isset($params['prev_text'])) {
    $prevText = $params['prev_text'];
} else {
    $prevText = 'Prev';
}

if (isset($params['next_text'])) {
    $nextText = $params['next_text'];
} else {
    $nextText = 'Next';
}

if (isset($params['prev_selector'])) {
    $prevSelector = $params['prev_selector'];
} else {
    $prevSelector = null;
}

if (isset($params['next_selector'])) {
    $nextSelector = $params['next_selector'];
} else {
    $nextSelector = null;
}

if (isset($params['pager_custom'])) {
    $pagerCustom = $params['pager_custom'];
} else {
    $pagerCustom = '';
}
?>

<script>mw.require('prop_editor.js')</script>
<script>mw.require('module_settings.js')</script>
<script>mw.require('icon_selector.js')</script>

<script>mw.require('wysiwyg.css')</script>

<script>
    $(window).on('load', function () {

        var skins = [];
        var fodlerItems = <?php print json_encode($skins); ?>;

        fodlerItems.forEach(function (item) {
            if (item !== '.' && item !== '..') {
                skins.push(item.split('.')[0])
            }
        });

        var data = <?php print json_encode($json); ?>;
        $.each(data, function (key) {
            if (typeof data[key].images === 'string') {
                data[key].images = data[key].images.split(',');
            }
        });

        this.bxSettings = new mw.moduleSettings({
            element: '#settings-box',
            header: '<i class="mw-icon-drag"></i> Slide {count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data: data,
            key: 'settings',
            group: '<?php print $params['id']; ?>',
            autoSave: true,
            schema: [
                {
                    interface: 'file',
                    id: 'images',
                    label: 'Add Image',
                    types: 'images',
                    multiple: 2
                },
                {
                    interface: 'select',
                    label: ['Skin'],
                    id: 'skin',
                    options: skins
                },
                {
                    interface: 'icon',
                    label: ['Icon'],
                    id: 'icon'
                },
                {
                    interface: 'text',
                    label: ['Slide Heading'],
                    id: 'primaryText'
                },
                {
                    interface: 'text',
                    label: ['Slide Description'],
                    id: 'secondaryText'
                },
                {
                    interface: 'text',
                    label: ['URL'],
                    id: 'url'
                },
                {
                    interface: 'text',
                    label: ['See more text'],
                    id: 'seemoreText'
                }
            ]
        });
        $(bxSettings).on("change", function (e, val) {
            var final = [];
            $.each(val, function () {
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
                <i class="mw-icon-gear"></i> <?php print _e('Slides'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-bxslider-settings">


                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>

                <div class="mw-ui-field-holder text-right">
                    <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" onclick="bxSettings.addNew(0);"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></span>
                </div>

                <div id="settings-box"></div>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-bxslider">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Pager"); ?></label>
                    <select name="pager" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                        <option value="false" <?php if ($pager == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                        <option value="true" <?php if ($pager == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                    </select>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Controls"); ?></label>
                    <select name="controls" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                        <option value="false" <?php if ($controls == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                        <option value="true" <?php if ($controls == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                    </select>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Loop"); ?></label>
                    <select name="loop" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                        <option value="false" <?php if ($loop == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                        <option value="true" <?php if ($loop == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                    </select>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Adaptive Height"); ?></label>
                    <select name="adaptive_height" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                        <option value="false" <?php if ($adaptiveHeight == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                        <option value="true" <?php if ($adaptiveHeight == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                    </select>
                </div>
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