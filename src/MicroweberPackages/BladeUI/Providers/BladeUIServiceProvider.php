<?php

namespace MicroweberPackages\BladeUI\Providers;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Illuminate\Support\ServiceProvider;

class BladeUIServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/blade-icons.php', 'blade-icons');
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-heroicons.php', 'blade-heroicons');

        $defaltPath = config('blade-icons.sets.default.path');
        if($defaltPath){
            $defaltPath = normalize_path($defaltPath, true);
            if(!is_dir($defaltPath)){
                mkdir_recursive($defaltPath);
            }
        }


        $this->app->register(BladeIconsServiceProvider::class);
        $this->app->register(BladeHeroiconsServiceProvider::class);
    }

}
