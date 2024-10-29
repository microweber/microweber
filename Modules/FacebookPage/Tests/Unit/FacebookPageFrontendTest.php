<?php

namespace Modules\FacebookPage\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\FacebookPage\Microweber\FacebookPageModule;
use Tests\TestCase;

class FacebookPageFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-fb-page-id' . uniqid(),
            'fbPage' => 'https://www.facebook.com/Microweber/',
            'width' => '380',
            'height' => '300',
            'friends' => true,
            'timeline' => true,
        ];
        $moduleId = $params['id'];
        $moduleType = 'facebook_page';

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

        $fbPageModule = new FacebookPageModule($params);
        $viewData = $fbPageModule->getViewData();

        $viewOutput = $fbPageModule->render();

        $this->assertTrue(View::exists('modules.facebook_page::templates.default'));
        $this->assertStringContainsString('https://www.facebook.com/Microweber/', $viewOutput);
        $this->assertStringContainsString('380', $viewOutput);
        $this->assertStringContainsString('300', $viewOutput);
        $this->assertStringContainsString('true', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
