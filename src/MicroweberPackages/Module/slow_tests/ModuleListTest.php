<?php

namespace MicroweberPackages\Module\slow_tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;


class ModuleListTest extends TestCase
{


    #[Test]


    public function it_load_from_module_manager(): void {

        $getModules = app()->module_repository->getAllModules();

        // Test modules
        foreach ($getModules as $i => $module) {

            $this->assertNotEmpty($module->getName());

        }

    }
}
