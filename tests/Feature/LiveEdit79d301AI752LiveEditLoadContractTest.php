<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-79d301 — Live Edit not loading: @push('scripts') inside JS comment.
 *
 * Root cause: live-edit.blade.php line 155 contained the literal text
 * "@push('scripts')" inside a JavaScript // comment. Blade processes
 * @push directives even inside // JS comments (Blade is not JS-aware).
 * The spurious @push opened a second, nested push block that the single
 * @endpush on line 190 closed — leaving the FIRST push block (opened on
 * line 4) permanently open. Blade then treated the rest of the file
 * (including all HTML layout) as push content, dumping the entire page
 * HTML inside a <script> block. The apijs_settings script tag ended up
 * embedded as raw text inside the script block's textContent rather
 * than as a real DOM element, so:
 *   - document.querySelector('script[data-public-url]') returned null
 *   - baseURL = '' in api_settings.js
 *   - mw.settings was never populated
 *   - Vue toolbar never mounted ("Loading...")
 *
 * Fix: changed the JS comment to use word-form — "the scripts-push block"
 * instead of the literal "@push('scripts')" directive token. This is the
 * same parser-meaningful-character family as the Blade @if / @else in CSS
 * comment pattern (LESSONS.md 2026-04-XX).
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class LiveEdit79d301AI752LiveEditLoadContractTest extends TestCase
{
    private const LIVE_EDIT_LAYOUT = 'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php';

    private string $raw;
    private string $stripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->raw = (string) file_get_contents(base_path(self::LIVE_EDIT_LAYOUT));

        // Strip Blade comments — {{-- ... --}} — before checking directive counts.
        // Blade processes directives before rendering comments, but the Blade
        // comment syntax {{-- ... --}} prevents the enclosed text from being
        // treated as a directive. So line 29's @push inside a Blade comment is safe;
        // stripping it first prevents false-positive counts.
        $this->stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $this->raw) ?? $this->raw;
    }

    // ─── @push / @endpush balance ────────────────────────────────────────────

    #[Test]
    public function push_scripts_directive_appears_exactly_once_in_executable_source(): void
    {
        $count = preg_match_all("~@push\s*\(\s*'scripts'\s*\)~", $this->stripped);
        $this->assertSame(
            1,
            $count,
            'Exactly one @push(\'scripts\') must appear in the executable source. ' .
            'Any occurrence inside a JS // comment is a Blade bug — rephrase to word-form.'
        );
    }

    #[Test]
    public function endpush_appears_exactly_once(): void
    {
        $count = preg_match_all('~@endpush~', $this->stripped);
        $this->assertSame(
            1,
            $count,
            'Exactly one @endpush must appear to close the single @push(\'scripts\') block.'
        );
    }

    #[Test]
    public function no_push_directive_inside_js_comment(): void
    {
        // Extract all JS // comment lines and assert none contain @push
        preg_match_all('~//[^\n]*~', $this->stripped, $jsComments);
        $offending = array_filter(
            $jsComments[0],
            fn (string $line) => str_contains($line, '@push') || str_contains($line, '@endpush')
        );
        $this->assertEmpty(
            $offending,
            'No @push or @endpush directive token must appear inside a JS // comment. ' .
            'Offending lines: ' . implode(' | ', $offending)
        );
    }

    #[Test]
    public function no_blade_directives_in_js_comments(): void
    {
        // Broader guard: no known problematic Blade directives in JS // comments
        preg_match_all('~//[^\n]*~', $this->stripped, $jsComments);
        $bladeDirectivePattern = '~@(push|endpush|stack|section|endsection|if|else|elseif|endif|foreach|endforeach)[\s(]~';
        $offending = array_filter(
            $jsComments[0],
            fn (string $line) => preg_match($bladeDirectivePattern, $line) === 1
        );
        $this->assertEmpty(
            $offending,
            'Blade directives must not appear inside JS // comments — Blade processes them. ' .
            'Rephrase to word-form. Offending: ' . implode(' | ', $offending)
        );
    }

    // ─── Fix marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-6eb365',
            $this->raw,
            'task-2026-05-22-6eb365 marker must remain in live-edit.blade.php.'
        );
    }

    #[Test]
    public function fix_comment_uses_word_form_not_directive(): void
    {
        $this->assertStringContainsString(
            'scripts-push block',
            $this->raw,
            'The comment that previously contained "@push(\'scripts\')" must now say ' .
            '"scripts-push block" (or similar word-form) to avoid Blade directive parsing.'
        );
    }

    // ─── mw.settings injection sanity ────────────────────────────────────────

    #[Test]
    public function apijs_settings_script_tag_shape_preserved_in_meta_tags_renderer(): void
    {
        // The #mw-api-settings script is emitted by MwSettingsJsScriptTag via the
        // AdminFilamentMetaTagsRenderer + HEAD_START render hook. Verify the class exists
        // and produces a script tag with data-public-url.
        $this->assertTrue(
            class_exists(\MicroweberPackages\MetaTags\Entities\MwSettingsJsScriptTag::class),
            'MwSettingsJsScriptTag must exist.'
        );

        $src = (string) file_get_contents(
            base_path('src/MicroweberPackages/MetaTags/Entities/MwSettingsJsScriptTag.php')
        );

        $this->assertStringContainsString(
            'data-public-url',
            $src,
            'MwSettingsJsScriptTag must emit data-public-url on the script tag.'
        );
        $this->assertStringContainsString(
            'get_apijs_settings_url',
            $src,
            'MwSettingsJsScriptTag must use get_apijs_settings_url() for the src.'
        );
    }
}
