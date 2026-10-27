<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * The live-edit JS save endpoint — the apply_js counterpart exposed to the Live
 * Edit code editor. Posting js_file_content must persist to the per-template
 * live_edit.js layer (the same store the AI apply_js tool writes to).
 */
class LiveEditJsSaveTest extends TestCase
{
    #[Test]
    public function the_endpoint_persists_js_to_the_live_edit_js_layer(): void
    {
        if (!app('router')->has('current_template_save_custom_js')) {
            $this->markTestSkipped('JS save route not registered.');
        }

        // Disable route middleware (api/admin/CSRF) and open the controller's
        // admin gate so we deterministically test the controller + manager
        // persistence wiring without standing up an authenticated admin session.
        $this->withoutMiddleware();
        config(['template-custom-css.admin_gate' => fn () => true]);

        $manager = app(TemplateCustomCssManager::class);
        $template = 'Bootstrap';

        // Preserve whatever JS the template already has; restore it afterwards so
        // the test leaves the real live_edit.js untouched.
        $original = $manager->liveEdit()->getLiveEditJsContent($template);

        $marker = 'mwLiveEditJsTest_' . substr(md5(uniqid('', true)), 0, 8);
        $js = '(function(){ window.' . $marker . ' = 1; })();';

        try {
            $response = $this->post(route('current_template_save_custom_js'), [
                'js_file_content' => $js,
                'active_site_template' => $template,
            ]);

            $response->assertOk();
            $response->assertJson(['success' => true]);

            $stored = $manager->liveEdit()->getLiveEditJsContent($template);
            $this->assertStringContainsString(
                $marker,
                $stored,
                'The endpoint must persist the posted JS to the template live_edit.js layer.'
            );
        } finally {
            $manager->liveEdit()->saveLiveEditJsContent($original, $template);
        }
    }
}
