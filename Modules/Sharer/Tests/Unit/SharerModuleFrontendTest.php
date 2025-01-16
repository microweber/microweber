<?php

namespace Modules\Sharer\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Sharer\Microweber\SharerModule;
use Tests\TestCase;

class SharerModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-sharer-id' . uniqid(),
            'facebook_enabled' => true,
            'x_enabled' => true,
            'pinterest_enabled' => true,
            'linkedin_enabled' => true,
            'viber_enabled' => true,
            'whatsapp_enabled' => true,
        ];
        $moduleId = $params['id'];
        $moduleType = 'sharer';

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

        $sharerModule = new SharerModule($params);
        $viewOutput = $sharerModule->render();

        $this->assertTrue(View::exists('modules.sharer::templates.default'));
        $this->assertStringContainsString('href="https://www.facebook.com/sharer/sharer.php?u=', $viewOutput);
        $this->assertStringContainsString('href="https://x.com/intent/tweet?text=', $viewOutput);
        $this->assertStringContainsString('href="javascript:void(0);" onclick="mw.pinMarklet();"', $viewOutput);
        $this->assertStringContainsString('href="https://www.linkedin.com/shareArticle?mini=true&url=', $viewOutput);
        $this->assertStringContainsString('href="#" id="viber_share"', $viewOutput);
        $this->assertStringContainsString('href="whatsapp://send?text=', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
