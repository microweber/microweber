<?php

namespace Modules\Embed\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Embed\Filament\EmbedModuleSettings;
use Tests\TestCase;

class EmbedModuleSettingsFilamentTest extends TestCase
{
    public function testEmbedModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'embed';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(EmbedModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.source_code')
            ->assertFormFieldExists('options.hide_in_live_edit');

        $data = [
            'options.source_code' => '<iframe src="https://www.example.com"></iframe>',
            'options.hide_in_live_edit' => true,
        ];

        Livewire::test(EmbedModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.source_code' => '<iframe src="https://www.example.com"></iframe>',
                'options.hide_in_live_edit' => true,
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'source_code', 'option_value' => '<iframe src="https://www.example.com"></iframe>']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'hide_in_live_edit', 'option_value' => true]);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
