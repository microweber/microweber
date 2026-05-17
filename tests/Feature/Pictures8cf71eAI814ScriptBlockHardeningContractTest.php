<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-8cf71e / AI-814 — Pictures module 3-defect fix
 * bundle across the 6 affected gallery templates.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-814
 * Priority: Medium (defense-in-depth XSS + perf + scope hygiene)
 *
 * Three defects per template:
 *   D1. JS-context XSS — `description: "{{ $item['title'] }}"`
 *       relied on Blade {{}} which HTML-escapes but does NOT
 *       JS-escape. Title containing JS-meaningful chars could
 *       break the string context. (Exploit posture varies by
 *       browser; defense-in-depth fix regardless.)
 *   D2. Global scope pollution — `gallery<rand> = [...]` had no
 *       var/let/const; lives on window. Multiple Pictures
 *       modules on a page = multiple globals.
 *   D3. JSON duplication — `data-mw-gallery="@php base64_encode(
 *       json_encode(array_map(...))) @endphp"` recomputed the
 *       gallery JSON inside every @foreach iteration. 50 pictures
 *       = 50x redundant encode work.
 *
 * Post-fix (designer recipe applied uniformly):
 *   Fix A: top @php block precomputes
 *     $mwAi814GalleryJson = base64_encode(json_encode(array_map(...)))
 *     ONCE per render.
 *   Fix B: every data-mw-gallery="@php echo base64_encode(...);
 *     @endphp" attribute becomes data-mw-gallery="
 *     {{ $mwAi814GalleryJson }}" — base64 chars are HTML-safe so
 *     {{}} doesn't alter content.
 *   Fix C: <script>gallery<rand> = [...]</script> rewritten as
 *     IIFE-wrapped window.gallery<rand> = json_encode(...,
 *     JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS).
 *     Explicit window.gallery<rand> = ... assignment preserved
 *     so external readers accessing by name still find it.
 *
 * Affected templates (6, all with bundled defects):
 *   default / masonry / simple / button_gallery /
 *   skin-3-beauty / skin-12.
 *
 * Selector-self-match guard family (20+ session-recurrences):
 * docblock + per-template AI-814 inline comments legitimately
 * mention the pre-fix shape; absence assertions pre-strip
 * Blade {{-- ... --}} comments before scanning.
 */
class Pictures8cf71eAI814ScriptBlockHardeningContractTest extends TestCase
{
    /**
     * 6 affected templates + per-file expected counts:
     *   [path, expected_data_mw_gallery_instances]
     */
    public static function affectedTemplatesProvider(): array
    {
        return [
            'default'         => ['Modules/Pictures/resources/views/templates/default.blade.php',         1],
            'masonry'         => ['Modules/Pictures/resources/views/templates/masonry.blade.php',         1],
            'simple'          => ['Modules/Pictures/resources/views/templates/simple.blade.php',          1],
            'button_gallery'  => ['Modules/Pictures/resources/views/templates/button_gallery.blade.php',  1],
            'skin-3-beauty'   => ['Modules/Pictures/resources/views/templates/skin-3-beauty.blade.php',   2],
            'skin-12'         => ['Modules/Pictures/resources/views/templates/skin-12.blade.php',         2],
        ];
    }

