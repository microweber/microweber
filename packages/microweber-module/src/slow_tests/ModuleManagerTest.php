<?php

namespace MicroweberPackages\Module\slow_tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;


class ModuleManagerTest extends TestCase
{

    #[Test]

    public function it_module_css_class(): void {
        $mod = 'shop/admin';
        $test = app()->module_manager->css_class($mod);
        $this->assertEquals($test, 'module-shop-admin');
    }

    #[Test]

    public function it_module_url_and_path(): void {
        $mod = 'Shop';
        $test = app()->module_manager->url($mod);
        $result = Str::endsWith($test, 'shop');

        $this->assertEquals(true, $result);
        $test = app()->module_manager->dir($mod);
        $test2 = normalize_path(modules_path() . 'Shop', true);
        $this->assertEquals($test, $test2);

    }


    #[Test]


    public function it_module_is_installed(): void {
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

    #[Test]

    public function it_if_modules_are_installed_only_once(): void {

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
