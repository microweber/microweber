<?php


function load_all_functions_files_for_modules($app=null)
{


    $is_installed = mw_is_installed();
    if (!$is_installed) {

        return;
    }
    $modules = mw()->module_manager->get('ui=any&installed=1&limit=99999order_by=position asc');
    $files = array();
    if (!empty($modules)) {
        foreach ($modules as $module) {
            if (isset($module['module'])) {
                $is_function = normalize_path(modules_path().$module['module'].DS.'functions.php', false);
                if (is_file($is_function)) {
                    include_once $is_function;
                    $files[] = ($is_function);
                }
//                $if_config = normalize_path(modules_path().$module['module'].DS.'config.php', false);
//
//
//                if (is_file($if_config)) {
//                    include_once $if_config;
//                    if (isset($config) && isset($config['service_provider'])) {
//                        $loadProviders = [];
//                        if (is_array($config['service_provider'])) {
//                            foreach ($config['service_provider'] as $serviceProvider) {
//                                $loadProviders[] = $serviceProvider;
//                            }
//                        } else {
//                            $loadProviders[] = $config['service_provider'];
//                        }
//                        foreach ($loadProviders as $loadProvider) {
//                            if (class_exists($loadProvider)) {
//                              //  app()->register($loadProvider);
//                            }
//                        }
//                    }
//                }
            }
        }


        // load service providers
        // Register module service providers
        if($app and is_object($app)){
            foreach ($modules as $module) {

                if (isset($module['settings']) and $module['settings'] and isset($module['settings']['service_provider']) and $module['settings']['service_provider']) {

                    app()->module_manager->boot_module($module);

                }
            }
        }

        return $files;
    }
}


function module_info($module_name)
{
    return mw()->module_manager->info($module_name);
}

function module_name_decode($module_name)
{
    $module_name = str_replace('__', '/', $module_name);

    return $module_name;
}

function module_name_encode($module_name)
{
    $module_name = str_replace('/', '__', $module_name);
    $module_name = str_replace('\\', '__', $module_name);

    return $module_name;
}

function module($params)
{
    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $options = $params2;
    }
    $tags = '';
    $em = EMPTY_MOD_STR;
    foreach ($params as $k => $v) {
        if ($k == 'type') {
            $module_name = $v;
        }

        if ($k == 'module') {
            $module_name = $v;
        }

        if ($k == 'data-type') {
            $module_name = $v;
        }

        if ($k != 'display') {
            if (is_array($v)) {
                $v1 = mw()->format->array_to_base64($v);
                $tags .= "{$k}=\"$v1\" ";
            } else {
                $em = str_ireplace('{'.$k.'}', $v, $em);

                $tags .= "{$k}=\"$v\" ";
            }
        }
    }

    $res = mw()->module_manager->load($module_name, $params);
    if (isset($params['wrap']) or isset($params['data-wrap'])) {
        $module_cl = module_css_class($module_name);
        $res = "<div class='module {$module_cl}' {$tags} data-type='{$module_name}'>".$res.'</div>';
    }

    return $res;
}

function is_module($module_name)
{
    return mw()->module_manager->exists($module_name);
}

function is_module_installed($module_name)
{
    return mw()->module_manager->is_installed($module_name);
}

function module_admin_url($module_name = false)
{
    return admin_url() . 'view:modules/load_module:' . module_name_encode(strtolower($module_name));
}

function module_url($module_name = false)
{
    return mw()->module_manager->url($module_name);
}

function module_dir($module_name)
{
    return mw()->module_manager->dir($module_name);
}

function locate_module($module_name, $custom_view = false, $no_fallback_to_view = false)
{
    return mw()->module_manager->locate($module_name, $custom_view, $no_fallback_to_view);
}

api_expose_admin('uninstall_module');

function uninstall_module($params)
{
    return mw()->module_manager->uninstall($params);
}

//event_bind('mw_db_init_modules', 're_init_modules_db');

function re_init_modules_db()
{

    //return mw()->module_manager->update_db();
}

api_expose_admin('install_module');

function install_module($params)
{
    return mw()->module_manager->set_installed($params);
}

function save_module_to_db($data_to_save)
{
    return mw()->module_manager->save($data_to_save);
}

function get_saved_modules_as_template($params)
{
    return mw()->module_manager->get_saved_modules_as_template($params);
}

api_expose_admin('delete_module_as_template');
function delete_module_as_template($data)
{
    return mw()->module_manager->delete_module_as_template($data);
}

api_bind_admin('module/reorder_modules', function ($data) {
    return mw()->module_manager->reorder_modules($data);
});
