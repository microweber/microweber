<?php

namespace Modules\FacebookLike\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\FacebookLike\Filament\FacebookLikeModuleSettings;
use Tests\TestCase;

class FacebookLikeModuleSettingsFilamentTest extends TestCase
{


    public function testFacebookLikeModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'facebook_like';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'module' => $moduleType,
            'params' => [
                'id' => $moduleId,
            ]
        ];

        Livewire::test(FacebookLikeModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.layout')
            ->assertFormFieldExists('options.color')
            ->assertFormFieldExists('options.show_faces')
            ->assertFormFieldExists('options.url');

        $data = [
            'options.layout' => 'standard',
            'options.color' => 'light',
            'options.show_faces' => true,
            'options.url' => 'https://www.example.com',
        ];

        Livewire::test(FacebookLikeModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.layout' => 'standard',
                'options.color' => 'light',
                'options.show_faces' => true,
                'options.url' => 'https://www.example.com',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'layout', 'option_value' => 'standard']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'color', 'option_value' => 'light']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'show_faces', 'option_value' => '1']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'url', 'option_value' => 'https://www.example.com']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
