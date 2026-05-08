<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-73 / AI-66 / TICKET-TT — Category images.blade.php
 * background-image span → real <img> migration regression coverage.
 *
 * The migration itself shipped earlier (audit-test 2026-05-07
 * post-merge follow-up #2). This contract test pins the new shape
 * so a future refactor cannot accidentally regress to the
 * `style="background-image: url('{{ $picture }}')"` shape that was
 * a CSS-injection sink (Blade HTML-escape doesn't protect against
 * URL-context breakouts inside `url(...)`).
 *
 * Pins:
 *   - The skin emits a real `<img>` tag with `src`, `alt`, and
 *     `loading="lazy"` (responsive, a11y, perf).
 *   - The fallback image (when no picture) is also a real `<img>`,
 *     not a background-image span.
 *   - No live inline `style="background-image: url(...)"` anywhere
 *     in the Category public skins.
 *   - The cycle-72 sibling work — aria-current on category links —
 *     is unaffected by this regression test.
 *
 * Style after the cycle-52..72 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CategoryImagesSkinImgMigrationContractTest extends TestCase
{
    /**
     * The Category public skins. AI-66 was scoped to `images.blade.php`
     * specifically, but pinning the rule across all skins guards
     * against a copy-paste regression sneaking into a sibling skin.
     */
    private const SKIN_PATHS = [
        'Modules/Category/resources/views/templates/images.blade.php',
        'Modules/Category/resources/views/templates/default.blade.php',
        'Modules/Category/resources/views/templates/skin-1.blade.php',
        'Modules/Category/resources/views/templates/horizontal-list-1.blade.php',
    ];

    private string $imagesSkin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imagesSkin = file_get_contents(base_path(
            'Modules/Category/resources/views/templates/images.blade.php'
        ));
    }

    #[Test]
    public function images_skin_emits_real_img_with_src_alt_loading(): void
    {
        // The migrated shape: `<img src="..." alt="..." loading="lazy" class="category-image">`.
        // Pin all three required attributes — losing any one is a regression.
        $this->assertMatchesRegularExpression(
            '/<img\\s+src="\\{\\{\\s*\\$picture\\s*\\}\\}"\\s+alt="\\{\\{\\s*\\$title\\s*\\}\\}"\\s+loading="lazy"/',
            $this->imagesSkin,
            'images.blade.php: must emit `<img src="{{ $picture }}" alt="{{ $title }}" loading="lazy">` for the picture-present branch'
        );

        // The class hook the inline CSS targets must remain.
        $this->assertStringContainsString(
            'class="category-image"',
            $this->imagesSkin,
            'images.blade.php: <img> must carry class="category-image" so the existing object-fit CSS still applies'
        );
    }

    #[Test]
    public function images_skin_fallback_is_also_a_real_img(): void
    {
        // The empty-picture branch must also use a real <img>, not a
        // div / span with background-image. Pin both the asset() URL
        // shape and the lazy-loading attribute.
        $this->assertMatchesRegularExpression(
            "/<img\\s+src=\"\\{\\{\\s*asset\\('modules\\/category\\/img\\/category_images\\.svg'\\)\\s*\\}\\}\"\\s+alt=\"\"\\s+loading=\"lazy\"/",
            $this->imagesSkin,
            'images.blade.php: empty-picture fallback must use a real <img> with the SVG asset, alt="" (decorative), loading="lazy"'
        );
    }

    #[Test]
    public function category_skins_carry_no_live_background_image_inline_style(): void
    {
        // The CSS-injection sink was the inline
        // `style="background-image: url('{{ $picture }}')"` shape.
        // Browsers HTML-decode the attribute BEFORE the CSS parser
        // sees it, so the Blade HTML-escape that turns `'` into
        // `&#039;` decodes back to `'` and the admin-supplied URL
        // can break out of url(...) into arbitrary CSS rules
        // (page-defacement + phishing-overlay primitive).
        //
        // Strip Blade `{{-- ... --}}` comment blocks AND HTML
        // `<!-- ... -->` comments before scanning so doc-comments
        // referencing the prior bug don't produce false positives.
        foreach (self::SKIN_PATHS as $rel) {
            $path = base_path($rel);
            $this->assertFileExists($path, "Category skin {$rel} must exist");
            $src = file_get_contents($path);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $stripped = preg_replace('/<!--[\\s\\S]*?-->/', '', $stripped);

            $this->assertDoesNotMatchRegularExpression(
                '/style\\s*=\\s*"[^"]*background-image\\s*:\\s*url\\(/',
                $stripped,
                "{$rel}: must not carry live inline style=\"background-image: url(...)\" — CSS-injection sink"
            );
        }
    }

    #[Test]
    public function landmark_navigation_pattern_remains(): void
    {
        // The cycle-15-or-prior a11y pass added <nav> + aria-labelledby +
        // visually-hidden <h2>. Pin that those layered improvements
        // stay alongside the cycle-73 <img> regression guard.
        $this->assertStringContainsString(
            '<nav class="module-categories module-categories-template-images"',
            $this->imagesSkin,
            'images.blade.php: <nav> landmark wrapper must stay'
        );
        $this->assertStringContainsString(
            'aria-labelledby="cat-',
            $this->imagesSkin,
            'images.blade.php: aria-labelledby must stay'
        );
        $this->assertStringContainsString(
            'class="visually-hidden">{{ __(\'Product categories\')',
            $this->imagesSkin,
            'images.blade.php: visually-hidden announce-only <h2> must stay'
        );
    }

    #[Test]
    public function items_count_carries_aria_label_for_screen_readers(): void
    {
        // A sibling fix layered onto the same skin: the (5) badge
        // gets aria-label="5 items" so screen readers don't announce
        // "open paren five close paren". Pin the trans_choice() shape
        // since it's the i18n entry point that handles non-English
        // pluralisation.
        $this->assertStringContainsString(
            "trans_choice('{1} :count item|[2,*] :count items'",
            $this->imagesSkin,
            'images.blade.php: items-count must use trans_choice() for the aria-label so non-English locales get correct plural forms'
        );
    }
}
