<?php

declare(strict_types=1);

namespace MicroweberPackages\FormBuilder\Tests;

use MicroweberPackages\FormBuilder\Dto\FieldDefinition;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;
use MicroweberPackages\FormBuilder\FormBuilder;
use PHPUnit\Framework\Attributes\Test;

/**
 * Pure-unit coverage of the registry and DTO logic — no Filament components are
 * instantiated, so these assertions exercise the package's own code directly.
 */
class FieldTypeRegistryTest extends TestCase
{
    #[Test]
    public function register_has_unregister_and_clear_behave(): void
    {
        $registry = new FieldTypeRegistry();

        $this->assertFalse($registry->has('demo'));

        $registry->register('demo', fn (array $field) => $field['name']);
        $this->assertTrue($registry->has('demo'));
        $this->assertContains('demo', $registry->getRegisteredTypes());

        // A callable resolver is invoked and its result returned.
        $this->assertSame('foo', $registry->resolve('demo', ['name' => 'foo']));

        $registry->unregister('demo');
        $this->assertFalse($registry->has('demo'));

        $registry->register('a', fn () => null);
        $registry->register('b', fn () => null);
        $registry->clear();
        $this->assertSame([], $registry->getRegisteredTypes());
    }

    #[Test]
    public function resolve_throws_on_unknown_type(): void
    {
        $registry = new FieldTypeRegistry();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown field type: 'nope'");

        $registry->resolve('nope', ['name' => 'x']);
    }

    #[Test]
    public function form_builder_from_json_rejects_invalid_json(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON');

        FormBuilder::fromJson('{ not valid json ');
    }

    #[Test]
    public function field_definition_maps_data_with_defaults_and_extra_keys(): void
    {
        $def = FieldDefinition::fromArray([
            'name' => 'title',
            'type' => 'text',
            'required' => 1,
            'custom_flag' => 'kept',
        ]);

        $this->assertSame('title', $def->name);
        $this->assertSame('text', $def->type);
        $this->assertTrue($def->required);
        $this->assertNull($def->label);
        $this->assertSame([], $def->options);
        $this->assertArrayHasKey('custom_flag', $def->extra);
        $this->assertSame('kept', $def->extra['custom_flag']);
    }
}
