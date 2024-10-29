<?php

namespace Modules\Audio\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use Modules\Audio\Filament\AudioModuleSettings;
use Tests\TestCase;

class AudioModuleSettingsFilamentTest extends TestCase
{

    public function testAudioModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'audio';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.data-audio-source')
            ->assertFormFieldExists('options.data-audio-upload')
            ->assertFormFieldExists('options.data-audio-url');

        $data = [
            'options.data-audio-source' => 'file',
            'options.data-audio-upload' => 'test-audio-file.mp3',
        ];

        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.data-audio-source' => 'file',
                'options.data-audio-upload' => 'test-audio-file.mp3',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-audio-source', 'option_value' => 'file']);


        // Test form submission with URL
        $data = [
            'options.data-audio-source' => 'url',
            'options.data-audio-url' => 'https://www.example.com/audio.mp3',
        ];
        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.data-audio-source' => 'url',
                'options.data-audio-url' => 'https://www.example.com/audio.mp3',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-audio-source', 'option_value' => 'url']);


        //clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

    }
}
