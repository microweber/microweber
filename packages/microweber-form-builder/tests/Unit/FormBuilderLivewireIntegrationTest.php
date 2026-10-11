<?php

namespace MicroweberPackages\FormBuilder\Tests\Unit;

use Livewire\Livewire;
use MicroweberPackages\FormBuilder\FormBuilder;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Spacer\Filament\SpacerModuleSettings;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormBuilderLivewireIntegrationTest extends TestCase
{
    #[Test]
    public function it_spacer_module_form_fields_exist(): void
    {
        $moduleId = 'fb-test-spacer-' . uniqid();
        $moduleType = 'spacer';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType,
            ],
        ];

        Livewire::test(SpacerModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.height');

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
    }

    #[Test]
    public function it_spacer_module_can_save_and_read(): void
    {
        $moduleId = 'fb-test-spacer-save-' . uniqid();
        $moduleType = 'spacer';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType,
            ],
        ];

        Livewire::test(SpacerModuleSettings::class)
            ->set($params)
            ->fillForm(['options.height' => '100px'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'option_key' => 'height',
            'option_value' => '100px',
        ]);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
    }

    #[Test]
    public function it_form_builder_produces_same_output_types_as_php_form(): void
    {
        // Build the same form definition from JSON that SpacerModuleSettings builds in PHP
        $jsonSchema = [
            ['name' => 'height', 'type' => 'text', 'label' => 'Height', 'placeholder' => '50px', 'help' => 'Enter the height of the spacer (e.g., 50px, 2rem, 5vh).'],
        ];

        $jsonComponents = FormBuilder::fromArray($jsonSchema)->prefix('options')->build();

        $this->assertCount(1, $jsonComponents);
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $jsonComponents[0]);
    }

    #[Test]
    public function it_form_builder_json_matches_php_video_settings(): void
    {
        // Build a subset of VideoModuleSettings from JSON
        $jsonSchema = [
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
                            ['name' => 'autoplay', 'type' => 'toggle', 'label' => 'Autoplay', 'default' => false],
                            ['name' => 'loop', 'type' => 'toggle', 'label' => 'Loop', 'default' => false],
                            ['name' => 'muted', 'type' => 'toggle', 'label' => 'Muted', 'default' => false],
                        ],
                    ],
                ],
            ],
        ];

        $components = FormBuilder::fromArray($jsonSchema)->prefix('options')->build();

        $this->assertCount(1, $components);
        $this->assertInstanceOf(\Filament\Schemas\Components\Tabs::class, $components[0]);

        $defaults = FormBuilder::fromArray($jsonSchema)->prefix('options')->defaultState();
        $this->assertFalse($defaults['options.autoplay']);
        $this->assertFalse($defaults['options.loop']);
        $this->assertFalse($defaults['options.muted']);
    }

    #[Test]
    public function it_form_builder_json_matches_skin_settings_format(): void
    {
        // This simulates what skin_settings.json files contain
        $skinSettings = [
            ['name' => 'title_color', 'type' => 'color', 'label' => 'Title Color'],
            ['name' => 'bg_image', 'type' => 'image', 'label' => 'Background Image'],
            ['name' => 'font_size', 'type' => 'number', 'label' => 'Font Size', 'options' => ['min' => 8, 'max' => 72]],
            ['name' => 'layout_type', 'type' => 'select', 'label' => 'Layout', 'options' => ['default' => 'Default', 'grid' => 'Grid']],
            ['name' => 'dark_mode', 'type' => 'toggle', 'label' => 'Dark Mode'],
        ];

        $components = FormBuilder::fromArray($skinSettings)->prefix('options')->build();

        $this->assertCount(5, $components);

        // Verify component types match what the old schemaToFormFields would produce
        $this->assertInstanceOf(\MicroweberPackages\Filament\Forms\Components\MwColorPicker::class, $components[0]);
        $this->assertInstanceOf(\MicroweberPackages\Filament\Forms\Components\MwFileUpload::class, $components[1]);
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $components[2]);
        $this->assertInstanceOf(\Filament\Forms\Components\Select::class, $components[3]);
        $this->assertInstanceOf(\Filament\Forms\Components\Toggle::class, $components[4]);
    }
}
