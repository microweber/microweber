<?php

namespace MicroweberPackages\View;


use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\DynamicComponent;
use Illuminate\View\Engines\CompilerEngine;


class ViewServiceProvider extends  \Illuminate\View\ViewServiceProvider
{


    public function  registerBladeEngine($resolver)
    {
        $this->app->singleton('blade.compiler',  function () {
            return new  MicroweberBladeCompiler(
                $this->app['files'],
                $this->app['config']['view.compiled'],
            );
        });

        $resolver->register('blade', function  () {
            return new  CompilerEngine(
                $this->app['blade.compiler']
            );
        });

    }

}
