<?php

declare(strict_types=1);

namespace MicroweberPackages\MicroweberUI\Tests;

use Illuminate\View\Component;
use MicroweberPackages\MicroweberUI\Components\IconPicker;
use MicroweberPackages\MicroweberUI\Components\Tabs;
use MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $this->assertArrayHasKey(
            MicroweberUIServiceProvider::class,
            $this->app->getLoadedProviders(),
            'MicroweberUIServiceProvider should be loaded by the booted app.'
        );
    }

    #[Test]
    public function view_namespace_is_registered(): void
    {
        // boot() registers the src/resources/views dir under the
        // "microweber-ui" namespace via View::prependNamespace().
        $this->assertTrue(
            view()->exists('microweber-ui::components.button'),
            'The microweber-ui::components.button view should resolve via the registered namespace.'
        );
        $this->assertTrue(
            view()->exists('microweber-ui::components.input'),
            'The microweber-ui::components.input view should resolve via the registered namespace.'
        );
    }

    #[Test]
    public function ui_components_are_illuminate_view_components(): void
    {
        $this->assertTrue(class_exists(Tabs::class));
        $this->assertTrue(class_exists(IconPicker::class));
        $this->assertInstanceOf(Component::class, new Tabs());
        $this->assertInstanceOf(Component::class, new IconPicker());
    }
}
