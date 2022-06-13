<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
$current_template = false;
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        if (!isset($params['parent-module']) and isset($params['root-module'])) {
            $params['parent-module'] = $params['root-module'];
        }
        if (!isset($params['parent-module-id']) and isset($params['root-module-id'])) {
            $params['parent-module-id'] = $params['root-module-id'];
        }

        if (!isset($params['parent-module']) and isset($params['prev-module'])) {
            $params['parent-module'] = $params['prev-module'];
        }
        if (!isset($params['parent-module-id']) and isset($params['prev-module-id'])) {
            $params['parent-module-id'] = $params['prev-module-id'];
        }

        if (isset($params['for-module'])) {
            $params['parent-module'] = $params['for-module'];
        }
        if (!isset($params['parent-module'])) {
            error('parent-module is required');

        }

        if (!isset($params['parent-module-id'])) {
            error('parent-module-id is required');

        }

        $filter = false;
        if(isset($params['template-filter'])){
            $filter = trim($params['template-filter']);
        }



        $site_templates = site_templates();

        $module_templates = module_templates($params['parent-module']);
        $templates = module_templates($params['parent-module']);


        $mod_name = $params['parent-module'];
        $mod_name = str_replace('admin', '', $mod_name);
        $mod_name = rtrim($mod_name, DS);
        $mod_name = rtrim($mod_name, '/');

        if ($filter) {
            if ($module_templates) {
                foreach ($module_templates as $key => $temp) {
                    if (!str_contains($temp['layout_file'], $filter)) {
                        unset($module_templates[$key]);
                    }
                }
            }

            if ($templates) {
                foreach ($templates as $key => $temp) {
                    if (!str_contains($temp['layout_file'], $filter)) {
                        unset($templates[$key]);
                    }
                }
            }
        }


        $screenshots = false;
        if (isset($params['data-screenshots'])) {
            $screenshots = $params['data-screenshots'];
        }





        $search_bar = false;
        if (isset($params['data-search'])) {
            $search_bar = $params['data-search'];
        }

        $small_view = false;
        if (isset($params['data-small-view'])) {
            $small_view = $params['data-small-view'];
        }


        $skin_change_mode = false;
        if (isset($params['data-skin-change-mode'])) {
            $skin_change_mode = $params['data-skin-change-mode'];
        }

        $hide_skin_settings = false;
        if (isset($params['data-hide-skin-settings'])) {
            $hide_skin_settings = $params['data-hide-skin-settings'];
        }

        $show_skin_setting_in_first_tab = false;
        $params['data-show-skin-settings-on-first-tab'] = true;

        if (isset($params['data-show-skin-settings-on-first-tab'])) {
            $show_skin_setting_in_first_tab = $params['data-show-skin-settings-on-first-tab'];
            $hide_skin_settings = true;
        }


        $cur_template = get_option('data-template', $params['parent-module-id']);


        if ($cur_template == false) {

            if (isset($_GET['data-template'])) {
                $cur_template = $_GET['data-template'] . '.php';
            } else if (isset($_REQUEST['template'])) {
                $cur_template = $_REQUEST['template'] . '.php';
            }
            if ($cur_template != false) {
                $cur_template = str_replace('..', '', $cur_template);
                $cur_template = str_replace('.php.php', '.php', $cur_template);
            }
        }

        if ($screenshots) {
            foreach ($module_templates as $temp) {


                if ($temp['layout_file'] == $cur_template) {
                    if (!isset($temp['screenshot'])) {
                        $temp['screenshot'] = '';
                    }
                    $current_template = array('name' => $temp['name'], 'screenshot' => $temp['screenshot'], 'layout_file' => $temp['layout_file']);
                }
            }
        }
        ?>


        <?php  if($current_template and isset($current_template['name'])):  ?>


            <script type="text/javascript">

                    $(document).ready(function () {
                        template_select_set_modal_title('<?php _ejs($current_template['name']) ?>')
                    });

            </script>


        <?php endif; ?>


        <script type="text/javascript">
            function template_select_set_modal_title(title) {
                if (typeof (thismodal) != 'undefined') {
                    if (typeof (window.mw_module_settings_info) != 'undefined') {

                        var modal_title_str = '';
                        if (typeof (mw_module_settings_info.name) == "undefined") {
                            modal_title_str = "<?php _ejs("Settings"); ?>"
                        } else {
                            modal_title_str = mw_module_settings_info.name;
                        }

                        if (mw_module_settings_info.icon) {
                            modal_title_str = ('<img class="mw-module-dialog-icon" src="' + mw_module_settings_info.icon + '">' + modal_title_str + ' - ' + title)
                         }

                        if (modal_title_str) {
                            thismodal.title(modal_title_str);
                        }
                    }
                }
            }
        </script>


        <script type="text/javascript">
            $(document).ready(function () {
                mw.options.form('.mw-mod-template-settings-holder', function () {
                    var selected_skin = $('#mw-module-skin-select-dropdown :selected').val();

                    if (mw.notification != undefined) {
                        mw.notification.success('<?php _ejs("Module template has changed"); ?>');
                    }

                    if (selected_skin) {
                        $('#mw-module-skin-settings-module').attr('parent-template', selected_skin).reload_module();
                        //  $('.module-admin-modules-templates-settings').attr('parent-template', selected_skin).reload_module();
                    }

                    <?php if ($screenshots): ?>
                    setTimeout(function () {
                        mw_admin_layouts_list_inner_modules_btns();
                    }, 999);
                    <?php endif; ?>
                });
            });
        </script>

        <script>
            function mw_admin_layouts_list_inner_modules_btns() {
                var mod_in_mods_html_btn = '';

                var mods_in_mod = window.parent.$('#<?php print $params['parent-module-id'] ?>').find('.module', '#<?php print $params['parent-module-id'] ?>');
                if (mods_in_mod) {
                    $(mods_in_mod).each(function () {

                        var inner_mod_type = $(this).attr("type");
                        var inner_mod_id = $(this).attr("id");
                        if (!inner_mod_type) {
                            var inner_mod_type = $(this).attr("data-type");
                        }
                        var inner_mod_title = $(this).attr("data-mw-title");
                        if (!inner_mod_title) {
                            inner_mod_title = inner_mod_type;
                        }

                        if (inner_mod_type) {
                            var inner_mod_type_admin = inner_mod_type + '/admin'
                            mod_in_mods_html_btn += '<a href="javascript:;" class="btn btn-primary mb-1 mr-1" onclick=\'window.mw.parent().tools.open_global_module_settings_modal("' + inner_mod_type_admin + '","' + inner_mod_id + '")\'>' + inner_mod_title + '</a>';
                        }
                    });
                }

                if (mod_in_mods_html_btn) {
                    $('.current-template-modules-list-wrap').show();
                    $('.current-template-modules-list').html(mod_in_mods_html_btn);
                } else {
                    $('.current-template-modules-list-wrap').hide();
                }
            }
        </script>

        <?php if (!$skin_change_mode): ?>

        <?php endif; ?>

        <?php if (is_array($templates)): ?>
            <?php $default_item_names = array(); ?>

            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#change-layout"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Change Layout'); ?></a>
            </nav>

            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="settings">
                    <?php if(!$from_live_edit): ?>
                        NO SETTINGS
                    <?php endif; ?>

                    <!-- Settings Content -->
                    <div class="module-live-edit-settings module-layouts-settings">
                        <div class="mw-mod-template-settings-holder">
                            <select id="mw-module-skin-select-dropdown" data-also-reload="#mw-module-skin-settings-module" name="data-template" class="mw-ui-field mw_option_field  w100 hidden" option_group="<?php print $params['parent-module-id'] ?>" data-refresh="<?php print $params['parent-module-id'] ?>">
                                <option value="default" <?php if (('default' == $cur_template)): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>

                                <?php foreach ($templates as $item): ?>
                                    <?php if ((strtolower($item['name']) != 'default')): ?>
                                        <?php $default_item_names[] = $item['name']; ?>
                                        <option <?php if (($item['layout_file'] == $cur_template)): ?>   selected="selected" <?php endif; ?> value="<?php print $item['layout_file'] ?>" title="Template: <?php print str_replace('.php', '', $item['layout_file']); ?>"> <?php print $item['name'] ?> </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php if (is_array($site_templates)): ?>
                                    <?php foreach ($site_templates as $site_template): ?>
                                        <?php if (isset($site_template['dir_name'])): ?>
                                            <?php
                                            $template_dir = templates_path() . $site_template['dir_name'];
                                            $possible_dir = $template_dir . DS . 'modules' . DS . $mod_name . DS;
                                            $possible_dir = normalize_path($possible_dir, false)
                                            ?>
                                            <?php if (is_dir($possible_dir)): ?>
                                                <?php
                                                $options = array();

                                                $options['for_modules'] = 1;
                                                $options['path'] = $possible_dir;
                                                $templates = mw()->layouts_manager->get_all($options);
                                                ?>

                                                <?php if (is_array($templates)): ?>
                                                    <?php if ($site_template['dir_name'] == template_name()) { ?>
                                                        <?php
                                                        $has_items = false;

                                                        foreach ($templates as $item) {
                                                            if (!in_array($item['name'], $default_item_names)) {
                                                                $has_items = true;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if (is_array($has_items)): ?>
                                                            <optgroup label="<?php print $site_template['name']; ?>">
                                                                <?php foreach ($templates as $item): ?>
                                                                    <?php if ((strtolower($item['name']) != 'default')): ?>
                                                                        <?php $opt_val = $site_template['dir_name'] . '/' . 'modules/' . $mod_name . $item['layout_file']; ?>
                                                                        <?php if (!in_array($item['name'], $default_item_names)): ?>
                                                                            <option <?php if (($opt_val == $cur_template)): ?>   selected="selected"  <?php endif; ?> value="<?php print $opt_val; ?>"><?php print $item['name'] ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php endif; ?>
                                                    <?php } ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <?php if (isset($current_template)): ?>
                                <!-- Current template - Start -->
                                <div class="row">
                                    <div class="col-12 current-template">
                                        <label class="control-label" title="<?php print $current_template['layout_file']; ?>"><?php _e('Current layout'); ?></label>
                                        <div class="screenshot">
                                            <div class="holder">
                                                <img data-url="<?php echo thumbnail($current_template['screenshot'], 800, 400); ?>" alt="<?php print $current_template['name']; ?>" style="max-width:100%;" title="<?php print $current_template['name']; ?>"/>
                                                <div class="title"><?php print $current_template['name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 current-template-modules">
                                        <div class="current-template-modules-list-wrap">
                                            <label class="control-label">This layout contains those modules</label>

                                            <div class="current-template-modules-list"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($show_skin_setting_in_first_tab): ?>
                                <module type="admin/modules/templates_settings" id="mw-module-skin-settings-module" parent-module-id="<?php print $params['parent-module-id'] ?>" parent-module="layouts" parent-template="<?php print $cur_template ?>"/>
                            <?php endif; ?>
                            <!-- Current template - End -->
                        </div>

                    </div>
                    <!-- Settings Content - End -->
                </div>

                <div class="tab-pane fade" id="change-layout">
                    <div class="mw-mod-template-settings-holder">
                        <?php if ($screenshots): ?>
                            <script>
                                $(document).ready(function () {
                                    mw_admin_layouts_list_inner_modules_btns();

                                    $('.module-layouts-viewer .js-apply-template').on('click', function () {
                                        var option = $(this).data('file');
                                        var title = $(this).find('div.title').first().html();
                                        $('.module-layouts-viewer .js-apply-template .screenshot').removeClass('active');
                                        $(this).find('.screenshot').addClass('active');
                                        $('select[name="data-template"] option:selected').removeAttr('selected');
                                        $('select[name="data-template"] option[value="' + option + '"]').attr('selected', 'selected');
                                        $('select[name="data-template"] option[value="' + option + '"]').prop('selected', true).trigger('change');

                                        template_select_set_modal_title(title)


                                    });
                                });
                            </script>

                        <?php if ($search_bar): ?>
                            <script>
                                $(document).ready(function () {
                                    mw.$('#module-skins-search').bind('keyup paste', function () {
                                        var search_kw = $(this).val();
                                        var items = document.querySelectorAll('.module-layouts-viewer > .js-apply-template');
                                        var el = this;
                                        var foundlen = 0;
                                        mw.tools.search(search_kw, items, function (found) {
                                            if (found) {
                                                foundlen++;
                                                $(this).show();
                                            } else {
                                                $(this).hide();
                                            }
                                        });
                                    });
                                });
                            </script>

                            <div class="form-group">
                                <div class="input-group prepend-transparent mb-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text px-2 bg-white"><i class="mdi mdi-magnify mdi-18px"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="module-skins-search" autocomplete="off" aria-label="Search" placeholder="Search">
                                </div>
                            </div>
                        <?php endif; ?>


                            <div class="module-layouts-viewer one-column-module-layouts-viewer">
                                <script>

                                    var rendImages = function (){
                                        var els = Array.from(document.querySelectorAll('[data-url]')).slice(0,5);
                                        var doneLike = 0;
                                        if(els && els.length) {
                                            els.forEach(function (img){
                                                img.src = img.dataset.url;
                                                img.style.display = '';
                                                delete img.dataset.url;
                                                img.addEventListener('load', function (){
                                                    doneLike++;
                                                    if(doneLike === 5) {
                                                        rendImages();
                                                    }
                                                })
                                                img.addEventListener('error', function (){
                                                    console.warn('Image ' + img.src + ' can not load')
                                                    doneLike++;
                                                    if(doneLike === 5) {
                                                        rendImages()
                                                    }
                                                })
                                            });

                                        }
                                    }

                                    addEventListener('load', function (){
                                        rendImages()
                                    })

                                </script>
                                <?php foreach ($module_templates as $item): ?>
                                    <?php if (($item['layout_file'] == $cur_template)): ?>
                                        <?php if ((strtolower($item['name']) != 'default')): ?>
                                            <a href="javascript:;" class="js-apply-template"
                                               data-file="<?php print $item['layout_file'] ?>">
                                                <?php if ($item['layout_file'] == $cur_template): ?>
                                                    <div class="default-layout">DEFAULT</div>
                                                <?php endif; ?>

                                                <div class="screenshot <?php if (($item['layout_file'] == $cur_template)): ?>active<?php endif; ?>">
                                                    <?php
                                                    $item_screenshot = thumbnail('');
                                                    if (isset($item['screenshot'])) {
                                                        $item_screenshot = $item['screenshot'];
                                                    }
                                                    ?>

                                                    <div class="holder">
                                                        <img
                                                            style="display: none;max-width: 100%"
                                                            data-url="<?php echo thumbnail($item_screenshot, 800, 400); ?>"
                                                            alt="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"
                                                            title="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"/>
                                                        <div class="title"><?php print $item['name']; ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php foreach ($module_templates as $item): ?>
                                    <?php if (($item['layout_file'] != $cur_template)): ?>
                                        <?php if ((strtolower($item['name']) != 'default')): ?>
                                            <a href="javascript:;" class="js-apply-template"
                                               data-file="<?php print $item['layout_file'] ?>">
                                                <?php if ($item['layout_file'] == $cur_template): ?>
                                                    <div class="default-layout">DEFAULT</div>
                                                <?php endif; ?>

                                                <div class="screenshot <?php if (($item['layout_file'] == $cur_template)): ?>active<?php endif; ?>">
                                                    <?php
                                                    $item_screenshot = thumbnail('');
                                                    if (isset($item['screenshot'])) {
                                                        $item_screenshot = $item['screenshot'];
                                                    }
                                                    ?>

                                                    <div class="holder">
                                                        <img data-url="<?php echo thumbnail($item_screenshot, 800, 400); ?>"
                                                             alt="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"
                                                             style="max-width:100%;"
                                                             title="<?php print $item['name']; ?> - <?php print addslashes($item['layout_file']) ?>"/>
                                                        <div class="title"><?php print $item['name']; ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!$hide_skin_settings): ?>
                        <module type="admin/modules/templates_settings" id="mw-module-skin-settings-module" parent-module-id="<?php print $params['parent-module-id'] ?>" parent-module="layouts" parent-template="<?php print $cur_template ?>"/>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
