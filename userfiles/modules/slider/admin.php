<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Slides'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <?php
                $defaults = array(
                    'images' => '',
                    'primaryText' => 'My Slider',
                    'secondaryText' => 'Your slogan here',
                    'seemoreText' => 'See more',
                    'url' => '',
                    'urlText' => '',
                    'skin' => 'default'
                );

                $settings = get_module_option('settings', $params['id']);
                $json = json_decode($settings, true);

                if (isset($json) == false or count($json) == 0) {
                    $json = array(0 => $defaults);
                }
                $module_template = get_module_option('data-template', $params['id']);
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

                $module_template_check = get_module_option('data-template', $params['id']);
                if ($module_template_check == false AND isset($params['template'])) {
                    $module_template = $params['template'];
                }
                include('options.php');
                ?>


                <script>mw.require('prop_editor.js')</script>
                <script>mw.require('module_settings.js')</script>
                <script>mw.require('icon_selector.js')</script>
                <script>mw.require('wysiwyg.css')</script>
                <script>
                    $(document).ready(function () {
                        $('select[name="data-template"]').on('change', function () {
                            var thisVal = $(this).find(':selected').val();

                            var first = thisVal.slice(0, thisVal.lastIndexOf('-skin'));

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
                            header: '<i class="mw-icon-drag"></i> <?php _e('Slide {count}'); ?> <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
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

                <!-- Settings Content -->
                <div class="module-live-edit-settings module-bxslider-settings">
                    <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>

                    <div class="mb-3">
                        <span class="btn btn-primary btn-rounded" onclick="bxSettings.addNew(0, 'blank');"><?php _e('Add new'); ?></span>
                    </div>

                    <div id="settings-box"></div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="settings">
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

                    <div class="form-group" style="display: none;">
                        <label class="control-label d-block"><?php _e("Engine"); ?></label>
                        <select name="engine" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                            <option value="bxslider" <?php if ($engine == 'bxslider'): ?> selected="selected" <?php endif ?>>bxSlider</option>
                            <option value="slickslider" <?php if ($engine == 'slickslider'): ?> selected="selected" <?php endif ?>>Slick Slider</option>
                            <option value="slider" <?php if ($engine == 'slider'): ?> selected="selected" <?php endif ?>>Just Slider - Using Your Own Libraries</option>
                        </select>
                    </div>

                    <div class="slider-options">
                        <!-- Sliders Responsive Options -->
                        <div class="row js-option js-slickslider">
                            <div class="col-12">
                                <h3>Responsive Options</h3>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="control-label d-block">Extra Small &lt; 576px</label>
                                    <select name="slides-xs" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-option-group="<?php print $params['id']; ?>" data-columns="xs">
                                        <option value="1" <?php if ($slides_xs == '1'): ?>selected<?php endif; ?>>1 slide</option>
                                        <option value="2" <?php if ($slides_xs == '2'): ?>selected<?php endif; ?>>2 slides</option>
                                        <option value="3" <?php if ($slides_xs == '3'): ?>selected<?php endif; ?>>3 slides</option>
                                        <option value="4" <?php if ($slides_xs == '4'): ?>selected<?php endif; ?>>4 slides</option>
                                        <option value="5" <?php if ($slides_xs == '5'): ?>selected<?php endif; ?>>5 slides</option>
                                        <option value="6" <?php if ($slides_xs == '6'): ?>selected<?php endif; ?>>6 slides</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="control-label d-block">Small ≥ 576px</label>
                                    <select name="slides-sm" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-option-group="<?php print $params['id']; ?>" data-columns="sm">
                                        <option value="1" <?php if ($slides_sm == '1'): ?>selected<?php endif; ?>>1 slide</option>
                                        <option value="2" <?php if ($slides_sm == '2'): ?>selected<?php endif; ?>>2 slides</option>
                                        <option value="3" <?php if ($slides_sm == '3'): ?>selected<?php endif; ?>>3 slides</option>
                                        <option value="4" <?php if ($slides_sm == '4'): ?>selected<?php endif; ?>>4 slides</option>
                                        <option value="5" <?php if ($slides_sm == '5'): ?>selected<?php endif; ?>>5 slides</option>
                                        <option value="6" <?php if ($slides_sm == '6'): ?>selected<?php endif; ?>>6 slides</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="control-label d-block">Medium ≥ 768px</label>
                                    <select name="slides-md" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-option-group="<?php print $params['id']; ?>" data-columns="md">
                                        <option value="1" <?php if ($slides_md == '1'): ?>selected<?php endif; ?>>1 slide</option>
                                        <option value="2" <?php if ($slides_md == '2'): ?>selected<?php endif; ?>>2 slides</option>
                                        <option value="3" <?php if ($slides_md == '3'): ?>selected<?php endif; ?>>3 slides</option>
                                        <option value="4" <?php if ($slides_md == '4'): ?>selected<?php endif; ?>>4 slides</option>
                                        <option value="5" <?php if ($slides_md == '5'): ?>selected<?php endif; ?>>5 slides</option>
                                        <option value="6" <?php if ($slides_md == '6'): ?>selected<?php endif; ?>>6 slides</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="control-label d-block">Large ≥ 992px</label>
                                    <select name="slides-lg" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-option-group="<?php print $params['id']; ?>" data-columns="lg">
                                        <option value="1" <?php if ($slides_lg == '1'): ?>selected<?php endif; ?>>1 slide</option>
                                        <option value="2" <?php if ($slides_lg == '2'): ?>selected<?php endif; ?>>2 slides</option>
                                        <option value="3" <?php if ($slides_lg == '3'): ?>selected<?php endif; ?>>3 slides</option>
                                        <option value="4" <?php if ($slides_lg == '4'): ?>selected<?php endif; ?>>4 slides</option>
                                        <option value="5" <?php if ($slides_lg == '5'): ?>selected<?php endif; ?>>5 slides</option>
                                        <option value="6" <?php if ($slides_lg == '6'): ?>selected<?php endif; ?>>6 slides</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="control-label d-block">Extra large ≥ 1200px</label>
                                    <select name="slides-xl" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-option-group="<?php print $params['id']; ?>" data-columns="xl">
                                        <option value="1" <?php if ($slides_xl == '1'): ?>selected<?php endif; ?>>1 slide</option>
                                        <option value="2" <?php if ($slides_xl == '2'): ?>selected<?php endif; ?>>2 slides</option>
                                        <option value="3" <?php if ($slides_xl == '3'): ?>selected<?php endif; ?>>3 slides</option>
                                        <option value="4" <?php if ($slides_xl == '4'): ?>selected<?php endif; ?>>4 slides</option>
                                        <option value="5" <?php if ($slides_xl == '5'): ?>selected<?php endif; ?>>5 slides</option>
                                        <option value="6" <?php if ($slides_xl == '6'): ?>selected<?php endif; ?>>6 slides</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h3>Options</h3>
                            </div>
                        </div>

                        <!-- Sliders Options -->
                        <div class="row">
                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Pager"); ?></label>
                                    <select name="pager" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($pager == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($pager == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Controls"); ?></label>
                                    <select name="controls" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($controls == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($controls == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Loop"); ?></label>
                                    <select name="loop" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($loop == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($loop == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Adaptive Height"); ?></label>
                                    <select name="adaptive_height" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($adaptiveHeight == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($adaptiveHeight == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Speed"); ?></label>
                                    <input type="text" value="<?php print $speed; ?>" name="speed" class="mw_option_field form-control" option_group="<?php print $params['id'] ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Autoplay Speed"); ?></label>
                                    <input type="text" value="<?php print $autoplaySpeed; ?>" name="autoplay_speed" class="mw_option_field form-control" option_group="<?php print $params['id'] ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Pause on hover"); ?></label>
                                    <select name="pause_on_hover" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($pauseOnHover == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($pauseOnHover == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Responsive"); ?></label>
                                    <select name="responsive" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($responsive == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($responsive == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Autoplay"); ?></label>
                                    <select name="autoplay" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($autoplay == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($autoplay == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-bxslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Touch Enabled"); ?></label>
                                    <select name="touch_enabled" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($touchEnabled == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($touchEnabled == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Slides per row"); ?></label>
                                    <input type="text" value="<?php print $slidesPerRow; ?>" name="slides_per_row" class="mw_option_field form-control" option_group="<?php print $params['id'] ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Center Mode"); ?></label>
                                    <select name="center_mode" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($centerMode == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($centerMode == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Center Padding"); ?></label>
                                    <input type="text" value="<?php print $centerPadding; ?>" name="center_padding" class="mw_option_field form-control" placeholder="Default: 50px" option_group="<?php print $params['id'] ?>"/>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Draggable"); ?></label>
                                    <select name="draggable" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($draggable == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($draggable == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Fade"); ?>
                                        <small>(only for one slide)</small>
                                    </label>
                                    <select name="fade" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($fade == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($fade == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 js-option js-slickslider">
                                <div class="form-group">
                                    <label class="control-label d-block"><?php _e("Focus On Select"); ?></label>
                                    <select name="focus_on_select" class="mw_option_field selectpicker" data-width="100%" data-size="5" option_group="<?php print $params['id'] ?>">
                                        <option value="false" <?php if ($focusOnSelect == 'false'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
                                        <option value="true" <?php if ($focusOnSelect == 'true'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
