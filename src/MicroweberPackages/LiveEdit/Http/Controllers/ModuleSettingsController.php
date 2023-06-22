<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;


class ModuleSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $type = module_name_decode($params['type']);
        if(!isset($params['id'])){
            $params['id'] = 'module-'.crc32($type);
        }
        $id = $params['id'];
        $hasError = false;


        return view('microweber-live-edit::module-settings', ['moduleId' => $id, 'moduleType' => $type, 'params'=>$params]);
    }
}
