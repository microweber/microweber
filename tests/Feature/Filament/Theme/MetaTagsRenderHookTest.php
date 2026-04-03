<?php

namespace Tests\Feature\Filament\Theme;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

/**
 * Regression test: HEAD_START render hook must not inject admin meta tags twice.
 *
 * Bug: MicroweberFilamentServiceProvider and MicroweberFilamentTheme both registered
 * a HEAD_START hook calling AdminFilamentMetaTagsRenderer::getHeadMetaTags(), causing
 * the Ziggy routes `const Ziggy = {...}` to be declared twice in the page — producing
 * a JS error: "Identifier 'Ziggy' has already been declared".
 *
 * Fix: removed the duplicate hook from MicroweberFilamentTheme.php.
 */
class MetaTagsRenderHookTest extends TestCase
{
    use InteractsWithFilamentPanel;

    #[Test]
    public function it_admin_dashboard_does_not_contain_duplicate_ziggy_declaration(): void
    {
        $response = $this->actingAs($this->getAdminUser())
            ->get('/admin');

        $response->assertStatus(200);

        $html = $response->getContent();

        // Count occurrences of the Ziggy route declaration
        $ziggyCount = substr_count($html, 'const Ziggy');

        $this->assertLessThanOrEqual(
            1,
            $ziggyCount,
            "Ziggy routes should be declared at most once in the page HTML, found {$ziggyCount} declarations"
        );
    }

    #[Test]
    public function it_admin_page_does_not_duplicate_api_settings_script(): void
    {
        $response = $this->actingAs($this->getAdminUser())
            ->get('/admin');

        $response->assertStatus(200);

        $html = $response->getContent();

        // Count occurrences of the api settings script tag
        $scriptCount = substr_count($html, 'mw-api-settings');

        $this->assertLessThanOrEqual(
            1,
            $scriptCount,
            "The mw-api-settings script should be included at most once, found {$scriptCount} inclusions"
        );
    }

    private function getAdminUser()
    {
        return \MicroweberPackages\User\Models\User::where('is_admin', 1)->first()
            ?? \MicroweberPackages\User\Models\User::factory()->create(['is_admin' => 1]);
    }
}
