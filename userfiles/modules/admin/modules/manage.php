<?php
/*if (!user_can_access('module.modules.index')) {
    return;
}*/


$whitelabelSettings = [];
if(function_exists('get_whitelabel_whmcs_settings')){
$whitelabelSettings = get_whitelabel_whmcs_settings();
}
?>

<?php $load_module = url_param('load_module');
if ($load_module == true): ?>
    <?php
    $mod = str_replace('___', DS, $load_module);
    $mod = load_module($mod, $attrs = array('view' => 'admin', 'backend' => 'true'));
    print $mod;
    ?>
<?php else: ?>
    <?php

$show_by_categories = false;

if(isset($params['show_modules_by_categories']) and intval($params['show_modules_by_categories']) != 0){
    $show_by_categories = true;
}


    $mod_params = array();
    $mod_params['ui'] = 'any';
    if (isset($params['reload_modules'])) {
        $s = 'skip_cache=1';
        if (isset($params['cleanup_db'])) {
            $s .= '&cleanup_db=1';
            $s .= '&reload_modules=1';
        }

        $mods = scan_for_modules($s);
    }

    if (isset($params['category'])) {
        $mod_params['category'] = $params['category'];
    }

    if (isset($params['keyword'])) {
        $mod_params['keyword'] = $params['keyword'];
    }

    if (isset($params['search-keyword'])) {
        $mod_params['keyword'] = $params['search-keyword'];
    }

    if (isset($params['show-ui'])) {
        if ($params['show-ui'] == 'admin') {
            $mod_params['ui_admin'] = '1';
        } else if ($params['show-ui'] == 'live_edit') {
            $mod_params['ui'] = '1';
        }
    }

    if (isset($params['installed'])) {
        $mod_params['installed'] = $params['installed'];
    } else {
        $mod_params['installed'] = 1;
    }

    if (isset($params['install_new'])) {
        $update_api = new \Microweber\Update();
        $result = $update_api->get_modules();
        $mods = $result;
    } else {
        $mods = mw()->module_manager->get($mod_params);
    }

    $moduleCategories = [];
    $moduleCategoriesOther = [] ;
    $allowMods = [];
    foreach ($mods as $mod) {
        if (!user_can_view_module($mod)) {
            continue;
        }

        if (isset($whitelabelSettings['whmcs_url']) && !empty($whitelabelSettings['whmcs_url'])) {
            if ($mod['module'] == 'white_label') {
                continue;
            }
        }

        // Skip modules when  run
        $should_skip = false;
        $mod_item_cat = false;

          if (isset($mod['categories']) and $mod['categories'] and is_string( $mod['categories'])) {
                $mod_item_cats_explode = explode(',',$mod['categories']);
              $mod_item_cats_explode = array_map('trim',$mod_item_cats_explode);
              $mod_item_cats_explode = array_filter($mod_item_cats_explode);
              if(isset($mod_item_cats_explode[0])){
                  $mod_item_cat = $mod_item_cats_explode[0];
              }
          }

        if (isset($mod['settings'])) {
           if (!is_array($mod['settings'])) {
               $mod['settings'] = json_decode($mod['settings'], true);
           }
            if (isset($mod['settings']['hide_from_modules_list']) && $mod['settings']['hide_from_modules_list']) {
                $should_skip = true;
            }
        }

        if (isset($mod['module']) && $mod['module'] == 'white_label') {

            $whitelabel = [];
            if (function_exists('get_white_label_config')) {
                $whitelabel = get_white_label_config();
            }
            if (!empty($whitelabel['hide_white_label_module_from_list']) and intval($whitelabel['hide_white_label_module_from_list']) != 0) {

                $should_skip = true;
            }
        }


        if ($should_skip)  {
            continue;

        }

        if($mod_item_cat){
            $moduleCategories[$mod_item_cat][] =$mod;
        } else {
            $moduleCategoriesOther[] =$mod;
        }

        $allowMods[] = $mod;
    }

    if($moduleCategories and $moduleCategoriesOther){
        $moduleCategories['other'] = $moduleCategoriesOther;
    }

    $modsOriginal = $allowMods;
    array_multisort(array_column($modsOriginal, 'name'), SORT_NATURAL, $allowMods);

    $upds = false;
    if(!$show_by_categories){
        $moduleCategories= [];
        $moduleCategories['all'] = $allowMods;
    }
    ?>

    <script>mw.lib.require('mwui_init');</script>

    <style>
        .mw-module-installed-0 {
            opacity: 0.6;
        }
        .mw-modules-module-holder .mw-modules-badge{
            opacity: 0;
        }
        .mw-modules-module-holder:hover .mw-modules-badge{
            opacity: 1;
        }
    </style>

    <style>
        .module-img {
            height: 35px;
            margin-bottom: 10px;
        }
        div.module-img{
            width: 35px;
        }


        .mw-modules-module-holder {
            min-height: 140px;
            cursor: pointer;
        }

        .mw-modules-badge.cog-badge {

            background-color: #d5f3e4;
        }

        .mw-modules-badge.cog-settings {
            background-color: #f6d9da;
        }


    </style>

    <?php if (isset($mods) and is_array($mods) == true and $mods == true): ?>
        <div class="mw-modules">
            <?php if (is_array($upds) == true): ?>
                <?php foreach ($upds as $upd_mod): ?>
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <?php if (isset($upd_mod['module'])): ?>
                            <?php $item = module_info($upd_mod['module']); ?>

                            <?php if (isset($item['id'])): ?>
                                <div class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?> h-100" id="module-db-id-<?php print $item['id'] ?>">
                                    <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" class="h-100"/>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php if ($moduleCategories): ?>

              <?php foreach ($moduleCategories as $mod_cat => $mods): ?>
                <div class="row mw-modules">

                      <?php if ($show_by_categories): ?>
                          <div class="w100">
                              <h6 class="ml-3 font-weight-bold"><?php print titlelize( $mod_cat) ?></h6>
                          </div>
                      <?php endif; ?>

            <?php foreach ($mods as $k => $item): ?>

                <?php if (!isset($item['id'])): ?>

                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <div class="mw-admin-module-list-item mw-module-not-installed h-100" id="module-remote-id-<?php print $item['id'] ?>">
                            <div class=" module module-admin-modules-edit-module h-100">
                                <?php
                                if (isset($item[0]) and is_array($item[0])) {
                                    $item = $item[0];
                                }

                                $data = $item;
                                include($config["path"] . 'update_module.php'); ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-xl-3 col-md-4 col-6 mb-3">
                        <div class="mw-admin-module-list-item mw-module-installed-<?php print $item['installed'] ?> h-100" id="module-db-id-<?php print $item['id'] ?>">
                            <module type="admin/modules/edit_module" data-module-id="<?php print $item['id'] ?>" class="h-100"/>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>



        </div>
    <?php else : ?>
        <div class="card style-1 h-100 mw-modules-module-holder">
            <div class="card-body h-100 d-flex align-items-center justify-content-center flex-column">
                <div class="icon-title">
                    <i class="mdi mdi-view-grid-plus"></i> <h5 class="mb-0"><?php _e("No modules found"); ?></h5>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
