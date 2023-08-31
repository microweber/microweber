<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Assert as PHPUnit;
/**
 * @runTestsInSeparateProcesses
 */
class ModuleListTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    private function testModuleIndex()
    {
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules index
        foreach ($getModules as $module) {

            $moduleOutput = app()->parser->process('<module type="' . $module['module'] . '" />');

            // Looking for parser errors
            foreach ($this->parserErrorStrings as $errorString) {
                PHPUnit::assertFalse(str_contains($moduleOutput, $errorString));
            }

            $this->assertNotEmpty($moduleOutput);
        }
    }


    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testModuleAdmin()
    {
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules admin
        foreach ($getModules as $module) {
            if (isset($module['ui_admin']) and $module['ui_admin']) {

                $moduleOutput = app()->parser->process('<module type="' . $module['module'] . '/admin" />');

                // Looking for parser errors
                foreach ($this->parserErrorStrings as $errorString) {
                    PHPUnit::assertFalse(str_contains($moduleOutput, $errorString));
                }

               $this->assertNotEmpty($moduleOutput);

            }
        }

    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testLoadFromModuleManager()
    {
        if (!defined('IN_EDIT')) {
            define('IN_EDIT', 1);
        }
        $getModules = app()->module_repository->getAllModules();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Test modules
        foreach ($getModules as $i => $module) {

            if (intval($module['installed']) == 1) {
                if ($module['ui_admin']) {

                    $moduleOutput = app()->module_manager->load($module['module'] . '/admin', ['id' => 'mod-admin-' . $i ]);

                    // Looking for parser errors
                    foreach ($this->parserErrorStrings as $errorString) {

                        PHPUnit::assertFalse(str_contains($moduleOutput, $errorString), 'Found module error: ' . $errorString. ' in module: ' . $module['module']);
                    }

                    $this->assertNotEmpty($moduleOutput, 'Module output is empty: ' . $module['module']);
                }
                if ($module['ui']) {
                    $moduleOutput = app()->module_manager->load($module['module'], ['id' => 'mod-' . $i]);

                    // Looking for parser errors
                    foreach ($this->parserErrorStrings as $errorString) {
                        PHPUnit::assertFalse(str_contains($moduleOutput, $errorString));
                    }

                    $this->assertNotEmpty($moduleOutput, 'Module output is empty: ' . $module['module']);
                }
            }
        }
    }
}
