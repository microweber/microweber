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
$for_module_id = $params['parent-module-id'];
if (isset($params['for-module-id'])) {
    $for_module_id = $params['for-module-id'];
} else {
    $params['for-module-id'] = $for_module_id;
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

$cur_template = get_option('data-template', $for_module_id);

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

            <?php endif; ?>
        });
    });
</script>




<?php if (is_array($templates)): ?>
    <div class="mw-mod-template-settings-holder">
        <?php $default_item_names = array(); ?>

        <style>
            .skin-dorpdown-select {
                cursor:pointer;
            }
            .skin-dropdown-options-wrapper {
                background:#f2f2f2;
                max-height:500px;
                overflow:scroll;
                border-radius: 8px;
                padding: 9px;
                margin-top: 6px;
            }
            .skin-dropdown-option {
                margin-top:8px;
                background:#fff;
                border-radius:8px;
                padding: 8px;
                border: 3px solid transparent;
                cursor:pointer;
                line-height: 30px;
                text-align: center;
            }
            .skin-dropdown-option img {
                width:160px;
            }
            .skin-dropdown-option:hover {
                border-color:#0000001c;
            }
        </style>

        <?php
        $currentSkinData = [
            'screenshot' => false,
            'name' => 'Default',
            'file' => $cur_template
        ];

        foreach ($templates as $item) {
            if ($item['layout_file'] == $cur_template) {
                $currentSkinData['name'] = $item['name'];
                if (isset($item['screenshot'])) {
                    $currentSkinData['screenshot'] = $item['screenshot'];
                }
            }
        }

        ?>
        <div x-data="{
            showSkinDropdown: false,
            selectedSkin: {
                'name': '<?php echo $currentSkinData['name']; ?>',
                'screenshot': '<?php echo $currentSkinData['screenshot']; ?>',
                'file': '<?php echo $currentSkinData['file']; ?>'
            }
         }">

            <div class="form-group d-block">
                <div class="form-label font-weight-bold"><?php _e("Current Skin / Template"); ?></div>
                <small class="text-muted d-block mb-2"><?php _e('Select different design'); ?></small>

                <div x-html="selectedSkin.name" x-on:click="showSkinDropdown = ! showSkinDropdown" class="skin-dorpdown-select mw_option_field form-select">
                    Default
                </div>

                <div x-show="showSkinDropdown" class="skin-dropdown-options-wrapper">

                    <?php
                    $newOrderTemplates = [];
                    $newOrderTemplates[] = array('name' => 'Default', 'layout_file' => 'default.php');
                    $newOrderTemplates = array_merge($newOrderTemplates, $templates);
                    ?>

                    <?php foreach ($newOrderTemplates as $item): ?>

                        <?php
                        if (!isset($item['screenshot'])) {
                            $item['screenshot'] = '';
                        }
                        ?>

                            <?php $default_item_names[] = $item['name']; ?>

                            <div x-on:click="() => {
                                    mw.options.saveOption({
                                        group: '<?php print $params['for-module-id'] ?>',
                                        key: 'data-template',
                                        value: '<?php print $item['layout_file'] ?>'
                                    });
                                    mw.top().app.editor.dispatch('onModuleSettingsChanged', ({'moduleId': '<?php print $params['for-module-id'] ?>'} || {}));
                                    showSkinDropdown = false;
                                    selectedSkin = {'screenshot': '<?php print $item['screenshot'] ?>', 'name': '<?php print $item['name'] ?>', 'file': '<?php print $item['layout_file'] ?>' };
                                }"
                                class="skin-dropdown-option" <?php if (($item['layout_file'] == $cur_template)): ?>selected="selected" <?php endif; ?>value="<?php print $item['layout_file'] ?>" title="Template: <?php print str_replace('.php', '', $item['layout_file']); ?>">


                               <?php if (isset($item['screenshot']) && !empty($item['screenshot'])): ?>

                               <div>
                                   <div>
                                       <h4>
                                           <?php print $item['name'] ?>
                                       </h4>
                                   </div>
                                   <div class="mw-skin-templates-bg-image" style="background: url('<?php print $item['screenshot'] ?>');">
                               </div>
                               </div>
                               <?php else: ?>
                                   <div>
                                       <h4>
                                           <?php print $item['name'] ?>
                                       </h4>
                                   </div>
                               <?php endif; ?>

                            </div>

                    <?php endforeach; ?>

                    <?php if (is_array($site_templates)): ?>
                        <?php foreach ($site_templates as $site_template): ?>
                            <?php if (isset($site_template['dir_name'])): ?>
                                <?php
                                $template_dir = templates_dir() . $site_template['dir_name'];
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
                                                <div label="<?php print $site_template['name']; ?>">
                                                    <?php foreach ($templates as $item): ?>
                                                        <?php if ((strtolower($item['name']) != 'default')): ?>
                                                            <?php $opt_val = $site_template['dir_name'] . '/' . 'modules/' . $mod_name . $item['layout_file']; ?>
                                                            <?php if (!in_array($item['name'], $default_item_names)): ?>
                                                                <div <?php if (($opt_val == $cur_template)): ?>   selected="selected"  <?php endif; ?> value="<?php print $opt_val; ?>"><?php print $item['name'] ?></div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php } ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div x-show="selectedSkin.screenshot">
                <img :src="selectedSkin.screenshot" />
            </div>
        </div>

        <?php if (isset($current_template)): ?>
            <!-- Current template - Start -->
            <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col current-template" style="width: 50%;">
                    <span class="title"><?php _e('Current layout'); ?></span>
                    <div class="screenshot">
                        <div class="holder">
                            <img src="<?php echo $current_template['screenshot']; ?>" alt="<?php print $current_template['name']; ?>" style="width:100%; object-fit: cover" title="<?php print $current_template['name']; ?>"/>
                            <div class="title"><?php print $current_template['name']; ?></div>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col current-template-modules" style="width: 50%;">

                    <module type="admin/modules/inner_modules_list" for-module-id="<?php print $params['parent-module-id'] ?>"  />




                </div>
            </div>
        <?php endif; ?>

        <!-- Current template - End -->
        <?php if ($screenshots): ?>
            <hr/>

            <script>
                $(document).ready(function () {
                    if(typeof mw_admin_layouts_list_inner_modules_btns === 'function') {
                        mw_admin_layouts_list_inner_modules_btns();
                    }
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
                                $item_screenshot = pixum( 800, 300);
                                if (isset($item['screenshot'])) {
                                    $item_screenshot = $item['screenshot'] ;
                                }
                                ?>

                                <div class="holder">
                                    <img src="<?php echo $item_screenshot; ?>" alt="<?php print $item['name']; ?>"
                                         style="max-width:100%; object-fit: cover" title="<?php print $item['name']; ?>"/>
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
<!---->
<!--        --><?php //if (!isset($params['simple'])) { ?>
<!--            <small class="text-umted d-block mt-3">--><?php //_e("Looking for more designs ?"); ?><!--</small>-->
<!--            <a class="btn btn-link btn-sm p-0" target="_blank" href="--><?php //print admin_url(); ?><!--marketplace">--><?php //_e("Check in our Marketplace"); ?><!--</a>-->
<!--        --><?php //} ?>
    </div>
<?php endif; ?>
