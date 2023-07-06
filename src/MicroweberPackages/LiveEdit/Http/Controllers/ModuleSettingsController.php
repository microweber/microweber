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

        if(isset($params['from_url'])) {
            // define constants
            if (!defined('PAGE_ID')) {
                 app()->content_manager->define_constants();
            }
            unset($params['from_url']);
        }


        return view('microweber-live-edit::module-settings', ['moduleId' => $id, 'moduleType' => $type, 'params'=>$params]);
    }
}
