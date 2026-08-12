<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\LivewireServiceProvider;
use MicroweberPackages\LivewireModal\LivewireModalServiceProvider;
use MicroweberPackages\LivewireModal\Tests\Fixtures\DemoModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedChildModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\NestedParentModal;
use MicroweberPackages\LivewireModal\Tests\Fixtures\OptionsModal;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

/**
 * Standalone Testbench base — validates the package works outside the CMS.
 */
abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addNamespace('livewire-modal-tests', __DIR__.'/views');

        \Livewire\Livewire::component('demo-modal', DemoModal::class);
        \Livewire\Livewire::component('nested-parent-modal', NestedParentModal::class);
        \Livewire\Livewire::component('nested-child-modal', NestedChildModal::class);
        \Livewire\Livewire::component('options-modal', OptionsModal::class);
    }

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LivewireModalServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('livewire-modal.include_css', true);
        $app['config']->set('livewire-modal.include_js', true);
    }
}
