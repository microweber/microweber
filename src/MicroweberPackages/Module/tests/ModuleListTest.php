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

        // Test modules index
        foreach ($getModules as $module) {
           $moduleOutput = app()->parser->process('<module type="'.$module['module'].'">');
        }

        // Test modules admin
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);
        foreach ($getModules as $module) {
            $moduleOutput = app()->parser->process('<module type="'.$module['module'].'/admin">');
        }

    }

}
