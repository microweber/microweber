<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-90 / AI-78 / TICKET-AE — Post-list skin cleanup regression
 * coverage.
 *
 * Pins the cycle-90 deliverable across the four files that actually
 * needed treatment after the cycle-23..89 sweep already covered most
 * Post skins:
 *
 *   - `Modules/Content/resources/views/templates/default.blade.php`
 *   - `Modules/Content/resources/views/templates/skin-1.blade.php`
 *   - `Modules/Post/resources/views/templates/skin-8.blade.php`
 *   - `Modules/Post/resources/views/templates/content-module-skin.blade.php`
 *
 * Pins:
 *   - Dead `$thumb_quality` + `$thumbs_columns` + dead loose-equality
 *     `if ($columns_xl != null || ... != false || ... != '')` are gone
 *     from BOTH Content list templates (Post/default + Post/skin-1
 *     delegate to these via @include).
 *   - AOS fade-in delays are capped (`min($key, 8) * 100`) so visitors
 *     scrolling past post #10 don't sit through 9.9 s of staggered
 *     animation.
 *   - The duplicate `itemprop="url"` on inner h3-link + read-more
 *     anchor is gone (Schema.org expects one canonical URL per item;
 *     outer thumbnail wrapper carries it).
 *   - `<br>` for layout spacing in Content/default + Content/skin-1
 *     + Post/skin-8 is gone (replaced with CSS padding/margin).
 *   - Pagination footer present in Content/default + Content/skin-1
 *     so multi-page Post lists actually paginate end-to-end (matches
 *     the existing sidebar/masonry/search convention).
 *   - `Post/content-module-skin.blade.php` thumbnail now carries a
 *     real alt + `loading="lazy"` + `decoding="async"`.
 *
 * Style after the cycle-52..89 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PostListSkinCleanupContractTest extends TestCase
{
    private const CONTENT_DEFAULT = 'Modules/Content/resources/views/templates/default.blade.php';
    private const CONTENT_SKIN_1  = 'Modules/Content/resources/views/templates/skin-1.blade.php';
    private const POST_SKIN_8     = 'Modules/Post/resources/views/templates/skin-8.blade.php';
    private const POST_CMSKIN     = 'Modules/Post/resources/views/templates/content-module-skin.blade.php';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function content_list_templates_dropped_dead_thumb_quality_and_thumbs_columns(): void
    {
        // Both `$thumb_quality` and `$thumbs_columns` were dead — never
        // read after assignment. Strip PHP // line comments and Blade
        // {{-- ... --}} blocks first so the audit-trail comment that
        // mentions them doesn't trigger a false positive.
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1] as $rel) {
            $src = $this->read($rel);
            $stripped = preg_replace('!//.*$!m', '', $src);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $stripped);

            $this->assertStringNotContainsString(
                '$thumb_quality',
                $stripped,
                "{$rel}: dead `\$thumb_quality` variable must be removed"
            );
            $this->assertStringNotContainsString(
                '$thumbs_columns',
                $stripped,
                "{$rel}: dead `\$thumbs_columns` variable must be removed"
            );
        }
    }

    #[Test]
    public function content_list_templates_dropped_dead_loose_equality(): void
    {
        // The pre-fix expression `if ($columns_xl != null ||
        // $columns_xl != false || $columns_xl != '')` is always true
        // for any non-trivial value, so it was effectively a no-op
        // wrapper around the $thumbs_columns assignments. Strip
        // comments first.
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1] as $rel) {
            $src = $this->read($rel);
            $stripped = preg_replace('!//.*$!m', '', $src);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $stripped);

            $this->assertDoesNotMatchRegularExpression(
                '/!= ?null \\|\\| .* != ?false \\|\\| .* != ?\'\'/',
                $stripped,
                "{$rel}: the pre-fix dead loose-equality OR-of-three must be gone"
            );
        }
    }

    #[Test]
    public function content_list_templates_cap_aos_delay(): void
    {
        // `data-aos-delay="{{$key}}00"` becomes 9900 ms for $key=99,
        // which is a UX disaster on long lists. Pin the cap so a
        // future refactor can't silently re-introduce it.
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1] as $rel) {
            $src = $this->read($rel);

            // Negative: pre-fix shape must be gone.
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $this->assertStringNotContainsString(
                'data-aos-delay="{{ $key }}00"',
                $stripped,
                "{$rel}: pre-fix uncapped `data-aos-delay=\"{{\$key}}00\"` must be gone"
            );

            // Positive: capped form must be present.
            $this->assertStringContainsString(
                "min(\$key, 8) * 100",
                $src,
                "{$rel}: AOS delay must be capped via `min(\$key, 8) * 100`"
            );
        }
    }

    #[Test]
    public function content_list_templates_dropped_duplicate_itemprop_url_on_inner_anchors(): void
    {
        // Outer thumbnail anchor keeps `itemprop="url"`; the inner
        // h3-link + read-more anchor drop it (Schema.org expects ONE
        // canonical URL per item).
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1] as $rel) {
            $src = $this->read($rel);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

            // The inner h3-link must not carry `itemprop="url"`.
            $this->assertDoesNotMatchRegularExpression(
                '/<h3 itemprop="name"[^>]*><a href="\\{\\{ \\$item\\[\'link\'\\] \\}\\}" itemprop="url">/',
                $stripped,
                "{$rel}: inner h3-link must not carry duplicate itemprop=\"url\""
            );

            // The read-more `button-8` anchor must not carry it.
            $this->assertDoesNotMatchRegularExpression(
                '/<a href="\\{\\{ \\$item\\[\'link\'\\] \\}\\}" itemprop="url" class="button-8/',
                $stripped,
                "{$rel}: read-more button-8 anchor must not carry duplicate itemprop=\"url\""
            );

            // Outer thumbnail anchor still carries it (the canonical one).
            $this->assertMatchesRegularExpression(
                '/<a href="\\{\\{ \\$item\\[\'link\'\\] \\}\\}" itemprop="url">\\s*<div class="thumbnail-holder">/',
                $src,
                "{$rel}: outer thumbnail anchor must still carry itemprop=\"url\""
            );
        }
    }

    #[Test]
    public function br_for_layout_spacing_is_gone(): void
    {
        // `<br>` and `<br/>` for spacing → CSS padding/margin in:
        //   - Content/default.blade.php  (between thumbnail + post-bottom-holder)
        //   - Content/skin-1.blade.php   (same shape)
        //   - Post/skin-8.blade.php      (between description + read-more)
        // Strip Blade {{-- comments AND CSS /* */ comments first
        // because the audit-trail comments reference the removed
        // `<br>` shape (and the CSS one mentions it inside <style>).
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1, self::POST_SKIN_8] as $rel) {
            $src = $this->read($rel);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $stripped = preg_replace('!/\\*[\\s\\S]*?\\*/!', '', $stripped);

            $this->assertDoesNotMatchRegularExpression(
                '/<br\\s*\\/?>/',
                $stripped,
                "{$rel}: must not use `<br>` for layout spacing"
            );
        }
    }

    #[Test]
    public function content_list_templates_emit_pagination_footer(): void
    {
        // Mirrors the convention from sidebar/masonry/search.blade.php:
        // `@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))`
        // wraps a `paging("num=...&paging_param=...&current_page=...")`
        // call. Multi-page Post lists previously had no footer at all.
        foreach ([self::CONTENT_DEFAULT, self::CONTENT_SKIN_1] as $rel) {
            $src = $this->read($rel);

            $this->assertStringContainsString(
                "isset(\$pages_count) && \$pages_count > 1 && isset(\$paging_param)",
                $src,
                "{$rel}: must guard the pagination footer with the canonical isset() check"
            );
            $this->assertStringContainsString(
                "paging(\"num={\$pages_count}&paging_param={\$paging_param}&current_page={\$current_page}\")",
                $src,
                "{$rel}: must emit the canonical paging() helper call"
            );
        }
    }

    #[Test]
    public function content_module_skin_thumbnail_has_real_alt_and_lazy(): void
    {
        // Pre-fix: `<img class="img_img img-fluid" alt="image" ...>` —
        // placeholder alt + no lazy + no decoding hint. Cycle-90 swaps
        // alt to the post title (or '' fallback for blank-title posts)
        // and adds the standard lazy/decoding-async pair. Strip Blade
        // {{-- comments first because the audit-trail comment mentions
        // the literal pre-fix `alt="image"` shape.
        $src = $this->read(self::POST_CMSKIN);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        $this->assertStringNotContainsString(
            'alt="image"',
            $stripped,
            'content-module-skin: placeholder `alt="image"` must be replaced with the post title'
        );

        $this->assertMatchesRegularExpression(
            '/<img\\s[^>]*loading="lazy"[^>]*decoding="async"[^>]*alt="\\{\\{ \\$item\\[\'title\'\\] \\?\\? \'\' \\}\\}"/',
            $src,
            'content-module-skin: thumbnail must carry loading="lazy" + decoding="async" + alt={{ $item[title] ?? "" }}'
        );
    }
}
