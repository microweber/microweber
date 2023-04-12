<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;


class ModuleSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $type = module_name_decode($params['type']);
        $id = $params['id'];
        $hasError = false;
//        try {
//
//            $output = view('live-edit::module_settings', ['moduleId' => $id, 'moduleType' => $type]);
//
//        } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }catch (\InvalidArgumentException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }catch (\Illuminate\View\ViewException $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        } catch (\Exception $e) {
//            $hasError = true;
//            $output = $e->getMessage();
//        }
//
//
//        if($hasError){
//            $output = view('live-edit::module_settings_error', ['moduleId' => $id, 'moduleType' => $type, 'error' => $output]);
//        }

        return view('live-edit::module-settings', ['moduleId' => $id, 'moduleType' => $type]);
    }
}
