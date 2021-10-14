<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class ModuleListTest extends TestCase
{
    public function testModuleIndex()
    {
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules index
        foreach ($getModules as $module) {
            $moduleOutput = app()->parser->process('<module type="' . $module['module'] . '" />');
            $this->assertNotEmpty($moduleOutput);
        }
    }


    public function testModuleAdmin()
    {
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules admin
        foreach ($getModules as $module) {
            if (isset($module['ui_admin']) and $module['ui_admin']) {
                $moduleOutput = app()->parser->process('<module type="' . $module['module'] . '/admin" />');
                $this->assertNotEmpty($moduleOutput);
            }
        }

    }

    public function testLoadFromModuleManager()
    {
        if(!defined('IN_EDIT')){
            define('IN_EDIT',1);
        }
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules
        foreach ($getModules as $i => $module) {
            if($module['ui_admin']){
                $moduleOutput = app()->module_manager->load($module['module'].'/admin', ['id' => 'mod-' . $i]);
                $this->assertNotEmpty($moduleOutput);
            }
            if($module['ui']){
                $moduleOutput = app()->module_manager->load($module['module'], ['id' => 'mod-' . $i]);
                $this->assertNotEmpty($moduleOutput);
            }
        }
    }
}
