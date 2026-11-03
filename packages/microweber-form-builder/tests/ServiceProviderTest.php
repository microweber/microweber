<?php

declare(strict_types=1);

namespace MicroweberPackages\FormBuilder\Tests;

use MicroweberPackages\FormBuilder\FieldTypeRegistry;
use MicroweberPackages\FormBuilder\FormBuilder;
use MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_boots_and_is_registered(): void
    {
        $this->assertArrayHasKey(
            FormBuilderServiceProvider::class,
            $this->app->getLoadedProviders(),
            'FormBuilderServiceProvider should be loaded by the booted app.'
        );
    }

    #[Test]
    public function field_type_registry_is_a_bound_singleton(): void
    {
        $first = $this->app->make(FieldTypeRegistry::class);
        $second = $this->app->make(FieldTypeRegistry::class);

        $this->assertInstanceOf(FieldTypeRegistry::class, $first);
        $this->assertSame($first, $second, 'FieldTypeRegistry should resolve as a singleton.');
    }

    #[Test]
    public function form_builder_is_a_bound_singleton(): void
    {
        $this->assertInstanceOf(FormBuilder::class, $this->app->make(FormBuilder::class));
        $this->assertSame(
            $this->app->make(FormBuilder::class),
            $this->app->make(FormBuilder::class)
        );
    }

    #[Test]
    public function boot_registers_the_default_field_types(): void
    {
        // FormBuilderServiceProvider::boot() runs the Filament + Microweber
        // resolvers, which register their type keys into the singleton registry.
        $registry = $this->app->make(FieldTypeRegistry::class);

        foreach (['text', 'textarea', 'select', 'checkbox', 'toggle'] as $type) {
            $this->assertTrue(
                $registry->has($type),
                "The default field type '{$type}' should be registered on boot."
            );
        }

        // Microweber custom types are registered too.
        $this->assertTrue($registry->has('image'));
        $this->assertTrue($registry->has('rich_editor'));
        $this->assertNotEmpty($registry->getRegisteredTypes());
    }
}
