<?php

namespace Modules\Form\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Form\Tools\FormActivitySummaryTool;
use Modules\Form\Tools\FormLookupTool;
use Modules\Form\Tools\FormSubmissionDetailTool;
use Modules\Form\Tools\FormSubmissionSearchTool;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\CustomField\FieldsManager;
use Modules\Form\FormsManager;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Form\Filament\Resources\FormEntryResource;

class FormServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'Form';

    protected string $moduleNameLower = 'form';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerAiTools([
            FormActivitySummaryTool::class,
            FormLookupTool::class,
            FormSubmissionDetailTool::class,
            FormSubmissionSearchTool::class,
        ]);



        /**
         * @property \Modules\Form\FormsManager $forms_manager
         */
        $this->app->singleton('forms_manager', function ($app) {
            return new FormsManager();
        });
   //     $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations/');
       // $this->loadRoutesFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'routes/api_public.php');

    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        /**
         * @property \Modules\Form\FormsManager $forms_manager
         */
        $this->app->singleton('forms_manager', function ($app) {
            return new FormsManager();
        });

        Validator::extendImplicit('valid_image', \Modules\Form\Validators\ImageValidator::class.'@validate', 'Invalid image file');

        FilamentRegistry::registerResource(FormEntryResource::class);


    }

}
