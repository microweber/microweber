<script>mw.lib.require('font_awesome5');</script>

<?php
$defaults = array(
    'images' => '',
    'primaryText' => 'My bxSlider',
    'secondaryText' => 'Your slogan here',
    'seemoreText' => 'See more',
    'url' => '',
    'urlText' => '',
    'skin' => 'bxslider-skin-1'
);

$settings = get_option('settings', $params['id']);
$json = json_decode($settings, true);

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
$module_template = get_option('data-template', $params['id']);
if (!$module_template OR $module_template == 'default') {
    $module_template = 'bxslider-skin-1';
}
$module_template_clean = str_replace('.php', '', $module_template);

$default_skins_path = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins';
$template_skins_path = template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins';
$skins = array();

if (is_dir($template_skins_path)) {
    $skins = scandir($template_skins_path);
}

if (empty($skins) and is_dir($default_skins_path)) {
    $skins = scandir($default_skins_path);
}

$count = 0;

include('options.php');
?>


<script>mw.require('prop_editor.js')</script>
<script>mw.require('module_settings.js')</script>
<script>mw.require('icon_selector.js')</script>
<script>mw.require('ui.css')</script>
<script>mw.require('wysiwyg.css')</script>
<script>
    $(document).ready(function () {
        $('select[name="data-template"]').on('change', function () {
            var thisVal = $(this).find(':selected').val();
//            console.log(thisVal);

            var first = thisVal.slice(0, thisVal.lastIndexOf('-skin'));


//            console.log(first);

            $('select[name="engine"]').val(first).trigger('change');
        })
    })
</script>


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
                <script>
                    $(document).ready(function () {
                        function showOptionsForSlider(slider) {
                            var slider;
                            $('.js-option').hide();
                            $('.js-' + slider).show();
                        }

                        var engine = $('select[name="engine"]');
                        var selectedEngine = engine.find(":selected").val();
                        showOptionsForSlider(selectedEngine);

                        $(engine).on('change', function () {

                            selectedEngine = $(this).find(":selected").val();
                            showOptionsForSlider(selectedEngine);
                        });
                    });
                </script>

                <module type="admin/modules/templates" simple="true"/>

                <div class="mw-ui-field-holder" style="display: none;">
                    <label class="mw-ui-label"><?php _e("Engine"); ?></label>
                    <select name="engine" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                        <option value="bxslider" <?php if ($engine == 'bxslider'): ?> selected="selected" <?php endif ?>>bxSlider</option>
                        <option value="slickslider" <?php if ($engine == 'slickslider'): ?> selected="selected" <?php endif ?>>Slick Slider</option>
                    </select>
                </div>

                <div class="slider-options">
                    <div class="mw-ui-field-holder js-option js-bxslider js-slickslider">
                        <label class="mw-ui-label"><?php _e("Pager"); ?></label>
                        <select name="pager" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($pager == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($pager == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-bxslider js-slickslider">
                        <label class="mw-ui-label"><?php _e("Controls"); ?></label>
                        <select name="controls" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($controls == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($controls == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-bxslider js-slickslider">
                        <label class="mw-ui-label"><?php _e("Loop"); ?></label>
                        <select name="loop" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($loop == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($loop == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-bxslider js-slickslider">
                        <label class="mw-ui-label"><?php _e("Adaptive Height"); ?></label>
                        <select name="adaptive_height" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($adaptiveHeight == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($adaptiveHeight == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-bxslider js-slickslider">
                        <label class="mw-ui-label"><?php _e("Speed"); ?></label>
                        <input type="text" value="<?php print $speed; ?>" name="speed" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>"/>
                    </div>


                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Pause on hover"); ?></label>
                        <select name="pause_on_hover" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($pauseOnHover == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($pauseOnHover == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Responsive"); ?></label>
                        <select name="responsive" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($responsive == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($responsive == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>
                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Autoplay"); ?></label>
                        <select name="autoplay" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($autoplay == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($autoplay == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option  js-slickslider">
                        <label class="mw-ui-label"><?php _e("Slides per row"); ?></label>
                        <input type="text" value="<?php print $slidesPerRow; ?>" name="slides_per_row" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>"/>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Slides to show"); ?></label>
                        <input type="text" value="<?php print $slidesToShow; ?>" name="slides_to_show" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>"/>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Slides to scroll"); ?></label>
                        <input type="text" value="<?php print $slidesToScroll; ?>" name="slides_to_scroll" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>"/>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Center Mode"); ?></label>
                        <select name="center_mode" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($centerMode == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($centerMode == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Draggable"); ?></label>
                        <select name="draggable" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($draggable == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($draggable == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Fade"); ?></label>
                        <select name="fade" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($fade == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($fade == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>

                    <div class="mw-ui-field-holder js-option js-slickslider">
                        <label class="mw-ui-label"><?php _e("Center Mode"); ?></label>
                        <select name="focus_on_select" class="mw-ui-field mw_option_field mw-full-width" option_group="<?php print $params['id'] ?>">
                            <option value="false" <?php if ($focusOnSelect == 'false'): ?> selected="selected" <?php endif ?>><?php _e("False"); ?></option>
                            <option value="true" <?php if ($focusOnSelect == 'true'): ?> selected="selected" <?php endif ?>><?php _e("True"); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /*
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>*/ ?>
</div>