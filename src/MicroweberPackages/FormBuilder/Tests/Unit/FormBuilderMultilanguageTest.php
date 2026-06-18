<?php

namespace MicroweberPackages\FormBuilder\Tests\Unit;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use MicroweberPackages\FormBuilder\FormBuilder;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormBuilderMultilanguageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        // Always reset multilanguage state after tests
        MultilanguageHelpers::setMultilanguageEnabled(false);
        parent::tearDown();
    }

    #[Test]
    public function it_builds_non_translatable_fields_normally_when_multilanguage_disabled(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $fields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'translatable' => false],
            ['name' => 'body', 'type' => 'textarea', 'label' => 'Body', 'translatable' => false],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(2, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
        $this->assertInstanceOf(Textarea::class, $components[1]);
    }

    #[Test]
    public function it_builds_translatable_fields_when_multilanguage_disabled(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        // Even with translatable: true, when multilanguage is disabled
        // the field should be built as a normal field
        $fields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'translatable' => true],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(1, $components);
        // Should still be a TextInput (not transformed)
        $this->assertInstanceOf(TextInput::class, $components[0]);
    }

    #[Test]
    public function it_builds_mixed_translatable_and_non_translatable_fields(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $fields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'translatable' => true],
            ['name' => 'slug', 'type' => 'text', 'label' => 'Slug', 'translatable' => false],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'translatable' => true],
            ['name' => 'sort_order', 'type' => 'number', 'label' => 'Sort Order'],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(4, $components);
    }

    #[Test]
    public function it_translatable_flag_is_preserved_in_field_definition(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'translatable' => true],
            ['name' => 'count', 'type' => 'number', 'translatable' => false],
            ['name' => 'body', 'type' => 'textarea'],
        ];

        // Build with multilanguage disabled — should work fine
        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(3, $components);
    }

    #[Test]
    public function it_builds_translatable_textarea_fields(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $fields = [
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'translatable' => true],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(1, $components);
    }

    #[Test]
    public function it_translatable_fields_from_json_schema(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $json = json_encode([
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'translatable' => true, 'default' => 'Hello'],
            ['name' => 'body', 'type' => 'textarea', 'label' => 'Body', 'translatable' => true],
            ['name' => 'order', 'type' => 'number', 'label' => 'Order', 'translatable' => false],
        ]);

        $components = FormBuilder::fromJson($json)->prefix('options')->build();

        $this->assertCount(3, $components);

        // Defaults should work regardless of translatable flag
        $defaults = FormBuilder::fromJson($json)->prefix('options')->defaultState();
        $this->assertEquals('Hello', $defaults['options.title']);
    }

    #[Test]
    public function it_translatable_fields_inside_sections(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $fields = [
            [
                'type' => 'section',
                'label' => 'Content',
                'schema' => [
                    ['name' => 'title', 'type' => 'text', 'translatable' => true],
                    ['name' => 'body', 'type' => 'textarea', 'translatable' => true],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(1, $components);
    }

    #[Test]
    public function it_translatable_fields_inside_tabs(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        $fields = [
            [
                'type' => 'tabs',
                'label' => 'Settings',
                'tabs' => [
                    [
                        'label' => 'General',
                        'schema' => [
                            ['name' => 'title', 'type' => 'text', 'translatable' => true],
                        ],
                    ],
                    [
                        'label' => 'SEO',
                        'schema' => [
                            ['name' => 'meta_title', 'type' => 'text', 'translatable' => true],
                            ['name' => 'meta_description', 'type' => 'textarea', 'translatable' => true],
                        ],
                    ],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(1, $components);

        // Validation rules should still work
        $fieldsWithValidation = [
            [
                'type' => 'tabs',
                'label' => 'Settings',
                'tabs' => [
                    [
                        'label' => 'General',
                        'schema' => [
                            ['name' => 'title', 'type' => 'text', 'translatable' => true, 'required' => true],
                        ],
                    ],
                ],
            ],
        ];

        $rules = FormBuilder::fromArray($fieldsWithValidation)->prefix('options')->validationRules();
        $this->assertArrayHasKey('options.title', $rules);
    }

    #[Test]
    public function it_builds_full_multilingual_form_scenario(): void
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        // Simulate a real-world module settings form with multilingual fields
        $schema = [
            [
                'type' => 'tabs',
                'label' => 'Module Settings',
                'tabs' => [
                    [
                        'label' => 'Content',
                        'schema' => [
                            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'translatable' => true, 'required' => true, 'default' => 'My Widget'],
                            ['name' => 'subtitle', 'type' => 'text', 'label' => 'Subtitle', 'translatable' => true],
                            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description', 'translatable' => true],
                            ['name' => 'layout', 'type' => 'select', 'label' => 'Layout', 'options' => ['grid' => 'Grid', 'list' => 'List'], 'default' => 'grid'],
                            ['name' => 'items_count', 'type' => 'number', 'label' => 'Items', 'default' => 6, 'options' => ['min' => 1, 'max' => 24]],
                        ],
                    ],
                    [
                        'label' => 'Design',
                        'schema' => [
                            ['name' => 'bg_color', 'type' => 'color_picker', 'label' => 'Background'],
                            ['name' => 'text_color', 'type' => 'color_picker', 'label' => 'Text Color'],
                            ['name' => 'show_border', 'type' => 'toggle', 'label' => 'Show Border', 'default' => false],
                        ],
                    ],
                ],
            ],
        ];

        $json = json_encode($schema);
        $engine = FormBuilder::fromJson($json)->prefix('options');

        // Build components
        $components = $engine->build();
        $this->assertCount(1, $components);

        // Check defaults
        $defaults = FormBuilder::fromJson($json)->prefix('options')->defaultState();
        $this->assertEquals('My Widget', $defaults['options.title']);
        $this->assertEquals('grid', $defaults['options.layout']);
        $this->assertEquals(6, $defaults['options.items_count']);
        $this->assertFalse($defaults['options.show_border']);

        // Check validation
        $rules = FormBuilder::fromJson($json)->prefix('options')->validationRules();
        $this->assertArrayHasKey('options.title', $rules);
    }
}
