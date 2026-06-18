<?php

namespace MicroweberPackages\FormBuilder\Tests\Unit;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use MicroweberPackages\FormBuilder\Contracts\FieldTypeInterface;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FieldTypeRegistryTest extends TestCase
{
    protected FieldTypeRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $this->registry = new FieldTypeRegistry();
    }

    #[Test]
    public function it_registers_a_field_type_with_closure(): void
    {
        $this->registry->register('custom', function (array $field) {
            return TextInput::make($field['name']);
        });

        $this->assertTrue($this->registry->has('custom'));
    }

    #[Test]
    public function it_registers_a_field_type_with_interface(): void
    {
        $resolver = new class implements FieldTypeInterface {
            public function resolve(array $field): Component
            {
                return TextInput::make($field['name']);
            }
        };

        $this->registry->register('custom_interface', $resolver);

        $this->assertTrue($this->registry->has('custom_interface'));
    }

    #[Test]
    public function it_resolves_a_registered_type(): void
    {
        $this->registry->register('text', function (array $field) {
            return TextInput::make($field['name'])->label('Test');
        });

        $component = $this->registry->resolve('text', ['name' => 'my_field']);

        $this->assertInstanceOf(TextInput::class, $component);
    }

    #[Test]
    public function it_throws_on_unknown_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown field type: 'nonexistent'");

        $this->registry->resolve('nonexistent', ['name' => 'test']);
    }

    #[Test]
    public function it_can_override_existing_type(): void
    {
        $this->registry->register('text', function (array $field) {
            return TextInput::make($field['name'])->label('Original');
        });

        $this->registry->register('text', function (array $field) {
            return TextInput::make($field['name'])->label('Overridden');
        });

        $component = $this->registry->resolve('text', ['name' => 'test']);
        $this->assertInstanceOf(TextInput::class, $component);
    }

    #[Test]
    public function it_returns_registered_types(): void
    {
        $this->registry->register('text', fn($f) => TextInput::make($f['name']));
        $this->registry->register('number', fn($f) => TextInput::make($f['name']));

        $types = $this->registry->getRegisteredTypes();

        $this->assertContains('text', $types);
        $this->assertContains('number', $types);
    }

    #[Test]
    public function it_can_unregister_a_type(): void
    {
        $this->registry->register('text', fn($f) => TextInput::make($f['name']));
        $this->assertTrue($this->registry->has('text'));

        $this->registry->unregister('text');
        $this->assertFalse($this->registry->has('text'));
    }

    #[Test]
    public function it_can_clear_all_types(): void
    {
        $this->registry->register('a', fn($f) => TextInput::make($f['name']));
        $this->registry->register('b', fn($f) => TextInput::make($f['name']));

        $this->registry->clear();

        $this->assertEmpty($this->registry->getRegisteredTypes());
    }

    #[Test]
    public function it_default_types_are_registered_via_service_provider(): void
    {
        // The service provider should have registered all default types
        $registry = app(FieldTypeRegistry::class);

        $expectedTypes = [
            'text', 'textarea', 'number', 'select', 'toggle',
            'toggle_buttons', 'checkbox', 'color', 'date', 'datetime',
            'hidden', 'placeholder', 'radio',
            // Mw custom types
            'image', 'file_upload', 'color_picker', 'icon', 'icon_picker',
            'link', 'link_picker', 'slider', 'range_slider', 'rich_editor',
            'media_browser', 'title_with_slug',
        ];

        foreach ($expectedTypes as $type) {
            $this->assertTrue($registry->has($type), "Expected type '{$type}' to be registered");
        }
    }
}
