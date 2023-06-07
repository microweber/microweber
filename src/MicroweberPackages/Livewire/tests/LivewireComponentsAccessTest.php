<?php

namespace MicroweberPackages\Livewire\tests;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Livewire\Livewire;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;


class LivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{

    public function testIfCanAccessAllComponents()
    {

        load_all_service_providers_for_modules();
        load_all_functions_files_for_modules();
        load_service_providers_for_template();
        load_functions_files_for_template();

        $migrator = app()->mw_migrator->run(app()->migrator->paths());

        $this->actingAsAdmin();
        $componentsList = Livewire::getComponentAliases();
        foreach ($componentsList as $component) {

            if(str_contains($component, 'Microweber')){
                Livewire::test($component)->assertOk();
            }
        }

    }


    public function setUp() : void
    {

        if (! $this->app) {
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