    private function executableTemplate(string $relativePath): string
    {
        $src = (string) file_get_contents(base_path($relativePath));
        return preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $src);
    }

    private function rawTemplate(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  Fix A — top @php block carries $mwAi814GalleryJson precompute
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function top_php_block_precomputes_gallery_json(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The $mwAi814GalleryJson variable MUST be assigned via
        // base64_encode(json_encode(array_map(...))) inside a @php
        // block somewhere above the @foreach loop.
        $this->assertMatchesRegularExpression(
            '/\$mwAi814GalleryJson\s*=\s*base64_encode\(json_encode\(array_map\(/',
            $exec,
            "AI-814 Fix A: {$relativePath} MUST precompute \$mwAi814GalleryJson via base64_encode(json_encode(array_map(...))) outside the @foreach loop."
        );

        // The precompute MUST live in a @php ... @endphp block.
        $this->assertMatchesRegularExpression(
            '/@php\s+\$rand\s*=\s*uniqid\(\)\s*;[\s\S]+?\$mwAi814GalleryJson\s*=[\s\S]+?@endphp/',
            $exec,
            "AI-814 Fix A: {$relativePath} \$mwAi814GalleryJson precompute MUST live in the same top @php block as \$rand (computes once per render)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  Fix B — data-mw-gallery= attribute uses the precomputed var
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function data_mw_gallery_uses_precomputed_var(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Positive: data-mw-gallery="{{ $mwAi814GalleryJson }}" appears EXACTLY
        // $expected times (the per-file count from the data provider).
        $count = preg_match_all(
            '/data-mw-gallery="\{\{\s*\$mwAi814GalleryJson\s*\}\}"/',
            $exec
        );
        $this->assertSame(
            $expected,
            $count,
            "AI-814 Fix B: {$relativePath} MUST have exactly {$expected} data-mw-gallery=\"{{ \$mwAi814GalleryJson }}\" attribute occurrence(s)."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_inline_base64_encode_remains_in_data_mw_gallery(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Negative: the pre-fix inline shape must be gone (post-
        // comment-strip so docblock prose mentioning the legacy
        // pattern doesn't false-fail).
        $this->assertDoesNotMatchRegularExpression(
            '/data-mw-gallery="@php echo base64_encode/',
            $exec,
            "AI-814 Fix B: {$relativePath} MUST NOT carry inline `@php echo base64_encode(...)` in any data-mw-gallery= attribute (post-comment-strip)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  Fix C — script block uses IIFE + json_encode HEX flags
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function script_block_uses_iife_wrapper(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Positive: IIFE pattern `(function () { ... })();` present.
        $this->assertMatchesRegularExpression(
            '/<script>\s*\n\s*\(function\s*\(\)\s*\{[\s\S]+?\}\)\(\);\s*\n\s*<\/script>/',
            $exec,
            "AI-814 Fix C: {$relativePath} MUST wrap gallery<rand> assignment in an IIFE — `(function () { ... })();`."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function script_block_uses_window_gallery_explicit_assignment(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Explicit window.gallery<rand> assignment (not implicit
        // global). The {{ $rand }} interpolation completes the
        // unique-per-module global name.
        $this->assertMatchesRegularExpression(
            '/window\.gallery\{\{\s*\$rand\s*\}\}\s*=/',
            $exec,
            "AI-814 Fix C: {$relativePath} MUST use explicit `window.gallery{{ \$rand }} = ...` assignment (not implicit `gallery<rand> = ...` global)."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function script_block_uses_json_encode_with_hex_flags(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // The JSON_HEX_QUOT + JSON_HEX_TAG + JSON_HEX_AMP +
        // JSON_HEX_APOS flag combination is the canonical JS-context
        // escape recipe per designer spec. Match the flag names
        // as bitwise-OR expression.
        $this->assertMatchesRegularExpression(
            '/json_encode\([\s\S]+?,\s*JSON_HEX_QUOT\s*\|\s*JSON_HEX_TAG\s*\|\s*JSON_HEX_AMP\s*\|\s*JSON_HEX_APOS\s*\)/',
            $exec,
            "AI-814 Fix C: {$relativePath} MUST pass `JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS` to json_encode() for full JS-context escape."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_legacy_implicit_global_gallery_assignment_remains(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Negative: the pre-fix shape `gallery<rand> = [` (implicit
        // global, no var/let/const, no window. prefix) must be
        // gone in executable source.
        $this->assertDoesNotMatchRegularExpression(
            '/(?<!window\.)gallery\{\{\s*\$rand\s*\}\}\s*=\s*\[/',
            $exec,
            "AI-814 Fix C: {$relativePath} MUST NOT carry implicit `gallery<rand> = [...]` global assignment (post-comment-strip)."
        );
    }

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function no_legacy_inline_description_blade_interp_remains(string $relativePath, int $expected): void
    {
        $exec = $this->executableTemplate($relativePath);
        // Negative: the pre-fix `description: "{{ $item['title']
        // }}"` inline-in-JS-string shape must be gone. The post-
        // fix uses json_encode which never produces a bare Blade
        // {{}} interpolation inside a JS string.
        $this->assertDoesNotMatchRegularExpression(
            '/description:\s*"\{\{[\s\S]+?\}\}"/',
            $exec,
            "AI-814 Fix C: {$relativePath} MUST NOT carry `description: \"{{ ... }}\"` inline Blade interpolation in a JS-string context (the AI-814 D1 XSS surface)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  recon-surface guard — no NEW Pictures template with these defects
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_other_pictures_template_carries_any_legacy_pattern(): void
    {
        $templatesDir = base_path('Modules/Pictures/resources/views/templates');
        $allBlades = glob($templatesDir . '/*.blade.php');
        $covered = array_map(
            fn ($p) => base_path($p[0]),
            array_values(static::affectedTemplatesProvider())
        );
        $uncovered = array_diff($allBlades, $covered);

        // 3 legacy patterns to scan for in uncovered templates.
        $legacyPatterns = [
            'data-mw-gallery-inline-base64' => '/data-mw-gallery="@php echo base64_encode/',
            'implicit-global-gallery'       => '/(?<!window\.)gallery\{\{\s*\$rand\s*\}\}\s*=\s*\[/',
            'description-blade-in-jsstring' => '/description:\s*"\{\{[\s\S]+?\}\}"/',
        ];

        foreach ($uncovered as $file) {
            $raw = (string) file_get_contents($file);
            $exec = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $raw);
            foreach ($legacyPatterns as $label => $pattern) {
                if (preg_match($pattern, $exec)) {
                    $this->fail(sprintf(
                        'AI-814: uncovered Pictures template %s carries the legacy %s pattern. ' .
                        'Add to Pictures8cf71eAI814ScriptBlockHardeningContractTest::affectedTemplatesProvider() AND apply the AI-814 fix bundle.',
                        basename($file),
                        $label,
                    ));
                }
            }
        }
        $this->addToAssertionCount(1);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  task-id markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('affectedTemplatesProvider')]
    public function task_id_marker_present(string $relativePath, int $expected): void
    {
        $raw = $this->rawTemplate($relativePath);
        $this->assertStringContainsString(
            'task-2026-05-17-8cf71e',
            $raw,
            "AI-814: {$relativePath} MUST carry the task-id marker for audit grep."
        );
        $this->assertStringContainsString(
            'AI-814',
            $raw,
            "AI-814: {$relativePath} MUST cite the ticket ID for audit grep."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F  count sanity — 6 templates, 8 data-mw-gallery instances total
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function data_provider_contains_6_templates_and_8_total_instances(): void
    {
        $entries = static::affectedTemplatesProvider();
        $this->assertCount(6, $entries, 'AI-814: data provider MUST contain exactly 6 templates.');
        $total = array_sum(array_map(fn ($e) => $e[1], array_values($entries)));
        $this->assertSame(
            8,
            $total,
            'AI-814: total data-mw-gallery instances across all 6 templates MUST equal 8 (4 single + 2 double).'
        );
    }
}
