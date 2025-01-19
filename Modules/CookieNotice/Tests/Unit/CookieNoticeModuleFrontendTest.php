<?php

namespace Modules\CookieNotice\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\CookieNotice\Microweber\CookieNoticeModule;
use Tests\TestCase;

class CookieNoticeModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'cookie_notice',
            'enabled' => true,
            'position' => 'bottom',
            'message' => 'This website uses cookies to ensure you get the best experience.',
            'cookie_policy_url' => 'https://example.com/cookie-policy',

        ];

        $moduleId = $params['id'];
        $moduleType = 'cookie_notice';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        // Save options to the database
        foreach ($params as $key => $value) {
            ModuleOption::create([
                'option_group' => $moduleId,
                'module' => $moduleType,
                'option_key' => $key,
                'option_value' => $value,
            ]);
        }

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $cookieNoticeModule = new CookieNoticeModule($params);
        $viewData = $cookieNoticeModule->getViewData();
        $viewOutput = $cookieNoticeModule->render();

        $this->assertTrue(View::exists('modules.cookie_notice::templates.default'));
        $this->assertStringContainsString('This website uses cookies', $viewOutput);
        $this->assertStringContainsString('https://example.com/cookie-policy', $viewOutput);

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }



}
