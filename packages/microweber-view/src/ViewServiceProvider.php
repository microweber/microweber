<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use MicroweberPackages\View\Contracts\ModuleProcessorInterface;
use MicroweberPackages\View\Support\CmsModuleProcessorAdapter;
use MicroweberPackages\View\Support\NullModuleProcessor;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/microweber-view.php', 'microweber-view');

        $this->app->singleton(StringBlade::class, static fn () => new StringBlade());
        $this->app->alias(StringBlade::class, 'microweber.string_blade');

        $this->app->singleton(TwigView::class, static fn () => new TwigView());
        $this->app->alias(TwigView::class, 'microweber.twig_view');

        $this->app->singleton(MicroweberModuleTagCompiler::class, function (Application $app): MicroweberModuleTagCompiler {
            /** @var BladeCompiler $blade */
            $blade = $app->make('blade.compiler');

            return new MicroweberModuleTagCompiler([], [], $blade);
        });

        $this->app->singleton(ModuleProcessorInterface::class, function (Application $app): ModuleProcessorInterface {
            // Prefer CMS parser when present
            if ($app->bound('parser')) {
                $parser = $app->make('parser');
                if (is_object($parser)) {
                    return new CmsModuleProcessorAdapter($parser);
                }
            }

            return new NullModuleProcessor();
        });
    }

    public function boot(): void
    {
        if ((bool) config('microweber-view.module_directive_enabled', true)) {
            $this->registerTagCompiler();
            Blade::directive('module', [MicroweberBladeDirectives::class, 'module']);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/microweber-view.php' => config_path('microweber-view.php'),
            ], 'microweber-view-config');
        }
    }

    protected function registerTagCompiler(): void
    {
        /** @var BladeCompiler $compiler */
        $compiler = $this->app->make('blade.compiler');

        $compiler->precompiler(function (string $string): string {
            return $this->app->make(MicroweberModuleTagCompiler::class)->compile($string);
        });
    }
}
