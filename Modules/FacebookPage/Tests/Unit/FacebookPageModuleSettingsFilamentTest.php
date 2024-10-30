<?php

namespace Modules\FacebookPage\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\FacebookPage\Filament\FacebookPageModuleSettings;
use Tests\TestCase;

class FacebookPageModuleSettingsFilamentTest extends TestCase
{
    public function testFacebookPageModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'facebook_page';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'module' => $moduleType,
            'params' => [
                'id' => $moduleId,
            ]
        ];

        Livewire::test(FacebookPageModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.fbPage')
            ->assertFormFieldExists('options.width')
            ->assertFormFieldExists('options.height')
            ->assertFormFieldExists('options.friends')
            ->assertFormFieldExists('options.timeline');

        $data = [
            'options.fbPage' => 'https://www.facebook.com/Microweber/',
            'options.width' => '380',
            'options.height' => '300',
            'options.friends' => true,
            'options.timeline' => true,
        ];

        Livewire::test(FacebookPageModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.fbPage' => 'https://www.facebook.com/Microweber/',
                'options.width' => '380',
                'options.height' => '300',
                'options.friends' => true,
                'options.timeline' => true,
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'fbPage', 'option_value' => 'https://www.facebook.com/Microweber/']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'width', 'option_value' => '380']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'height', 'option_value' => '300']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'friends', 'option_value' => '1']);
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'timeline', 'option_value' => '1']);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
