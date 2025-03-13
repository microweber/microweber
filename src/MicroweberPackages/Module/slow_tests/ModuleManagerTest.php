<?php

namespace MicroweberPackages\Module\slow_tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;


class ModuleManagerTest extends TestCase
{

    public function testModuleCssClass()
    {
        $mod = 'shop/admin';
        $test = app()->module_manager->css_class($mod);
        $this->assertEquals($test, 'module-shop-admin');
    }

    public function testModuleUrlAndPath()
    {
        $mod = 'Shop';
        $test = app()->module_manager->url($mod);
        $result = Str::endsWith($test, 'shop');

        $this->assertEquals(true, $result);
        $test = app()->module_manager->dir($mod);
        $test2 = normalize_path(modules_path() . 'Shop', true);
        $this->assertEquals($test, $test2);

    }


    public function testModuleIsInstalled()
    {
        $mod = 'Shop';
        $params = [
            'for_module' => $mod
        ];
        app()->module_manager->set_installed($params);

        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(true, $test);



        app()->module_manager->uninstall($params);
        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(false, $test);

        app()->module_manager->set_installed($params);
        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(true, $test);

    }

    public function testIfModulesAreInstalledOnlyOnce()
    {

        $db = DB::table('modules')
            ->select('module',  \DB::raw('count(module) as total'))
            ->groupBy('module')
            ->get();

        $foundMoreThanOnce = false;
        if($db){
            foreach ($db as $item){
                if($item->total > 1){
                    $foundMoreThanOnce = $item->total;
                }
            }
        }

        $this->assertEquals(false, $foundMoreThanOnce);

    }
}
