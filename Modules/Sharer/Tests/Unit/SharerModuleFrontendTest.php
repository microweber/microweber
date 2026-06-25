<?php

namespace Modules\Sharer\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\View;
use MicroweberPackages\Option\Models\ModuleOption;
use Modules\Sharer\Microweber\SharerModule;
use Tests\TestCase;

class SharerModuleFrontendTest extends TestCase
{
    #[Test]
    public function it_default_view_rendering(): void {
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
        // CSP migration: inline onclick was replaced by the delegated
        // data-mw-pinmarklet handler (csp-skin-handlers.js).
        $this->assertStringContainsString('href="javascript:void(0);" data-mw-pinmarklet', $viewOutput);
        $this->assertStringContainsString('href="https://www.linkedin.com/shareArticle?mini=true&url=', $viewOutput);
        $this->assertStringContainsString('href="#" id="viber_share"', $viewOutput);
        // AI-791: switched from the whatsapp:// deep-link to the cross-platform
        // wa.me web URL.
        $this->assertStringContainsString('href="https://wa.me/?text=', $viewOutput);

        // Clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);
    }
}
