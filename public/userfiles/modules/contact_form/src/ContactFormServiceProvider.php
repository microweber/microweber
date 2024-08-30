<?php
namespace MicroweberPackages\Modules\ContactForm;

use Livewire\Livewire;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Modules\ContactForm\Http\Livewire\FormDataPreviewModalComponent;
use MicroweberPackages\Modules\ContactForm\Http\Livewire\IntegrationsModalComponent;
use MicroweberPackages\Modules\ContactForm\Http\Livewire\ListComponent;
use MicroweberPackages\Modules\ContactForm\Http\Livewire\SettingsModalComponent;

class ContactFormServiceProvider extends ServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
    protected $defer = true;


    public function provides() {
        return ['MicroweberPackages\Modules\ContactForm'];
    }
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       Livewire::component('contact-form.integrations-modal', IntegrationsModalComponent::class);
       Livewire::component('contact-form.settings-modal', SettingsModalComponent::class);
       Livewire::component('contact-form.list', ListComponent::class);
       Livewire::component('contact-form.form-data-preview-modal', FormDataPreviewModalComponent::class);

        View::addNamespace('contact-form', normalize_path((__DIR__) . '/resources/views/livewire'));

    }

    public function register()
    {
        $this->loadMigrationsFrom(normalize_path((__DIR__) . '/migrations/'));
        $this->loadRoutesFrom((__DIR__) . '/routes/admin.php');
    }
}
