<?php

namespace Modules\FacebookLike\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\FacebookLike\Microweber\FacebookLikeModule;
use Tests\TestCase;

class FacebookLikeModuleFrontendTest extends TestCase
{

    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-facebook-like-id' . uniqid(),
            'layout' => 'standard',
            'url' => 'https://www.example.com',
            'color' => 'light',
            'show_faces' => true,
        ];
        $moduleId = $params['id'];
        $moduleType = 'facebook_like';

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

        $facebookLikeModule = new FacebookLikeModule($params);
        $viewData = $facebookLikeModule->getViewData();

        $viewOutput = $facebookLikeModule->render();

        $this->assertTrue(View::exists('modules.facebook_like::templates.default'));
        $this->assertStringContainsString(urlencode('https://www.example.com'), $viewOutput);
        $this->assertStringContainsString('standard', $viewOutput);
        $this->assertStringContainsString('light', $viewOutput);
        $this->assertStringContainsString('show_faces', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
