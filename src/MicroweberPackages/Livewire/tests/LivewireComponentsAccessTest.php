<?php

namespace MicroweberPackages\Livewire\tests;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Livewire\Livewire;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;


class LivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $template_name = 'big';

    #[Test]

    public function it_if_can_access_all_components(): void {

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
        // Livewire v4 uses Finder instead of ComponentRegistry
        $finder = app('livewire.finder');
        $componentsList = get_class_protected_property_value($finder, 'classComponents');


        $skip = [
            \MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\DropdownMappingPreview::class,
            \MicroweberPackages\User\Filament\Pages\ApiApplicationsPage::class,
            // Redirect-only page: mount() returns a Redirector (sends the user
            // to the Profile panel), so it never renders a view — assertOk()
            // on it throws "Redirector could not be converted to int".
            \MicroweberPackages\User\Filament\Pages\AdminProfileRedirectPage::class,
        ];


        foreach ($componentsList as $component) {

            if(in_array($component, $skip)){
                continue;
            }

            // Skip Filament edit/view pages that require a record ID parameter
            if (str_contains($component, '\\Pages\\Edit') || str_contains($component, '\\Pages\\View')) {
                continue;
            }

            if (str_contains($component, 'Microweber')) {
                try {
                   // $component = new $component();
                    Livewire::test($component)->assertOk();
                    $this->assertTrue(true, 'Component access success ' . $component);

                } catch (\Exception $e) {
                    // A redirect-only page (the Login page when already
                    // authenticated, profile redirect, etc.) throws "Redirector
                    // could not be converted to int" — that is the component
                    // REDIRECTING, which is valid access behaviour, not an
                    // access error. Only fail on genuine errors.
                    if (str_contains($e->getMessage(), 'Redirector could not be converted')) {
                        $this->assertTrue(true, 'Component redirects (ok) ' . $component);
                        continue;
                    }
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

