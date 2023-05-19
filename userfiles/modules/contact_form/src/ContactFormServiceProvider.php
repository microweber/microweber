<?php
namespace MicroweberPackages\Modules\ContactForm;

use Livewire\Livewire;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
       // Livewire::component('contact-form.list-component', ListComponent::class);
        View::addNamespace('contact-form', normalize_path((__DIR__) . '/resources/views/livewire'));

    }

    public function register()
    {
        $this->loadMigrationsFrom(normalize_path((__DIR__) . '/migrations/'));
        $this->loadRoutesFrom((__DIR__) . '/routes/admin.php');
    }
}
