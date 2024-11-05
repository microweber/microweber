<?php

namespace Modules\Sharer\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Sharer\Filament\SharerModuleSettings;
use Tests\TestCase;

class SharerSettingsFilamentTest extends TestCase
{
    public function testSharerModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'sharer';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(SharerModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.facebook_enabled')
            ->assertFormFieldExists('options.twitter_enabled')
            ->assertFormFieldExists('options.pinterest_enabled')
            ->assertFormFieldExists('options.linkedin_enabled')
            ->assertFormFieldExists('options.viber_enabled')
            ->assertFormFieldExists('options.whatsapp_enabled');

        $data = [
            'options.facebook_enabled' => true,
            'options.twitter_enabled' => true,
            'options.pinterest_enabled' => false,
            'options.linkedin_enabled' => true,
            'options.viber_enabled' => false,
            'options.whatsapp_enabled' => true,
        ];

        Livewire::test(SharerModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        foreach ($data as $key => $value) {
            $this->assertDatabaseHas('options', [
                'option_group' => $moduleId,
                'module' => $moduleType,
                'option_key' => str_replace('options.', '', $key),
                'option_value' => $value ? '1' : '0',
            ]);
        }

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
