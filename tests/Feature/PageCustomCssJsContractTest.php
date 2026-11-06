<?php

declare(strict_types=1);

namespace Tests\Feature;

use Butschster\Head\MetaTags\Meta;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\MetaTags\Entities\PageCustomCssFooterTags;
use MicroweberPackages\MetaTags\Entities\PageCustomJsFooterTags;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract test for the per-PAGE custom CSS/JS layer (keyed by content id) that
 * sits alongside the global per-template live_edit.{css,js}.
 *
 * Pins: the manager's per-page store round-trip + empty-guard; that the page CSS
 * is a FOOTER tag (so it renders after the end-of-<body> global live_edit.css and
 * overrides it) and the page JS is a footer tag; the save/read routes; and the
 * public helpers.
 */
class PageCustomCssJsContractTest extends TestCase
{
    private int $contentId = 999000111; // an id no real page uses

    protected function tearDown(): void
    {
        // Remove the files this test wrote.
        try {
            $mgr = app(LiveEditCssManager::class);
            foreach ([$mgr->getPageCssPath($this->contentId, false), $mgr->getPageJsPath($this->contentId, false)] as $p) {
                if (is_string($p) && is_file($p)) {
                    @unlink($p);
                }
            }
        } catch (\Throwable) {
            // ignore
        }
        parent::tearDown();
    }

    #[Test]
    public function manager_round_trips_per_page_css_and_js(): void
    {
        $mgr = app(LiveEditCssManager::class);

        $mgr->savePageCssContent($this->contentId, '.mw-x{color:red}');
        $mgr->savePageJsContent($this->contentId, 'console.log(1)');

        $this->assertStringContainsString('.mw-x', $mgr->getPageCssContent($this->contentId));
        $this->assertStringContainsString('console.log(1)', $mgr->getPageJsContent($this->contentId));

        $this->assertIsString($mgr->getPageCssPath($this->contentId, true) ?: '');
        $this->assertNotFalse($mgr->getPageCssPath($this->contentId, true));
        $this->assertNotFalse($mgr->getPageJsPath($this->contentId, true));

        // Filenames are keyed by content id.
        $this->assertSame('live_edit_' . $this->contentId . '.css', $mgr->getPageCssFilename($this->contentId));
        $this->assertSame('live_edit_' . $this->contentId . '.js', $mgr->getPageJsFilename($this->contentId));

        // A non-empty file yields a URL...
        $this->assertNotNull($mgr->getPageCssUrl($this->contentId));
        $this->assertNotNull($mgr->getPageJsUrl($this->contentId));

        // ...and an EMPTY layer yields no URL (so no empty tag is ever shipped).
        $mgr->savePageCssContent($this->contentId, '');
        $mgr->savePageJsContent($this->contentId, '');
        $this->assertNull($mgr->getPageCssUrl($this->contentId));
        $this->assertNull($mgr->getPageJsUrl($this->contentId));
    }

    #[Test]
    public function page_css_is_a_footer_tag_so_it_overrides_the_global_layer(): void
    {
        // The global live_edit.css is injected at the END of <body>; the per-page
        // override must be a FOOTER stylesheet (registered last) to come after it.
        $this->assertSame(Meta::PLACEMENT_FOOTER, (new PageCustomCssFooterTags())->getPlacement());
        $this->assertSame(Meta::PLACEMENT_FOOTER, (new PageCustomJsFooterTags())->getPlacement());
    }

    #[Test]
    public function injection_entities_emit_nothing_without_a_current_content_id(): void
    {
        // Outside a page render content_id() is 0, so both layers emit nothing
        // (they never ship an empty tag or guess an id).
        $this->assertSame('', (new PageCustomCssFooterTags())->toHtml());
        $this->assertSame('', (new PageCustomJsFooterTags())->toHtml());
    }

    #[Test]
    public function save_and_read_routes_are_registered(): void
    {
        foreach ([
            'api.page.save_custom_css' => 'api/page_save_custom_css',
            'api.page.save_custom_js' => 'api/page_save_custom_js',
            'api.page.get_custom_style' => 'api/page_get_custom_style',
        ] as $name => $uri) {
            $route = Route::getRoutes()->getByName($name);
            $this->assertNotNull($route, "Route {$name} must be registered.");
            $this->assertSame($uri, $route->uri());
            $this->assertStringContainsString('TemplateCustomCssController', $route->getActionName());
        }
    }

    #[Test]
    public function public_helpers_exist(): void
    {
        foreach (['get_page_custom_css', 'save_page_custom_css', 'get_page_custom_js', 'save_page_custom_js'] as $fn) {
            $this->assertTrue(function_exists($fn), "Helper {$fn}() must exist.");
        }
    }
}
