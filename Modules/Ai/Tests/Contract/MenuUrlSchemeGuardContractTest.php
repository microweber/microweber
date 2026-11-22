<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use Modules\Ai\Tools\LiveEdit\Concerns\GuardsUrlScheme;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Guard for the server-side menu URL-scheme defence.
 *
 * add_menu_item / edit_menu_item persist an author-controlled URL straight to
 * the Menu DB table, which the published menu renders as <a href="{url}">. A
 * javascript:/vbscript:/data: URL there is a stored XSS that fires when a
 * visitor clicks the link — and set_link deliberately routes menu links to
 * edit_menu_item, so this is a live path. Both tools must run the URL through
 * the shared GuardsUrlScheme::unsafeUrlScheme() guard.
 *
 * This test exercises the guard directly (behavioural) and pins the two call
 * sites in source (so neither tool drops the check).
 */
final class MenuUrlSchemeGuardContractTest extends TestCase
{
    use GuardsUrlScheme;

    #[Test]
    public function guard_blocks_script_and_markup_schemes_including_bypasses(): void
    {
        foreach ([
            'javascript:alert(1)' => 'javascript',
            "java\tscript:alert(1)" => 'javascript',   // embedded-tab bypass
            "  javascript:alert(1)" => 'javascript',   // leading whitespace
            "JAVAscript:alert(1)" => 'javascript',     // case
            'vbscript:x' => 'vbscript',
            'data:text/html,<script>x</script>' => 'data',
        ] as $url => $scheme) {
            $this->assertSame($scheme, $this->unsafeUrlScheme($url), "Must block: {$url}");
        }
    }

    #[Test]
    public function guard_allows_legitimate_urls(): void
    {
        foreach ([
            'https://example.com/ok',
            'http://a.b',
            '/about',
            '#section',
            'mailto:a@b.com',
            'tel:+1234',
            '',
        ] as $url) {
            $this->assertSame('', $this->unsafeUrlScheme($url), "Must allow: {$url}");
        }
    }

    #[Test]
    public function both_menu_tools_run_the_url_through_the_guard(): void
    {
        $root = dirname(__DIR__, 4);
        foreach (['AddMenuItemTool', 'EditMenuItemTool'] as $tool) {
            $src = (string) file_get_contents($root . "/Modules/Ai/Tools/LiveEdit/{$tool}.php");
            $this->assertStringContainsString('use GuardsUrlScheme;', $src, "{$tool} must use the GuardsUrlScheme trait.");
            $this->assertStringContainsString('unsafeUrlScheme(', $src, "{$tool} must call unsafeUrlScheme() on the url before persisting it.");
        }
    }
}
