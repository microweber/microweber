<?php

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
                $em = str_ireplace('{' . $k . '}', $v, $em);

                $tags .= "{$k}=\"$v\" ";
            }
        }
    }

    $res = mw()->module_manager->load($module_name, $params);
    if (isset($params['wrap']) or isset($params['data-wrap'])) {
        $module_cl = module_css_class($module_name);
        $res = "<div class='module {$module_cl}' {$tags} data-type='{$module_name}'>" . $res . '</div>';
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
    $module = module_info($module_name);


    $urlFromModuleAdmin = \MicroweberPackages\Module\Facades\ModuleAdmin::getAdminUrl($module_name);
    if($urlFromModuleAdmin){
        return $urlFromModuleAdmin;
    }

    if (isset($module['settings']['routes']['admin'])) {
        if (Route::has($module['settings']['routes']['admin'])) {
            return route($module['settings']['routes']['admin']);
        }
    }

    return admin_url() . 'module/view?type=' . module_name_encode(strtolower($module_name));
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


//event_bind('mw_db_init_modules', 're_init_modules_db');






function get_saved_modules_as_template($params)
{
    return mw()->module_manager->get_saved_modules_as_template($params);
}

api_expose_admin('delete_module_as_template');
function delete_module_as_template($data)
{
    return mw()->module_manager->delete_module_as_template($data);
}

