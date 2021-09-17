<?php

namespace MicroweberPackages\Moddule\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class ModuleListTest extends TestCase
{
    public function testModuleIndex()
    {

        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        // Test modules index
        foreach ($getModules as $module) {
            $moduleOutput = app()->parser->process('<module type="' . $module['module'] . '">');
        }
    }


    public function testModuleAdmin()
    {

        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        // Test modules admin
        foreach ($getModules as $module) {
            $moduleOutput = app()->parser->process('<module type="'.$module['module'].'/admin">');
        }

    }
}
