<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;


class ModuleListTest extends TestCase
{


    public function testLoadFromModuleManager()
    {

        $getModules = app()->module_repository->getAllModules();

        // Test modules
        foreach ($getModules as $i => $module) {

            $this->assertNotEmpty($module->getName());

        }

    }
}
