<?php
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;

$modules = array();
$mod_obj_str = 'modules';
if (isset($is_elements) and $is_elements == true) {
    $mod_obj_str = 'elements';
    $el_params = array();
    if (isset($params['layout_type'])) {
        $el_params['layout_type'] = $params['layout_type'];
    }
    $modules = mw()->layouts_manager->get($el_params);

    if ($modules == false) {
        // scan_for_modules($modules_options);
        $el_params['no_cache'] = true;
        mw()->modules->scan_for_elements($el_params);
        $modules = mw()->layouts_manager->get($el_params);
    }
    if ($modules == false) {
        $modules = array();
    }
    // $dynamic_layouts = mw()->layouts_manager->get_all('no_cache=1&get_dynamic_layouts=1');
    $dynamic_layouts = false;
    $module_layouts_skins = false;
    $dynamic_layouts = mw()->layouts_manager->get_all('no_cache=1&get_dynamic_layouts=1');

    $module_layouts_skins = mw()->modules->templates('layouts');



} else {
    $modules = mw()->modules->get('installed=1&ui=1');

    $sortout_el = array();
    $sortout_mod = array();
    if (!empty($modules)) {
        foreach ($modules as $mod) {
            if (isset($mod['as_element']) and intval($mod['as_element']) == 1) {
                $sortout_el[] = $mod;
            } else {
                $sortout_mod[] = $mod;
            }
        }
        $modules = array_merge($sortout_el, $sortout_mod);
    }

    $modules_from_template = mw()->modules->get_modules_from_current_site_template();
    if (!empty($modules_from_template)) {
        $modules = array_merge($modules, $modules_from_template);
    }

}

if (isset($_COOKIE['recommend']) and is_string($_COOKIE['recommend']) and isset($modules) and is_array($modules)) {
    $recommended = json_decode($_COOKIE['recommend'], true);

    if (is_array($recommended) and !empty($recommended)) {
        $position = 9;
        $sorted_modules = array();
        arsort($recommended);
        foreach ($recommended as $key => $value) {
            foreach ($modules as $mod_key => $item) {
                if (isset($item['module']) and isset($item['position']) and $item['position'] > $position) {
                    if ($key == $item['module']) {
                        $sorted_modules[] = $item;
                    }
                }
            }
        }

        if (!empty($sorted_modules)) {
            //arsort( $sorted_modules);
            if (!empty($modules)) {
                $re_sorted_modules = array();
                $temp = array();
                $modules_copy = $modules;
                foreach ($modules_copy as $key => $item) {
                    if (is_array($sorted_modules) and !empty($sorted_modules)) {
                        foreach ($sorted_modules as $key2 => $sorted_module) {
                            if ($sorted_module['module'] == $item['module']) {
                                unset($modules_copy[$key]);
                            }
                        }
                    }
                }
                foreach ($modules_copy as $key => $item) {
                    $re_sorted_modules[] = $item;

                    if (!isset($item['position'])) {
                        $item['position'] = 999;
                    }

                    if ($item['position'] > $position) {
                        if (is_array($sorted_modules) and !empty($sorted_modules)) {
                            foreach ($sorted_modules as $key2 => $sorted_module) {
                                $re_sorted_modules[] = $sorted_module;
                                unset($sorted_modules[$key2]);
                            }
                        }

                    }

                }
                if (!empty($re_sorted_modules)) {
                    $modules = $re_sorted_modules;
                }
            }
        }
    }

}


?>  <?php if (!isset($params['clean'])) { ?>
    <script type="text/javascript">

        Modules_List_<?php print $mod_obj_str ?> = {}

    </script>

<?php } ?>

