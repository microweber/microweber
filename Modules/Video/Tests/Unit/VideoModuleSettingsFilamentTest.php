<?php

namespace Modules\Video\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Video\Filament\VideoModuleSettings;
use Tests\TestCase;

class VideoModuleSettingsFilamentTest extends TestCase
{


    public function testVideoModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'video';

        // Clean any existing test data
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        // Test form fields exist
        Livewire::test(VideoModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.embed_url')
            ->assertFormFieldExists('options.upload')
            ->assertFormFieldExists('options.width')
            ->assertFormFieldExists('options.height')
            ->assertFormFieldExists('options.autoplay')
            ->assertFormFieldExists('options.loop')
            ->assertFormFieldExists('options.muted')
            ->assertFormFieldExists('options.hide_controls')
            ->assertFormFieldExists('options.lazy_load')
            ->assertFormFieldExists('options.thumbnail');

        // Test form submission
        $testData = [
            'options.embed_url' => 'https://www.youtube.com/embed/test123',
            'options.width' => 800,
            'options.height' => 450,
            'options.autoplay' => true,
            'options.loop' => false,
            'options.muted' => true,
            'options.hide_controls' => false,
            'options.lazy_load' => true
        ];

        Livewire::test(VideoModuleSettings::class)
            ->set($params)
            ->fillForm($testData)
            ->assertFormSet($testData)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        // Verify database entries
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'embed_url',
            'option_value' => 'https://www.youtube.com/embed/test123'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'width',
            'option_value' => '800'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'autoplay',
            'option_value' => '1'
        ]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
