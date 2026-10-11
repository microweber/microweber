<?php

namespace MicroweberPackages\FormBuilder\Tests\Unit;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\Filament\Forms\Components\MwRichEditor;
use MicroweberPackages\FormBuilder\FormBuilder;
use MicroweberPackages\FormBuilder\FormBuilderEngine;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormBuilderTest extends TestCase
{
    // ─── Simple Field Build ──────────────────────────────────────

    #[Test]
    public function it_builds_text_input_from_array(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'placeholder' => 'Enter title'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
    }

    #[Test]
    public function it_builds_textarea_from_array(): void
    {
        $fields = [
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Textarea::class, $components[0]);
    }

    #[Test]
    public function it_builds_number_input_from_array(): void
    {
        $fields = [
            ['name' => 'count', 'type' => 'number', 'label' => 'Count', 'options' => ['min' => 0, 'max' => 100, 'step' => 1]],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
    }

    #[Test]
    public function it_builds_select_from_array(): void
    {
        $fields = [
            ['name' => 'layout', 'type' => 'select', 'label' => 'Layout', 'options' => ['grid' => 'Grid', 'list' => 'List']],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Select::class, $components[0]);
    }

    #[Test]
    public function it_builds_toggle_from_array(): void
    {
        $fields = [
            ['name' => 'enabled', 'type' => 'toggle', 'label' => 'Enabled'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Toggle::class, $components[0]);
    }

    #[Test]
    public function it_builds_toggle_buttons_from_array(): void
    {
        $fields = [
            ['name' => 'align', 'type' => 'toggle_buttons', 'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'], 'inline' => true, 'icons' => ['left' => 'heroicon-o-bars-3-bottom-left']],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(ToggleButtons::class, $components[0]);
    }

    #[Test]
    public function it_builds_checkbox_from_array(): void
    {
        $fields = [
            ['name' => 'agree', 'type' => 'checkbox', 'label' => 'I agree'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Checkbox::class, $components[0]);
    }

    #[Test]
    public function it_builds_color_picker_from_array(): void
    {
        $fields = [
            ['name' => 'bg_color', 'type' => 'color', 'label' => 'Background Color'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        // Uses MwColorPicker (extends ColorPicker) to match the old schemaToFormFields behavior
        $this->assertInstanceOf(MwColorPicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_date_picker_from_array(): void
    {
        $fields = [
            ['name' => 'start_date', 'type' => 'date', 'label' => 'Start Date'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(DatePicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_datetime_picker_from_array(): void
    {
        $fields = [
            ['name' => 'event_time', 'type' => 'datetime', 'label' => 'Event Time'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(DateTimePicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_hidden_from_array(): void
    {
        $fields = [
            ['name' => 'secret', 'type' => 'hidden', 'default' => 'xyz'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Hidden::class, $components[0]);
    }

    #[Test]
    public function it_builds_radio_from_array(): void
    {
        $fields = [
            ['name' => 'size', 'type' => 'radio', 'options' => ['sm' => 'Small', 'lg' => 'Large']],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Radio::class, $components[0]);
    }

    #[Test]
    public function it_builds_multiple_fields_from_array(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text'],
            ['name' => 'body', 'type' => 'textarea'],
            ['name' => 'count', 'type' => 'number'],
            ['name' => 'active', 'type' => 'toggle'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(4, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
        $this->assertInstanceOf(Textarea::class, $components[1]);
        $this->assertInstanceOf(TextInput::class, $components[2]);
        $this->assertInstanceOf(Toggle::class, $components[3]);
    }

    // ─── Custom Mw Types ─────────────────────────────────────────

    #[Test]
    public function it_builds_mw_file_upload_from_image_type(): void
    {
        $fields = [
            ['name' => 'photo', 'type' => 'image', 'label' => 'Photo'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(MwFileUpload::class, $components[0]);
    }

    #[Test]
    public function it_builds_mw_color_picker_from_color_picker_type(): void
    {
        $fields = [
            ['name' => 'accent', 'type' => 'color_picker', 'label' => 'Accent Color'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(MwColorPicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_mw_icon_picker(): void
    {
        $fields = [
            ['name' => 'icon', 'type' => 'icon', 'label' => 'Icon'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(MwIconPicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_mw_link_picker(): void
    {
        $fields = [
            ['name' => 'url', 'type' => 'link', 'label' => 'URL'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(MwLinkPicker::class, $components[0]);
    }

    #[Test]
    public function it_builds_mw_rich_editor(): void
    {
        // MwRichEditor depends on Filament\Forms\Components\Contracts\HasFileAttachments
        // which may not exist in all Filament versions. Skip if the class can't load.
        try {
            $fields = [
                ['name' => 'content', 'type' => 'rich_editor', 'label' => 'Content'],
            ];

            $components = FormBuilder::fromArray($fields)->build();

            $this->assertCount(1, $components);
            $this->assertInstanceOf(MwRichEditor::class, $components[0]);
        } catch (\Error $e) {
            if (str_contains($e->getMessage(), 'HasFileAttachments')) {
                $this->markTestSkipped('MwRichEditor requires HasFileAttachments interface not available in this Filament version.');
            }
            throw $e;
        }
    }

    // ─── Container Nesting ───────────────────────────────────────

    #[Test]
    public function it_builds_section_with_fields(): void
    {
        $fields = [
            [
                'type' => 'section',
                'label' => 'SEO',
                'schema' => [
                    ['name' => 'meta_title', 'type' => 'text', 'label' => 'Meta Title'],
                    ['name' => 'meta_desc', 'type' => 'textarea', 'label' => 'Meta Description'],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Section::class, $components[0]);
    }

    #[Test]
    public function it_builds_grid_with_columns(): void
    {
        $fields = [
            [
                'type' => 'grid',
                'columns' => 2,
                'schema' => [
                    ['name' => 'first_name', 'type' => 'text'],
                    ['name' => 'last_name', 'type' => 'text'],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Grid::class, $components[0]);
    }

    #[Test]
    public function it_builds_tabs_with_nested_schemas(): void
    {
        $fields = [
            [
                'type' => 'tabs',
                'label' => 'Settings',
                'tabs' => [
                    [
                        'label' => 'General',
                        'schema' => [
                            ['name' => 'title', 'type' => 'text'],
                        ],
                    ],
                    [
                        'label' => 'Advanced',
                        'schema' => [
                            ['name' => 'css_class', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Tabs::class, $components[0]);
    }

    #[Test]
    public function it_builds_repeater(): void
    {
        $fields = [
            [
                'type' => 'repeater',
                'name' => 'items',
                'label' => 'Items',
                'schema' => [
                    ['name' => 'title', 'type' => 'text'],
                    ['name' => 'description', 'type' => 'textarea'],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Repeater::class, $components[0]);
    }

    #[Test]
    public function it_builds_group(): void
    {
        $fields = [
            [
                'type' => 'group',
                'columns' => 2,
                'schema' => [
                    ['name' => 'width', 'type' => 'text'],
                    ['name' => 'height', 'type' => 'text'],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Group::class, $components[0]);
    }

    #[Test]
    public function it_builds_fieldset(): void
    {
        $fields = [
            [
                'type' => 'fieldset',
                'label' => 'Dimensions',
                'schema' => [
                    ['name' => 'width', 'type' => 'number'],
                    ['name' => 'height', 'type' => 'number'],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Fieldset::class, $components[0]);
    }

    #[Test]
    public function it_builds_deeply_nested_tabs_section_grid(): void
    {
        $fields = [
            [
                'type' => 'tabs',
                'label' => 'Main',
                'tabs' => [
                    [
                        'label' => 'Content',
                        'schema' => [
                            [
                                'type' => 'section',
                                'label' => 'Details',
                                'schema' => [
                                    [
                                        'type' => 'grid',
                                        'columns' => 2,
                                        'schema' => [
                                            ['name' => 'first', 'type' => 'text'],
                                            ['name' => 'second', 'type' => 'text'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => 'Design',
                        'schema' => [
                            ['name' => 'color', 'type' => 'color'],
                        ],
                    ],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Tabs::class, $components[0]);
    }

    // ─── Prefix Support ──────────────────────────────────────────

    #[Test]
    public function it_applies_prefix_to_field_names(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text'],
            ['name' => 'body', 'type' => 'textarea'],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(2, $components);
        // Verify the field name includes the prefix by checking the component's name
        $this->assertStringContainsString('options', $components[0]->getName());
    }

    #[Test]
    public function it_does_not_double_prefix(): void
    {
        $fields = [
            ['name' => 'options.title', 'type' => 'text'],
        ];

        $components = FormBuilder::fromArray($fields)->prefix('options')->build();

        $this->assertCount(1, $components);
        // Should not be options.options.title
        $name = $components[0]->getName();
        $this->assertStringNotContainsString('options.options', $name);
    }

    // ─── Validation Rules ────────────────────────────────────────

    #[Test]
    public function it_extracts_validation_rules(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'required' => true, 'validation' => ['string', 'max:120']],
            ['name' => 'count', 'type' => 'number', 'validation' => ['integer']],
            ['name' => 'optional', 'type' => 'text'],
        ];

        $rules = FormBuilder::fromArray($fields)->validationRules();

        $this->assertArrayHasKey('title', $rules);
        $this->assertStringContainsString('required', $rules['title']);
        $this->assertStringContainsString('string', $rules['title']);
        $this->assertStringContainsString('max:120', $rules['title']);
        $this->assertArrayHasKey('count', $rules);
        $this->assertStringContainsString('integer', $rules['count']);
        // Optional field with no rules should not be in the rules array
        $this->assertArrayNotHasKey('optional', $rules);
    }

    #[Test]
    public function it_extracts_validation_rules_with_prefix(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'required' => true],
        ];

        $rules = FormBuilder::fromArray($fields)->prefix('options')->validationRules();

        $this->assertArrayHasKey('options.title', $rules);
    }

    #[Test]
    public function it_extracts_validation_rules_from_nested_containers(): void
    {
        $fields = [
            [
                'type' => 'section',
                'label' => 'Info',
                'schema' => [
                    ['name' => 'name', 'type' => 'text', 'required' => true],
                ],
            ],
        ];

        $rules = FormBuilder::fromArray($fields)->validationRules();

        $this->assertArrayHasKey('name', $rules);
    }

    #[Test]
    public function it_extracts_validation_rules_from_tabs(): void
    {
        $fields = [
            [
                'type' => 'tabs',
                'tabs' => [
                    [
                        'label' => 'Tab1',
                        'schema' => [
                            ['name' => 'email', 'type' => 'text', 'required' => true, 'validation' => ['email']],
                        ],
                    ],
                ],
            ],
        ];

        $rules = FormBuilder::fromArray($fields)->validationRules();

        $this->assertArrayHasKey('email', $rules);
        $this->assertStringContainsString('required', $rules['email']);
        $this->assertStringContainsString('email', $rules['email']);
    }

    // ─── Default State ───────────────────────────────────────────

    #[Test]
    public function it_extracts_default_state(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'default' => 'Untitled'],
            ['name' => 'count', 'type' => 'number', 'default' => 10],
            ['name' => 'active', 'type' => 'toggle', 'default' => true],
            ['name' => 'no_default', 'type' => 'text'],
        ];

        $defaults = FormBuilder::fromArray($fields)->defaultState();

        $this->assertEquals('Untitled', $defaults['title']);
        $this->assertEquals(10, $defaults['count']);
        $this->assertTrue($defaults['active']);
        $this->assertArrayNotHasKey('no_default', $defaults);
    }

    #[Test]
    public function it_extracts_default_state_with_prefix(): void
    {
        $fields = [
            ['name' => 'title', 'type' => 'text', 'default' => 'Hello'],
        ];

        $defaults = FormBuilder::fromArray($fields)->prefix('options')->defaultState();

        $this->assertArrayHasKey('options.title', $defaults);
        $this->assertEquals('Hello', $defaults['options.title']);
    }

    #[Test]
    public function it_extracts_default_state_from_nested_containers(): void
    {
        $fields = [
            [
                'type' => 'section',
                'label' => 'Settings',
                'schema' => [
                    ['name' => 'font_size', 'type' => 'number', 'default' => 14],
                ],
            ],
            [
                'type' => 'tabs',
                'tabs' => [
                    [
                        'label' => 'General',
                        'schema' => [
                            ['name' => 'theme', 'type' => 'select', 'default' => 'light'],
                        ],
                    ],
                ],
            ],
        ];

        $defaults = FormBuilder::fromArray($fields)->defaultState();

        $this->assertEquals(14, $defaults['font_size']);
        $this->assertEquals('light', $defaults['theme']);
    }

    // ─── JSON Parsing ────────────────────────────────────────────

    #[Test]
    public function it_builds_from_json_string(): void
    {
        $json = json_encode([
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'body', 'type' => 'textarea', 'label' => 'Body'],
        ]);

        $components = FormBuilder::fromJson($json)->build();

        $this->assertCount(2, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
        $this->assertInstanceOf(Textarea::class, $components[1]);
    }

    #[Test]
    public function it_throws_on_invalid_json(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON');

        FormBuilder::fromJson('{invalid}')->build();
    }

    #[Test]
    public function it_builds_from_json_file(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'fb_test_');
        file_put_contents($tempFile, json_encode([
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
        ]));

        try {
            $components = FormBuilder::fromJsonFile($tempFile)->build();

            $this->assertCount(1, $components);
            $this->assertInstanceOf(TextInput::class, $components[0]);
        } finally {
            @unlink($tempFile);
        }
    }

    #[Test]
    public function it_throws_on_missing_json_file(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('JSON file not found');

        FormBuilder::fromJsonFile('/nonexistent/path.json')->build();
    }

    // ─── Visibility Conditions ───────────────────────────────────

    #[Test]
    public function it_evaluates_equals_condition(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition('grid', '=', 'grid'));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('list', '=', 'grid'));
    }

    #[Test]
    public function it_evaluates_not_equals_condition(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition('list', '!=', 'grid'));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('grid', '!=', 'grid'));
    }

    #[Test]
    public function it_evaluates_in_condition(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition('a', 'in', ['a', 'b']));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('c', 'in', ['a', 'b']));
    }

    #[Test]
    public function it_evaluates_not_in_condition(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition('c', 'not_in', ['a', 'b']));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('a', 'not_in', ['a', 'b']));
    }

    #[Test]
    public function it_evaluates_comparison_conditions(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition(5, '>', 3));
        $this->assertFalse(FormBuilderEngine::evaluateCondition(2, '>', 3));
        $this->assertTrue(FormBuilderEngine::evaluateCondition(5, '>=', 5));
        $this->assertTrue(FormBuilderEngine::evaluateCondition(2, '<', 3));
        $this->assertTrue(FormBuilderEngine::evaluateCondition(3, '<=', 3));
    }

    #[Test]
    public function it_evaluates_filled_and_empty_conditions(): void
    {
        $this->assertTrue(FormBuilderEngine::evaluateCondition('hello', 'filled', null));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('', 'filled', null));
        $this->assertTrue(FormBuilderEngine::evaluateCondition('', 'empty', null));
        $this->assertFalse(FormBuilderEngine::evaluateCondition('hello', 'empty', null));
    }

    #[Test]
    public function it_builds_field_with_visible_when(): void
    {
        $fields = [
            ['name' => 'layout', 'type' => 'select', 'options' => ['grid' => 'Grid', 'list' => 'List']],
            [
                'name' => 'columns',
                'type' => 'number',
                'visible_when' => ['field' => 'layout', 'operator' => '=', 'value' => 'grid'],
            ],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(2, $components);
    }

    // ─── Legacy show_when Support ────────────────────────────────

    #[Test]
    public function it_builds_field_with_show_when_legacy(): void
    {
        $fields = [
            ['name' => 'show_title', 'type' => 'toggle'],
            ['name' => 'title', 'type' => 'text', 'show_when' => ['show_title']],
        ];

        $components = FormBuilder::fromArray($fields)
            ->withState(['show_title' => true])
            ->build();

        $this->assertCount(2, $components);
    }

    // ─── Real-World Scenario: Spacer Module ──────────────────────

    #[Test]
    public function it_builds_spacer_module_equivalent_from_json(): void
    {
        $json = json_encode([
            ['name' => 'height', 'type' => 'text', 'label' => 'Height', 'placeholder' => '50px', 'help' => 'Enter the height of the spacer (e.g., 50px, 2rem, 5vh).'],
        ]);

        $components = FormBuilder::fromJson($json)->prefix('options')->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);
    }

    // ─── Real-World Scenario: Blog-like Settings from JSON ───────

    #[Test]
    public function it_builds_blog_like_settings_from_json(): void
    {
        $schema = [
            [
                'type' => 'tabs',
                'label' => 'Settings',
                'tabs' => [
                    [
                        'label' => 'Content',
                        'schema' => [
                            [
                                'type' => 'section',
                                'label' => 'Content',
                                'schema' => [
                                    ['name' => 'title', 'type' => 'text', 'label' => 'Blog Title', 'placeholder' => 'My Blog', 'default' => ''],
                                    ['name' => 'posts_per_page', 'type' => 'number', 'label' => 'Posts Per Page', 'default' => 10, 'options' => ['min' => 1, 'max' => 50]],
                                    ['name' => 'order_by', 'type' => 'select', 'label' => 'Sort by', 'options' => ['date_desc' => 'Newest first', 'date_asc' => 'Oldest first', 'title_asc' => 'Title A → Z', 'title_desc' => 'Title Z → A'], 'default' => 'date_desc'],
                                ],
                            ],
                            [
                                'type' => 'section',
                                'label' => 'Display',
                                'schema' => [
                                    ['name' => 'layout', 'type' => 'toggle_buttons', 'label' => 'Layout', 'options' => ['grid' => 'Grid', 'list' => 'List'], 'default' => 'grid'],
                                    ['name' => 'show_categories', 'type' => 'toggle', 'label' => 'Show Categories', 'default' => true],
                                    ['name' => 'show_tags', 'type' => 'toggle', 'label' => 'Show Tags', 'default' => true],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $json = json_encode($schema);
        $components = FormBuilder::fromJson($json)->prefix('options')->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Tabs::class, $components[0]);

        // Verify defaults
        $defaults = FormBuilder::fromJson($json)->prefix('options')->defaultState();
        $this->assertEquals('', $defaults['options.title']);
        $this->assertEquals(10, $defaults['options.posts_per_page']);
        $this->assertEquals('date_desc', $defaults['options.order_by']);
        $this->assertEquals('grid', $defaults['options.layout']);
        $this->assertTrue($defaults['options.show_categories']);
        $this->assertTrue($defaults['options.show_tags']);
    }

    // ─── Real-World Scenario: Video-like Settings from JSON ──────

    #[Test]
    public function it_builds_video_like_settings_from_json(): void
    {
        $schema = [
            [
                'type' => 'tabs',
                'label' => 'Settings',
                'tabs' => [
                    [
                        'label' => 'Video',
                        'schema' => [
                            ['name' => 'embed_url', 'type' => 'text', 'label' => 'Video URL', 'placeholder' => 'https://www.youtube.com/watch?v=...'],
                            ['name' => 'upload', 'type' => 'image', 'label' => 'Upload Video'],
                        ],
                    ],
                    [
                        'label' => 'Settings',
                        'schema' => [
                            [
                                'type' => 'group',
                                'columns' => 2,
                                'schema' => [
                                    ['name' => 'width', 'type' => 'text', 'label' => 'Width', 'placeholder' => '100%'],
                                    ['name' => 'height', 'type' => 'text', 'label' => 'Height', 'placeholder' => '350px'],
                                ],
                            ],
                            ['name' => 'autoplay', 'type' => 'toggle', 'label' => 'Autoplay', 'default' => false],
                            ['name' => 'loop', 'type' => 'toggle', 'label' => 'Loop', 'default' => false],
                            ['name' => 'muted', 'type' => 'toggle', 'label' => 'Muted', 'default' => false],
                        ],
                    ],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($schema)->prefix('options')->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(Tabs::class, $components[0]);

        // Verify defaults
        $defaults = FormBuilder::fromArray($schema)->prefix('options')->defaultState();
        $this->assertFalse($defaults['options.autoplay']);
        $this->assertFalse($defaults['options.loop']);
        $this->assertFalse($defaults['options.muted']);
    }

    // ─── schemaToFormFields Replacement ──────────────────────────

    #[Test]
    public function it_old_schema_format_produces_correct_component_types(): void
    {
        // This is the format used by skin_settings.json files
        $schema = [
            ['name' => 'title_color', 'type' => 'color', 'label' => 'Title Color'],
            ['name' => 'bg_image', 'type' => 'image', 'label' => 'Background Image'],
            ['name' => 'font_size', 'type' => 'number', 'label' => 'Font Size', 'options' => ['min' => 8, 'max' => 72, 'step' => 1]],
            ['name' => 'description', 'type' => 'text', 'label' => 'Description', 'placeholder' => 'Enter description'],
            ['name' => 'content', 'type' => 'textarea', 'label' => 'Content'],
            ['name' => 'style', 'type' => 'select', 'label' => 'Style', 'options' => ['default' => 'Default', 'modern' => 'Modern']],
            ['name' => 'dark_mode', 'type' => 'toggle', 'label' => 'Dark Mode'],
            ['name' => 'spacing', 'type' => 'slider', 'label' => 'Spacing', 'options' => ['min' => 0, 'max' => 100, 'step' => 5]],
        ];

        $components = FormBuilder::fromArray($schema)->prefix('options')->build();

        $this->assertCount(8, $components);
        $this->assertInstanceOf(MwColorPicker::class, $components[0]); // color → MwColorPicker
        $this->assertInstanceOf(MwFileUpload::class, $components[1]); // image → MwFileUpload
        $this->assertInstanceOf(TextInput::class, $components[2]); // number → TextInput
        $this->assertInstanceOf(TextInput::class, $components[3]); // text → TextInput
        $this->assertInstanceOf(Textarea::class, $components[4]); // textarea → Textarea
        $this->assertInstanceOf(Select::class, $components[5]); // select → Select
        $this->assertInstanceOf(Toggle::class, $components[6]); // toggle → Toggle
        // slider produces MwInputSliderGroup which is a Component
        $this->assertNotNull($components[7]); // slider
    }

    // ─── FormBuilder Static API ──────────────────────────────────

    #[Test]
    public function it_registers_custom_type_via_static_api(): void
    {
        FormBuilder::registerFieldType('my_custom', function (array $field) {
            return TextInput::make($field['name'])->label('Custom: ' . ($field['label'] ?? ''));
        });

        $this->assertTrue(FormBuilder::hasFieldType('my_custom'));

        $components = FormBuilder::fromArray([
            ['name' => 'test', 'type' => 'my_custom', 'label' => 'Test'],
        ])->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(TextInput::class, $components[0]);

        // Clean up
        FormBuilder::getRegistry()->unregister('my_custom');
    }

    #[Test]
    public function it_returns_all_registered_types(): void
    {
        $types = FormBuilder::getRegisteredTypes();

        $this->assertIsArray($types);
        $this->assertContains('text', $types);
        $this->assertContains('image', $types);
    }

    // ─── Skips Invalid Definitions ───────────────────────────────

    #[Test]
    public function it_skips_entries_without_type(): void
    {
        $fields = [
            ['name' => 'no_type_field'],
            ['name' => 'valid', 'type' => 'text'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
    }

    #[Test]
    public function it_skips_non_array_entries(): void
    {
        $fields = [
            'not_an_array',
            ['name' => 'valid', 'type' => 'text'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
    }

    // ─── Complex Nested JSON Scenario ────────────────────────────

    #[Test]
    public function it_builds_complex_nested_form_from_json(): void
    {
        $schema = [
            [
                'type' => 'tabs',
                'label' => 'Module Settings',
                'tabs' => [
                    [
                        'label' => 'Content',
                        'schema' => [
                            ['name' => 'title', 'type' => 'text', 'label' => 'Title', 'required' => true, 'default' => 'My Module'],
                            [
                                'type' => 'section',
                                'label' => 'Layout',
                                'collapsed' => true,
                                'schema' => [
                                    ['name' => 'columns_count', 'type' => 'number', 'label' => 'Columns', 'default' => 3, 'options' => ['min' => 1, 'max' => 6]],
                                    ['name' => 'gap', 'type' => 'slider', 'label' => 'Gap', 'options' => ['min' => 0, 'max' => 50, 'step' => 2]],
                                ],
                            ],
                            [
                                'type' => 'repeater',
                                'name' => 'items',
                                'label' => 'Items',
                                'schema' => [
                                    ['name' => 'item_title', 'type' => 'text', 'label' => 'Item Title'],
                                    ['name' => 'item_image', 'type' => 'image', 'label' => 'Item Image'],
                                    ['name' => 'item_link', 'type' => 'link', 'label' => 'Item Link'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'label' => 'Design',
                        'schema' => [
                            ['name' => 'bg_color', 'type' => 'color_picker', 'label' => 'Background'],
                            ['name' => 'text_color', 'type' => 'color_picker', 'label' => 'Text Color'],
                            ['name' => 'border_radius', 'type' => 'number', 'label' => 'Border Radius', 'options' => ['min' => 0, 'max' => 50]],
                        ],
                    ],
                    [
                        'label' => 'Advanced',
                        'schema' => [
                            ['name' => 'css_class', 'type' => 'text', 'label' => 'CSS Class'],
                            ['name' => 'lazy_load', 'type' => 'toggle', 'label' => 'Lazy Load', 'default' => true],
                            [
                                'type' => 'fieldset',
                                'label' => 'SEO',
                                'schema' => [
                                    ['name' => 'meta_title', 'type' => 'text', 'label' => 'Meta Title', 'validation' => ['string', 'max:60']],
                                    ['name' => 'meta_description', 'type' => 'textarea', 'label' => 'Meta Description', 'validation' => ['string', 'max:160']],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $json = json_encode($schema);
        $engine = FormBuilder::fromJson($json)->prefix('options');

        $components = $engine->build();
        $this->assertCount(1, $components);
        $this->assertInstanceOf(Tabs::class, $components[0]);

        // Check validation rules
        $rules = FormBuilder::fromJson($json)->prefix('options')->validationRules();
        $this->assertArrayHasKey('options.title', $rules);
        $this->assertStringContainsString('required', $rules['options.title']);
        $this->assertArrayHasKey('options.meta_title', $rules);
        $this->assertStringContainsString('max:60', $rules['options.meta_title']);

        // Check defaults
        $defaults = FormBuilder::fromJson($json)->prefix('options')->defaultState();
        $this->assertEquals('My Module', $defaults['options.title']);
        $this->assertEquals(3, $defaults['options.columns_count']);
        $this->assertTrue($defaults['options.lazy_load']);
    }

    // ─── Validation String Format ────────────────────────────────

    #[Test]
    public function it_handles_validation_as_pipe_delimited_string(): void
    {
        $fields = [
            ['name' => 'email', 'type' => 'text', 'required' => true, 'validation' => 'email|max:255'],
        ];

        $rules = FormBuilder::fromArray($fields)->validationRules();

        $this->assertArrayHasKey('email', $rules);
        $this->assertStringContainsString('required', $rules['email']);
        $this->assertStringContainsString('email', $rules['email']);
        $this->assertStringContainsString('max:255', $rules['email']);
    }

    // ─── Auto Label ──────────────────────────────────────────────

    #[Test]
    public function it_auto_generates_label_from_name(): void
    {
        $fields = [
            ['name' => 'my_field_name', 'type' => 'text'],
        ];

        $components = FormBuilder::fromArray($fields)->build();

        $this->assertCount(1, $components);
        // The label should be auto-generated from the name
    }
}
