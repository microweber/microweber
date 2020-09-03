<?php
namespace MicroweberPackages\CustomField;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Database\Observers\CreatedByObserver;

use MicroweberPackages\CustomField\Models\CustomField;



class CustomFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        CustomField::observe(CreatedByObserver::class);
    }
}
