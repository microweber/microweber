<?php

namespace Modules\SocialLinks\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\SocialLinks\Filament\SocialLinksModuleSettings;
use Tests\TestCase;

class SocialLinksSettingsFilamentTest extends TestCase
{
    public function testSocialLinksModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'sociallinks';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(SocialLinksModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.facebook_enabled')
            ->assertFormFieldExists('options.facebook_url')
            ->assertFormFieldExists('options.twitter_enabled')
            ->assertFormFieldExists('options.twitter_url')
            ->assertFormFieldExists('options.pinterest_enabled')
            ->assertFormFieldExists('options.pinterest_url')
            ->assertFormFieldExists('options.linkedin_enabled')
            ->assertFormFieldExists('options.linkedin_url')
            ->assertFormFieldExists('options.viber_enabled')
            ->assertFormFieldExists('options.viber_url')
            ->assertFormFieldExists('options.whatsapp_enabled')
            ->assertFormFieldExists('options.whatsapp_url');

        $data = [
            'options.facebook_enabled' => true,
            'options.facebook_url' => 'https://facebook.com/example',
            'options.twitter_enabled' => true,
            'options.twitter_url' => 'https://twitter.com/example',
            'options.pinterest_enabled' => true,
            'options.pinterest_url' => 'https://pinterest.com/example',
            'options.linkedin_enabled' => true,
            'options.linkedin_url' => 'https://linkedin.com/example',
            'options.viber_enabled' => true,
            'options.viber_url' => 'https://viber.com/example',
            'options.whatsapp_enabled' => true,
            'options.whatsapp_url' => 'https://whatsapp.com/example',
        ];

        Livewire::test(SocialLinksModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        foreach ($data as $key => $value) {

            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }

            $this->assertDatabaseHas('options', [
                'option_group' => $moduleId,
                'module' => $moduleType,
                'option_key' => str_replace('options.', '', $key),
                'option_value' => $value,
            ]);
        }

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
