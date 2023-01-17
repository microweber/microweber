<?php

namespace MicroweberPackages\View;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\LivewireTagCompiler;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerTagCompiler();

        Blade::directive('module', [MicroweberBladeDirectives::class, 'module']);
    }

    protected function registerTagCompiler()
    {
        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(MicroweberModuleTagCompiler::class)->compile($string);
            });
        }
    }


}