<ul class="modules-list list-<?php print $mod_obj_str ?>">
    <?php
    $def_icon = modules_path() . 'default.png';
    $def_icon = mw()->url_manager->link_to_file($def_icon);


    ?>
    <?php if (isset($dynamic_layouts) and is_array($dynamic_layouts)): ?>
        <?php foreach ($dynamic_layouts as $dynamic_layout): ?>
            <?php if (isset($dynamic_layout['template_dir']) and isset($dynamic_layout['layout_file'])): ?>
                <li data-module-name="layout"
                    template="<?php print $dynamic_layout['template_dir'] ?>/<?php print $dynamic_layout['layout_file'] ?>"
                    data-filter="<?php print $dynamic_layout['name'] ?>" class="module-item" unselectable="on"> <span
                            class="mw_module_hold">
    <?php if (!isset($dynamic_layout['icon'])): ?>
        <?php $dynamic_layout['icon'] = $def_icon; ?>
    <?php endif; ?>
                        <span class="mw_module_image"> <span class="mw_module_image_holder">
    <img
            alt="<?php print $dynamic_layout['name'] ?>"
            title="<?php isset($dynamic_layout['description']) ? print addslashes($dynamic_layout['description']) : print addslashes($dynamic_layout['name']); ?>"
            class="module_draggable"
            data-module-name-enc="layout_<?php print date("YmdHis") ?>"
            data-module-name="<?php print $dynamic_layout['layout_file'] ?>"
            src="<?php print $dynamic_layout['icon'] ?>"
    /> </span></span> <span class="module_name"
                            alt="<?php isset($dynamic_layout['description']) ? print addslashes($dynamic_layout['description']) : ''; ?>">
    <?php print titlelize(_e($dynamic_layout['name'], true)); ?>
    </span> </span></li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>










    <?php if (isset($module_layouts_skins) and is_array($module_layouts_skins)): ?>
        <?php foreach ($module_layouts_skins as $dynamic_layout): ?>
            <?php if (isset($dynamic_layout['layout_file'])): ?>
                <li data-module-name="layouts"
                    template="<?php print $dynamic_layout['layout_file'] ?>"
                    data-filter="<?php print $dynamic_layout['name'] ?>" class="module-item" unselectable="on"> <span
                            class="mw_module_hold">
    <?php if (!isset($dynamic_layout['screenshot'])): ?>
        <?php $dynamic_layout['screenshot'] = $def_icon; ?>
    <?php endif; ?>
                        <span class="mw_module_image"> <span class="mw_module_image_holder">
    <img
            alt="<?php print $dynamic_layout['name'] ?>"
            title="<?php isset($dynamic_layout['description']) ? print addslashes($dynamic_layout['description']) : print addslashes($dynamic_layout['name']); ?>"
            class="module_draggable"
            data-module-name-enc="layout_<?php print date("YmdHis") ?>"
            data-module-name="<?php print $dynamic_layout['layout_file'] ?>"
            src="<?php print $dynamic_layout['screenshot'] ?>"
    /> </span></span> <span class="module_name"
                            alt="<?php isset($dynamic_layout['description']) ? print addslashes($dynamic_layout['description']) : ''; ?>">
    <?php print titlelize(_e($dynamic_layout['name'], true)); ?>
    </span> </span></li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>












    <?php if (isset($modules) and !empty($modules)): ?>


        <?php foreach ($modules as $module_item): ?>
            <?php if (isset($module_item['module'])): ?>
                <?php


                $module_group2 = explode(DIRECTORY_SEPARATOR, $module_item['module']);
                $module_group2 = $module_group2[0];
                ?>
                <?php $module_item['module'] = str_replace('\\', '/', $module_item['module']);

                $module_item['module'] = rtrim($module_item['module'], '/');
                $module_item['module'] = rtrim($module_item['module'], '\\');
                $temp = array();
                if (isset($module_item['categories']) and is_array($module_item['categories']) and !empty($module_item['categories'])) {
                    foreach ($module_item['categories'] as $it) {
                        $temp[] = $it['parent_id'];
                    }
                    $module_item['categories'] = implode(',', $temp);
                }

                ?>
                <?php $module_item['module_clean'] = str_replace('/', '__', $module_item['module']); ?>
                <?php $module_item['name_clean'] = str_replace('/', '-', $module_item['module']); ?>
                <?php $module_item['name_clean'] = str_replace(' ', '-', $module_item['name_clean']);
                if (isset($module_item['categories']) and is_array($module_item['categories'])) {
                    $module_item['categories'] = implode(',', $module_item['categories']);
                }

                if (!isset($module_item['description'])) {
                    $module_item['description'] = $module_item['name'];
                }

                ?>
                <?php $module_id = $module_item['name_clean'] . '_' . uniqid(); ?>
                <li <?php if (!isset($params['clean'])) { ?> id="<?php print $module_id; ?>" <?php } ?>
                        data-module-name="<?php print $module_item['module'] ?>"

                        data-filter="<?php print $module_item['name'] ?>"
                        ondrop="true"
                        data-category="<?php isset($module_item['categories']) ? print addslashes($module_item['categories']) : ''; ?>"
                        class="module-item <?php if (isset($module_item['as_element']) and intval($module_item['as_element'] == 1) or (isset($is_elements) and $is_elements == true)) : ?> module-as-element<?php endif; ?>"> <span
                            unselectable="on" class="mw_module_hold"
                            title="<?php print addslashes($module_item["name"]); ?>. <?php print addslashes($module_item["description"]) ?>">
                    
                      
                    
  <?php if (!isset($params['clean'])) { ?>
      <script type="text/javascript">
          Modules_List_<?php print $mod_obj_str ?>['<?php print($module_id); ?>'] = {
              id: '<?php print($module_id); ?>',
              name: '<?php print $module_item["module"] ?>',
              title: '<?php print $module_item["name"] ?> / <?php _e($module_item["name"]); ?>',
              description: '<?php print addslashes($module_item["description"]) ?>'
          }





          <?php if (isset($module_item['settings']) and is_array($module_item['settings']) and !empty($module_item['settings'])): ?>
          if (typeof(mw.live_edit_module_settings_array) != 'undefined') {
              mw.live_edit_module_settings_array['<?php print $module_item["module"] ?>'] = <?php print json_encode($module_item['settings']) ?>
          }
          <?php endif; ?>

		  
		  
		  
      </script>


  <?php } ?>
                        <?php if ($module_item['icon']): ?>
                            <span class="mw_module_image">
        <span class="mw_module_image_holder">
            <img
                    alt="<?php print $module_item['name'] ?>"
                    title="<?php isset($module_item['description']) ? print addslashes($module_item['description']) : ''; ?>"
                    class="module_draggable"
                    data-module-name-enc="<?php print $module_item['module_clean'] ?>|<?php print $module_item['name_clean'] ?>_<?php print date("YmdHis") ?>"
                    src="<?php print $module_item['icon']; ?>"/>
        </span>
    </span>
                        <?php endif; ?>
                        <span class="module_name"
                              alt="<?php isset($module_item['description']) ? print addslashes($module_item['description']) : ''; ?>">
    <?php _e($module_item['name']); ?>
    </span> </span></li>
            <?php endif; ?>
        <?php endforeach; ?>


    <?php endif; ?>


</ul>
