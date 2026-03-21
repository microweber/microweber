<?php

namespace MicroweberPackages\LiveEdit\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Http\Livewire\VisualEditor\VisualEditorComponent;

/**
 * Visual Editor Service Provider
 *
 * Registers the visual drag-and-drop editor components and configuration.
 *
 * @package MicroweberPackages\LiveEdit\Providers
 */
class VisualEditorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/visual-editor.php',
            'visual-editor'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Livewire components
        Livewire::component('visual-editor', VisualEditorComponent::class);

        // Register views
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'microweber-live-edit'
        );

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/visual-editor.php' => config_path('visual-editor.php'),
        ], 'visual-editor-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/microweber-live-edit'),
        ], 'visual-editor-views');
    }
}
