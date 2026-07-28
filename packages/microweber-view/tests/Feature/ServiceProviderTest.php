<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Feature;

use MicroweberPackages\View\Contracts\ModuleProcessorInterface;
use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\TwigView;
use MicroweberPackages\View\ViewServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_is_loaded(): void
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(ViewServiceProvider::class)
            || class_exists(ViewServiceProvider::class)
        );
    }

    #[Test]
    public function bindings_resolve(): void
    {
        $this->assertInstanceOf(StringBlade::class, app(StringBlade::class));
        $this->assertInstanceOf(TwigView::class, app(TwigView::class));
        $this->assertInstanceOf(ModuleProcessorInterface::class, app(ModuleProcessorInterface::class));
    }

    #[Test]
    public function config_is_merged(): void
    {
        $this->assertNotNull(config('microweber-view'));
        $this->assertIsBool((bool) config('microweber-view.module_directive_enabled'));
    }
}
