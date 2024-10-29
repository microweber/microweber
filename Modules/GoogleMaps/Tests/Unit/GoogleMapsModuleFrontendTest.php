<?php

namespace Modules\GoogleMaps\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\GoogleMaps\Microweber\GoogleMapsModule;
use Tests\TestCase;

class GoogleMapsModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-google-maps-id' . uniqid(),
            'data-country' => 'USA',
            'data-city' => 'New York',
            'data-street' => '5th Avenue',
            'data-zip' => '10001',
            'data-zoom' => '10',
            'data-width' => '600',
            'data-height' => '400',
        ];
        $moduleId = $params['id'];
        $moduleType = 'google_maps';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $params['id'],
                'module' => $moduleType,
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }
        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $googleMapsModule = new GoogleMapsModule($params);
        $viewData = $googleMapsModule->getViewData();

        $viewOutput = $googleMapsModule->render();

        $this->assertTrue(View::exists('modules.google_maps::templates.default'));
        $this->assertStringContainsString(urlencode('New York'), $viewOutput);
        $this->assertStringContainsString(urlencode('5th Avenue'), $viewOutput);
        $this->assertStringContainsString(urlencode('10001'), $viewOutput);
        $this->assertStringContainsString('600', $viewOutput);
        $this->assertStringContainsString('400', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
