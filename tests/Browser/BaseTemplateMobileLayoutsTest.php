<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\EnsuresAdminUser;
use Tests\Browser\Traits\SeedsLayoutModules;

/**
 * Mobile layout regression test for the Base template.
 *
 * Seeds a page full of layout-skin modules (SeedsLayoutModules), activates the
 * Base template, loads the page at a phone width and asserts that no skin:
 *   - bleeds content past the right edge (horizontal overflow), or
 *   - has lopsided side padding (content pushed to one side).
 *
 * The side padding comes from a shared normalizer (public-touch.css) loaded by
 * both Base and Big, so this guards both templates against regressions.
 *
 * Prereqs: a running server on http://127.0.0.1:8000 and admin login enabled.
 */
class BaseTemplateMobileLayoutsTest extends DuskTestCase
{
    use AdminLoginTrait;
    use EnsuresAdminUser;
    use SeedsLayoutModules;

    private const MOBILE_WIDTH = 390;

    /** Max allowed |leftInset - rightInset| before padding is "lopsided". */
    private const SYMMETRY_TOLERANCE = 12;

    private ?string $previousTemplate = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureAdminUser();

        $this->previousTemplate = function_exists('get_option')
            ? get_option('current_template', 'template')
            : null;

        if (function_exists('save_option')) {
            save_option('current_template', 'Base', 'template');
        }
    }

    protected function tearDown(): void
    {
        // Always restore the site's original template so the shared dev/test DB
        // is not left switched to Base.
        if ($this->previousTemplate && function_exists('save_option')) {
            save_option('current_template', $this->previousTemplate, 'template');
        }

        parent::tearDown();
    }

    protected function assertPreConditions(): void
    {
        // Rely on the already-running server's database.
    }

    #[Test]
    public function base_layouts_have_no_mobile_overflow_or_lopsided_padding(): void
    {
        $url = $this->seedLayoutModulesPage();

        $this->browse(function (Browser $browser) use ($url) {
            $browser->resize(self::MOBILE_WIDTH, 900)
                ->visit('/' . $url)
                ->pause(2500);

            /** @var array<int, array{skin:string,left:int,right:int}> $measurements */
            $measurements = $browser->driver->executeScript($this->measurementScript());

            $this->assertNotEmpty(
                $measurements,
                'No layout skins rendered on the seeded page — the fixture did not populate.'
            );

            $overflow = [];
            $lopsided = [];
            foreach ($measurements as $m) {
                // Overflow: ANY element bleeding past the right edge.
                if ($m['worstR'] !== null && $m['worstR'] < -3) {
                    $overflow[] = $m['skin'] . ' (right ' . $m['worstR'] . 'px)';
                }

                // Lopsided padding is judged on TEXT content alignment, not on
                // images / list rows (a card can have a full-bleed image plus
                // inset text and still be perfectly symmetric). Intentional
                // full-bleed text sections (~0/0) are left alone.
                if ($m['textSeen']) {
                    $hasPadding = $m['tL'] > 4 || $m['tR'] > 4;
                    if ($hasPadding && abs($m['tL'] - $m['tR']) > self::SYMMETRY_TOLERANCE) {
                        $lopsided[] = $m['skin'] . ' (L' . $m['tL'] . '/R' . $m['tR'] . ')';
                    }
                }
            }

            $this->assertEmpty(
                $overflow,
                'These Base skins overflow the right edge on mobile: ' . implode('; ', $overflow)
            );
            $this->assertEmpty(
                $lopsided,
                'These Base skins have lopsided side padding on mobile: ' . implode('; ', $lopsided)
            );
        });
    }

    /**
     * JS that measures, per skin marker:
     *   - tL / tR: smallest left/right inset of TEXT content (padding symmetry);
     *   - worstR: smallest right inset of ANY element (right-edge overflow).
     * Off-canvas carousel slides and breadcrumbs (page-title driven) are ignored.
     */
    private function measurementScript(): string
    {
        return <<<'JS'
var cw = document.documentElement.clientWidth;
var TEXT = 'h1,h2,h3,h4,h5,h6,p,a.btn,.btn';
var ALL = 'h1,h2,h3,h4,h5,h6,p,a.btn,.btn,li,img,input,textarea';
var out = [];
document.querySelectorAll('[data-skin-marker]').forEach(function (sec) {
    var skin = sec.getAttribute('data-skin-marker');
    var tL = 99999, tR = 99999, worstR = 99999, textSeen = false;
    sec.querySelectorAll(ALL).forEach(function (el) {
        // Breadcrumb width is driven by the page title (site content), not the
        // skin's responsive layout, so a long title must not fail a skin test.
        if (el.closest('.breadcrumb')) { return; }
        var b = el.getBoundingClientRect();
        if (b.width < 20 || b.height < 5) { return; }
        var left = Math.round(b.left);
        var right = Math.round(cw - b.right);
        // Ignore clearly off-canvas elements (carousel slides etc.).
        if (left < -50 || right < -50) { return; }
        if (right < worstR) { worstR = right; }
        if (el.matches(TEXT)) {
            textSeen = true;
            if (left < tL) { tL = left; }
            if (right < tR) { tR = right; }
        }
    });
    out.push({
        skin: skin,
        tL: tL, tR: tR,
        worstR: (worstR === 99999 ? null : worstR),
        textSeen: textSeen
    });
});
return out;
JS;
    }
}
