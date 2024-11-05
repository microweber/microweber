<?php

namespace Modules\SocialLinks\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\SocialLinks\Microweber\SocialLinksModule;
use Tests\TestCase;

class SocialLinksModuleFrontendTest extends TestCase
{
    public function testDefaultViewRendering()
    {
        $params = [
            'id' => 'test-social-links-id' . uniqid(),
            'facebook_enabled' => true,
            'facebook_url' => 'https://facebook.com/example',
            'twitter_enabled' => true,
            'twitter_url' => 'https://twitter.com/example',
            'pinterest_enabled' => true,
            'pinterest_url' => 'https://pinterest.com/example',
            'linkedin_enabled' => true,
            'linkedin_url' => 'https://linkedin.com/example',
            'viber_enabled' => true,
            'viber_url' => 'https://viber.com/example',
            'whatsapp_enabled' => true,
            'whatsapp_url' => 'https://whatsapp.com/example',
        ];
        $moduleId = $params['id'];
        $moduleType = 'sociallinks';

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

        $socialLinksModule = new SocialLinksModule($params);
        $viewOutput = $socialLinksModule->render();

        $this->assertTrue(View::exists('modules.sociallinks::templates.default'));
        $this->assertStringContainsString('example', $viewOutput);
        $this->assertStringContainsString('facebook.com', $viewOutput);
        $this->assertStringContainsString('twitter.com', $viewOutput);
        $this->assertStringContainsString('pinterest.com', $viewOutput);
        $this->assertStringContainsString('linkedin.com', $viewOutput);
        $this->assertStringContainsString('viber.com', $viewOutput);
        $this->assertStringContainsString('whatsapp://', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
