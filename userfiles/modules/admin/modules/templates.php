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


$mod_name = $params['parent-module'];
$mod_name = str_replace('/admin', '', $mod_name);
$mod_name = rtrim($mod_name, DS);
$mod_name = rtrim($mod_name, '/');

$site_templates = site_templates();

//$module_templates = module_templates($params['parent-module']);
$templates = $module_templates = module_templates($mod_name);


$screenshots = false;
if (isset($params['data-screenshots'])) {
    $screenshots = $params['data-screenshots'];
}

$cur_template = get_option('data-template', $params['parent-module-id']);


if ($cur_template == false) {

    if (isset($_GET['data-template'])) {
        $cur_template = $_GET['data-template'] . '.php';
    } else if (isset($_REQUEST['template'])) {
        $cur_template = $_REQUEST['template'] . '.php';
    }
    if ($cur_template != false) {
        $cur_template = sanitize_path($cur_template);
        $cur_template = str_replace('.php.php', '.php', $cur_template);
    }
}

if ($screenshots) {
    foreach ($module_templates as $temp) {
        if ($temp['layout_file'] == $cur_template) {
            if (!isset($temp['screenshot'])) {
                $temp['screenshot'] = '';
            }
            $current_template = array('name' => $temp['name'], 'screenshot' => $temp['screenshot']);
        }
    }
}
?>


<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.mw-mod-template-settings-holder', function () {
            if (mw.notification != undefined) {
                mw.notification.success('<?php _ejs("Module template has changed"); ?>');
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
                    mod_in_mods_html_btn += '<a class="mw-ui-btn"  onclick=\'window.mw.parent().tools.open_global_module_settings_modal("' + inner_mod_type_admin + '","' + inner_mod_id + '")\' >' + inner_mod_title + '</a>';
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


<?php if (is_array($templates)): ?>
    <div class="mw-mod-template-settings-holder">
        <?php $default_item_names = array(); ?>



        <label class="form-group d-block">
            <label class="control-label"><?php _e("Current Skin / Template"); ?></label>
            <small class="text-muted d-block mb-2"><?php _e('Select different design'); ?></small>
            <select data-also-reload="#mw-module-skin-settings-module" name="data-template" class="mw_option_field selectpicker" data-width="100%" option_group="<?php print $params['parent-module-id'] ?>" data-refresh="<?php print $params['parent-module-id'] ?>" data-size="5">
                <option value="default" <?php if (('default' == $cur_template)): ?>   selected="selected"  <?php endif; ?>>
                    <?php _e("Default"); ?>
                </option>

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
        </label>

        <?php if (isset($current_template)): ?>
            <!-- Current template - Start -->
            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col current-template" style="width: 50%;">
                    <span class="title"><?php _e('Current layout'); ?></span>
                    <div class="screenshot">
                        <div class="holder">
                            <img src="<?php echo thumbnail($current_template['screenshot'], 1800, 1200); ?>" alt="<?php print $current_template['name']; ?>" style="max-width:100%;" title="<?php print $current_template['name']; ?>"/>
                            <div class="title"><?php print $current_template['name']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col current-template-modules" style="width: 50%;">
                    <div class="current-template-modules-list-wrap">
                        <span class="title"><?php _e('This layout contains modules'); ?></span>

                        <div class="current-template-modules-list">

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Current template - End -->
        <?php if ($screenshots): ?>
            <hr/>

            <script>
                $(document).ready(function () {
                    mw_admin_layouts_list_inner_modules_btns();
                });
            </script>

            <script>
                $(document).ready(function () {
                    $('.module-layouts-viewer .js-apply-template').on('click', function () {
                        var option = $(this).data('file');
                        $('.module-layouts-viewer .js-apply-template .screenshot').removeClass('active');
                        $(this).find('.screenshot').addClass('active');
                        $('select[name="data-template"] option:selected').removeAttr('selected');
                        $('select[name="data-template"] option[value="' + option + '"]').attr('selected', 'selected').trigger('change');
                    });
                });
            </script>

            <div class="module-layouts-viewer">
                <?php foreach ($module_templates as $item): ?>
                    <?php if ((strtolower($item['name']) != 'default')): ?>
                        <a href="javascript:;" class="js-apply-template"
                           data-file="<?php print $item['layout_file'] ?>">
                            <div class="screenshot <?php if (($item['layout_file'] == $cur_template)): ?>active<?php endif; ?>">
                                <?php
                                $item_screenshot = thumbnail('', 800, 300);
                                if (isset($item['screenshot'])) {
                                    $item_screenshot = thumbnail($item['screenshot'] , 800, 300);
                                }
                                ?>

                                <div class="holder">
                                    <img src="<?php echo $item_screenshot; ?>" alt="<?php print $item['name']; ?>"
                                         style="max-width:100%;" title="<?php print $item['name']; ?>"/>
                                    <div class="title"><?php print $item['name']; ?></div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


        <module type="admin/modules/templates_settings" id="mw-module-skin-settings-module"
                for-module-id="<?php print $params['parent-module-id'] ?>"
                parent-module-id="<?php print $params['parent-module-id'] ?>"
                parent-module="<?php print $params['parent-module'] ?>" parent-template="<?php print $cur_template ?>"/>

        <?php if (!isset($params['simple'])) { ?>
            <small class="text-umted d-block mt-3"><?php _e("Looking for more designs ?"); ?></small>
            <a class="btn btn-link btn-sm p-0" target="_blank" href="<?php print admin_url(); ?>view:packages"><?php _e("Check in our Marketplace"); ?></a>
        <?php } ?>
    </div>
<?php endif; ?>
