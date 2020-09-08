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
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Slides'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">


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

                        this.slickSettings = new mw.moduleSettings({
                            element: '#settings-box',
                            header: '<i class="mw-icon-drag"></i> Slide {count} <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
                            data: data,
                            key: 'settings',
                            group: '<?php print $params['id']; ?>',
                            autoSave: true,
                            schema: [
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
                                    label: ['Main text'],
                                    id: 'primaryText'
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
                                },
                                {
                                    interface: 'file',
                                    id: 'images',
                                    label: 'Add Image',
                                    types: 'images',
                                    multiple: 1
                                }
                            ]
                        });
                        $(slickSettings).on("change", function (e, val) {
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
                <div class="mb-3">
                    <span class="btn btn-primary btn-rounded" onclick="slickSettings.addNew();"><?php _e('Add new'); ?></span>
                </div>
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>
                <div id="settings-box"></div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>