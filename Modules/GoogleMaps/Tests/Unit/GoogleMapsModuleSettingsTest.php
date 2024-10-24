<?php

namespace Modules\GoogleMaps\Tests\Unit;

use Livewire\Livewire;
use MicroweberPackages\Option\Models\Option;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;
use Tests\TestCase;

class GoogleMapsModuleSettingsTest extends TestCase
{
    public function testGoogleMapsModuleSettingsForm()
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'google_maps';

        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        $data = [
            'options.data-country' => 'USA',
            'options.data-city' => 'New York',
            'options.data-street' => '5th Avenue',
            'options.data-zip' => '10001',
            'options.data-zoom' => '10',
            'options.data-width' => '600',
            'options.data-height' => '400',
        ];

        Livewire::test(GoogleMapsModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet($data)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        foreach ($data as $key => $value) {
            $key = str_replace('options.', '', $key);
            $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => $key, 'option_value' => $value]);
        }

        // Clean up
        Option::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
