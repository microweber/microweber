<?php

namespace MicroweberPackages\Livewire\tests;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Livewire\Livewire;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;


class LivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $template_name = 'big';

    public function testIfCanAccessAllComponents()
    {

        $this->assertTrue(mw_is_installed());

        save_option('current_template', $this->template_name, 'template');

        $system_refresh = new \MicroweberPackages\Install\DbInstaller();
        $system_refresh->createSchema();
        app()->rebootApplication();
        try {


        } catch (\Exception $e) {


        }

       // load_all_service_providers_for_modules();
       // load_all_functions_files_for_modules();
       // load_service_providers_for_template();
       // load_functions_files_for_template();

        $migrator = app()->mw_migrator->run(app()->migrator->paths());

        $this->actingAsAdmin();
       // $componentsList = Livewire::getComponentAliases();
        $componentsList = app(\Livewire\Mechanisms\ComponentRegistry::class);
        $componentsList = get_class_protected_property_value($componentsList, 'aliases');


        $skip = [
            \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\DropdownMappingPreview::class,
        ];


        foreach ($componentsList as $component) {

            if(in_array($component, $skip)){
                continue;
            }

            if (str_contains($component, 'Microweber')) {
                try {
                   // $component = new $component();
                    Livewire::test($component)->assertOk();
                    $this->assertTrue(true, 'Component access success ' . $component);

                } catch (\Exception $e) {
                    // continue;
                    $this->assertTrue(false, 'Component access error ' . $component . ' ' . $e->getMessage());
                }

            }
        }

    }


    public function setUp(): void
    {

        if (!$this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            call_user_func($callback);
        }

        Facade::clearResolvedInstances();

        Model::setEventDispatcher($this->app['events']);

        $this->setUpHasRun = true;

    }
}

