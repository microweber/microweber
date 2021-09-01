<?php

namespace MicroweberPackages\Moddule\tests;

use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use Illuminate\Database\Eloquent\Model;


class ModuleManagerTest extends TestCase
{
    public function testModuleInfo()
    {

        $mod = 'shop';
        $mod2 = 'shop/admin';

        $check = app()->module_manager->info($mod);
        $check2 = app()->module_manager->info($mod2);
        $check3 = module_info($mod2);

        $this->assertEquals($check, $check2);
        $this->assertEquals($check, $check3);
        $this->assertEquals($check2, $check3);

        $info_all = app()->module_repository->getAllModules();

        $found_empty = false;

        foreach ($info_all as $mod){
            $info = module_info($mod['module']);
            if(empty($info)){
                $found_empty = true;
            }

            $info2 = module_info($mod['module'].'/admin');
            if(empty($info2)){
                $found_empty = true;
            }

        }

        $this->assertEquals(false, $found_empty);

    }

    public function testModuleCssClass()
    {
        $mod = 'shop/admin';
        $test = app()->module_manager->css_class($mod);
        $this->assertEquals($test, 'module-shop-admin');
    }

    public function testModuleUrlAndPath()
    {
        $mod = 'shop/admin';
        $test = app()->module_manager->url($mod);
        $result = Str::endsWith($test, '/userfiles/modules/shop/');

        $this->assertEquals(true, $result);
        $test = app()->module_manager->dir($mod);
        $test2 = normalize_path(modules_path() . 'shop', true);
        $this->assertEquals($test, $test2);

    }

    public function testModuleLocate()
    {
        $mod = 'shop/admin';
        $test = app()->module_manager->locate($mod);

        $test2 = normalize_path(modules_path() . 'shop/admin.php', false);
        $this->assertEquals($test, $test2);

    }

    public function testModuleIsInstalled()
    {
        $mod = 'shop';
        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(true, $test);


        $params = [
            'for_module' => $mod
        ];

        app()->module_manager->uninstall($params);
        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(false, $test);

        app()->module_manager->set_installed($params);
        $test = app()->module_manager->is_installed($mod);
        $this->assertEquals(true, $test);

    }

    public function testIfModulesAreInstalledOnlyOnce()
    {

        $db = \DB::table('modules')
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
