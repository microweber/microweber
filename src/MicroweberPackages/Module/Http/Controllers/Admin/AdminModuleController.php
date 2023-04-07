<?php

namespace MicroweberPackages\Module\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminModuleController
{
    public function index(Request $request)
    {
        return view('module::admin.index');
    }

    public function view(Request $request)
    {

        $type = $request->get('type', false);
        $type = module_name_decode($type);

        $module_info = module_info($type);

        $module_permissions = module_permissions($module_info);
        $module_denied = true;

        if ($module_permissions) {
            if (user_can_access($module_permissions['index'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['create'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['edit'])) {
                $module_denied = false;
            }
            if (user_can_access($module_permissions['destroy'])) {
                $module_denied = false;
            }
            if ($module_denied) {
                return 'Permission denied';
            }
        }




        if(!is_module($type)){
            return 'No module found';
        }

        return view('module::admin.view', [
            'type' => $type,
        ]);

    }
}
